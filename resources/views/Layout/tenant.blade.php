<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Trang chủ người thuê</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body>

<!-- NAVBAR NGANG — TENANT -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="tenant.html">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link active" href="tenant.html">Trang chủ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#rooms">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Yêu thích</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ url('/tenant/invoices/index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
      </ul>

      <div class="navbar-actions">
        <button class="notif-btn">🔔<span class="notif-dot"></span></button>
        <a href="#" class="user-chip">
          <span class="user-avatar">TH</span>
          <span class="user-meta">
            <span class="user-name d-block">Thanh Huyền</span>
            <span class="user-role">Người thuê</span>
          </span>
          <span class="caret">▾</span>
        </a>
      </div>
    </div>
  </div>
</nav>

<!-- HERO + SEARCH -->
<section class="hero">
  <div class="hero-inner">
    <div class="hero-kicker">👋 CHÀO HUYỀN, TÌM PHÒNG MỚI HÔM NAY?</div>
    <h1>Tìm phòng phù hợp,<br><span class="highlight">ở đúng nơi bạn muốn.</span></h1>
    <p class="hero-desc">Khám phá hàng trăm phòng trọ, căn hộ và nhà cho thuê. Lọc theo khu vực, mức giá, diện tích và tiện ích để tìm đúng nơi bạn cần.</p>

    <div class="search-panel">
      <div class="row g-0 align-items-center">
        <div class="col-lg-3">
          <div class="search-field">
            <span class="field-label">Bạn muốn tìm ở đâu?</span>
            <div class="field-value">📍 TP. Hồ Chí Minh</div>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="search-field">
            <span class="field-label">Khoảng giá</span>
            <div class="field-value field-muted">Tất cả mức giá</div>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="search-field">
            <span class="field-label">Diện tích</span>
            <div class="field-value field-muted">Tất cả</div>
          </div>
        </div>
        <div class="col-lg-2">
          <div class="search-field">
            <span class="field-label">Tiện ích</span>
            <div class="field-value field-muted">Tất cả</div>
          </div>
        </div>
        <div class="col-lg-3 p-2">
          <button class="search-btn">🔎 Tìm kiếm phòng</button>
        </div>
      </div>
    </div>

    <div class="quick-filters">
      <span class="quick-filter">≤ 2 triệu</span>
      <span class="quick-filter">2 - 3 triệu</span>
      <span class="quick-filter">3 - 5 triệu</span>
      <span class="quick-filter">Có máy lạnh</span>
      <span class="quick-filter">Có gác</span>
      <span class="quick-filter">Có ban công</span>
      <span class="quick-filter">Nuôi thú cưng</span>
    </div>
  </div>
</section>

<div class="page-wrap" id="rooms">
  <div class="page-header">
    <div>
      <h2 class="page-title">Phòng gợi ý cho bạn</h2>
      <div class="page-desc">Dựa trên khu vực và mức giá bạn đã tìm gần đây.</div>
    </div>
    <a class="section-link" href="#">Xem tất cả →</a>
  </div>

  <div class="row g-3">
    <div class="col-md-6 col-lg-4">
      <div class="room-card">
        <button class="fav-btn">♥</button>
        <img class="room-img" src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80" alt="Phòng trọ">
        <div class="room-body">
          <div class="room-badges">
            <span class="badge-room hot">🔥 HOT</span>
            <span class="badge-room">Đã xác thực</span>
          </div>
          <div class="room-title">Phòng trọ máy lạnh gần Q.7</div>
          <div class="room-address">Đường Nguyễn Thị Thập, Quận 7, TP.HCM</div>
          <div class="room-stats">
            <span>📐 22 m²</span>
            <span>❄️ Máy lạnh</span>
            <span>🚗 Bãi xe</span>
          </div>
          <div class="room-bottom">
            <div class="room-price">2,8 triệu <small>/ tháng</small></div>
            <button class="detail-btn">Xem phòng</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-4">
      <div class="room-card">
        <button class="fav-btn">♥</button>
        <img class="room-img" src="https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1000&q=80" alt="Căn hộ">
        <div class="room-body">
          <div class="room-badges">
            <span class="badge-room">Đã xác thực</span>
          </div>
          <div class="room-title">Căn hộ mini đầy đủ nội thất</div>
          <div class="room-address">Phường An Phú, TP. Thủ Đức, TP.HCM</div>
          <div class="room-stats">
            <span>📐 35 m²</span>
            <span>🛋️ Full nội thất</span>
            <span>🚗 Bãi xe</span>
          </div>
          <div class="room-bottom">
            <div class="room-price">6,8 triệu <small>/ tháng</small></div>
            <button class="detail-btn">Xem phòng</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-6 col-lg-4">
      <div class="room-card">
        <button class="fav-btn">♥</button>
        <img class="room-img" src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1000&q=80" alt="Phòng">
        <div class="room-body">
          <div class="room-badges">
            <span class="badge-room hot">🔥 HOT</span>
            <span class="badge-room">Đã xác thực</span>
          </div>
          <div class="room-title">Phòng cửa sổ lớn, giờ giấc tự do</div>
          <div class="room-address">Phường Tân Thành, Tân Phú, TP.HCM</div>
          <div class="room-stats">
            <span>📐 24 m²</span>
            <span>🪟 Cửa sổ</span>
            <span>🕐 Tự do</span>
          </div>
          <div class="room-bottom">
            <div class="room-price">3,2 triệu <small>/ tháng</small></div>
            <button class="detail-btn">Xem phòng</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mt-1">
    <div class="col-lg-6">
      <div class="panel">
        <div class="panel-head">
          <h3 class="panel-title">Lịch xem phòng sắp tới</h3>
          <a class="panel-link" href="#">Xem tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Phòng</th><th>Ngày giờ</th><th>Trạng thái</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="cell-title">Phòng trọ máy lạnh Q.7</div><div class="cell-sub">Chủ nhà: Anh Tuấn</div></td>
              <td>28/08 · 15:00</td>
              <td><span class="badge-status pending">Chờ xác nhận</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Căn hộ mini An Phú</div><div class="cell-sub">Chủ nhà: Chị Lan</div></td>
              <td>30/08 · 09:30</td>
              <td><span class="badge-status occupied">Đã xác nhận</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="col-lg-6">
      <div class="panel">
        <div class="panel-head">
          <h3 class="panel-title">Hóa đơn gần đây</h3>
          <a class="panel-link" href="#">Xem tất cả</a>
        </div>
        <table class="data-table">
          <thead><tr><th>Kỳ hóa đơn</th><th>Số tiền</th><th>Trạng thái</th></tr></thead>
          <tbody>
            <tr>
              <td><div class="cell-title">Tháng 08/2026</div><div class="cell-sub">Phòng 12A, Q.7</div></td>
              <td>3.450.000 đ</td>
              <td><span class="badge-status occupied">Đã thanh toán</span></td>
            </tr>
            <tr>
              <td><div class="cell-title">Tháng 07/2026</div><div class="cell-sub">Phòng 12A, Q.7</div></td>
              <td>3.200.000 đ</td>
              <td><span class="badge-status overdue">Quá hạn</span></td>
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
