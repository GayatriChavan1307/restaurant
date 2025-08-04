<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\ApiAuthController;
use App\Http\Controllers\Api\WaiterController;
use App\Http\Controllers\Api\KitchenController;
use App\Http\Controllers\Api\ReceptionController;
use App\Http\Controllers\Api\OwnerController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\TableController;
use App\Http\Controllers\Api\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/user', [ApiAuthController::class, 'user'])->middleware('auth:sanctum');

// Protected routes
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Waiter routes
    Route::prefix('waiter')->middleware('role:waiter')->group(function () {
        Route::get('/tables', [WaiterController::class, 'getTables']);
        Route::get('/orders', [WaiterController::class, 'getOrders']);
        Route::post('/tables/{table}/assign', [WaiterController::class, 'assignTable']);
        Route::post('/tables/{table}/unassign', [WaiterController::class, 'unassignTable']);
        Route::post('/orders/{order}/items', [WaiterController::class, 'addItemsToOrder']);
        Route::post('/orders/{order}/send-to-kitchen', [WaiterController::class, 'sendToKitchen']);
        Route::patch('/orders/{order}/items/{item}', [WaiterController::class, 'updateOrderItem']);
        Route::delete('/orders/{order}/items/{item}', [WaiterController::class, 'removeOrderItem']);
    });

    // Kitchen routes
    Route::prefix('kitchen')->middleware('role:kitchen')->group(function () {
        Route::get('/orders', [KitchenController::class, 'getOrders']);
        Route::post('/orders/{order}/start-preparing', [KitchenController::class, 'startPreparing']);
        Route::post('/orders/{order}/mark-ready', [KitchenController::class, 'markAsReady']);
        Route::post('/orders/{order}/mark-served', [KitchenController::class, 'markAsServed']);
        Route::post('/orders/{order}/items/{item}/status', [KitchenController::class, 'updateItemStatus']);
        Route::post('/orders/{order}/note', [KitchenController::class, 'addNote']);
        Route::post('/orders/{order}/issue', [KitchenController::class, 'reportIssue']);
    });

    // Reception routes
    Route::prefix('reception')->middleware('role:reception')->group(function () {
        Route::get('/tables', [ReceptionController::class, 'getTables']);
        Route::get('/orders', [ReceptionController::class, 'getOrders']);
        Route::get('/notifications', [ReceptionController::class, 'getNotifications']);
        Route::get('/stats', [ReceptionController::class, 'getStats']);
        Route::get('/orders/{order}/bill', [ReceptionController::class, 'generateBill']);
        Route::post('/orders/{order}/paid', [ReceptionController::class, 'markAsPaid']);
    });

    // Owner routes
    Route::prefix('owner')->middleware('role:owner,superadmin')->group(function () {
        Route::get('/analytics', [OwnerController::class, 'getAnalytics']);
        Route::get('/staff', [OwnerController::class, 'getStaff']);
        Route::post('/staff', [OwnerController::class, 'createStaff']);
        Route::patch('/staff/{user}/toggle', [OwnerController::class, 'toggleStaffStatus']);
        Route::post('/reports/generate', [OwnerController::class, 'generateReport']);
        Route::post('/reports/export', [OwnerController::class, 'exportReport']);
    });

    // Inventory routes
    Route::prefix('inventory')->group(function () {
        Route::get('/items', [InventoryController::class, 'getItems']);
        Route::post('/items', [InventoryController::class, 'createItem']);
        Route::patch('/items/{item}', [InventoryController::class, 'updateItem']);
        Route::delete('/items/{item}', [InventoryController::class, 'deleteItem']);
        Route::post('/items/{item}/stock', [InventoryController::class, 'updateStock']);
        Route::get('/items/{item}/history', [InventoryController::class, 'getStockHistory']);
        Route::get('/suppliers', [InventoryController::class, 'getSuppliers']);
        Route::get('/reports/generate', [InventoryController::class, 'generateReport']);
    });

    // Menu routes
    Route::prefix('menu')->group(function () {
        Route::get('/categories', [MenuController::class, 'getCategories']);
        Route::get('/items', [MenuController::class, 'getItems']);
        Route::post('/categories', [MenuController::class, 'createCategory']);
        Route::post('/items', [MenuController::class, 'createItem']);
        Route::patch('/items/{item}', [MenuController::class, 'updateItem']);
        Route::delete('/items/{item}', [MenuController::class, 'deleteItem']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/', [OrderController::class, 'store']);
        Route::get('/{order}', [OrderController::class, 'show']);
        Route::patch('/{order}', [OrderController::class, 'update']);
        Route::delete('/{order}', [OrderController::class, 'destroy']);
    });

    // Table routes
    Route::prefix('tables')->group(function () {
        Route::get('/', [TableController::class, 'index']);
        Route::post('/', [TableController::class, 'store']);
        Route::patch('/{table}', [TableController::class, 'update']);
        Route::delete('/{table}', [TableController::class, 'destroy']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::post('/{notification}/read', [NotificationController::class, 'markAsRead']);
        Route::delete('/{notification}', [NotificationController::class, 'delete']);
    });
});