<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Webhook nhận thông báo biến động số dư từ SePay.
     * Xác minh giao dịch, kiểm tra số tiền và cập nhật trạng thái hóa đơn.
     */
    public function webhook(Request $request)
    {
        Log::info('SePay Webhook Received:', $request->all());

        // 1. Xác thực Webhook (nếu có cấu hình token)
        $configuredToken = config('services.sepay.webhook_token');
        if (!empty($configuredToken)) {
            $authHeader = $request->header('Authorization', '');
            $apiKeyHeader = $request->header('x-api-key', '');

            $providedToken = null;
            if (preg_match('/^(?:Apikey|Bearer)\s+(.+)$/i', $authHeader, $matches)) {
                $providedToken = trim($matches[1]);
            } elseif (!empty($apiKeyHeader)) {
                $providedToken = trim($apiKeyHeader);
            }

            if (!$providedToken || !hash_equals($configuredToken, $providedToken)) {
                Log::warning('SePay Webhook: Unauthorized attempt', [
                    'ip' => $request->ip(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
        } elseif (app()->isProduction()) {
            // Môi trường Production bắt buộc phải cấu hình webhook token
            Log::critical('SePay Webhook: Rejected in production because SEPAY_WEBHOOK_TOKEN is not configured');
            return response()->json([
                'success' => false,
                'message' => 'Webhook security token not configured in production'
            ], 401);
        }

        // 2. Chỉ xử lý giao dịch tiền vào (transferType === "in")
        $transferType = strtolower((string) $request->input('transferType', ''));
        if ($transferType !== 'in') {
            Log::info('SePay Webhook: Ignored non-incoming transfer', ['transferType' => $transferType]);
            return response()->json([
                'success' => true,
                'message' => 'Ignored: not an incoming transfer'
            ], 200);
        }

        // 3. Kiểm tra mã giao dịch SePay (ID / referenceCode) để chống webhook lặp
        $sepayId = (string) ($request->input('id') ?? $request->input('referenceCode') ?? '');
        if ($sepayId === '') {
            Log::warning('SePay Webhook: Missing SePay transaction ID', $request->all());
            return response()->json([
                'success' => false,
                'message' => 'Missing SePay transaction ID'
            ], 400);
        }

        // Kiểm tra xem ID giao dịch SePay này đã được áp dụng cho thanh toán nào chưa
        $alreadyProcessed = Payment::query()
            ->where('transaction_code', $sepayId)
            ->where('status', 'success')
            ->first();

        if ($alreadyProcessed) {
            Log::info('SePay Webhook: Transaction already processed (idempotent response)', [
                'sepay_id' => $sepayId,
                'payment_code' => $alreadyProcessed->payment_code
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Transaction already processed',
                'payment_code' => $alreadyProcessed->payment_code
            ], 200);
        }

        // 4. Trích xuất chính xác payment_code từ content / description
        $content = (string) ($request->input('content') ?? $request->input('description') ?? '');
        $paymentCode = $this->extractPaymentCode($content);

        if (!$paymentCode) {
            Log::warning('SePay Webhook: Could not extract valid payment_code from content', [
                'content' => $content,
                'sepay_id' => $sepayId
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Payment code not found in content'
            ], 422);
        }

        // 5. Kiểm tra số tiền chuyển
        $transferAmount = $request->input('transferAmount');
        if ($transferAmount === null || !is_numeric($transferAmount)) {
            Log::warning('SePay Webhook: Invalid transferAmount', ['transferAmount' => $transferAmount]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid or missing transferAmount'
            ], 422);
        }

        $paidAmount = round((float) $transferAmount, 2);

        // 6. Xử lý cập nhật DB bằng Transaction và lockForUpdate
        return DB::transaction(function () use ($paymentCode, $paidAmount, $sepayId, $request) {
            /** @var Payment|null $payment */
            $payment = Payment::query()
                ->where('payment_code', $paymentCode)
                ->lockForUpdate()
                ->first();

            if (!$payment) {
                Log::warning('SePay Webhook: Payment not found for code', ['code' => $paymentCode]);
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not found'
                ], 404);
            }

            // Nếu payment đã thành công trước đó (idempotent)
            if ($payment->status === 'success') {
                Log::info('SePay Webhook: Payment is already marked as success', ['payment_code' => $paymentCode]);
                return response()->json([
                    'success' => true,
                    'message' => 'Payment already succeeded',
                    'payment_code' => $paymentCode
                ], 200);
            }

            // Không xử lý payment đã failed hoặc cancelled
            if (in_array($payment->status, ['failed', 'cancelled'], true)) {
                Log::warning("SePay Webhook: Payment {$paymentCode} is in terminal status: {$payment->status}");
                return response()->json([
                    'success' => false,
                    'message' => "Payment is already {$payment->status}"
                ], 422);
            }

            // So sánh số tiền (tránh lỗi làm tròn float)
            $expectedAmount = round((float) $payment->amount, 2);
            if ($paidAmount < $expectedAmount) {
                Log::warning('SePay Webhook: Insufficient transfer amount', [
                    'payment_code' => $paymentCode,
                    'expected' => $expectedAmount,
                    'received' => $paidAmount,
                ]);
                return response()->json([
                    'success' => false,
                    'message' => "Insufficient amount. Expected: {$expectedAmount}, Received: {$paidAmount}"
                ], 422);
            }

            // Thời gian thanh toán
            $txDate = $request->input('transactionDate');
            $paidAt = null;
            if ($txDate) {
                try {
                    $paidAt = Carbon::parse($txDate);
                } catch (\Throwable $e) {
                    $paidAt = now();
                }
            } else {
                $paidAt = now();
            }

            // Cập nhật Payment: status = 'success', method = 'qr'
            $payment->update([
                'status' => 'success',
                'method' => 'qr',
                'transaction_code' => $sepayId,
                'paid_at' => $paidAt,
            ]);

            // Cập nhật Invoice: status = 'paid'
            /** @var Invoice|null $invoice */
            $invoice = $payment->invoice()->lockForUpdate()->first();
            if ($invoice && $invoice->status !== 'cancelled') {
                $invoice->update([
                    'status' => 'paid',
                ]);
            }

            // Gửi thông báo hệ thống nếu có Notification model
            if ($invoice && $invoice->contract) {
                try {
                    Notification::notify(
                        $invoice->contract->tenant_id,
                        'Thanh toán thành công',
                        "Hóa đơn #{$invoice->invoice_code} đã được thanh toán thành công qua SePay QR.",
                        'payment',
                        'invoice',
                        $invoice->id
                    );
                    Notification::notify(
                        $invoice->contract->owner_id,
                        'Khách đã thanh toán hóa đơn',
                        "Phòng {$invoice->contract->room?->name} đã thanh toán hóa đơn #{$invoice->invoice_code} qua SePay QR.",
                        'payment',
                        'invoice',
                        $invoice->id
                    );
                } catch (\Throwable $e) {
                    Log::error('SePay Webhook: Error sending notification: ' . $e->getMessage());
                }
            }

            Log::info('SePay Webhook: Payment and Invoice updated successfully', [
                'payment_code' => $paymentCode,
                'sepay_id' => $sepayId,
                'amount' => $paidAmount,
                'invoice_id' => $invoice?->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment processed successfully',
                'payment_code' => $paymentCode,
                'status' => 'success'
            ], 200);
        });
    }

    /**
     * Trích xuất chính xác payment_code từ nội dung chuyển khoản SePay.
     * Hỗ trợ TROOI... và PAY-...
     */
    private function extractPaymentCode(string $content): ?string
    {
        $clean = trim($content);
        if ($clean === '') {
            return null;
        }

        // 1. Tìm mã định dạng TROOI + 4-20 ký tự chữ hoa/số (ví dụ TROOI123456, TROOITEST001)
        if (preg_match('/(TROOI[A-Z0-9]{4,20})/i', $clean, $matches)) {
            return strtoupper($matches[1]);
        }

        // 2. Tìm mã định dạng PAY-YYYYMMDD-XXXXXX
        if (preg_match('/(PAY-[0-9]{8}-[A-Z0-9]{6})/i', $clean, $matches)) {
            return strtoupper($matches[1]);
        }

        // 3. Khớp chính xác toàn bộ nội dung
        $exact = strtoupper($clean);
        if (Payment::query()->where('payment_code', $exact)->exists()) {
            return $exact;
        }

        // 4. Tìm từng từ có trong bảng payments
        $words = preg_split('/[\s\-\_,\.\:\;]+/', $clean);
        foreach ($words as $word) {
            $w = strtoupper(trim($word));
            if (strlen($w) >= 6 && Payment::query()->where('payment_code', $w)->exists()) {
                return $w;
            }
        }

        return null;
    }
}
