<?php

namespace App\Services;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class WalletService
{
    // Deposit Money
    public static function deposit(User $user, $amount, $reference, $paymentMethod = 'Korapay', $description = 'Wallet Top Up')
    {
        return DB::transaction(function () use ($user, $amount, $reference, $paymentMethod, $description) {
            
            // 1. Capture Balance Before
            $balanceBefore = $user->balance;
            
            // 2. Calculate Balance After
            $balanceAfter = $balanceBefore + $amount;

            // 3. Save the Ledger Entry
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

            // 4. Update User Balance
            $user->increment('balance', $amount);
            
            return true;
        });
    }

    // Withdraw Money (Place Order)
    public static function withdraw(User $user, $amount, $reference, $paymentMethod = 'Internal Order', $description = 'New Order')
    {
        return DB::transaction(function () use ($user, $amount, $reference, $paymentMethod, $description) {
            if ($user->balance < $amount) {
                throw new \Exception('Insufficient funds');
            }

            // 1. Capture Balance Before
            $balanceBefore = $user->balance;

            // 2. Calculate Balance After
            $balanceAfter = $balanceBefore - $amount;

            // 3. Save the Ledger Entry
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
            
            // 1. Capture Balance Before
            $balanceBefore = $user->balance;
            
            // 2. Calculate Balance After
            $balanceAfter = $balanceBefore + $amount;

            // 3. Generate Refund Reference
            $refundReference = 'REFUND-' . strtoupper(uniqid());

            // 4. Save the Ledger Entry
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

            // 5. Update User Balance
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