<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('utility_readings', function (Blueprint $table) {
            $table->boolean('reading_date_confirmed')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('utility_readings', function (Blueprint $table) {
            $table->dropColumn('reading_date_confirmed');
        });
    }
};
