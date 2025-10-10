<?php

namespace App\Events;

use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\User; // Import User model
use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TableAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $table;
    public $order;
    public $message;
    public $link;
    public $created_at;

    public function __construct(RestaurantTable $table, Order $order)
    {
        $this->table = $table;
        $this->order = $order;
        $this->message = "Table {$table->name} has been assigned to Order #{$order->id} with {$order->customer_count} customers.";
        $this->link = route('reception.bill.generate', $order->id);
        $this->created_at = now()->toDateTimeString();

        // Store notification
        $receptionUsers = User::where('role', 'reception')->get();
        if ($receptionUsers->isEmpty()) {
            \Log::warning('No reception users found to notify for table assignment: Table #' . $table->id);
        }
        foreach ($receptionUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'message' => $this->message,
                'link' => $this->link,
                'is_read' => false,
                'type' => 'table_assigned', // Add if column exists
            ]);
        }
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('reception-notifications'),
        ];
    }
}