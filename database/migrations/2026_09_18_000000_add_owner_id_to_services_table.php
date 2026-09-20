<?php

use Illuminate\Database\Migrations\Migration;
return new class extends Migration
{
    public function up(): void
    {
        // The base services migration now owns this column. This migration is
        // intentionally retained because it has already been applied locally.
    }

    public function down(): void
    {
        // See up().
    }
};
