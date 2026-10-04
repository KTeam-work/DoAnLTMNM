<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết yêu cầu #TCK-{{ $maintenance->id }} | Trọ Ơi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .page-wrap{max-width:960px;margin:0 auto;padding:1.5rem 1.25rem 3rem;}
  .back-link{color:var(--muted);text-decoration:none;font-size:13px;font-weight:600;}
  .back-link:hover{color:var(--green);}
  .panel{background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.4rem;margin-bottom:1.2rem;}
  .panel h5{color:var(--green-dark);font-weight:800;font-size:1rem;margin-bottom:1rem;}
  .badge-status{display:inline-block;padding:.3rem .65rem;border-radius:99px;font-size:.74rem;font-weight:700;}
  .badge-status.pending{background:var(--yellow-light);color:#9b6a00;}
  .badge-status.processing{background:var(--blue-soft);color:var(--blue);}
  .badge-status.completed{background:var(--green-soft);color:var(--green);}
  .badge-status.rejected{background:#f0eadc;color:var(--muted);}
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
              <a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a>
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
              <a class="dropdown-item active" href="{{ route('owner.maintenance.index') }}">🛠️ Sửa chữa</a>
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
  <a href="{{ route('owner.maintenance.index') }}" class="back-link">← Quay lại danh sách sửa chữa</a>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 my-3">
    <h1 class="h4 fw-bold mb-0" style="color: var(--green-dark);">#TCK-{{ $maintenance->id }} — {{ $maintenance->title }}</h1>
    @php $statusLabels = ['pending' => 'Chờ xử lý', 'processing' => 'Đang sửa', 'completed' => 'Hoàn thành', 'rejected' => 'Đã từ chối']; @endphp
    <span class="badge-status {{ $maintenance->status }}">{{ $statusLabels[$maintenance->status] ?? $maintenance->status }}</span>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <div class="panel">
    <h5>Thông tin yêu cầu</h5>
    <div class="row g-3" style="font-size: .9rem;">
      <div class="col-md-6">Phòng: <strong>{{ $maintenance->room->name ?? '' }}</strong></div>
      <div class="col-md-6">Người thuê: <strong>{{ $maintenance->tenant->name ?? '' }}</strong> ({{ $maintenance->tenant->phone ?? '' }})</div>
      <div class="col-md-6">Mức độ: <strong>{{ ['low' => 'Thấp', 'medium' => 'Trung bình', 'high' => 'Cao', 'urgent' => 'Khẩn cấp'][$maintenance->priority] ?? $maintenance->priority }}</strong></div>
      <div class="col-md-6">Gửi lúc: {{ $maintenance->created_at->format('H:i d/m/Y') }}</div>
      @if($maintenance->contract)
      <div class="col-md-6">Hợp đồng: <strong>{{ $maintenance->contract->contract_code }}</strong></div>
      @endif
      <div class="col-12">Mô tả: {{ $maintenance->description }}</div>
      @if($maintenance->image)
      <div class="col-12">Ảnh đính kèm:<br><img src="{{ asset('storage/' . $maintenance->image) }}" alt="Ảnh hiện trường" style="max-width: 320px; border-radius: 12px; border: 1px solid var(--border);"></div>
      @endif
      @if($maintenance->owner_note)
      <div class="col-12">Ghi chú của bạn: {{ $maintenance->owner_note }}</div>
      @endif
    </div>
  </div>

  @if(in_array($maintenance->status, ['pending', 'processing']))
  <div class="panel">
    <h5>Cập nhật xử lý</h5>
    <form action="{{ route('owner.maintenance.status', $maintenance->id) }}" method="POST" class="row g-3">
      @csrf
      @method('PUT')
      <div class="col-md-5">
        <label class="form-label small fw-bold">Trạng thái mới</label>
        <select name="status" class="form-select" required>
          @if($maintenance->status === 'pending')
            <option value="processing">Đang sửa</option>
          @endif
          @if($maintenance->status === 'processing')
            <option value="completed">Hoàn thành</option>
          @endif
          <option value="rejected">Từ chối</option>
        </select>
      </div>
      <div class="col-md-7">
        <label class="form-label small fw-bold">Ghi chú cho người thuê</label>
        <input type="text" name="owner_note" value="{{ old('owner_note', $maintenance->owner_note) }}" class="form-control" placeholder="VD: Thợ sẽ qua lúc 17h hôm nay">
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-success">Lưu cập nhật</button>
      </div>
    </form>
  </div>
  @endif
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
