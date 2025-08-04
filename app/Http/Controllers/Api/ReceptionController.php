<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;
use App\Models\Order;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ReceptionController extends Controller
{
    public function getTables()
    {
        $tables = RestaurantTable::with(['currentOrder.items'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'tables' => $tables,
            'stats' => [
                'total' => $tables->count(),
                'available' => $tables->where('status', 'available')->count(),
                'occupied' => $tables->where('status', 'occupied')->count(),
                'reserved' => $tables->where('status', 'reserved')->count(),
            ]
        ]);
    }

    public function getOrders()
    {
        $orders = Order::with(['restaurantTable', 'waiter', 'items.menuItem'])
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'orders' => $orders,
            'stats' => [
                'total' => $orders->count(),
                'pending' => $orders->where('status', 'pending')->count(),
                'confirmed' => $orders->where('status', 'confirmed')->count(),
                'preparing' => $orders->where('status', 'preparing')->count(),
                'ready' => $orders->where('status', 'ready')->count(),
            ]
        ]);
    }

    public function getNotifications()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => auth()->user()->notifications()->where('read_at', null)->count()
        ]);
    }

    public function getStats()
    {
        $today = now()->startOfDay();
        
        $stats = [
            'today_revenue' => Order::where('status', 'paid')
                ->whereDate('completed_at', $today)
                ->sum('total_amount'),
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'active_tables' => RestaurantTable::where('status', 'occupied')->count(),
            'total_tables' => RestaurantTable::count(),
            'pending_orders' => Order::whereIn('status', ['pending', 'confirmed'])->count(),
            'ready_orders' => Order::where('status', 'ready')->count(),
        ];

        return response()->json($stats);
    }

    public function generateBill(Request $request, Order $order)
    {
        $order->load(['restaurantTable', 'items.menuItem', 'waiter']);
        
        // Calculate tax and total
        $subtotal = $order->items->sum(function($item) {
            return $item->quantity * $item->price_at_order;
        });
        
        $tax = $subtotal * 0.10; // 10% tax
        $total = $subtotal + $tax;

        $bill = [
            'order_id' => $order->id,
            'table' => $order->restaurantTable->name,
            'waiter' => $order->waiter->name,
            'customer_count' => $order->customer_count,
            'items' => $order->items->map(function($item) {
                return [
                    'name' => $item->menuItem->name,
                    'quantity' => $item->quantity,
                    'price' => $item->price_at_order,
                    'total' => $item->quantity * $item->price_at_order
                ];
            }),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'completed_at' => $order->completed_at ? $order->completed_at->format('Y-m-d H:i:s') : null,
        ];

        return response()->json($bill);
    }

    public function markAsPaid(Request $request, Order $order)
    {
        $order->update([
            'status' => 'paid',
            'completed_at' => now()
        ]);

        // Update table status
        $order->restaurantTable->update(['status' => 'available']);

        // Create notification for waiter
        Notification::create([
            'user_id' => $order->user_id,
            'type' => 'order_paid',
            'title' => 'Order Paid',
            'message' => "Order #{$order->id} has been paid and table cleared.",
            'data' => ['order_id' => $order->id]
        ]);

        return response()->json([
            'message' => 'Order marked as paid successfully',
            'order' => $order->load(['restaurantTable', 'items.menuItem'])
        ]);
    }

    public function getMenuItems()
    {
        $menuItems = \App\Models\MenuItem::with('category')
            ->where('is_available', true)
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return response()->json($menuItems);
    }

    public function updateMenuItem(Request $request, \App\Models\MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_available' => 'boolean',
            'category_id' => 'required|exists:categories,id'
        ]);

        $menuItem->update($request->all());

        return response()->json([
            'message' => 'Menu item updated successfully',
            'menu_item' => $menuItem->load('category')
        ]);
    }

    public function createMenuItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image_path' => 'nullable|string'
        ]);

        $menuItem = \App\Models\MenuItem::create($request->all());

        return response()->json([
            'message' => 'Menu item created successfully',
            'menu_item' => $menuItem->load('category')
        ], 201);
    }

    public function deleteMenuItem(\App\Models\MenuItem $menuItem)
    {
        $menuItem->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully'
        ]);
    }
}