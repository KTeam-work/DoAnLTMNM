@extends('Layout.landlord')

@section('title', 'Lịch sử thanh toán | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/payments/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/modal_premium.css') }}">
@endpush

@section('content')


<div class="page-wrap pt-4 pb-5">
  <!-- Page Header -->
  <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Lịch sử giao dịch</span></h2>
      <div class="page-desc mt-1 skeleton-box"><span class="hide-on-skeleton">Theo dõi dòng tiền và xác nhận thanh toán của người thuê.</span></div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4 animate-up delay-2" id="paymentStatCards">
    <div class="col-12 col-md-6">
      <div class="stat-primary skeleton-box">
        <div class="hide-on-skeleton">
          <div class="stat-period"><i class="bi bi-calendar3 me-1"></i>Tổng đã thu</div>
          <div class="stat-value mono-num" id="statCollectedValue">{{ number_format((float) $stats['collected']) }} ₫</div>
          <div class="stat-label">Các giao dịch thành công</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-6">
      <div class="stat-secondary skeleton-box">
        <div class="hide-on-skeleton">
          <div class="stat-period"><i class="bi bi-clock-history me-1"></i>Đang chờ xác nhận</div>
          <div class="stat-value mono-num" id="statPendingValue">{{ number_format((float) $stats['pending']) }} ₫</div>
          <div class="stat-label">Các giao dịch chuyển khoản chờ duyệt</div>
        </div>
      </div>
    </div>
  </div>

  <!-- BẢNG GIAO DỊCH -->
  <div class="panel animate-up delay-3 skeleton-box">
    @php
      $methodLabels = ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản'];
      $payStatusLabels = ['pending' => 'Đang chờ', 'success' => 'Thành công', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'];
    @endphp
    <div class="panel-head d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom pb-3 hide-on-skeleton">
      <h3 class="panel-title mb-0">Danh sách giao dịch</h3>

      <form id="paymentFilterForm" method="GET" action="{{ route('owner.payments.index') }}" class="d-flex flex-wrap gap-2">
        <input type="month" name="month" id="filterMonth" class="form-control form-control-sm w-auto shadow-none" value="{{ $filters['month'] ?? '' }}">
        <select name="method" id="filterMethod" class="form-select form-select-sm w-auto shadow-none fw-semibold">
          <option value="">Tất cả hình thức</option>
          @foreach($methodLabels as $value => $label)
            <option value="{{ $value }}" @selected(($filters['method'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" id="filterStatus" class="form-select form-select-sm w-auto shadow-none fw-semibold">
          <option value="">Tất cả trạng thái</option>
          @foreach($payStatusLabels as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <div id="paymentResetWrapper" class="d-inline-flex">
          @if(!empty($filters['month']) || !empty($filters['method']) || !empty($filters['status']))
            <a href="{{ route('owner.payments.index') }}" class="btn btn-sm btn-light border" id="resetPaymentFilterBtn">Xóa lọc</a>
          @endif
        </div>
      </form>
    </div>

    <div class="table-responsive mt-3 hide-on-skeleton" id="paymentTableWrapper">
      <table class="data-table" id="paymentTable">
        <thead>
          <tr>
            <th class="ps-2">Mã giao dịch / ngày</th>
            <th>Thông tin phòng</th>
            <th>Tham chiếu hóa đơn</th>
            <th>Số tiền</th>
            <th>Hình thức</th>
            <th>Trạng thái</th>
            <th class="text-end pe-2">Thao tác</th>
          </tr>
        </thead>
        <tbody id="paymentTableBody">
          @forelse($payments as $payment)
          <tr>
            <td class="ps-2">
              <div class="txn-id">#{{ $payment->payment_code }}</div>
              <div class="txn-time"><i class="bi bi-clock me-1"></i>{{ $payment->created_at->format('d/m/y · H:i') }}</div>
            </td>
            <td>
              <div class="room-name">{{ $payment->invoice->contract->room->name ?? '' }}</div>
              <div class="tenant-name">{{ $payment->invoice->contract->tenant->name ?? ($payment->payer->name ?? '') }}</div>
            </td>
            <td>
              @if($payment->invoice)
                <a href="{{ route('owner.invoices.show', $payment->invoice->id) }}" class="invoice-ref">#{{ $payment->invoice->invoice_code }}</a>
              @else
                <span class="deposit-ref">—</span>
              @endif
            </td>
            <td><div class="amount-in mono-num">+ {{ number_format((float) $payment->amount) }} ₫</div></td>
            <td><span class="badge-method {{ $payment->method === 'cash' ? 'cash' : ($payment->method === 'bank_transfer' ? 'transfer' : 'other') }}">{{ $methodLabels[$payment->method] ?? $payment->method }}</span></td>
            <td><span class="badge-status {{ $payment->status }}">{{ $payStatusLabels[$payment->status] ?? $payment->status }}</span></td>
            <td class="text-end pe-2">
              @if($payment->status === 'pending')
                <button type="button" class="btn-icon success btn-open-confirm-modal" 
                        data-bs-toggle="tooltip" title="Xác nhận đã nhận tiền"
                        data-action="{{ route('owner.payments.confirm', $payment->id) }}"
                        data-amount="{{ number_format((float) $payment->amount) }} đ"
                        data-code="{{ $payment->payment_code }}"
                        data-room="{{ $payment->invoice->contract->room->name ?? '' }}"
                        data-tenant="{{ $payment->invoice->contract->tenant->name ?? ($payment->payer->name ?? '') }}"
                        data-paymethod="{{ $methodLabels[$payment->method] ?? $payment->method }}">
                  <i class="bi bi-check-lg"></i>
                </button>
                <button type="button" class="btn-icon danger btn-open-reject-modal" 
                        data-bs-toggle="tooltip" title="Từ chối giao dịch"
                        data-action="{{ route('owner.payments.reject', $payment->id) }}"
                        data-code="{{ $payment->payment_code }}">
                  <i class="bi bi-x-lg"></i>
                </button>
              @else
                <span class="text-muted small">—</span>
              @endif
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-5">Chưa có giao dịch nào.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="panel-footer hide-on-skeleton" id="paymentFooterWrapper">
      <div>Tổng {{ $payments->total() }} giao dịch</div>
      <div>{{ $payments->links() }}</div>
    </div>
  </div>

  <script>
    if (typeof window.initPaymentAjaxFilters === 'function') {
      window.initPaymentAjaxFilters();
    }
  </script>
</div>

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
        @method('PUT')
        <div class="modal-body p-4 text-center">
          <div class="mb-2 text-muted small text-uppercase fw-bold" style="letter-spacing: 0.5px;">Số tiền xác nhận</div>
          <div class="fw-bold text-success mb-3" id="confirmModalAmount" style="font-size: 28px; font-family: 'Be Vietnam Pro', sans-serif;">
            0 đ
          </div>
          <div class="p-3 bg-light rounded-3 text-start small mb-3 border">
            <div class="d-flex justify-content-between mb-2">
              <span class="text-muted">Mã giao dịch:</span>
              <strong class="text-dark" id="confirmModalCode">—</strong>
            </div>
            <div class="d-flex justify-content-between mb-2" id="confirmModalRoomRow">
              <span class="text-muted">Phòng / Khách thuê:</span>
              <strong class="text-dark" id="confirmModalRoom">—</strong>
            </div>
            <div class="d-flex justify-content-between">
              <span class="text-muted">Hình thức thanh toán:</span>
              <strong class="text-dark" id="confirmModalMethod">—</strong>
            </div>
          </div>
          <p class="text-secondary small mb-0">
            Bạn xác nhận đã nhận đủ số tiền trên và muốn cập nhật trạng thái giao dịch sang <strong>Thành công</strong>?
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
            Giao dịch <strong id="rejectModalCode">—</strong> sẽ bị chuyển sang trạng thái <strong>Thất bại / Đã hủy</strong> và yêu cầu người thuê nộp lại.
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
    <script src="{{ asset('js/tv4/payments/index.js') }}"></script>
    <script>
      if (typeof window.initPaymentAjaxFilters === 'function') {
        window.initPaymentAjaxFilters();
      }

      document.addEventListener('DOMContentLoaded', function () {
        var confirmModalEl = document.getElementById('confirmPaymentModal');
        var confirmModal = confirmModalEl ? new bootstrap.Modal(confirmModalEl) : null;
        var confirmForm = document.getElementById('confirmPaymentForm');
        var confirmAmount = document.getElementById('confirmModalAmount');
        var confirmCode = document.getElementById('confirmModalCode');
        var confirmRoom = document.getElementById('confirmModalRoom');
        var confirmMethod = document.getElementById('confirmModalMethod');

        var rejectModalEl = document.getElementById('rejectPaymentModal');
        var rejectModal = rejectModalEl ? new bootstrap.Modal(rejectModalEl) : null;
        var rejectForm = document.getElementById('rejectPaymentForm');
        var rejectCode = document.getElementById('rejectModalCode');

        // Event delegation để hoạt động tốt cả khi table được reload qua Ajax
        document.addEventListener('click', function (e) {
          var btnConfirm = e.target.closest('.btn-open-confirm-modal');
          if (btnConfirm && confirmModal) {
            confirmForm.action = btnConfirm.getAttribute('data-action') || '';
            confirmAmount.textContent = btnConfirm.getAttribute('data-amount') || '0 đ';
            confirmCode.textContent = btnConfirm.getAttribute('data-code') || '—';
            var roomInfo = btnConfirm.getAttribute('data-room') || '';
            var tenantInfo = btnConfirm.getAttribute('data-tenant') || '';
            confirmRoom.textContent = (roomInfo ? roomInfo + ' · ' : '') + (tenantInfo || '—');
            confirmMethod.textContent = btnConfirm.getAttribute('data-paymethod') || 'Chuyển khoản';
            confirmModal.show();
            return;
          }

          var btnReject = e.target.closest('.btn-open-reject-modal');
          if (btnReject && rejectModal) {
            rejectForm.action = btnReject.getAttribute('data-action') || '';
            rejectCode.textContent = btnReject.getAttribute('data-code') || '—';
            rejectModal.show();
            return;
          }
        });
      });
    </script>
@endpush


@endsection