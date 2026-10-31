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
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('entity_type'); // 'guest', 'inventory', 'room', etc.
            $table->string('name');
            $table->string('label');
            $table->enum('type', ['text', 'number', 'email', 'phone', 'date', 'textarea', 'select', 'checkbox'])->default('text');
            $table->text('options')->nullable(); // JSON for select options
            $table->boolean('is_required')->default(false);
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['property_id', 'entity_type', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_fields');
    }
};
