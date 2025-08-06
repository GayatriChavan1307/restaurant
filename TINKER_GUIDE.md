# 🔧 Laravel Tinker Guide

## Overview

Laravel Tinker is a powerful REPL (Read-Eval-Print Loop) for Laravel that allows you to interact with your application's models, run commands, and debug your code interactively. This guide covers all essential Tinker operations for the Restaurant Management System.

## 🚀 Getting Started

### Start Tinker
```bash
php artisan tinker
```

### Exit Tinker
```bash
exit
# or
quit
# or
Ctrl + C
```

## 👥 User Management

### View All Users
```php
// Get all users
App\Models\User::all();

// Get users with specific role
App\Models\User::where('role', 'waiter')->get();

// Get user by email
App\Models\User::where('email', 'waiter1@restaurant.com')->first();
```

### Create New User
```php
// Create a new waiter
App\Models\User::create([
    'name' => 'New Waiter',
    'email' => 'newwaiter@restaurant.com',
    'password' => Hash::make('password'),
    'role' => 'waiter',
    'email_verified_at' => now()
]);

// Create reception staff
App\Models\User::create([
    'name' => 'New Reception',
    'email' => 'newreception@restaurant.com',
    'password' => Hash::make('password'),
    'role' => 'reception',
    'email_verified_at' => now()
]);
```

### Update User
```php
// Find and update user
$user = App\Models\User::find(1);
$user->update(['name' => 'Updated Name']);

// Update password
$user = App\Models\User::where('email', 'waiter1@restaurant.com')->first();
$user->update(['password' => Hash::make('newpassword')]);

// Change user role
$user->update(['role' => 'reception']);
```

### Delete User
```php
// Delete by ID
App\Models\User::find(1)->delete();

// Delete by email
App\Models\User::where('email', 'test@example.com')->delete();
```

### User Role Operations
```php
// Check user roles
$user = App\Models\User::find(1);
$user->isWaiter();      // Returns true/false
$user->isReception();   // Returns true/false
$user->isSuperAdmin();  // Returns true/false
$user->isKitchen();     // Returns true/false

// Get all users by role
$waiters = App\Models\User::where('role', 'waiter')->get();
$reception = App\Models\User::where('role', 'reception')->get();
$admins = App\Models\User::where('role', 'superadmin')->get();
```

## 🍽️ Menu Management

### View Menu Items
```php
// Get all menu items
App\Models\MenuItem::all();

// Get items by category
App\Models\MenuItem::where('category_id', 1)->get();

// Get available items only
App\Models\MenuItem::where('is_available', true)->get();

// Get items with category info
App\Models\MenuItem::with('category')->get();
```

### Create Menu Item
```php
// Create new menu item
App\Models\MenuItem::create([
    'category_id' => 1,
    'name' => 'New Dish',
    'description' => 'Delicious new dish',
    'price' => 15.99,
    'is_available' => true
]);
```

### Update Menu Item
```php
// Update item price
$item = App\Models\MenuItem::find(1);
$item->update(['price' => 18.99]);

// Toggle availability
$item->update(['is_available' => !$item->is_available]);
```

### Menu Categories
```php
// Get all categories
App\Models\Category::all();

// Get category with items
App\Models\Category::with('menuItems')->find(1);

// Create new category
App\Models\Category::create([
    'name' => 'New Category',
    'description' => 'Category description',
    'sort_order' => 5
]);
```

## 🪑 Table Management

### View Tables
```php
// Get all tables
App\Models\RestaurantTable::all();

// Get available tables
App\Models\RestaurantTable::where('status', 'available')->get();

// Get occupied tables
App\Models\RestaurantTable::where('status', 'occupied')->get();

// Get tables with current orders
App\Models\RestaurantTable::with('currentOrder')->get();
```

### Create Table
```php
// Create new table
App\Models\RestaurantTable::create([
    'name' => 'Table 15',
    'capacity' => 4,
    'status' => 'available',
    'visual_coordinates' => json_encode(['x' => 700, 'y' => 400, 'width' => 120, 'height' => 100])
]);
```

### Update Table Status
```php
// Assign table
$table = App\Models\RestaurantTable::find(1);
$table->update(['status' => 'occupied']);

// Free table
$table->update(['status' => 'available']);
```

