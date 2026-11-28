<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payout_status', 20)->nullable()->index();
            $table->timestamp('payout_transferred_at')->nullable();
        });
        DB::table('payments')->where('status', 'success')->whereNotNull('platform_fee')->where('method', '!=', 'cash')->update(['payout_status' => 'pending']);
        Schema::create('payout_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('from_status', 20);
            $table->string('to_status', 20);
            $table->string('note', 500)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payout_events');
        Schema::table('payments', function (Blueprint $table) {
            $table->dropIndex(['payout_status']);
            $table->dropColumn(['payout_status', 'payout_transferred_at']);
        });
    }
};
