<?php

namespace App\Services;

use App\Models\Logged;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class KorapayService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;
    protected $currency;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('KORAPAY_BASE_URL', 'https://api.korapay.com/merchant'), '/');
        $this->secretKey = trim(env('KORAPAY_SECRET_KEY'));
        $this->publicKey = trim(env('KORAPAY_PUBLIC_KEY'));
        $this->currency = env('KORAPAY_CURRENCY', 'NGN');
    }

    public function initializeTransaction($amount, $user, $redirectUrl)
    {
        try {
            if (empty($this->secretKey) || empty($this->publicKey)) {
                return [
                    'success' => false,
                    'message' => 'Payment gateway not configured. Please contact support.'
                ];
            }

            $reference = 'VIRAL-' . strtoupper(Str::random(10));

            $payload = [
                'reference' => $reference,
                'amount' => (int)$amount, // Korapay expects integer in kobo/cents
                'currency' => $this->currency,
                'redirect_url' => $redirectUrl,
                'customer' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'notification_url' => route('wallet.webhook'), // For webhook notifications
                'merchant_bears_cost' => true, // we bear the cost
                'metadata' => [
                    'user_id' => $user->id,
                    'purpose' => 'wallet_topup',
                ],
            ];

            // Create initial log entry with PENDING status
            $logEntry = Logged::create([
                'user_id' => $user->id,
                'reference' => $reference,
                'type' => 'wallet_topup',
                'method' => 'Korapay',
                'amount' => $amount, // Store the ORIGINAL amount user intended to pay
                'status' => 'pending',
                'description' => 'Wallet top-up initiated',
                'request_data' => $payload,
                'ip_address' => request()->ip(),
            ]);

            \Log::info('Korapay Initialize Request', [
                'reference' => $reference,
                'amount' => $amount,
                'redirect_url' => $redirectUrl
            ]);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/api/v1/charges/initialize', $payload);

            $responseData = $response->json();

            // Update log with response
            $logEntry->update([
                'response_data' => $responseData,
            ]);

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                if (isset($responseData['data']['checkout_url'])) {
                    return [
                        'success' => true,
                        'data' => [
                            'checkout_url' => $responseData['data']['checkout_url']
                        ],
                        'reference' => $reference
                    ];
                }

                // Update log as failed
                $logEntry->update([
                    'status' => 'failed',
                    'error_message' => 'Unexpected response structure',
                ]);

                return [
                    'success' => false,
                    'message' => 'Unexpected response from payment gateway'
                ];
            }

            $errorMessage = $responseData['message'] ?? 'Payment Gateway Error';

            // Update log as failed
            $logEntry->update([
                'status' => 'failed',
                'error_message' => $errorMessage,
            ]);

            return [
                'success' => false,
                'message' => $errorMessage
            ];

        } catch (\Exception $e) {
            // Update log if it exists
            if (isset($logEntry)) {
                $logEntry->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            \Log::error('Korapay Initialize Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function verifyTransaction($reference)
    {
        try {
            if (empty($this->secretKey)) {
                return ['success' => false, 'message' => 'Configuration error'];
            }

            // Find the log entry first - we need the original amount
            $logEntry = Logged::where('reference', $reference)->first();
            
            if (!$logEntry) {
                return [
                    'success' => false,
                    'message' => 'Transaction not found'
                ];
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . "/api/v1/charges/{$reference}");

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['status']) && $responseData['status'] === true) {
                if (isset($responseData['data'])) {
                    $data = $responseData['data'];
                    $status = strtolower($data['status'] ?? '');

                    // Update log with verification response
                    $logEntry->update([
                        'response_data' => array_merge(
                            $logEntry->response_data ?? [],
                            ['verification' => $data]
                        ),
                    ]);

                    if ($status === 'success') {
                        // Update log as successful
                        $logEntry->update([
                            'status' => 'success',
                        ]);

                        // Return the ORIGINAL amount from our log (what user intended to pay)
                        // NOT the amount_paid from Korapay (which includes fees)
                        return [
                            'success' => true,
                            'amount' => $logEntry->amount, // Original amount from our database
                            'reference' => $reference,
                            'transaction_id' => $data['reference'] ?? null,
                            'payment_method' => $data['payment_method'] ?? 'korapay',
                        ];
                    } elseif ($status === 'processing' || $status === 'pending') {
                        $logEntry->update(['status' => 'pending']);

                        return [
                            'success' => false,
                            'message' => 'Payment is still pending',
                            'status' => 'pending'
                        ];
                    } elseif ($status === 'failed' || $status === 'cancelled') {
                        $logEntry->update([
                            'status' => 'failed',
                            'error_message' => 'Payment was ' . $status,
                        ]);

                        return [
                            'success' => false,
                            'message' => 'Payment was ' . $status,
                            'status' => 'failed'
                        ];
                    }

                    return [
                        'success' => false,
                        'message' => 'Payment status: ' . $status,
                        'status' => $status
                    ];
                }
            }

            // Update log as failed
            $logEntry->update([
                'status' => 'failed',
                'error_message' => 'Verification request failed',
                'response_data' => array_merge(
                    $logEntry->response_data ?? [],
                    ['verification_error' => $responseData]
                ),
            ]);

            return [
                'success' => false,
                'message' => 'Verification request failed'
            ];

        } catch (\Exception $e) {
            // Update log if it exists
            $logEntry = Logged::where('reference', $reference)->first();
            if ($logEntry) {
                $logEntry->update([
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                ]);
            }

            return [
                'success' => false,
                'message' => 'Verification error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature using HMAC SHA256
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        try {
            if (empty($this->secretKey)) {
                return false;
            }

            // Korapay signs only the 'data' object in the webhook payload
            $dataToSign = is_array($payload) ? json_encode($payload['data'] ?? $payload) : $payload;
            
            $expectedSignature = hash_hmac('sha256', $dataToSign, $this->secretKey);
            
            return hash_equals($expectedSignature, $signature);
        } catch (\Exception $e) {
            \Log::error('Korapay webhook signature verification failed: ' . $e->getMessage());
            return false;
        }
    }
}