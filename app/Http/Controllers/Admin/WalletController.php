<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Logged;
use Illuminate\Http\Request;
use App\Traits\LogsAdminActivity;
use App\Traits\ChecksPendingDeposits;

class WalletController extends Controller
{
    use LogsAdminActivity;
    use ChecksPendingDeposits;

    /**
     * Display all wallet transactions with filters and pagination
     */
    public function index(Request $request)
    {
        // CHECK PENDING DEPOSITS (batch of 15 on wallet page)
        $this->checkAllPendingDeposits(15, 'Admin wallet page');

        $query = Wallet::with('user');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->has('amount_min') && $request->amount_min) {
            $query->where('amount', '>=', $request->amount_min);
        }
        if ($request->has('amount_max') && $request->amount_max) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Pagination
        $transactions = $query->latest()->paginate(20)->withQueryString();

        // Statistics
        $totalTransactions = Wallet::count();
        $totalDeposits = Wallet::where('type', 'credit')->where('status', 'success')->count();
        $totalDebits = Wallet::where('type', 'debit')->count();
        $pendingDeposits = Wallet::where('type', 'credit')->where('status', 'pending')->count();
        $pendingAmount = Wallet::where('type', 'credit')->where('status', 'pending')->sum('amount');
        $completedAmount = Wallet::where('type', 'credit')->where('status', 'success')->sum('amount');

        // Log the view
        $this->logActivity(
            'viewed',
            auth('admin')->user()->name . ' viewed wallet transactions list',
            'Wallet',
            null
        );

        return view('admin.wallet.index', compact(
            'transactions',
            'totalTransactions',
            'totalDeposits',
            'totalDebits',
            'pendingDeposits',
            'pendingAmount',
            'completedAmount'
        ));
    }

    /**
     * Show single transaction details
     */
    public function show($id)
    {
        $transaction = Wallet::with('user')->findOrFail($id);
        
        // Get logs related to this transaction
        $logs = Logged::where('user_id', $transaction->user_id)
            ->where(function($q) use ($transaction) {
                $q->where('reference', $transaction->reference)
                  ->orWhere('description', 'like', "%{$transaction->reference}%");
            })
            ->latest()
            ->paginate(10);

        // Get customer's current balance
        $latestWallet = Wallet::where('user_id', $transaction->user_id)
            ->orderBy('created_at', 'desc')
            ->first();
        $customerBalance = $latestWallet ? $latestWallet->balance_after : 0;

        // Get customer's transaction history count
        $totalTransactions = Wallet::where('user_id', $transaction->user_id)->count();

        // Log the view
        $this->logViewed(
            'Wallet',
            $transaction->id,
            auth('admin')->user()->name . ' viewed transaction ' . $transaction->reference
        );

        return view('admin.wallet.show', compact('transaction', 'logs', 'customerBalance', 'totalTransactions'));
    }

    /**
     * Approve pending deposit (Super Admin & Accountant only)
     */
    public function approve(Request $request, $id)
    {
        if (!auth('admin')->user()->canEditTransactions()) {
            abort(403, 'Unauthorized action.');
        }
        $transaction = Wallet::with('user')->findOrFail($id);
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be approved');
        }

        $balanceAfter = $transaction->user->balance + $transaction->amount;

        $transaction->update([
            'status' => 'success',
            'balance_after' => $balanceAfter,
        ]);

        // Increment the user's actual balance
        $transaction->user->increment('balance', $transaction->amount);

        // Log the approval in admin logs
        $this->logActivity(
            'approved_transaction',
            auth('admin')->user()->name . ' approved transaction ' . $transaction->reference . ' - ₦' . number_format($transaction->amount, 2) . ' for ' . $transaction->user->name,
            'Wallet',
            $transaction->id,
            [
                'status' => [
                    'old' => 'pending',
                    'new' => 'success'
                ]
            ]
        );
        // Log in customer's activity log
        Logged::create([
            'user_id' => $transaction->user_id,
            'reference' => $transaction->reference,
            'type' => 'wallet',
            'method' => 'manual_approval',
            'amount' => $transaction->amount,
            'status' => 'success',
            'description' => "Transaction {$transaction->reference} approved by admin: " . auth('admin')->user()->name,
            'ip_address' => $request->ip(),
        ]);
        return back()->with('success', 'Transaction approved successfully');
    }

    /**
     * Reject pending deposit (Super Admin & Accountant only)
     */
    public function reject(Request $request, $id)
    {
        if (!auth('admin')->user()->canEditTransactions()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction = Wallet::with('user')->findOrFail($id);

        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Only pending transactions can be rejected');
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $reason = $request->reason ?? 'No reason provided';

        if ($transaction->type === 'credit') {
            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'description' => $transaction->description . ' (Rejected by admin: ' . $reason . ')'
            ]);

        } else {
            $transaction->update(['status' => 'failed']);
        }

        // Log the rejection in admin logs
        $this->logActivity(
            'rejected_transaction',
            auth('admin')->user()->name . ' rejected transaction ' . $transaction->reference . ' - ₦' . number_format($transaction->amount, 2) . ' for ' . $transaction->user->name . ' - Reason: ' . $reason,
            'Wallet',
            $transaction->id,
            [
                'status' => [
                    'old' => 'pending',
                    'new' => 'failed'
                ],
                'reason' => $reason
            ]
        );

        // Log in customer's activity log
        Logged::create([
            'user_id' => $transaction->user_id,
            'reference' => $transaction->reference,
            'type' => 'wallet',
            'method' => 'manual_rejection',
            'amount' => $transaction->amount,
            'status' => 'failed',
            'description' => "Transaction {$transaction->reference} rejected by admin: " . auth('admin')->user()->name . " - Reason: " . $reason,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Transaction rejected successfully. Balance has been reversed.');
    }

    /**
     * Delete transaction (Super Admin & Accountant only)
     */
    public function destroy($id)
    {
        if (!auth('admin')->user()->canDeleteTransactions()) {
            abort(403, 'Unauthorized action.');
        }

        $transaction = Wallet::with('user')->findOrFail($id);

        if ($transaction->status === 'completed') {
            return back()->with('error', 'Cannot delete completed transactions. This could affect customer balances.');
        }

        $transactionData = [
            'reference' => $transaction->reference,
            'amount' => $transaction->amount,
            'user_name' => $transaction->user->name,
        ];

        // Log the deletion
        $this->logDeleted(
            'Wallet',
            $transaction->id,
            auth('admin')->user()->name . ' deleted transaction ' . $transaction->reference . ' for ' . $transaction->user->name
        );

        $transaction->delete();

        return redirect()->route('admin.wallet.index')
            ->with('success', 'Transaction deleted successfully');
    }
}