
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
      <button type="button" class="btn btn-outline-success fw-bold" style="border-radius: 8px; padding: 8px 14px; font-size: 13px;" data-bs-toggle="modal" data-bs-target="#processDueModal" title="Kích hoạt kiểm tra hạn hóa đơn tự động">
        Quét nợ &amp; Cập nhật quá hạn
      </button>
      <button class="btn-brand" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
        Tạo hóa đơn tháng
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert" style="border-radius: 12px; font-size: 14px;">
      <i class="bi bi-check-circle-fill fs-5 text-success"></i>
      <div>{{ session('success') }}</div>
      <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="border-radius: 12px; font-size: 14px;">
      <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
      <button type="button" class="btn-close ms-auto shadow-none" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4" id="invoiceStatCards">
    <div class="col-12 col-md-4">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Doanh thu</span>
          <span class="stat-trend" style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Kỳ {{ \Carbon\Carbon::parse($currentMonth)->format('m/Y') }}</span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 5px 0;">{{ number_format((float) $stats['expected'], 0, ',', '.') }}đ</div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Tổng doanh thu chờ thu</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Thực thu</span>
          <span class="stat-trend" style="background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Đã thu</span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #16834b; line-height: 1.2; margin: 12px 0 5px 0;">{{ number_format((float) $stats['collected'], 0, ',', '.') }}đ</div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Đã thu trong kỳ</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); border-left: 3px solid #dc3545;">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Chưa thu</span>
          <span class="stat-trend down" style="background: #fef2f2; color: #dc2626; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">{{ $stats['outstanding_count'] }} cần xử lý</span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #dc3545; line-height: 1.2; margin: 12px 0 5px 0;">{{ number_format((float) $stats['outstanding'], 0, ',', '.') }}đ</div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Còn nợ &amp; Quá hạn</div>
      </div>
    </div>
  </div>

  <!-- BẢNG HÓA ĐƠN -->
  <div class="panel">
    <div class="panel-head">
      <h3 class="fw-bold fs-5 mb-0">Danh sách Hóa đơn</h3>
      <form id="invoiceFilterForm" method="GET" action="{{ route('owner.invoices.index') }}" class="d-flex flex-wrap gap-2">
        <input type="month" name="month" class="form-control form-control-sm w-auto shadow-none" value="{{ $filters['month'] ?? '' }}">
        <select name="status" class="form-select form-select-sm w-auto shadow-none">
          <option value="">Tất cả trạng thái</option>
          @foreach(['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'] as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        <div id="invoiceResetWrapper" class="d-inline-flex">
          @if(!empty($filters['month']) || !empty($filters['status']))
            <a href="{{ route('owner.invoices.index') }}" class="btn btn-sm btn-light border" id="resetInvoiceFilterBtn">Xóa lọc</a>
          @endif
        </div>
      </form>
    </div>

    <div class="table-responsive" id="invoiceTableWrapper">
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
              <a href="{{ route('owner.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary">Xem</a>
              <a href="{{ route('owner.invoices.print', $invoice->id) }}" target="_blank" class="btn btn-sm btn-outline-success ms-1" title="In / Xuất PDF">In</a>
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
          <h5 class="modal-title fw-bold" style="color: var(--green);">Tạo hóa đơn tháng</h5>
          <div class="text-muted" style="font-size: 13px;">Tự động tính tiền phòng, điện nước và dịch vụ cho các hợp đồng hiệu lực.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label for="invoice-contract" class="form-label small fw-bold">Người thuê / phòng</label>
          <select id="invoice-contract" name="contract_id" class="form-select">
            <option value="">Tất cả người thuê đang có hợp đồng hiệu lực</option>
            @foreach($activeContracts as $contract)
              <option value="{{ $contract->id }}">
                {{ $contract->room->name ?? 'Phòng chưa xác định' }} — {{ $contract->tenant->name ?? 'Người thuê chưa xác định' }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Chọn một người thuê để lập hóa đơn riêng, hoặc giữ “Tất cả” để phát hành hàng loạt.</div>
        </div>
        <div class="mb-3">
          <label for="invoice-month" class="form-label small fw-bold">Tháng tính tiền</label>
          <input id="invoice-month" type="month" name="month" class="form-control" value="{{ $currentMonth }}" lang="vi" title="Chọn tháng và năm tính tiền" required>
          <div class="form-text">Chọn tháng và năm cần tính hóa đơn.</div>
        </div>
        <div class="mb-3">
          <label for="invoice-due-date" class="form-label small fw-bold">Hạn chót thanh toán (tùy chọn)</label>
          <input id="invoice-due-date" type="date" name="due_date" class="form-control" value="{{ now()->addDays(7)->toDateString() }}">
        </div>
        <div class="mb-3">
          <label class="form-label small fw-bold">Giảm giá chung (đ, tùy chọn)</label>
          <input type="number" name="discount" class="form-control" min="0" step="0.01" value="0">
        </div>
        <div class="alert alert-info mb-0" style="font-size: 13px;">Chọn kỳ đã có chỉ số điện nước (VD: 2026-10). Phòng thiếu chỉ số của kỳ sẽ bị bỏ qua và liệt kê sau khi tạo. Không tạo trùng kỳ/hợp đồng.</div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-brand fw-bold px-4">Phát hành Hóa đơn</button>
      </div>
    </form>
  </div>
</div>

<!-- MODAL QUÉT NỢ & CẬP NHẬT QUÁ HẠN -->
<div class="modal fade" id="processDueModal" tabindex="-1" aria-labelledby="processDueModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 18px; overflow: hidden;">
      <div class="modal-header border-0 pb-0 pt-4 px-4">
        <h5 class="modal-title fw-bold text-dark" id="processDueModalLabel">
          Quét nợ & Cập nhật quá hạn
        </h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>
      <div class="modal-body px-4 py-3">
        <p class="text-muted mb-3" style="font-size: 14px;">
          Hệ thống sẽ tiến hành rà soát tự động tất cả các hóa đơn trong hệ thống:
        </p>

        <div class="p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
          <div class="d-flex align-items-start gap-2 mb-2">
            <span class="badge bg-primary-subtle text-primary rounded-pill px-2 py-1" style="font-size: 11px;">1. Quét hạn chót</span>
            <span style="font-size: 13px; color: #334155;">Kiểm tra ngày đến hạn thanh toán của từng hóa đơn đang chờ thu.</span>
          </div>
          <div class="d-flex align-items-start gap-2 mb-2">
            <span class="badge bg-warning-subtle text-warning-emphasis rounded-pill px-2 py-1" style="font-size: 11px;">2. Gửi nhắc nợ</span>
            <span style="font-size: 13px; color: #334155;">Tự động gửi thông báo đến người thuê cho các hóa đơn sắp đến hạn hoặc đến hạn hôm nay.</span>
          </div>
          <div class="d-flex align-items-start gap-2">
            <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1" style="font-size: 11px;">3. Quá hạn</span>
            <span style="font-size: 13px; color: #334155;">Chuyển trạng thái sang <strong>Quá hạn</strong> nếu đã vượt quá ngày thanh toán mà chưa trả.</span>
          </div>
        </div>

        <div class="text-muted" style="font-size: 12.5px;">
          Bạn có chắc chắn muốn tiến hành quét và cập nhật trạng thái ngay bây giờ?
        </div>
      </div>
      <div class="modal-footer border-0 bg-light px-4 py-3">
        <button type="button" class="btn btn-light border fw-semibold px-4" data-bs-dismiss="modal">Hủy bỏ</button>
        <form action="{{ route('owner.invoices.process-due-now') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="btn btn-success fw-bold px-4">
            Bắt đầu quét &amp; Cập nhật
          </button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
  if (typeof window.initInvoiceAjaxFilters === 'function') {
    window.initInvoiceAjaxFilters();
  }
</script>

@push('scripts')
<script>
function initInvoiceAjaxFilters() {
  const form = document.getElementById('invoiceFilterForm');
  if (!form || form.dataset.ajaxInitialized === 'true') return;
  form.dataset.ajaxInitialized = 'true';

  let isFetching = false;

  async function applyInvoiceFilterAjax(url) {
    if (isFetching) return;
    isFetching = true;

    const tableWrapper = document.getElementById('invoiceTableWrapper');
    const statWrapper = document.getElementById('invoiceStatCards');

    try {
      const res = await fetch(url, {
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'text/html'
        }
      });

      if (!res.ok) {
        window.location.href = url;
        return;
      }

      const html = await res.text();
      const doc = new DOMParser().parseFromString(html, 'text/html');

      // 1. Cập nhật Stat Cards
      const newStats = doc.getElementById('invoiceStatCards');
      if (newStats && statWrapper) {
        statWrapper.innerHTML = newStats.innerHTML;
      }

      // 2. Cập nhật Table
      const newTable = doc.getElementById('invoiceTableWrapper');
      if (newTable && tableWrapper) {
        tableWrapper.innerHTML = newTable.innerHTML;
      }

      // 3. Cập nhật nút Xóa lọc
      const resetWrapper = document.getElementById('invoiceResetWrapper');
      const newReset = doc.getElementById('invoiceResetWrapper');
      if (newReset && resetWrapper) {
        resetWrapper.innerHTML = newReset.innerHTML;
      }

      // 4. Cập nhật URL trình duyệt
      window.history.pushState({}, '', url);

    } catch (e) {
      console.error('Invoice AJAX filter error:', e);
      window.location.href = url;
    } finally {
      isFetching = false;
    }
  }

  function onInvoiceFilterChange() {
    const formData = new FormData(form);
    const params = new URLSearchParams();
    for (const [key, value] of formData.entries()) {
      if (value) params.append(key, value);
    }
    const targetUrl = form.action + (params.toString() ? '?' + params.toString() : '');
    applyInvoiceFilterAjax(targetUrl);
  }

  form.querySelectorAll('input, select').forEach(el => {
    el.onchange = onInvoiceFilterChange;
  });

  form.onsubmit = function(e) {
    e.preventDefault();
    onInvoiceFilterChange();
  };

  document.addEventListener('click', function(e) {
    const resetLink = e.target.closest('#resetInvoiceFilterBtn');
    if (resetLink) {
      e.preventDefault();
      form.querySelectorAll('input, select').forEach(el => el.value = '');
      applyInvoiceFilterAjax(resetLink.href);
    }
  });
}

window.initInvoiceAjaxFilters = initInvoiceAjaxFilters;

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initInvoiceAjaxFilters);
} else {
  initInvoiceAjaxFilters();
}
</script>
@endpush

@endsection
