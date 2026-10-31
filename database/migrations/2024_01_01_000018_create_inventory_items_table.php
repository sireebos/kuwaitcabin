<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name');
            $table->string('sku')->unique();
            $table->enum('type', ['ingredient', 'beverage', 'supply'])->default('ingredient');
            $table->string('unit');
            $table->decimal('quantity', 10, 2);
            $table->decimal('reorder_level', 10, 2);
            $table->decimal('unit_cost', 10, 2);
            $table->string('supplier')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
};

