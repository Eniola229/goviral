<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Services\OgaviralService;
use App\Models\Logged;

class DashboardController extends Controller
{
    protected $ogaviralService;

    public function __construct(OgaviralService $ogaviralService)
    {
        $this->ogaviralService = $ogaviralService;
    }

    public function index()
    {
        $user = auth()->user();
        
        // 3. Recent Orders (Last 5) - Get before stats
        $recentOrders = $user->orders()->latest()->limit(5)->get();
        
        // Auto-update recent orders status
        $this->autoUpdateOrderStatuses($recentOrders);
        
        // 1. Wallet Balance
        $balance = $user->balance;
        
        // 2. Order Statistics (Fresh after update)
        $totalOrders = $user->orders()->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $processingOrders = $user->orders()->where('status', 'processing')->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $totalSpent = $user->orders()->sum('charge');
        
        return view('dashboard', compact(
            'balance', 
            'totalOrders', 
            'completedOrders', 
            'processingOrders',
            'pendingOrders',
            'totalSpent', 
            'recentOrders'
        ));
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
                                'dashboard_auto_update',
                                'DASH-' . $order->id,
                                0,
                                'success',
                                'Order status auto-updated on dashboard from ' . $oldStatus . ' to ' . $newStatus,
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
                    \Log::error('Dashboard auto status update failed for order ' . $order->id . ': ' . $e->getMessage());
                    
                    $this->logOrderAction(
                        'dashboard_auto_update_failed',
                        'DASH-' . $order->id,
                        0,
                        'failed',
                        'Dashboard auto status update failed',
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
                'user_id' => auth()->id(),
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