<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo Hợp Đồng | Trọ Ơi Admin</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <style>
        /* Đã xóa CSS body fix cứng để kế thừa màu nền chung (#f7f9f8) và font chữ từ styles.css */
        
        .panel-modern { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 1.5rem; margin-bottom: 1.5rem; }
        .form-label { font-weight: 500; color: #475569; font-size: 0.9rem; }
        .form-control, .form-select { border-radius: 8px; border-color: #cbd5e1; padding: 0.6rem 1rem; }
        .form-control:focus, .form-select:focus { box-shadow: none; border-color: #10b981; }
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
          <a class="app-nav-link " href="{{ url('/landlord') }}">Tổng quan</a>
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
    <div class="mb-3">
        <a href="{{ url('/owner/contracts') }}" class="text-decoration-none text-muted">← Quay lại danh sách</a>
    </div>
    
    <h2 class="fw-bold text-dark mb-4">Tạo hợp đồng mới</h2>

    <form action="#" method="POST">
        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin phòng -->
                <div class="panel-modern">
                    <h5 class="fw-bold mb-3">1. Thông tin thuê phòng</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Chọn nhà / khu trọ</label>
                            <select class="form-select shadow-none">
                                <option>Nhà trọ Q.7</option>
                                <option>Chung cư mini Thủ Đức</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chọn phòng trống</label>
                            <select class="form-select shadow-none">
                                <option>Phòng 102</option>
                                <option>Phòng 205</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giá thuê (VNĐ/tháng)</label>
                            <input type="number" class="form-control shadow-none" value="3000000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiền cọc (VNĐ)</label>
                            <input type="number" class="form-control shadow-none" value="3000000">
                        </div>
                    </div>
                </div>

                <!-- Thông tin thời gian -->
                <div class="panel-modern">
                    <h5 class="fw-bold mb-3">2. Thời hạn hợp đồng</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày lập hóa đơn</label>
                            <select class="form-select shadow-none">
                                <option>Mùng 1 hàng tháng</option>
                                <option>Mùng 5 hàng tháng</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Đại diện thuê -->
                <div class="panel-modern">
                    <h5 class="fw-bold mb-3">3. Người đại diện thuê</h5>
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control shadow-none" placeholder="Nhập tên khách...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control shadow-none" placeholder="0901...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số CMND / CCCD</label>
                        <input type="text" class="form-control shadow-none" placeholder="079...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end gap-2 mt-2">
            <a href="{{ url('/owner/contracts') }}" class="btn btn-light px-4 py-2 border shadow-none">Hủy bỏ</a>
            <button type="submit" class="btn btn-success px-4 py-2 shadow-none">Tạo hợp đồng</button>
        </div>
    </form>
</div>

</body>
</html>