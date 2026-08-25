<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi Admin | Bảng điều khiển</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<!-- NAVBAR NGANG — ADMIN -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="admin.html">Trọ <span>Ơi</span><small>ADMIN</small></a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link active" href="admin.html">Dashboard</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Người dùng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Phòng trọ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Tin đăng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Báo cáo</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Đánh giá</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Thống kê</a></li>
      </ul>

      <div class="navbar-actions">
        <button class="notif-btn">🔔<span class="notif-dot"></span></button>
        <a href="#" class="user-chip">
          <span class="user-avatar">AD</span>
          <span class="user-meta">
            <span class="user-name d-block">Admin hệ thống</span>
            <span class="user-role">Quản trị viên</span>
          </span>
          <span class="caret">▾</span>
        </a>
      </div>
    </div>
  </div>
</nav>

<div class="page-wrap">
  <div class="page-header">
    <div>
      <h2 class="page-title">Tổng quan hệ thống</h2>
      <div class="page-desc">Theo dõi hoạt động toàn nền tảng Trọ Ơi.</div>
    </div>
    <button class="btn-outline-brand">Xuất báo cáo</button>
  </div>

  <!-- STAT CARDS -->
  <div class="row g-3 mb-3">
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon blue">👥</div>
          <span class="stat-trend">+312 tuần này</span>
        </div>
        <div class="stat-value">18.204</div>
        <div class="stat-label">Người dùng</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon">🏠</div>
          <span class="stat-trend">+96</span>
        </div>
        <div class="stat-value">6.532</div>
        <div class="stat-label">Phòng trọ đang đăng</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green">📋</div>
          <span class="stat-trend down">Cần xử lý</span>
        </div>
        <div class="stat-value">27</div>
        <div class="stat-label">Tin đăng chờ duyệt</div>
      </div>
    </div>
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon red">🚩</div>
          <span class="stat-trend down">Ưu tiên cao</span>
        </div>
        <div class="stat-value">9</div>
        <div class="stat-label">Báo cáo chưa xử lý</div>
      </div>
    </div>
  </div>

  <div class="row g-3">
    <div class="col-lg-7">
      <div class="panel">
        <div class="panel-head">
          <h3 class="panel-title">Tin đăng chờ duyệt</h3>
          <a class="panel-link" href="#">Xem tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Tin đăng</th><th>Chủ trọ</th><th>Ngày gửi</th><th>Trạng thái</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="cell-title">Phòng trọ máy lạnh Q.7</div><div class="cell-sub">22 m² · 2,8 triệu/th</div></td>
              <td>Minh Tuấn</td>
              <td>24/08</td>
              <td><span class="badge-status pending">Chờ duyệt</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Căn hộ mini An Phú</div><div class="cell-sub">35 m² · 6,8 triệu/th</div></td>
              <td>Chị Lan</td>
              <td>23/08</td>
              <td><span class="badge-status pending">Chờ duyệt</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Phòng cửa sổ lớn Tân Phú</div><div class="cell-sub">24 m² · 3,2 triệu/th</div></td>
              <td>Anh Kiệt</td>
              <td>22/08</td>
              <td><span class="badge-status new">Mới gửi</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="col-lg-5">
      <div class="panel mb-3">
        <div class="panel-head">
          <h3 class="panel-title">Người dùng gần đây</h3>
          <a class="panel-link" href="#">Xem tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Người dùng</th><th>Vai trò</th></tr></thead>
          <tbody>
            <tr><td><div class="avatar-sm mb-1">TH</div>Thanh Huyền</td><td>Người thuê</td></tr>
            <tr><td><div class="avatar-sm mb-1">MT</div>Minh Tuấn</td><td>Chủ trọ</td></tr>
            <tr><td><div class="avatar-sm mb-1">QK</div>Quốc Khánh</td><td>Người thuê</td></tr>
          </tbody>
        </table>
      </div>

      <div class="panel">
        <div class="panel-head">
          <h3 class="panel-title">Báo cáo cần xử lý</h3>
          <a class="panel-link" href="#">Xem tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Nội dung</th><th>Trạng thái</th></tr></thead>
          <tbody>
            <tr><td>Tin đăng sai thông tin diện tích</td><td><span class="badge-status overdue">Chưa xử lý</span></td></tr>
            <tr><td>Chủ trọ không phản hồi lịch xem</td><td><span class="badge-status pending">Đang xem xét</span></td></tr>
            <tr><td>Đánh giá spam trên tin đăng</td><td><span class="badge-status resolved">Đã xử lý</span></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
