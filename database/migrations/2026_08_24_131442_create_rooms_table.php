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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('room_code', 20)->unique()->nullable();
            $table->foreignId('property_id')->constrained('properties')->onDelete('cascade');
            $table->string('name', 100);
            $table->decimal('area', 8, 2);
            $table->decimal('price', 15, 2);
            $table->decimal('deposit', 15, 2);
            $table->integer('max_people');
            $table->integer('floor')->nullable();
            $table->text('description')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['available', 'pending', 'rented', 'maintenance', 'hidden'])->default('available');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
