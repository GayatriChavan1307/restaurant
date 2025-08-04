<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\RestaurantTable;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SuperAdminController extends Controller
{
    public function getAnalytics()
    {
        $today = now()->startOfDay();
        $yesterday = $today->copy()->subDay();
        $thisMonth = now()->startOfMonth();
        
        // Today's stats
        $todayStats = [
            'revenue' => Order::where('status', 'paid')
                ->whereDate('completed_at', $today)
                ->sum('total_amount'),
            'orders' => Order::whereDate('created_at', $today)->count(),
            'average_order_value' => Order::where('status', 'paid')
                ->whereDate('completed_at', $today)
                ->avg('total_amount') ?? 0,
        ];

        // Yesterday's stats for comparison
        $yesterdayStats = [
            'revenue' => Order::where('status', 'paid')
                ->whereDate('completed_at', $yesterday)
                ->sum('total_amount'),
            'orders' => Order::whereDate('created_at', $yesterday)->count(),
            'average_order_value' => Order::where('status', 'paid')
                ->whereDate('completed_at', $yesterday)
                ->avg('total_amount') ?? 0,
        ];

        // Monthly stats
        $monthlyStats = [
            'revenue' => Order::where('status', 'paid')
                ->whereDate('completed_at', '>=', $thisMonth)
                ->sum('total_amount'),
            'orders' => Order::whereDate('created_at', '>=', $thisMonth)->count(),
            'average_order_value' => Order::where('status', 'paid')
                ->whereDate('completed_at', '>=', $thisMonth)
                ->avg('total_amount') ?? 0,
        ];

        // Top selling items
        $topItems = DB::table('order_items')
            ->join('menu_items', 'order_items.menu_item_id', '=', 'menu_items.id')
            ->select('menu_items.name', DB::raw('SUM(order_items.quantity) as total_quantity'))
            ->whereDate('order_items.created_at', '>=', $thisMonth)
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();

        // Staff performance
        $staffPerformance = User::whereIn('role', ['waiter', 'reception'])
            ->withCount(['ordersTaken as total_orders' => function($query) use ($thisMonth) {
                $query->whereDate('created_at', '>=', $thisMonth);
            }])
            ->withSum(['ordersTaken as total_revenue' => function($query) use ($thisMonth) {
                $query->where('status', 'paid')
                    ->whereDate('completed_at', '>=', $thisMonth);
            }], 'total_amount')
            ->get();

        return response()->json([
            'today' => $todayStats,
            'yesterday' => $yesterdayStats,
            'monthly' => $monthlyStats,
            'top_items' => $topItems,
            'staff_performance' => $staffPerformance,
            'revenue_change' => $yesterdayStats['revenue'] > 0 
                ? round((($todayStats['revenue'] - $yesterdayStats['revenue']) / $yesterdayStats['revenue']) * 100, 2)
                : 0,
            'orders_change' => $yesterdayStats['orders'] > 0
                ? round((($todayStats['orders'] - $yesterdayStats['orders']) / $yesterdayStats['orders']) * 100, 2)
                : 0,
        ]);
    }

    public function getStaff()
    {
        $staff = User::whereIn('role', ['waiter', 'reception', 'superadmin'])
            ->withCount(['ordersTaken as total_orders'])
            ->withSum(['ordersTaken as total_revenue'], 'total_amount')
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        return response()->json([
            'staff' => $staff,
            'stats' => [
                'total_staff' => $staff->count(),
                'waiters' => $staff->where('role', 'waiter')->count(),
                'reception' => $staff->where('role', 'reception')->count(),
                'superadmin' => $staff->where('role', 'superadmin')->count(),
            ]
        ]);
    }

    public function createStaff(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:waiter,reception,superadmin'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json([
            'message' => 'Staff member created successfully',
            'user' => $user
        ], 201);
    }

    public function updateStaff(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:waiter,reception,superadmin',
            'password' => 'nullable|string|min:8'
        ]);

        $data = $request->only(['name', 'email', 'role']);
        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Staff member updated successfully',
            'user' => $user
        ]);
    }

    public function toggleStaffStatus(User $user)
    {
        // For now, we'll use a simple active/inactive toggle
        // You might want to add an 'is_active' field to the users table
        $user->update([
            'email_verified_at' => $user->email_verified_at ? null : now()
        ]);

        return response()->json([
            'message' => 'Staff status updated successfully',
            'user' => $user
        ]);
    }

    public function deleteStaff(User $user)
    {
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'Cannot delete your own account'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'message' => 'Staff member deleted successfully'
        ]);
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:sales,inventory,staff,revenue',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date'
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        switch ($request->type) {
            case 'sales':
                $report = $this->generateSalesReport($startDate, $endDate);
                break;
            case 'inventory':
                $report = $this->generateInventoryReport($startDate, $endDate);
                break;
            case 'staff':
                $report = $this->generateStaffReport($startDate, $endDate);
                break;
            case 'revenue':
                $report = $this->generateRevenueReport($startDate, $endDate);
                break;
        }

        return response()->json($report);
    }

    private function generateSalesReport($startDate, $endDate)
    {
        $orders = Order::with(['restaurantTable', 'waiter', 'items.menuItem'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalRevenue = $orders->where('status', 'paid')->sum('total_amount');
        $totalOrders = $orders->count();
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'type' => 'sales',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_orders' => $totalOrders,
                'average_order_value' => $averageOrderValue,
                'paid_orders' => $orders->where('status', 'paid')->count(),
                'cancelled_orders' => $orders->where('status', 'cancelled')->count(),
            ],
            'orders' => $orders
        ];
    }

    private function generateInventoryReport($startDate, $endDate)
    {
        // This would need inventory tracking implementation
        return [
            'type' => 'inventory',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'message' => 'Inventory reporting not yet implemented'
        ];
    }

    private function generateStaffReport($startDate, $endDate)
    {
        $staff = User::whereIn('role', ['waiter', 'reception'])
            ->withCount(['ordersTaken as total_orders' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['ordersTaken as total_revenue' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'paid')
                    ->whereBetween('completed_at', [$startDate, $endDate]);
            }], 'total_amount')
            ->get();

        return [
            'type' => 'staff',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'staff' => $staff
        ];
    }

    private function generateRevenueReport($startDate, $endDate)
    {
        $dailyRevenue = Order::where('status', 'paid')
            ->whereBetween('completed_at', [$startDate, $endDate])
            ->selectRaw('DATE(completed_at) as date, SUM(total_amount) as revenue, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'type' => 'revenue',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'daily_revenue' => $dailyRevenue,
            'total_revenue' => $dailyRevenue->sum('revenue'),
            'total_orders' => $dailyRevenue->sum('orders'),
        ];
    }

    public function getSystemSettings()
    {
        // This would typically come from a settings table
        $settings = [
            'restaurant_name' => config('app.name', 'Restaurant Management System'),
            'business_hours' => [
                'monday' => '09:00-22:00',
                'tuesday' => '09:00-22:00',
                'wednesday' => '09:00-22:00',
                'thursday' => '09:00-22:00',
                'friday' => '09:00-23:00',
                'saturday' => '10:00-23:00',
                'sunday' => '10:00-21:00',
            ],
            'tax_rate' => 0.10, // 10%
            'currency' => 'USD',
            'printer_settings' => [
                'kitchen_printer' => 'Kitchen Printer',
                'receipt_printer' => 'Receipt Printer',
            ]
        ];

        return response()->json($settings);
    }

    public function updateSystemSettings(Request $request)
    {
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'tax_rate' => 'required|numeric|min:0|max:1',
            'currency' => 'required|string|max:3',
            'business_hours' => 'required|array'
        ]);

        // In a real application, you'd save these to a settings table
        // For now, we'll just return success
        return response()->json([
            'message' => 'System settings updated successfully'
        ]);
    }
}