<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class OrderPlaced extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // Save to DB
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => "Your order for {$this->order->quantity} {$this->order->service_name} has been placed successfully.",
            'link' => route('orders.index'),
            'icon' => 'check-circle', // For the header dropdown icon
        ];
    }
}