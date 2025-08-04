<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            // Drop existing columns that don't match the seeder
            $table->dropColumn(['category', 'unit_of_measure', 'current_stock', 'reorder_level', 'average_cost_price']);
            
            // Add new columns that match the seeder
            $table->foreignId('category_id')->constrained('expense_categories')->after('description');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->after('category_id');
            $table->decimal('unit_price', 10, 2)->after('supplier_id');
            $table->integer('quantity')->default(0)->after('unit_price');
            $table->integer('reorder_level')->default(0)->after('quantity');
            $table->string('unit', 50)->after('reorder_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_items', function (Blueprint $table) {
            $table->dropForeign(['category_id', 'supplier_id']);
            $table->dropColumn(['category_id', 'supplier_id', 'unit_price', 'quantity', 'reorder_level', 'unit']);
            
            // Restore original columns
            $table->string('category')->nullable();
            $table->string('unit_of_measure');
            $table->decimal('current_stock', 10, 3)->default(0);
            $table->decimal('reorder_level', 10, 3)->nullable();
            $table->decimal('average_cost_price', 10, 2)->nullable();
        });
    }
};
