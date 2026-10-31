<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    public function run(): void
    {
        RoomType::create([
            'property_id' => 1,
            'name' => 'Standard Room',
            'slug' => 'standard-room',
            'description' => 'Comfortable room with basic amenities',
            'capacity' => 2,
            'base_price' => 100,
            'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Private Bathroom'],
        ]);

        RoomType::create([
            'property_id' => 1,
            'name' => 'Deluxe Room',
            'slug' => 'deluxe-room',
            'description' => 'Spacious room with premium amenities',
            'capacity' => 2,
            'base_price' => 150,
            'amenities' => ['WiFi', 'Smart TV', 'Air Conditioning', 'Luxury Bathroom', 'Mini Bar', 'Work Desk'],
        ]);

        RoomType::create([
            'property_id' => 1,
            'name' => 'Suite',
            'slug' => 'suite',
            'description' => 'Luxurious suite with separate living area',
            'capacity' => 4,
            'base_price' => 250,
            'amenities' => ['WiFi', 'Smart TV', 'Air Conditioning', 'Luxury Bathroom', 'Mini Bar', 'Work Desk', 'Living Area', 'Jacuzzi'],
        ]);

        RoomType::create([
            'property_id' => 2,
            'name' => 'Standard Room',
            'slug' => 'standard-room-riverside',
            'description' => 'Comfortable room with basic amenities',
            'capacity' => 2,
            'base_price' => 80,
            'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Private Bathroom'],
        ]);

        RoomType::create([
            'property_id' => 2,
            'name' => 'River View Room',
            'slug' => 'river-view-room',
            'description' => 'Room with scenic river views',
            'capacity' => 2,
            'base_price' => 120,
            'amenities' => ['WiFi', 'TV', 'Air Conditioning', 'Private Bathroom', 'Balcony'],
        ]);
    }
}

