<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@importexport.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'phone' => '+1-555-123-4567',
            'is_active' => true,
        ]);

        // Create manager user
        User::create([
            'name' => 'Manager User',
            'email' => 'manager@importexport.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_MANAGER,
            'phone' => '+1-555-123-4568',
            'is_active' => true,
        ]);

        // Create sales user
        User::create([
            'name' => 'Sales Representative',
            'email' => 'sales@importexport.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_SALES,
            'phone' => '+1-555-123-4569',
            'is_active' => true,
        ]);

        // Create some customer users
        User::create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CUSTOMER,
            'phone' => '+1-555-987-6543',
            'company_name' => 'Smith Trading Co.',
            'country' => 'United States',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Maria Garcia',
            'email' => 'maria@example.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_CUSTOMER,
            'phone' => '+34-123-456-789',
            'company_name' => 'European Imports Ltd.',
            'country' => 'Spain',
            'is_active' => true,
        ]);
    }
}