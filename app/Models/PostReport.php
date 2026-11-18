<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostReport extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'resolved_at' => 'datetime',
        ];
    }

    /**
     * Tin đăng cho thuê bị tố cáo.
     */
    public function rentalPost(): BelongsTo
    {
        return $this->belongsTo(RentalPost::class);
    }

    /**
     * Người gửi tố cáo (khách tìm phòng / người thuê).
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reporter_id');
    }

    /**
     * Quản trị viên xử lý báo cáo này.
     */
    public function handler(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handler_id');
    }

    /**
     * Nhãn tiếng Việt cho lý do vi phạm.
     */
    public function getReasonLabelAttribute(): string
    {
        return match($this->reason) {
            'fake_info' => 'Thông tin / ảnh sai thực tế',
            'price_mismatch' => 'Giá thuê sai so với thực tế',
            'fraud_scam' => 'Có dấu hiệu lừa đảo / cọc tiền',
            'contact_failed' => 'Không liên hệ được chủ trọ',
            'inappropriate' => 'Nội dung phản cảm, thô tục',
            default => 'Lý do khác',
        };
    }

    /**
     * Nhãn tiếng Việt cho trạng thái xử lý.
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Chờ xử lý',
            'investigating' => 'Đang xác minh',
            'resolved' => 'Đã xử lý',
            'dismissed' => 'Đã bác bỏ',
            default => $this->status,
        };
    }

    /**
     * Class màu badge Bootstrap cho trạng thái.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning text-dark',
            'investigating' => 'info text-dark',
            'resolved' => 'success',
            'dismissed' => 'secondary',
            default => 'light text-dark',
        };
    }

    /**
     * Nhãn tiếng Việt cho hành động xử lý.
     */
    public function getActionTakenLabelAttribute(): string
    {
        return match($this->action_taken) {
            'post_hidden' => 'Đã ẩn tin đăng',
            'owner_warned' => 'Đã gửi cảnh cáo chủ trọ',
            'owner_banned' => 'Đã khóa tài khoản chủ trọ',
            default => 'Chưa có chế tài',
        };
    }
}

