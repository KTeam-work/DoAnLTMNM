<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'billing_month' => 'date',
            'issue_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function contract(): BelongsTo
    {
        return $this->belongsTo(Contract::class);
    }

    public function utilityReading(): BelongsTo
    {
        return $this->belongsTo(UtilityReading::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Chuyển đổi số tiền thành chữ tiếng Việt.
     */
    public function totalInWords(): string
    {
        return self::convertNumberToVietnameseWords((float) $this->total);
    }

    public static function convertNumberToVietnameseWords(float|int $number): string
    {
        $number = round($number);
        if ($number == 0) {
            return 'Không đồng';
        }

        $units = ['', ' nghìn', ' triệu', ' tỷ', ' nghìn tỷ', ' triệu tỷ'];
        $digits = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];

        $blocks = [];
        $temp = $number;
        while ($temp > 0) {
            $blocks[] = $temp % 1000;
            $temp = (int) ($temp / 1000);
        }

        $words = [];
        for ($i = count($blocks) - 1; $i >= 0; $i--) {
            $block = $blocks[$i];
            if ($block == 0) continue;

            $hundreds = (int) ($block / 100);
            $tens = (int) (($block % 100) / 10);
            $ones = $block % 10;

            $blockWords = [];

            // Hàng trăm
            if ($hundreds > 0 || (count($blocks) > 1 && $i < count($blocks) - 1)) {
                $blockWords[] = $digits[$hundreds] . ' trăm';
            }

            // Hàng chục
            if ($tens > 1) {
                $blockWords[] = $digits[$tens] . ' mươi';
            } elseif ($tens == 1) {
                $blockWords[] = 'mười';
            } elseif ($tens == 0 && $hundreds > 0 && $ones > 0) {
                $blockWords[] = 'lẻ';
            }

            // Hàng đơn vị
            if ($tens > 1 && $ones == 1) {
                $blockWords[] = 'mốt';
            } elseif ($tens > 0 && $ones == 5) {
                $blockWords[] = 'lăm';
            } elseif ($ones > 0 || ($hundreds == 0 && $tens == 0 && count($blockWords) == 0)) {
                if ($ones > 0) {
                    $blockWords[] = $digits[$ones];
                }
            }

            $words[] = implode(' ', $blockWords) . $units[$i];
        }

        $result = trim(implode(' ', $words)) . ' đồng chẵn';
        return mb_strtoupper(mb_substr($result, 0, 1)) . mb_substr($result, 1);
    }
}
