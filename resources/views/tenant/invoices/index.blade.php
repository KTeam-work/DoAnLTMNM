

@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/invoices/index.css') }}?v={{ filemtime(public_path('css/tv3/tenant/invoices/index.css')) }}">
@endpush

@section('content')






<div class="page-wrap invoice-page" id="invoices">
  
  <!-- Header -->
  <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 animate-up delay-1">
    <div>
      <div class="invoice-eyebrow">KHÔNG GIAN CỦA BẠN</div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Quản lý Hóa đơn</span></h2>
      <div class="page-desc skeleton-box mt-2"><span class="hide-on-skeleton">Theo dõi công nợ, lịch sử thanh toán và biên lai phòng trọ của bạn.</span></div>
    </div>
  </div>

  <!-- Dashboard Cards -->
  <div class="row g-3 mb-4 animate-up delay-2">
    <!-- Card 1 -->
    <div class="col-md-4">
      <div class="stat-card invoice-stat invoice-stat-overdue">
        <span class="invoice-stat-icon" aria-hidden="true">!</span>
        <div class="stat-label hide-on-skeleton mb-2">Tổng nợ quá hạn</div>
        <div class="stat-value hide-on-skeleton">{{ number_format((float) $stats['overdue'], 0, ',', '.') }} đ</div>
      </div>
    </div>
    <!-- Card 2 -->
    <div class="col-md-4">
      <div class="stat-card invoice-stat invoice-stat-unpaid">
        <span class="invoice-stat-icon" aria-hidden="true">₫</span>
        <div class="stat-label hide-on-skeleton mb-2">Chưa thanh toán</div>
        <div class="stat-value hide-on-skeleton">{{ number_format((float) $stats['unpaid'], 0, ',', '.') }} đ</div>
      </div>
    </div>
    <!-- Card 3 -->
    <div class="col-md-4">
      <div class="stat-card invoice-stat invoice-stat-paid">
        <span class="invoice-stat-icon" aria-hidden="true">✓</span>
        <div class="stat-label hide-on-skeleton mb-2">Đã thanh toán (Năm nay)</div>
        <div class="stat-value hide-on-skeleton">{{ number_format((float) $stats['paid_year'], 0, ',', '.') }} đ</div>
      </div>
    </div>
  </div>

  <!-- Bảng Dữ Liệu -->
  <div class="animate-up delay-3">
    @php
      $statusLabels = ['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'];
    @endphp
    <!-- Thanh Filter -->
    <form method="GET" action="{{ route('tenant.invoices.index') }}" id="invoice-filter-form" class="enterprise-filter-bar invoice-filter mb-3 skeleton-box">
      <div class="invoice-filter-heading hide-on-skeleton"><strong>Danh sách hóa đơn</strong><span>Lọc theo trạng thái hoặc kỳ thanh toán</span></div>
      <div class="invoice-filter-controls hide-on-skeleton">
        <label class="visually-hidden" for="invoice-status">Trạng thái hóa đơn</label>
        <select id="invoice-status" name="status" class="form-select" onchange="this.form.requestSubmit()">
          <option value="">Tất cả trạng thái</option>
          @foreach($statusLabels as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <label class="visually-hidden" for="invoice-month">Tháng hóa đơn</label>
        <input id="invoice-month" type="month" name="month" class="form-control" value="{{ $filters['month'] ?? '' }}" onchange="this.form.requestSubmit()">
        @if(!empty($filters['status']) || !empty($filters['month']))
          <a href="{{ route('tenant.invoices.index') }}" class="btn btn-sm btn-light fw-bold">Xóa lọc</a>
        @endif
      </div>
    </form>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div id="invoice-results" aria-live="polite" aria-busy="false">
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
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton" style="{{ $isOverdue ? 'color: var(--red);' : '' }}">
                @if($isOverdue)
                  Đã quá hạn
                @elseif($invoice->status === 'paid')
                  Đã thanh toán
                @else
                  @php
                    $diffDays = (int) ceil(now()->diffInDays($invoice->due_date, false));
                  @endphp
                  {{ $diffDays > 0 ? 'Còn ' . $diffDays . ' ngày' : ($diffDays === 0 ? 'Đến hạn hôm nay' : 'Đã quá hạn') }}
                @endif
              </div></div>
            </td>
            <td><div class="skeleton-box"><strong class="hide-on-skeleton" style="color: var(--green-dark); font-size: 15px;">{{ number_format((float) $invoice->total) }} đ</strong></div></td>
            <td><div class="skeleton-box rounded-pill"><span class="badge-status {{ $displayStatus }} hide-on-skeleton">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</span></div></td>
            <td class="text-end" style="padding-right: 20px;">
              <div class="d-flex justify-content-end align-items-center gap-2 skeleton-box rounded">
                @php
                  $hasPendingPayment = $invoice->status === 'pending' || ($invoice->relationLoaded('payments') ? $invoice->payments->where('status', 'pending')->count() > 0 : $invoice->payments()->where('status', 'pending')->exists());
                @endphp
                @if($hasPendingPayment)
                  <span class="hide-on-skeleton badge bg-warning bg-opacity-10 text-warning-emphasis fw-bold" style="font-size: 12px; padding: 6px 12px; border-radius: 8px; border: 1px solid rgba(245, 158, 11, 0.3);">
                    Chờ xác nhận
                  </span>
                  <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="btn btn-sm btn-light hide-on-skeleton text-decoration-none" style="padding: 6px 12px; font-size: 12px; border-radius: 8px; font-weight: 600;">Chi tiết</a>
                @elseif(in_array($invoice->status, ['unpaid', 'overdue']))
                  <button type="button" class="btn-brand hide-on-skeleton btn-pay-qr border-0"
                          data-invoice-id="{{ $invoice->id }}"
                          data-invoice-code="{{ $invoice->invoice_code }}"
                          style="padding: 6px 14px; font-size: 12px; border-radius: 8px; cursor: pointer; display: inline-flex; align-items: center; gap: 4px;{{ $isOverdue ? ' background: var(--red); color: white;' : '' }}">
                    Thanh toán QR
                  </button>
                  <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="btn btn-sm btn-light hide-on-skeleton text-decoration-none" style="padding: 6px 12px; font-size: 12px; border-radius: 8px; font-weight: 600;">Chi tiết</a>
                @elseif($invoice->status === 'paid')
                  <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="btn btn-sm btn-light hide-on-skeleton text-decoration-none" style="padding: 6px 12px; font-size: 12px; border-radius: 8px; font-weight: 600;">Chi tiết</a>
                @else
                  <span class="hide-on-skeleton" style="font-size: 12px; color: var(--muted); padding-right: 8px; font-weight: 600;">{{ $statusLabels[$invoice->status] ?? $invoice->status }}</span>
                  <a href="{{ route('tenant.invoices.show', $invoice->id) }}" class="btn btn-sm btn-light hide-on-skeleton text-decoration-none" style="padding: 6px 12px; font-size: 12px; border-radius: 8px; font-weight: 600;">Chi tiết</a>
                @endif
                <div class="dropdown action-dropdown hide-on-skeleton">
                  <button class="btn dropdown-toggle border-0 shadow-none text-muted" type="button" data-bs-toggle="dropdown">⋮</button>
                  <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px;">
                    <li><a class="dropdown-item py-2" href="{{ route('tenant.invoices.show', $invoice->id) }}">Xem chi tiết</a></li>
                    <li><a class="dropdown-item py-2" href="{{ route('tenant.invoices.print', $invoice->id) }}" target="_blank">In hóa đơn / Lưu PDF</a></li>
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
    <div id="invoice-filter-error" role="alert" class="text-danger small mt-2" hidden>Không thể tải danh sách. Vui lòng thử lại.</div>
  </div>
</div>


@include('tenant.invoices.partials.qr_modal')

@push('scripts')
    <script src="{{ asset('js/tv3/tenant/invoices/index.js') }}?v={{ filemtime(public_path('js/tv3/tenant/invoices/index.js')) }}"></script>
    <script src="{{ asset('js/tv3/tenant/invoices/sepay_qr.js') }}?v={{ filemtime(public_path('js/tv3/tenant/invoices/sepay_qr.js')) }}"></script>
@endpush


@endsection
