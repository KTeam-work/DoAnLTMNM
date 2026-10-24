<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalPostImage extends Model
{
    protected $fillable = [
        'rental_post_id',
        'image_path',
        'is_thumbnail',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_thumbnail' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function rentalPost(): BelongsTo
    {
        return $this->belongsTo(RentalPost::class);
    }
}
