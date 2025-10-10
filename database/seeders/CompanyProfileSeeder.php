<?php

namespace Database\Seeders;

use App\Models\CompanyProfile;
use Illuminate\Database\Seeder;

class CompanyProfileSeeder extends Seeder
{
    public function run(): void
    {
        CompanyProfile::create([
            'company_name' => 'Global ImportExport Solutions',
            'tagline' => 'Connecting Markets, Delivering Quality Worldwide',
            'description' => 'Leading import-export company specializing in high-quality agricultural products, spices, and food items with global reach.',
            'about_us' => 'Global ImportExport Solutions has been at the forefront of international trade for over 15 years. We specialize in sourcing and supplying premium quality agricultural products, spices, food items, and industrial goods to customers worldwide. Our commitment to quality, reliability, and customer satisfaction has made us a trusted partner for businesses across 50+ countries.',
            'mission' => 'To facilitate seamless global trade by providing high-quality products, reliable services, and building lasting partnerships with suppliers and customers worldwide.',
            'vision' => 'To be the most trusted and preferred global trade partner, known for our commitment to quality, innovation, and customer satisfaction in international commerce.',
            'values' => 'Integrity, Quality, Reliability, Innovation, and Customer-centricity guide everything we do. We believe in building long-term relationships based on trust and mutual success.',
            'email' => 'info@importexport.com',
            'phone' => '+1-555-123-4567',
            'address' => '123 Business Street, Trade District',
            'city' => 'New York',
            'state' => 'NY',
            'country' => 'United States',
            'postal_code' => '10001',
            'website' => 'www.importexport.com',
            'established_year' => 2008,
            'employee_count' => 150,
            'annual_turnover' => 50000000.00,
            'export_countries' => [
                'United States', 'Canada', 'United Kingdom', 'Germany', 'France',
                'Netherlands', 'Belgium', 'Italy', 'Spain', 'Australia',
                'New Zealand', 'Japan', 'South Korea', 'Singapore', 'Malaysia',
                'Thailand', 'UAE', 'Saudi Arabia', 'Kuwait', 'Qatar',
                'South Africa', 'Kenya', 'Nigeria', 'Brazil', 'Argentina',
                'Chile', 'Mexico', 'Russia', 'Poland', 'Czech Republic',
                'Hungary', 'Romania', 'Bulgaria', 'Croatia', 'Serbia',
                'Turkey', 'Israel', 'Egypt', 'Morocco', 'Tunisia',
                'Bangladesh', 'Sri Lanka', 'Nepal', 'Bhutan', 'Myanmar',
                'Vietnam', 'Philippines', 'Indonesia', 'Cambodia', 'Laos'
            ],
            'certifications' => [
                'ISO 22000:2018 - Food Safety Management',
                'HACCP - Hazard Analysis Critical Control Points',
                'BRC - British Retail Consortium',
                'IFS - International Featured Standards',
                'USDA Organic Certification',
                'EU Organic Certification',
                'Fair Trade Certified',
                'Rainforest Alliance Certified',
                'Kosher Certification',
                'Halal Certification'
            ],
            'social_links' => [
                'facebook' => 'https://facebook.com/importexport',
                'twitter' => 'https://twitter.com/importexport',
                'linkedin' => 'https://linkedin.com/company/importexport',
                'instagram' => 'https://instagram.com/importexport',
                'youtube' => 'https://youtube.com/importexport'
            ],
            'business_hours' => [
                'monday' => '09:00 - 18:00',
                'tuesday' => '09:00 - 18:00',
                'wednesday' => '09:00 - 18:00',
                'thursday' => '09:00 - 18:00',
                'friday' => '09:00 - 18:00',
                'saturday' => '09:00 - 14:00',
                'sunday' => 'Closed'
            ],
            'meta_title' => 'Global ImportExport Solutions - Premium Quality Products Worldwide',
            'meta_description' => 'Leading import-export company specializing in agricultural products, spices, and food items. Quality guaranteed, competitive prices, global shipping to 50+ countries.',
            'meta_keywords' => 'import, export, agricultural products, spices, food items, global trade, international shipping, quality products, competitive prices'
        ]);
    }
}