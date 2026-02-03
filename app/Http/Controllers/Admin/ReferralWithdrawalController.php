<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ReferralWalletTransaction;
use App\Models\Referral;
use App\Models\User;
use App\Models\AdminLogged;
use App\Models\Logged;
use App\Services\KorapayService;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\WalletService;

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
        $search = $request->get('search', '');
        $filterMethod = $request->get('method', '');
        $amountMin = $request->get('amount_min', '');
        $amountMax = $request->get('amount_max', '');
        $dateFrom = $request->get('date_from', '');
        $dateTo = $request->get('date_to', '');

        $query = ReferralWalletTransaction::with(['referral.user'])
            ->where('type', 'debit')
            ->whereIn('status', ['pending', 'approved', 'declined', 'success']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Search by name, email, reference, account name, or bank name
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('reference', 'LIKE', "%{$search}%")
                  ->orWhere('account_name', 'LIKE', "%{$search}%")
                  ->orWhere('bank_name', 'LIKE', "%{$search}%")
                  ->orWhereHas('referral.user', function ($userQuery) use ($search) {
                      $userQuery->where('name', 'LIKE', "%{$search}%")
                                ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by withdrawal method (bank or wallet)
        if (!empty($filterMethod)) {
            $query->where('withdrawal_method', $filterMethod);
        }

        // Filter by amount range
        if (!empty($amountMin)) {
            $query->where('amount', '>=', $amountMin);
        }
        if (!empty($amountMax)) {
            $query->where('amount', '<=', $amountMax);
        }

        // Filter by date range
        if (!empty($dateFrom)) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }
        if (!empty($dateTo)) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $withdrawals = $query->latest()->paginate(20)->appends([
            'status'      => $status,
            'search'      => $search,
            'method'      => $filterMethod,
            'amount_min'  => $amountMin,
            'amount_max'  => $amountMax,
            'date_from'   => $dateFrom,
            'date_to'     => $dateTo,
        ]);

        $stats = [
            'pending'  => ReferralWalletTransaction::where('type', 'debit')->where('status', 'pending')->count(),
            'approved' => ReferralWalletTransaction::where('type', 'debit')->where('status', 'approved')->count(),
            'success'  => ReferralWalletTransaction::where('type', 'debit')->where('status', 'success')->count(),
            'failed'   => ReferralWalletTransaction::where('type', 'debit')->where('status', 'declined')->count(),
        ];

        return view('admin.referral.withdrawals.index', compact(
            'withdrawals', 'stats', 'status',
            'search', 'filterMethod', 'amountMin', 'amountMax', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Show single withdrawal details
     */
    public function show($id)
    {
        $withdrawal = ReferralWalletTransaction::with(['referral.user'])->findOrFail($id);
        
        // Get current referral balance
        $referralBalance = $withdrawal->referral->balance;
        
        // Get total transactions count for this referral
        $totalTransactions = ReferralWalletTransaction::where('referral_id', $withdrawal->referral_id)->count();
        
        // Get admin logs related to this withdrawal
        $adminLogs = AdminLogged::where('target_type', ReferralWalletTransaction::class)
            ->where('target_id', $withdrawal->id)
            ->with('admin')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get user transaction logs related to this withdrawal (by reference)
        $userLogs = Logged::where('reference', $withdrawal->reference)
            ->where('user_id', $withdrawal->referral->user_id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Merge and sort all logs by created_at
        $allLogs = $adminLogs->concat($userLogs)->sortByDesc('created_at');
        
        // Paginate the merged collection
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $logs = new \Illuminate\Pagination\LengthAwarePaginator(
            $allLogs->forPage($currentPage, $perPage),
            $allLogs->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('admin.referral.withdrawals.show', compact('withdrawal', 'referralBalance', 'totalTransactions', 'logs'));
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
            
            $withdrawal = ReferralWalletTransaction::with(['referral.user'])->findOrFail($id);
            
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
                ReferralWalletTransaction::class,
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

            $withdrawal = ReferralWalletTransaction::with(['referral.user'])->findOrFail($id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This withdrawal has already been processed'
                ]);
            }

            if ($withdrawal->withdrawal_method !== 'bank') {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'This is not a bank withdrawal'
                ]);
            }

            if (!$withdrawal->bank_name || !$withdrawal->account_number || !$withdrawal->account_name) {
                return back()->with('alert', [
                    'type' => 'error',
                    'message' => 'Bank details are missing'
                ]);
            }

            $user = $withdrawal->referral->user;

            // Initiate payout via Korapay
            // Pass amount directly (in Naira) + add the user's email
            $payoutResult = $this->korapayService->initiatePayout(
                $withdrawal->amount,                          // Naira
                $withdrawal->bank_name,
                $withdrawal->account_number,
                $withdrawal->account_name,
                $withdrawal->reference,
                'Referral withdrawal - ' . $user->name,
                $user->email                                  // customer email 
            );

            if ($payoutResult['success']) {
                // Update withdrawal status to approved (awaiting Korapay confirmation)
                $withdrawal->status = 'approved';
                $withdrawal->approved_at = now();
                $withdrawal->approved_by = Auth::guard('admin')->id();
                $withdrawal->admin_note = 'Transfer initiated. Transfer ID: ' . ($payoutResult['transfer_id'] ?? 'N/A');
                $withdrawal->save();

                // Log admin action using trait
                $this->logActivity(
                    'referral_withdrawal_approved',
                    "Approved bank withdrawal for user {$user->name} - ₦" . number_format($withdrawal->amount, 2),
                    ReferralWalletTransaction::class,
                    $withdrawal->id,
                    [
                        'withdrawal_id' => $withdrawal->id,
                        'reference' => $withdrawal->reference,
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'amount' => $withdrawal->amount,
                        'type' => 'bank',
                        'bank_name' => $withdrawal->bank_name,
                        'account_number' => $withdrawal->account_number,
                        'account_name' => $withdrawal->account_name,
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
                    ReferralWalletTransaction::class,
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
                'message' => 'Failed to approve withdrawal'
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

            $withdrawal = ReferralWalletTransaction::with(['referral.user'])->findOrFail($id);

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
            $withdrawal->status = 'declined';
            $withdrawal->approved_by = Auth::guard('admin')->id();
            $withdrawal->approved_at = now();
            $withdrawal->admin_note = 'Rejected: ' . $request->reason;
            $withdrawal->save();

            // Log admin action using trait
            $this->logActivity(
                'referral_withdrawal_rejected',
                "Rejected withdrawal for user {$user->name} - ₦" . number_format($amount, 2),
                ReferralWalletTransaction::class,
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
                'message' => 'Failed to reject withdrawal'
            ]);
        }
    }
}