<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý dịch vụ - Trọ Ơi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    {{-- CSS dùng chung --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        /* =====================================================
           PAGE RIÊNG - ĐỒNG BỘ VỚI styles.css
        ===================================================== */
        .property-box { background: #fff; border: 1px solid var(--border); border-radius: 18px; padding: 22px; margin-bottom: 26px; }
        .property-label { margin-bottom: 6px; color: var(--muted); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; }
        .property-name { color: var(--green-dark); font-size: 17px; font-weight: 800; }
        .property-address { margin-top: 4px; color: var(--muted); font-size: 12px; }
        .property-select { width: 100%; padding: 11px 13px; background: #fff; border: 1px solid var(--border); border-radius: 11px; color: var(--text); font-family: inherit; font-size: 13px; outline: none; transition: .2s; }
        .property-select:focus { border-color: var(--green); box-shadow: 0 0 0 3px rgba(32, 88, 79, .08); }

        /* STATS */
        .service-stat-card { background: #fff; border: 1px solid var(--border); border-radius: 18px; padding: 20px 22px; height: 100%; }
        .service-stat-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--green-soft); color: var(--green); display: flex; align-items: center; justify-content: center; font-size: 20px; }
        .service-stat-title { color: var(--muted); font-size: 12px; font-weight: 600; margin-bottom: 3px; }
        .service-stat-number { color: var(--green-dark); font-size: 25px; font-weight: 800; }

        /* SERVICE PANEL */
        .service-panel { margin-top: 26px; padding: 0; overflow: hidden; }
        .service-panel-header { padding: 22px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; }
        .service-panel-title { margin: 0; color: var(--green-dark); font-size: 16px; font-weight: 800; }
        .service-panel-description { margin-top: 4px; color: var(--muted); font-size: 12px; }
        .service-count { background: var(--yellow-light); color: #9b6a00; border-radius: 999px; padding: 6px 11px; font-size: 11px; font-weight: 800; }

        /* TABLE */
        .service-table { width: 100%; margin: 0; border-collapse: collapse; }
        .service-table thead th { background: #fcfaf3; color: var(--muted); font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; padding: 13px 18px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        .service-table tbody td { padding: 16px 18px; vertical-align: middle; border-bottom: 1px solid #f0eadc; color: var(--text); font-size: 12.5px; }
        .service-table tbody tr:last-child td { border-bottom: 0; }
        .service-table tbody tr { transition: .18s; }
        .service-table tbody tr:hover { background: #fdfbf5; }

        /* SERVICE INFO */
        .service-wrapper { display: flex; align-items: center; gap: 12px; }
        .service-icon { width: 42px; height: 42px; border-radius: 12px; background: var(--green-soft); display: flex; align-items: center; justify-content: center; font-size: 18px; flex: 0 0 auto; }
        .service-name { color: var(--green-dark); font-weight: 800; font-size: 13px; }
        .service-description { max-width: 300px; color: var(--muted); font-size: 11.5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .service-id { color: var(--muted); font-size: 12px; font-weight: 700; }
        .unit { color: var(--muted); }
        .price { color: var(--green-dark); font-weight: 800; }

        /* STATUS */
        .status-badge { display: inline-flex; align-items: center; gap: 5px; padding: 6px 10px; border-radius: 999px; font-size: 10.5px; font-weight: 800; white-space: nowrap; }
        .status-active { background: var(--green-soft); color: var(--green); }
        .status-inactive { background: var(--red-soft); color: var(--red); }

        /* ACTION */
        .action-group { display: flex; justify-content: flex-end; gap: 7px; white-space: nowrap; }
        .btn-action { display: inline-flex; align-items: center; justify-content: center; border-radius: 9px; padding: 7px 11px; font-size: 11.5px; font-weight: 800; text-decoration: none; transition: .2s; border: none; }
        .btn-edit { color: var(--green); background: var(--green-soft); border: 1px solid #d7e9df; }
        .btn-edit:hover { background: var(--green); color: #fff; }
        .btn-disable { color: #9b6a00; background: var(--yellow-light); border: 1px solid #eadcae; }
        .btn-disable:hover { background: var(--yellow); color: var(--green-dark); }

        /* RESPONSIVE */
        @media (max-width: 992px) { .property-select { margin-top: 15px; } .service-table { min-width: 950px; } }
        @media (max-width: 768px) { .service-panel-header { align-items: flex-start; gap: 12px; } .action-group { justify-content: flex-start; flex-wrap: wrap; } }
        @media (max-width: 576px) { .service-panel-header { flex-direction: column; } }
    </style>
</head>

<body>

    {{-- =====================================================
         NAVBAR ĐÃ ĐỒNG BỘ CHUẨN
    ====================================================== --}}
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
          <a class="app-nav-link" href="{{ url('/landlord') }}">Tổng quan</a>
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
              <a class="dropdown-item active" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a>
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
    {{-- =====================================================
         CONTENT
    ====================================================== --}}
    <main class="page-wrap">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý dịch vụ</h1>
                <div class="page-desc">Quản lý các dịch vụ và đơn giá áp dụng cho nhà trọ</div>
            </div>
            <a href="{{ url('/owner/services/create') }}" class="btn-brand">
                <span>＋</span> Thêm dịch vụ
            </a>
        </div>

        {{-- PROPERTY SELECT --}}
        <div class="property-box">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="property-label">Nhà trọ đang xem</div>
                    <div class="property-name">Nhà trọ Q.7</div>
                    <div class="property-address">123 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh</div>
                </div>
                <div class="col-md-5">
                    <select class="property-select">
                        <option>Nhà trọ Q.7</option>
                        <option>Chung cư mini Thủ Đức</option>
                        <option>Nhà trọ Bình Thạnh</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        <div class="row g-3">
            <div class="col-lg-4 col-md-6">
                <div class="service-stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="service-stat-icon">☷</div>
                        <div>
                            <div class="service-stat-title">Tổng dịch vụ</div>
                            <div class="service-stat-number">7</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="service-stat-icon">✓</div>
                        <div>
                            <div class="service-stat-title">Đang hoạt động</div>
                            <div class="service-stat-number">6</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="service-stat-icon" style="background:var(--red-soft); color:var(--red);">◷</div>
                        <div>
                            <div class="service-stat-title">Tạm ngưng</div>
                            <div class="service-stat-number">1</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SERVICE TABLE --}}
        <div class="panel service-panel">
            <div class="service-panel-header">
                <div>
                    <h2 class="service-panel-title">Dịch vụ - Nhà trọ Q.7</h2>
                    <div class="service-panel-description">Các dịch vụ được áp dụng cho nhà trọ này</div>
                </div>
                <span class="service-count">7 dịch vụ</span>
            </div>

            <div class="table-responsive">
                <table class="service-table">
                    <thead>
                        <tr>
                            <th style="width:70px;">ID</th>
                            <th>Tên dịch vụ</th>
                            <th>Mô tả</th>
                            <th>Đơn vị</th>
                            <th>Đơn giá áp dụng</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- SERVICE 1 --}}
                        <tr>
                            <td><span class="service-id">#1</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">⚡</div>
                                    <div class="service-name">Điện</div>
                                </div>
                            </td>
                            <td><div class="service-description">Tiền điện sử dụng hàng tháng</div></td>
                            <td><span class="unit">kWh</span></td>
                            <td><span class="price">3.500 ₫</span></td>
                            <td><span class="status-badge status-active">● Đang hoạt động</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ url('/owner/services/1/edit') }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Tạm ngưng</button>
                                </div>
                            </td>
                        </tr>

                        {{-- SERVICE 2 --}}
                        <tr>
                            <td><span class="service-id">#2</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">💧</div>
                                    <div class="service-name">Nước</div>
                                </div>
                            </td>
                            <td><div class="service-description">Tiền nước sử dụng hàng tháng</div></td>
                            <td><span class="unit">m³</span></td>
                            <td><span class="price">20.000 ₫</span></td>
                            <td><span class="status-badge status-active">● Đang hoạt động</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ url('/owner/services/2/edit') }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Tạm ngưng</button>
                                </div>
                            </td>
                        </tr>

                        {{-- SERVICE 3 --}}
                        <tr>
                            <td><span class="service-id">#3</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">📶</div>
                                    <div class="service-name">Internet</div>
                                </div>
                            </td>
                            <td><div class="service-description">Internet và Wifi dùng chung</div></td>
                            <td><span class="unit">tháng</span></td>
                            <td><span class="price">100.000 ₫</span></td>
                            <td><span class="status-badge status-active">● Đang hoạt động</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ url('/owner/services/3/edit') }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Tạm ngưng</button>
                                </div>
                            </td>
                        </tr>

                        {{-- SERVICE 4 --}}
                        <tr>
                            <td><span class="service-id">#4</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">🅿️</div>
                                    <div class="service-name">Giữ xe</div>
                                </div>
                            </td>
                            <td><div class="service-description">Phí gửi xe hàng tháng</div></td>
                            <td><span class="unit">chiếc</span></td>
                            <td><span class="price">100.000 ₫</span></td>
                            <td><span class="status-badge status-active">● Đang hoạt động</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ url('/owner/services/4/edit') }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Tạm ngưng</button>
                                </div>
                            </td>
                        </tr>

                        {{-- SERVICE 5 --}}
                        <tr>
                            <td><span class="service-id">#5</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">🧹</div>
                                    <div class="service-name">Vệ sinh</div>
                                </div>
                            </td>
                            <td><div class="service-description">Phí vệ sinh khu vực chung</div></td>
                            <td><span class="unit">tháng</span></td>
                            <td><span class="price">50.000 ₫</span></td>
                            <td><span class="status-badge status-active">● Đang hoạt động</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ url('/owner/services/5/edit') }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Tạm ngưng</button>
                                </div>
                            </td>
                        </tr>

                        {{-- SERVICE 6 --}}
                        <tr>
                            <td><span class="service-id">#6</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">🗑️</div>
                                    <div class="service-name">Rác</div>
                                </div>
                            </td>
                            <td><div class="service-description">Phí thu gom rác sinh hoạt</div></td>
                            <td><span class="unit">tháng</span></td>
                            <td><span class="price">30.000 ₫</span></td>
                            <td><span class="status-badge status-active">● Đang hoạt động</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ url('/owner/services/6/edit') }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Tạm ngưng</button>
                                </div>
                            </td>
                        </tr>

                        {{-- SERVICE 7 --}}
                        <tr>
                            <td><span class="service-id">#7</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">❄️</div>
                                    <div class="service-name">Máy lạnh</div>
                                </div>
                            </td>
                            <td><div class="service-description">Phí sử dụng và bảo trì máy lạnh</div></td>
                            <td><span class="unit">tháng</span></td>
                            <td><span class="price">150.000 ₫</span></td>
                            <td><span class="status-badge status-inactive">● Tạm ngưng</span></td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('owner.services.edit', ['id' => 7]) }}" class="btn-action btn-edit">✎ Sửa</a>
                                    <button type="button" class="btn-action btn-disable">Kích hoạt</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- BOOTSTRAP JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>