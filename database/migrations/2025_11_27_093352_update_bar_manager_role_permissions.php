<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $barManagerRole = Role::where('name', 'bar_manager')->first();
        if ($barManagerRole) {
            $barManagerRole->update([
                'description' => 'Can manage bar operations and orders',
                'permissions' => [
                    'view_dashboard',
                    'manage_bar_orders',
                    'create_bar_orders',
                    'update_order_status',
                ],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $barManagerRole = Role::where('name', 'bar_manager')->first();
        if ($barManagerRole) {
            $barManagerRole->update([
                'description' => 'Can manage bar operations, orders, and inventory',
                'permissions' => [
                    'view_dashboard',
                    'manage_bar_orders',
                    'create_bar_orders',
                    'manage_inventory',
                    'view_reports',
                    'update_order_status',
                ],
            ]);
        }
    }
};
