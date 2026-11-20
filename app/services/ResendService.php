<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use RuntimeException;

class ResendService
{
    /**
     * Gửi email chung qua SMTP hoặc Resend API.
     * Tự động ưu tiên SMTP (nếu cấu hình Gmail SMTP để gửi tới bất kỳ email nào),
     * hoặc dùng Resend API (có cơ chế sandbox khi chạy thử nghiệm).
     */
    public function sendEmail(
        string $to,
        string $subject,
        string $html
    ): void {
        // 1. Nếu hệ thống đã cấu hình SMTP (như Gmail SMTP), ưu tiên gửi trực tiếp tới đúng người nhận:
        if (config('mail.default') === 'smtp' && !empty(config('mail.mailers.smtp.username')) && !empty(config('mail.mailers.smtp.password'))) {
            try {
                Mail::html($html, function ($message) use ($to, $subject) {
                    $message->to($to)->subject($subject);
                });
                return;
            } catch (\Throwable $e) {
                Log::warning('Gửi email qua SMTP thất bại, thử tiếp tục qua Resend API: ' . $e->getMessage());
            }
        }

        // 2. Gửi qua Resend API
        $apiKey = config('services.resend.key');
        $fromEmail = config('services.resend.from_email');
        $fromName = config('services.resend.from_name');

        if (!$apiKey || !$fromEmail) {
            throw new RuntimeException('Resend chưa được cấu hình đầy đủ (thiếu RESEND_API_KEY hoặc RESEND_FROM_EMAIL).');
        }

        $resendSandboxTestEmail = 'hieu0911k6@gmail.com';

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->post('https://api.resend.com/emails', [
                'from' => $fromName ? "{$fromName} <{$fromEmail}>" : $fromEmail,
                'to' => [$to],
                'subject' => $subject,
                'html' => $html,
            ]);

        if (!$response->successful()) {
            $body = $response->body();
            // Nếu bị hạn chế bởi Resend Sandbox (chỉ gửi được cho email chủ sở hữu đăng ký ban đầu):
            if ($response->status() === 403 && str_contains($body, 'own email address') && strtolower($to) !== strtolower($resendSandboxTestEmail)) {
                Log::warning("Resend sandbox: Tự động chuyển tiếp email từ [{$to}] sang [{$resendSandboxTestEmail}].");

                $sandboxNotice = "<div style='background:#fef3c7;border:1px solid #f59e0b;padding:12px;margin-bottom:16px;border-radius:8px;color:#92400e;font-size:13px;line-height:1.5;'>"
                    . "<strong>[THỬ NGHIỆM SANDBOX]:</strong> Thư này được chuyển tiếp tới hộp thư thử nghiệm của bạn (<code>{$resendSandboxTestEmail}</code>) do địa chỉ người nhận gốc (<code>{$to}</code>) chưa thuộc tên miền được xác minh trên Resend.<br>"
                    . "Người nhận dự kiến: <b>{$to}</b>"
                    . "</div>";

                $retryResponse = Http::withToken($apiKey)
                    ->acceptJson()
                    ->post('https://api.resend.com/emails', [
                        'from' => $fromName ? "{$fromName} <{$fromEmail}>" : $fromEmail,
                        'to' => [$resendSandboxTestEmail],
                        'subject' => "[Thử nghiệm tới {$to}] " . $subject,
                        'html' => $sandboxNotice . $html,
                    ]);

                if ($retryResponse->successful()) {
                    return;
                }
            }

            throw new RuntimeException('Resend gửi email thất bại: ' . $body);
        }
    }

