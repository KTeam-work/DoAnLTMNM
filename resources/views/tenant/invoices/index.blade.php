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
        <li class="nav-item"><a class="app-nav-link" href="#rooms">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Yêu thích</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
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
          <div class="stat-value hide-on-skeleton" style="color: var(--red);">3.200.000 đ</div>
        </div>
      </div>
    </div>
    <!-- Card 2 -->
    <div class="col-md-4">
      <div class="stat-card p-3 rounded-4">
        <div class="stat-top mb-3 skeleton-box">
          <div class="stat-icon hide-on-skeleton" style="background: var(--yellow-light); color: #9b6a00;">⏳</div>
          <div class="stat-trend hide-on-skeleton" style="color: #9b6a00;">Hạn: 05/09</div>
        </div>
        <div class="skeleton-box mt-2">
          <div class="stat-label hide-on-skeleton">Chưa thanh toán (Kỳ này)</div>
          <div class="stat-value hide-on-skeleton">3.450.000 đ</div>
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
          <div class="stat-value hide-on-skeleton" style="color: var(--green);">24.150.000 đ</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bảng Dữ Liệu -->
  <div class="animate-up delay-3">
    <!-- Thanh Filter -->
    <div class="enterprise-filter-bar d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3 border-0 bg-white rounded-4 p-3 skeleton-box" style="box-shadow: 0 4px 15px rgba(0,0,0,0.02);">
      <div class="d-flex flex-wrap gap-2 hide-on-skeleton">
        <select class="form-select form-select-sm border-0 bg-light shadow-none fw-bold" style="border-radius: 8px;"><option>Tất cả trạng thái</option></select>
        <select class="form-select form-select-sm border-0 bg-light shadow-none fw-bold" style="border-radius: 8px;"><option>Năm 2026</option></select>
      </div>
      <div class="input-group input-group-sm hide-on-skeleton" style="max-width: 250px;">
        <span class="input-group-text bg-light border-0" style="border-radius: 8px 0 0 8px;">🔍</span>
        <input type="text" class="form-control bg-light border-0 shadow-none ps-0" placeholder="Tìm mã HĐ..." style="border-radius: 0 8px 8px 0;">
      </div>
    </div>

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
          <!-- Dòng 1: Chờ thanh toán -->
          <tr>
            <td style="padding-left: 20px;">
              <div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">#INV-092026</div></div>
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Kỳ: Tháng 09/2026</div></div>
            </td>
            <td>
              <div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">Phòng 12A</div></div>
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Phòng + Dịch vụ</div></div>
            </td>
            <td>
              <div class="skeleton-box mb-1"><div class="hide-on-skeleton" style="font-weight: 600;">05/09/2026</div></div>
              <div class="skeleton-box"><div class="cell-sub hide-on-skeleton" style="color: #9b6a00;">Còn 3 ngày</div></div>
            </td>
            <td><div class="skeleton-box"><strong class="hide-on-skeleton" style="color: var(--green-dark); font-size: 15px;">3.450.000 đ</strong></div></td>
            <td><div class="skeleton-box rounded-pill"><span class="badge-status pending hide-on-skeleton">Chưa thanh toán</span></div></td>
            <td class="text-end" style="padding-right: 20px;">
              <div class="d-flex justify-content-end align-items-center gap-2 skeleton-box rounded">
                <!-- Nút Thanh toán (Mở bảng trượt bên phải) -->
                <button class="btn-brand hide-on-skeleton" style="padding: 6px 16px; font-size: 12px;" data-bs-toggle="offcanvas" data-bs-target="#paymentOffcanvas">Thanh toán</button>
                
                <!-- Dropdown Thao tác -->
                <div class="dropdown action-dropdown hide-on-skeleton">
                  <button class="btn dropdown-toggle border-0 shadow-none text-muted" type="button" data-bs-toggle="dropdown">⋮</button>
                  <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px;">
                    <!-- Nút Xem chi tiết trỏ tới trang show.blade.php bằng đường dẫn an toàn url() -->
                    <li><a class="dropdown-item py-2" href="{{ url('/tenant/invoices/1') }}">👁️ Xem chi tiết</a></li>
                    <li><a class="dropdown-item py-2" href="#">📥 Tải PDF</a></li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>

          <!-- Dòng 2: Quá hạn -->
          <tr>
            <td style="padding-left: 20px;"><div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">#INV-072026</div></div><div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Kỳ: Tháng 07/2026</div></div></td>
            <td><div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">Phòng 12A</div></div><div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Phòng + Dịch vụ</div></div></td>
            <td><div class="skeleton-box mb-1"><div class="hide-on-skeleton" style="font-weight: 600; color: var(--red);">05/07/2026</div></div><div class="skeleton-box"><div class="cell-sub hide-on-skeleton" style="color: var(--red);">Trễ 60 ngày</div></div></td>
            <td><div class="skeleton-box"><strong class="hide-on-skeleton" style="color: var(--green-dark); font-size: 15px;">3.200.000 đ</strong></div></td>
            <td><div class="skeleton-box rounded-pill"><span class="badge-status overdue hide-on-skeleton">Quá hạn</span></div></td>
            <td class="text-end" style="padding-right: 20px;">
              <div class="d-flex justify-content-end align-items-center gap-2 skeleton-box rounded">
                <button class="btn-brand hide-on-skeleton" style="padding: 6px 16px; font-size: 12px; background: var(--red); color: white;" data-bs-toggle="offcanvas" data-bs-target="#paymentOffcanvas">Thanh toán</button>
                <div class="dropdown action-dropdown hide-on-skeleton">
                  <button class="btn dropdown-toggle border-0 shadow-none text-muted" type="button" data-bs-toggle="dropdown">⋮</button>
                  <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px;">
                    <li><a class="dropdown-item py-2" href="{{ url('/tenant/invoices/1') }}">👁️ Xem chi tiết</a></li>
                    <li><a class="dropdown-item py-2" href="#">📥 Tải PDF</a></li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>

          <!-- Dòng 3: Đã thanh toán -->
          <tr>
            <td style="padding-left: 20px;"><div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">#INV-082026</div></div><div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Kỳ: Tháng 08/2026</div></div></td>
            <td><div class="skeleton-box mb-1"><div class="cell-title hide-on-skeleton">Phòng 12A</div></div><div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Phòng + Dịch vụ</div></div></td>
            <td><div class="skeleton-box mb-1"><div class="hide-on-skeleton" style="font-weight: 600;">05/08/2026</div></div><div class="skeleton-box"><div class="cell-sub hide-on-skeleton">Đã CK ngày 02/08</div></div></td>
            <td><div class="skeleton-box"><strong class="hide-on-skeleton" style="color: var(--green-dark); font-size: 15px;">3.450.000 đ</strong></div></td>
            <td><div class="skeleton-box rounded-pill"><span class="badge-status occupied hide-on-skeleton">Đã thanh toán</span></div></td>
            <td class="text-end" style="padding-right: 20px;">
              <div class="d-flex justify-content-end align-items-center gap-2 skeleton-box rounded">
                <span class="hide-on-skeleton" style="font-size: 12px; color: var(--muted); padding-right: 8px; font-weight: 600;">✓ Hoàn tất</span>
                <div class="dropdown action-dropdown hide-on-skeleton">
                  <button class="btn dropdown-toggle border-0 shadow-none text-muted" type="button" data-bs-toggle="dropdown">⋮</button>
                  <ul class="dropdown-menu dropdown-menu-end shadow border-0" style="border-radius: 12px;">
                    <li><a class="dropdown-item py-2" href="{{ url('/tenant/invoices/1') }}">👁️ Xem chi tiết</a></li>
                    <li><a class="dropdown-item py-2" href="#">📥 Tải Biên lai PDF</a></li>
                  </ul>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- OFFCANVAS: BẢNG THANH TOÁN QR -->
