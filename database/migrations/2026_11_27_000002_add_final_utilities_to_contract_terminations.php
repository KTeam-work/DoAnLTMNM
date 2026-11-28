<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contract_terminations', function (Blueprint $table) {
            $table->foreignId('utility_reading_id')->nullable()->constrained('utility_readings')->nullOnDelete();
            foreach (['electricity_old', 'electricity_new', 'electricity_price', 'water_old', 'water_new', 'water_price', 'utility_total'] as $field) {
                $table->decimal($field, 15, 2)->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('contract_terminations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('utility_reading_id');
            $table->dropColumn(['electricity_old', 'electricity_new', 'electricity_price', 'water_old', 'water_new', 'water_price', 'utility_total']);
        });
    }
};
