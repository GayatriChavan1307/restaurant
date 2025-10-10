<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Inquiry;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::active()->count(),
            'total_categories' => Category::count(),
            'pending_inquiries' => Inquiry::pending()->count(),
            'total_inquiries' => Inquiry::count(),
            'processing_inquiries' => Inquiry::processing()->count(),
            'total_customers' => User::where('role', User::ROLE_CUSTOMER)->count(),
            'recent_inquiries' => Inquiry::with(['product'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
        ];

        // Monthly inquiry stats for chart
        $monthlyInquiries = Inquiry::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $inquiryChart = [];
        for ($i = 1; $i <= 12; $i++) {
            $inquiryChart[] = $monthlyInquiries[$i] ?? 0;
        }

        // Top categories by product count
        $topCategories = Category::withCount('products')
            ->orderBy('products_count', 'desc')
            ->limit(5)
            ->get();

        // Recent products
        $recentProducts = Product::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'inquiryChart',
            'topCategories',
            'recentProducts'
        ));
    }
}