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
        Schema::create('utility_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->date('month');
            $table->decimal('electricity_old', 10, 2);
            $table->decimal('electricity_new', 10, 2);
            $table->decimal('electricity_price', 15, 2);
            $table->decimal('water_old', 10, 2);
            $table->decimal('water_new', 10, 2);
            $table->decimal('water_price', 15, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utility_readings');
    }
};
