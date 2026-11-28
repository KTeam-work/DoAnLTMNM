<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PlatformFeeController extends Controller
{
    public function index(Request $request)
    {
        abort_unless($request->user()->role === 'owner', 403);
        $query = Payment::query()->where('status', 'success')->where('method', 'cash')->whereNotNull('fee_payment_status')
            ->whereHas('invoice.contract', fn ($q) => $q->where('owner_id', $request->user()->id));
        $pendingTotal = (clone $query)->where('fee_payment_status', 'pending')->sum('platform_fee');
        $paidTotal = (clone $query)->where('fee_payment_status', 'paid')->sum('platform_fee');
        $payments = $query->with(['invoice.contract.room'])->latest('paid_at')->paginate(15);
        return view('owner.platform-fees.index', compact('payments', 'pendingTotal', 'paidTotal'));
    }

    public function simulate(Request $request, Payment $payment)
    {
        abort_unless($request->user()->role === 'owner', 403);
        abort_unless(app()->environment(['local', 'testing']), 404);
        $payment = $this->ownerFee($request, $payment);
        if ($payment->fee_payment_status === 'paid') return back()->with('success', 'Khoản phí này đã được ghi nhận trước đó.');
        $headers = [];
        if ($token = config('services.sepay.webhook_token')) $headers['HTTP_AUTHORIZATION'] = 'Apikey '.$token;
        $webhook = Request::create('/api/sepay/webhook', 'POST', [
            'id' => 'DEMO-FEE-'.Str::uuid(),
            'content' => 'FEE'.$payment->id,
            'transferType' => 'in',
            'transferAmount' => (float) $payment->platform_fee,
        ], [], [], $headers);
        $webhook->attributes->set('local_fee_demo', true);
        $response = app(\App\Http\Controllers\PaymentController::class)->webhook($webhook);
        if ($response->getStatusCode() !== 200) return back()->with('error', 'Không thể giả lập nộp phí. Vui lòng thử lại.');
        return back()->with('success', 'Đã ghi nhận nộp phí thành công (mô phỏng qua webhook).');
    }

    public function status(Request $request, Payment $payment)
    {
        abort_unless($request->user()->role === 'owner', 403);
        $payment = $this->ownerFee($request, $payment);
        return response()->json(['status' => $payment->fee_payment_status]);
    }

    private function ownerFee(Request $request, Payment $payment): Payment
    {
        $payment = Payment::whereHas('invoice.contract', fn ($q) => $q->where('owner_id', $request->user()->id))->findOrFail($payment->id);
        abort_unless($payment->status === 'success' && $payment->method === 'cash' && $payment->platform_fee !== null, 404);
        return $payment;
    }
}