## 📋 Order Management

### View Orders
```php
// Get all orders
App\Models\Order::all();

// Get active orders
App\Models\Order::whereNotIn('status', ['paid', 'cancelled'])->get();

// Get orders by status
App\Models\Order::where('status', 'pending')->get();
App\Models\Order::where('status', 'confirmed')->get();
App\Models\Order::where('status', 'ready')->get();

// Get orders with details
App\Models\Order::with(['restaurantTable', 'waiter', 'items.menuItem'])->get();
```

### Create Order
```php
// Create new order
$order = App\Models\Order::create([
    'restaurant_table_id' => 1,
    'user_id' => 3, // waiter ID
    'customer_count' => 4,
    'status' => 'pending',
    'notes' => 'Customer request: no onions'
]);

// Add items to order
App\Models\OrderItem::create([
    'order_id' => $order->id,
    'menu_item_id' => 1,
    'quantity' => 2,
    'price_at_order' => 8.99,
    'status' => 'pending'
]);
```

### Update Order
```php
// Update order status
$order = App\Models\Order::find(1);
$order->update(['status' => 'confirmed']);

// Calculate order total
$order->calculateTotal();
```

## 📦 Inventory Management

### View Inventory
```php
// Get all inventory items
App\Models\InventoryItem::all();

// Get low stock items
App\Models\InventoryItem::where('quantity', '<=', 'reorder_level')->get();

// Get items by category
App\Models\InventoryItem::where('category_id', 1)->get();

// Get items with supplier info
App\Models\InventoryItem::with(['supplier', 'category'])->get();
```

### Update Stock
```php
// Update item quantity
$item = App\Models\InventoryItem::find(1);
$item->update(['quantity' => $item->quantity + 10]);

// Set new quantity
$item->update(['quantity' => 50]);
```

### Suppliers
```php
// Get all suppliers
App\Models\Supplier::all();

// Create new supplier
App\Models\Supplier::create([
    'name' => 'New Supplier',
    'contact_person' => 'John Doe',
    'email' => 'john@supplier.com',
    'phone' => '555-0123',
    'address' => '123 Supplier St',
    'notes' => 'Reliable supplier'
]);
```

## 📊 Analytics & Reports

### Revenue Analytics
```php
// Today's revenue
App\Models\Order::where('status', 'paid')
    ->whereDate('completed_at', today())
    ->sum('total_amount');

// This month's revenue
App\Models\Order::where('status', 'paid')
    ->whereMonth('completed_at', now()->month)
    ->sum('total_amount');

// Revenue by waiter
App\Models\Order::where('status', 'paid')
    ->with('waiter')
    ->get()
    ->groupBy('waiter.name')
    ->map(function($orders) {
        return $orders->sum('total_amount');
    });
```

### Order Statistics
```php
// Total orders today
App\Models\Order::whereDate('created_at', today())->count();

// Orders by status
App\Models\Order::selectRaw('status, count(*) as count')
    ->groupBy('status')
    ->get();

// Average order value
App\Models\Order::where('status', 'paid')->avg('total_amount');
```

### Table Utilization
```php
// Table utilization rate
$totalTables = App\Models\RestaurantTable::count();
$occupiedTables = App\Models\RestaurantTable::where('status', 'occupied')->count();
$utilizationRate = ($occupiedTables / $totalTables) * 100;
echo "Utilization Rate: {$utilizationRate}%";
```

## 🔍 Database Queries

### Complex Queries
```php
// Get orders with items and total
App\Models\Order::with(['items.menuItem', 'restaurantTable'])
    ->where('status', 'pending')
    ->get()
    ->map(function($order) {
        $order->total_items = $order->items->sum('quantity');
        return $order;
    });

// Get popular menu items
App\Models\OrderItem::with('menuItem')
    ->selectRaw('menu_item_id, SUM(quantity) as total_ordered')
    ->groupBy('menu_item_id')
    ->orderBy('total_ordered', 'desc')
    ->limit(10)
    ->get();

// Get waiter performance
App\Models\User::where('role', 'waiter')
    ->withCount(['ordersTaken as total_orders'])
    ->withSum(['ordersTaken as total_revenue'], 'total_amount')
    ->get();
```

