<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RestaurantTable;

class RestaurantTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tables = [
            [
                'name' => 'Table 1',
                'capacity' => 2,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 50, 'y' => 50, 'width' => 100, 'height' => 80])
            ],
            [
                'name' => 'Table 2',
                'capacity' => 4,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 200, 'y' => 50, 'width' => 120, 'height' => 100])
            ],
            [
                'name' => 'Table 3',
                'capacity' => 4,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 350, 'y' => 50, 'width' => 120, 'height' => 100])
            ],
            [
                'name' => 'Table 4',
                'capacity' => 6,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 50, 'y' => 200, 'width' => 140, 'height' => 120])
            ],
            [
                'name' => 'Table 5',
                'capacity' => 6,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 220, 'y' => 200, 'width' => 140, 'height' => 120])
            ],
            [
                'name' => 'Table 6',
                'capacity' => 8,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 400, 'y' => 200, 'width' => 160, 'height' => 140])
            ],
            [
                'name' => 'Table 7',
                'capacity' => 2,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 50, 'y' => 350, 'width' => 100, 'height' => 80])
            ],
            [
                'name' => 'Table 8',
                'capacity' => 4,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 200, 'y' => 350, 'width' => 120, 'height' => 100])
            ],
            [
                'name' => 'Table 9',
                'capacity' => 4,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 350, 'y' => 350, 'width' => 120, 'height' => 100])
            ],
            [
                'name' => 'Table 10',
                'capacity' => 2,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 500, 'y' => 350, 'width' => 100, 'height' => 80])
            ],
            [
                'name' => 'Bar 1',
                'capacity' => 1,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 600, 'y' => 50, 'width' => 60, 'height' => 80])
            ],
            [
                'name' => 'Bar 2',
                'capacity' => 1,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 600, 'y' => 150, 'width' => 60, 'height' => 80])
            ],
            [
                'name' => 'Bar 3',
                'capacity' => 1,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 600, 'y' => 250, 'width' => 60, 'height' => 80])
            ],
            [
                'name' => 'Bar 4',
                'capacity' => 1,
                'status' => 'available',
                'visual_coordinates' => json_encode(['x' => 600, 'y' => 350, 'width' => 60, 'height' => 80])
            ]
        ];

        foreach ($tables as $table) {
            RestaurantTable::create($table);
        }
    }
}