<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\KitchenPrint;
use App\Models\Notification;
use App\Models\User;

class KitchenController extends Controller
{
    public function getOrders()
    {
        $orders = Order::with(['restaurantTable', 'waiter', 'items.menuItem'])
            ->whereIn('status', ['confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'orders' => $orders,
            'stats' => [
                'total' => $orders->count(),
                'confirmed' => $orders->where('status', 'confirmed')->count(),
                'preparing' => $orders->where('status', 'preparing')->count(),
                'ready' => $orders->where('status', 'ready')->count(),
            ]
        ]);
    }

    public function startPreparing(Request $request, Order $order)
    {
        $order->update(['status' => 'preparing']);

        // Create kitchen print record
        KitchenPrint::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'start_preparing',
            'printed_at' => now(),
        ]);

        // Notify reception
        Notification::create([
            'user_id' => User::where('role', 'reception')->first()->id ?? 1,
            'type' => 'order_status',
            'title' => 'Order Started',
            'message' => "Order #{$order->id} is now being prepared.",
            'data' => ['order_id' => $order->id, 'status' => 'preparing']
        ]);

        return response()->json([
            'message' => 'Order marked as preparing',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function markAsReady(Request $request, Order $order)
    {
        $order->update(['status' => 'ready']);

        // Create kitchen print record
        KitchenPrint::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'ready',
            'printed_at' => now(),
        ]);

        // Notify waiter
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_ready',
            'title' => 'Order Ready',
            'message' => "Order #{$order->id} is ready to serve.",
            'data' => ['order_id' => $order->id]
        ]);

        return response()->json([
            'message' => 'Order marked as ready',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function markAsServed(Request $request, Order $order)
    {
        $order->update(['status' => 'served']);

        return response()->json([
            'message' => 'Order marked as served',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function updateItemStatus(Request $request, Order $order, OrderItem $item)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,ready,cancelled'
        ]);

        $item->update(['status' => $request->status]);

        // Check if all items are ready
        $allItemsReady = $order->items()
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'ready')
            ->count() === 0;

        if ($allItemsReady && $order->status !== 'ready') {
            $order->update(['status' => 'ready']);
        }

        return response()->json([
            'message' => 'Item status updated',
            'item' => $item->load('menuItem'),
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'note' => 'required|string|max:500'
        ]);

        $order->update(['notes' => $order->notes . "\n" . now()->format('H:i') . ": " . $request->note]);

        return response()->json([
            'message' => 'Note added to order',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function reportIssue(Request $request, Order $order)
    {
        $request->validate([
            'issue' => 'required|string|max:500'
        ]);

        // Create notification for reception
        Notification::create([
            'user_id' => User::where('role', 'reception')->first()->id ?? 1,
            'type' => 'kitchen_issue',
            'title' => 'Kitchen Issue',
            'message' => "Issue with Order #{$order->id}: {$request->issue}",
            'data' => ['order_id' => $order->id, 'issue' => $request->issue]
        ]);

        return response()->json([
            'message' => 'Issue reported successfully'
        ]);
    }

    public function getKitchenPrints()
    {
        $prints = KitchenPrint::with(['order.restaurantTable', 'order.waiter'])
            ->whereDate('created_at', today())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'prints' => $prints,
            'today_count' => $prints->count()
        ]);
    }
}