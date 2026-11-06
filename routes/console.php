<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Lịch chạy tự động định kỳ (Cron Job / Scheduled Tasks):
 * Mỗi sáng lúc 08:00, hệ thống tự động quét ngày hết hạn của các hóa đơn
 * để nhắc nhở khách thuê và đánh dấu các hóa đơn quá hạn (overdue).
 */
Schedule::command('invoices:process-due')
    ->dailyAt('08:00')
    ->name('Quét nợ và xử lý hóa đơn quá hạn định kỳ');
