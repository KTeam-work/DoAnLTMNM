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
        Schema::create('property_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_id');
            $table->unsignedBigInteger('service_id');
            $table->decimal('price', 15, 2);
            $table->enum('status', ['active', 'inactive']);

            // Thay vì dùng $table->timestamps(); mặc định, ta định nghĩa tường minh kiểu DATETIME2 (hoặc dateTime)
            $table->dateTime('created_at', precision: 6)->nullable(); // precision 6 tương ứng với độ chính xác DATETIME2
            $table->dateTime('updated_at', precision: 6)->nullable();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_services');
    }
};
