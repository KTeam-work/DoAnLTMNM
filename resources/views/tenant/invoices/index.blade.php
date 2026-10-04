<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Quản lý Hóa đơn</title>

<!-- Import Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<style>
  /* --- 1. SKELETON LOADING --- */
  .skeleton-mode .hide-on-skeleton { opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box {
    background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 8px;
    color: transparent !important;
    border-color: transparent !important;
    pointer-events: none;
    user-select: none;
  }
  .skeleton-mode .skeleton-box * { visibility: hidden; }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }

  /* --- 2. ANIMATIONS & MICRO-INTERACTIONS --- */
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .animate-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
  .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }

  /* Nổi thẻ 3D cao cấp */
  .stat-card { transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1); position: relative; overflow: hidden; background: #fff; border: 1px solid #f0f0f0; }
  .stat-card:hover { transform: translateY(-6px); box-shadow: 0 20px 40px rgba(32, 88, 79, 0.08); border-color: var(--green-soft); }
  
  /* Bảng dữ liệu: Hover nhẹ nhàng, sang trọng */
  .data-table { border-collapse: separate; border-spacing: 0 8px; }
  .data-table thead th { border-bottom: none; padding-bottom: 5px; font-size: 11px; letter-spacing: 0.5px; }
  .data-table tbody tr { transition: all 0.3s ease; background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,0.02); }
  .data-table tbody tr td { border-bottom: none; border-top: 1px solid transparent; border-bottom: 1px solid transparent; padding: 18px 12px; }
  .data-table tbody tr td:first-child { border-radius: 12px 0 0 12px; border-left: 1px solid transparent; }
  .data-table tbody tr td:last-child { border-radius: 0 12px 12px 0; border-right: 1px solid transparent; }
  .data-table tbody tr:hover { transform: scale(1.005); box-shadow: 0 10px 30px rgba(32, 88, 79, 0.08); z-index: 10; position: relative; }
  
  /* Cảnh báo đỏ nhấp nháy cho Overdue */
  @keyframes pulseRed { 0% { box-shadow: 0 0 0 0 rgba(198,91,74,0.4); } 70% { box-shadow: 0 0 0 6px rgba(198,91,74,0); } 100% { box-shadow: 0 0 0 0 rgba(198,91,74,0); } }
  .badge-status.overdue { animation: pulseRed 2s infinite; }

  /* Nút bấm Ripple/Scale */
  .btn-brand, .btn-outline-brand { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); border-radius: 10px; font-weight: 700; }
  .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(245,200,75,0.3); }
  .btn-brand:active { transform: scale(0.95); }

  /* --- 3. GLASSMORPHISM OFFCANVAS (Bảng trượt kính mờ) --- */
  .offcanvas-glass {
    background: rgba(255, 255, 255, 0.9) !important;
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border-left: 1px solid rgba(255,255,255,0.4);
    box-shadow: -10px 0 40px rgba(0,0,0,0.08);
  }
  .receipt-ticket {
    background: #fff; border-radius: 20px; padding: 24px; position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: 1px solid rgba(0,0,0,0.05);
  }
  .receipt-ticket::before, .receipt-ticket::after {
    content: ''; position: absolute; top: 130px; width: 24px; height: 24px; background: #f7f9f8; border-radius: 50%;
  }
  .receipt-ticket::before { left: -12px; box-shadow: inset -1px 0 0 rgba(0,0,0,0.05); }
  .receipt-ticket::after { right: -12px; box-shadow: inset 1px 0 0 rgba(0,0,0,0.05); }
  .receipt-divider { border-top: 2px dashed #e0e0e0; margin: 20px 0; }
  .receipt-item { display: flex; justify-content: space-between; margin-bottom: 12px; font-size: 13px; }
  .receipt-item span:first-child { color: var(--muted); font-weight: 500; }
  
  /* --- 4. TOAST NOTIFICATION --- */
  .toast-container-custom { position: fixed; bottom: 24px; left: 50%; transform: translateX(-50%); z-index: 9999; }
  .toast-custom { background: #213430; color: #fff; padding: 12px 24px; border-radius: 99px; font-size: 13px; font-weight: 600; box-shadow: 0 10px 25px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 8px; opacity: 0; transform: translateY(20px); transition: all 0.3s ease; pointer-events: none; }
  .toast-custom.show { opacity: 1; transform: translateY(0); }
</style>
</head>

<!-- Thêm class skeleton-mode vào body lúc khởi tạo -->
<body class="skeleton-mode">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ url('/') }}">Trọ <span>Ơi</span></a>
    <div class="collapse navbar-collapse" id="mainMenu">
    <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.home') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('rooms.index') }}">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('favorites.index') }}">Yêu thích</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('appointments.index') }}">Lịch xem phòng</a></li>
        <li class="nav-item">
          <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a></li>
        </li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
      </ul>
     <div class="navbar-actions skeleton-box">
        <div class="hide-on-skeleton d-flex align-items-center gap-3">
          <!-- Thêm text-decoration-none vào class -->
          <a href="{{ route('tenant.notifications.index') }}" class="notif-btn text-decoration-none">
            🔔<span class="notif-dot"></span>
          </a>
          
          <!-- Thêm text-decoration-none vào class -->
          <a href="#" class="user-chip text-decoration-none">
            <span class="user-avatar">TH</span>
            <span class="user-meta">
              <span class="user-name d-block" style="text-decoration: none;">Thanh Huyền</span>
              <span class="user-role">Người thuê</span>
            </span>
            <span class="caret">▾</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT -->
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Tắt Skeleton sau khi trang tải xong
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      document.body.classList.remove('skeleton-mode');
    }, 800);
  });
</script>
</body>
</html>