    /**
     * Gửi email thông báo hợp đồng mới và thông tin tài khoản cho khách vãng lai.
     */
    public function sendWalkInContractEmail(
        string $toEmail,
        string $tenantName,
        string $roomName,
        string $propertyName,
        string $contractCode,
        string $defaultPassword = 'default_password_123',
        bool $isNewAccount = true
    ): void {
        $loginUrl = route('login');
        $subject = "[Trọ Ơi] Hợp đồng thuê phòng mới & Tài khoản đăng nhập ({$roomName})";

        if ($isNewAccount) {
            $accountSection = "
                <p>Hệ thống đã tự động khởi tạo tài khoản đăng nhập để bạn tiện quản lý hợp đồng và hóa đơn:</p>
                <div style='background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px 18px; margin: 16px 0;'>
                    <ul style='margin: 0; padding-left: 20px; line-height: 1.8;'>
                        <li><b>Tài khoản đăng nhập:</b> <span style='color: #0c2340; font-weight: bold;'>{$toEmail}</span></li>
                        <li><b>Mật khẩu đăng nhập:</b> <code style='background: #e2e8f0; padding: 3px 8px; border-radius: 4px; font-weight: bold; color: #0f172a; font-size: 14px;'>{$defaultPassword}</code></li>
                    </ul>
                </div>
                <p>Vui lòng đăng nhập vào hệ thống <b>Trọ Ơi</b> để kiểm tra chi tiết hợp đồng của bạn.</p>
                <p style='color: #64748b; font-size: 13px;'><i>(Sau khi đăng nhập, bạn có thể đổi mật khẩu mới trong phần Tài khoản).</i></p>
            ";
        } else {
            $accountSection = "
                <p>Hợp đồng thuê phòng mã <b>{$contractCode}</b> đã được gắn trực tiếp vào tài khoản hiện tại của bạn ({$toEmail}).</p>
                <p>Vui lòng đăng nhập vào hệ thống <b>Trọ Ơi</b> để kiểm tra thông tin hợp đồng.</p>
            ";
        }

        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; color: #1e293b; background: #ffffff;'>
                <div style='margin-bottom: 20px; border-bottom: 2px solid #ef7d27; padding-bottom: 12px;'>
                    <h2 style='color: #0c2340; margin: 0;'>Trọ <span style='color: #ef7d27;'>Ơi</span></h2>
                </div>
                <h3 style='color: #0c2340;'>Chào bạn {$tenantName},</h3>
                <p>Chủ trọ vừa lập <b>Hợp đồng thuê phòng</b> cho bạn tại <b>{$roomName}</b> thuộc <b>{$propertyName}</b> (Mã hợp đồng: <b>{$contractCode}</b>).</p>
                {$accountSection}
                <div style='margin-top: 24px; text-align: center;'>
                    <a href='{$loginUrl}' style='display: inline-block; padding: 12px 28px; background: #0c2340; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;'>
                        Đăng nhập hệ thống
                    </a>
                </div>
                <hr style='margin-top: 30px; border: none; border-top: 1px solid #e2e8f0;'>
                <p style='font-size: 12px; color: #94a3b8; text-align: center; margin: 0;'>
                    Đây là email tự động từ hệ thống Quản Lý Phòng Trọ - Trọ Ơi. Vui lòng không trả lời thư này.
                </p>
            </div>
        ";

        $this->sendEmail($toEmail, $subject, $html);
    }

