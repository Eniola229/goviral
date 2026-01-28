<?php

namespace App\Services;

use App\Models\Logged;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class TheOwletService
{
    protected $apiUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->apiUrl = config('services.theowlet.api_url', 'https://the-owlet.com/api/v2');
        $this->apiKey = config('services.theowlet.api_key');
    }

    /**
     * Log API request and response
     */
    protected function logApiCall($method, $requestData, $responseData, $status = 'success', $errorMessage = null)
    {
        try {
            Logged::create([
                'user_id' => Auth::id(),
                'reference' => 'OWLET-' . strtoupper(uniqid()),
                'type' => 'api',
                'method' => $method,
                'amount' => $requestData['charge'] ?? 0,
                'status' => $status,
                'description' => "The Owlet API - {$method}",
                'request_data' => $requestData,
                'response_data' => $responseData,
                'error_message' => $errorMessage,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log API call: ' . $e->getMessage());
        }
    }

    /**
     * Make API Request
     */
    protected function makeRequest($action, array $additionalParams = [])
    {
        $params = array_merge([
            'key' => $this->apiKey,
            'action' => $action,
        ], $additionalParams);

        try {
            $response = Http::timeout(30)
                ->asForm()
                ->post($this->apiUrl, $params);

            $responseData = $response->json();

            // Log the API call
            $this->logApiCall(
                $action,
                $params,
                $responseData,
                $response->successful() ? 'success' : 'failed',
                $response->successful() ? null : ($responseData['error'] ?? 'Unknown error')
            );

            if (!$response->successful()) {
                return [
                    'status' => 'error',
                    'error' => $responseData['error'] ?? 'API request failed',
                    'response' => $responseData
                ];
            }

            return $responseData;

        } catch (\Exception $e) {
            $errorData = [
                'status' => 'error',
                'error' => $e->getMessage()
            ];

            // Log the exception
            $this->logApiCall(
                $action,
                $params,
                $errorData,
                'failed',
                $e->getMessage()
            );

            return $errorData;
        }
    }

    /**
     * Get Services List
     */
    public function getServices()
    {
        $response = $this->makeRequest('services');

        // The Owlet returns array directly for services
        if (is_array($response) && !isset($response['error'])) {
            return $response;
        }

        // If error, return empty array
        return [];
    }

    /**
     * Place Order
     */
    public function placeOrder($serviceId, $link, $quantity, array $additionalParams = [])
    {
        $params = array_merge([
            'service' => $serviceId,
            'link' => $link,
            'quantity' => $quantity,
        ], $additionalParams);

        // Calculate charge for logging
        $params['charge'] = 0; // This will be calculated on controller side

        return $this->makeRequest('add', $params);
    }

    /**
     * Get Order Status
     */
    public function getOrderStatus($orderId)
    {
        return $this->makeRequest('status', [
            'order' => $orderId
        ]);
    }

    /**
     * Get Multiple Order Status
     */
    public function getMultipleOrderStatus(array $orderIds)
    {
        return $this->makeRequest('status', [
            'orders' => implode(',', $orderIds)
        ]);
    }

    /**
     * Create Refill
     */
    public function createRefill($orderId)
    {
        return $this->makeRequest('refill', [
            'order' => $orderId
        ]);
    }

    /**
     * Create Multiple Refills
     */
    public function createMultipleRefills(array $orderIds)
    {
        return $this->makeRequest('refill', [
            'orders' => implode(',', $orderIds)
        ]);
    }

    /**
     * Get Refill Status
     */
    public function getRefillStatus($refillId)
    {
        return $this->makeRequest('refill_status', [
            'refill' => $refillId
        ]);
    }

    /**
     * Cancel Order(s)
     */
    public function cancelOrders(array $orderIds)
    {
        return $this->makeRequest('cancel', [
            'orders' => implode(',', $orderIds)
        ]);
    }

    /**
     * Get Balance
     */
    public function getBalance()
    {
        return $this->makeRequest('balance');
    }
}