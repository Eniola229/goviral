<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wallet;
use App\Models\Logged;
use Illuminate\Http\Request;
use App\Traits\LogsAdminActivity;

class WalletController extends Controller
{
    use LogsAdminActivity;

    /**
     * Display all wallet transactions (Coming in Step 8)
     */
    public function index(Request $request)
    {
        return 'Wallet Transactions Index - Coming in Step 8';
    }

    /**
     * Show single transaction (Coming in Step 8)
     */
    public function show($id)
    {
        return 'Transaction Details - Coming in Step 8';
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

        $transaction->update(['status' => 'completed']);

        // Log the approval in admin logs
        $this->logActivity(
            'approved_transaction',
            auth('admin')->user()->name . ' approved transaction ' . $transaction->reference . ' - ₦' . number_format($transaction->amount, 2) . ' for ' . $transaction->user->name,
            'Wallet',
            $transaction->id,
            [
                'status' => [
                    'old' => 'pending',
                    'new' => 'completed'
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
            'status' => 'completed',
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

        // If it was a credit (deposit), we need to reverse the balance
        if ($transaction->type === 'credit') {
            // Update transaction status
            $transaction->update([
                'status' => 'failed',
                'description' => $transaction->description . ' (Rejected: ' . $reason . ')'
            ]);

            // Reverse the balance
            Wallet::create([
                'user_id' => $transaction->user_id,
                'balance_before' => $transaction->balance_after,
                'amount' => $transaction->amount,
                'balance_after' => $transaction->balance_before,
                'type' => 'debit',
                'description' => "Reversal of rejected transaction: " . $transaction->reference,
                'reference' => 'REV-' . $transaction->reference,
                'payment_method' => 'reversal',
                'status' => 'completed',
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

        return back()->with('success', 'Transaction rejected successfully');
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