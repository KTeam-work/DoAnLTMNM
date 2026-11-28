<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('contract_terminations', function (Blueprint $table) {
            $table->foreignId('utility_invoice_id')->nullable()->constrained('invoices')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('contract_terminations', fn (Blueprint $table) => $table->dropConstrainedForeignId('utility_invoice_id'));
    }
};
