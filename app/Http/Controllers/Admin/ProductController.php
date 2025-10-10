<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'minimum_order_quantity' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'origin_country' => 'nullable|string|max:100',
            'packaging_details' => 'nullable|string',
            'shelf_life' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        // Handle specifications
        if ($request->filled('specifications')) {
            $specs = [];
            foreach ($request->specifications as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $specs[$key] = $value;
                }
            }
            $data['specifications'] = $specs;
        }

        // Handle certifications
        if ($request->filled('certifications')) {
            $data['certifications'] = array_filter($request->certifications);
        }

        Product::create($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'supplier']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::active()->orderBy('name')->get();
        $suppliers = Supplier::active()->orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:500',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'nullable|numeric|min:0',
            'currency' => 'nullable|string|max:3',
            'minimum_order_quantity' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:50',
            'origin_country' => 'nullable|string|max:100',
            'packaging_details' => 'nullable|string',
            'shelf_life' => 'nullable|string|max:100',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500'
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->name);
        
        // Handle specifications
        if ($request->filled('specifications')) {
            $specs = [];
            foreach ($request->specifications as $key => $value) {
                if (!empty($key) && !empty($value)) {
                    $specs[$key] = $value;
                }
            }
            $data['specifications'] = $specs;
        }

        // Handle certifications
        if ($request->filled('certifications')) {
            $data['certifications'] = array_filter($request->certifications);
        }

        $product->update($data);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        $status = $product->is_active ? 'activated' : 'deactivated';
        
        return back()->with('success', "Product {$status} successfully.");
    }
}