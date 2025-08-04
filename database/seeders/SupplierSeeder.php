<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name' => 'Fresh Market Foods',
                'contact_person' => 'John Smith',
                'email' => 'john@freshmarket.com',
                'phone' => '555-0101',
                'address' => '123 Market St, City, State 12345',
                'notes' => 'Primary supplier for fresh produce and dairy'
            ],
            [
                'name' => 'Quality Meats Co.',
                'contact_person' => 'Sarah Johnson',
                'email' => 'sarah@qualitymeats.com',
                'phone' => '555-0102',
                'address' => '456 Butcher Ave, City, State 12345',
                'notes' => 'Premium meat and poultry supplier'
            ],
            [
                'name' => 'Ocean Fresh Seafood',
                'contact_person' => 'Mike Wilson',
                'email' => 'mike@oceanfresh.com',
                'phone' => '555-0103',
                'address' => '789 Harbor Dr, City, State 12345',
                'notes' => 'Fresh seafood and fish supplier'
            ],
            [
                'name' => 'Beverage Distributors Inc.',
                'contact_person' => 'Lisa Brown',
                'email' => 'lisa@beveragedist.com',
                'phone' => '555-0104',
                'address' => '321 Drink Blvd, City, State 12345',
                'notes' => 'Soft drinks and alcoholic beverages'
            ],
            [
                'name' => 'Restaurant Supply Co.',
                'contact_person' => 'David Lee',
                'email' => 'david@restaurantsupply.com',
                'phone' => '555-0105',
                'address' => '654 Supply Rd, City, State 12345',
                'notes' => 'Kitchen equipment and cleaning supplies'
            ],
            [
                'name' => 'Pantry Essentials',
                'contact_person' => 'Maria Garcia',
                'email' => 'maria@pantryessentials.com',
                'phone' => '555-0106',
                'address' => '987 Pantry Ln, City, State 12345',
                'notes' => 'Dry goods and pantry items'
            ]
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
}
