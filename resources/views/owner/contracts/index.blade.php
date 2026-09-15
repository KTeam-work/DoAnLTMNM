<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Hợp đồng | Trọ Ơi Admin</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    
    <style>
        .stat-card-modern { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 1.5rem; transition: all 0.3s ease; box-shadow: 0 1px 3px rgba(0,0,0,0.02); height: 100%; }
        .stat-card-modern:hover { transform: translateY(-4px); box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08); }
        .icon-box { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        .bg-blue-soft { background: #e0f2fe; color: #0284c7; } 
        .bg-green-soft { background: #dcfce7; color: #16a34a; }
        .bg-orange-soft { background: #ffedd5; color: #ea580c; } 
        .bg-red-soft { background: #fee2e2; color: #ef4444; }
        .panel-modern { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 1.5rem; }
        .table-modern { width: 100%; border-collapse: separate; border-spacing: 0 8px; }
        .table-modern thead th { color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; border: none; padding: 0 1rem 0.5rem 1rem; }
        .table-modern tbody tr { background: #f8fafc; transition: all 0.2s ease; }
        .table-modern tbody tr:hover { background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05); }
        .table-modern td { vertical-align: middle; padding: 1rem; border: none; }
        .table-modern td:first-child { border-top-left-radius: 8px; border-bottom-left-radius: 8px; }
        .table-modern td:last-child { border-top-right-radius: 8px; border-bottom-right-radius: 8px; }
        .badge-soft-success { background: #dcfce7; color: #16a34a; font-weight: 600; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 0.85rem; }
        .badge-soft-warning { background: #ffedd5; color: #ea580c; font-weight: 600; padding: 6px 12px; border-radius: 6px; display: inline-block; font-size: 0.85rem; }
        .btn-action { border-radius: 6px; font-weight: 500; transition: all 0.2s; border: 1px solid #e2e8f0; color: #475569; background: #fff; padding: 6px 12px; font-size: 0.875rem; text-decoration: none; }
        .btn-action:hover { background: #0f172a; color: #fff; border-color: #0f172a; }
    </style>
</head>
<body>

<!-- NAVBAR -->
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
<!-- MAIN CONTENT -->
<div class="page-wrap mt-4 mb-5">
    
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold text-dark mb-1">Quản lý Hợp đồng</h2>
            <div class="page-desc text-muted">Quản lý danh sách hợp đồng thuê phòng của khách.</div>
        </div>
        <a href="{{ route('owner.contracts.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-medium shadow-sm text-decoration-none">+ Tạo hợp đồng mới</a>
    </div>

    <!-- THỐNG KÊ -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-blue-soft">📋</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Tổng hợp đồng</div>
                    <div class="fs-4 fw-bold text-dark">28</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-green-soft">✅</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Đang hiệu lực</div>
                    <div class="fs-4 fw-bold text-dark">25</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-orange-soft">⏳</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Sắp hết hạn</div>
                    <div class="fs-4 fw-bold text-dark">02</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-red-soft">🔒</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Đã thanh lý</div>
                    <div class="fs-4 fw-bold text-dark">01</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BẢNG DANH SÁCH HỢP ĐỒNG -->
    <div class="panel-modern">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark m-0 fs-5">Danh sách hợp đồng</h4>
            <input type="text" class="form-control form-control-sm w-25" placeholder="Tìm mã HĐ, tên khách...">
        </div>
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Mã HĐ</th>
                        <th>Phòng / Nhà</th>
                        <th>Khách đại diện</th>
                        <th>Thời hạn</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong class="text-primary">#HD-2026-01</strong></td>
                        <td>
                            <div class="fw-bold text-dark">Phòng 12A</div>
                            <div class="small text-muted">Nhà trọ Q.7</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">Thanh Huyền</div>
                            <div class="small text-muted">0901 234 567</div>
                        </td>
                        <td>
                            <div class="text-dark">01/02/2026</div>
                            <div class="small text-muted">Đến 01/02/2027</div>
                        </td>
                        <td><span class="badge-soft-success">Đang hiệu lực</span></td>
                        <td class="text-end">
                            <a href="{{ url('/owner/contracts/1') }}" class="btn-action">Xem</a>
                        </td>
                    </tr>
                    <tr>
                        <td><strong class="text-primary">#HD-2025-42</strong></td>
                        <td>
                            <div class="fw-bold text-dark">Phòng 105</div>
                            <div class="small text-muted">Chung cư mini Thủ Đức</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">Hoàng Minh</div>
                            <div class="small text-muted">0988 765 432</div>
                        </td>
                        <td>
                            <div class="text-dark">15/08/2025</div>
                            <div class="small text-muted">Đến 15/08/2026</div>
                        </td>
                        <td><span class="badge-soft-warning">Sắp hết hạn</span></td>
                        <td class="text-end">
                            <a href="{{ url('/owner/contracts/2') }}" class="btn-action">Xem</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>