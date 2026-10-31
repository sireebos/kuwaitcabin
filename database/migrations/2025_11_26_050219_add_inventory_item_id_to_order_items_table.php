<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Make menu_item_id nullable for bar orders
            $table->foreignId('menu_item_id')->nullable()->change();
            // Add inventory_item_id for bar orders
            $table->foreignId('inventory_item_id')->nullable()->constrained('inventory_items')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeignKeyIfExists(['inventory_item_id']);
            $table->dropColumn('inventory_item_id');
            $table->foreignId('menu_item_id')->nullable(false)->change();
        });
    }
};
