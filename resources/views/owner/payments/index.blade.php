@extends('Layout.landlord')

@section('title', 'Lịch sử thanh toán | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/payments/index.css') }}">
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
  <div class="row g-3 mb-4 animate-up delay-2">
    <div class="col-12 col-md-5">
      <div class="stat-primary skeleton-box">
        <div class="hide-on-skeleton">
          <div class="stat-period"><i class="bi bi-calendar3 me-1"></i>Tổng đã thu</div>
          <div class="stat-value mono-num">{{ number_format((float) $stats['collected']) }} ₫</div>
          <div class="stat-label">Các giao dịch thành công</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-7">
      <div class="row g-3 h-100">
        <div class="col-12">
          <div class="stat-secondary skeleton-box">
            <div class="hide-on-skeleton d-flex align-items-center gap-3 w-100">
              <div class="icon-chip" style="background: var(--bank-tint); color: var(--bank);"><i class="bi bi-clock"></i></div>
              <div>
                <div class="stat-value mono-num">{{ number_format((float) $stats['pending']) }} ₫</div>
                <div class="stat-label">Đang chờ xác nhận</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BẢNG GIAO DỊCH -->
  <div class="panel animate-up delay-3 skeleton-box">
    @php
      $methodLabels = ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR', 'online' => 'Online'];
      $payStatusLabels = ['pending' => 'Đang chờ', 'success' => 'Thành công', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'];
    @endphp
    <div class="panel-head d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom pb-3 hide-on-skeleton">
      <h3 class="panel-title mb-0">Danh sách giao dịch</h3>

      <form method="GET" action="{{ route('owner.payments.index') }}" class="d-flex flex-wrap gap-2">
        <input type="month" name="month" class="form-control form-control-sm w-auto shadow-none" value="{{ $filters['month'] ?? '' }}" onchange="this.form.submit()">
        <select name="method" class="form-select form-select-sm w-auto shadow-none fw-semibold" onchange="this.form.submit()">
          <option value="">Tất cả hình thức</option>
          @foreach($methodLabels as $value => $label)
            <option value="{{ $value }}" @selected(($filters['method'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <select name="status" class="form-select form-select-sm w-auto shadow-none fw-semibold" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          @foreach($payStatusLabels as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @if(!empty($filters['month']) || !empty($filters['method']) || !empty($filters['status']))
          <a href="{{ route('owner.payments.index') }}" class="btn btn-sm btn-light border">Xóa lọc</a>
        @endif
      </form>
    </div>

    <div class="table-responsive mt-3 hide-on-skeleton">
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
                <form action="{{ route('owner.payments.confirm', $payment->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn-icon success" data-bs-toggle="tooltip" title="Xác nhận" onclick="return confirm('Xác nhận đã nhận {{ number_format((float) $payment->amount) }} đ?')"><i class="bi bi-check-lg"></i></button>
                </form>
                <form action="{{ route('owner.payments.reject', $payment->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <button type="submit" class="btn-icon danger" data-bs-toggle="tooltip" title="Từ chối" onclick="return confirm('Từ chối giao dịch này?')"><i class="bi bi-x-lg"></i></button>
                </form>
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

    <div class="panel-footer hide-on-skeleton">
      <div>Tổng {{ $payments->total() }} giao dịch</div>
      <div>{{ $payments->links() }}</div>
    </div>
  </div>
</div>



@push('scripts')
    <script src="{{ asset('js/tv4/payments/index.js') }}"></script>
@endpush


@endsection