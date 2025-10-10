<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            [
                'customer_name' => 'John Smith',
                'company_name' => 'Smith Trading Corporation',
                'country' => 'United States',
                'message' => 'Excellent quality products and outstanding service. We have been importing from them for over 3 years and they never disappoint. Their onion powder is the best in the market.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'customer_name' => 'Maria Garcia',
                'company_name' => 'European Spice Distributors',
                'country' => 'Spain',
                'message' => 'Professional team with deep knowledge of international trade. They handle all documentation perfectly and deliver on time. Highly recommended for spice imports.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'customer_name' => 'Ahmed Al-Rashid',
                'company_name' => 'Middle East Food Supplies',
                'country' => 'UAE',
                'message' => 'Reliable partner for our rice and grain requirements. Their basmati rice quality is exceptional and pricing is very competitive. Great customer support.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'customer_name' => 'Lisa Chen',
                'company_name' => 'Pacific Rim Imports',
                'country' => 'Australia',
                'message' => 'Fast shipping and excellent packaging. Products always arrive in perfect condition. Their cashew nuts are premium quality and our customers love them.',
                'rating' => 4,
                'is_featured' => false,
                'sort_order' => 4,
            ],
            [
                'customer_name' => 'Pierre Dubois',
                'company_name' => 'French Gourmet Supplies',
                'country' => 'France',
                'message' => 'Outstanding quality control and attention to detail. Every shipment meets our strict European standards. Excellent communication throughout the process.',
                'rating' => 5,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'customer_name' => 'Robert Johnson',
                'company_name' => 'Canadian Food Distributors',
                'country' => 'Canada',
                'message' => 'Competitive prices without compromising on quality. Their turmeric powder has excellent curcumin content. Very satisfied with their service.',
                'rating' => 4,
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'customer_name' => 'Yuki Tanaka',
                'company_name' => 'Tokyo Spice Company',
                'country' => 'Japan',
                'message' => 'Consistent quality and reliable delivery schedules. They understand our market requirements and always deliver products that meet Japanese standards.',
                'rating' => 5,
                'is_featured' => true,
                'sort_order' => 7,
            ],
            [
                'customer_name' => 'Hans Mueller',
                'company_name' => 'German Organic Foods',
                'country' => 'Germany',
                'message' => 'Excellent organic certifications and documentation. Their organic products are truly premium quality. Professional handling of all export procedures.',
                'rating' => 5,
                'is_featured' => false,
                'sort_order' => 8,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}