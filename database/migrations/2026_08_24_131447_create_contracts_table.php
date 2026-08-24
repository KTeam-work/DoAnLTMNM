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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_code', 50)->unique();
            $table->foreignId('viewing_appointment_id')->nullable()->constrained('viewing_appointments')->onDelete('set null');
            $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('tenant_id')->constrained('users')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('rent', 15, 2);
            $table->decimal('deposit', 15, 2);
            $table->enum('payment_cycle', ['monthly', 'quarterly'])->default('monthly');
            $table->text('terms')->nullable();
            $table->enum('status', ['draft', 'active', 'expired', 'terminated'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->decimal('initial_electricity_reading', 10, 2)->default(0);
            $table->decimal('initial_water_reading', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
