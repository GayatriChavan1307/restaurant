<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\InventoryItem;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // Produce
            [
                'name' => 'Tomatoes',
                'description' => 'Fresh Roma tomatoes',
                'category_id' => 1, // Produce
                'supplier_id' => 1, // Fresh Market Foods
                'unit_price' => 2.50,
                'quantity' => 50,
                'reorder_level' => 10,
                'unit' => 'lbs'
            ],
            [
                'name' => 'Lettuce',
                'description' => 'Fresh iceberg lettuce heads',
                'category_id' => 1,
                'supplier_id' => 1,
                'unit_price' => 1.99,
                'quantity' => 30,
                'reorder_level' => 5,
                'unit' => 'heads'
            ],
            [
                'name' => 'Onions',
                'description' => 'Yellow onions',
                'category_id' => 1,
                'supplier_id' => 1,
                'unit_price' => 1.25,
                'quantity' => 40,
                'reorder_level' => 8,
                'unit' => 'lbs'
            ],

            // Meat & Poultry
            [
                'name' => 'Chicken Breast',
                'description' => 'Boneless, skinless chicken breasts',
                'category_id' => 2, // Meat & Poultry
                'supplier_id' => 2, // Quality Meats Co.
                'unit_price' => 8.99,
                'quantity' => 25,
                'reorder_level' => 5,
                'unit' => 'lbs'
            ],
            [
                'name' => 'Ground Beef',
                'description' => '80/20 ground beef',
                'category_id' => 2,
                'supplier_id' => 2,
                'unit_price' => 6.99,
                'quantity' => 30,
                'reorder_level' => 8,
                'unit' => 'lbs'
            ],
            [
                'name' => 'Beef Tenderloin',
                'description' => 'Premium beef tenderloin',
                'category_id' => 2,
                'supplier_id' => 2,
                'unit_price' => 24.99,
                'quantity' => 15,
                'reorder_level' => 3,
                'unit' => 'lbs'
            ],

            // Seafood
            [
                'name' => 'Salmon Fillets',
                'description' => 'Fresh Atlantic salmon fillets',
                'category_id' => 3, // Seafood
                'supplier_id' => 3, // Ocean Fresh Seafood
                'unit_price' => 18.99,
                'quantity' => 20,
                'reorder_level' => 4,
                'unit' => 'lbs'
            ],
            [
                'name' => 'Shrimp',
                'description' => 'Large shrimp, peeled and deveined',
                'category_id' => 3,
                'supplier_id' => 3,
                'unit_price' => 12.99,
                'quantity' => 15,
                'reorder_level' => 3,
                'unit' => 'lbs'
            ],

            // Dairy
            [
                'name' => 'Mozzarella Cheese',
                'description' => 'Fresh mozzarella cheese',
                'category_id' => 4, // Dairy
                'supplier_id' => 1, // Fresh Market Foods
                'unit_price' => 4.99,
                'quantity' => 20,
                'reorder_level' => 5,
                'unit' => 'lbs'
            ],
            [
                'name' => 'Heavy Cream',
                'description' => 'Heavy whipping cream',
                'category_id' => 4,
                'supplier_id' => 1,
                'unit_price' => 3.99,
                'quantity' => 15,
                'reorder_level' => 3,
                'unit' => 'quarts'
            ],

            // Pantry
            [
                'name' => 'Pasta',
                'description' => 'Spaghetti and fettuccine pasta',
                'category_id' => 5, // Pantry
                'supplier_id' => 6, // Pantry Essentials
                'unit_price' => 2.99,
                'quantity' => 30,
                'reorder_level' => 8,
                'unit' => 'lbs'
            ],
            [
                'name' => 'Olive Oil',
                'description' => 'Extra virgin olive oil',
                'category_id' => 5,
                'supplier_id' => 6,
                'unit_price' => 8.99,
                'quantity' => 20,
                'reorder_level' => 5,
                'unit' => 'bottles'
            ],
            [
                'name' => 'Garlic',
                'description' => 'Fresh garlic bulbs',
                'category_id' => 5,
                'supplier_id' => 6,
                'unit_price' => 1.99,
                'quantity' => 25,
                'reorder_level' => 5,
                'unit' => 'lbs'
            ],

            // Beverages
            [
                'name' => 'Orange Juice',
                'description' => 'Fresh orange juice',
                'category_id' => 6, // Beverages
                'supplier_id' => 4, // Beverage Distributors Inc.
                'unit_price' => 3.99,
                'quantity' => 20,
                'reorder_level' => 5,
                'unit' => 'gallons'
            ],
            [
                'name' => 'Lemonade',
                'description' => 'Fresh lemonade mix',
                'category_id' => 6,
                'supplier_id' => 4,
                'unit_price' => 2.99,
                'quantity' => 15,
                'reorder_level' => 3,
                'unit' => 'gallons'
            ],

            // Alcoholic Beverages
            [
                'name' => 'Red Wine',
                'description' => 'House red wine',
                'category_id' => 7, // Alcoholic Beverages
                'supplier_id' => 4, // Beverage Distributors Inc.
                'unit_price' => 12.99,
                'quantity' => 24,
                'reorder_level' => 6,
                'unit' => 'bottles'
            ],
            [
                'name' => 'White Wine',
                'description' => 'House white wine',
                'category_id' => 7,
                'supplier_id' => 4,
                'unit_price' => 11.99,
                'quantity' => 24,
                'reorder_level' => 6,
                'unit' => 'bottles'
            ],
            [
                'name' => 'Draft Beer',
                'description' => 'Local craft beer kegs',
                'category_id' => 7,
                'supplier_id' => 4,
                'unit_price' => 89.99,
                'quantity' => 8,
                'reorder_level' => 2,
                'unit' => 'kegs'
            ],

            // Cleaning Supplies
            [
                'name' => 'Dish Soap',
                'description' => 'Commercial dish soap',
                'category_id' => 8, // Cleaning Supplies
                'supplier_id' => 5, // Restaurant Supply Co.
                'unit_price' => 4.99,
                'quantity' => 12,
                'reorder_level' => 3,
                'unit' => 'bottles'
            ],
            [
                'name' => 'Paper Towels',
                'description' => 'Commercial paper towels',
                'category_id' => 9, // Paper Goods
                'supplier_id' => 5,
                'unit_price' => 2.99,
                'quantity' => 20,
                'reorder_level' => 5,
                'unit' => 'rolls'
            ]
        ];

        foreach ($items as $item) {
            InventoryItem::create($item);
        }
    }
}