<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return ['read_at' => 'datetime'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Tạo thông báo cho người dùng. Dùng cho mọi sự kiện hệ thống.
     */
    public static function notify(
        int $userId,
        string $title,
        string $message,
        string $type = 'general',
        ?string $referenceType = null,
        ?int $referenceId = null
    ): self {
        return static::query()->create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    /**
     * Link điều hướng theo dữ liệu tham chiếu (chịu lỗi khi bản ghi đã xóa).
     */
    public function targetUrl(): ?string
    {
        try {
            return match ($this->reference_type) {
                'invoice' => $this->reference_id ? route('tenant.invoices.show', $this->reference_id) : null,
                'maintenance' => $this->reference_id ? route('tenant.maintenance.show', $this->reference_id) : null,
                'contract' => $this->reference_id ? route('tenant.contracts.show', $this->reference_id) : null,
                default => null,
            };
        } catch (\Throwable) {
            return null;
        }
    }
}
