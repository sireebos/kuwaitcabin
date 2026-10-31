<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        // Ingredients
        InventoryItem::create([
            'property_id' => 1,
            'name' => 'Salmon Fillet',
            'sku' => 'SALMON-001',
            'type' => 'ingredient',
            'unit' => 'kg',
            'quantity' => 50,
            'reorder_level' => 10,
            'unit_cost' => 15.00,
            'supplier' => 'Fresh Fish Co.',
            'is_active' => true,
        ]);

        InventoryItem::create([
            'property_id' => 1,
            'name' => 'Ribeye Steak',
            'sku' => 'STEAK-001',
            'type' => 'ingredient',
            'unit' => 'kg',
            'quantity' => 40,
            'reorder_level' => 8,
            'unit_cost' => 25.00,
            'supplier' => 'Premium Meats Inc.',
            'is_active' => true,
        ]);

        InventoryItem::create([
            'property_id' => 1,
            'name' => 'Romaine Lettuce',
            'sku' => 'LETTUCE-001',
            'type' => 'ingredient',
            'unit' => 'kg',
            'quantity' => 30,
            'reorder_level' => 5,
            'unit_cost' => 2.50,
            'supplier' => 'Fresh Produce Ltd.',
            'is_active' => true,
        ]);

        // Beverages
        InventoryItem::create([
            'property_id' => 1,
            'name' => 'House Wine Red',
            'sku' => 'WINE-RED-001',
            'type' => 'beverage',
            'unit' => 'bottle',
            'quantity' => 100,
            'reorder_level' => 20,
            'unit_cost' => 8.00,
            'supplier' => 'Wine Distributors',
            'is_active' => true,
        ]);

        InventoryItem::create([
            'property_id' => 1,
            'name' => 'House Wine White',
            'sku' => 'WINE-WHITE-001',
            'type' => 'beverage',
            'unit' => 'bottle',
            'quantity' => 80,
            'reorder_level' => 15,
            'unit_cost' => 7.50,
            'supplier' => 'Wine Distributors',
            'is_active' => true,
        ]);

        // Supplies
        InventoryItem::create([
            'property_id' => 1,
            'name' => 'Paper Napkins',
            'sku' => 'NAPKINS-001',
            'type' => 'supply',
            'unit' => 'box',
            'quantity' => 50,
            'reorder_level' => 10,
            'unit_cost' => 5.00,
            'supplier' => 'Restaurant Supplies Co.',
            'is_active' => true,
        ]);

        InventoryItem::create([
            'property_id' => 1,
            'name' => 'Plastic Cups',
            'sku' => 'CUPS-001',
            'type' => 'supply',
            'unit' => 'box',
            'quantity' => 100,
            'reorder_level' => 20,
            'unit_cost' => 3.00,
            'supplier' => 'Restaurant Supplies Co.',
            'is_active' => true,
        ]);
    }
}

