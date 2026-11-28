<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('platform_fee_rate', 5, 2)->nullable();
            $table->decimal('platform_fee', 15, 2)->nullable();
            $table->decimal('owner_amount', 15, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('payments', fn (Blueprint $table) => $table->dropColumn(['platform_fee_rate', 'platform_fee', 'owner_amount']));
    }
};
