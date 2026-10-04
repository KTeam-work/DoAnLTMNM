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
            ->with('contract.owner:id')
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

        Notification::notify(
            $invoice->contract->owner_id,
            'Yêu cầu thanh toán mới',
            "Phòng {$invoice->contract->room->name} vừa gửi thanh toán {$payment->payment_code} cho hóa đơn {$invoice->invoice_code}.",
            'payment',
            'invoice',
            $invoice->id
        );

        if (! $request->expectsJson()) {
            return redirect()->route('tenant.invoices.show', $invoice->id)
                ->with('success', "Đã tạo thanh toán {$payment->payment_code}. Trạng thái: chờ chủ nhà xác nhận.");
        }

        return response()->json(['message' => 'Payment created.', 'data' => $payment], 201);
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
                Notification::notify(
                    $payment->invoice->contract->tenant_id,
                    'Thanh toán thành công',
                    "Hóa đơn {$payment->invoice->invoice_code} đã được thanh toán.",
                    'payment',
                    'invoice',
                    $payment->invoice->id
                );
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
}

