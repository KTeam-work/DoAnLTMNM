
@extends('Layout.landlord')

@section('title', 'Quản lý hoa đơn | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/invoice/index.css') }}">
@endpush

@section('content')

<!-- CONTENT -->
<div class="page-wrap">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
      <h2 class="page-title">Quản lý Hóa đơn</h2>
      <div class="page-desc">Kỳ thanh toán hiện tại: <strong>Tháng {{ \Carbon\Carbon::parse($currentMonth)->format('m/Y') }}</strong></div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn-brand" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
        <i class="bi bi-magic me-1"></i> Tạo hóa đơn tháng
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon"><i class="bi bi-receipt"></i></div>
          <span class="text-muted small fw-bold">Dự kiến {{ \Carbon\Carbon::parse($currentMonth)->format('m/Y') }}</span>
        </div>
        <div class="stat-value">{{ number_format((float) $stats['expected']) }} ₫</div>
        <div class="stat-label">Tổng doanh thu chờ thu</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
          <span class="fw-bold text-success">Đã thu</span>
        </div>
        <div class="stat-value" style="color: var(--green);">{{ number_format((float) $stats['collected']) }} ₫</div>
        <div class="stat-label">Đã thu trong kỳ</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card" style="border-color: var(--red-soft);">
        <div class="stat-top">
          <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <span class="text-danger fw-bold small">{{ $stats['outstanding_count'] }} hóa đơn cần xử lý</span>
        </div>
        <div class="stat-value" style="color: var(--red);">{{ number_format((float) $stats['outstanding']) }} ₫</div>
        <div class="stat-label">Còn nợ &amp; Quá hạn</div>
      </div>
    </div>
  </div>

  <!-- BẢNG HÓA ĐƠN -->
  <div class="panel">
    <div class="panel-head">
      <h3 class="fw-bold fs-5 mb-0">Danh sách Hóa đơn</h3>
      <form method="GET" action="{{ route('owner.invoices.index') }}" class="d-flex flex-wrap gap-2">
        <input type="month" name="month" class="form-control form-control-sm w-auto shadow-none" value="{{ $filters['month'] ?? '' }}" onchange="this.form.submit()">
        <select name="status" class="form-select form-select-sm w-auto shadow-none" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          @foreach(['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'] as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @if(!empty($filters['month']) || !empty($filters['status']))
          <a href="{{ route('owner.invoices.index') }}" class="btn btn-sm btn-light border">Xóa lọc</a>
        @endif
      </form>
    </div>

    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>MÃ HĐ / NGÀY LẬP</th>
            <th>PHÒNG &amp; KHÁCH</th>
            <th>TỔNG TIỀN</th>
            <th>TRẠNG THÁI</th>
            <th class="text-end">THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          @php
            $statusLabels = ['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'];
          @endphp
          @forelse($invoices as $invoice)
            @php
              $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
              $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
            @endphp
          <tr>
            <td>
              <div class="cell-title" style="color: var(--green);">#{{ $invoice->invoice_code }}</div>
              <div class="cell-sub">Hạn: {{ $invoice->due_date->format('d/m/Y') }}</div>
            </td>
            <td>
              <div class="cell-title">{{ $invoice->contract->room->name ?? '' }}</div>
              <div class="cell-sub">{{ $invoice->contract->tenant->name ?? '' }}</div>
            </td>
            <td><strong>{{ number_format((float) $invoice->total) }} ₫</strong></td>
            <td><span class="badge-status {{ $displayStatus }}">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</span></td>
            <td class="text-end">
              <a href="{{ route('owner.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i> Xem</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-5">Chưa có hóa đơn nào.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-3">
      <span class="text-muted small">Tổng {{ $invoices->total() }} hóa đơn</span>
      <div>{{ $invoices->links() }}</div>
    </div>
  </div>
</div>

<!-- MODAL: TẠO HÓA ĐƠN THÁNG -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('owner.invoices.store') }}" class="modal-content">
      @csrf
      <div class="modal-header bg-light">
        <div>
          <h5 class="modal-title fw-bold" style="color: var(--green);"><i class="bi bi-magic me-2"></i>Tạo hóa đơn tháng</h5>
          <div class="text-muted" style="font-size: 13px;">Tự động tính tiền phòng, điện nước và dịch vụ cho các hợp đồng hiệu lực.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label small fw-bold">Kỳ thanh toán</label>
          <input type="month" name="month" class="form-control" value="{{ $currentMonth }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-bold">Giảm giá chung (đ, tùy chọn)</label>
          <input type="number" name="discount" class="form-control" min="0" step="0.01" value="0">
        </div>
        <div class="alert alert-info mb-0" style="font-size: 13px;">Chọn kỳ đã có chỉ số điện nước (VD: 2026-10). Phòng thiếu chỉ số của kỳ sẽ bị bỏ qua và liệt kê sau khi tạo. Không tạo trùng kỳ/hợp đồng.</div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-brand fw-bold px-4"><i class="bi bi-send-check me-1"></i> Phát hành Hóa đơn</button>
      </div>
    </form>
  </div>
</div>

@endsection