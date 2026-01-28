<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Logged;
use App\Services\OgaviralService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Notifications\OrderPlaced;

class OrderController extends Controller
{
    protected $ogaviralService;

    public function __construct(OgaviralService $ogaviralService)
    {
        $this->ogaviralService = $ogaviralService;
    }

    // Show New Order Form
    public function create()
    {
        $services = $this->ogaviralService->getServices();

        // Map social media names to FontAwesome icons (assuming FA6)
        $platformIcons = [
            'Instagram' => 'fa-brands fa-instagram',
            'TikTok'    => 'fa-brands fa-tiktok',
            'Facebook'  => 'fa-brands fa-facebook',
            'Telegram'  => 'fa-brands fa-telegram',
            'Twitter'   => 'fa-brands fa-x-twitter', // or fa-twitter
            'YouTube'   => 'fa-brands fa-youtube',
            'Spotify'   => 'fa-brands fa-spotify',
            'Pinterest' => 'fa-brands fa-pinterest',
            'LinkedIn'  => 'fa-brands fa-linkedin',
            'Discord'   => 'fa-brands fa-discord',
            'Snapchat'  => 'fa-brands fa-snapchat',
            'Twitch'    => 'fa-brands fa-twitch',
            'WhatsApp'    => 'fa-brands fa-whatsapp',
            'Website'   => 'fa-solid fa-globe', // Generic for others
        ];

        $groupedServices = [];

        foreach ($services as $service) {
            $serviceName = $service['name'];
            $platform = 'Website'; // Default fallback

            // Detect platform from the service name (Case insensitive)
            // We check for specific keywords to group them
            if (stripos($serviceName, 'Instagram') !== false) $platform = 'Instagram';
            elseif (stripos($serviceName, 'TikTok') !== false) $platform = 'TikTok';
            elseif (stripos($serviceName, 'Facebook') !== false) $platform = 'Facebook';
            elseif (stripos($serviceName, 'Telegram') !== false) $platform = 'Telegram';
            elseif (stripos($serviceName, 'Twitter') !== false) $platform = 'Twitter';
            elseif (stripos($serviceName, 'Youtube') !== false || stripos($serviceName, 'YouTube') !== false) $platform = 'YouTube';
            elseif (stripos($serviceName, 'Spotify') !== false) $platform = 'Spotify';
            elseif (stripos($serviceName, 'Pinterest') !== false) $platform = 'Pinterest';
            elseif (stripos($serviceName, 'Linkedin') !== false) $platform = 'LinkedIn';
            elseif (stripos($serviceName, 'Discord') !== false) $platform = 'Discord';
            elseif (stripos($serviceName, 'Snapchat') !== false) $platform = 'Snapchat';
            elseif (stripos($serviceName, 'Twitch') !== false) $platform = 'Twitch';
            elseif (stripos($serviceName, 'WhatsApp') !== false) $platform = 'WhatsApp';
            
            // Get the icon, default to globe if not found
            $icon = $platformIcons[$platform] ?? $platformIcons['Website'];

            // Initialize the platform group if it doesn't exist
            if (!isset($groupedServices[$platform])) {
                $groupedServices[$platform] = [
                    'icon' => $icon,
                    'services' => []
                ];
            }

            // Add service to the platform group
            $groupedServices[$platform]['services'][] = $service;
        }

        return view('order.new', compact('groupedServices'));
    }

