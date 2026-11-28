@extends('Layout.landlord')
@section('title', 'Phí nền tảng | Trọ Ơi')
@push('styles')
  <link rel="stylesheet" href="{{ asset('css/tv4/payments/platform-fees.css') }}">
@endpush
@section('content')
<div class="platform-fees-page">
  <nav class="fee-breadcrumb" aria-label="Đường dẫn"><a href="{{ route('landlord.dashboard') }}">Tổng quan</a><span aria-hidden="true">/</span><span>Tài chính</span><span aria-hidden="true">/</span><strong>Phí nền tảng</strong></nav>
  <header class="fee-header">
    <div><div class="fee-eyebrow">TÀI CHÍNH CỦA BẠN</div><h1>Phí nền tảng</h1><p>Theo dõi và thanh toán phí từ các hóa đơn tiền mặt tại một nơi.</p></div>
    <a href="{{ route('owner.payments.index') }}" class="fee-history-link"><i class="bi bi-clock-history" aria-hidden="true"></i> Lịch sử giao dịch</a>
  </header>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif
  <div class="fee-overview">
    <section class="fee-card fee-card-due" aria-label="Phí cần nộp">
      <div class="fee-card-top"><span class="fee-card-icon"><i class="bi bi-wallet2" aria-hidden="true"></i></span><span class="fee-pill fee-pill-due">{{ (float) $pendingTotal > 0 ? 'Cần thanh toán' : 'Không có phí chờ' }}</span></div>
      <div class="fee-card-label">Phí cần nộp</div><div class="fee-card-amount">{{ number_format((float) $pendingTotal, 0, ',', '.') }} <small>đ</small></div>
      <p>{{ (float) $pendingTotal > 0 ? 'Chọn khoản phí bên dưới để thanh toán.' : 'Bạn không có khoản phí nào cần nộp.' }}</p>
    </section>
    <section class="fee-card fee-card-paid" aria-label="Phí đã nộp">
      <div class="fee-card-top"><span class="fee-card-icon"><i class="bi bi-check2-circle" aria-hidden="true"></i></span><span class="fee-pill fee-pill-paid">Đã ghi nhận</span></div>
      <div class="fee-card-label">Phí đã nộp</div><div class="fee-card-amount">{{ number_format((float) $paidTotal, 0, ',', '.') }} <small>đ</small></div>
      <p>Tổng phí tiền mặt đã được ghi nhận thanh toán.</p>
    </section>
  </div>
  <section class="fee-panel">
    <div class="fee-panel-heading"><div><h2>Lịch sử phí tiền mặt</h2><p>Chi tiết phí từ các hóa đơn đã thu bằng tiền mặt.</p></div><span class="fee-count">{{ $payments->total() }} khoản phí</span></div>
    <div class="fee-table-wrap"><table class="fee-table">
      <thead><tr><th>Hóa đơn / phòng</th><th>Hóa đơn đã thanh toán</th><th>Phí nền tảng</th><th>Trạng thái</th><th><span class="visually-hidden">Thao tác</span></th></tr></thead>
      <tbody>@forelse($payments as $payment)
        <tr><td><div class="fee-invoice"><span class="fee-invoice-icon"><i class="bi bi-receipt" aria-hidden="true"></i></span><div><strong>{{ $payment->invoice?->invoice_code ?? '—' }}</strong><small>Phòng {{ $payment->invoice?->contract?->room?->name ?? '—' }}</small></div></div></td><td class="fee-money">{{ number_format((float) $payment->amount, 0, ',', '.') }} đ</td><td><strong class="fee-amount">{{ number_format((float) $payment->platform_fee, 0, ',', '.') }} đ</strong><small class="fee-rate">{{ number_format((float) $payment->platform_fee_rate, 0) }}% hóa đơn</small></td><td>@if($payment->fee_payment_status === 'paid')<span class="fee-status-badge is-paid"><i class="bi bi-check-circle-fill" aria-hidden="true"></i> Đã nộp</span><small class="fee-status-date">{{ $payment->fee_paid_at?->format('d/m/Y H:i') }}{{ $payment->fee_payment_source === 'demo' ? ' · Mô phỏng' : ($payment->fee_payment_source === 'sepay' ? ' · SePay' : ' · Dữ liệu cũ') }}</small>@else<span class="fee-status-badge is-pending"><i class="bi bi-clock-fill" aria-hidden="true"></i> Cần nộp</span>@endif</td><td class="fee-actions">
          @if($payment->fee_payment_status === 'pending' && (config('services.sepay.webhook_token') || app()->environment('local', 'testing')))
            <button type="button" class="fee-pay-btn" data-bs-toggle="modal" data-bs-target="#fee-modal-{{ $payment->id }}">Nộp phí</button>
          @elseif($payment->fee_payment_status === 'pending')<span class="fee-unavailable">Chưa hỗ trợ thanh toán trực tuyến</span>@else<span class="fee-complete">Hoàn tất</span>@endif
        </td></tr>
      @empty <tr><td colspan="5"><div class="fee-empty"><i class="bi bi-receipt" aria-hidden="true"></i><strong>Chưa có khoản phí nào</strong><span>Khi bạn xác nhận hóa đơn tiền mặt, phí sẽ hiển thị tại đây.</span></div></td></tr> @endforelse</tbody>
    </table></div>
    @if($payments->hasPages())<div class="fee-pagination">{{ $payments->links() }}</div>@endif
  </section>
  <div class="fee-explainer"><i class="bi bi-info-circle" aria-hidden="true"></i><div><strong>Phí nền tảng được tính thế nào?</strong><p>Phí bằng 5% giá trị hóa đơn người thuê trả tiền mặt. Với hóa đơn thanh toán QR, phí được ghi nhận khi thanh toán thành công.</p></div></div>
