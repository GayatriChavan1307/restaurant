<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ExpenseCategory;

class ExpenseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Produce',
                'description' => 'Fresh fruits and vegetables'
            ],
            [
                'name' => 'Meat & Poultry',
                'description' => 'Fresh meat and poultry products'
            ],
            [
                'name' => 'Seafood',
                'description' => 'Fresh and frozen seafood'
            ],
            [
                'name' => 'Dairy',
                'description' => 'Milk, cheese, and dairy products'
            ],
            [
                'name' => 'Pantry',
                'description' => 'Dry goods and pantry items'
            ],
            [
                'name' => 'Beverages',
                'description' => 'Soft drinks, juices, and non-alcoholic beverages'
            ],
            [
                'name' => 'Alcoholic Beverages',
                'description' => 'Wine, beer, and spirits'
            ],
            [
                'name' => 'Cleaning Supplies',
                'description' => 'Kitchen and restaurant cleaning supplies'
            ],
            [
                'name' => 'Paper Goods',
                'description' => 'Napkins, paper towels, and disposable items'
            ],
            [
                'name' => 'Equipment',
                'description' => 'Kitchen equipment and tools'
            ]
        ];

        foreach ($categories as $category) {
            ExpenseCategory::create($category);
        }
    }
}