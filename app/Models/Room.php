<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['area' => 'decimal:2', 'price' => 'decimal:2', 'deposit' => 'decimal:2'];
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }

    public function utilityReadings(): HasMany
    {
        return $this->hasMany(UtilityReading::class);
    }
    public function rentalPosts(): HasMany
{
    return $this->hasMany(RentalPost::class);
}

public function amenities(): BelongsToMany
{
    return $this->belongsToMany(Amenity::class, 'room_amenities')
        ->withTimestamps();
}
}
