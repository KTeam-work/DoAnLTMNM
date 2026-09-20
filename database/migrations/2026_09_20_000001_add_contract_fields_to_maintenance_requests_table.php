<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Gắn yêu cầu sửa chữa với hợp đồng (tenant tạo yêu cầu từ trang hợp đồng).
     */
    public function up(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->foreignId('contract_id')->nullable()->after('tenant_id')->constrained('contracts')->nullOnDelete();
            $table->string('category', 50)->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('maintenance_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('contract_id');
            $table->dropColumn('category');
        });
    }
};
