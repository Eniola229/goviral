<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferralTransaction;
use App\Models\Referral;
use App\Models\User;
use App\Services\KorapayService;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferralWithdrawalController extends Controller
{
    use LogsAdminActivity;

    protected $korapayService;

    public function __construct(KorapayService $korapayService)
    {
        $this->korapayService = $korapayService;
    }

    /**
     * Display list of pending withdrawals
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');
        
        $query = ReferralTransaction::with(['referral.user'])
            ->where('type', 'debit')
            ->whereIn('status', ['pending', 'approved', 'failed', 'success']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $withdrawals = $query->latest()->paginate(20)->appends(['status' => $status]);

        $stats = [
            'pending' => ReferralTransaction::where('type', 'debit')->where('status', 'pending')->count(),
            'approved' => ReferralTransaction::where('type', 'debit')->where('status', 'approved')->count(),
            'success' => ReferralTransaction::where('type', 'debit')->where('status', 'success')->count(),
            'failed' => ReferralTransaction::where('type', 'debit')->where('status', 'failed')->count(),
        ];

        return view('admin.referral.withdrawals.index', compact('withdrawals', 'stats', 'status'));
    }

    /**
     * Show single withdrawal details
     */
    public function show($id)
    {
        $withdrawal = ReferralTransaction::with(['referral.user'])->findOrFail($id);
        
        return view('admin.referral.withdrawals.show', compact('withdrawal'));
    }

    /**
     * Approve withdrawal to wallet
     */
    public function approveWallet($id)
    {
        // Check permission - only super admin and accountant
        if (!Auth::guard('admin')->user()->isSuperAdmin() && !Auth::guard('admin')->user()->isAccountant()) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'You do not have permission to approve withdrawals'
            ]);
        }
        
        try {
            DB::beginTransaction();
            
            $withdrawal = ReferralTransaction::with(['referral.user'])->findOrFail($id);
            
            if ($withdrawal->status !== 'pending') {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This withdrawal has already been processed'
                ]);
            }
            
            if (!str_contains($withdrawal->description, 'wallet')) {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This is not a wallet withdrawal'
                ]);
            }
            
            $user = $withdrawal->referral->user;
            $oldWalletBalance = $user->balance;
            
            // Use WalletService to credit user's wallet and create transaction record
            WalletService::referralWithdrawalToWallet(
                $user, 
                $withdrawal->amount, 
                $withdrawal->reference,
                "Referral Withdrawal to Wallet - Ref: {$withdrawal->reference}"
            );
            
            // Refresh user to get updated balance
            $user->refresh();
            
            // Update withdrawal status
            $withdrawal->status = 'success';
            $withdrawal->save();
            
            // Log admin action using trait
            $this->logActivity(
                'referral_withdrawal_approved',
                "Approved wallet withdrawal for user {$user->name} - ₦" . number_format($withdrawal->amount, 2),
                ReferralTransaction::class,
                $withdrawal->id,
                [
                    'withdrawal_id' => $withdrawal->id,
                    'reference' => $withdrawal->reference,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'amount' => $withdrawal->amount,
                    'type' => 'wallet',
                    'old_status' => 'pending',
                    'new_status' => 'success',
                    'old_wallet_balance' => $oldWalletBalance,
                    'new_wallet_balance' => $user->balance,
                ]
            );
            
            DB::commit();
            
            return back()->with('alert', [
                'type' => 'success',
                'message' => 'Withdrawal approved successfully! ₦' . number_format($withdrawal->amount, 2) . ' has been added to user wallet.'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Referral wallet withdrawal approval failed: ' . $e->getMessage());
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Failed to approve withdrawal'
            ]);
        }
    }
    /**
     * Approve and process bank withdrawal
     */
    public function approveBank($id)
    {
        // Check permission - only super admin and accountant
        if (!Auth::guard('admin')->user()->isSuperAdmin() && !Auth::guard('admin')->user()->isAccountant()) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'You do not have permission to approve withdrawals'
            ]);
        }

        try {
            DB::beginTransaction();

            $withdrawal = ReferralTransaction::with(['referral.user'])->findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This withdrawal has already been processed'
                ]);
            }

            if (!str_contains($withdrawal->description, 'bank')) {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This is not a bank withdrawal'
                ]);
            }

            $metadata = $withdrawal->metadata ?? [];
            
            if (!isset($metadata['bank_name'], $metadata['account_number'], $metadata['account_name'])) {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Bank details are missing'
                ]);
            }

            $user = $withdrawal->referral->user;

            // Convert amount to kobo for Korapay
            $amountInKobo = $withdrawal->amount * 100;

            // Initiate payout via Korapay
            $payoutResult = $this->korapayService->initiatePayout(
                $amountInKobo,
                $metadata['bank_name'],
                $metadata['account_number'],
                $metadata['account_name'],
                $withdrawal->reference,
                'Referral withdrawal - ' . $user->name
            );

            if ($payoutResult['success']) {
                // Update withdrawal status to approved (awaiting Korapay confirmation)
                $withdrawal->status = 'approved';
                $withdrawal->metadata = array_merge($metadata, [
                    'transfer_id' => $payoutResult['transfer_id'] ?? null,
                    'approved_at' => now()->toDateTimeString(),
                    'approved_by' => Auth::guard('admin')->user()->name,
                ]);
                $withdrawal->save();

                // Log admin action using trait
                $this->logActivity(
                    'referral_withdrawal_approved',
                    "Approved bank withdrawal for user {$user->name} - ₦" . number_format($withdrawal->amount, 2),
                    ReferralTransaction::class,
                    $withdrawal->id,
                    [
                        'withdrawal_id' => $withdrawal->id,
                        'reference' => $withdrawal->reference,
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'amount' => $withdrawal->amount,
                        'type' => 'bank',
                        'bank_name' => $metadata['bank_name'],
                        'account_number' => $metadata['account_number'],
                        'account_name' => $metadata['account_name'],
                        'old_status' => 'pending',
                        'new_status' => 'approved',
                        'transfer_id' => $payoutResult['transfer_id'] ?? null,
                    ]
                );

                DB::commit();

                return back()->with('alert', [
                    'type' => 'success',
                    'message' => 'Bank withdrawal initiated successfully! Transfer ID: ' . ($payoutResult['transfer_id'] ?? 'N/A')
                ]);

            } else {
                DB::rollBack();

                // Log failed attempt using trait
                $this->logActivity(
                    'referral_withdrawal_failed',
                    "Failed to process bank withdrawal for user {$user->name} - ₦" . number_format($withdrawal->amount, 2),
                    ReferralTransaction::class,
                    $withdrawal->id,
                    [
                        'withdrawal_id' => $withdrawal->id,
                        'reference' => $withdrawal->reference,
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'error_message' => $payoutResult['message'] ?? 'Unknown error',
                    ]
                );

                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Bank transfer failed: ' . ($payoutResult['message'] ?? 'Unknown error')
                ]);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Referral bank withdrawal approval failed: ' . $e->getMessage());

            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Failed to approve withdrawal: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reject withdrawal
     */
    public function reject(Request $request, $id)
    {
        // Check permission - only super admin and accountant
        if (!Auth::guard('admin')->user()->isSuperAdmin() && !Auth::guard('admin')->user()->isAccountant()) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'You do not have permission to reject withdrawals'
            ]);
        }

        $request->validate([
            'reason' => 'required|string|min:10',
        ]);

        try {
            DB::beginTransaction();

            $withdrawal = ReferralTransaction::with(['referral.user'])->findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This withdrawal has already been processed'
                ]);
            }

            $user = $withdrawal->referral->user;
            $amount = $withdrawal->amount;

            // Refund the amount back to referral balance
            $withdrawal->referral->referral_balance += $amount;
            $withdrawal->referral->save();

            // Update withdrawal status
            $withdrawal->status = 'failed';
            $withdrawal->metadata = array_merge($withdrawal->metadata ?? [], [
                'rejection_reason' => $request->reason,
                'rejected_at' => now()->toDateTimeString(),
                'rejected_by' => Auth::guard('admin')->user()->name,
            ]);
            $withdrawal->save();

            // Log admin action using trait
            $this->logActivity(
                'referral_withdrawal_rejected',
                "Rejected withdrawal for user {$user->name} - ₦" . number_format($amount, 2),
                ReferralTransaction::class,
                $withdrawal->id,
                [
                    'withdrawal_id' => $withdrawal->id,
                    'reference' => $withdrawal->reference,
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'amount' => $amount,
                    'old_status' => 'pending',
                    'new_status' => 'failed',
                    'rejection_reason' => $request->reason,
                    'refunded_to_balance' => true,
                ]
            );

            DB::commit();

            return back()->with('alert', [
                'type' => 'success',
                'message' => 'Withdrawal rejected and ₦' . number_format($amount, 2) . ' refunded to user\'s referral balance.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Referral withdrawal rejection failed: ' . $e->getMessage());

            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Failed to reject withdrawal: ' . $e->getMessage()
            ]);
        }
    }
}