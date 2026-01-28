<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Logged;
use App\Services\OgaviralService;
use Illuminate\Console\Command;

class UpdateOrderStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-statuses 
                            {--limit=50 : Maximum number of orders to check}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update statuses for pending and processing orders from Ogaviral API';

    protected $ogaviralService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(OgaviralService $ogaviralService)
    {
        parent::__construct();
        $this->ogaviralService = $ogaviralService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $limit = $this->option('limit');
        
        $this->info('Starting order status update...');
        
        // Get pending and processing orders with API order IDs
        $orders = Order::whereIn('status', ['pending', 'processing'])
            ->whereNotNull('api_order_id')
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        if ($orders->isEmpty()) {
            $this->info('No pending or processing orders found.');
            return 0;
        }
        
        $this->info("Found {$orders->count()} orders to check.");
        
        $updated = 0;
        $failed = 0;
        $unchanged = 0;
        
        $progressBar = $this->output->createProgressBar($orders->count());
        $progressBar->start();
        
        foreach ($orders as $order) {
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
                        
                        // Log the update
                        $this->logOrderAction(
                            'cron_status_update',
                            'CRON-' . $order->id,
                            0,
                            'success',
                            'Order status updated via cron from ' . $oldStatus . ' to ' . $newStatus,
                            [
                                'order_id' => $order->id,
                                'api_order_id' => $order->api_order_id,
                                'old_status' => $oldStatus,
                                'new_status' => $newStatus,
                            ],
                            $status,
                            $order->user_id
                        );
                        
                        $updated++;
                    } else {
                        $unchanged++;
                    }
                } else {
                    $failed++;
                }
                
                // Small delay to avoid rate limiting
                usleep(200000); // 0.2 seconds
                
            } catch (\Exception $e) {
                $failed++;
                
                \Log::error('Cron status update failed for order ' . $order->id . ': ' . $e->getMessage());
                
                $this->logOrderAction(
                    'cron_status_update_failed',
                    'CRON-' . $order->id,
                    0,
                    'failed',
                    'Cron status update failed',
                    [
                        'order_id' => $order->id,
                        'api_order_id' => $order->api_order_id,
                    ],
                    null,
                    $e->getMessage(),
                    $order->user_id
                );
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        // Summary
        $this->info("Status update complete!");
        $this->table(
            ['Result', 'Count'],
            [
                ['Updated', $updated],
                ['Unchanged', $unchanged],
                ['Failed', $failed],
                ['Total Checked', $orders->count()],
            ]
        );
        
        return 0;
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
        $errorMessage = null,
        $userId = null
    ) {
        try {
            Logged::create([
                'user_id' => $userId,
                'reference' => $reference,
                'type' => 'order',
                'method' => $method,
                'amount' => $amount,
                'status' => $status,
                'description' => $description,
                'request_data' => $requestData,
                'response_data' => $responseData,
                'error_message' => $errorMessage,
                'ip_address' => '127.0.0.1', // Cron job
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to log order action: ' . $e->getMessage());
        }
    }
}