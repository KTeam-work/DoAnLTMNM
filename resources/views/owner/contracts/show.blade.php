<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết Hợp đồng | Trọ Ơi Admin</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <style>
        /* Đã xóa CSS body fix cứng để kế thừa màu nền chung (#f7f9f8) và font chữ từ styles.css */
        
        .panel-modern { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 1.5rem; margin-bottom: 1.5rem; }
        .badge-soft-success { background: #dcfce7; color: #16a34a; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
        .detail-label { font-size: 0.85rem; color: #64748b; margin-bottom: 0.25rem; }
        .detail-value { font-size: 1rem; font-weight: 600; color: #0f172a; }
        .table-minimal th { font-weight: 500; color: #64748b; font-size: 0.85rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
        .table-minimal td { padding: 0.75rem 0; border-bottom: 1px dashed #e2e8f0; }
    </style>
</head>
<body>

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
              <a class="dropdown-item active" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a>
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
<div class="page-wrap mt-4 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <a href="{{ url('/owner/contracts') }}" class="text-decoration-none text-muted">← Quay lại danh sách</a>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm px-3 shadow-none">Tải PDF</button>
            <button class="btn btn-outline-danger btn-sm px-3 shadow-none">Thanh lý HĐ</button>
        </div>
    </div>

    <div class="d-flex align-items-center gap-3 mb-4">
        <h2 class="fw-bold text-dark m-0">Hợp đồng #HD-2026-01</h2>
        <span class="badge-soft-success">Đang hiệu lực</span>
    </div>

    <div class="row">
        <!-- Cột trái: Thông tin phòng & Dịch vụ -->
        <div class="col-lg-8">
            <div class="panel-modern">
                <h5 class="fw-bold mb-4">Thông tin thuê</h5>
                <div class="row g-4">
                    <div class="col-md-4"><div class="detail-label">Phòng thuê</div><div class="detail-value text-primary">Phòng 12A - Nhà Q.7</div></div>
                    <div class="col-md-4"><div class="detail-label">Giá thuê</div><div class="detail-value text-danger">2.800.000 đ/tháng</div></div>
                    <div class="col-md-4"><div class="detail-label">Tiền cọc</div><div class="detail-value">2.800.000 đ</div></div>
                    <div class="col-md-4"><div class="detail-label">Ngày bắt đầu</div><div class="detail-value">01/02/2026</div></div>
                    <div class="col-md-4"><div class="detail-label">Ngày kết thúc</div><div class="detail-value">01/02/2027</div></div>
                    <div class="col-md-4"><div class="detail-label">Kỳ thanh toán</div><div class="detail-value">Mùng 5 hàng tháng</div></div>
                </div>
            </div>

            <div class="panel-modern">
                <h5 class="fw-bold mb-3">Dịch vụ & Tiêu thụ</h5>
                <table class="table w-100 table-minimal">
                    <thead><tr><th>Tên dịch vụ</th><th>Đơn giá</th><th>Cách tính</th></tr></thead>
                    <tbody>
                        <tr><td>⚡ Điện</td><td>3.500 đ</td><td>Theo đồng hồ (kWh)</td></tr>
                        <tr><td>💧 Nước</td><td>100.000 đ</td><td>Theo người</td></tr>
                        <tr><td>🌐 Rác & Wifi</td><td>80.000 đ</td><td>Cố định theo phòng</td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Cột phải: Thông tin khách thuê -->
        <div class="col-lg-4">
            <div class="panel-modern">
                <h5 class="fw-bold mb-3">Đại diện thuê phòng</h5>
                <div class="text-center mb-3">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary fw-bold mb-2" style="width:60px; height:60px; font-size: 24px;">TH</div>
                    <div class="fw-bold fs-5 text-dark">Thanh Huyền</div>
                </div>
                <hr class="border-light-subtle">
                <div class="mb-2"><div class="detail-label">Số điện thoại</div><div class="detail-value">0901 234 567</div></div>
                <div class="mb-2"><div class="detail-label">Số CMND / CCCD</div><div class="detail-value">079199012345</div></div>
                <div class="mb-2"><div class="detail-label">Ngày sinh</div><div class="detail-value">14/05/1998</div></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>