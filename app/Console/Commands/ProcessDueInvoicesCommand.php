<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ProcessDueInvoicesCommand extends Command
{
    /**
     * Tên cú pháp gọi lệnh trong Terminal.
     */
    protected $signature = 'invoices:process-due {--dry-run : Chỉ quét kiểm tra, không lưu dữ liệu} {--simulate-date= : Ngày mô phỏng (YYYY-MM-DD) để test demo}';

    /**
     * Mô tả chức năng của lệnh.
     */
    protected $description = 'Tự động quét hóa đơn đến hạn để nhắc nợ và chuyển trạng thái quá hạn (Overdue)';

    public function handle(): int
    {
        $isDryRun = (bool) $this->option('dry-run');
        $simulateDate = $this->option('simulate-date');
        $customDate = $simulateDate ? Carbon::parse($simulateDate)->startOfDay() : null;

        if ($customDate) {
            $this->warn("⚙️ ĐANG MÔ PHỎNG NGÀY HỆ THỐNG: " . $customDate->format('d/m/Y'));
        }
        $this->info($isDryRun ? '--- ĐANG CHẠY CHẾ ĐỘ THỬ NGHIỆM (DRY-RUN) ---' : '--- BẮT ĐẦU QUÉT HÓA ĐƠN ĐỊNH KỲ ---');

        $stats = self::processDueInvoices($isDryRun, null, $customDate);

        $this->table(
            ['Chỉ số thống kê', 'Số lượng'],
            [
                ['Tổng số hóa đơn chưa thanh toán đã quét', $stats['total_scanned']],
                ['Thông báo nhắc nợ sắp đến hạn gửi khách', $stats['upcoming_reminders']],
                ['Thông báo hạn chót hôm nay gửi khách', $stats['due_today_reminders']],
                ['Hóa đơn chuyển trạng thái Quá hạn (Overdue)', $stats['marked_overdue']],
                ['Thông báo nợ quá hạn gửi chủ nhà', $stats['owner_overdue_alerts']],
            ]
        );

        $this->info("✓ Đã hoàn tất xử lý hóa đơn định kỳ thành công!");
        return Command::SUCCESS;
    }

    /**
     * Hàm dùng chung cho cả Artisan Command và Web Controller Trigger.
     */
    public static function processDueInvoices(bool $dryRun = false, ?int $ownerId = null, ?Carbon $customToday = null): array
    {
        $today = $customToday ? $customToday->copy()->startOfDay() : Carbon::today();

        $query = Invoice::query()
            ->whereIn('status', ['unpaid', 'pending', 'overdue'])
            ->with(['contract.room.property', 'contract.tenant', 'contract.owner']);

        if ($ownerId) {
            $query->whereHas('contract', fn ($q) => $q->where('owner_id', $ownerId));
        }

        $invoices = $query->get();

        $stats = [
            'total_scanned' => $invoices->count(),
            'upcoming_reminders' => 0,
            'due_today_reminders' => 0,
            'marked_overdue' => 0,
            'owner_overdue_alerts' => 0,
        ];

        foreach ($invoices as $invoice) {
            $tenant = $invoice->contract?->tenant;
            $owner = $invoice->contract?->owner;
            $roomName = $invoice->contract?->room?->name ?? 'Phòng';
            $dueDate = Carbon::parse($invoice->due_date)->startOfDay();

            // Tính số ngày chênh lệch: (dueDate - today)
            // $diffInDays > 0: còn hạn trong tương lai
            // $diffInDays === 0: đúng ngày hôm nay
            // $diffInDays < 0: đã quá hạn
            $diffInDays = $today->diffInDays($dueDate, false);

            // 1. Trường hợp SẮP ĐẾN HẠN (còn 1 đến 2 ngày)
            if ($diffInDays > 0 && $diffInDays <= 2 && in_array($invoice->status, ['unpaid', 'pending'])) {
                if ($tenant && !self::alreadyNotifiedToday($tenant->id, $invoice->id)) {
                    $stats['upcoming_reminders']++;
                    if (!$dryRun) {
                        Notification::notify(
                            $tenant->id,
                            "🔔 Nhắc nợ: Hóa đơn phòng {$roomName} sắp đến hạn",
                            "Hóa đơn #{$invoice->invoice_code} kỳ {$invoice->billing_month->format('m/Y')} (" . number_format((float)$invoice->total) . " đ) sẽ đến hạn thanh toán trong {$diffInDays} ngày tới (Hạn nộp: {$dueDate->format('d/m/Y')}). Quý khách vui lòng thanh toán đúng hạn.",
                            'invoice',
                            'invoice',
                            $invoice->id
                        );
                    }
                }
            }

            // 2. Trường hợp ĐÚNG HÔM NAY LÀ HẠN CHÓT (diffInDays === 0)
            elseif ($diffInDays === 0 && in_array($invoice->status, ['unpaid', 'pending'])) {
                if ($tenant && !self::alreadyNotifiedToday($tenant->id, $invoice->id)) {
                    $stats['due_today_reminders']++;
                    if (!$dryRun) {
                        Notification::notify(
                            $tenant->id,
                            "⏰ Hôm nay là hạn chót thanh toán tiền phòng {$roomName}",
                            "Hôm nay ({$dueDate->format('d/m/Y')}) là hạn chót thanh toán hóa đơn #{$invoice->invoice_code} (" . number_format((float)$invoice->total) . " đ). Quý khách vui lòng thanh toán ngay để tránh phát sinh trạng thái quá hạn.",
                            'invoice',
                            'invoice',
                            $invoice->id
                        );
                    }
                }
            }

            // 3. Trường hợp ĐÃ QUÁ HẠN (diffInDays < 0)
            elseif ($diffInDays < 0) {
                $daysOverdue = abs($diffInDays);

                // Chuyển trạng thái sang overdue nếu chưa chuyển
                if ($invoice->status !== 'overdue') {
                    $stats['marked_overdue']++;
                    if (!$dryRun) {
                        $invoice->update(['status' => 'overdue']);
                    }
                }

                // Gửi thông báo quá hạn cho Khách thuê (tối đa 1 lần / ngày)
                if ($tenant && !self::alreadyNotifiedToday($tenant->id, $invoice->id)) {
                    if (!$dryRun) {
                        Notification::notify(
                            $tenant->id,
                            "⚠️ Hóa đơn tiền phòng {$roomName} đã quá hạn",
                            "Hóa đơn #{$invoice->invoice_code} (" . number_format((float)$invoice->total) . " đ) đã quá hạn thanh toán {$daysOverdue} ngày (Hạn chót: {$dueDate->format('d/m/Y')}). Quý khách vui lòng nộp tiền ngay để đảm bảo quyền lợi thuê phòng.",
                            'warning',
                            'invoice',
                            $invoice->id
                        );
                    }
                }

                // Gửi thông báo quá hạn cho Chủ nhà (tối đa 1 lần / ngày)
                if ($owner && !self::alreadyNotifiedToday($owner->id, $invoice->id)) {
                    $stats['owner_overdue_alerts']++;
                    if (!$dryRun) {
                        Notification::notify(
                            $owner->id,
                            "⚠️ Phòng {$roomName} nợ tiền quá hạn ({$daysOverdue} ngày)",
                            "Khách thuê {$tenant?->name} ({$roomName}) chưa đóng tiền hóa đơn #{$invoice->invoice_code} (" . number_format((float)$invoice->total) . " đ). Đã quá hạn {$daysOverdue} ngày kể từ ngày {$dueDate->format('d/m/Y')}.",
                            'warning',
                            'invoice',
                            $invoice->id
                        );
                    }
                }
            }
        }

        return $stats;
    }

    private static function alreadyNotifiedToday(int $userId, int $invoiceId): bool
    {
        return Notification::query()
            ->where('user_id', $userId)
            ->where('reference_type', 'invoice')
            ->where('reference_id', $invoiceId)
            ->whereDate('created_at', Carbon::today())
            ->exists();
    }
}

