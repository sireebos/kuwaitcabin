<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Grand Hotel Menu
        $menu = Menu::create([
            'property_id' => 1,
            'name' => 'Main Restaurant Menu',
            'slug' => 'main-restaurant-menu',
            'description' => 'Our signature restaurant menu',
            'type' => 'both',
            'is_active' => true,
        ]);

        // Categories
        $appetizers = MenuCategory::create([
            'menu_id' => $menu->id,
            'name' => 'Appetizers',
            'slug' => 'appetizers',
            'description' => 'Start your meal with our delicious appetizers',
            'sort_order' => 1,
        ]);

        $mains = MenuCategory::create([
            'menu_id' => $menu->id,
            'name' => 'Main Courses',
            'slug' => 'main-courses',
            'description' => 'Our signature main courses',
            'sort_order' => 2,
        ]);

        $desserts = MenuCategory::create([
            'menu_id' => $menu->id,
            'name' => 'Desserts',
            'slug' => 'desserts',
            'description' => 'Sweet treats to finish your meal',
            'sort_order' => 3,
        ]);

        $beverages = MenuCategory::create([
            'menu_id' => $menu->id,
            'name' => 'Beverages',
            'slug' => 'beverages',
            'description' => 'Refreshing drinks',
            'sort_order' => 4,
        ]);

        // Menu Items
        MenuItem::create([
            'menu_category_id' => $appetizers->id,
            'name' => 'Caesar Salad',
            'slug' => 'caesar-salad',
            'description' => 'Fresh romaine lettuce with parmesan and croutons',
            'price' => 12.99,
            'ingredients' => ['Romaine Lettuce', 'Parmesan', 'Croutons', 'Caesar Dressing'],
            'is_available' => true,
        ]);

        MenuItem::create([
            'menu_category_id' => $appetizers->id,
            'name' => 'Shrimp Cocktail',
            'slug' => 'shrimp-cocktail',
            'description' => 'Chilled shrimp with cocktail sauce',
            'price' => 14.99,
            'ingredients' => ['Shrimp', 'Cocktail Sauce', 'Lemon'],
            'is_available' => true,
        ]);

        MenuItem::create([
            'menu_category_id' => $mains->id,
            'name' => 'Grilled Salmon',
            'slug' => 'grilled-salmon',
            'description' => 'Fresh Atlantic salmon with lemon butter sauce',
            'price' => 28.99,
            'ingredients' => ['Salmon', 'Lemon', 'Butter', 'Herbs'],
            'is_available' => true,
        ]);

        MenuItem::create([
            'menu_category_id' => $mains->id,
            'name' => 'Ribeye Steak',
            'slug' => 'ribeye-steak',
            'description' => '12oz prime ribeye with garlic mashed potatoes',
            'price' => 35.99,
            'ingredients' => ['Ribeye Steak', 'Garlic', 'Potatoes', 'Butter'],
            'is_available' => true,
        ]);

        MenuItem::create([
            'menu_category_id' => $desserts->id,
            'name' => 'Chocolate Lava Cake',
            'slug' => 'chocolate-lava-cake',
            'description' => 'Warm chocolate cake with molten center',
            'price' => 9.99,
            'ingredients' => ['Chocolate', 'Flour', 'Eggs', 'Butter'],
            'is_available' => true,
        ]);

        MenuItem::create([
            'menu_category_id' => $beverages->id,
            'name' => 'Iced Tea',
            'slug' => 'iced-tea',
            'description' => 'Refreshing iced tea',
            'price' => 3.99,
            'ingredients' => ['Tea', 'Ice', 'Lemon'],
            'is_available' => true,
        ]);

        MenuItem::create([
            'menu_category_id' => $beverages->id,
            'name' => 'House Wine',
            'slug' => 'house-wine',
            'description' => 'Glass of our house wine selection',
            'price' => 8.99,
            'ingredients' => ['Wine'],
            'is_available' => true,
        ]);
    }
}

