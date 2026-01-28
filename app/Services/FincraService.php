<?php

namespace App\Services;

use App\Models\Logged;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class FincraService
{
    protected $baseUrl;
    protected $secretKey;
    protected $publicKey;
    protected $businessId;
    protected $currency;

    public function __construct()
    {
        $this->baseUrl = rtrim(env('FINCRA_BASE_URL'), '/');
        $this->secretKey = trim(env('FINCRA_SECRET_KEY'));
        $this->publicKey = trim(env('FINCRA_PUBLIC_KEY'));
        $this->businessId = trim(env('FINCRA_BUSINESS_ID'));
        $this->currency = env('FINCRA_CURRENCY', 'NGN');
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
                'amount' => (string)$amount,
                'currency' => $this->currency,
                'customer' => [
                    'name'  => $user->name,
                    'email' => $user->email,
                ],
                'paymentMethods' => ['card', 'bank_transfer'],
                'feeBearer' => 'customer',
                'reference' => $reference,
                'redirectUrl' => $redirectUrl,
                'metadata' => [
                    'user_id' => $user->id,
                ],
            ];

            // Create initial log entry
            $logEntry = Logged::create([
                'user_id' => $user->id,
                'reference' => $reference,
                'type' => 'wallet_topup',
                'method' => 'Fincra',
                'amount' => $amount,
                'status' => 'pending',
                'description' => 'Wallet top-up initiated',
                'request_data' => $payload,
                'ip_address' => request()->ip(),
            ]);

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api-key' => $this->secretKey,
                'x-pub-key' => $this->publicKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/checkout/payments', $payload);

            $responseData = $response->json();

            // Update log with response
            $logEntry->update([
                'response_data' => $responseData,
            ]);

            if ($response->successful()) {
                if (isset($responseData['data']['link'])) {
                    return [
                        'success' => true,
                        'data' => [
                            'checkout_url' => $responseData['data']['link']
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

            return [
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ];
        }
    }

    public function verifyTransaction($reference)
    {
        try {
            if (empty($this->secretKey) || empty($this->businessId)) {
                return ['success' => false, 'message' => 'Configuration error'];
            }

            $response = Http::withHeaders([
                'accept' => 'application/json',
                'api-key' => $this->secretKey,
                'x-business-id' => $this->businessId,
            ])->get($this->baseUrl . "/checkout/payments/merchant-reference/$reference");

            $responseData = $response->json();

            // Find the log entry
            $logEntry = Logged::where('reference', $reference)->first();

            if ($response->successful()) {
                if (isset($responseData['data'])) {
                    $data = $responseData['data'];
                    $status = strtolower($data['status'] ?? '');

                    // Update log with verification response
                    if ($logEntry) {
                        $logEntry->update([
                            'response_data' => array_merge(
                                $logEntry->response_data ?? [],
                                ['verification' => $data]
                            ),
                        ]);
                    }

                    if ($status === 'success' || $status === 'successful') {
                        $amountReceived = $data['amountReceived'] ?? 0;
                        
                        if ($amountReceived > 0) {
                            // Update log as successful
                            if ($logEntry) {
                                $logEntry->update([
                                    'status' => 'success',
                                ]);
                            }

                            return [
                                'success' => true,
                                'amount' => $data['amount'],
                                'amount_received' => $amountReceived,
                                'reference' => $reference,
                                'transaction_id' => $data['id'] ?? null,
                            ];
                        } else {
                            if ($logEntry) {
                                $logEntry->update([
                                    'status' => 'failed',
                                    'error_message' => 'Status is success but no amount received',
                                ]);
                            }
                        }
                    } elseif ($status === 'pending') {
                        if ($logEntry) {
                            $logEntry->update(['status' => 'pending']);
                        }

                        return [
                            'success' => false,
                            'message' => 'Payment is still pending',
                            'status' => 'pending'
                        ];
                    } elseif ($status === 'failed' || $status === 'cancelled') {
                        if ($logEntry) {
                            $logEntry->update([
                                'status' => $status,
                                'error_message' => 'Payment was ' . $status,
                            ]);
                        }

                        return [
                            'success' => false,
                            'message' => 'Payment was ' . $status,
                            'status' => $status
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
            if ($logEntry) {
                $logEntry->update([
                    'status' => 'failed',
                    'error_message' => 'Verification request failed',
                    'response_data' => array_merge(
                        $logEntry->response_data ?? [],
                        ['verification_error' => $responseData]
                    ),
                ]);
            }

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
     * Verify webhook signature (implement based on Fincra documentation)
     */
    public function verifyWebhookSignature($payload, $signature)
    {
        // TODO: Implement signature verification based on Fincra docs
        // This typically involves HMAC verification
        return true;
    }
}