<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý Điện nước | Trọ Ơi</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font: Be Vietnam Pro -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<style>
  :root {
    --green: #20584f;
    --green-dark: #17463e;
    --green-soft: #eaf3ef;
    --cream: #f8f5eb;
    --yellow: #f5c84b;
    --yellow-light: #fff7d7;
    --text: #213430;
    --muted: #78837e;
    --border: #e6dcc2;
    --white: #ffffff;
    --red: #c65b4a;
    --red-soft: #fbe9e6;
    --blue: #356d9e;
    --blue-soft: #e8f2ff;
  }

  * { box-sizing: border-box; }
  html { scroll-behavior: smooth; }
  body {
    margin: 0;
    font-family: "Be Vietnam Pro", sans-serif;
    background: var(--cream);
    color: var(--text);
  }

  /* =========================================
     NAVBAR 
  ========================================= */
  .app-navbar {
    background: var(--green);
    min-height: 74px;
    box-shadow: 0 3px 14px rgba(20,55,47,.12);
    position: sticky;
    top: 0;
    z-index: 1000;
    padding: 0;
  }
  .app-navbar .container-fluid {
    max-width: 1360px;
    padding: 0 28px;
  }
  .logo {
    color: #fff;
    text-decoration: none;
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -1.2px;
    white-space: nowrap;
  }
  .logo span { color: var(--yellow); }

  .app-nav-link {
    color: rgba(255,255,255,.82) !important;
    font-size: 13.5px;
    font-weight: 600;
    padding: 9px 13px !important;
    border-radius: 10px;
    transition: .2s;
    white-space: nowrap;
    text-decoration: none;
  }
  .app-nav-link:hover, .app-nav-link.active {
    color: var(--green) !important;
    background: #fff;
  }

  /* Right-hand user zone */
  .navbar-actions {
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .notif-btn {
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
  }
  .notif-dot {
    position: absolute;
    top: 7px;
    right: 7px;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--yellow);
    border: 2px solid var(--green);
  }
  .user-chip {
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
  .user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: var(--yellow);
    color: var(--green-dark);
    font-weight: 800;
    font-size: 13px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
  }
  .user-meta { line-height: 1.2; }
  .user-name { font-size: 12.5px; font-weight: 700; color: #fff; }
  .user-role { font-size: 10px; color: rgba(255,255,255,.65); }
  .caret { font-size: 9px; color: rgba(255,255,255,.6); margin-left: 2px; }

  @media(max-width:991px){
    .app-nav-link { margin: 2px 0; }
    .navbar-toggler { filter: invert(1); border: none; }
  }

  /* =========================================
     PAGE HEADER & BUTTONS
  ========================================= */
  .page-wrap { max-width: 1360px; margin: 0 auto; padding: 30px 28px 70px; }
  
  .page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    flex-wrap: wrap;
    gap: 16px;
    margin-bottom: 26px;
  }
  .page-title {
    color: var(--green-dark);
    font-size: 26px;
    font-weight: 800;
    letter-spacing: -.6px;
    margin: 0;
  }
  .page-desc { color: var(--muted); font-size: 13px; margin-top: 6px; }
  
  .btn-brand {
    border: 0;
    background: var(--yellow);
    color: var(--green-dark);
    font-weight: 800;
    font-size: 13px;
    border-radius: 11px;
    padding: 11px 18px;
    white-space: nowrap;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: 0.2s;
  }
  .btn-brand:hover { background: #ffd968; color: var(--green-dark); }
  
  .btn-outline-brand {
    border: 1px solid var(--border);
    background: #fff;
    color: var(--green);
    font-weight: 700;
    font-size: 13px;
    border-radius: 11px;
    padding: 10px 16px;
    transition: 0.2s;
  }
  .btn-outline-brand:hover { background: var(--green-soft); }

  /* =========================================
     STAT CARDS
  ========================================= */
  .stat-card {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 20px 22px;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 10px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  }
  .stat-top { display: flex; align-items: center; justify-content: space-between; }
  .stat-icon {
    width: 42px; height: 42px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 19px;
    background: var(--yellow-light);
  }
  .stat-icon.blue { background: var(--blue-soft); }
  .stat-icon.red { background: var(--red-soft); }
  .stat-icon.green { background: var(--green-soft); }
  
  .stat-trend { font-size: 11px; font-weight: 800; color: var(--green); }
  .stat-trend.down { color: var(--red); }
  .stat-trend.muted { color: var(--muted); }
  
  .stat-value { font-size: 26px; font-weight: 800; color: var(--green-dark); letter-spacing: -.5px; }
  .stat-value small { font-size: 14px; font-weight: 600; color: var(--muted); }
  .stat-label { font-size: 12px; color: var(--muted); font-weight: 600; }

  /* =========================================
     PANEL & TABLES
  ========================================= */
  .panel {
    background: #fff;
    border: 1px solid var(--border);
    border-radius: 18px;
    padding: 24px;
    height: 100%;
    box-shadow: 0 4px 12px rgba(0,0,0,0.02);
  }
  .panel-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
  }
  .panel-title { font-size: 16px; font-weight: 800; color: var(--green-dark); margin: 0; }

  .data-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
  .data-table thead th {
    text-align: left;
    color: var(--muted);
    font-size: 10.5px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 0 12px 12px;
    border-bottom: 1px solid var(--border);
    white-space: nowrap;
  }
  .data-table tbody td {
    padding: 16px 12px;
    border-bottom: 1px solid #f0eadc;
    color: var(--text);
    vertical-align: middle;
  }
  .data-table tbody tr:last-child td { border-bottom: 0; }
  .data-table tbody tr:hover td { background-color: rgba(248, 245, 235, 0.4); }

  .cell-title { font-weight: 800; color: var(--green-dark); font-size: 14px; margin-bottom: 3px; }
  .cell-sub { color: var(--muted); font-size: 11.5px; }
  
  .avatar-sm {
    width: 36px; height: 36px; border-radius: 10px;
    background: var(--green-soft); color: var(--green);
    font-weight: 800; font-size: 13px;
    display: flex; align-items: center; justify-content: center;
  }
  .avatar-sm.orange { background: var(--yellow-light); color: #9b6a00; }

  /* BADGES */
  .badge-status {
    display: inline-block;
    padding: 5px 10px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 800;
  }
  .badge-status.occupied { background: var(--green-soft); color: var(--green); }
  .badge-status.vacant { background: #f0eadc; color: var(--muted); }
  .badge-status.pending { background: var(--yellow-light); color: #9b6a00; }

  /* FILTERS */
  .filter-label {
    display: block; font-size: 11px; font-weight: 800; color: var(--muted);
    text-transform: uppercase; letter-spacing: .4px; margin-bottom: 6px;
  }
  .custom-input {
    width: 100%;
    min-height: 44px;
    padding: 8px 14px;
    border-radius: 11px;
    border: 1px solid var(--border);
    background: #fff;
    color: var(--green-dark);
    font-weight: 600;
    font-size: 13px;
    outline: none;
    transition: 0.2s;
  }
  .custom-input:focus { border-color: var(--green); box-shadow: 0 0 0 3px var(--green-soft); }

  .btn-icon {
    width: 34px; height: 34px; border-radius: 10px;
    background: #fff; border: 1px solid var(--border);
    color: var(--green-dark); font-size: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    transition: 0.2s; cursor: pointer;
  }
  .btn-icon:hover { background: var(--green-soft); color: var(--green); border-color: var(--green); }
  
  /* MODAL */
  .modal-content { border-radius: 20px; border: none; overflow: hidden; }
  .modal-header { border-bottom: 1px solid var(--border); padding: 22px 26px; }
  .modal-title { color: var(--green-dark); font-weight: 800; font-size: 18px; letter-spacing: -0.3px; }
  .modal-body { padding: 26px; }
  .modal-footer { border-top: 1px solid var(--border); padding: 18px 26px; }
  .reading-box { 
    background: var(--cream); 
    border: 1px solid var(--border); 
    border-radius: 14px; 
    padding: 18px; 
    margin-bottom: 16px; 
  }
  .reading-box h6 { color: var(--green-dark); font-weight: 800; font-size: 14px; }
  
  .text-highlight { color: var(--red); font-weight: 800; }
</style>
</head>
<body>

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
              <a class="dropdown-item active" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a>
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

<div class="page-wrap">
  <!-- PAGE HEADER -->
  <div class="page-header">
    <div>
      <h2 class="page-title">Quản lý điện nước</h2>
      <div class="page-desc">Theo dõi mức tiêu thụ và chốt chỉ số cho các phòng trong tháng.</div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn-outline-brand">📥 Xuất Excel</button>
      <a class="btn-brand" href="{{ route('owner.utilities.create') }}" >+ Ghi chỉ số mới</a>
    </div>
  </div>

  <!-- STAT CARDS -->
  @php
      $totalRooms = $rooms->count();
      $currentMonth = \Carbon\Carbon::parse($month)->startOfMonth();
      $readingsThisMonth = $readings->filter(fn($r) => \Carbon\Carbon::parse($r->month)->isSameMonth($currentMonth));
      $doneCount = $readingsThisMonth->count();
      $totalKwh = $readingsThisMonth->sum(fn($r) => (float)$r->electricity_new - (float)$r->electricity_old);
      $totalM3 = $readingsThisMonth->sum(fn($r) => (float)$r->water_new - (float)$r->water_old);
      $totalMoney = $readingsThisMonth->sum(fn($r) => ((float)$r->electricity_new - (float)$r->electricity_old) * (float)$r->electricity_price + ((float)$r->water_new - (float)$r->water_old) * (float)$r->water_price);
  @endphp
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon">🏠</div>
          <span class="stat-trend muted">{{ $currentMonth->format('m/Y') }}</span>
        </div>
        <div class="stat-value">{{ $totalRooms }}</div>
        <div class="stat-label">Tổng số phòng quản lý</div>
      </div>
    </div>
    
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green">⚡</div>
          <span class="stat-trend">Còn {{ max(0, $totalRooms - $doneCount) }} phòng</span>
        </div>
        <div class="stat-value">{{ $doneCount }} <small>/ {{ $totalRooms }}</small></div>
        <div class="stat-label">Đã chốt chỉ số</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon blue">💧</div>
          <span class="stat-trend">Tháng hiện tại</span>
        </div>
        <div class="stat-value">{{ number_format($totalKwh + $totalM3) }}</div>
        <div class="stat-label">Tổng tiêu thụ (kWh + m³)</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon red">💰</div>
          <span class="stat-trend">Tạm tính</span>
        </div>
        <div class="stat-value">{{ number_format($totalMoney) }}đ</div>
        <div class="stat-label">Tổng tiền dự kiến</div>
      </div>
    </div>
  </div>

  <!-- BẢNG DỮ LIỆU -->
  <div class="panel">

    @if(session('success'))
      <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    
    <!-- FILTERS -->
    <form method="GET" action="{{ route('owner.utilities.index') }}" class="row g-3 mb-4">
      <div class="col-md-4">
        <label class="filter-label">Phòng</label>
        <select name="room_id" class="custom-input">
          <option value="">Tất cả phòng</option>
          @foreach($rooms as $room)
            <option value="{{ $room->id }}" @selected((string) $selectedRoomId === (string) $room->id)>{{ $room->name }} · {{ $room->property->name ?? '' }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="filter-label">Kỳ ghi chỉ số</label>
        <input type="month" name="month" class="custom-input" value="{{ $month }}">
      </div>
      <div class="col-md-4 d-flex align-items-end gap-2">
        <button type="submit" class="btn-brand flex-grow-1" style="height: 44px; justify-content: center;">Lọc dữ liệu</button>
        <a href="{{ route('owner.utilities.index') }}" class="btn-outline-brand text-decoration-none" style="height: 44px; display: inline-flex; align-items: center;">Xóa lọc</a>
      </div>
    </form>

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Phòng</th>
            <th>Tháng</th>
            <th>Chỉ số Điện (Cũ - Mới)</th>
            <th>Chỉ số Nước (Cũ - Mới)</th>
            <th>Tiêu thụ</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($readings as $reading)
            @php
                $eUse = (float)$reading->electricity_new - (float)$reading->electricity_old;
                $wUse = (float)$reading->water_new - (float)$reading->water_old;
                $eMoney = $eUse * (float)$reading->electricity_price;
                $wMoney = $wUse * (float)$reading->water_price;
            @endphp
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">{{ substr($reading->room->name ?? '?', -3) }}</div>
                <div>
                  <div class="cell-title">{{ $reading->room->name ?? '—' }} · {{ $reading->room->room_code ?? '' }}</div>
                  <div class="cell-sub">{{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }}</div>
                </div>
              </div>
            </td>
            <td>{{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }}</td>
            <td>
              <div>Cũ: <strong>{{ number_format((float)$reading->electricity_old) }}</strong></div>
              <div class="mt-1">Mới: <strong>{{ number_format((float)$reading->electricity_new) }}</strong></div>
            </td>
            <td>
              <div>Cũ: <strong>{{ number_format((float)$reading->water_old) }}</strong></div>
              <div class="mt-1">Mới: <strong>{{ number_format((float)$reading->water_new) }}</strong></div>
            </td>
            <td>
              <div style="font-weight: 700; color: var(--green-dark);">⚡ {{ number_format($eUse) }} kWh</div>
              <div class="mt-1" style="font-weight: 700; color: var(--green-dark);">💧 {{ number_format($wUse) }} m³</div>
            </td>
            <td>
              <div>⚡ {{ number_format($eMoney) }}đ</div>
              <div class="mt-1 border-bottom pb-1" style="border-color: #f0eadc!important;">💧 {{ number_format($wMoney) }}đ</div>
              <div class="text-highlight mt-1">Σ {{ number_format($eMoney + $wMoney) }}đ</div>
            </td>
            <td><span class="badge-status occupied">Đã ghi</span></td>
            <td class="text-end">
              <a class="btn-brand" style="padding: 6px 12px;" href="{{ route('owner.utilities.create', ['id' => $reading->id]) }}">✏️ Sửa</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-5">Chưa có chỉ số điện nước nào.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>