</div>
@foreach($payments as $payment)
  @if($payment->fee_payment_status === 'pending')
    @php
      $feeCode = 'FEE' . $payment->id;
      $qrUrl = 'https://qr.sepay.vn/img?' . http_build_query(['acc' => config('services.sepay.bank_account'), 'bank' => config('services.sepay.bank_name'), 'amount' => (int) ceil((float) $payment->platform_fee), 'des' => $feeCode, 'template' => config('services.sepay.qr_template', 'compact2')]);
    @endphp
    <div class="modal fade fee-modal" data-status-url="{{ route('owner.platform-fees.status', $payment) }}" id="fee-modal-{{ $payment->id }}" tabindex="-1" aria-labelledby="fee-title-{{ $payment->id }}" aria-hidden="true"><div class="modal-dialog modal-dialog-centered"><div class="modal-content">
      <div class="modal-header"><h2 class="modal-title fs-5" id="fee-title-{{ $payment->id }}">Nộp phí nền tảng</h2><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button></div>
      <div class="modal-body text-center">@if(!config('services.sepay.webhook_token'))<div class="alert alert-warning text-start">Chưa cấu hình mã xác thực webhook SePay; chỉ có thể giả lập. Không chuyển tiền thật.</div>@else<div class="alert alert-info text-start">Quét mã để nộp phí qua SePay. Giữ nguyên nội dung chuyển khoản để hệ thống nhận diện.</div>@endif
        <img src="{{ $qrUrl }}" alt="Mã QR minh họa nộp phí" class="img-fluid" style="max-width:220px;">
        <p class="mb-1">Hóa đơn {{ $payment->invoice?->invoice_code }}</p><p class="fw-bold fs-5">{{ number_format(ceil((float) $payment->platform_fee), 0, ',', '.') }} đ</p><p class="text-muted small">Nội dung chuyển khoản: <strong>{{ $feeCode }}</strong></p><p class="small fee-status" aria-live="polite">Đang chờ thanh toán.</p>
      </div><div class="modal-footer"><button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>@if(app()->environment('local', 'testing'))<form action="{{ route('owner.platform-fees.simulate', $payment) }}" method="POST" data-no-ajax="true">@csrf<button class="btn btn-success" type="submit">Giả lập webhook SePay</button></form>@endif</div>
    </div></div></div>
  @endif
@endforeach
<script>
document.querySelectorAll('.fee-modal').forEach(modal => {
  let timer;
  const check = async () => {
    try {
      const response = await fetch(modal.dataset.statusUrl, {headers: {'Accept': 'application/json'}, cache: 'no-store'});
      if (!response.ok) throw new Error();
      if ((await response.json()).status === 'paid') { clearInterval(timer); window.location.reload(); }
    } catch (_) { modal.querySelector('.fee-status').textContent = 'Không kiểm tra được trạng thái. Vui lòng tải lại trang.'; }
  };
  modal.addEventListener('shown.bs.modal', () => { check(); timer = setInterval(check, 5000); });
  modal.addEventListener('hidden.bs.modal', () => clearInterval(timer));
});
</script>
@endsection
