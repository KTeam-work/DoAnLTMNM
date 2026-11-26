<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('property_amenities', function (Blueprint $table) {
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
            $table->primary(['property_id', 'amenity_id']);
        });

        // Existing room assignments determine which property owns each setting.
        $existing = DB::table('room_amenities')
            ->join('rooms', 'rooms.id', '=', 'room_amenities.room_id')
            ->join('amenities', 'amenities.id', '=', 'room_amenities.amenity_id')
            ->select('rooms.property_id', 'amenities.id as amenity_id', 'amenities.description', 'amenities.status')
            ->distinct()->get();
        foreach ($existing as $item) {
            DB::table('property_amenities')->insert([
                'property_id' => $item->property_id, 'amenity_id' => $item->amenity_id,
                'description' => $item->description, 'status' => $item->status,
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('property_amenities');
    }
};