    /**
     * Gửi email thông báo hóa đơn mới kèm tài khoản đăng nhập và 2 cách thanh toán.
     */
    public function sendInvoiceEmail(
        string $toEmail,
        string $tenantName,
        string $roomName,
        string $propertyName,
        string $invoiceCode,
        string $billingMonth,
        float|int $totalAmount,
        string $dueDate,
        int $invoiceId,
        ?string $initialPassword = null
    ): void {
        $loginUrl = route('login');
        $invoiceUrl = route('tenant.invoices.show', $invoiceId);
        $formattedAmount = number_format($totalAmount, 0, ',', '.') . ' đ';
        $subject = "[Trọ Ơi] Hóa đơn tiền phòng kỳ {$billingMonth} (Mã: {$invoiceCode})";

        $passwordHtml = '';
        if (!empty($initialPassword)) {
            $passwordHtml = "
                <div style='background: #f8fafc; border: 1px solid #cbd5e1; border-radius: 8px; padding: 14px 18px; margin: 16px 0;'>
                    <div style='font-size: 13px; font-weight: bold; color: #0c2340; margin-bottom: 6px;'>Thông tin đăng nhập tài khoản của bạn:</div>
                    <ul style='margin: 0; padding-left: 20px; line-height: 1.8;'>
                        <li><b>Tài khoản:</b> {$toEmail}</li>
                        <li><b>Mật khẩu:</b> <code style='background: #e2e8f0; padding: 2px 8px; border-radius: 4px; font-weight: bold; color: #0f172a; font-size: 14px;'>{$initialPassword}</code></li>
                    </ul>
                    <div style='font-size: 12px; color: #64748b; margin-top: 6px;'>Bạn có thể sử dụng thông tin trên để đăng nhập xem chi tiết và thanh toán hóa đơn.</div>
                </div>
            ";
        }

        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; color: #1e293b; background: #ffffff;'>
                <div style='margin-bottom: 20px; border-bottom: 2px solid #ef7d27; padding-bottom: 12px;'>
                    <h2 style='color: #0c2340; margin: 0;'>Trọ <span style='color: #ef7d27;'>Ơi</span></h2>
                </div>
                <h3 style='color: #0c2340;'>Chào bạn {$tenantName},</h3>
                <p>Hóa đơn tiền phòng kỳ <b>{$billingMonth}</b> cho <b>{$roomName}</b> thuộc <b>{$propertyName}</b> đã được khởi tạo.</p>

                <!-- Bảng tóm tắt hóa đơn -->
                <div style='background: #f1f5f9; border-radius: 8px; padding: 16px; margin: 16px 0;'>
                    <table style='width: 100%; border-collapse: collapse; font-size: 14px;'>
                        <tr>
                            <td style='padding: 6px 0; color: #64748b;'>Mã hóa đơn:</td>
                            <td style='padding: 6px 0; font-weight: bold; text-align: right;'>#{$invoiceCode}</td>
                        </tr>
                        <tr>
                            <td style='padding: 6px 0; color: #64748b;'>Kỳ thanh toán:</td>
                            <td style='padding: 6px 0; font-weight: bold; text-align: right;'>Tháng {$billingMonth}</td>
                        </tr>
                        <tr>
                            <td style='padding: 6px 0; color: #64748b;'>Hạn chót thanh toán:</td>
                            <td style='padding: 6px 0; font-weight: bold; color: #dc2626; text-align: right;'>{$dueDate}</td>
                        </tr>
                        <tr style='border-top: 1px solid #cbd5e1;'>
                            <td style='padding: 10px 0 4px 0; font-weight: bold; color: #0c2340; font-size: 15px;'>Tổng số tiền:</td>
                            <td style='padding: 10px 0 4px 0; font-weight: bold; color: #0c2340; font-size: 18px; text-align: right;'>{$formattedAmount}</td>
                        </tr>
                    </table>
                </div>

                {$passwordHtml}

                <!-- Hướng dẫn 2 cách thanh toán -->
                <div style='margin: 20px 0;'>
                    <div style='font-size: 14px; font-weight: bold; color: #0c2340; margin-bottom: 8px;'>Quý khách có thể lựa chọn 1 trong 2 cách thanh toán:</div>
                    <div style='background: #fafafa; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px; margin-bottom: 8px;'>
                        <strong style='color: #0c2340;'>Cách 1: Thanh toán bằng QR</strong>
                        <p style='margin: 4px 0 0 0; font-size: 13px; color: #475569;'>Truy cập hóa đơn, mở mã VietQR và quét trên app ngân hàng. Tiền vào tài khoản và hệ thống tự động gạch nợ sau 3 - 5 giây.</p>
                    </div>
                    <div style='background: #fafafa; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 16px;'>
                        <strong style='color: #0c2340;'>Cách 2: Thanh toán bằng xác nhận</strong>
                        <p style='margin: 4px 0 0 0; font-size: 13px; color: #475569;'>Nộp tiền mặt hoặc chuyển khoản trực tiếp cho chủ trọ, sau đó bấm Xác nhận thanh toán trên trang hóa đơn để chủ trọ duyệt gạch nợ.</p>
                    </div>
                </div>

                <div style='margin-top: 24px; text-align: center;'>
                    <a href='{$invoiceUrl}' style='display: inline-block; padding: 12px 28px; background: #0c2340; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;'>
                        Xem và thanh toán hóa đơn
                    </a>
                </div>

                <hr style='margin-top: 30px; border: none; border-top: 1px solid #e2e8f0;'>
                <p style='font-size: 12px; color: #94a3b8; text-align: center; margin: 0;'>
                    Đây là email tự động từ hệ thống Quản Lý Phòng Trọ - Trọ Ơi. Vui lòng không trả lời thư này.
                </p>
            </div>
        ";

        $this->sendEmail($toEmail, $subject, $html);
    }

