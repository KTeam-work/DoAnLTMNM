<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amenity extends Model
{
    protected $table = 'amenities';

    protected $guarded = [];

    public function rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class, 'room_amenities')
            ->withTimestamps();
    }
}
