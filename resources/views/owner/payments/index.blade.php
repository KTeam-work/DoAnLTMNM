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
    text-decoration: none;
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
    color: #a8e6cf;
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

<!-- NAVBAR ĐÃ ĐỒNG BỘ -->
<!-- NAVBAR ĐÃ SỬA LỖI -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        
        <!-- 1. TỔNG QUAN -->
        <li class="nav-item">
          <a class="app-nav-link" href="{{ route('landlord.home') }}">Tổng quan</a>
        </li>

        <!-- 2. QUẢN LÝ TÀI SẢN -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Nhà &amp; Phòng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
            <li>
              <a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a>
            </li>
          </ul>
        </li>

        <!-- 3. KHÁCH THUÊ -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Khách &amp; Hợp đồng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop2">
            <li>
              <a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a>
            </li>
          </ul>
        </li>

        <!-- 4. TÀI CHÍNH -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tài chính
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop3">
            <li>
              <a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.utilities.index') }}">⚡ Điện nước</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route( 'owner.payments.index') }}">💰 Giao dịch</a>
            </li>
          </ul>
        </li>

        <!-- 5. VẬN HÀNH & TƯƠNG TÁC -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Vận hành
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop4">
            <li>
              <a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.appointments.index') }}">📅 Lịch xem</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ url('/owner/maintenance') }}">🛠️ Sửa chữa</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.reviews.index') }}">⭐ Đánh giá</a>
            </li>
          </ul>
        </li>

      </ul>
       <div class="navbar-actions ms-lg-3">
        <button class="notif-btn" type="button" aria-label="Thông báo">
          🔔<span class="notif-dot"></span>
        </button>
        <a href="#" class="user-chip">
          <div class="user-avatar">A</div>
          <div class="user-meta">
            <div class="user-name">Chủ trọ</div>
            <div class="user-role">Owner</div>
          </div>
          <span class="caret">▼</span>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Fallback: luôn hiện nội dung sau 2.5s kể cả khi JS phía dưới lỗi
  setTimeout(function () { document.body.classList.remove('skeleton-mode'); }, 2500);
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    // Tắt skeleton
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

    // Tooltip
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
    const tooltipList = [...tooltipTriggerList].map(el => new bootstrap.Tooltip(el));
  });
</script>
</body>
</html>