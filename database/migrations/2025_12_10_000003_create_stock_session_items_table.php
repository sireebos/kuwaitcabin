<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_session_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_session_id')->constrained('stock_sessions')->onDelete('cascade');
            $table->foreignId('inventory_item_id')->constrained()->onDelete('cascade');
            $table->decimal('opening_quantity', 10, 3);
            $table->decimal('quantity_received', 10, 3)->default(0);
            $table->decimal('quantity_used', 10, 3)->default(0);
            $table->decimal('manual_adjustment', 10, 3)->default(0);
            $table->text('adjustment_reason')->nullable();
            $table->decimal('closing_quantity', 10, 3)->nullable();
            $table->decimal('expected_closing', 10, 3)->nullable();
            $table->decimal('variance', 10, 3)->nullable();
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('opening_value', 10, 2);
            $table->decimal('closing_value', 10, 2)->nullable();
            $table->timestamps();
            
            $table->unique(['stock_session_id', 'inventory_item_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_session_items');
    }
};
