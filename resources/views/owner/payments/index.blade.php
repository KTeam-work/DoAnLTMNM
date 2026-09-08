<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Lịch sử Giao dịch</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<!-- CSS GỐC CỦA DỰ ÁN -->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<style>
  :root{
    --forest: #20584f;
    --forest-deep: #163e37;
    --forest-tint: #eaf3ef;
    --amber: #b5792a;
    --amber-tint: #fbf1e3;
    --bank: #2f5fa8;
    --bank-tint: #eaf1fb;
    --cream: #faf8f3;
    --line: #e7e2d6;
    --ink: #23281f;
    --ink-muted: #6c7266;
    --red: #c65b4a;
    --red-tint: #fdf0ed;
  }

  body{ background: var(--cream); font-family: 'Be Vietnam Pro', sans-serif; color: var(--ink); }
  .mono-num{ font-family: 'JetBrains Mono', monospace; font-feature-settings: "tnum" 1; font-variant-numeric: tabular-nums; }

  /* ---- Skeleton loading ---- */
  .skeleton-mode .hide-on-skeleton{ opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box{ background: linear-gradient(90deg, #e9e5d8 25%, #f3f1e9 50%, #e9e5d8 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 10px; color: transparent !important; border-color: transparent !important; pointer-events: none; }
  @keyframes shimmer{ 0%{ background-position: 200% 0; } 100%{ background-position: -200% 0; } }

  /* ---- Navbar override ---- */
  .app-navbar{
    background: var(--forest);
    min-height: 74px;
    box-shadow: 0 3px 14px rgba(20,55,47,.12);
    position: sticky;
    top: 0;
    z-index: 1000;
  }
  .app-navbar .container-fluid{
    max-width: 1360px;
    padding: 0 28px;
  }
  .logo{
    color: #fff;
    text-decoration: none;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -1.2px;
    white-space: nowrap;
  }
  .logo span{ color: #f5c84b; }
  .app-nav-link{
    color: rgba(255,255,255,.82) !important;
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 13px !important;
    border-radius: 10px;
    transition: .2s;
    white-space: nowrap;
  }
  .app-nav-link:hover, .app-nav-link.active{
    color: var(--forest) !important;
    background: #fff;
  }
  .navbar-actions{
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .notif-btn{
    position: relative;
    width: 40px;
    height: 40px;
    border-radius: 11px;
    border: 1px solid rgba(255,255,255,.18);
    background: rgba(255,255,255,.08);
    color: #fff;
    font-size: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
  }
  .notif-dot{
    position: absolute;
    top: 7px;
    right: 7px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #f5c84b;
    border: 2px solid var(--forest);
  }
  .user-chip{
    display: flex;
    align-items: center;
    gap: 9px;
    padding: 6px 12px 6px 6px;
    border-radius: 12px;
    background: rgba(255,255,255,.08);
    border: 1px solid rgba(255,255,255,.16);
    color: #fff;
    text-decoration: none;
    margin-left: 6px;
  }
  .user-avatar{
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: #f5c84b;
    color: var(--forest-deep);
    font-weight: 800;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
  }
  .user-meta{ line-height: 1.2; }
  .user-name{ font-size: 12.5px; font-weight: 700; color: #fff; }
  .user-role{ font-size: 10px; color: rgba(255,255,255,.65); }
  .caret{ font-size: 9px; color: rgba(255,255,255,.6); margin-left: 2px; }

  @media(max-width: 991px){ .app-nav-link{ margin: 2px 0; } }

  /* ---- Page header ---- */
  .page-wrap{ max-width: 1180px; margin: 0 auto; padding: 1.5rem 1.5rem 3rem; }
  .page-title{ font-size: 1.5rem; font-weight: 700; letter-spacing: -0.01em; margin: 0; color: var(--forest-deep); }
  .page-desc{ color: var(--ink-muted); font-size: 0.92rem; }

  .btn-brand{
    background: var(--forest); color: #fff; border: none; border-radius: 10px;
    padding: 0.55rem 1.1rem; font-weight: 600; font-size: 0.88rem;
    transition: background 0.15s ease;
  }
  .btn-brand:hover{ background: var(--forest-deep); color: #fff; }
  .btn-outline-ledger{
    background: #fff; color: var(--ink); border: 1px solid var(--line); border-radius: 10px;
    padding: 0.55rem 1.1rem; font-weight: 600; font-size: 0.88rem;
    transition: border-color 0.15s ease, color 0.15s ease;
  }
  .btn-outline-ledger:hover{ border-color: var(--forest); color: var(--forest); }

  /* ---- Stat cards ---- */
  .stat-primary{
    background: linear-gradient(135deg, var(--forest) 0%, var(--forest-deep) 100%);
    border-radius: 16px; padding: 1.5rem 1.75rem; color: #fff; height: 100%;
    display: flex; flex-direction: column; justify-content: space-between;
  }
  .stat-primary .stat-period{ font-size: 0.8rem; color: #cfe3dd; font-weight: 500; }
  .stat-primary .stat-value{ 
    font-size: 2.1rem; 
    font-weight: 700; 
    letter-spacing: -0.02em; 
    margin-top: 0.35rem; 
    color: #a8e6cf;  /* Màu xanh mint nhạt */
    }
  .stat-primary .stat-label{ font-size: 0.85rem; color: #cfe3dd; margin-top: 0.15rem; }

  .stat-secondary{
    background: #fff; border: 1px solid var(--line); border-radius: 14px;
    padding: 1.1rem 1.25rem; height: 100%; display: flex; align-items: center; gap: 0.9rem;
  }
  .stat-secondary .icon-chip{
    width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.05rem; flex-shrink: 0;
  }
  .stat-secondary .stat-value{ font-size: 1.15rem; font-weight: 700; }
  .stat-secondary .stat-label{ font-size: 0.78rem; color: var(--ink-muted); }
  .stat-secondary .stat-share{ margin-left: auto; font-size: 0.78rem; font-weight: 600; color: var(--ink-muted); }

  /* ---- Panel / table ---- */
  .panel{ background: #fff; border: 1px solid var(--line); border-radius: 16px; padding: 1.5rem; }
  .panel-title{ font-size: 1.05rem; font-weight: 700; }

  .form-control, .form-select{ border-color: var(--line); font-size: 0.85rem; }
  .form-control:focus, .form-select:focus{ border-color: var(--forest); box-shadow: 0 0 0 3px rgba(32,88,79,0.12); }

  .data-table{ width: 100%; border-collapse: separate; border-spacing: 0; }
  .data-table thead th{
    text-align: left; font-size: 0.76rem; font-weight: 600; color: var(--ink-muted);
    padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--line);
  }
  .data-table tbody td{ padding: 0.9rem 0.75rem; border-bottom: 1px solid var(--line); vertical-align: middle; }
  .data-table tbody tr:last-child td{ border-bottom: none; }
  .data-table tbody tr{ transition: background 0.12s ease; }
  .data-table tbody tr:hover{ background: #faf9f5; }

  .txn-id{ font-weight: 700; color: var(--forest); font-size: 0.92rem; }
  .txn-time{ color: var(--ink-muted); font-size: 12px; }
  .room-name{ font-weight: 600; }
  .tenant-name{ color: var(--ink-muted); font-size: 12px; }
  .invoice-ref{ text-decoration: none; font-weight: 600; font-size: 0.83rem; color: var(--bank); }
  .invoice-ref:hover{ text-decoration: underline; }
  .deposit-ref{ color: var(--ink-muted); font-size: 0.83rem; font-style: italic; }
  .amount-in{ font-weight: 700; font-size: 0.98rem; color: var(--forest); }
  .amount-out{ font-weight: 700; font-size: 0.98rem; color: var(--red); }

  .badge-method{ display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px; border-radius: 99px; font-size: 12px; font-weight: 600; }
  .badge-method.transfer{ background: var(--bank-tint); color: var(--bank); }
  .badge-method.cash{ background: var(--forest-tint); color: var(--forest); }
  .badge-method.momo{ background: #fce8e8; color: #d82c2c; }
  .badge-method.other{ background: #f0f0f0; color: #666; }

  .badge-status{
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 99px; font-size: 11px; font-weight: 700;
  }
  .badge-status.success{ background: var(--forest-tint); color: var(--forest); }
  .badge-status.pending{ background: var(--amber-tint); color: var(--amber); }
  .badge-status.failed{ background: var(--red-tint); color: var(--red); }
  .badge-status.refund{ background: #f0f0f0; color: #666; }

  .btn-icon{
    width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center;
    border-radius: 9px; transition: 0.15s; border: 1px solid var(--line); background: #fff; color: var(--ink-muted);
  }
  .btn-icon:hover{ background: var(--forest-tint); color: var(--forest); border-color: var(--forest); }
  .btn-icon.success:hover{ background: var(--forest-tint); color: var(--forest); border-color: var(--forest); }
  .btn-icon.danger:hover{ background: var(--red-tint); color: var(--red); border-color: var(--red); }

  .panel-footer{ display: flex; align-items: center; justify-content: space-between; padding-top: 1rem; margin-top: 0.25rem; border-top: 1px solid var(--line); font-size: 0.83rem; color: var(--ink-muted); flex-wrap: wrap; gap: 0.5rem; }
  .page-btn{
    width: 32px; height: 32px; border-radius: 8px; border: 1px solid var(--line); background: #fff;
    display: inline-flex; align-items: center; justify-content: center; font-size: 0.82rem; color: var(--ink);
    transition: 0.15s;
  }
  .page-btn:hover{ border-color: var(--forest); color: var(--forest); }
  .page-btn.active{ background: var(--forest); border-color: var(--forest); color: #fff; }
  .page-btn:disabled{ opacity: 0.4; cursor: not-allowed; }

  /* ---- Modal ---- */
  .modal-content{ border-radius: 16px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
  .modal-header{ border-bottom: 1px solid var(--line); padding: 1.25rem 1.5rem; }
  .modal-footer{ border-top: 1px solid var(--line); padding: 1rem 1.5rem; }

  /* ---- Animation ---- */
  @keyframes fadeInUp{ from{ opacity: 0; transform: translateY(15px); } to{ opacity: 1; transform: translateY(0); } }
  .animate-up{ animation: fadeInUp 0.4s ease forwards; opacity: 0; }
  .delay-1{ animation-delay: 0.1s; }
  .delay-2{ animation-delay: 0.15s; }
  .delay-3{ animation-delay: 0.2s; }
</style>
</head>
<body class="skeleton-mode">

<!-- NAVBAR NGANG -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ url('/') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="#">Tổng quan</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Nhà &amp; Phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Tin đăng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Người thuê</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('owner.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link  active" href="{{ route('owner.payments.index') }}">Giao dịch</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ url('/owner/maintenance') }}">Sửa chữa</a></li>
      </ul>

      <div class="navbar-actions">
        <a href="#" class="notif-btn"><i class="bi bi-bell-fill"></i><span class="notif-dot"></span></a>
        <a href="#" class="user-chip">
          <span class="user-avatar">MT</span>
          <span class="user-meta">
            <span class="user-name d-block">{{ Auth::user()->name ?? 'Minh Tuấn' }}</span>
            <span class="user-role">Chủ trọ</span>
          </span>
          <span class="caret">▾</span>
        </a>
      </div>
    </div>
  </div>
</nav>

<div class="page-wrap pt-4 pb-5">
  <!-- Page Header -->
  <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Lịch sử giao dịch</span></h2>
      <div class="page-desc mt-1 skeleton-box"><span class="hide-on-skeleton">Theo dõi dòng tiền thực tế đã thu trong tháng.</span></div>
    </div>
    <div class="d-flex gap-2 hide-on-skeleton">
      <button class="btn-outline-ledger" id="btnExport"><i class="bi bi-file-earmark-excel me-1"></i>Xuất sổ phụ</button>
      <button class="btn-brand" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
        <i class="bi bi-plus-lg me-1"></i> Ghi nhận thu
      </button>
    </div>
  </div>

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4 animate-up delay-2">
    <div class="col-12 col-md-5">
      <div class="stat-primary skeleton-box">
        <div class="hide-on-skeleton">
          <div class="stat-period"><i class="bi bi-calendar3 me-1"></i>Tháng 09/2026</div>
          <div class="stat-value mono-num">29.575.000 ₫</div>
          <div class="stat-label">Tổng thực thu kỳ này</div>
        </div>
      </div>
    </div>

    <div class="col-12 col-md-7">
      <div class="row g-3 h-100">
        <div class="col-12">
          <div class="stat-secondary skeleton-box">
            <div class="hide-on-skeleton d-flex align-items-center gap-3 w-100">
              <div class="icon-chip" style="background: var(--bank-tint); color: var(--bank);"><i class="bi bi-bank"></i></div>
              <div>
                <div class="stat-value mono-num">24.250.000 ₫</div>
                <div class="stat-label">Thu qua chuyển khoản</div>
              </div>
              <div class="stat-share">82%</div>
            </div>
          </div>
        </div>
        <div class="col-12">
          <div class="stat-secondary skeleton-box">
            <div class="hide-on-skeleton d-flex align-items-center gap-3 w-100">
              <div class="icon-chip" style="background: var(--forest-tint); color: var(--forest);"><i class="bi bi-cash-stack"></i></div>
              <div>
                <div class="stat-value mono-num">5.325.000 ₫</div>
                <div class="stat-label">Thu qua tiền mặt</div>
              </div>
              <div class="stat-share">18%</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- BẢNG GIAO DỊCH -->
  <div class="panel animate-up delay-3 skeleton-box">
    <div class="panel-head d-flex flex-wrap gap-3 justify-content-between align-items-center border-bottom pb-3 hide-on-skeleton">
      <h3 class="panel-title mb-0">Danh sách giao dịch</h3>

      <div class="d-flex flex-wrap gap-2">
        <div class="input-group input-group-sm" style="width: 240px;">
          <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
          <input type="text" id="searchInput" class="form-control border-start-0 ps-0 shadow-none" placeholder="Tìm theo mã GD, tên khách, phòng...">
        </div>
        <select class="form-select form-select-sm w-auto shadow-none fw-semibold" id="periodFilter">
          <option value="2026-09">Tháng 09/2026</option>
          <option value="2026-08">Tháng 08/2026</option>
          <option value="2026-07">Tháng 07/2026</option>
        </select>
        <select class="form-select form-select-sm w-auto shadow-none fw-semibold" id="methodFilter">
          <option value="all">Tất cả hình thức</option>
          <option value="transfer">Chuyển khoản</option>
          <option value="cash">Tiền mặt</option>
          <option value="momo">MoMo</option>
        </select>
        <select class="form-select form-select-sm w-auto shadow-none fw-semibold" id="statusFilter">
          <option value="all">Tất cả trạng thái</option>
          <option value="success">Thành công</option>
          <option value="pending">Đang chờ</option>
          <option value="failed">Thất bại</option>
          <option value="refund">Hoàn tiền</option>
        </select>
      </div>
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
          <!-- Dữ liệu mẫu -->
          <tr>
            <td class="ps-2">
              <div class="txn-id">#TXN-0905-A12</div>
              <div class="txn-time"><i class="bi bi-clock me-1"></i>05/09/26 · 14:30</div>
            </td>
            <td>
              <div class="room-name">Phòng 12A</div>
              <div class="tenant-name">Thanh Huyền</div>
            </td>
            <td>
              <a href="#" class="invoice-ref">#INV-09-12A</a>
            </td>
            <td><div class="amount-in mono-num">+ 3.450.000 ₫</div></td>
            <td><span class="badge-method transfer"><i class="bi bi-bank"></i> Chuyển khoản</span></td>
            <td><span class="badge-status success"><i class="bi bi-check-circle"></i> Thành công</span></td>
            <td class="text-end pe-2">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon print-btn" data-bs-toggle="tooltip" title="In biên lai"><i class="bi bi-printer"></i></button>
            </td>
          </tr>

          <tr>
            <td class="ps-2">
              <div class="txn-id">#TXN-0902-B14</div>
              <div class="txn-time"><i class="bi bi-clock me-1"></i>02/09/26 · 09:15</div>
            </td>
            <td>
              <div class="room-name">Phòng 14B</div>
              <div class="tenant-name">Hoàng Nam</div>
            </td>
            <td>
              <a href="#" class="invoice-ref">#INV-09-14B</a>
            </td>
            <td><div class="amount-in mono-num">+ 4.100.000 ₫</div></td>
            <td><span class="badge-method cash"><i class="bi bi-cash-stack"></i> Tiền mặt</span></td>
            <td><span class="badge-status success"><i class="bi bi-check-circle"></i> Thành công</span></td>
            <td class="text-end pe-2">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon print-btn" data-bs-toggle="tooltip" title="In biên lai"><i class="bi bi-printer"></i></button>
            </td>
          </tr>

          <tr>
            <td class="ps-2">
              <div class="txn-id">#TXN-0830-C01</div>
              <div class="txn-time"><i class="bi bi-clock me-1"></i>30/08/26 · 18:00</div>
            </td>
            <td>
              <div class="room-name">Phòng 01C</div>
              <div class="tenant-name">Ngọc Ánh</div>
            </td>
            <td>
              <span class="deposit-ref">Thu cọc giữ chỗ</span>
            </td>
            <td><div class="amount-in mono-num">+ 1.000.000 ₫</div></td>
            <td><span class="badge-method transfer"><i class="bi bi-bank"></i> Chuyển khoản</span></td>
            <td><span class="badge-status pending"><i class="bi bi-clock"></i> Đang chờ</span></td>
            <td class="text-end pe-2">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon success confirm-btn" data-bs-toggle="tooltip" title="Xác nhận" style="border-color:#28a745;color:#28a745;"><i class="bi bi-check-lg"></i></button>
            </td>
          </tr>

          <tr>
            <td class="ps-2">
              <div class="txn-id">#TXN-0825-D02</div>
              <div class="txn-time"><i class="bi bi-clock me-1"></i>25/08/26 · 11:00</div>
            </td>
            <td>
              <div class="room-name">Phòng 02D</div>
              <div class="tenant-name">Quốc Bảo</div>
            </td>
            <td>
              <a href="#" class="invoice-ref">#INV-08-02D</a>
            </td>
            <td><div class="amount-out mono-num">- 3.200.000 ₫</div></td>
            <td><span class="badge-method momo"><i class="bi bi-phone"></i> MoMo</span></td>
            <td><span class="badge-status failed"><i class="bi bi-x-circle"></i> Thất bại</span></td>
            <td class="text-end pe-2">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon danger retry-btn" data-bs-toggle="tooltip" title="Thử lại"><i class="bi bi-arrow-repeat"></i></button>
            </td>
          </tr>

          <tr>
            <td class="ps-2">
              <div class="txn-id">#TXN-0815-B03</div>
              <div class="txn-time"><i class="bi bi-clock me-1"></i>15/08/26 · 10:00</div>
            </td>
            <td>
              <div class="room-name">Phòng 03B</div>
              <div class="tenant-name">Thu Trang</div>
            </td>
            <td>
              <span class="deposit-ref">Hoàn tiền hủy HĐ</span>
            </td>
            <td><div class="amount-out mono-num">- 3.500.000 ₫</div></td>
            <td><span class="badge-method cash"><i class="bi bi-cash-stack"></i> Tiền mặt</span></td>
            <td><span class="badge-status refund"><i class="bi bi-arrow-counterclockwise"></i> Hoàn tiền</span></td>
            <td class="text-end pe-2">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon print-btn" data-bs-toggle="tooltip" title="In biên lai"><i class="bi bi-printer"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="panel-footer hide-on-skeleton">
      <div id="resultCount">Hiển thị 5 trên 27 giao dịch</div>
      <div class="d-flex gap-2" id="pager">
        <button class="page-btn" disabled><i class="bi bi-chevron-left"></i></button>
        <button class="page-btn active">1</button>
        <button class="page-btn">2</button>
        <button class="page-btn">3</button>
        <button class="page-btn"><i class="bi bi-chevron-right"></i></button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: THÊM GHI NHẬN THANH TOÁN - ĐÁP ỨNG TRƯỜNG HỢP THỦ CÔNG -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" style="color: var(--forest);">
          <i class="bi bi-plus-circle me-2"></i>Ghi nhận thanh toán
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <!-- Thông báo có thể ghi nhận thủ công -->
        <div class="alert alert-info border-0 py-2 px-3 mb-3" style="background: var(--forest-tint); color: var(--forest); font-size: 0.85rem;">
          <i class="bi bi-info-circle me-1"></i> Có thể chọn hóa đơn có sẵn hoặc nhập tay cho các khoản thu phát sinh (tiền cọc, đền bù,...)
        </div>

        <!-- Chọn hóa đơn (có option để nhập tay) -->
        <div class="mb-3">
          <label class="form-label fw-bold small">Hóa đơn / Khách thuê</label>
          <select class="form-select" id="invoiceSelect">
            <option value="">+ Nhập tay (không chọn hóa đơn)</option>
            <option value="INV-09-12A">#INV-09-12A - Thanh Huyền - Phòng 12A - 3.450.000 ₫</option>
            <option value="INV-09-14B">#INV-09-14B - Hoàng Nam - Phòng 14B - 4.100.000 ₫</option>
            <option value="INV-08-01C">#INV-08-01C - Ngọc Ánh - Phòng 01C - 2.800.000 ₫</option>
            <option value="INV-08-02D">#INV-08-02D - Quốc Bảo - Phòng 02D - 3.200.000 ₫</option>
          </select>
        </div>

        <!-- Nhập tên khách (hiện khi chọn "Nhập tay") -->
        <div class="mb-3" id="manualTenantGroup" style="display: none;">
          <label class="form-label fw-bold small">Tên khách thuê <span class="text-danger">*</span></label>
          <input type="text" class="form-control" id="manualTenant" placeholder="Nhập tên khách thuê...">
        </div>

        <!-- Nhập phòng (hiện khi chọn "Nhập tay") -->
        <div class="mb-3" id="manualRoomGroup" style="display: none;">
          <label class="form-label fw-bold small">Phòng</label>
          <input type="text" class="form-control" id="manualRoom" placeholder="Nhập tên phòng...">
        </div>

        <div class="row g-3">
          <div class="col-6">
            <label class="form-label fw-bold small">Số tiền <span class="text-danger">*</span></label>
            <div class="input-group">
              <span class="input-group-text">₫</span>
              <input type="number" class="form-control" id="formAmount" placeholder="0">
            </div>
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small">Hình thức <span class="text-danger">*</span></label>
            <select class="form-select" id="formMethod">
              <option value="transfer">Chuyển khoản</option>
              <option value="cash">Tiền mặt</option>
              <option value="momo">MoMo</option>
            </select>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-6">
            <label class="form-label fw-bold small">Ngày thanh toán <span class="text-danger">*</span></label>
            <input type="date" class="form-control" id="formDate" value="{{ date('Y-m-d') }}">
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small">Trạng thái</label>
            <select class="form-select" id="formStatus">
              <option value="success">Thành công</option>
              <option value="pending">Đang chờ</option>
              <option value="failed">Thất bại</option>
            </select>
          </div>
        </div>

        <div class="mb-0 mt-3">
          <label class="form-label fw-bold small">Ghi chú</label>
          <textarea class="form-control" id="formNote" rows="2" placeholder="Nhập ghi chú (nếu có)..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-brand fw-bold px-4" id="btnSavePayment">
          <i class="bi bi-save me-1"></i>Lưu giao dịch
        </button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: CHI TIẾT GIAO DỊCH -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title fw-bold" style="color: var(--forest);" id="detailCode">#TXN-0905-A12</h5>
          <div class="text-muted small" id="detailDate">05/09/2026 · 14:30</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-6">
            <div class="text-muted small">Khách thuê</div>
            <div class="fw-bold" id="detailTenant">Thanh Huyền</div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Phòng</div>
            <div class="fw-bold" id="detailRoom">Phòng 12A</div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Số tiền</div>
            <div class="fw-bold fs-5 text-success mono-num" id="detailAmount">+ 3.450.000 ₫</div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Hình thức</div>
            <div id="detailMethod"><span class="badge-method transfer"><i class="bi bi-bank"></i> Chuyển khoản</span></div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Trạng thái</div>
            <div id="detailStatus"><span class="badge-status success"><i class="bi bi-check-circle"></i> Thành công</span></div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Tham chiếu</div>
            <div class="fw-bold" id="detailRef"><a href="#" style="color:var(--bank);text-decoration:none;">#INV-09-12A</a></div>
          </div>
          <div class="col-12" id="detailNoteContainer">
            <div class="text-muted small">Ghi chú</div>
            <div class="fw-bold" id="detailNote">Thanh toán tiền thuê tháng 9</div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-light border fw-bold" id="btnPrintDetail"><i class="bi bi-printer-fill me-1"></i>In biên lai</button>
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-outline-danger fw-bold d-none" id="btnRefund"><i class="bi bi-arrow-counterclockwise me-1"></i>Hoàn tiền</button>
          <button class="btn btn-sm btn-success fw-bold d-none" id="btnConfirm"><i class="bi bi-check-lg me-1"></i>Xác nhận</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Tắt skeleton
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

    // Tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));

    // ==================== HIỂN THỊ NHẬP TAY KHI CHỌN OPTION ====================
    const invoiceSelect = document.getElementById('invoiceSelect');
    const manualTenantGroup = document.getElementById('manualTenantGroup');
    const manualRoomGroup = document.getElementById('manualRoomGroup');
    const manualTenant = document.getElementById('manualTenant');
    const manualRoom = document.getElementById('manualRoom');
    const formAmount = document.getElementById('formAmount');

    invoiceSelect.addEventListener('change', function() {
      const value = this.value;
      if (value === '') {
        // Chọn "Nhập tay"
        manualTenantGroup.style.display = 'block';
        manualRoomGroup.style.display = 'block';
        formAmount.value = '';
        formAmount.placeholder = 'Nhập số tiền...';
      } else {
        manualTenantGroup.style.display = 'none';
        manualRoomGroup.style.display = 'none';
        // Tự động lấy thông tin từ option
        const selectedOption = this.options[this.selectedIndex];
        const text = selectedOption.text;
        const parts = text.split(' - ');
        if (parts.length >= 3) {
          const amountText = parts[2] || '';
          const amountMatch = amountText.match(/([\d.]+)/);
          if (amountMatch) {
            formAmount.value = amountMatch[0].replace(/\./g, '');
          }
        }
        formAmount.placeholder = '0';
      }
    });

    // ==================== VIEW DETAIL ====================
    document.querySelectorAll('.view-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        const modal = new bootstrap.Modal(document.getElementById('detailModal'));
        modal.show();
      });
    });

    // ==================== CONFIRM PAYMENT ====================
    document.querySelectorAll('.confirm-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        if (confirm('Xác nhận giao dịch này thành công?')) {
          const row = this.closest('tr');
          const statusCell = row.querySelector('td:nth-child(6)');
          statusCell.innerHTML = '<span class="badge-status success"><i class="bi bi-check-circle"></i> Thành công</span>';
          const amountCell = row.querySelector('td:nth-child(4) div');
          amountCell.className = 'amount-in mono-num';
          this.remove();
          alert('Đã xác nhận giao dịch thành công!');
        }
      });
    });

    // ==================== RETRY PAYMENT ====================
    document.querySelectorAll('.retry-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        if (confirm('Thử lại giao dịch này?')) {
          alert('Đã gửi yêu cầu thử lại!');
        }
      });
    });

    // ==================== PRINT ====================
    document.querySelectorAll('.print-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        alert('Đang in biên lai...');
      });
    });

    // ==================== SAVE PAYMENT ====================
    document.getElementById('btnSavePayment').addEventListener('click', function() {
      const invoiceVal = invoiceSelect.value;
      const amount = formAmount.value;
      const method = document.getElementById('formMethod').value;
      const status = document.getElementById('formStatus').value;
      const note = document.getElementById('formNote').value;
      const date = document.getElementById('formDate').value;

      // Kiểm tra số tiền
      if (!amount || parseInt(amount) <= 0) {
        alert('Vui lòng nhập số tiền hợp lệ!');
        return;
      }

      // Lấy thông tin
      let tenantName = '';
      let roomName = '';
      let invoiceRef = '';

      if (invoiceVal === '') {
        // Trường hợp nhập tay
        tenantName = manualTenant.value.trim() || 'Khách mới';
        roomName = manualRoom.value.trim() || 'Chưa xác định';
        invoiceRef = 'Thu phát sinh';
      } else {
        // Trường hợp chọn hóa đơn
        const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
        const text = selectedOption.text;
        const parts = text.split(' - ');
        invoiceRef = parts[0] || '';
        tenantName = parts[1] || '';
        roomName = parts[2] || '';
      }

      const methodLabels = {
        transfer: 'Chuyển khoản',
        cash: 'Tiền mặt',
        momo: 'MoMo'
      };

      const statusLabels = {
        success: 'Thành công',
        pending: 'Đang chờ',
        failed: 'Thất bại'
      };

      const fmtVND = n => Math.round(n).toLocaleString('vi-VN').replace(/,/g, '.') + ' ₫';

      alert(
        '✅ Đã lưu giao dịch!\n\n' +
        '📌 Khách thuê: ' + tenantName + '\n' +
        '🏠 Phòng: ' + roomName + '\n' +
        '📄 Tham chiếu: ' + invoiceRef + '\n' +
        '💰 Số tiền: ' + fmtVND(parseInt(amount)) + '\n' +
        '💳 Hình thức: ' + methodLabels[method] + '\n' +
        '📅 Ngày: ' + date + '\n' +
        '📌 Trạng thái: ' + statusLabels[status] + '\n' +
        '📝 Ghi chú: ' + (note || 'Không có') + '\n\n' +
        '🔄 Giao dịch đã được thêm vào danh sách!'
      );

      // Đóng modal
      const modal = bootstrap.Modal.getInstance(document.getElementById('addPaymentModal'));
      modal.hide();

      // Reset form
      invoiceSelect.value = '';
      manualTenant.value = '';
      manualRoom.value = '';
      formAmount.value = '';
      document.getElementById('formNote').value = '';
      manualTenantGroup.style.display = 'none';
      manualRoomGroup.style.display = 'none';
    });

    // ==================== SEARCH TABLE ====================
    document.getElementById('searchInput').addEventListener('input', function() {
      const search = this.value.toLowerCase();
      const rows = document.querySelectorAll('#paymentTableBody tr');
      let visible = 0;
      rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(search)) {
          row.style.display = '';
          visible++;
        } else {
          row.style.display = 'none';
        }
      });
      document.getElementById('resultCount').textContent = `Hiển thị ${visible} trên ${rows.length} giao dịch`;
    });

    // ==================== FILTER ====================
    document.querySelectorAll('#methodFilter, #statusFilter, #periodFilter').forEach(filter => {
      filter.addEventListener('change', function() {
        const method = document.getElementById('methodFilter').value;
        const status = document.getElementById('statusFilter').value;
        const period = document.getElementById('periodFilter').value;
        const rows = document.querySelectorAll('#paymentTableBody tr');
        let visible = 0;

        rows.forEach(row => {
          const methodText = row.querySelector('td:nth-child(5)')?.textContent || '';
          const statusText = row.querySelector('td:nth-child(6)')?.textContent || '';
          const dateText = row.querySelector('.txn-time')?.textContent || '';

          let show = true;
          if (method !== 'all' && !methodText.toLowerCase().includes(method)) show = false;
          if (status !== 'all' && !statusText.toLowerCase().includes(status)) show = false;
          if (period && !dateText.includes(period.replace('-', '/'))) show = false;

          row.style.display = show ? '' : 'none';
          if (show) visible++;
        });

        document.getElementById('resultCount').textContent = `Hiển thị ${visible} trên ${rows.length} giao dịch`;
      });
    });
  });
</script>
</body>
</html>