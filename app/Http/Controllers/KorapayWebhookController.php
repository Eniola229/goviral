<?php

namespace App\Http\Controllers;

use App\Models\Logged;
use App\Models\User;
use App\Models\Wallet;
use App\Services\KorapayService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class KorapayWebhookController extends Controller
{
    protected $korapayService;

    public function __construct(KorapayService $korapayService)
    {
        $this->korapayService = $korapayService;
    }

    /**
     * Handle Korapay webhook notifications
     */
    public function handleWebhook(Request $request)
    {
        try {
            // Get the signature from headers
            $signature = $request->header('X-Korapay-Signature');
            
            // Get raw payload
            $payload = $request->all();
            
            // Extract reference early to find user
            $reference = $payload['data']['reference'] ?? null;
            
            // Find the user from existing wallet transaction
            $existingWallet = null;
            $user = null;
            
            if ($reference) {
                $existingWallet = Wallet::where('reference', $reference)
                                     ->where('payment_method', 'Korapay')
                                     ->first();
                
                if ($existingWallet) {
                    $user = User::find($existingWallet->user_id);
                }
            }
            
            // If no user found, return early (don't log without user)
            if (!$user) {
                return response()->json(['message' => 'Transaction or user not found'], 404);
            }
            
            // Log webhook received
            Logged::create([
                'user_id' => $user->id,
                'reference' => $reference,
                'type' => 'webhook_received',
                'method' => 'Korapay',
                'amount' => $existingWallet->amount ?? 0,
                'status' => 'received',
                'description' => 'Korapay Webhook Received',
                'request_data' => [
                    'payload' => $payload,
                    'signature' => $signature,
                ],
                'ip_address' => $request->ip(),
            ]);

            // Verify webhook signature
            if (!$this->korapayService->verifyWebhookSignature($payload, $signature)) {
                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $reference,
                    'type' => 'webhook_verification_failed',
                    'method' => 'Korapay',
                    'amount' => $existingWallet->amount ?? 0,
                    'status' => 'failed',
                    'description' => 'Korapay Webhook Signature Verification Failed',
                    'request_data' => [
                        'payload' => $payload,
                        'signature' => $signature,
                    ],
                    'error_message' => 'Invalid signature',
                    'ip_address' => $request->ip(),
                ]);
                
                return response()->json(['message' => 'Invalid signature'], 401);
            }

            // Extract data from webhook
            $data = $payload['data'] ?? [];
            $status = strtolower($data['status'] ?? '');
            $eventType = $payload['event'] ?? '';

            if (!$reference) {
                return response()->json(['message' => 'No reference found'], 400);
            }

            if (!$existingWallet) {
                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $reference,
                    'type' => 'webhook_transaction_not_found',
                    'method' => 'Korapay',
                    'amount' => 0,
                    'status' => 'failed',
                    'description' => 'Korapay Webhook: Wallet transaction not found in database',
                    'request_data' => [
                        'reference' => $reference,
                        'payload' => $payload,
                    ],
                    'error_message' => 'Wallet transaction not found',
                    'ip_address' => $request->ip(),
                ]);
                
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // Create webhook log entry
            Logged::create([
                'user_id' => $user->id,
                'reference' => $reference,
                'type' => 'webhook_notification',
                'method' => 'Korapay',
                'amount' => $existingWallet->amount,
                'status' => $status,
                'description' => "Webhook notification received: {$eventType}",
                'request_data' => $payload,
                'ip_address' => $request->ip(),
            ]);

            // Process based on status
            $this->processWebhookStatus($existingWallet, $status, $data);

            return response()->json(['message' => 'Webhook processed successfully'], 200);

        } catch (\Exception $e) {
            // Only log exceptions if we have a user
            if (isset($user) && $user) {
                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $reference ?? 'WEBHOOK-ERROR-' . time(),
                    'type' => 'webhook_exception',
                    'method' => 'Korapay',
                    'amount' => 0,
                    'status' => 'failed',
                    'description' => 'Korapay Webhook Exception',
                    'request_data' => $request->all(),
                    'error_message' => $e->getMessage(),
                    'ip_address' => $request->ip(),
                ]);
            }

            return response()->json(['message' => 'Webhook processing failed'], 500);
        }
    }

    /**
     * Process webhook status and update records
     * 
     * @param Wallet $walletEntry - The actual wallet transaction record
     */
    protected function processWebhookStatus(Wallet $walletEntry, string $status, array $data)
    {
        // Check the actual WALLET status 
        // Refresh from database to get latest status
        $walletEntry = $walletEntry->fresh();
        
        if ($walletEntry->status === 'success') {
            Logged::create([
                'user_id' => $walletEntry->user_id,
                'reference' => $walletEntry->reference,
                'type' => 'webhook_duplicate_prevention',
                'method' => 'Korapay',
                'amount' => $walletEntry->amount,
                'status' => 'success',
                'description' => 'Korapay Webhook: Wallet already credited - Duplicate prevention',
                'request_data' => [
                    'current_wallet_status' => $walletEntry->status,
                    'webhook_status' => $status,
                ],
                'ip_address' => request()->ip(),
            ]);
            
            return;
        }

        $user = User::find($walletEntry->user_id);
        
        if (!$user) {
            Logged::create([
                'user_id' => $walletEntry->user_id,
                'reference' => $walletEntry->reference,
                'type' => 'webhook_user_not_found',
                'method' => 'Korapay',
                'amount' => $walletEntry->amount,
                'status' => 'failed',
                'description' => 'Korapay Webhook: User not found in database',
                'request_data' => [
                    'user_id' => $walletEntry->user_id,
                    'reference' => $walletEntry->reference,
                ],
                'error_message' => 'User not found',
                'ip_address' => request()->ip(),
            ]);
            
            return;
        }

        if ($status === 'success') {
            // Double-check: verify transaction one more time with Korapay API
            $verification = $this->korapayService->verifyTransaction($walletEntry->reference);
            
            if ($verification['success']) {
                // Credit user wallet (WalletService::deposit will update the wallet entry from pending to success)
                WalletService::deposit(
                    $user,
                    $walletEntry->amount, // Use original amount from wallet
                    $walletEntry->reference,
                    'Korapay',
                    'Wallet top-up confirmed via webhook'
                );

                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $walletEntry->reference,
                    'type' => 'webhook_wallet_credited',
                    'method' => 'Korapay',
                    'amount' => $walletEntry->amount,
                    'status' => 'success',
                    'description' => 'Korapay Webhook: Wallet credited successfully',
                    'request_data' => [
                        'user_id' => $user->id,
                        'amount' => $walletEntry->amount,
                        'new_balance' => $user->fresh()->balance,
                    ],
                    'response_data' => $verification,
                    'ip_address' => request()->ip(),
                ]);
            } else {
                Logged::create([
                    'user_id' => $user->id,
                    'reference' => $walletEntry->reference,
                    'type' => 'webhook_verification_failed',
                    'method' => 'Korapay',
                    'amount' => $walletEntry->amount,
                    'status' => 'failed',
                    'description' => 'Korapay Webhook: Verification failed for success webhook',
                    'request_data' => [
                        'reference' => $walletEntry->reference,
                    ],
                    'response_data' => $verification,
                    'error_message' => 'API verification failed despite webhook success status',
                    'ip_address' => request()->ip(),
                ]);
            }

        } elseif (in_array($status, ['failed', 'cancelled', 'expired'])) {
            // Mark wallet entry as failed
            WalletService::markDepositFailed($walletEntry->reference);

            Logged::create([
                'user_id' => $user->id,
                'reference' => $walletEntry->reference,
                'type' => 'webhook_transaction_failed',
                'method' => 'Korapay',
                'amount' => $walletEntry->amount,
                'status' => 'failed',
                'description' => "Korapay Webhook: Transaction marked as {$status}",
                'request_data' => [
                    'user_id' => $user->id,
                    'webhook_status' => $status,
                ],
                'error_message' => "Payment {$status} via webhook",
                'ip_address' => request()->ip(),
            ]);

        } elseif (in_array($status, ['pending', 'processing'])) {
            // Keep as pending (wallet entry is already pending)
            Logged::create([
                'user_id' => $user->id,
                'reference' => $walletEntry->reference,
                'type' => 'webhook_still_pending',
                'method' => 'Korapay',
                'amount' => $walletEntry->amount,
                'status' => 'pending',
                'description' => "Korapay Webhook: Transaction still {$status}",
                'request_data' => [
                    'user_id' => $user->id,
                    'webhook_status' => $status,
                ],
                'ip_address' => request()->ip(),
            ]);
        }
    }
}