<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\InquiryController;
use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');

// Product Routes
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('products.index');
    Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');
    Route::get('/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/{product}/inquiry', [ProductController::class, 'inquiry'])->name('products.inquiry');
});

// Authentication Routes
Auth::routes();

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Product Management
    Route::resource('products', AdminProductController::class, ['as' => 'admin']);
    Route::post('products/{product}/toggle-status', [AdminProductController::class, 'toggleStatus'])->name('admin.products.toggle-status');
    
    // Inquiry Management
    Route::resource('inquiries', InquiryController::class, ['as' => 'admin']);
    Route::post('inquiries/{inquiry}/update-status', [InquiryController::class, 'updateStatus'])->name('admin.inquiries.update-status');
    Route::post('inquiries/{inquiry}/add-quote', [InquiryController::class, 'addQuote'])->name('admin.inquiries.add-quote');
    Route::post('inquiries/{inquiry}/set-follow-up', [InquiryController::class, 'setFollowUp'])->name('admin.inquiries.set-follow-up');
    Route::get('inquiries/export', [InquiryController::class, 'export'])->name('admin.inquiries.export');
});

// API Routes for AJAX requests
Route::prefix('api')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});