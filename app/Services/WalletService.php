<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    /**
     * Create a pending wallet entry when payment is initiated
     * This runs BEFORE the user is redirected to Korapay
     */
    public static function initiateDeposit(User $user, $amount, $reference, $paymentMethod = 'Korapay', $description = 'Wallet Top Up')
    {
        Wallet::create([
            'user_id' => $user->id,
            'balance_before' => $user->balance,
            'amount' => $amount,
            'balance_after' => $user->balance, // Same as before until confirmed
            'type' => 'credit',
            'description' => $description,
            'reference' => $reference,
            'payment_method' => $paymentMethod,
            'status' => 'pending'
        ]);
    }

    // Deposit Money - Updates the pending record to success
    public static function deposit(User $user, $amount, $reference, $paymentMethod = 'Korapay', $description = 'Wallet Top Up')
    {
        return DB::transaction(function () use ($user, $amount, $reference, $paymentMethod, $description) {
            
            // Find the existing pending wallet entry
            $walletEntry = Wallet::where('reference', $reference)
                                 ->where('status', 'pending')
                                 ->first();

            // Capture current balance
            $balanceBefore = $user->balance;
            $balanceAfter = $balanceBefore + $amount;

            if ($walletEntry) {
                // Update the existing pending entry to success with real balance_after
                $walletEntry->update([
                    'balance_before' => $balanceBefore,
                    'balance_after' => $balanceAfter,
                    'status' => 'success',
                ]);
            } else {
                // Fallback: create new entry if pending one doesn't exist for some reason
                Wallet::create([
                    'user_id' => $user->id,
                    'balance_before' => $balanceBefore,
                    'amount' => $amount,
                    'balance_after' => $balanceAfter,
                    'type' => 'credit',
                    'description' => $description,
                    'reference' => $reference,
                    'payment_method' => $paymentMethod,
                    'status' => 'success'
                ]);
            }

            // Update User Balance
            $user->increment('balance', $amount);
            
            return true;
        });
    }

    // Mark a pending deposit as failed — balance_after stays equal to balance_before
    public static function markDepositFailed(string $reference)
    {
        $walletEntry = Wallet::where('reference', $reference)
                             ->where('status', 'pending')
                             ->first();

        if ($walletEntry) {
            $walletEntry->update([
                'balance_after' => $walletEntry->balance_before, // No change
                'status' => 'failed',
            ]);
        }
    }

    // Withdraw Money (Place Order)
    public static function withdraw(User $user, $amount, $reference, $paymentMethod = 'Internal Order', $description = 'New Order')
    {
        return DB::transaction(function () use ($user, $amount, $reference, $paymentMethod, $description) {
            if ($user->balance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            // Capture Balance Before
            $balanceBefore = $user->balance;

            // Calculate Balance After
            $balanceAfter = $balanceBefore - $amount;

            // Save the Ledger Entry
            Wallet::create([
                'user_id' => $user->id,
                'balance_before' => $balanceBefore,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'type' => 'debit',
                'description' => $description,
                'reference' => $reference,
                'payment_method' => $paymentMethod,
                'status' => 'success'
            ]);

            // 4. Update User Balance
            $user->decrement('balance', $amount);

            return true;
        });
    }

    // Refund Money (Failed Order or Cancellation)
    public static function refund(User $user, $amount, $description = 'Order Refund', $originalReference = null)
    {
        return DB::transaction(function () use ($user, $amount, $description, $originalReference) {
            
            // Capture Balance Before
            $balanceBefore = $user->balance;
            
            // Calculate Balance After
            $balanceAfter = $balanceBefore + $amount;

            // Generate Refund Reference
            $refundReference = 'REFUND-' . strtoupper(uniqid());

            // Save the Ledger Entry
            Wallet::create([
                'user_id' => $user->id,
                'balance_before' => $balanceBefore,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'type' => 'credit',
                'description' => $description . ($originalReference ? " (Ref: {$originalReference})" : ''),
                'reference' => $refundReference,
                'payment_method' => 'Refund',
                'status' => 'success'
            ]);

            // Update User Balance
            $user->increment('balance', $amount);
            
            return [
                'success' => true,
                'reference' => $refundReference,
                'amount' => $amount,
                'new_balance' => $balanceAfter
            ];
        });
    }
}