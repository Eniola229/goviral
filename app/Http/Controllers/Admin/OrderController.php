<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Logged;
use App\Models\User;
use Illuminate\Http\Request;
use App\Traits\LogsAdminActivity;

class OrderController extends Controller
{
    use LogsAdminActivity;

    protected $ogaviralService;

    public function __construct()
    {
        $this->ogaviralService = app(\App\Services\OgaviralService::class);
    }

    /**
     * Display all orders with AUTO STATUS UPDATE
     */
    public function index(Request $request)
    {
        $query = Order::with('user');

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhere('service_name', 'like', "%{$search}%")
                  ->orWhere('link', 'like', "%{$search}%")
                  ->orWhere('api_order_id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->has('amount_min') && $request->amount_min) {
            $query->where('charge', '>=', $request->amount_min);
        }
        if ($request->has('amount_max') && $request->amount_max) {
            $query->where('charge', '<=', $request->amount_max);
        }

        // Pagination
        $orders = $query->latest()->paginate(20)->withQueryString();

        // AUTO-UPDATE ORDER STATUSES
        $this->autoUpdateOrderStatuses($orders);

        // Statistics
        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::where('status', 'cancelled')->count();
        $totalRevenue = Order::where('status', 'completed')->sum('charge');

        // Log the view
        $this->logActivity(
            'viewed',
            auth('admin')->user()->name . ' viewed orders list',
            'Order',
            null
        );

        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'pendingOrders',
            'processingOrders',
            'completedOrders',
            'cancelledOrders',
            'totalRevenue'
        ));
    }

    /**
     * Show single order with AUTO STATUS UPDATE
     */
    public function show($id)
    {
        $order = Order::with('user')->findOrFail($id);
        
        // AUTO-UPDATE THIS ORDER'S STATUS
        $this->autoUpdateSingleOrder($order);
        
        // Refresh order data after update
        $order->refresh();
        
        // Get logs related to this order
        $logs = Logged::where('user_id', $order->user_id)
            ->where(function($q) use ($order) {
                $q->where('reference', $order->id)
                  ->orWhere('description', 'like', "%Order #" . substr($order->id, 0, 8) . "%");
            })
            ->latest()
            ->paginate(10);

        // Get customer's wallet balance
        $latestWallet = Wallet::where('user_id', $order->user_id)
            ->orderBy('created_at', 'desc')
            ->first();
        $customerBalance = $latestWallet ? $latestWallet->balance_after : 0;

        // Log the view
        $this->logViewed(
            'Order',
            $order->id,
            auth('admin')->user()->name . ' viewed Order #' . substr($order->id, 0, 8)
        );

        return view('admin.orders.show', compact('order', 'logs', 'customerBalance'));
    }

    /**
     * Manual status check (triggered by button click)
     */
    public function checkStatus($id)
    {
        $order = Order::with('user')->findOrFail($id);

        if (!$order->api_order_id) {
            return back()->with('error', 'No API order ID found for this order.');
        }

        try {
            $status = $this->ogaviralService->getOrderStatus($order->api_order_id);

            if (isset($status['status'])) {
                $oldStatus = $order->status;
                $apiStatus = $status['status'];
                $newStatus = $this->mapApiStatus($apiStatus);

                // Check if order should be auto-refunded
                if ($this->shouldAutoRefund($oldStatus, $newStatus)) {
                    $this->processAutoRefund($order, $oldStatus, $newStatus);
                    return back()->with('success', 'Order cancelled and refunded automatically. ₦' . number_format($order->charge, 2) . ' has been credited to customer\'s wallet.');
                }

                // Update order
                $order->update([
                    'status' => $newStatus,
                    'api_response' => json_encode($status),
                ]);

                // Log in admin logs
                $this->logUpdated(
                    'Order',
                    $order->id,
                    auth('admin')->user()->name . ' manually checked status for Order #' . substr($order->id, 0, 8) . ' - Updated from ' . $oldStatus . ' to ' . $newStatus,
                    [
                        'status' => [
                            'old' => $oldStatus,
                            'new' => $newStatus
                        ],
                        'api_status' => $apiStatus
                    ]
                );

                // Log in customer logs
                Logged::create([
                    'user_id' => $order->user_id,
                    'reference' => $order->id,
                    'type' => 'order',
                    'method' => 'manual_status_check',
                    'amount' => $order->charge,
                    'status' => 'success',
                    'description' => "Order #" . substr($order->id, 0, 8) . " status manually checked by admin: " . auth('admin')->user()->name . " - Status: {$newStatus}",
                    'ip_address' => request()->ip(),
                ]);

                return back()->with('success', 'Order status updated successfully: ' . ucfirst($newStatus));
            }

            return back()->with('error', 'Failed to fetch order status from API.');

        } catch (\Exception $e) {
            \Log::error('Manual status check failed for order ' . $order->id . ': ' . $e->getMessage());
            
            return back()->with('error', 'Failed to check order status: ' . $e->getMessage());
        }
    }

    /**
     * Auto-update order statuses (multiple orders)
     */
    protected function autoUpdateOrderStatuses($orders)
    {
        foreach ($orders as $order) {
            if (in_array($order->status, ['pending', 'processing']) && $order->api_order_id) {
                try {
                    $status = $this->ogaviralService->getOrderStatus($order->api_order_id);
                    
                    if (isset($status['status'])) {
                        $apiStatus = $status['status'];
                        $newStatus = $this->mapApiStatus($apiStatus);
                        
                        if ($order->status !== $newStatus) {
                            $oldStatus = $order->status;
                            
                            // Check if order should be auto-refunded
                            if ($this->shouldAutoRefund($oldStatus, $newStatus)) {
                                $this->processAutoRefund($order, $oldStatus, $newStatus);
                                continue;
                            }
                            
                            $order->update([
                                'status' => $newStatus,
                                'api_response' => json_encode($status),
                            ]);
                            
                            // Log auto-update in admin logs
                            $this->logActivity(
                                'auto_status_update',
                                'System auto-updated Order #' . substr($order->id, 0, 8) . ' from ' . $oldStatus . ' to ' . $newStatus,
                                'Order',
                                $order->id,
                                [
                                    'status' => [
                                        'old' => $oldStatus,
                                        'new' => $newStatus
                                    ],
                                    'api_order_id' => $order->api_order_id
                                ]
                            );

                            // Log in customer logs
                            Logged::create([
                                'user_id' => $order->user_id,
                                'reference' => $order->id,
                                'type' => 'order',
                                'method' => 'auto_status_update',
                                'amount' => $order->charge,
                                'status' => 'completed',
                                'description' => "Order #" . substr($order->id, 0, 8) . " status auto-updated from {$oldStatus} to {$newStatus}",
                                'ip_address' => request()->ip(),
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    \Log::error('Auto status update failed for order ' . $order->id . ': ' . $e->getMessage());
                }
            }
        }
    }

    /**
     * Auto-update single order status
     */
    protected function autoUpdateSingleOrder($order)
    {
        if (in_array($order->status, ['pending', 'processing']) && $order->api_order_id) {
            try {
                $status = $this->ogaviralService->getOrderStatus($order->api_order_id);
                
                if (isset($status['status'])) {
                    $apiStatus = $status['status'];
                    $newStatus = $this->mapApiStatus($apiStatus);
                    
                    if ($order->status !== $newStatus) {
                        $oldStatus = $order->status;
                        
                        // Check if order should be auto-refunded
                        if ($this->shouldAutoRefund($oldStatus, $newStatus)) {
                            $this->processAutoRefund($order, $oldStatus, $newStatus);
                            return;
                        }
                        
                        $order->update([
                            'status' => $newStatus,
                            'api_response' => json_encode($status),
                        ]);
                        
                        // Log auto-update
                        $this->logActivity(
                            'auto_status_update',
                            'System auto-updated Order #' . substr($order->id, 0, 8) . ' from ' . $oldStatus . ' to ' . $newStatus,
                            'Order',
                            $order->id,
                            [
                                'status' => [
                                    'old' => $oldStatus,
                                    'new' => $newStatus
                                ]
                            ]
                        );

                        // Log in customer logs
                        Logged::create([
                            'user_id' => $order->user_id,
                            'reference' => $order->id,
                            'type' => 'order',
                            'method' => 'auto_status_update',
                            'amount' => $order->charge,
                            'status' => 'completed',
                            'description' => "Order #" . substr($order->id, 0, 8) . " status auto-updated from {$oldStatus} to {$newStatus}",
                            'ip_address' => request()->ip(),
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Auto status update failed for order ' . $order->id . ': ' . $e->getMessage());
            }
        }
    }

    /**
     * Check if order should be auto-refunded
     */
    protected function shouldAutoRefund($oldStatus, $newStatus)
    {
        // If old status was pending or processing and new status is cancelled
        return in_array($oldStatus, ['pending', 'processing']) && $newStatus === 'cancelled';
    }

    /**
     * Process automatic refund
     */
    protected function processAutoRefund($order, $oldStatus, $newStatus)
    {
        try {
            // Get user's current balance
            $user = $order->user;
            $latestWallet = Wallet::where('user_id', $order->user_id)
                ->orderBy('created_at', 'desc')
                ->first();
            $currentBalance = $latestWallet ? $latestWallet->balance_after : 0;

            // Create refund wallet transaction
            $wallet = Wallet::create([
                'user_id' => $order->user_id,
                'balance_before' => $currentBalance,
                'amount' => $order->charge,
                'balance_after' => $currentBalance + $order->charge,
                'type' => 'credit',
                'description' => "Auto-refund for Order #" . substr($order->id, 0, 8) . " - Order cancelled by provider",
                'reference' => 'AUTO-REFUND-' . $order->id,
                'payment_method' => 'refund',
                'status' => 'success',
            ]);

            // Update user balance
            $user->increment('balance', $order->charge);

            // Update order status
            $order->update(['status' => 'cancelled']);

            // Log the auto-refund in admin logs
            $this->logActivity(
                'auto_refunded',
                'System auto-refunded Order #' . substr($order->id, 0, 8) . ' - ₦' . number_format($order->charge, 2) . ' refunded to ' . $order->user->name . ' (Status changed from ' . $oldStatus . ' to cancelled)',
                'Order',
                $order->id,
                [
                    'refund_amount' => $order->charge,
                    'order_status_changed' => [
                        'old' => $oldStatus,
                        'new' => 'cancelled'
                    ],
                    'refund_type' => 'automatic'
                ]
            );

            // Log in customer's activity log
            Logged::create([
                'user_id' => $order->user_id,
                'reference' => $wallet->reference,
                'type' => 'wallet',
                'method' => 'auto_refund',
                'amount' => $order->charge,
                'status' => 'success',
                'description' => "Auto-refund processed for Order #" . substr($order->id, 0, 8) . " - Order was cancelled by provider",
                'ip_address' => request()->ip(),
            ]);

            \Log::info('Auto-refund processed for order ' . $order->id . ' - Amount: ₦' . number_format($order->charge, 2));

        } catch (\Exception $e) {
            \Log::error('Auto-refund failed for order ' . $order->id . ': ' . $e->getMessage());
        }
    }

    /**
     * Map API status to database status
     */
    protected function mapApiStatus($apiStatus)
    {
        $statusMap = [
            'Pending' => 'pending',
            'In progress' => 'processing',
            'Processing' => 'processing',
            'Completed' => 'completed',
            'Partial' => 'completed', // Map partial to completed
            'Cancelled' => 'cancelled',
            'Canceled' => 'cancelled',
        ];
        
        return $statusMap[$apiStatus] ?? strtolower($apiStatus);
    }

    /**
     * Update order status (Super Admin & Accountant only)
     */
    public function updateStatus(Request $request, $id)
    {
        if (!auth('admin')->user()->canEditOrders()) {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::with('user')->findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled,refunded',
        ]);

        $oldStatus = $order->status;
        $order->update(['status' => $request->status]);

        // Log the status change in admin logs
        $this->logUpdated(
            'Order',
            $order->id,
            auth('admin')->user()->name . ' updated Order #' . substr($order->id, 0, 8) . ' status from ' . $oldStatus . ' to ' . $request->status,
            [
                'status' => [
                    'old' => $oldStatus,
                    'new' => $request->status
                ]
            ]
        );

        // Log in customer's activity log
        Logged::create([
            'user_id' => $order->user_id,
            'reference' => $order->id,
            'type' => 'order',
            'method' => 'status_update',
            'amount' => $order->charge,
            'status' => 'success',
            'description' => "Order #" . substr($order->id, 0, 8) . " status changed from {$oldStatus} to {$request->status} by admin: " . auth('admin')->user()->name,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Order status updated successfully');
    }

    /**
     * Refund order (Super Admin & Accountant only)
     */
    public function refund(Request $request, $id)
    {
        if (!auth('admin')->user()->canEditOrders()) {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::with('user')->findOrFail($id);

        if ($order->status === 'completed') {
            return back()->with('error', 'Cannot refund completed orders.');
        }

        // Get user's current balance
        $user = $order->user;
        $latestWallet = Wallet::where('user_id', $order->user_id)
            ->orderBy('created_at', 'desc')
            ->first();
        $currentBalance = $latestWallet ? $latestWallet->balance_after : 0;

        // Refund to wallet
        $wallet = Wallet::create([
            'user_id' => $order->user_id,
            'balance_before' => $currentBalance,
            'amount' => $order->charge,
            'balance_after' => $currentBalance + $order->charge,
            'type' => 'credit',
            'description' => "Refund for Order #" . substr($order->id, 0, 8) . " - " . $order->service_name,
            'reference' => 'REFUND-' . $order->id,
            'payment_method' => 'refund',
            'status' => 'success',
        ]);

        // Update user balance
        $user->increment('balance', $order->charge);

        // Update order status
        $oldStatus = $order->status;
        $order->update(['status' => 'cancelled']);

        // Log the refund in admin logs
        $this->logActivity(
            'refunded',
            auth('admin')->user()->name . ' refunded Order #' . substr($order->id, 0, 8) . ' - ₦' . number_format($order->charge, 2) . ' refunded to ' . $order->user->name,
            'Order',
            $order->id,
            [
                'refund_amount' => $order->charge,
                'order_status_changed' => [
                    'old' => $oldStatus,
                    'new' => 'cancelled'
                ]
            ]
        );

        // Log in customer's activity log
        Logged::create([
            'user_id' => $order->user_id,
            'reference' => $wallet->reference,
            'type' => 'wallet',
            'method' => 'refund',
            'amount' => $order->charge,
            'status' => 'success',
            'description' => "Refund processed for Order #" . substr($order->id, 0, 8) . " by admin: " . auth('admin')->user()->name,
            'ip_address' => $request->ip(),
        ]);

        return back()->with('success', 'Order refunded successfully. ₦' . number_format($order->charge, 2) . ' has been credited to customer\'s wallet.');
    }

    /**
     * Delete order (Super Admin & Accountant only)
     */
    public function destroy($id)
    {
        if (!auth('admin')->user()->canDeleteOrders()) {
            abort(403, 'Unauthorized action.');
        }

        $order = Order::with('user')->findOrFail($id);

        if (in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'Cannot delete active orders. Please cancel or complete the order first.');
        }

        $orderData = [
            'id' => $order->id,
            'service_name' => $order->service_name,
            'charge' => $order->charge,
            'user_name' => $order->user->name,
        ];

        // Log the deletion
        $this->logDeleted(
            'Order',
            $order->id,
            auth('admin')->user()->name . ' deleted Order #' . substr($order->id, 0, 8) . ' for ' . $order->user->name
        );

        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully');
    }
}