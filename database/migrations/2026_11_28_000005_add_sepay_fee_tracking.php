<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('fee_transaction_code', 100)->nullable()->unique();
            $table->string('fee_payment_source', 20)->nullable();
        });
        Schema::create('sepay_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code', 100)->unique();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('purpose', 20);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('sepay_receipts');
        Schema::table('payments', function (Blueprint $table) {
            $table->dropUnique(['fee_transaction_code']);
            $table->dropColumn(['fee_transaction_code', 'fee_payment_source']);
        });
    }
};
