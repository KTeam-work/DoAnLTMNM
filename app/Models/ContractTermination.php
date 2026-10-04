<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContractTermination extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['return_date' => 'date', 'deposit_amount' => 'decimal:2', 'deduction_amount' => 'decimal:2', 'refund_amount' => 'decimal:2'];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }
}
