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
  .data-table tbody tr:hover td { background-color: rgba(248, 245, 235, 0.4); } /* Cream hover */

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
  .badge-status.occupied { background: var(--green-soft); color: var(--green); } /* Xanh lá */
  .badge-status.vacant { background: #f0eadc; color: var(--muted); } /* Xám nhạt */
  .badge-status.pending { background: var(--yellow-light); color: #9b6a00; } /* Vàng */

  /* BỘ LỌC (FILTERS) */
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

<!-- NAVBAR NGANG — OWNER -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="owner.html">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="#">Tổng quan</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Nhà &amp; Phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Tin đăng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Người thuê</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Dịch vụ</a></li>
        <!-- ACTIVE Ở ĐÂY -->
        <li class="nav-item"><a class="app-nav-link active" href="#">Điện nước</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Giao dịch</a></li>
      </ul>

      <div class="navbar-actions mt-3 mt-lg-0">
        <button class="notif-btn">🔔<span class="notif-dot"></span></button>
        <a href="#" class="user-chip">
          <span class="user-avatar">MT</span>
          <span class="user-meta d-none d-md-block">
            <span class="user-name d-block">Minh Tuấn</span>
            <span class="user-role">Chủ trọ</span>
          </span>
          <span class="caret d-none d-md-block">▾</span>
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
      <a class="btn-brand" href="{{ route("owner.utilities.create") }}" >Them ghi chú mới</a>
    </div>
  </div>

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon">🏠</div>
          <span class="stat-trend muted">Tháng 09/2026</span>
        </div>
        <div class="stat-value">42</div>
        <div class="stat-label">Tổng số phòng quản lý</div>
      </div>
    </div>
    
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green">⚡</div>
          <span class="stat-trend">Còn 7 phòng</span>
        </div>
        <div class="stat-value">35 <small>/ 42</small></div>
        <div class="stat-label">Đã chốt chỉ số</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon blue">💧</div>
          <span class="stat-trend">+5% so với T8</span>
        </div>
        <div class="stat-value">4.280</div>
        <div class="stat-label">Tổng tiêu thụ (kWh + m³)</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon red">💰</div>
          <span class="stat-trend">Tạm tính</span>
        </div>
        <div class="stat-value">16,5tr</div>
        <div class="stat-label">Tổng tiền dự kiến</div>
      </div>
    </div>
  </div>

  <!-- BẢNG DỮ LIỆU -->
  <div class="panel">
    
    <!-- FILTERS -->
    <div class="row g-3 mb-4">
      <div class="col-md-3">
        <label class="filter-label">Khu vực / Tòa nhà</label>
        <select class="custom-input">
          <option>Tất cả nhà trọ</option>
          <option>Nhà trọ A - Q.7</option>
          <option>Nhà trọ B - Bình Thạnh</option>
        </select>
      </div>
      <div class="col-md-3">
        <label class="filter-label">Kỳ ghi chỉ số</label>
        <input type="month" class="custom-input" value="2026-09">
      </div>
      <div class="col-md-3">
        <label class="filter-label">Trạng thái</label>
        <select class="custom-input">
          <option>Tất cả trạng thái</option>
          <option>Đã ghi</option>
          <option>Chưa ghi</option>
          <option>Phòng trống</option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button class="btn-brand w-100" style="height: 44px;">Lọc dữ liệu</button>
      </div>
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Phòng</th>
            <th>Chỉ số Điện (Cũ - Mới)</th>
            <th>Chỉ số Nước (Cũ - Mới)</th>
            <th>Tiêu thụ</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          <!-- HÀNG 1: Đã ghi -->
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">101</div>
                <div>
                  <div class="cell-title">Phòng 101 · Nhà A</div>
                  <div class="cell-sub">👤 Ngọc Anh</div>
                </div>
              </div>
            </td>
            <td>
              <div>Cũ: <strong>1,250</strong></div>
              <div class="mt-1">Mới: <strong>1,380</strong></div>
            </td>
            <td>
              <div>Cũ: <strong>80</strong></div>
              <div class="mt-1">Mới: <strong>85</strong></div>
            </td>
            <td>
              <div style="font-weight: 700; color: var(--green-dark);">⚡ 130 kWh</div>
              <div class="mt-1" style="font-weight: 700; color: var(--green-dark);">💧 5 m³</div>
            </td>
            <td>
              <div>⚡ 455.000đ</div>
              <div class="mt-1 border-bottom pb-1" style="border-color: #f0eadc!important;">💧 100.000đ</div>
              <div class="text-highlight mt-1">Σ 555.000đ</div>
            </td>
            <td><span class="badge-status occupied">Đã ghi</span></td>
            <td class="text-end">
              <button class="btn-icon" onclick="openModal('Phòng 101 · Nhà A', 'Ngọc Anh', 1250, 80)">✏️</button>
            </td>
          </tr>

          <!-- HÀNG 2: Trống -->
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm" style="background: #f0eadc; color: var(--muted);">102</div>
                <div>
                  <div class="cell-title" style="color: var(--muted);">Phòng 102 · Nhà A</div>
                  <div class="cell-sub">—</div>
                </div>
              </div>
            </td>
            <td colspan="4" class="text-center" style="background: var(--cream); border-radius: 12px; color: var(--muted); font-weight: 600;">
              Chưa có khách thuê
            </td>
            <td><span class="badge-status vacant">Phòng trống</span></td>
            <td class="text-end">
              <button class="btn-icon" style="opacity: 0.5; cursor: not-allowed;">✏️</button>
            </td>
          </tr>

          <!-- HÀNG 3: Chưa ghi -->
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm orange">201</div>
                <div>
                  <div class="cell-title">Phòng 201 · Nhà B</div>
                  <div class="cell-sub">👤 Văn Hùng</div>
                </div>
              </div>
            </td>
            <td>
              <div>Cũ: <strong>2,100</strong></div>
              <div class="mt-1 text-muted">Mới: —</div>
            </td>
            <td>
              <div>Cũ: <strong>120</strong></div>
              <div class="mt-1 text-muted">Mới: —</div>
            </td>
            <td class="text-muted">—</td>
            <td class="text-muted">—</td>
            <td><span class="badge-status pending">Chưa chốt</span></td>
            <td class="text-end">
              <button class="btn-brand" style="padding: 6px 12px;" onclick="openModal('Phòng 201 · Nhà B', 'Văn Hùng', 2100, 120)">+ Ghi</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- =====================================================
     MODAL GHI CHỈ SỐ (Được style theo theme)
===================================================== -->
<div class="modal fade" id="readingModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Cập nhật chỉ số điện nước</h5>
        <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
      </div>

      <form>
        <div class="modal-body">
          
          <div class="d-flex align-items-center gap-3 mb-4 p-3 rounded" style="background: var(--green-soft); border: 1px solid var(--border);">
            <div class="avatar-sm">🏠</div>
            <div>
              <div class="cell-title fs-5" id="modalRoom" style="margin-bottom: 0;">Phòng 101</div>
              <div class="cell-sub" id="modalTenant" style="font-size: 13px;">Ngọc Anh</div>
            </div>
          </div>

          <!-- ĐIỆN -->
          <div class="reading-box">
            <h6 class="d-flex align-items-center gap-2 mb-3">
              <span style="font-size: 18px;">⚡</span> Chỉ số điện
            </h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="filter-label">Chỉ số cũ</label>
                <input type="number" id="dienCu" class="custom-input" style="background: #f0eadc; border: none;" readonly>
              </div>
              <div class="col-md-4">
                <label class="filter-label">Chỉ số mới</label>
                <input type="number" class="custom-input border-success" placeholder="Nhập số mới..." required autofocus>
              </div>
              <div class="col-md-4">
                <label class="filter-label">Đơn giá (đ/kWh)</label>
                <input type="number" class="custom-input" value="3500" required>
              </div>
            </div>
          </div>

          <!-- NƯỚC -->
          <div class="reading-box mb-0">
            <h6 class="d-flex align-items-center gap-2 mb-3">
              <span style="font-size: 18px;">💧</span> Chỉ số nước
            </h6>
            <div class="row g-3">
              <div class="col-md-4">
                <label class="filter-label">Chỉ số cũ</label>
                <input type="number" id="nuocCu" class="custom-input" style="background: #f0eadc; border: none;" readonly>
              </div>
              <div class="col-md-4">
                <label class="filter-label">Chỉ số mới</label>
                <input type="number" class="custom-input border-success" placeholder="Nhập số mới..." required>
              </div>
              <div class="col-md-4">
                <label class="filter-label">Đơn giá (đ/m³)</label>
                <input type="number" class="custom-input" value="20000" required>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal-footer d-flex gap-2">
          <button type="button" class="btn-outline-brand" data-bs-dismiss="modal">Hủy bỏ</button>
          <button type="submit" class="btn-brand">💾 Lưu chỉ số</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const readingModal = new bootstrap.Modal(document.getElementById('readingModal'));

  function openModal(room, tenant, dienCu, nuocCu) {
    document.getElementById('modalRoom').innerText = room;
    document.getElementById('modalTenant').innerText = '👤 ' + tenant;
    document.getElementById('dienCu').value = dienCu;
    document.getElementById('nuocCu').value = nuocCu;
    readingModal.show();
  }
</script>
</body>
</html>