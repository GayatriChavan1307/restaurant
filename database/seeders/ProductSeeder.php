<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $suppliers = Supplier::all();

        $products = [
            [
                'name' => 'Premium Red Onions',
                'short_description' => 'Fresh, high-quality red onions sourced directly from certified farms.',
                'description' => 'Our premium red onions are carefully selected from the finest farms and undergo strict quality control. Perfect for export with excellent shelf life and superior taste. Available in various sizes and packaging options.',
                'sku' => 'AGR-ON-001',
                'price' => 450.00,
                'currency' => 'USD',
                'minimum_order_quantity' => 1000,
                'unit' => 'kg',
                'origin_country' => 'India',
                'packaging_details' => '25kg mesh bags, 50kg jute bags, or custom packaging',
                'shelf_life' => '3-4 months in proper storage',
                'specifications' => [
                    'Size' => '40-70mm diameter',
                    'Moisture' => 'Max 86%',
                    'Purity' => 'Min 99%',
                    'Color' => 'Dark red to purple',
                ],
                'certifications' => ['ISO 22000', 'HACCP', 'Organic'],
                'is_featured' => true,
            ],
            [
                'name' => 'Dehydrated Onion Powder',
                'short_description' => 'Premium quality dehydrated onion powder with natural flavor and aroma.',
                'description' => 'Made from carefully selected fresh onions using advanced dehydration technology. Retains natural flavor, aroma, and nutritional value. Ideal for food processing, seasoning, and culinary applications.',
                'sku' => 'AGR-ON-002',
                'price' => 2800.00,
                'currency' => 'USD',
                'minimum_order_quantity' => 500,
                'unit' => 'kg',
                'origin_country' => 'India',
                'packaging_details' => '25kg multi-layer paper bags with inner PE lining',
                'shelf_life' => '24 months in dry conditions',
                'specifications' => [
                    'Moisture' => 'Max 5%',
                    'Mesh Size' => '80-120 mesh',
                    'Color' => 'Cream to light yellow',
                    'Flavor' => 'Characteristic onion flavor',
                ],
                'certifications' => ['ISO 22000', 'HACCP', 'FDA'],
                'is_featured' => true,
            ],
            [
                'name' => 'Basmati Rice Premium',
                'short_description' => 'Aromatic long-grain basmati rice with exceptional quality and taste.',
                'description' => 'Premium quality basmati rice known for its distinctive aroma, long grains, and excellent cooking properties. Aged for optimal flavor and texture. Perfect for international markets.',
                'sku' => 'AGR-RC-001',
                'price' => 1200.00,
                'currency' => 'USD',
                'minimum_order_quantity' => 2000,
                'unit' => 'kg',
                'origin_country' => 'India',
                'packaging_details' => '25kg, 50kg PP bags or custom packaging',
                'shelf_life' => '12 months in proper storage',
                'specifications' => [
                    'Grain Length' => '6.5-7.5mm',
                    'Moisture' => 'Max 14%',
                    'Broken Grains' => 'Max 2%',
                    'Foreign Matter' => 'Max 0.1%',
                ],
                'certifications' => ['ISO 22000', 'BRC', 'Organic'],
                'is_featured' => true,
            ],
            [
                'name' => 'Turmeric Powder',
                'short_description' => 'Pure turmeric powder with high curcumin content and vibrant color.',
                'description' => 'Premium quality turmeric powder made from carefully selected turmeric rhizomes. High curcumin content ensures superior quality and health benefits. Ideal for culinary and pharmaceutical applications.',
                'sku' => 'SPC-TU-001',
                'price' => 3200.00,
                'currency' => 'USD',
                'minimum_order_quantity' => 500,
                'unit' => 'kg',
                'origin_country' => 'India',
                'packaging_details' => '25kg multi-layer paper bags or aluminum pouches',
                'shelf_life' => '24 months in dry conditions',
                'specifications' => [
                    'Curcumin Content' => 'Min 3%',
                    'Moisture' => 'Max 10%',
                    'Mesh Size' => '80-100 mesh',
                    'Color Value' => 'Min 250 ASTA units',
                ],
                'certifications' => ['ISO 22000', 'USDA Organic', 'EU Organic'],
                'is_featured' => true,
            ],
            [
                'name' => 'Black Pepper Whole',
                'short_description' => 'Premium quality whole black peppercorns with intense flavor and aroma.',
                'description' => 'Carefully selected and processed black peppercorns known for their pungent flavor and aromatic properties. Perfect for international spice markets and food processing.',
                'sku' => 'SPC-BP-001',
                'price' => 5500.00,
                'currency' => 'USD',
                'minimum_order_quantity' => 200,
                'unit' => 'kg',
                'origin_country' => 'India',
                'packaging_details' => '25kg jute bags or PP bags with inner lining',
                'shelf_life' => '36 months in proper storage',
                'specifications' => [
                    'Size' => '4.5-5.5mm',
                    'Moisture' => 'Max 12%',
                    'Piperine Content' => 'Min 5%',
                    'Light Berries' => 'Max 2%',
                ],
                'certifications' => ['ISO 22000', 'Rainforest Alliance', 'Fair Trade'],
                'is_featured' => false,
            ],
            [
                'name' => 'Cashew Nuts W320',
                'short_description' => 'Premium grade W320 cashew nuts with excellent taste and quality.',
                'description' => 'Top quality W320 grade cashew nuts, carefully processed and graded. Known for their creamy texture, rich taste, and nutritional value. Perfect for snacking and culinary applications.',
                'sku' => 'FD-CN-001',
                'price' => 8500.00,
                'currency' => 'USD',
                'minimum_order_quantity' => 100,
                'unit' => 'kg',
                'origin_country' => 'India',
                'packaging_details' => '10kg tin containers or vacuum-packed bags',
                'shelf_life' => '12 months in cool, dry conditions',
                'specifications' => [
                    'Grade' => 'W320 (320 pieces per pound)',
                    'Moisture' => 'Max 5%',
                    'Broken Nuts' => 'Max 5%',
                    'Foreign Matter' => 'Nil',
                ],
                'certifications' => ['ISO 22000', 'BRC', 'Kosher'],
                'is_featured' => true,
            ],
        ];

        foreach ($products as $productData) {
            // Find appropriate category
            $category = null;
            if (strpos($productData['sku'], 'AGR') === 0) {
                $category = $categories->where('slug', 'agricultural-products')->first();
            } elseif (strpos($productData['sku'], 'SPC') === 0) {
                $category = $categories->where('slug', 'spices-herbs')->first();
            } elseif (strpos($productData['sku'], 'FD') === 0) {
                $category = $categories->where('slug', 'food-products')->first();
            }

            if (!$category) {
                $category = $categories->first();
            }

            Product::create([
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']),
                'short_description' => $productData['short_description'],
                'description' => $productData['description'],
                'sku' => $productData['sku'],
                'category_id' => $category->id,
                'supplier_id' => $suppliers->random()->id ?? null,
                'price' => $productData['price'],
                'currency' => $productData['currency'],
                'minimum_order_quantity' => $productData['minimum_order_quantity'],
                'unit' => $productData['unit'],
                'origin_country' => $productData['origin_country'],
                'packaging_details' => $productData['packaging_details'],
                'shelf_life' => $productData['shelf_life'],
                'specifications' => $productData['specifications'],
                'certifications' => $productData['certifications'],
                'is_featured' => $productData['is_featured'],
                'is_active' => true,
                'sort_order' => 0,
            ]);
        }
    }
}