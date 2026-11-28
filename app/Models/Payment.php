<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saving(function (Payment $payment) {
            if ($payment->status === 'success' && $payment->platform_fee === null) {
                $amount = round((float) $payment->amount, 2);
                $fee = round($amount * 0.05, 2);
                $payment->platform_fee_rate = 5;
                $payment->platform_fee = $fee;
                $payment->owner_amount = round($amount - $fee, 2);
            }
            if ($payment->status === 'success' && $payment->platform_fee !== null && $payment->fee_payment_status === null && (! $payment->exists || $payment->isDirty('status'))) {
                if ($payment->method === 'cash') {
                    $payment->fee_payment_status = 'pending';
                } elseif ($payment->method === 'qr') {
                    $payment->fee_payment_status = 'paid';
                    $payment->fee_paid_at = $payment->paid_at ?? now();
                }
            }
        });
    }

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'platform_fee_rate' => 'decimal:2',
            'platform_fee' => 'decimal:2',
            'owner_amount' => 'decimal:2',
            'paid_at' => 'datetime',
            'fee_paid_at' => 'datetime',
        ];
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }
}
