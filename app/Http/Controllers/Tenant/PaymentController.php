<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * Tenant tạo yêu cầu thanh toán cho hóa đơn của mình.
     * Số tiền lấy từ hóa đơn, không tin số tiền từ form.
     */
    public function store(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $data = $request->validate([
            'invoice_id' => ['required', 'integer', 'exists:invoices,id'],
            'method' => ['required', 'in:cash,bank_transfer,qr,online'],
            'transaction_code' => ['nullable', 'string', 'max:100'],
        ]);

        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with(['contract.owner:id', 'contract.room'])
            ->find($data['invoice_id']);

        if (! $invoice) {
            if (! $request->expectsJson()) {
                return back()->withErrors(['invoice_id' => 'Hóa đơn không thuộc tài khoản của bạn.'])->withInput();
            }
            abort(404);
        }

        if (in_array($invoice->status, ['paid', 'cancelled'], true)) {
            $message = 'Hóa đơn này không còn cho phép thanh toán.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['invoice_id' => $message])->withInput();
            }
            abort(422, $message);
        }

        if ($invoice->payments()->whereIn('status', ['pending', 'success'])->exists()) {
            $message = 'Hóa đơn đã có giao dịch đang chờ hoặc đã thành công.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['invoice_id' => $message])->withInput();
            }
            abort(422, $message);
        }

        $payment = DB::transaction(function () use ($invoice, $tenant, $data) {
            $payment = Payment::query()->create([
                'payment_code' => $this->paymentCode(),
                'invoice_id' => $invoice->id,
                'payer_id' => $tenant->id,
                'amount' => $invoice->total,
                'method' => $data['method'],
                'transaction_code' => $data['transaction_code'] ?? null,
                'status' => 'pending',
            ]);

            if ($invoice->status === 'unpaid') {
                $invoice->update(['status' => 'pending']);
            }

            return $payment;
        });

        if ($invoice->contract && $invoice->contract->owner_id) {
            Notification::notify(
                $invoice->contract->owner_id,
                'Yêu cầu thanh toán mới',
                "Phòng {$invoice->contract->room?->name} vừa gửi thanh toán {$payment->payment_code} cho hóa đơn {$invoice->invoice_code}.",
                'payment',
                'invoice',
                $invoice->id
            );
        }

        if (! $request->expectsJson()) {
            return redirect()->route('tenant.invoices.show', $invoice->id)
                ->with('success', "Đã tạo thanh toán {$payment->payment_code}. Trạng thái: chờ chủ nhà xác nhận.");
        }

        return response()->json(['message' => 'Payment created.', 'data' => $payment], 201);
    }

    /**
     * Tạo hoặc lấy giao dịch QR SePay đang chờ (pending) cho hóa đơn của Tenant.
     * Tự động tái sử dụng giao dịch pending cũ để không tạo giao dịch rác trùng lặp.
     */
    public function createOrGetQrPayment(Request $request, $id)
    {
        $tenant = $this->actingTenant($request);

        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with(['contract.room.property', 'contract.owner', 'payments'])
            ->findOrFail($id);

        if ($invoice->status === 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Hóa đơn này đã được thanh toán hoàn tất.',
                'status' => 'paid',
            ], 422);
        }

        if ($invoice->status === 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => 'Hóa đơn này đã bị hủy.',
                'status' => 'cancelled',
            ], 422);
        }

        // Tìm giao dịch pending có sẵn của hóa đơn này
        $payment = $invoice->payments()
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        if (!$payment) {
            $payment = DB::transaction(function () use ($invoice, $tenant) {
                $payment = Payment::create([
                    'payment_code' => $this->generateSepayPaymentCode(),
                    'invoice_id' => $invoice->id,
                    'payer_id' => $tenant->id,
                    'amount' => $invoice->total,
                    'method' => 'qr',
                    'status' => 'pending',
                ]);

                if ($invoice->status === 'unpaid') {
                    $invoice->update(['status' => 'pending']);
                }

                return $payment;
            });
        }

        // Lấy thông tin cấu hình SePay
        $bankName = config('services.sepay.bank_name', 'MSB');
        $bankAccount = config('services.sepay.bank_account', '0000000001');
        $accountName = config('services.sepay.account_name', 'SEPAY TEST');
        $template = config('services.sepay.qr_template', 'compact2');

        $amountInt = (int) round((float) $payment->amount);
        $paymentCode = $payment->payment_code;

        // Tạo link ảnh VietQR / SePay QR chuẩn
        $qrUrl = "https://qr.sepay.vn/img?acc=" . urlencode($bankAccount)
            . "&bank=" . urlencode($bankName)
            . "&amount=" . $amountInt
            . "&des=" . urlencode($paymentCode)
            . "&template=" . urlencode($template);

        return response()->json([
            'success' => true,
            'data' => [
                'payment_id' => $payment->id,
                'payment_code' => $paymentCode,
                'amount' => (float) $payment->amount,
                'amount_formatted' => number_format((float) $payment->amount, 0, ',', '.') . ' đ',
                'invoice_id' => $invoice->id,
                'invoice_code' => $invoice->invoice_code,
                'bank_name' => $bankName,
                'bank_account' => $bankAccount,
                'account_name' => $accountName,
                'qr_url' => $qrUrl,
                'status' => $payment->status,
                'invoice_status' => $invoice->fresh()->status,
            ]
        ], 200);
    }

    /**
     * Kiểm tra trạng thái thanh toán của hóa đơn (phục vụ AJAX Polling trên giao diện).
     */
    public function checkPaymentStatus(Request $request, $id)
    {
        $tenant = $this->actingTenant($request);

        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with('payments')
            ->findOrFail($id);

        $latestPayment = $invoice->payments()->latest('id')->first();

        return response()->json([
            'success' => true,
            'data' => [
                'invoice_id' => $invoice->id,
                'invoice_code' => $invoice->invoice_code,
                'invoice_status' => $invoice->status,
                'is_paid' => $invoice->status === 'paid',
                'payment_status' => $latestPayment?->status,
                'payment_code' => $latestPayment?->payment_code,
                'paid_at' => $latestPayment?->paid_at?->format('H:i d/m/Y'),
            ]
        ], 200);
    }

    /**
     * Giả lập giao dịch SePay Test Mode trực tiếp từ giao diện (Dành cho demo đồ án).
     * Gọi thẳng vào webhook xử lý để kiểm thử toàn diện luồng gạch nợ.
     */
    public function simulateSepayWebhook(Request $request, $id)
    {
        $tenant = $this->actingTenant($request);

        /** @var Invoice $invoice */
        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with('payments')
            ->findOrFail($id);

        $payment = $invoice->payments()
            ->where('status', 'pending')
            ->latest('id')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy giao dịch pending để giả lập.'
            ], 400);
        }

        // Tạo request giả lập SePay webhook chuẩn
        $mockWebhookRequest = Request::create('/api/sepay/webhook', 'POST', [
            'gateway' => config('services.sepay.bank_name', 'MSB'),
            'transactionDate' => now()->format('Y-m-d H:i:s'),
            'accountNumber' => config('services.sepay.bank_account', '0000000001'),
            'subAccount' => 'SBSEPAYTEST',
            'code' => null,
            'content' => $payment->payment_code,
            'transferType' => 'in',
            'description' => $payment->payment_code,
            'transferAmount' => (float) $payment->amount,
            'referenceCode' => 'SIM' . time(),
            'id' => rand(100000, 999999)
        ]);

        $webhookController = app(\App\Http\Controllers\PaymentController::class);
        return $webhookController->webhook($mockWebhookRequest);
    }

    /**
     * Callback cổng thanh toán (demo). Idempotent: giao dịch đã xử lý thì
     * trả kết quả cũ, không ghi nhận hai lần.
     */
    public function callback(Request $request)
    {
        $data = $request->validate([
            'payment_code' => ['required', 'string'],
            'status' => ['required', 'in:success,failed'],
            'amount' => ['required', 'numeric', 'min:0'],
            'transaction_code' => ['nullable', 'string', 'max:100'],
        ]);

        $payment = Payment::query()->where('payment_code', $data['payment_code'])->with('invoice.contract')->first();
        if (! $payment) {
            return response()->json(['message' => 'Payment not found.'], 404);
        }
        if ((float) $data['amount'] !== (float) $payment->amount) {
            return response()->json(['message' => 'Amount mismatch.'], 422);
        }
        if ($payment->status !== 'pending') {
            return response()->json(['message' => 'Already processed.', 'data' => $payment->fresh()]);
        }

        DB::transaction(function () use ($payment, $data) {
            $payment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            if ($payment->status !== 'pending') {
                return;
            }
            $payment->update([
                'status' => $data['status'],
                'transaction_code' => $data['transaction_code'] ?? $payment->transaction_code,
                'paid_at' => $data['status'] === 'success' ? now() : null,
            ]);
            if ($data['status'] === 'success') {
                $payment->invoice->update(['status' => 'paid']);
                if ($payment->invoice->contract) {
                    Notification::notify(
                        $payment->invoice->contract->tenant_id,
                        'Thanh toán thành công',
                        "Hóa đơn {$payment->invoice->invoice_code} đã được thanh toán.",
                        'payment',
                        'invoice',
                        $payment->invoice->id
                    );
                }
            }
        });

        return response()->json(['message' => 'Callback processed.', 'data' => $payment->fresh()]);
    }

    private function actingTenant(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'tenant', 403, 'Only tenants can pay invoices.');

            return $user;
        }
        if (! $request->expectsJson()) {
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'tenant')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản người thuê, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }

    private function paymentCode(): string
    {
        do {
            $code = 'PAY-' . now()->format('Ymd') . '-' . Str::upper(Str::random(6));
        } while (Payment::query()->where('payment_code', $code)->exists());

        return $code;
    }

    /**
     * Tạo mã payment_code chuẩn SePay (bắt đầu bằng TROOI + 6 ký tự số/chữ hoa).
     * Dễ đọc, không chứa dấu gạch nối, chống lỗi xử lý nội dung ở các ngân hàng.
     */
    private function generateSepayPaymentCode(): string
    {
        do {
            $code = 'TROOI' . Str::upper(Str::random(6));
        } while (Payment::query()->where('payment_code', $code)->exists());

        return $code;
    }
}
