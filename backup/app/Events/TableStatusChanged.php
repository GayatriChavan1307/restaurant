<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\RestaurantTable;

class TableStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $table;

    public function __construct(RestaurantTable $table)
    {
        $this->table = $table;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('restaurant-updates'),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'table' => $this->table
        ];
    }
}