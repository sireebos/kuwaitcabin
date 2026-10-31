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
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $permissions = $managerRole->permissions ?? [];

            // Add bar permissions if not already present
            if (!in_array('manage_bar_orders', $permissions)) {
                $permissions[] = 'manage_bar_orders';
            }
            if (!in_array('create_bar_orders', $permissions)) {
                $permissions[] = 'create_bar_orders';
            }

            $managerRole->update(['permissions' => $permissions]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $managerRole = Role::where('name', 'manager')->first();
        if ($managerRole) {
            $permissions = $managerRole->permissions ?? [];

            // Remove bar permissions
            $permissions = array_filter($permissions, function ($permission) {
                return $permission !== 'manage_bar_orders' && $permission !== 'create_bar_orders';
            });

            $managerRole->update(['permissions' => array_values($permissions)]);
        }
    }
};