    // Process the Order
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|integer',
            'service_name' => 'required|string',
            'link' => 'required|url',
            'quantity' => 'required|integer|min:10',
            'charge' => 'required|numeric|min:0',
        ]);

        $user = Auth::user();
        $orderReference = 'ORD-' . strtoupper(Str::random(8));
        
        // Log initial order request
        $this->logOrderAction(
            'order_initiated',
            $orderReference,
            $request->charge,
            'success',
            'Order initiated by user',
            [
                'service_id' => $request->service_id,
                'service_name' => $request->service_name,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'charge' => $request->charge,
            ],
            ['status' => 'initiated']
        );
        
        // 1. Check Balance (Double check)
        if ($user->balance < $request->charge) {
            // Log insufficient balance
            $this->logOrderAction(
                'order_failed',
                $orderReference,
                $request->charge,
                'failed',
                'Insufficient wallet balance',
                ['balance' => $user->balance, 'required' => $request->charge],
                null,
                'Insufficient funds'
            );
            
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'Insufficient wallet balance.'
            ]);
        }

        // 2. Transaction: Deduct Wallet
        try {
            WalletService::withdraw(
                $user, 
                $request->charge, 
                $orderReference, 
                'Order Debit', 
                "Order for: {$request->service_name}"
            );

            // Log successful wallet deduction
            $this->logOrderAction(
                'wallet_debited',
                $orderReference,
                $request->charge,
                'success',
                'Wallet debited for order',
                ['previous_balance' => $user->balance + $request->charge],
                ['new_balance' => $user->balance]
            );

            // 3. Send to Ogaviral API
            $apiResponse = $this->ogaviralService->placeOrder(
                $request->service_id, 
                $request->link, 
                $request->quantity
            );

            // 4. Handle API Response
            // Ogaviral returns {"order": 23501} on success
            if (isset($apiResponse['order']) && is_numeric($apiResponse['order'])) {

                // SUCCESS: Save Order
                $order = Order::create([
                    'user_id' => $user->id,
                    'service_id' => $request->service_id,
                    'service_name' => $request->service_name,
                    'link' => $request->link,
                    'quantity' => $request->quantity,
                    'charge' => $request->charge,
                    'status' => 'processing',
                    'api_order_id' => $apiResponse['order'],
                    'api_response' => json_encode($apiResponse),
                ]);

                // Log successful order
                $this->logOrderAction(
                    'order_success',
                    $orderReference,
                    $request->charge,
                    'success',
                    'Order placed successfully',
                    [
                        'order_id' => $order->id,
                        'api_order_id' => $apiResponse['order'],
                        'service_id' => $request->service_id,
                        'quantity' => $request->quantity,
                    ],
                    $apiResponse
                );

                //Send Notification
                try {
                    $user->notify(new OrderPlaced($order));
                } catch (\Exception $e) {
                    \Log::error('Failed to send order notification: ' . $e->getMessage());
                }

                return redirect()->route('orders.index')->with('alert', [
                    'type' => 'success',
                    'message' => 'Order placed successfully! Order ID: ' . $apiResponse['order']
                ]);

            } else {
                // FAILURE: Auto Refund
                $errorMessage = $apiResponse['error'] ?? 'Unknown Error';
                
                // Log API failure
                $this->logOrderAction(
                    'api_failed',
                    $orderReference,
                    $request->charge,
                    'failed',
                    'API order placement failed',
                    [
                        'service_id' => $request->service_id,
                        'link' => $request->link,
                        'quantity' => $request->quantity,
                    ],
                    $apiResponse,
                    $errorMessage
                );
                
                // Refund the user
                $refundResult = WalletService::refund(
                    $user, 
                    $request->charge, 
                    'Order Failed - API Error: ' . $errorMessage,
                    $orderReference
                );

                // Log refund
                $this->logOrderAction(
                    'order_refunded',
                    $orderReference,
                    $request->charge,
                    'success',
                    'Wallet refunded after API failure',
                    ['original_reference' => $orderReference],
                    $refundResult
                );
                
                // Save failed order for record
                Order::create([
                    'user_id' => $user->id,
                    'service_id' => $request->service_id,
                    'service_name' => $request->service_name,
                    'link' => $request->link,
                    'quantity' => $request->quantity,
                    'charge' => $request->charge,
                    'status' => 'cancelled',
                    'api_response' => json_encode($apiResponse),
                ]);

                return redirect()->route('orders.index')->with('alert', [
                    'type' => 'error',
                    'message' => 'Order failed: ' . $errorMessage . '. ₦' . number_format($request->charge, 2) . ' has been refunded to your wallet.'
                ]);
            }

        } catch (\Exception $e) {
            // Log exception
            $this->logOrderAction(
                'system_error',
                $orderReference,
                $request->charge,
                'failed',
                'System error during order processing',
                [
                    'service_id' => $request->service_id,
                    'exception_class' => get_class($e),
                ],
                null,
                $e->getMessage()
            );

            // If wallet was deducted but something went wrong, try to refund
            if (isset($orderReference)) {
                try {
                    WalletService::refund(
                        $user, 
                        $request->charge, 
                        'System Error - ' . $e->getMessage(),
                        $orderReference
                    );

                    // Log refund after system error
                    $this->logOrderAction(
                        'error_refunded',
                        $orderReference,
                        $request->charge,
                        'success',
                        'Wallet refunded after system error',
                        ['original_reference' => $orderReference],
                        ['refunded' => true]
                    );
                    
                    $refundMessage = ' Your wallet has been refunded.';
                } catch (\Exception $refundException) {
                    \Log::error('Refund failed: ' . $refundException->getMessage());
                    
                    // Log refund failure
                    $this->logOrderAction(
                        'refund_failed',
                        $orderReference,
                        $request->charge,
                        'failed',
                        'Refund failed after system error',
                        ['original_reference' => $orderReference],
                        null,
                        $refundException->getMessage()
                    );
                    
                    $refundMessage = ' Please contact support for refund.';
                }
            } else {
                $refundMessage = '';
            }

            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage() . $refundMessage
            ]);
        }
    }

    // Order History - WITH AUTO STATUS UPDATE
    public function index()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        
        // Auto-update status for pending/processing orders
        $this->autoUpdateOrderStatuses($orders);
        
        return view('order.index', compact('orders'));
    }

    /**
     * Automatically check and update statuses for pending/processing orders
     */
    protected function autoUpdateOrderStatuses($orders)
    {
        foreach ($orders as $order) {
            // Only check orders that are not completed or cancelled
            if (in_array($order->status, ['pending', 'processing']) && $order->api_order_id) {
                try {
                    // Get status from API
                    $status = $this->ogaviralService->getOrderStatus($order->api_order_id);
                    
                    if (isset($status['status'])) {
                        // Map API status to our database status
                        $apiStatus = $status['status'];
                        $newStatus = $this->mapApiStatus($apiStatus);
                        
                        // Only update if status has changed
                        if ($order->status !== $newStatus) {
                            $oldStatus = $order->status;
                            
                            // Update order status
                            $order->update([
                                'status' => $newStatus,
                                'api_response' => json_encode($status),
                            ]);
                            
                            // Log the auto-update
                            $this->logOrderAction(
                                'auto_status_update',
                                'AUTO-' . $order->id,
                                0,
                                'success',
                                'Order status auto-updated from ' . $oldStatus . ' to ' . $newStatus,
                                [
                                    'order_id' => $order->id,
                                    'api_order_id' => $order->api_order_id,
                                    'old_status' => $oldStatus,
                                    'new_status' => $newStatus,
                                ],
                                $status
                            );
                        }
                    }
                } catch (\Exception $e) {
                    // Log error but don't stop the page from loading
                    \Log::error('Auto status update failed for order ' . $order->id . ': ' . $e->getMessage());
                    
                    $this->logOrderAction(
                        'auto_status_update_failed',
                        'AUTO-' . $order->id,
                        0,
                        'failed',
                        'Auto status update failed',
                        [
                            'order_id' => $order->id,
                            'api_order_id' => $order->api_order_id,
                        ],
                        null,
                        $e->getMessage()
                    );
                }
            }
        }
    }

    // Check Order Status (Manual)
    public function checkStatus($orderId)
    {
        $order = Auth::user()->orders()->findOrFail($orderId);

        if (!$order->api_order_id) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'No API order ID found for this order.'
            ]);
        }

        // Log status check initiation
        $this->logOrderAction(
            'status_check',
            'STATUS-' . $order->id,
            0,
            'success',
            'Order status check initiated',
            ['order_id' => $order->id, 'api_order_id' => $order->api_order_id],
            null
        );

        $status = $this->ogaviralService->getOrderStatus($order->api_order_id);

        if (isset($status['status'])) {
            $oldStatus = $order->status;
            
            // Map API status to our database status
            $apiStatus = $status['status'];
            $newStatus = $this->mapApiStatus($apiStatus);
            
            // Update local order status
            $order->update([
                'status' => $newStatus,
                'api_response' => json_encode($status),
            ]);

            // Log successful status check
            $this->logOrderAction(
                'status_updated',
                'STATUS-' . $order->id,
                0,
                'success',
                'Order status updated successfully',
                [
                    'order_id' => $order->id, 
                    'old_status' => $oldStatus,
                    'new_status' => $status['status']
                ],
                $status
            );

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'message' => 'Order status updated: ' . $status['status']
            ]);
        }

        // Log failed status check
        $this->logOrderAction(
            'status_check_failed',
            'STATUS-' . $order->id,
            0,
            'failed',
            'Failed to fetch order status',
            ['order_id' => $order->id, 'api_order_id' => $order->api_order_id],
            $status,
            'Status not found in response'
        );

        return redirect()->back()->with('alert', [
            'type' => 'error',
            'message' => 'Failed to fetch order status.'
        ]);
    }

    // Request Refill
    public function requestRefill($orderId)
    {
        $order = Auth::user()->orders()->findOrFail($orderId);

        if (!$order->api_order_id) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'No API order ID found for this order.'
            ]);
        }

        // Log refill request initiation
        $this->logOrderAction(
            'refill_request',
            'REFILL-' . $order->id,
            0,
            'success',
            'Refill request initiated',
            ['order_id' => $order->id, 'api_order_id' => $order->api_order_id],
            null
        );

        $refillResponse = $this->ogaviralService->createRefill($order->api_order_id);

        if (isset($refillResponse['refill'])) {
            // Log successful refill
            $this->logOrderAction(
                'refill_success',
                'REFILL-' . $order->id,
                0,
                'success',
                'Refill requested successfully',
                ['order_id' => $order->id, 'api_order_id' => $order->api_order_id],
                $refillResponse
            );

            return redirect()->back()->with('alert', [
                'type' => 'success',
                'message' => 'Refill requested successfully! Refill ID: ' . $refillResponse['refill']
            ]);
        }

        $errorMessage = $refillResponse['error'] ?? 'Failed to create refill';

        // Log failed refill
        $this->logOrderAction(
            'refill_failed',
            'REFILL-' . $order->id,
            0,
            'failed',
            'Refill request failed',
            ['order_id' => $order->id, 'api_order_id' => $order->api_order_id],
            $refillResponse,
            $errorMessage
        );

        return redirect()->back()->with('alert', [
            'type' => 'error',
            'message' => $errorMessage
        ]);
    }

    /**
     * Map Ogaviral API status to our database status
     */
    protected function mapApiStatus($apiStatus)
    {
        $statusMap = [
            'Pending' => 'pending',
            'In progress' => 'processing',
            'Processing' => 'processing',
            'Completed' => 'completed',
            'Partial' => 'partial',
            'Cancelled' => 'cancelled',
            'Canceled' => 'cancelled',
        ];
        
        return $statusMap[$apiStatus] ?? strtolower($apiStatus);
    }

    /**
     * Log order-related actions to the Logged model
     */
    protected function logOrderAction(
        $method, 
        $reference, 
        $amount, 
        $status, 
        $description, 
        $requestData = [], 
        $responseData = null, 
        $errorMessage = null
    ) {
        try {
            Logged::create([
                'user_id' => Auth::id(),
                'reference' => $reference,
                'type' => 'order',
                'method' => $method,
                'amount' => $amount,
                'status' => $status,
                'description' => $description,
                'request_data' => $requestData,
                'response_data' => $responseData,
                'error_message' => $errorMessage,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log order action: ' . $e->getMessage());
        }
    }
}