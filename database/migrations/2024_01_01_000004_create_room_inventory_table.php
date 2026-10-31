<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['available', 'reserved', 'occupied', 'blocked'])->default('available');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->unique(['room_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_inventory');
    }
};

