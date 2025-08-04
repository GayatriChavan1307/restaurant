<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Serve the Vue.js SPA for all routes
Route::get('/{any}', function () {
    return view('app');
})->where('any', '.*');

// API routes are handled in api.php
// Authentication routes for web fallback
Auth::routes();