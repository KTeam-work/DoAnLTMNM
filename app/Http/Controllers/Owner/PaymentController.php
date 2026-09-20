<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);

        $query = Payment::query()
            ->whereHas('invoice.contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['invoice:id,invoice_code,contract_id,status', 'invoice.contract:id,room_id,tenant_id', 'invoice.contract.room:id,name', 'invoice.contract.tenant:id,name', 'payer:id,name'])
            ->latest();

        if ($request->filled('month')) {
            $query->whereMonth('created_at', Carbon::parse($request->input('month'))->month)
                ->whereYear('created_at', Carbon::parse($request->input('month'))->year);
        }
        if ($request->filled('status') && in_array($request->input('status'), ['pending', 'success', 'failed', 'cancelled'], true)) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('method') && in_array($request->input('method'), ['cash', 'bank_transfer', 'qr', 'online'], true)) {
            $query->where('method', $request->input('method'));
        }

        $payments = (clone $query)->paginate(15)->withQueryString();
        $stats = [
            'collected' => (clone $query)->where('status', 'success')->sum('amount'),
            'pending' => (clone $query)->where('status', 'pending')->sum('amount'),
        ];
        $filters = $request->only(['month', 'status', 'method']);

        if (! $request->expectsJson()) {
            return view('owner.payments.index', compact('payments', 'stats', 'filters'));
        }

        return response()->json(['data' => $payments, 'stats' => $stats]);
    }

    /**
     * Xác nhận thanh toán tiền mặt/chuyển khoản đang pending.
     */
    public function confirm(Request $request, int $id)
    {
        return $this->settle($request, $id, 'success');
    }

    /**
     * Từ chối giao dịch không hợp lệ.
     */
    public function reject(Request $request, int $id)
    {
        return $this->settle($request, $id, 'cancelled');
    }

    private function settle(Request $request, int $id, string $status)
    {
        $owner = $this->actingOwner($request);

        $payment = Payment::query()
            ->whereHas('invoice.contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with('invoice.contract')
            ->findOrFail($id);

        if ($payment->status !== 'pending') {
            $message = 'Giao dịch này đã được xử lý, không thể xác nhận lại.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['payment' => $message]);
            }
            abort(422, $message);
        }

        DB::transaction(function () use ($payment, $status) {
            $payment = Payment::query()->lockForUpdate()->findOrFail($payment->id);
            if ($payment->status !== 'pending') {
                return;
            }
            $payment->update(['status' => $status, 'paid_at' => $status === 'success' ? now() : null]);
            if ($status === 'success') {
                $payment->invoice->update(['status' => 'paid']);
                Notification::notify(
                    $payment->invoice->contract->tenant_id,
                    'Thanh toán được xác nhận',
                    "Thanh toán {$payment->payment_code} cho hóa đơn {$payment->invoice->invoice_code} đã được chủ nhà xác nhận.",
                    'payment',
                    'invoice',
                    $payment->invoice->id
                );
            } else {
                $payment->invoice->update(['status' => 'unpaid']);
                Notification::notify(
                    $payment->invoice->contract->tenant_id,
                    'Thanh toán bị từ chối',
                    "Thanh toán {$payment->payment_code} không hợp lệ. Vui lòng thử lại.",
                    'payment',
                    'invoice',
                    $payment->invoice->id
                );
            }
        });

        $message = $status === 'success' ? 'Đã xác nhận thanh toán.' : 'Đã từ chối giao dịch.';
        if (! $request->expectsJson()) {
            return back()->with('success', $message);
        }

        return response()->json(['message' => $message, 'data' => $payment->fresh()]);
    }

    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage payments.');

            return $user;
        }
        if (! $request->expectsJson()) {
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'owner')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản chủ trọ, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }
}

