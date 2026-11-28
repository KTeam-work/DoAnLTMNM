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

    public function getTitleAttribute(?string $value): string
    {
        if (!$value) {
            return '';
        }
        $clean = preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{FE00}-\x{FE0F}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{20D0}-\x{20FF}\x{2300}-\x{23FF}\x{2B50}\x{2B55}\x{2934}\x{2935}\x{25AA}\x{25AB}\x{25FE}\x{25FD}\x{25FB}\x{25FC}\x{25B6}\x{25C0}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F251}]/u', '', $value);
        return trim(str_replace(['✓', '✔', '📅', '❌', '✅', '⚠️', '🚫', 'ℹ️', '🔔', '⏰', '🔍', '💬', '🗓️', '💰', '💳', '🔧', '⭐', '📜', '🕒'], '', $clean));
    }

    
    # Tạo thông báo cho người dùng. Dùng cho mọi sự kiện hệ thống.
    
    public static function notify(
        int $userId,
        string $title,
        string $message,
        string $type = 'general',
        ?string $referenceType = null,
        ?int $referenceId = null
    ): self {
        $cleanTitle = trim(str_replace(
            ['✓', '✔', '📅', '❌', '✅', '⚠️', '🚫', 'ℹ️', '🔔', '⏰', '🔍', '💬', '🗓️', '💰', '💳', '🔧', '⭐', '📜', '🕒'],
            '',
            preg_replace('/[\x{1F600}-\x{1F64F}\x{1F300}-\x{1F5FF}\x{1F680}-\x{1F6FF}\x{1F1E0}-\x{1F1FF}\x{2600}-\x{26FF}\x{2700}-\x{27BF}\x{FE00}-\x{FE0F}\x{1F900}-\x{1F9FF}\x{1FA00}-\x{1FAFF}\x{20D0}-\x{20FF}\x{2300}-\x{23FF}\x{2B50}\x{2B55}\x{2934}\x{2935}\x{25AA}\x{25AB}\x{25FE}\x{25FD}\x{25FB}\x{25FC}\x{25B6}\x{25C0}\x{1F004}\x{1F0CF}\x{1F170}-\x{1F251}]/u', '', $title)
        ));

        return static::query()->create([
            'user_id' => $userId,
            'title' => $cleanTitle,
            'message' => $message,
            'type' => $type,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }

    
     # Link điều hướng theo dữ liệu tham chiếu (chịu lỗi khi bản ghi đã xóa).
     
    public function targetUrl(): ?string
    {
        try {
            return match ($this->reference_type) {
                'invoice' => $this->reference_id ? route('tenant.invoices.show', $this->reference_id) : null,
                'maintenance' => $this->reference_id ? route('tenant.maintenance.show', $this->reference_id) : null,
                'contract' => $this->reference_id ? route('tenant.contracts.show', $this->reference_id) : null,
                'rental_post' => route('owner.rental-posts.index'),
                'platform_fee' => \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'owner'
                    ? route('owner.platform-fees.index') : null,
                'user' => route('admin.users', ['status' => 'unlock_requested']),
                'appointment' => \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'owner'
                    ? route('owner.appointments.index')
                    : route('appointments.index'),
                'report' => \Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'admin'
                    ? route('admin.reports.index')
                    : (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->role === 'owner'
                        ? route('owner.rental-posts.index')
                        : route('tenant.reports.index')),
                default => null,
            };
        } catch (\Throwable) {
            return null;
        }
    }
}
