<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Starters',
                'description' => 'Appetizers and small plates',
                'sort_order' => 1
            ],
            [
                'name' => 'Soups',
                'description' => 'Hot and cold soups',
                'sort_order' => 2
            ],
            [
                'name' => 'Salads',
                'description' => 'Fresh salads and greens',
                'sort_order' => 3
            ],
            [
                'name' => 'Main Course',
                'description' => 'Primary dishes and entrees',
                'sort_order' => 4
            ],
            [
                'name' => 'Pasta',
                'description' => 'Italian pasta dishes',
                'sort_order' => 5
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fresh seafood dishes',
                'sort_order' => 6
            ],
            [
                'name' => 'Desserts',
                'description' => 'Sweet treats and desserts',
                'sort_order' => 7
            ],
            [
                'name' => 'Beverages',
                'description' => 'Drinks and beverages',
                'sort_order' => 8
            ],
            [
                'name' => 'Coffee & Tea',
                'description' => 'Hot and cold coffee and tea',
                'sort_order' => 9
            ],
            [
                'name' => 'Alcoholic Drinks',
                'description' => 'Wine, beer, and cocktails',
                'sort_order' => 10
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}