### Data Export
```php
// Export users to array
$users = App\Models\User::all()->toArray();

// Export orders to JSON
$orders = App\Models\Order::with(['restaurantTable', 'waiter'])->get()->toJson();

// Get data for CSV export
$menuItems = App\Models\MenuItem::select('name', 'price', 'is_available')->get();
```

## 🛠️ System Maintenance

### Clear Caches
```php
// Clear application cache
Artisan::call('cache:clear');

// Clear config cache
Artisan::call('config:clear');

// Clear route cache
Artisan::call('route:clear');

// Clear view cache
Artisan::call('view:clear');
```

### Database Operations
```php
// Run migrations
Artisan::call('migrate');

// Rollback migrations
Artisan::call('migrate:rollback');

// Seed database
Artisan::call('db:seed');

// Fresh migration and seed
Artisan::call('migrate:fresh', ['--seed' => true]);
```

### Model Operations
```php
// Check model relationships
$user = App\Models\User::find(1);
$user->ordersTaken; // Get user's orders

$order = App\Models\Order::find(1);
$order->restaurantTable; // Get order's table
$order->waiter; // Get order's waiter
$order->items; // Get order items

$table = App\Models\RestaurantTable::find(1);
$table->orders; // Get table's orders
$table->currentOrder; // Get current active order
```

## 🔧 Debugging

### Check Authentication
```php
// Check if user is authenticated
auth()->check();

// Get current user
auth()->user();

// Check user role
$user = auth()->user();
$user->role;
$user->isWaiter();
```

### Check Database Connections
```php
// Test database connection
DB::connection()->getPdo();

// Check table structure
Schema::getColumnListing('users');
Schema::getColumnListing('orders');
```

### Performance Monitoring
```php
// Enable query logging
DB::enableQueryLog();

// Run some queries
App\Models\Order::all();

// Check logged queries
DB::getQueryLog();
```

## 📝 Useful Shortcuts

### Common Patterns
```php
// Find or create
$user = App\Models\User::firstOrCreate(
    ['email' => 'test@example.com'],
    ['name' => 'Test User', 'password' => Hash::make('password'), 'role' => 'waiter']
);

// Update or create
$item = App\Models\MenuItem::updateOrCreate(
    ['name' => 'Pizza'],
    ['price' => 12.99, 'category_id' => 1]
);

// Mass assignment
App\Models\User::where('role', 'waiter')->update(['email_verified_at' => now()]);
```

### Data Validation
```php
// Validate email format
filter_var('test@example.com', FILTER_VALIDATE_EMAIL);

// Check if record exists
App\Models\User::where('email', 'test@example.com')->exists();

// Count records
App\Models\Order::where('status', 'pending')->count();
```

## 🚨 Troubleshooting

### Common Issues
```php
// Fix broken relationships
$order = App\Models\Order::find(1);
if (!$order->waiter) {
    echo "Order has no waiter assigned";
}

// Check for orphaned records
$orphanedOrders = App\Models\Order::whereDoesntHave('waiter')->get();

// Fix data inconsistencies
App\Models\Order::whereNull('total_amount')->update(['total_amount' => 0]);
```

### Error Handling
```php
// Try-catch in Tinker
try {
    $user = App\Models\User::find(999);
    $user->name;
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
```

## 📋 Quick Reference

### Essential Commands
```php
// User operations
App\Models\User::all();
App\Models\User::find(1);
App\Models\User::where('email', 'test@example.com')->first();

// Order operations
App\Models\Order::with(['restaurantTable', 'waiter'])->get();
App\Models\Order::where('status', 'pending')->get();

// Table operations
App\Models\RestaurantTable::where('status', 'available')->get();
App\Models\RestaurantTable::with('currentOrder')->get();

// Menu operations
App\Models\MenuItem::where('is_available', true)->get();
App\Models\Category::with('menuItems')->get();
```

### Data Export
```php
// Export to array
$data = App\Models\User::all()->toArray();

// Export to JSON
$json = App\Models\Order::all()->toJson();

// Export specific fields
$users = App\Models\User::select('name', 'email', 'role')->get();
```

---

**🔧 Tinker is now your powerful debugging and data management tool!**