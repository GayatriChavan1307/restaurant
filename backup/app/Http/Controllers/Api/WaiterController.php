<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Events\OrderCreated;
use App\Events\OrderUpdated;
use App\Events\TableStatusChanged;

class WaiterController extends Controller
{
    public function getTables()
    {
        $tables = RestaurantTable::with(['currentOrder.orderItems.menuItem'])
            ->get()
            ->map(function ($table) {
                return [
                    'id' => $table->id,
                    'name' => $table->name,
                    'capacity' => $table->capacity,
                    'status' => $table->status,
                    'current_order' => $table->currentOrder ? [
                        'id' => $table->currentOrder->id,
                        'total_amount' => $table->currentOrder->total_amount,
                        'items_count' => $table->currentOrder->orderItems->count(),
                        'status' => $table->currentOrder->status
                    ] : null
                ];
            });

        return response()->json($tables);
    }

    public function getOrders()
    {
        $orders = Order::with(['restaurantTable', 'orderItems.menuItem'])
            ->where('user_id', auth()->id())
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($orders);
    }

    public function assignTable(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'customer_count' => 'required|integer|min:1|max:' . $table->capacity,
            'notes' => 'nullable|string'
        ]);

        // Create new order
        $order = Order::create([
            'restaurant_table_id' => $table->id,
            'user_id' => auth()->id(),
            'customer_count' => $request->customer_count,
            'status' => 'pending',
            'notes' => $request->notes,
            'total_amount' => 0
        ]);

        // Update table status
        $table->update(['status' => 'occupied']);

        // Broadcast events
        broadcast(new OrderCreated($order->load('restaurantTable', 'waiter')));
        broadcast(new TableStatusChanged($table->load('currentOrder')));

        return response()->json([
            'message' => 'Table assigned successfully',
            'order' => $order->load('restaurantTable', 'orderItems.menuItem')
        ]);
    }

    public function unassignTable(RestaurantTable $table)
    {
        $currentOrder = $table->currentOrder;
        
        if ($currentOrder) {
            $currentOrder->update(['status' => 'completed']);
        }

        $table->update(['status' => 'available']);

        broadcast(new TableStatusChanged($table));

        return response()->json(['message' => 'Table cleared successfully']);
    }

    public function addItemsToOrder(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|array',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price_at_order' => 'required|numeric|min:0'
        ]);

        foreach ($request->items as $itemData) {
            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $itemData['menu_item_id'],
                'quantity' => $itemData['quantity'],
                'price_at_order' => $itemData['price_at_order'],
                'status' => 'pending'
            ]);
        }

        $order->calculateTotal();
        $order->load('restaurantTable', 'orderItems.menuItem');

        broadcast(new OrderUpdated($order));

        return response()->json([
            'message' => 'Items added successfully',
            'order' => $order
        ]);
    }

    public function sendToKitchen(Order $order)
    {
        $order->update(['status' => 'confirmed']);
        
        // Mark items as sent to kitchen
        $order->orderItems()->update(['printed_to_kitchen' => true]);

        broadcast(new OrderUpdated($order->load('restaurantTable', 'orderItems.menuItem')));

        return response()->json(['message' => 'Order sent to kitchen']);
    }

    public function updateOrderItem(Request $request, Order $order, OrderItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'special_instructions' => 'nullable|string'
        ]);

        $item->update($request->only('quantity', 'special_instructions'));
        $order->calculateTotal();

        broadcast(new OrderUpdated($order->load('restaurantTable', 'orderItems.menuItem')));

        return response()->json(['message' => 'Item updated successfully']);
    }

    public function removeOrderItem(Order $order, OrderItem $item)
    {
        $item->delete();
        $order->calculateTotal();

        broadcast(new OrderUpdated($order->load('restaurantTable', 'orderItems.menuItem')));

        return response()->json(['message' => 'Item removed successfully']);
    }
}