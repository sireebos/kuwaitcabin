<?php

namespace Database\Seeders;

use App\Models\Room;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        // Grand Hotel rooms
        for ($i = 1; $i <= 50; $i++) {
            Room::create([
                'property_id' => 1,
                'room_type_id' => 1,
                'room_number' => '10' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'floor' => 1,
                'status' => 'available',
            ]);
        }

        for ($i = 1; $i <= 50; $i++) {
            Room::create([
                'property_id' => 1,
                'room_type_id' => 2,
                'room_number' => '20' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'floor' => 2,
                'status' => 'available',
            ]);
        }

        for ($i = 1; $i <= 50; $i++) {
            Room::create([
                'property_id' => 1,
                'room_type_id' => 3,
                'room_number' => '30' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'floor' => 3,
                'status' => 'available',
            ]);
        }

        // Riverside Inn rooms
        for ($i = 1; $i <= 30; $i++) {
            Room::create([
                'property_id' => 2,
                'room_type_id' => 4,
                'room_number' => '10' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'floor' => 1,
                'status' => 'available',
            ]);
        }

        for ($i = 1; $i <= 20; $i++) {
            Room::create([
                'property_id' => 2,
                'room_type_id' => 5,
                'room_number' => '20' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'floor' => 2,
                'status' => 'available',
            ]);
        }
    }
}

