<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RestaurantTable;

class TableController extends Controller
{
    public function index()
    {
        $tables = RestaurantTable::with(['currentOrder.items'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'tables' => $tables,
            'stats' => [
                'total' => $tables->count(),
                'available' => $tables->where('status', 'available')->count(),
                'occupied' => $tables->where('status', 'occupied')->count(),
                'reserved' => $tables->where('status', 'reserved')->count(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:restaurant_tables,name',
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'visual_coordinates' => 'nullable|string'
        ]);

        $table = RestaurantTable::create($request->all());

        return response()->json([
            'message' => 'Table created successfully',
            'table' => $table
        ], 201);
    }

    public function show(RestaurantTable $table)
    {
        return response()->json($table->load(['currentOrder.items', 'orders.waiter']));
    }

    public function update(Request $request, RestaurantTable $table)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:restaurant_tables,name,' . $table->id,
            'capacity' => 'required|integer|min:1|max:20',
            'status' => 'required|in:available,occupied,reserved,maintenance',
            'visual_coordinates' => 'nullable|string'
        ]);

        $table->update($request->all());

        return response()->json([
            'message' => 'Table updated successfully',
            'table' => $table
        ]);
    }

    public function destroy(RestaurantTable $table)
    {
        // Check if table has active orders
        if ($table->orders()->whereNotIn('status', ['paid', 'cancelled'])->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete table with active orders'
            ], 400);
        }

        $table->delete();

        return response()->json([
            'message' => 'Table deleted successfully'
        ]);
    }

    public function assignTable(Request $request, RestaurantTable $table)
    {
        if ($table->status !== 'available') {
            return response()->json([
                'message' => 'Table is not available'
            ], 400);
        }

        $request->validate([
            'customer_count' => 'required|integer|min:1|max:' . $table->capacity
        ]);

        $table->update(['status' => 'occupied']);

        return response()->json([
            'message' => 'Table assigned successfully',
            'table' => $table
        ]);
    }

    public function unassignTable(Request $request, RestaurantTable $table)
    {
        if ($table->status !== 'occupied') {
            return response()->json([
                'message' => 'Table is not occupied'
            ], 400);
        }

        // Cancel any active orders
        $table->orders()
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->update(['status' => 'cancelled']);

        $table->update(['status' => 'available']);

        return response()->json([
            'message' => 'Table unassigned successfully',
            'table' => $table
        ]);
    }

    public function reserveTable(Request $request, RestaurantTable $table)
    {
        if ($table->status !== 'available') {
            return response()->json([
                'message' => 'Table is not available'
            ], 400);
        }

        $request->validate([
            'reservation_time' => 'required|date|after:now',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_count' => 'required|integer|min:1|max:' . $table->capacity
        ]);

        $table->update([
            'status' => 'reserved',
            'reservation_time' => $request->reservation_time,
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone,
            'customer_count' => $request->customer_count
        ]);

        return response()->json([
            'message' => 'Table reserved successfully',
            'table' => $table
        ]);
    }

    public function getTableLayout()
    {
        $tables = RestaurantTable::orderBy('name')->get();

        // Group tables by area/section if visual_coordinates are set
        $layout = [];
        foreach ($tables as $table) {
            $coordinates = json_decode($table->visual_coordinates, true);
            if ($coordinates) {
                $layout[] = [
                    'id' => $table->id,
                    'name' => $table->name,
                    'capacity' => $table->capacity,
                    'status' => $table->status,
                    'x' => $coordinates['x'] ?? 0,
                    'y' => $coordinates['y'] ?? 0,
                    'width' => $coordinates['width'] ?? 100,
                    'height' => $coordinates['height'] ?? 100,
                    'current_order' => $table->currentOrder
                ];
            } else {
                // Default grid layout
                $layout[] = [
                    'id' => $table->id,
                    'name' => $table->name,
                    'capacity' => $table->capacity,
                    'status' => $table->status,
                    'x' => ($table->id - 1) * 120,
                    'y' => floor(($table->id - 1) / 4) * 120,
                    'width' => 100,
                    'height' => 100,
                    'current_order' => $table->currentOrder
                ];
            }
        }

        return response()->json([
            'layout' => $layout,
            'total_tables' => count($layout)
        ]);
    }

    public function updateTableLayout(Request $request)
    {
        $request->validate([
            'tables' => 'required|array',
            'tables.*.id' => 'required|exists:restaurant_tables,id',
            'tables.*.x' => 'required|numeric',
            'tables.*.y' => 'required|numeric',
            'tables.*.width' => 'required|numeric|min:50',
            'tables.*.height' => 'required|numeric|min:50'
        ]);

        foreach ($request->tables as $tableData) {
            $table = RestaurantTable::find($tableData['id']);
            $table->update([
                'visual_coordinates' => json_encode([
                    'x' => $tableData['x'],
                    'y' => $tableData['y'],
                    'width' => $tableData['width'],
                    'height' => $tableData['height']
                ])
            ]);
        }

        return response()->json([
            'message' => 'Table layout updated successfully'
        ]);
    }

    public function getTableStats()
    {
        $stats = [
            'total_tables' => RestaurantTable::count(),
            'available_tables' => RestaurantTable::where('status', 'available')->count(),
            'occupied_tables' => RestaurantTable::where('status', 'occupied')->count(),
            'reserved_tables' => RestaurantTable::where('status', 'reserved')->count(),
            'maintenance_tables' => RestaurantTable::where('status', 'maintenance')->count(),
            'total_capacity' => RestaurantTable::sum('capacity'),
            'average_capacity' => RestaurantTable::avg('capacity'),
            'utilization_rate' => RestaurantTable::where('status', 'occupied')->count() / RestaurantTable::count() * 100
        ];

        return response()->json($stats);
    }
}