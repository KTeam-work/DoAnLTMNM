<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý phòng | Trọ Ơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .page-wrap { max-width: 1360px; margin: 0 auto; padding: 30px 28px 70px; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 26px; }
        .page-title { color: var(--green-dark); font-size: 26px; font-weight: 800; letter-spacing: -.6px; margin: 0; }
        .page-desc { color: var(--muted); font-size: 13px; margin-top: 6px; }
        .btn-brand { border: 0; background: var(--yellow); color: var(--green-dark); font-weight: 800; font-size: 13px; border-radius: 11px; padding: 11px 18px; white-space: nowrap; text-decoration: none; display: inline-flex; align-items: center; }
        .btn-brand:hover { background: #ffd968; color: var(--green-dark); }
        .stat-card { background: #fff; border: 1px solid var(--border); border-radius: 18px; padding: 20px 22px; height: 100%; }
        .stat-label { font-size: 12px; color: var(--muted); font-weight: 600; }
        .stat-value { font-size: 26px; font-weight: 800; color: var(--green-dark); }
        .panel { background: #fff; border: 1px solid var(--border); border-radius: 18px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,.02); }
        .data-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .data-table thead th { text-align: left; color: var(--muted); font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; padding: 0 12px 12px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        .data-table tbody td { padding: 14px 12px; border-bottom: 1px solid #f0eadc; vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: 0; }
        .badge-status { display: inline-block; padding: 5px 10px; border-radius: 999px; font-size: 10.5px; font-weight: 800; }
        .badge-status.available { background: var(--green-soft); color: var(--green); }
        .badge-status.rented { background: var(--blue-soft); color: var(--blue); }
        .badge-status.other { background: #f0eadc; color: var(--muted); }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>
    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item">
          <a class="app-nav-link" href="{{ route('landlord.home') }}">Tổng quan</a>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Nhà &amp; Phòng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
            <li>
              <a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a>
            </li>
            <li>
              <a class="dropdown-item active" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a>
            </li>
          </ul>
        </li>
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
              <a class="dropdown-item" href="{{ route('owner.payments.index') }}">💰 Giao dịch</a>
            </li>
          </ul>
        </li>
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

<main class="page-wrap">
    <div class="page-header">
        <div>
            <h1 class="page-title">Quản lý phòng</h1>
            <div class="page-desc">Các phòng thuộc khu trọ của bạn.</div>
        </div>
        <a class="btn-brand" href="{{ route('owner.rooms.create') }}">+ Thêm phòng</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="stat-label">Tổng số phòng</div><div class="stat-value">{{ $rooms->count() }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="stat-label">Còn trống</div><div class="stat-value" style="color: var(--green);">{{ $rooms->where('status', 'available')->count() }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="stat-label">Đang thuê</div><div class="stat-value" style="color: var(--blue);">{{ $rooms->where('status', 'rented')->count() }}</div></div></div>
        <div class="col-6 col-lg-3"><div class="stat-card"><div class="stat-label">Bảo trì</div><div class="stat-value" style="color: #9b6a00;">{{ $rooms->where('status', 'maintenance')->count() }}</div></div></div>
    </div>

    <div class="panel">
        <div class="table-responsive">
            <table class="data-table">
                <thead><tr><th>Phòng</th><th>Khu trọ</th><th>Diện tích</th><th>Giá thuê</th><th>Sức chứa</th><th>Trạng thái</th></tr></thead>
                <tbody>
                @forelse ($rooms as $room)
                    <tr>
                        <td><strong>{{ $room->name }}</strong><br><small class="text-muted">{{ $room->room_code ?: 'Chưa có mã' }}</small></td>
                        <td>{{ $room->property->name }}</td>
                        <td>{{ $room->area }} m²</td>
                        <td><strong>{{ number_format((float) $room->price) }} đ/tháng</strong></td>
                        <td>{{ $room->max_people }} người</td>
                        <td><span class="badge-status {{ in_array($room->status, ['available', 'rented']) ? $room->status : 'other' }}">{{ ['available' => 'Còn trống', 'rented' => 'Đang thuê', 'maintenance' => 'Bảo trì', 'pending' => 'Chờ duyệt', 'hidden' => 'Đã ẩn'][$room->status] ?? $room->status }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-5">Chưa có phòng nào. Hãy tạo phòng đầu tiên.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
