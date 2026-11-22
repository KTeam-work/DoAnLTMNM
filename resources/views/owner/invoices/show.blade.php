@extends('Layout.landlord')

@section('title', 'Chi tiết hóa đơn #' . $invoice->invoice_code . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/invoice/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/modal_premium.css') }}">
@endpush

@section('content')

<main class="page-wrap">
  @if(session('success'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 p-3 mb-4 shadow-sm border-0" 
         style="background: #ecfdf5; border-left: 5px solid #10b981 !important; border-radius: 14px; color: #065f46;" role="alert">
      <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; background: #10b981; color: white; font-size: 18px;">
        <i class="bi bi-check-lg"></i>
      </div>
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size: 14px;">Thành công!</div>
        <div style="font-size: 13px; color: #047857;">{{ session('success') }}</div>
      </div>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
  @endif

  @if(session('error'))
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 p-3 mb-4 shadow-sm border-0" 
         style="background: #fef2f2; border-left: 5px solid #ef4444 !important; border-radius: 14px; color: #991b1b;" role="alert">
      <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; background: #ef4444; color: white; font-size: 18px;">
        <i class="bi bi-exclamation-triangle"></i>
      </div>
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size: 14px;">Thông báo</div>
        <div style="font-size: 13px;">{{ session('error') }}</div>
      </div>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
  @endif

  <!-- Top Navigation & Header -->
  <div class="mb-3">
    <a href="{{ route('owner.invoices.index') }}" class="back-link mb-2 d-inline-flex">
      <i class="bi bi-arrow-left"></i> Quay lại danh sách hóa đơn
    </a>

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-1">
      <div class="d-flex align-items-center gap-2 flex-wrap">
        <h1 class="h3 fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">#{{ $invoice->invoice_code }}</h1>
        @php
          $statusLabels = [
            'unpaid' => 'Chưa thanh toán',
            'pending' => 'Chờ xác nhận',
            'paid' => 'Đã thanh toán',
            'overdue' => 'Quá hạn',
            'cancelled' => 'Đã hủy'
          ];
          $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
          $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
        @endphp
        <span class="badge-status {{ $displayStatus }}">
          @if($displayStatus === 'pending')
            <span class="spinner-grow spinner-grow-sm text-warning" style="width: 6px; height: 6px;" role="status"></span>
          @elseif($displayStatus === 'paid')
            <i class="bi bi-check-circle-fill"></i>
          @endif
          {{ $statusLabels[$displayStatus] ?? $displayStatus }}
        </span>
      </div>

      <!-- Action Buttons -->
      <div class="d-flex gap-2 align-items-center flex-wrap">
        @if($invoice->status !== 'paid' && $invoice->status !== 'cancelled')
          <button type="button" class="btn btn-success fw-bold d-flex align-items-center gap-2 shadow-sm btn-open-confirm-modal" 
                  style="border-radius: 12px; background: #059669; border: none; font-size: 13.5px; padding: 9px 18px;"
                  data-action="{{ route('owner.invoices.record-cash', $invoice->id) }}"
                  data-method-field=""
                  data-amount="{{ number_format((float) $invoice->total) }} đ"
                  data-code="{{ $invoice->invoice_code }}"
                  data-paymethod="Tiền mặt trực tiếp"
                  data-note="Xác nhận khách đã nộp tiền mặt trực tiếp và gạch nợ hóa đơn này?">
            <i class="bi bi-cash-stack"></i> Thu tiền mặt ({{ number_format((float) $invoice->total) }} đ)
          </button>
        @endif

        <a href="{{ route('owner.invoices.print', $invoice->id) }}" target="_blank" 
           class="btn btn-light fw-bold d-flex align-items-center gap-2 border shadow-sm" 
           style="border-radius: 12px; background: #ffffff; color: #1e293b; font-size: 13.5px; padding: 9px 18px;">
          <i class="bi bi-printer"></i> In hóa đơn
        </a>
      </div>
    </div>
  </div>

  @php
    $pendingPayment = $invoice->payments->firstWhere('status', 'pending');
  @endphp

  @if($pendingPayment)
    <!-- Callout Banner: Khách báo nộp tiền -->
    <div class="p-3 mb-4 rounded-4 d-flex align-items-center justify-content-between flex-wrap gap-3 shadow-sm" 
         style="background: #fffbeb; border: 1px solid #fde68a;">
      <div class="d-flex align-items-center gap-3">
        <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
             style="width: 44px; height: 44px; background: #fef3c7; color: #b45309; font-size: 20px;">
          <i class="bi bi-clock-history"></i>
        </div>
        <div>
          <div class="fw-bold text-dark" style="font-size: 14.5px;">
            Khách thuê đã báo nộp tiền: <span class="text-danger">{{ number_format((float) $pendingPayment->amount) }} đ</span>
            ({{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR'][$pendingPayment->method] ?? $pendingPayment->method }})
          </div>
          <div class="text-muted small">Mã giao dịch: <strong>{{ $pendingPayment->payment_code }}</strong> · Vui lòng xác nhận sau khi bạn đã kiểm tra và nhận đủ tiền.</div>
        </div>
      </div>
      <div class="d-flex gap-2">
        <button type="button" class="btn btn-success fw-bold px-3 py-2 shadow-sm btn-open-confirm-modal" 
                style="border-radius: 10px; font-size: 13px; background: #059669; border: none;"
                data-action="{{ route('owner.payments.confirm', $pendingPayment->id) }}"
                data-method-field="PUT"
                data-amount="{{ number_format((float) $pendingPayment->amount) }} đ"
                data-code="{{ $pendingPayment->payment_code }}"
                data-paymethod="{{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR SePay'][$pendingPayment->method] ?? $pendingPayment->method }}"
                data-note="Xác nhận đã nhận đủ số tiền và gạch nợ hóa đơn này?">
          ✓ Xác nhận đã nhận tiền
        </button>
        <button type="button" class="btn btn-outline-danger fw-bold px-3 py-2 btn-open-reject-modal" 
                style="border-radius: 10px; font-size: 13px;"
                data-action="{{ route('owner.payments.reject', $pendingPayment->id) }}"
                data-code="{{ $pendingPayment->payment_code }}">
          Từ chối
        </button>
      </div>
    </div>
  @endif

  <!-- Thông tin chung (4 Cột Thẻ) -->
  <div class="invoice-panel mb-4">
    <div class="invoice-panel-header">
      <span>Thông tin chung</span>
      <span class="text-muted fw-normal small">Hợp đồng: <strong>#{{ $invoice->contract->contract_code ?? '—' }}</strong></span>
    </div>
    <div class="row g-3">
      <!-- Cột 1: Phòng & Bất động sản -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Phòng trọ</div>
          <div class="info-item-value text-truncate">{{ $invoice->contract->room->name ?? '—' }}</div>
          <div class="info-item-sub text-truncate">{{ $invoice->contract->room->property->name ?? 'Nhà trọ' }}</div>
        </div>
      </div>

      <!-- Cột 2: Khách thuê -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Khách thuê</div>
          <div class="info-item-value text-truncate">{{ $invoice->contract->tenant->name ?? '—' }}</div>
          <div class="info-item-sub text-truncate">{{ $invoice->contract->tenant->phone ?? 'Chưa có SĐT' }}</div>
        </div>
      </div>

      <!-- Cột 3: Kỳ & Ngày lập -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Kỳ thanh toán</div>
          <div class="info-item-value">Tháng {{ $invoice->billing_month->format('m/Y') }}</div>
          <div class="info-item-sub">Ngày lập: {{ $invoice->issue_date->format('d/m/Y') }}</div>
        </div>
      </div>

      <!-- Cột 4: Hạn thanh toán -->
      <div class="col-md-3 col-6">
        <div class="info-item-box">
          <div class="info-item-label">Hạn thanh toán</div>
          <div class="info-item-value {{ $isOverdue ? 'text-danger' : 'text-dark' }}">
            {{ $invoice->due_date->format('d/m/Y') }}
          </div>
          <div class="info-item-sub">
            @if($invoice->status === 'paid')
              <span class="text-success fw-bold">Đã hoàn tất</span>
            @elseif($isOverdue)
              <span class="text-danger fw-bold">Đã quá hạn thanh toán</span>
            @else
              @php
                $days = (int) ceil(now()->diffInDays($invoice->due_date, false));
              @endphp
              <span class="text-muted">{{ $days > 0 ? 'Còn ' . $days . ' ngày' : 'Đến hạn hôm nay' }}</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Chi tiết các khoản -->
  <div class="invoice-panel mb-4">
    <div class="invoice-panel-header">
      <span>Chi tiết các khoản phí</span>
      <span class="text-muted fw-normal small">{{ count($invoice->items) }} hạng mục</span>
    </div>

    <div class="table-responsive">
      <table class="invoice-table">
        <thead>
          <tr>
            <th style="width: 50px;">#</th>
            <th>Hạng mục</th>
            <th class="text-end" style="width: 120px;">Số lượng</th>
            <th class="text-end" style="width: 150px;">Đơn giá</th>
            <th class="text-end" style="width: 160px;">Thành tiền</th>
          </tr>
        </thead>
        <tbody>
          @foreach($invoice->items as $index => $item)
          <tr>
            <td class="text-muted fw-bold">{{ $index + 1 }}</td>
            <td class="fw-bold text-dark">{{ $item->description }}</td>
            <td class="text-end">{{ rtrim(rtrim(number_format((float) $item->quantity, 2), '0'), '.') }}</td>
            <td class="text-end text-muted">{{ number_format((float) $item->unit_price) }} đ</td>
            <td class="text-end fw-bold text-dark">{{ number_format((float) $item->amount) }} đ</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <!-- Tổng tiền -->
    <div class="d-flex justify-content-end mt-4 pt-3 border-top">
      <div style="min-width: 260px;">
        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 13.5px;">
          <span>Tạm tính:</span>
          <span class="fw-bold text-dark">{{ number_format((float) $invoice->subtotal) }} đ</span>
        </div>
        @if((float) $invoice->discount > 0)
        <div class="d-flex justify-content-between text-muted mb-2" style="font-size: 13.5px;">
          <span>Giảm giá:</span>
          <span class="text-danger fw-bold">-{{ number_format((float) $invoice->discount) }} đ</span>
        </div>
        @endif
        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
          <span class="fw-bold text-dark" style="font-size: 15px;">Tổng cộng:</span>
          <span class="fw-bold fs-4" style="color: #059669;">{{ number_format((float) $invoice->total) }} đ</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Lịch sử giao dịch -->
  <div class="invoice-panel">
    <div class="invoice-panel-header">
      <span>Lịch sử thanh toán & Giao dịch</span>
      <span class="text-muted fw-normal small">{{ count($invoice->payments) }} giao dịch</span>
    </div>

    @forelse($invoice->payments as $payment)
      <div class="payment-item d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
          <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" 
               style="width: 40px; height: 40px; background: {{ $payment->status === 'success' ? '#ecfdf5' : ($payment->status === 'pending' ? '#fffbeb' : '#f1f5f9') }}; color: {{ $payment->status === 'success' ? '#059669' : ($payment->status === 'pending' ? '#b45309' : '#64748b') }}; font-size: 18px;">
            <i class="bi bi-{{ $payment->method === 'cash' ? 'cash-coin' : ($payment->method === 'qr' ? 'qr-code' : 'bank') }}"></i>
          </div>
          <div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
              <span class="fw-bold text-dark" style="font-size: 14px;">#{{ $payment->payment_code }}</span>
              <span class="badge bg-light text-secondary border" style="font-size: 11px;">
                {{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR SePay', 'online' => 'Online'][$payment->method] ?? $payment->method }}
              </span>
            </div>
            <div class="text-muted small mt-1">
              {{ $payment->created_at ? $payment->created_at->format('H:i · d/m/Y') : '—' }}
              @if($payment->transaction_code)
                · Mã GD: <code>{{ $payment->transaction_code }}</code>
              @endif
            </div>
          </div>
        </div>

        <div class="d-flex align-items-center gap-3">
          <span class="fw-bold fs-6 text-dark">{{ number_format((float) $payment->amount) }} đ</span>
          <span class="badge-status {{ $payment->status === 'success' ? 'paid' : ($payment->status === 'pending' ? 'pending' : 'cancelled') }}">
            {{ ['pending' => 'Chờ xác nhận', 'success' => 'Thành công', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'][$payment->status] ?? $payment->status }}
          </span>

          @if($payment->status === 'pending')
            <div class="d-flex gap-1">
              <button type="button" class="btn btn-sm btn-success fw-bold px-3 py-1 btn-open-confirm-modal" 
                      style="border-radius: 8px; background: #059669; border: none;"
                      data-action="{{ route('owner.payments.confirm', $payment->id) }}"
                      data-method-field="PUT"
                      data-amount="{{ number_format((float) $payment->amount) }} đ"
                      data-code="{{ $payment->payment_code }}"
                      data-paymethod="{{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR SePay'][$payment->method] ?? $payment->method }}"
                      data-note="Xác nhận đã nhận đủ số tiền và gạch nợ hóa đơn này?">
                ✓ Xác nhận
              </button>
              <button type="button" class="btn btn-sm btn-outline-danger fw-bold px-3 py-1 btn-open-reject-modal" 
                      style="border-radius: 8px;"
                      data-action="{{ route('owner.payments.reject', $payment->id) }}"
                      data-code="{{ $payment->payment_code }}">
                Từ chối
              </button>
            </div>
          @endif
        </div>
      </div>
    @empty
      <div class="text-center text-muted py-4" style="font-size: 13.5px;">
        Chưa có giao dịch thanh toán nào được ghi nhận cho hóa đơn này.
      </div>
    @endforelse
  </div>
</main>

<!-- CONFIRM PAYMENT MODAL (PREMIUM) -->
<div class="modal fade modal-premium" id="confirmPaymentModal" tabindex="-1" aria-labelledby="confirmPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmPaymentModalLabel">Xác nhận nhận tiền thanh toán</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <form id="confirmPaymentForm" method="POST" action="">
        @csrf
        <div id="confirmMethodFieldContainer"></div>
        <div class="modal-body p-4 text-center">
          <div class="mb-2 text-muted small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Số tiền xác nhận</div>
          <div class="fw-bold text-success mb-3" id="confirmModalAmount" style="font-size: 28px; font-family: 'Be Vietnam Pro', sans-serif;">
            0 đ
          </div>
          <div class="p-3 bg-light rounded-3 text-start small mb-3 border">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Mã tham chiếu:</span>
              <strong class="text-dark" id="confirmModalCode">—</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Hình thức thanh toán:</span>
              <strong class="text-dark" id="confirmModalMethod">—</strong>
            </div>
          </div>
          <p class="text-secondary small mb-0" id="confirmModalNote">
            Bạn xác nhận đã nhận đủ số tiền trên và muốn cập nhật trạng thái hóa đơn này sang <strong>Đã thanh toán</strong>?
          </p>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-light px-4 rounded-3 border" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-success px-4 rounded-3 fw-bold" style="background: #059669; border: none;">
            ✓ Xác nhận đã nhận tiền
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- REJECT PAYMENT MODAL (PREMIUM) -->
<div class="modal fade modal-premium" id="rejectPaymentModal" tabindex="-1" aria-labelledby="rejectPaymentModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header" style="background: #991b1b !important;">
        <h5 class="modal-title" id="rejectPaymentModalLabel">Từ chối giao dịch thanh toán</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <form id="rejectPaymentForm" method="POST" action="">
        @csrf
        @method('PUT')
        <div class="modal-body p-4 text-center">
          <div class="text-danger mb-3" style="font-size: 42px;">
            <i class="bi bi-exclamation-triangle"></i>
          </div>
          <h6 class="fw-bold text-dark mb-2">Bạn có chắc chắn muốn từ chối?</h6>
          <p class="text-secondary small mb-2">
            Giao dịch <strong id="rejectModalCode">—</strong> sẽ bị chuyển sang trạng thái <strong>Thất bại / Đã hủy</strong> và hóa đơn sẽ tiếp tục chờ thanh toán.
          </p>
        </div>
        <div class="modal-footer d-flex justify-content-between">
          <button type="button" class="btn btn-light px-4 rounded-3 border" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger px-4 rounded-3 fw-bold">
            ✕ Xác nhận từ chối
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var confirmModalEl = document.getElementById('confirmPaymentModal');
  var confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
  var confirmForm = document.getElementById('confirmPaymentForm');
  var confirmAmount = document.getElementById('confirmModalAmount');
  var confirmCode = document.getElementById('confirmModalCode');
  var confirmMethod = document.getElementById('confirmModalMethod');
  var confirmNote = document.getElementById('confirmModalNote');
  var confirmMethodFieldContainer = document.getElementById('confirmMethodFieldContainer');

  document.querySelectorAll('.btn-open-confirm-modal').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!confirmModal) return;
      confirmForm.action = this.getAttribute('data-action') || '';
      confirmAmount.textContent = this.getAttribute('data-amount') || '0 đ';
      confirmCode.textContent = this.getAttribute('data-code') || '—';
      confirmMethod.textContent = this.getAttribute('data-paymethod') || 'Tiền mặt';
      if (confirmNote) {
        confirmNote.innerHTML = this.getAttribute('data-note') || 'Bạn xác nhận đã nhận đủ số tiền trên và muốn cập nhật trạng thái hóa đơn này?';
      }

      var methodField = this.getAttribute('data-method-field');
      if (methodField === 'PUT') {
        confirmMethodFieldContainer.innerHTML = '<input type="hidden" name="_method" value="PUT">';
      } else {
        confirmMethodFieldContainer.innerHTML = '';
      }

      confirmModal.show();
    });
  });

  var rejectModalEl = document.getElementById('rejectPaymentModal');
  var rejectModal = rejectModalEl ? new bootstrap.Modal(rejectModalEl) : null;
  var rejectForm = document.getElementById('rejectPaymentForm');
  var rejectCode = document.getElementById('rejectModalCode');

  document.querySelectorAll('.btn-open-reject-modal').forEach(function (btn) {
    btn.addEventListener('click', function () {
      if (!rejectModal) return;
      rejectForm.action = this.getAttribute('data-action') || '';
      rejectCode.textContent = this.getAttribute('data-code') || '—';
      rejectModal.show();
    });
  });
});
</script>
@endpush

@endsection
