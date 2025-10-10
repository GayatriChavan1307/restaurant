<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Agricultural Products',
                'description' => 'Fresh and processed agricultural products including grains, pulses, and vegetables.',
                'sort_order' => 1,
            ],
            [
                'name' => 'Spices & Herbs',
                'description' => 'Premium quality spices, herbs, and seasonings from around the world.',
                'sort_order' => 2,
            ],
            [
                'name' => 'Food Products',
                'description' => 'Processed and packaged food products for global distribution.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Industrial Goods',
                'description' => 'Industrial raw materials and manufactured goods.',
                'sort_order' => 4,
            ],
            [
                'name' => 'Textiles',
                'description' => 'Textile products including fabrics, garments, and accessories.',
                'sort_order' => 5,
            ],
            [
                'name' => 'Chemicals',
                'description' => 'Industrial and specialty chemicals for various applications.',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'sort_order' => $category['sort_order'],
                'is_active' => true,
            ]);
        }

        // Create some subcategories
        $agriculturalCategory = Category::where('slug', 'agricultural-products')->first();
        if ($agriculturalCategory) {
            $subcategories = [
                'Onions & Onion Products',
                'Rice & Grains',
                'Pulses & Lentils',
                'Fresh Vegetables',
            ];

            foreach ($subcategories as $index => $subcategory) {
                Category::create([
                    'name' => $subcategory,
                    'slug' => Str::slug($subcategory),
                    'description' => "High quality {$subcategory} for export.",
                    'parent_id' => $agriculturalCategory->id,
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]);
            }
        }
    }
}