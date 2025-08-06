<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'superadmin',
            'email_verified_at' => now(),
        ]);

        // Reception
        User::create([
            'name' => 'Reception Staff',
            'email' => 'reception@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'reception',
            'email_verified_at' => now(),
        ]);

        // Waiters
        User::create([
            'name' => 'John Waiter',
            'email' => 'waiter1@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Sarah Waiter',
            'email' => 'waiter2@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'waiter',
            'email_verified_at' => now(),
        ]);

        // Kitchen Staff
        User::create([
            'name' => 'Chef Mike',
            'email' => 'kitchen@restaurant.com',
            'password' => Hash::make('password'),
            'role' => 'kitchen',
            'email_verified_at' => now(),
        ]);
    }
}