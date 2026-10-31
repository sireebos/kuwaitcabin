<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->foreignId('room_type_id')->constrained('room_types')->onDelete('cascade');
            $table->string('room_number');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'blocked'])->default('available');
            $table->integer('floor')->nullable();
            $table->string('wing')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['property_id', 'room_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

