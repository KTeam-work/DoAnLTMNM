<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UtilityReading extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'month' => 'date',
            'electricity_old' => 'decimal:2',
            'electricity_new' => 'decimal:2',
            'electricity_price' => 'decimal:2',
            'water_old' => 'decimal:2',
            'water_new' => 'decimal:2',
            'water_price' => 'decimal:2',
        ];
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
