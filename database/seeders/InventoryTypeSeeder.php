<?php

namespace Database\Seeders;

use App\Models\InventoryType;
use App\Models\Property;
use Illuminate\Database\Seeder;

class InventoryTypeSeeder extends Seeder
{
    public function run(): void
    {
        $properties = Property::all();
        
        $defaultTypes = [
            ['name' => 'ingredient', 'description' => 'Ingredients used in cooking'],
            ['name' => 'beverage', 'description' => 'Beverages including drinks and juices'],
            ['name' => 'supply', 'description' => 'General supplies and non-food items'],
        ];

        foreach ($properties as $property) {
            foreach ($defaultTypes as $type) {
                InventoryType::firstOrCreate(
                    ['property_id' => $property->id, 'name' => $type['name']],
                    ['description' => $type['description'], 'is_active' => true]
                );
            }
        }
    }
}
