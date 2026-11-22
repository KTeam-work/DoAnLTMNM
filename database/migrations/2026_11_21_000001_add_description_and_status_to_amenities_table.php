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
        Schema::table('amenities', function (Blueprint $table) {
            if (!Schema::hasColumn('amenities', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('amenities', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('amenities', function (Blueprint $table) {
            if (Schema::hasColumn('amenities', 'status')) {
                $table->dropColumn('status');
            }
            if (Schema::hasColumn('amenities', 'description')) {
                $table->dropColumn('description');
            }
        });
    }
};
