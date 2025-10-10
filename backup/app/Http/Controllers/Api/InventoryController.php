<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\Supplier;
use App\Models\StockTransaction;
use App\Models\Expense;
use App\Models\ExpenseCategory;

class InventoryController extends Controller
{
    public function getItems()
    {
        $items = InventoryItem::with(['supplier', 'category'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'items' => $items,
            'stats' => [
                'total_items' => $items->count(),
                'low_stock_items' => $items->where('quantity', '<=', 'reorder_level')->count(),
                'out_of_stock_items' => $items->where('quantity', 0)->count(),
                'total_value' => $items->sum(function($item) {
                    return $item->quantity * $item->unit_price;
                })
            ]
        ]);
    }

    public function createItem(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:expense_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'unit_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:50'
        ]);

        $item = InventoryItem::create($request->all());

        return response()->json([
            'message' => 'Inventory item created successfully',
            'item' => $item->load(['supplier', 'category'])
        ], 201);
    }

    public function updateItem(Request $request, InventoryItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:expense_categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'unit_price' => 'required|numeric|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:50'
        ]);

        $item->update($request->all());

        return response()->json([
            'message' => 'Inventory item updated successfully',
            'item' => $item->load(['supplier', 'category'])
        ]);
    }

    public function deleteItem(InventoryItem $item)
    {
        $item->delete();

        return response()->json([
            'message' => 'Inventory item deleted successfully'
        ]);
    }

    public function updateStock(Request $request, InventoryItem $item)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:add,remove,set',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0'
        ]);

        $oldQuantity = $item->quantity;
        $newQuantity = $oldQuantity;

        switch ($request->type) {
            case 'add':
                $newQuantity = $oldQuantity + $request->quantity;
                break;
            case 'remove':
                $newQuantity = max(0, $oldQuantity - $request->quantity);
                break;
            case 'set':
                $newQuantity = $request->quantity;
                break;
        }

        $item->update(['quantity' => $newQuantity]);

        // Record transaction
        StockTransaction::create([
            'inventory_item_id' => $item->id,
            'user_id' => auth()->id(),
            'type' => $request->type,
            'quantity' => $request->quantity,
            'old_quantity' => $oldQuantity,
            'new_quantity' => $newQuantity,
            'cost' => $request->cost,
            'notes' => $request->notes
        ]);

        return response()->json([
            'message' => 'Stock updated successfully',
            'item' => $item->load(['supplier', 'category'])
        ]);
    }

    public function getStockHistory(InventoryItem $item)
    {
        $transactions = $item->stockTransactions()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'item' => $item->load(['supplier', 'category']),
            'transactions' => $transactions
        ]);
    }

    public function getSuppliers()
    {
        $suppliers = Supplier::withCount('inventoryItems')
            ->orderBy('name')
            ->get();

        return response()->json($suppliers);
    }

    public function createSupplier(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $supplier = Supplier::create($request->all());

        return response()->json([
            'message' => 'Supplier created successfully',
            'supplier' => $supplier
        ], 201);
    }

    public function updateSupplier(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        $supplier->update($request->all());

        return response()->json([
            'message' => 'Supplier updated successfully',
            'supplier' => $supplier
        ]);
    }

    public function deleteSupplier(Supplier $supplier)
    {
        if ($supplier->inventoryItems()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete supplier with associated inventory items'
            ], 400);
        }

        $supplier->delete();

        return response()->json([
            'message' => 'Supplier deleted successfully'
        ]);
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:low_stock,value,transactions,expenses',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date'
        ]);

        switch ($request->type) {
            case 'low_stock':
                $report = $this->generateLowStockReport();
                break;
            case 'value':
                $report = $this->generateValueReport();
                break;
            case 'transactions':
                $report = $this->generateTransactionsReport($request->start_date, $request->end_date);
                break;
            case 'expenses':
                $report = $this->generateExpensesReport($request->start_date, $request->end_date);
                break;
        }

        return response()->json($report);
    }

    private function generateLowStockReport()
    {
        $items = InventoryItem::with(['supplier', 'category'])
            ->where('quantity', '<=', 'reorder_level')
            ->orderBy('quantity')
            ->get();

        return [
            'type' => 'low_stock',
            'items' => $items,
            'total_items' => $items->count(),
            'out_of_stock' => $items->where('quantity', 0)->count(),
            'below_reorder_level' => $items->where('quantity', '>', 0)->count()
        ];
    }

    private function generateValueReport()
    {
        $items = InventoryItem::with(['supplier', 'category'])
            ->orderBy('name')
            ->get();

        $totalValue = $items->sum(function($item) {
            return $item->quantity * $item->unit_price;
        });

        $categoryValues = $items->groupBy('category_id')->map(function($categoryItems) {
            return $categoryItems->sum(function($item) {
                return $item->quantity * $item->unit_price;
            });
        });

        return [
            'type' => 'value',
            'total_value' => $totalValue,
            'total_items' => $items->count(),
            'category_values' => $categoryValues,
            'items' => $items
        ];
    }

    private function generateTransactionsReport($startDate, $endDate)
    {
        $query = StockTransaction::with(['inventoryItem', 'user']);

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        return [
            'type' => 'transactions',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'transactions' => $transactions,
            'total_transactions' => $transactions->count(),
            'total_cost' => $transactions->sum('cost')
        ];
    }

    private function generateExpensesReport($startDate, $endDate)
    {
        $query = Expense::with(['category']);

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        $expenses = $query->orderBy('date', 'desc')->get();

        return [
            'type' => 'expenses',
            'period' => ['start' => $startDate, 'end' => $endDate],
            'expenses' => $expenses,
            'total_amount' => $expenses->sum('amount'),
            'category_totals' => $expenses->groupBy('category_id')->map(function($categoryExpenses) {
                return $categoryExpenses->sum('amount');
            })
        ];
    }

    public function getInventoryStats()
    {
        $items = InventoryItem::with(['supplier', 'category'])->get();

        $stats = [
            'total_items' => $items->count(),
            'total_value' => $items->sum(function($item) {
                return $item->quantity * $item->unit_price;
            }),
            'low_stock_items' => $items->where('quantity', '<=', 'reorder_level')->count(),
            'out_of_stock_items' => $items->where('quantity', 0)->count(),
            'categories' => $items->groupBy('category_id')->count(),
            'suppliers' => $items->groupBy('supplier_id')->count(),
            'average_unit_price' => $items->avg('unit_price'),
            'total_quantity' => $items->sum('quantity')
        ];

        return response()->json($stats);
    }
}