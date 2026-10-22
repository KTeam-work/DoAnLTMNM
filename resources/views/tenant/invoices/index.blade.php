

@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/invoices/index.css') }}">
@endpush

@section('content')






<div class="page-wrap" id="invoices" style="padding-top: 30px;">
  
  <!-- Header -->
  <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Quản lý Hóa đơn</span></h2>
      <div class="page-desc skeleton-box mt-2"><span class="hide-on-skeleton">Theo dõi công nợ, lịch sử thanh toán và biên lai phòng trọ của bạn.</span></div>
    </div>
    <div class="skeleton-box rounded">
      <button class="btn-outline-brand d-flex align-items-center gap-2 hide-on-skeleton">
        ⬇ Tải sao kê năm
      </button>
    </div>
  </div>

  <!-- Dashboard Cards -->
  <div class="row g-3 mb-4 animate-up delay-2">
    <!-- Card 1 -->
    <div class="col-md-4">
      <div class="stat-card p-3 rounded-4">
        <div class="stat-top mb-3 skeleton-box">
          <div class="stat-icon red hide-on-skeleton">⚠️</div>
          <div class="stat-trend down fw-bold hide-on-skeleton">Cần xử lý ngay</div>
        </div>
        <div class="skeleton-box mt-2">
          <div class="stat-label hide-on-skeleton">Tổng nợ quá hạn</div>
          <div class="stat-value hide-on-skeleton" style="color: var(--red);">{{ number_format((float) $stats['overdue']) }} đ</div>
        </div>
      </div>
    </div>
    <!-- Card 2 -->
    <div class="col-md-4">
      <div class="stat-card p-3 rounded-4">
        <div class="stat-top mb-3 skeleton-box">
          <div class="stat-icon hide-on-skeleton" style="background: var(--yellow-light); color: #9b6a00;">⏳</div>
          <div class="stat-trend hide-on-skeleton" style="color: #9b6a00;">Chưa thanh toán</div>
        </div>
        <div class="skeleton-box mt-2">
          <div class="stat-label hide-on-skeleton">Chưa thanh toán</div>
          <div class="stat-value hide-on-skeleton">{{ number_format((float) $stats['unpaid']) }} đ</div>
        </div>
      </div>
    </div>
    <!-- Card 3 -->
    <div class="col-md-4">
      <div class="stat-card p-3 rounded-4">
        <div class="stat-top mb-3 skeleton-box">
          <div class="stat-icon green hide-on-skeleton">✅</div>
          <div class="stat-trend fw-bold hide-on-skeleton">Tốt</div>
        </div>
        <div class="skeleton-box mt-2">
          <div class="stat-label hide-on-skeleton">Đã thanh toán (Năm nay)</div>
          <div class="stat-value hide-on-skeleton" style="color: var(--green);">{{ number_format((float) $stats['paid_year']) }} đ</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bảng Dữ Liệu -->
  <div class="animate-up delay-3">
    @php
      $statusLabels = ['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'];
    @endphp
    <!-- Thanh Filter -->
    <form method="GET" action="{{ route('tenant.invoices.index') }}" class="enterprise-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3 border-0 bg-white rounded-4 p-3 skeleton-box" style="box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
      <div class="d-flex flex-wrap gap-2 hide-on-skeleton">
        <select name="status" class="form-select form-select-sm border-0 bg-light shadow-none fw-bold" style="border-radius: 8px;" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          @foreach($statusLabels as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <input type="month" name="month" class="form-control form-control-sm border-0 bg-light shadow-none fw-bold" style="border-radius: 8px;" value="{{ $filters['month'] ?? '' }}" onchange="this.form.submit()">
      </div>
      <div class="hide-on-skeleton">
        @if(!empty($filters['status']) || !empty($filters['month']))
          <a href="{{ route('tenant.invoices.index') }}" class="btn btn-sm btn-light fw-bold">Xóa lọc</a>
        @endif
      </div>
    </form>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive" style="min-height: 300px;">
      <table class="data-table align-middle w-100">
        <thead>
          <tr>
            <th style="padding-left: 20px;">MÃ HĐ / KỲ</th>
            <th>CHI TIẾT</th>
            <th>HẠN THANH TOÁN</th>
            <th>TỔNG TIỀN</th>
            <th>TRẠNG THÁI</th>
            <th class="text-end" style="padding-right: 20px;">THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          @forelse($invoices as $invoice)
            @php
              $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
              $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
            @endphp
          <tr>
            <td style="padding-left: 20px;">
              <div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">#{{ $invoice->invoice_code }}</div></div>
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Kỳ: Tháng {{ $invoice->billing_month->format('m/Y') }}</div></div>
            </td>
            <td>
              <div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">{{ $invoice->contract->room->name ?? '' }}</div></div>
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton">{{ $invoice->contract->contract_code ?? '' }}</div></div>
            </td>
            <td>
              <div class="skeleton-box mb-1"><div class="hide-on-skeleton" style="font-weight: 600;{{ $isOverdue ? ' color: var(--red);' : '' }}">{{ $invoice->due_date->format('d/m/Y') }}</div></div>
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton" style="{{ $isOverdue ? 'color: var(--red);' : '' }}">{{ $isOverdue ? 'Đã quá hạn' : ($invoice->status === 'paid' ? 'Đã thanh toán' : 'Còn ' . now()->diffInDays($invoice->due_date) . ' ngày') }}</div></div>
            </td>
            <td><div class="skeleton-box"><strong class="hide-on-skeleton" style="color: var(--green-dark); font-size: 15px;">{{ number_format((float) $invoice->total) }} đ</strong></div></td>
            <td><div class="skeleton-box rounded-pill"><span class="badge-status {{ $displayStatus }} hide-on-skeleton">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</span></div></td>
            <td class="text-end" style="padding-right: 20px;">
              <div class="d-flex justify-content-end align-items-center gap-2 skeleton-box rounded">
                @if(in_array($invoice->status, ['unpaid', 'pending', 'overdue']))
                  <a href="{{ route('tenant.invoices.show', $invoice->id) }}#pay" class="btn-brand hide-on-skeleton text-decoration-none" style="padding: 6px 16px; font-size: 12px;{{ $isOverdue ? ' background: var(--red); color: white;' : '' }}">Thanh toán</a>
                @else
                  <span class="hide-on-skeleton" style="font-size: 12px; color: var(--muted); padding-right: 8px; font-weight: 600;">✓ Hoàn tất</span>
                @endif
                <div class="dropdown action-dropdown hide-on-skeleton">
                  <button class="btn dropdown-toggle border-0 shadow-none text-muted" type="button" data-bs-toggle="dropdown">⋮</button>
                  <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px;">
                    <li><a class="dropdown-item py-2" href="{{ route('tenant.invoices.show', $invoice->id) }}">👁️ Xem chi tiết</a></li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-5">Bạn chưa có hóa đơn nào.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <div class="mt-3">{{ $invoices->links() }}</div>
  </div>
</div>


@push('scripts')
    <script src="{{ asset('js/tv3/tenant/invoices/index.js') }}"></script>
@endpush


@endsection