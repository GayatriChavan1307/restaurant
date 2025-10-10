<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MenuItem;
use App\Models\Category;

class MenuItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = [
            // Starters
            [
                'category_id' => 1, // Starters
                'name' => 'Bruschetta',
                'description' => 'Toasted bread topped with tomatoes, garlic, and olive oil',
                'price' => 8.99,
                'is_available' => true
            ],
            [
                'category_id' => 1,
                'name' => 'Mozzarella Sticks',
                'description' => 'Crispy breaded mozzarella served with marinara sauce',
                'price' => 9.99,
                'is_available' => true
            ],
            [
                'category_id' => 1,
                'name' => 'Garlic Bread',
                'description' => 'Fresh bread with garlic butter and herbs',
                'price' => 5.99,
                'is_available' => true
            ],

            // Soups
            [
                'category_id' => 2, // Soups
                'name' => 'Tomato Basil Soup',
                'description' => 'Creamy tomato soup with fresh basil',
                'price' => 7.99,
                'is_available' => true
            ],
            [
                'category_id' => 2,
                'name' => 'Chicken Noodle Soup',
                'description' => 'Homemade chicken soup with vegetables and noodles',
                'price' => 8.99,
                'is_available' => true
            ],

            // Salads
            [
                'category_id' => 3, // Salads
                'name' => 'Caesar Salad',
                'description' => 'Romaine lettuce, parmesan cheese, croutons with caesar dressing',
                'price' => 12.99,
                'is_available' => true
            ],
            [
                'category_id' => 3,
                'name' => 'Greek Salad',
                'description' => 'Mixed greens, feta cheese, olives, tomatoes, cucumber',
                'price' => 11.99,
                'is_available' => true
            ],

            // Main Course
            [
                'category_id' => 4, // Main Course
                'name' => 'Grilled Chicken Breast',
                'description' => 'Herb-marinated chicken breast with seasonal vegetables',
                'price' => 18.99,
                'is_available' => true
            ],
            [
                'category_id' => 4,
                'name' => 'Beef Tenderloin',
                'description' => '8oz tenderloin with garlic mashed potatoes',
                'price' => 28.99,
                'is_available' => true
            ],
            [
                'category_id' => 4,
                'name' => 'Vegetable Stir Fry',
                'description' => 'Fresh vegetables in soy sauce with steamed rice',
                'price' => 14.99,
                'is_available' => true
            ],

            // Pasta
            [
                'category_id' => 5, // Pasta
                'name' => 'Spaghetti Carbonara',
                'description' => 'Pasta with eggs, cheese, pancetta, and black pepper',
                'price' => 16.99,
                'is_available' => true
            ],
            [
                'category_id' => 5,
                'name' => 'Fettuccine Alfredo',
                'description' => 'Fettuccine pasta with creamy alfredo sauce',
                'price' => 15.99,
                'is_available' => true
            ],

            // Seafood
            [
                'category_id' => 6, // Seafood
                'name' => 'Grilled Salmon',
                'description' => 'Atlantic salmon with lemon butter sauce and rice',
                'price' => 24.99,
                'is_available' => true
            ],
            [
                'category_id' => 6,
                'name' => 'Shrimp Scampi',
                'description' => 'Jumbo shrimp in garlic butter sauce with pasta',
                'price' => 22.99,
                'is_available' => true
            ],

            // Desserts
            [
                'category_id' => 7, // Desserts
                'name' => 'Tiramisu',
                'description' => 'Classic Italian dessert with coffee and mascarpone',
                'price' => 8.99,
                'is_available' => true
            ],
            [
                'category_id' => 7,
                'name' => 'Chocolate Lava Cake',
                'description' => 'Warm chocolate cake with molten center and vanilla ice cream',
                'price' => 9.99,
                'is_available' => true
            ],
            [
                'category_id' => 7,
                'name' => 'New York Cheesecake',
                'description' => 'Creamy cheesecake with berry compote',
                'price' => 7.99,
                'is_available' => true
            ],

            // Beverages
            [
                'category_id' => 8, // Beverages
                'name' => 'Fresh Orange Juice',
                'description' => 'Freshly squeezed orange juice',
                'price' => 4.99,
                'is_available' => true
            ],
            [
                'category_id' => 8,
                'name' => 'Lemonade',
                'description' => 'Fresh lemonade with mint',
                'price' => 3.99,
                'is_available' => true
            ],
            [
                'category_id' => 8,
                'name' => 'Iced Tea',
                'description' => 'House-made iced tea',
                'price' => 3.49,
                'is_available' => true
            ],

            // Coffee & Tea
            [
                'category_id' => 9, // Coffee & Tea
                'name' => 'Espresso',
                'description' => 'Single shot of espresso',
                'price' => 3.99,
                'is_available' => true
            ],
            [
                'category_id' => 9,
                'name' => 'Cappuccino',
                'description' => 'Espresso with steamed milk and foam',
                'price' => 4.99,
                'is_available' => true
            ],
            [
                'category_id' => 9,
                'name' => 'Green Tea',
                'description' => 'Premium green tea',
                'price' => 3.49,
                'is_available' => true
            ],

            // Alcoholic Drinks
            [
                'category_id' => 10, // Alcoholic Drinks
                'name' => 'House Red Wine',
                'description' => 'Glass of house red wine',
                'price' => 8.99,
                'is_available' => true
            ],
            [
                'category_id' => 10,
                'name' => 'House White Wine',
                'description' => 'Glass of house white wine',
                'price' => 8.99,
                'is_available' => true
            ],
            [
                'category_id' => 10,
                'name' => 'Draft Beer',
                'description' => 'Local craft beer on tap',
                'price' => 6.99,
                'is_available' => true
            ]
        ];

        foreach ($menuItems as $item) {
            MenuItem::create($item);
        }
    }
}