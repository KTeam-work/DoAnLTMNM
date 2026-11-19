<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'unlock_token')) {
                $table->string('unlock_token', 100)->nullable()->unique()->after('status');
            }
            if (!Schema::hasColumn('users', 'unlock_requested_at')) {
                $table->timestamp('unlock_requested_at')->nullable()->after('unlock_token');
            }
            if (!Schema::hasColumn('users', 'unlock_reason')) {
                $table->text('unlock_reason')->nullable()->after('unlock_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'unlock_reason')) {
                $table->dropColumn('unlock_reason');
            }
            if (Schema::hasColumn('users', 'unlock_requested_at')) {
                $table->dropColumn('unlock_requested_at');
            }
            if (Schema::hasColumn('users', 'unlock_token')) {
                $table->dropColumn('unlock_token');
            }
        });
    }
};