<!-- ========================================== -->
<div class="offcanvas offcanvas-end offcanvas-glass" tabindex="-1" id="paymentOffcanvas" style="width: 440px;">
  <div class="offcanvas-header border-bottom border-light">
    <h5 class="offcanvas-title fw-bold" style="color: var(--green-dark);">Thanh toán Hóa đơn</h5>
    <button type="button" class="btn-close shadow-none bg-white rounded-circle p-2" data-bs-dismiss="offcanvas"></button>
  </div>
  
  <div class="offcanvas-body">
    <div class="receipt-ticket">
      <div class="text-center mb-4">
        <div style="font-size: 11px; color: var(--muted); text-transform: uppercase; font-weight: 700; letter-spacing: 1px;">Kỳ thanh toán</div>
        <h4 class="fw-bold mt-1 mb-2" style="color: var(--green-dark);">Tháng 09/2026</h4>
        <span class="badge-status pending">#INV-092026</span>
      </div>
      <div class="receipt-item"><span>Phòng thuê:</span><span class="fw-bold">Phòng 12A</span></div>
      <div class="receipt-item"><span>Hạn thanh toán:</span><span class="fw-bold text-danger">05/09/2026</span></div>
      <div class="receipt-divider"></div>
      <div class="receipt-item total align-items-center mt-3">
        <span style="color: var(--green-dark); font-size: 14px; font-weight: 800;">TỔNG CỘNG:</span>
        <span style="font-size: 22px; color: var(--green-dark); font-weight: 800;">3.450.000 đ</span>
      </div>
    </div>

    <!-- QR Code & Copy -->
    <div class="mt-4">
      <div class="bg-white p-4 rounded-4 text-center" style="box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0;">
        <h6 class="fw-bold mb-3" style="color: var(--muted); font-size: 12px;">QUÉT MÃ ĐỂ THANH TOÁN</h6>
        <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="QR" style="width: 130px; height: 130px; margin-bottom: 16px; opacity: 0.9;">
        <div class="fw-bold mb-1" style="color: var(--green-dark); font-size: 14px;">Ngân hàng Vietcombank</div>
        <div class="text-muted mb-3" style="font-size: 12px;">Chủ TK: NGUYEN VAN TUAN<br>STK: 0123456789</div>
        <button onclick="showCopyToast()" class="btn btn-light btn-sm w-100 shadow-none border fw-bold text-muted py-2" style="border-radius: 10px;">📋 Sao chép STK & Số tiền</button>
      </div>
    </div>
    
    <div class="mt-4 text-center">
      <button class="btn-brand w-100 py-3 mb-3" style="font-size: 14px; border-radius: 12px;">Xác nhận đã chuyển khoản</button>
    </div>
  </div>
</div>

<!-- ========================================== -->
<!-- TOAST NOTIFICATION CONTAINER -->
<!-- ========================================== -->
<div class="toast-container-custom">
  <div id="copyToast" class="toast-custom">
    <span style="color: var(--yellow);">✔</span> Đã sao chép thông tin thanh toán!
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // 1. Tắt Skeleton sau khi trang tải xong (Ví dụ: sau 0.8s)
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      document.body.classList.remove('skeleton-mode');
    }, 800); 
  });

  // 2. Hiển thị Toast khi copy QR
  function showCopyToast() {
    const toast = document.getElementById('copyToast');
    toast.classList.add('show');
    setTimeout(() => { toast.classList.remove('show'); }, 3000);
  }
</script>
</body>
</html>