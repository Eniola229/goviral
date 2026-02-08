<?php

namespace App\Traits;

use App\Models\Logged;
use App\Models\User;
use App\Models\Wallet;
use App\Services\KorapayService;
use App\Services\WalletService;

trait ChecksPendingDeposits
{
    /**
     * Check pending deposits for a specific user (batch of 5)
     */
    protected function checkUserPendingDeposits($user, $limit = 5)
    {
        try {
            $korapayService = app(KorapayService::class);
            
            // Get pending WALLET TRANSACTIONS  for this user only
            $pendingWallets = Wallet::where('user_id', $user->id)
                                 ->where('type', 'credit')
                                 ->where('payment_method', 'Korapay')
                                 ->where('status', 'pending') // Check actual wallet transaction status
                                 ->where('created_at', '>=', now()->subDays(13))
                                 ->latest()
                                 ->limit($limit)
                                 ->get();

            if ($pendingWallets->isEmpty()) {
                return;
            }

            foreach ($pendingWallets as $walletEntry) {
                $this->processPendingDeposit($walletEntry, $user, $korapayService, 'User');
            }

        } catch (\Exception $e) {
            // Log error
            Logged::create([
                'user_id' => $user->id,
                'reference' => 'USER-DASH-ERROR-' . time(),
                'type' => 'pending_check_failed',
                'method' => 'Korapay',
                'amount' => 0,
                'status' => 'failed',
                'description' => 'User: Pending Deposits Check Failed',
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Check all pending deposits (batch limit)
     */
    protected function checkAllPendingDeposits($limit = 10, $source = 'Admin dashboard')
    {
        try {
            $korapayService = app(KorapayService::class);
            
            // Get pending WALLET TRANSACTIONS  for ALL users
            $pendingWallets = Wallet::where('type', 'credit')
                                 ->where('payment_method', 'Korapay')
                                 ->where('status', 'pending') // ← Check actual wallet transaction status
                                 ->where('created_at', '>=', now()->subDays(13))
                                 ->latest()
                                 ->limit($limit)
                                 ->get();

            if ($pendingWallets->isEmpty()) {
                return;
            }

            foreach ($pendingWallets as $walletEntry) {
                $user = User::find($walletEntry->user_id);
                
                if (!$user) {
                    // Log with the user_id from the transaction (even if user doesn't exist)
                    Logged::create([
                        'user_id' => $walletEntry->user_id,
                        'reference' => $walletEntry->reference,
                        'type' => 'pending_check_user_not_found',
                        'method' => 'Korapay',
                        'amount' => $walletEntry->amount,
                        'status' => 'failed',
                        'description' => "{$source}: User not found for pending deposit",
                        'error_message' => 'User not found',
                        'ip_address' => request()->ip(),
                    ]);
                    continue;
                }

                $this->processPendingDeposit($walletEntry, $user, $korapayService, $source);
            }

        } catch (\Exception $e) {
            // For system-level errors, use admin user or first user as fallback
            $fallbackUserId = auth('admin')->id() ?? auth()->id() ?? 1;
            
            Logged::create([
                'user_id' => $fallbackUserId,
                'reference' => 'ADMIN-ERROR-' . time(),
                'type' => 'pending_check_failed',
                'method' => 'Korapay',
                'amount' => 0,
                'status' => 'failed',
                'description' => "{$source}: Pending Deposits Check Failed",
                'request_data' => ['source' => $source],
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
            ]);
        }
    }

    /**
     * Process a single pending deposit
     * 
     * @param Wallet $walletEntry - The actual wallet transaction record
     */
    protected function processPendingDeposit($walletEntry, $user, $korapayService, $source)
    {
        try {
            // Create check log
            Logged::create([
                'user_id' => $user->id,
                'reference' => $walletEntry->reference,
                'type' => 'pending_check',
                'method' => 'Korapay',
                'amount' => $walletEntry->amount,
                'status' => 'pending',
                'description' => "{$source}: Checking pending transaction status",
                'request_data' => [
                    'source' => $source,
                    'original_wallet_status' => $walletEntry->status,
                    'wallet_id' => $walletEntry->id,
                ],
                'ip_address' => request()->ip(),
            ]);

            // Verify with Korapay
            $verification = $korapayService->verifyTransaction($walletEntry->reference);

            if ($verification['success']) {
                // Refresh wallet entry from database to get latest status
                $walletEntry = $walletEntry->fresh();
                
                //Check the actual WALLET status 
                if ($walletEntry->status === 'pending') {
                    // Wallet transaction still pending - now credit it
                    WalletService::deposit(
                        $user,
                        $walletEntry->amount,
                        $walletEntry->reference,
                        'Korapay',
                        "Wallet top-up successful"
                    );

                    Logged::create([
                        'user_id' => $user->id,
                        'reference' => $walletEntry->reference,
                        'type' => 'pending_check_wallet_credited',
                        'method' => 'Korapay',
                        'amount' => $walletEntry->amount,
                        'status' => 'success',
                        'description' => "{$source}: Wallet credited successfully",
                        'request_data' => [
                            'source' => $source,
                            'amount' => $walletEntry->amount,
                            'new_balance' => $user->fresh()->balance,
                            'wallet_id' => $walletEntry->id,
                        ],
                        'response_data' => $verification,
                        'ip_address' => request()->ip(),
                    ]);
                } else {
                    // Wallet transaction already processed (status is 'success' or 'failed')
                    Logged::create([
                        'user_id' => $user->id,
                        'reference' => $walletEntry->reference,
                        'type' => 'pending_check_already_processed',
                        'method' => 'Korapay',
                        'amount' => $walletEntry->amount,
                        'status' => 'success',
                        'description' => "{$source}: Wallet transaction already processed - Duplicate prevention",
                        'request_data' => [
                            'source' => $source,
                            'current_wallet_status' => $walletEntry->status,
                            'wallet_id' => $walletEntry->id,
                        ],
                        'ip_address' => request()->ip(),
                    ]);
                }
                
            } elseif (isset($verification['status']) && in_array($verification['status'], ['failed', 'cancelled', 'expired'])) {
                // Mark WALLET as failed
                WalletService::markDepositFailed($walletEntry->reference);

                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $walletEntry->reference,
                    'type' => 'pending_check_transaction_failed',
                    'method' => 'Korapay',
                    'amount' => $walletEntry->amount,
                    'status' => 'failed',
                    'description' => "{$source}: Transaction marked as failed",
                    'request_data' => [
                        'source' => $source,
                        'verification_status' => $verification['status'],
                        'wallet_id' => $walletEntry->id,
                    ],
                    'response_data' => $verification,
                    'error_message' => $verification['message'] ?? 'Payment failed',
                    'ip_address' => request()->ip(),
                ]);
            } else {
                // Transaction still pending on Korapay's end
                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $walletEntry->reference,
                    'type' => 'pending_check_still_pending',
                    'method' => 'Korapay',
                    'amount' => $walletEntry->amount,
                    'status' => 'pending',
                    'description' => "{$source}: Transaction still pending",
                    'request_data' => [
                        'source' => $source,
                        'wallet_id' => $walletEntry->id,
                    ],
                    'response_data' => $verification,
                    'ip_address' => request()->ip(),
                ]);
            }

        } catch (\Exception $e) {
            Logged::create([
                'user_id' => $user->id,
                'reference' => $walletEntry->reference,
                'type' => 'pending_check_error',
                'method' => 'Korapay',
                'amount' => $walletEntry->amount,
                'status' => 'failed',
                'description' => "{$source}: Pending Check Error",
                'request_data' => [
                    'source' => $source,
                    'wallet_id' => $walletEntry->id ?? null,
                ],
                'error_message' => $e->getMessage(),
                'ip_address' => request()->ip(),
            ]);
        }
    }
}