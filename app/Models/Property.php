<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Property extends Model
{
    protected $guarded = [];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities')
            ->withPivot(['description', 'status'])->withTimestamps();
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function thumbnail(): HasOne
    {
        return $this->hasOne(PropertyImage::class)
            ->where('is_thumbnail', true);
    }
}
