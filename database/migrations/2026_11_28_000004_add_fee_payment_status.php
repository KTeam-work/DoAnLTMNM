<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('fee_payment_status', 20)->nullable()->index();
            $table->timestamp('fee_paid_at')->nullable();
        });
        DB::table('payments')->where('status', 'success')->whereNotNull('platform_fee')->where('method', 'cash')->update(['fee_payment_status' => 'pending']);
        DB::table('payments')->where('status', 'success')->whereNotNull('platform_fee')->where('method', 'qr')->update(['fee_payment_status' => 'paid', 'fee_paid_at' => DB::raw('paid_at')]);
    }
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['fee_payment_status']);
            $table->dropColumn(['fee_payment_status', 'fee_paid_at']);
        });
    }
};
