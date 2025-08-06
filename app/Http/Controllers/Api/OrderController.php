<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\MenuItem;
use App\Models\RestaurantTable;
use App\Models\KitchenPrint;
use App\Models\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['restaurantTable', 'waiter', 'items.menuItem'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $request->validate([
            'restaurant_table_id' => 'required|exists:restaurant_tables,id',
            'customer_count' => 'required|integer|min:1',
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        // Check if table is available
        $table = RestaurantTable::find($request->restaurant_table_id);
        if ($table->status !== 'available') {
            return response()->json([
                'message' => 'Table is not available'
            ], 400);
        }

        // Create order
        $order = Order::create([
            'restaurant_table_id' => $request->restaurant_table_id,
            'user_id' => auth()->id(),
            'customer_count' => $request->customer_count,
            'status' => 'pending',
            'notes' => $request->notes ?? ''
        ]);

        // Add order items
        $totalAmount = 0;
        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price_at_order' => $menuItem->price,
                'status' => 'pending'
            ]);
            $totalAmount += $menuItem->price * $item['quantity'];
        }

        // Update order total
        $order->update(['total_amount' => $totalAmount]);

        // Update table status
        $table->update(['status' => 'occupied']);

        // Create kitchen print
        KitchenPrint::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'new_order',
            'printed_at' => now(),
        ]);

        // Notify reception
        $receptionUser = User::where('role', 'reception')->first();
        if ($receptionUser) {
            Notification::create([
                'user_id' => $receptionUser->id,
                'type' => 'new_order',
                'title' => 'New Order',
                'message' => "New order #{$order->id} created for table {$table->name}",
                'data' => ['order_id' => $order->id, 'table_id' => $table->id]
            ]);
        }

        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order->load(['restaurantTable', 'items.menuItem', 'waiter'])
        ], 201);
    }

    public function show(Order $order)
    {
        return response()->json($order->load(['restaurantTable', 'waiter', 'items.menuItem']));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'sometimes|in:pending,confirmed,preparing,ready,served,paid,cancelled',
            'notes' => 'sometimes|string',
            'customer_count' => 'sometimes|integer|min:1'
        ]);

        $order->update($request->all());

        // If order is cancelled, free up the table
        if ($request->status === 'cancelled') {
            $order->restaurantTable->update(['status' => 'available']);
        }

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order->load(['restaurantTable', 'items.menuItem', 'waiter'])
        ]);
    }

    public function destroy(Order $order)
    {
        // Only allow deletion of pending orders
        if ($order->status !== 'pending') {
            return response()->json([
                'message' => 'Cannot delete order that is not pending'
            ], 400);
        }

        // Free up the table
        $order->restaurantTable->update(['status' => 'available']);

        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }

    public function addItems(Request $request, Order $order)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.menu_item_id' => 'required|exists:menu_items,id',
            'items.*.quantity' => 'required|integer|min:1'
        ]);

        $newItems = [];
        $totalAmount = $order->total_amount;

        foreach ($request->items as $item) {
            $menuItem = MenuItem::find($item['menu_item_id']);
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'price_at_order' => $menuItem->price,
                'status' => 'pending',
                'printed_to_kitchen' => false // New items need to be printed
            ]);
            
            $totalAmount += $menuItem->price * $item['quantity'];
            $newItems[] = $orderItem;
        }

        // Update order total
        $order->update(['total_amount' => $totalAmount]);

        // Create kitchen print for new items only
        KitchenPrint::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'add_items',
            'printed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Items added to order successfully',
            'new_items' => $newItems,
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function updateItem(Request $request, Order $order, OrderItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'status' => 'sometimes|in:pending,preparing,ready,cancelled'
        ]);

        $oldQuantity = $item->quantity;
        $item->update($request->all());

        // Recalculate order total if quantity changed
        if ($oldQuantity !== $request->quantity) {
            $order->calculateTotal();
        }

        return response()->json([
            'message' => 'Order item updated successfully',
            'item' => $item->load('menuItem'),
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function removeItem(Request $request, Order $order, OrderItem $item)
    {
        $item->delete();

        // Recalculate order total
        $order->calculateTotal();

        // If no items left, cancel the order
        if ($order->items()->count() === 0) {
            $order->update(['status' => 'cancelled']);
            $order->restaurantTable->update(['status' => 'available']);
        }

        return response()->json([
            'message' => 'Item removed from order successfully',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function sendToKitchen(Request $request, Order $order)
    {
        // Mark unprinted items as printed
        $order->items()
            ->where('printed_to_kitchen', false)
            ->where('status', '!=', 'cancelled')
            ->update(['printed_to_kitchen' => true]);

        // Update order status
        $order->update(['status' => 'confirmed']);

        // Create kitchen print
        KitchenPrint::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'send_to_kitchen',
            'printed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Order sent to kitchen successfully',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function cancelOrder(Request $request, Order $order)
    {
        $order->update(['status' => 'cancelled']);
        
        // Free up the table
        $order->restaurantTable->update(['status' => 'available']);

        // Create cancellation print
        KitchenPrint::create([
            'order_id' => $order->id,
            'user_id' => auth()->id(),
            'type' => 'cancellation',
            'printed_at' => now(),
        ]);

        // Notify reception
        $receptionUser = User::where('role', 'reception')->first();
        if ($receptionUser) {
            Notification::create([
                'user_id' => $receptionUser->id,
                'type' => 'order_cancelled',
                'title' => 'Order Cancelled',
                'message' => "Order #{$order->id} has been cancelled",
                'data' => ['order_id' => $order->id]
            ]);
        }

        return response()->json([
            'message' => 'Order cancelled successfully',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }
}