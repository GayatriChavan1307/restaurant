<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier'])
            ->active();

        // Filter by category
        if ($request->filled('category')) {
            $category = Category::where('slug', $request->category)->first();
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('short_description', 'like', "%{$search}%");
            });
        }

        // Filter by origin country
        if ($request->filled('country')) {
            $query->where('origin_country', $request->country);
        }

        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        switch ($sortBy) {
            case 'price':
                $query->orderBy('price', $sortOrder);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', $sortOrder);
        }

        $products = $query->paginate(12);

        $categories = Category::active()
            ->parent()
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        $countries = Product::active()
            ->distinct()
            ->pluck('origin_country')
            ->filter()
            ->sort()
            ->values();

        return view('frontend.products.index', compact(
            'products',
            'categories',
            'countries'
        ));
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            abort(404);
        }

        $product->load(['category', 'supplier']);

        $relatedProducts = Product::with('category')
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)
            ->get();

        return view('frontend.products.show', compact('product', 'relatedProducts'));
    }

    public function inquiry(Request $request, Product $product)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'company_name' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'message' => 'nullable|string|max:2000'
        ]);

        $inquiry = Inquiry::create([
            'product_id' => $product->id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'company_name' => $request->company_name,
            'country' => $request->country,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'message' => $request->message,
            'status' => Inquiry::STATUS_PENDING
        ]);

        // Here you can add logic to send email notification to admin

        return back()->with('success', 'Your inquiry has been submitted successfully! We will contact you soon with a quote.');
    }

    public function category(Category $category)
    {
        if (!$category->is_active) {
            abort(404);
        }

        $products = Product::with(['category', 'supplier'])
            ->active()
            ->where('category_id', $category->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12);

        $subcategories = Category::active()
            ->where('parent_id', $category->id)
            ->withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('frontend.products.category', compact('category', 'products', 'subcategories'));
    }
}