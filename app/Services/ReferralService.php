<?php

namespace App\Services;

use App\Models\Referral;
use App\Models\ReferredUser;
use App\Models\ReferralWalletTransaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class ReferralService
{
    const BONUS_AMOUNT = 100; // ₦100 bonus per qualified referral

    /**
     * Create referral account for user
     */
    public static function createReferralAccount(User $user)
    {
        if ($user->referral) {
            return $user->referral;
        }

        return Referral::create([
            'user_id' => $user->id,
            'referral_code' => Referral::generateUniqueCode(),
            'referral_balance' => 0,
        ]);
    }

    /**
     * Record a user being referred
     */
    public static function recordReferral($referralCode, User $newUser)
    {
        $referral = Referral::where('referral_code', $referralCode)->first();

        if (!$referral) {
            return false;
        }

        // Don't allow self-referral
        if ($referral->user_id === $newUser->id) {
            return false;
        }

        // Check if already referred
        $exists = ReferredUser::where('referred_user_id', $newUser->id)->exists();
        if ($exists) {
            return false;
        }

        // Record the referral
        ReferredUser::create([
            'referrer_id' => $referral->user_id,
            'referred_user_id' => $newUser->id,
            'has_deposited' => false,
            'has_ordered' => false,
            'bonus_paid' => false,
        ]);

        // Update user's referred_by_code
        $newUser->update(['referred_by_code' => $referralCode]);

        return true;
    }

    /**
     * Mark that a referred user has made a deposit
     */
    public static function markDeposit(User $user)
    {
        ReferredUser::where('referred_user_id', $user->id)
            ->update(['has_deposited' => true]);
    }

    /**
     * Mark that a referred user has placed an order
     */
    public static function markOrder(User $user)
    {
        ReferredUser::where('referred_user_id', $user->id)
            ->update(['has_ordered' => true]);
    }

    /**
     * Process pending bonuses for a referrer
     * Call this when user visits their referral dashboard
     */
    public static function processPendingBonuses(User $referrer)
    {
        $referral = $referrer->referral;
        if (!$referral) {
            return 0;
        }

        // Get all referred users who have deposited AND ordered but haven't received bonus
        $qualifiedReferrals = ReferredUser::where('referrer_id', $referrer->id)
            ->where('has_deposited', true)
            ->where('has_ordered', true)
            ->where('bonus_paid', false)
            ->get();

        $totalBonusAdded = 0;

        foreach ($qualifiedReferrals as $referredUser) {
            // Credit the bonus
            DB::transaction(function () use ($referral, $referredUser, &$totalBonusAdded) {
                $balanceBefore = $referral->referral_balance;
                $balanceAfter = $balanceBefore + self::BONUS_AMOUNT;

                // Create transaction record
                ReferralWalletTransaction::create([
                    'referral_id' => $referral->id,
                    'balance_before' => $balanceBefore,
                    'amount' => self::BONUS_AMOUNT,
                    'balance_after' => $balanceAfter,
                    'type' => 'credit',
                    'description' => 'Referral bonus for user: ' . $referredUser->referredUser->name,
                    'reference' => 'REFBONUS-' . strtoupper(\Str::random(10)),
                    'status' => 'success',
                ]);

                // Update referral balance
                $referral->increment('referral_balance', self::BONUS_AMOUNT);

                // Mark bonus as paid
                $referredUser->update([
                    'bonus_paid' => true,
                    'bonus_paid_at' => now(),
                ]);

                $totalBonusAdded += self::BONUS_AMOUNT;
            });
        }

        return $totalBonusAdded;
    }

    /**
     * Withdraw to main wallet balance
     */
    public static function withdrawToWallet(User $user, $amount)
    {
        $referral = $user->referral;

        if (!$referral || $referral->referral_balance < $amount) {
            throw new \Exception('Insufficient referral balance');
        }

        return DB::transaction(function () use ($user, $referral, $amount) {
            $balanceBefore = $referral->referral_balance;
            $balanceAfter = $balanceBefore - $amount;

            // Create withdrawal transaction (pending approval)
            $withdrawal = ReferralWalletTransaction::create([
                'referral_id' => $referral->id,
                'balance_before' => $balanceBefore,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'type' => 'debit',
                'description' => 'Withdrawal to main wallet',
                'reference' => 'REFWD-' . strtoupper(\Str::random(10)),
                'withdrawal_method' => 'wallet',
                'status' => 'pending',
            ]);

            return $withdrawal;
        });
    }

    /**
     * Withdraw to bank account
     */
    public static function withdrawToBank(User $user, $amount, $bankName, $accountNumber, $accountName)
    {
        $referral = $user->referral;

        if (!$referral || $referral->referral_balance < $amount) {
            throw new \Exception('Insufficient referral balance');
        }

        return DB::transaction(function () use ($user, $referral, $amount, $bankName, $accountNumber, $accountName) {
            $balanceBefore = $referral->referral_balance;
            $balanceAfter = $balanceBefore - $amount;

            // Create withdrawal transaction (pending approval)
            $withdrawal = ReferralWalletTransaction::create([
                'referral_id' => $referral->id,
                'balance_before' => $balanceBefore,
                'amount' => $amount,
                'balance_after' => $balanceAfter,
                'type' => 'debit',
                'description' => 'Withdrawal to bank account',
                'reference' => 'REFWD-' . strtoupper(\Str::random(10)),
                'withdrawal_method' => 'bank',
                'bank_name' => $bankName,
                'account_number' => $accountNumber,
                'account_name' => $accountName,
                'status' => 'pending',
            ]);

            return $withdrawal;
        });
    }

    /**
     * Approve withdrawal (Admin only)
     */
    public static function approveWithdrawal($transactionId, $adminId, $note = null)
    {
        return DB::transaction(function () use ($transactionId, $adminId, $note) {
            $transaction = ReferralWalletTransaction::findOrFail($transactionId);

            if ($transaction->status !== 'pending') {
                throw new \Exception('Transaction is not pending');
            }

            $referral = $transaction->referral;

            // Check if sufficient balance
            if ($referral->referral_balance < $transaction->amount) {
                throw new \Exception('Insufficient referral balance');
            }

            // Deduct from referral balance
            $referral->decrement('referral_balance', $transaction->amount);

            // Update transaction status
            $transaction->update([
                'status' => 'approved',
                'approved_by' => $adminId,
                'approved_at' => now(),
                'admin_note' => $note,
            ]);

            // If withdrawal to wallet, credit main wallet
            if ($transaction->withdrawal_method === 'wallet') {
                WalletService::deposit(
                    $referral->user,
                    $transaction->amount,
                    $transaction->reference,
                    'Referral Withdrawal',
                    'Referral earnings transferred to main wallet'
                );

                // Also create record in normal wallet transactions
                Wallet::create([
                    'user_id' => $referral->user_id,
                    'balance_before' => $referral->user->balance - $transaction->amount,
                    'amount' => $transaction->amount,
                    'balance_after' => $referral->user->balance,
                    'type' => 'credit',
                    'description' => 'Referral earnings transferred to wallet',
                    'reference' => $transaction->reference,
                    'payment_method' => 'referral_transfer',
                    'status' => 'success',
                ]);

                $transaction->update(['status' => 'success']);
            }

            // If withdrawal to bank, status stays 'approved' until bank transfer is processed
            // You would integrate with Korapay transfer API here

            return $transaction;
        });
    }

    /**
     * Decline withdrawal (Admin only)
     */
    public static function declineWithdrawal($transactionId, $adminId, $reason)
    {
        return DB::transaction(function () use ($transactionId, $adminId, $reason) {
            $transaction = ReferralWalletTransaction::findOrFail($transactionId);

            if ($transaction->status !== 'pending') {
                throw new \Exception('Transaction is not pending');
            }

            // Update transaction status (balance not deducted, so nothing to refund)
            $transaction->update([
                'status' => 'declined',
                'approved_by' => $adminId,
                'approved_at' => now(),
                'admin_note' => $reason,
            ]);

            return $transaction;
        });
    }
}