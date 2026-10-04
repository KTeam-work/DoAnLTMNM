<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo phòng | Trọ Ơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .page-wrap { max-width: 960px; margin: 0 auto; padding: 30px 28px 70px; }
        .page-title { color: var(--green-dark); font-size: 26px; font-weight: 800; letter-spacing: -.6px; }
        .back-link { color: var(--muted); text-decoration: none; font-size: 13px; font-weight: 600; }
        .back-link:hover { color: var(--green); }
        .panel { background: #fff; border: 1px solid var(--border); border-radius: 18px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,.02); }
        .form-label { font-size: 12px; font-weight: 700; color: var(--text); }
        .form-control, .form-select { border-radius: 11px; border-color: var(--border); font-size: 13.5px; padding: 10px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 3px var(--green-soft); }
        .btn-brand { border: 0; background: var(--yellow); color: var(--green-dark); font-weight: 800; font-size: 13px; border-radius: 11px; padding: 11px 18px; }
        .btn-brand:hover { background: #ffd968; color: var(--green-dark); }
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
    <a href="{{ route('owner.rooms.index') }}" class="back-link">← Quay lại quản lý phòng</a>
    <h1 class="page-title my-3">Tạo phòng mới</h1>
    @if ($properties->isEmpty())
        <div class="alert alert-warning">Bạn chưa có khu trọ nào. Hãy tạo khu trọ trước khi thêm phòng.</div>
    @else
        <div class="panel">
        <form method="POST" action="{{ route('owner.rooms.store') }}">
            @csrf
            @if ($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Khu trọ</label><select name="property_id" class="form-select" required><option value="">Chọn khu trọ</option>@foreach ($properties as $property)<option value="{{ $property->id }}" @selected(old('property_id') == $property->id)>{{ $property->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Tên phòng</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Mã phòng</label><input name="room_code" value="{{ old('room_code') }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label">Trạng thái</label><select name="status" class="form-select"><option value="available">Còn trống</option><option value="maintenance">Bảo trì</option><option value="hidden">Ẩn</option></select></div>
                <div class="col-md-4"><label class="form-label">Diện tích (m²)</label><input name="area" type="number" min="0.01" step="0.01" value="{{ old('area') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Giá thuê</label><input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label">Tiền cọc</label><input name="deposit" type="number" min="0" step="0.01" value="{{ old('deposit') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Sức chứa</label><input name="max_people" type="number" min="1" value="{{ old('max_people', 1) }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label">Tầng</label><input name="floor" type="number" min="0" value="{{ old('floor') }}" class="form-control"></div>
                <div class="col-12"><label class="form-label">Mô tả</label><textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea></div>
                <div class="col-12"><button class="btn-brand">Lưu phòng</button></div>
            </div>
        </form>
        </div>
    @endif
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
