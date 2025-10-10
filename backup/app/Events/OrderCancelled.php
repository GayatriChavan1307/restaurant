<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCancelled implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $message;
    public $link;
    public $created_at;

    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->message = "Order #{$order->id} for Table {$order->restaurantTable->name} has been cancelled.";
        $this->link = route('reception.bill.generate', $order->id);
        $this->created_at = now()->toDateTimeString();
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('reception-notifications'),
        ];
    }
}