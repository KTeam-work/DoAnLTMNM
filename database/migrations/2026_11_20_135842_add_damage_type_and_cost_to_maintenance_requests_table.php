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
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->enum('damage_type', ['landlord', 'tenant'])->nullable()->after('priority');
            $table->decimal('cost', 15, 2)->default(0)->after('damage_type');
            $table->foreignId('invoice_id')->nullable()->after('cost')->constrained('invoices')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('invoice_id');
            $table->dropColumn(['damage_type', 'cost']);
        });
    }
};