    /**
     * Gửi email thông báo tài khoản bị khóa kèm liên kết xin mở khóa.
     */
    public function sendAccountLockedEmail(
        string $toEmail,
        string $userName,
        string $unlockUrl
    ): void {
        $subject = "[Trọ Ơi] Thông báo: Tài khoản của bạn đã bị tạm khóa";

        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; color: #1e293b; background: #ffffff;'>
                <div style='margin-bottom: 20px; border-bottom: 2px solid #ef7d27; padding-bottom: 12px;'>
                    <h2 style='color: #0c2340; margin: 0;'>Trọ <span style='color: #ef7d27;'>Ơi</span></h2>
                </div>
                <h3 style='color: #dc2626;'>Tài khoản của bạn đã bị tạm khóa</h3>
                <p>Chào bạn <b>{$userName}</b>,</p>
                <p>Tài khoản liên kết với địa chỉ email <b>{$toEmail}</b> trên nền tảng Trọ Ơi hiện đã bị Ban quản trị tạm khóa vì lý do an toàn hoặc vi phạm chính sách nền tảng.</p>

                <div style='background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px 18px; margin: 16px 0; color: #991b1b; font-size: 13.5px; line-height: 1.6;'>
                    Trong thời gian tài khoản bị khóa, bạn sẽ tạm thời không thể đăng nhập hoặc thực hiện các thao tác trên hệ thống.
                </div>

                <p>Nếu bạn cho rằng đây là sự nhầm lẫn hoặc mong muốn Ban quản trị xem xét mở khóa tài khoản, vui lòng bấm vào nút dưới đây để gửi yêu cầu xem xét:</p>

                <div style='margin: 24px 0; text-align: center;'>
                    <a href='{$unlockUrl}' style='display: inline-block; padding: 12px 28px; background: #0c2340; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;'>
                        Gửi yêu cầu mở khóa tài khoản
                    </a>
                </div>

                <p style='color: #64748b; font-size: 12.5px; line-height: 1.5;'>
                    <i>* Lưu ý: Đường dẫn này được tạo riêng cho tài khoản của bạn để gửi yêu cầu trực tiếp đến Admin.</i>
                </p>

                <hr style='margin-top: 30px; border: none; border-top: 1px solid #e2e8f0;'>
                <p style='font-size: 12px; color: #94a3b8; text-align: center; margin: 0;'>
                    Đây là email tự động từ hệ thống Quản Lý Phòng Trọ - Trọ Ơi. Vui lòng không trả lời thư này.
                </p>
            </div>
        ";

        $this->sendEmail($toEmail, $subject, $html);
    }

    /**
     * Gửi email thông báo tài khoản đã được Admin duyệt mở khóa thành công.
     */
    public function sendAccountUnlockedEmail(
        string $toEmail,
        string $userName
    ): void {
        $loginUrl = route('login');
        $subject = "[Trọ Ơi] Thông báo: Tài khoản của bạn đã được mở khóa thành công";

        $html = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; color: #1e293b; background: #ffffff;'>
                <div style='margin-bottom: 20px; border-bottom: 2px solid #ef7d27; padding-bottom: 12px;'>
                    <h2 style='color: #0c2340; margin: 0;'>Trọ <span style='color: #ef7d27;'>Ơi</span></h2>
                </div>
                <h3 style='color: #16a34a;'>Tài khoản của bạn đã được mở khóa</h3>
                <p>Chào bạn <b>{$userName}</b>,</p>
                <p>Ban quản trị Trọ Ơi đã xem xét yêu cầu và <b>chính thức mở khóa tài khoản</b> của bạn ({$toEmail}).</p>

                <div style='background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 14px 18px; margin: 16px 0; color: #166534; font-size: 13.5px; line-height: 1.6;'>
                    Hiện tại, tài khoản của bạn đã hoạt động trở lại bình thường. Bạn có thể đăng nhập và tiếp tục sử dụng các dịch vụ của Trọ Ơi ngay bây giờ.
                </div>

                <div style='margin: 24px 0; text-align: center;'>
                    <a href='{$loginUrl}' style='display: inline-block; padding: 12px 28px; background: #0c2340; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 14px;'>
                        Đăng nhập ngay
                    </a>
                </div>

                <hr style='margin-top: 30px; border: none; border-top: 1px solid #e2e8f0;'>
                <p style='font-size: 12px; color: #94a3b8; text-align: center; margin: 0;'>
                    Đây là email tự động từ hệ thống Quản Lý Phòng Trọ - Trọ Ơi. Vui lòng không trả lời thư này.
                </p>
            </div>
        ";

        $this->sendEmail($toEmail, $subject, $html);
    }
}
