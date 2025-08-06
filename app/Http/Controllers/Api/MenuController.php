<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function getCategories()
    {
        $categories = Category::withCount('menuItems')
            ->orderBy('name')
            ->get();

        return response()->json($categories);
    }

    public function createCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'message' => 'Category created successfully',
            'category' => $category
        ], 201);
    }

    public function updateCategory(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($request->all());

        return response()->json([
            'message' => 'Category updated successfully',
            'category' => $category
        ]);
    }

    public function deleteCategory(Category $category)
    {
        // Check if category has menu items
        if ($category->menuItems()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete category with existing menu items'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    public function getItems()
    {
        $menuItems = MenuItem::with('category')
            ->orderBy('category_id')
            ->orderBy('name')
            ->get();

        return response()->json($menuItems);
    }

    public function getItemsByCategory(Category $category)
    {
        $menuItems = $category->menuItems()
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        return response()->json([
            'category' => $category,
            'items' => $menuItems
        ]);
    }

    public function createItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'boolean',
            'image_path' => 'nullable|string'
        ]);

        $menuItem = MenuItem::create($request->all());

        return response()->json([
            'message' => 'Menu item created successfully',
            'menu_item' => $menuItem->load('category')
        ], 201);
    }

    public function updateItem(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_available' => 'boolean',
            'image_path' => 'nullable|string'
        ]);

        $menuItem->update($request->all());

        return response()->json([
            'message' => 'Menu item updated successfully',
            'menu_item' => $menuItem->load('category')
        ]);
    }

    public function deleteItem(MenuItem $menuItem)
    {
        // Check if item is used in any orders
        if ($menuItem->orderItems()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete menu item that has been ordered'
            ], 400);
        }

        $menuItem->delete();

        return response()->json([
            'message' => 'Menu item deleted successfully'
        ]);
    }

    public function toggleAvailability(MenuItem $menuItem)
    {
        $menuItem->update([
            'is_available' => !$menuItem->is_available
        ]);

        return response()->json([
            'message' => 'Menu item availability updated',
            'menu_item' => $menuItem->load('category')
        ]);
    }

    public function getMenuStats()
    {
        $stats = [
            'total_items' => MenuItem::count(),
            'available_items' => MenuItem::where('is_available', true)->count(),
            'categories' => Category::count(),
            'top_categories' => Category::withCount('menuItems')
                ->orderBy('menu_items_count', 'desc')
                ->limit(5)
                ->get(),
            'price_range' => [
                'min' => MenuItem::min('price'),
                'max' => MenuItem::max('price'),
                'average' => MenuItem::avg('price')
            ]
        ];

        return response()->json($stats);
    }
}