<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Kênh chủ trọ</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<!-- NAVBAR NGANG — OWNER -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="owner.html">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
  <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
          
          <!-- 1. TỔNG QUAN -->
          <li class="nav-item">
              <a class="app-nav-link active" href="{{ url('/landlord') }}">Tổng quan</a>
          </li>

          <!-- 2. QUẢN LÝ TÀI SẢN -->
          <li class="nav-item dropdown">
              <a class="app-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Nhà &amp; Phòng
              </a>
              <ul class="dropdown-menu">
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
              <a class="app-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Khách &amp; Hợp đồng
              </a>
              <ul class="dropdown-menu">
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
              <a class="app-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Tài chính
              </a>
              <ul class="dropdown-menu">
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
              <a class="app-nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                  Vận hành
              </a>
              <ul class="dropdown-menu">
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
  <div class="page-header">
    <div>
      <h2 class="page-title">Tổng quan hoạt động</h2>
      <div class="page-desc">Xin chào Minh Tuấn, đây là tình hình cho thuê hôm nay.</div>
    </div>
    <button class="btn-brand">+ Đăng tin cho thuê</button>
  </div>

  <!-- STAT CARDS -->
  <div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon">🏠</div>
          <span class="stat-trend">+2 tháng này</span>
        </div>
        <div class="stat-value">42</div>
        <div class="stat-label">Tổng số phòng</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green">✅</div>
          <span class="stat-trend">88%</span>
        </div>
        <div class="stat-value">37</div>
        <div class="stat-label">Phòng đang thuê</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon blue">🔑</div>
          <span class="stat-trend down">12%</span>
        </div>
        <div class="stat-value">5</div>
        <div class="stat-label">Phòng trống</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon red">💰</div>
          <span class="stat-trend">+8,4%</span>
        </div>
        <div class="stat-value">128,4tr</div>
        <div class="stat-label">Doanh thu tháng này</div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-7">
      <div class="panel">
        <div class="panel-head">
          <h3 class="panel-title">Danh sách phòng</h3>
          <a class="panel-link" href="#">Quản lý tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Phòng</th><th>Người thuê</th><th>Giá thuê</th><th>Trạng thái</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="cell-title">Phòng 101 · Nhà A</div><div class="cell-sub">Q.7, TP.HCM</div></td>
              <td>Ngọc Anh</td>
              <td>3,2 triệu</td>
              <td><span class="badge-status occupied">Đang thuê</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Phòng 102 · Nhà A</div><div class="cell-sub">Q.7, TP.HCM</div></td>
              <td>—</td>
              <td>3,0 triệu</td>
              <td><span class="badge-status vacant">Còn trống</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Phòng 201 · Nhà B</div><div class="cell-sub">Bình Thạnh, TP.HCM</div></td>
              <td>Văn Hùng</td>
              <td>2,8 triệu</td>
              <td><span class="badge-status occupied">Đang thuê</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Phòng 202 · Nhà B</div><div class="cell-sub">Bình Thạnh, TP.HCM</div></td>
              <td>Thu Trang</td>
              <td>2,8 triệu</td>
              <td><span class="badge-status overdue">Trễ hạn thanh toán</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="panel">
        <div class="panel-head">
          <h3 class="panel-title">Lịch xem phòng gần nhất</h3>
          <a class="panel-link" href="#">Xem tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Khách xem</th><th>Phòng</th><th>Thời gian</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="avatar-sm mb-1">TH</div>Thanh Huyền</td>
              <td>Phòng 102</td>
              <td>Hôm nay · 15:00</td>
            </tr>
            <tr>
              <td><div class="avatar-sm mb-1">QK</div>Quốc Khánh</td>
              <td>Phòng 202</td>
              <td>Mai · 09:30</td>
            </tr>
            <tr>
              <td><div class="avatar-sm mb-1">LP</div>Lan Phương</td>
              <td>Phòng 102</td>
              <td>30/08 · 14:00</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
