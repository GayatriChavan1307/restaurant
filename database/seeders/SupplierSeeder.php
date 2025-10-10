<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Rajesh Kumar',
                'company_name' => 'Kumar Agro Exports',
                'email' => 'rajesh@kumaragro.com',
                'phone' => '+91-9876543210',
                'address' => 'Plot No. 123, Industrial Area',
                'city' => 'Nashik',
                'state' => 'Maharashtra',
                'country' => 'India',
                'postal_code' => '422001',
                'website' => 'www.kumaragro.com',
                'contact_person' => 'Rajesh Kumar',
                'description' => 'Leading supplier of premium quality onions and agricultural products with over 15 years of experience in export business.',
                'certifications' => ['ISO 22000', 'HACCP', 'Organic India'],
                'rating' => 4.8,
            ],
            [
                'name' => 'Priya Sharma',
                'company_name' => 'Spice Valley Exports',
                'email' => 'priya@spicevalley.com',
                'phone' => '+91-9123456789',
                'address' => '456 Spice Market Road',
                'city' => 'Kochi',
                'state' => 'Kerala',
                'country' => 'India',
                'postal_code' => '682001',
                'website' => 'www.spicevalley.com',
                'contact_person' => 'Priya Sharma',
                'description' => 'Specialized in premium spices and herbs with direct sourcing from farmers and modern processing facilities.',
                'certifications' => ['ISO 22000', 'USDA Organic', 'Fair Trade'],
                'rating' => 4.9,
            ],
            [
                'name' => 'Mohammed Ali',
                'company_name' => 'Golden Rice Mills',
                'email' => 'ali@goldenrice.com',
                'phone' => '+91-9988776655',
                'address' => '789 Rice Mill Complex',
                'city' => 'Amritsar',
                'state' => 'Punjab',
                'country' => 'India',
                'postal_code' => '143001',
                'website' => 'www.goldenrice.com',
                'contact_person' => 'Mohammed Ali',
                'description' => 'Premium basmati rice supplier with state-of-the-art milling and packaging facilities.',
                'certifications' => ['ISO 22000', 'BRC', 'IFS'],
                'rating' => 4.7,
            ],
            [
                'name' => 'Sunita Patel',
                'company_name' => 'Cashew King Exports',
                'email' => 'sunita@cashewking.com',
                'phone' => '+91-9876512345',
                'address' => '321 Cashew Processing Unit',
                'city' => 'Mangalore',
                'state' => 'Karnataka',
                'country' => 'India',
                'postal_code' => '575001',
                'website' => 'www.cashewking.com',
                'contact_person' => 'Sunita Patel',
                'description' => 'Leading cashew processor and exporter with modern facilities and quality assurance.',
                'certifications' => ['ISO 22000', 'BRC', 'Kosher'],
                'rating' => 4.6,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}