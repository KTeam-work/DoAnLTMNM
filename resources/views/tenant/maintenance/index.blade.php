<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Quản lý Sự cố</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .skeleton-mode .hide-on-skeleton { opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box { background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; color: transparent !important; border-color: transparent !important; pointer-events: none; }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .animate-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
  .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
  
  /* Enterprise Ticket Card */
  .ticket-card { background: #fff; border: 1px solid #f0f0f0; border-radius: 16px; padding: 20px; transition: all 0.3s ease; margin-bottom: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); display: flex; align-items: stretch; gap: 20px; position: relative; overflow: hidden; }
  .ticket-card::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #e0e0e0; transition: 0.3s; }
  .ticket-card.priority-urgent::before { background: var(--red); }
  .ticket-card.priority-normal::before { background: var(--blue); }
  .ticket-card:hover { transform: translateY(-3px); box-shadow: 0 12px 24px rgba(32, 88, 79, 0.08); border-color: var(--green-soft); }
  
  .ticket-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0; }
  .ticket-icon.dien-nuoc { background: var(--blue-soft); color: var(--blue); }
  .ticket-icon.dien-lanh { background: var(--green-soft); color: var(--green); }
  .ticket-icon.noi-that { background: var(--yellow-light); color: #9b6a00; }

  /* Summary Metrics */
  .metric-card { background: #fff; border: 1px solid #f0f0f0; border-radius: 16px; padding: 16px 20px; display: flex; align-items: center; gap: 16px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); }
  .metric-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 18px; }

  .filter-tabs { display: flex; gap: 12px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 4px; }
  .filter-tab { padding: 8px 16px; border-radius: 99px; font-size: 13px; font-weight: 700; color: var(--muted); background: #fff; border: 1px solid #e0e0e0; text-decoration: none; transition: 0.2s; white-space: nowrap; }
  .filter-tab:hover, .filter-tab.active { background: var(--green-dark); color: #fff; border-color: var(--green-dark); }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('tenant.home') }}">Trọ <span>Ơi</span></a>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.home') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('rooms.index') }}">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('favorites.index') }}">Yêu thích</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('appointments.index') }}">Lịch xem phòng</a></li>
         <li class="nav-item">
          <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a></li>
        </li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
      </ul>
     <div class="navbar-actions skeleton-box">
        <div class="hide-on-skeleton d-flex align-items-center gap-3">
          <!-- Thêm text-decoration-none vào class -->
          <a href="{{ route('tenant.notifications.index') }}" class="notif-btn text-decoration-none">
            🔔<span class="notif-dot"></span>
          </a>
          
          <!-- Thêm text-decoration-none vào class -->
          <a href="#" class="user-chip text-decoration-none">
            <span class="user-avatar">TH</span>
            <span class="user-meta">
              <span class="user-name d-block" style="text-decoration: none;">Thanh Huyền</span>
              <span class="user-role">Người thuê</span>
            </span>
            <span class="caret">▾</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="page-wrap" style="padding-top: 30px; max-width: 1000px;">
  
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" role="alert" style="background-color: #d1e7dd; color: #0f5132; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <strong>✓ Đã tiếp nhận!</strong> {{ session('success') }}
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Trung tâm Hỗ trợ & Sửa chữa</span></h2>
      <div class="text-muted mt-2 skeleton-box"><span class="hide-on-skeleton">Quản lý và theo dõi tiến độ khắc phục sự cố tại phòng của bạn.</span></div>
    </div>
    <div class="skeleton-box rounded">
      <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-brand px-4 py-2 hide-on-skeleton shadow-sm">
        + Tạo Ticket Mới
      </a>
    </div>
  </div>

  <!-- Metrics Dashboard -->
  @php
    $urgentCount = $requests->whereIn('priority', ['high', 'urgent'])->whereIn('status', ['pending', 'processing'])->count();
    $processingCount = $requests->where('status', 'processing')->count();
    $doneCount = $requests->where('status', 'completed')->count();
    $categoryLabels = ['dien_nuoc' => 'Điện / Nước', 'dien_lanh' => 'Thiết bị điện lạnh', 'noi_that' => 'Nội thất', 'khac' => 'Vấn đề khác'];
    $statusLabels = ['pending' => '⏳ Đang chờ xác nhận', 'processing' => '🔧 Đang xử lý', 'completed' => '✅ Hoàn thành', 'rejected' => '✖ Đã từ chối'];
  @endphp
  <div class="row g-3 mb-4 animate-up delay-2">
    <div class="col-md-4">
      <div class="metric-card skeleton-box">
        <div class="metric-icon hide-on-skeleton" style="background: var(--red-soft); color: var(--red);">🚨</div>
        <div class="hide-on-skeleton">
          <div class="text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Cần xử lý gấp</div>
          <div style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ $urgentCount }} <span style="font-size: 13px; color: var(--muted); font-weight: 500;">yêu cầu</span></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card skeleton-box">
        <div class="metric-icon hide-on-skeleton" style="background: var(--blue-soft); color: var(--blue);">🔧</div>
        <div class="hide-on-skeleton">
          <div class="text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Đang xử lý</div>
          <div style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ $processingCount }} <span style="font-size: 13px; color: var(--muted); font-weight: 500;">yêu cầu</span></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card skeleton-box">
        <div class="metric-icon hide-on-skeleton" style="background: var(--green-soft); color: var(--green);">✓</div>
        <div class="hide-on-skeleton">
          <div class="text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Đã hoàn thành</div>
          <div style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ $doneCount }} <span style="font-size: 13px; color: var(--muted); font-weight: 500;">yêu cầu</span></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Tabs -->
  <div class="filter-tabs animate-up delay-3 skeleton-box rounded-pill border-0 p-0 mb-4">
    <a href="{{ route('tenant.maintenance.index') }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'all' ? 'active' : '' }}">Tất cả ({{ $requests->count() }})</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'pending']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'pending' ? 'active' : '' }}">Chờ xử lý</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'processing']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'processing' ? 'active' : '' }}">Đang xử lý</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'completed']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'completed' ? 'active' : '' }}">Hoàn thành</a>
  </div>

  <!-- Ticket List -->
  <div class="animate-up delay-3">

    @forelse($requests as $ticket)
    <div class="ticket-card {{ in_array($ticket->priority, ['high', 'urgent']) ? 'priority-urgent' : 'priority-normal' }} skeleton-box">
      <div class="ticket-icon {{ $ticket->category === 'dien_lanh' ? 'dien-lanh' : ($ticket->category === 'noi_that' ? 'noi-that' : 'dien-nuoc') }} hide-on-skeleton">{{ $ticket->category === 'dien_lanh' ? '❄️' : ($ticket->category === 'noi_that' ? '🚪' : '💧') }}</div>
      <div class="flex-grow-1 hide-on-skeleton d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge {{ in_array($ticket->priority, ['high', 'urgent']) ? 'bg-danger text-white' : 'bg-secondary bg-opacity-10 text-secondary' }} rounded-1" style="font-size: 10px; letter-spacing: 0.5px;">{{ strtoupper($ticket->priority) }}</span>
            <span class="text-muted" style="font-size: 12px; font-weight: 600;">#TCK-{{ $ticket->id }} • {{ $ticket->created_at->format('H:i, d/m') }}</span>
          </div>
          <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">{{ $ticket->title }}</h5>
          <p class="text-muted mb-0" style="font-size: 13px;">Khu vực: {{ $categoryLabels[$ticket->category] ?? 'Khác' }} • Phòng {{ $ticket->room->name ?? '' }}{{ $ticket->contract ? ' • HĐ ' . $ticket->contract->contract_code : '' }}</p>
        </div>
        <div class="text-end d-flex flex-column align-items-end">
          <span class="badge-status {{ $ticket->status }} px-3 py-2 rounded-pill mb-2" style="font-size: 11.5px; font-weight: 700;">{{ $statusLabels[$ticket->status] ?? $ticket->status }}</span>
          <a href="{{ route('tenant.maintenance.show', $ticket->id) }}" class="text-decoration-none fw-bold" style="font-size: 13px; color: var(--green);">Xem chi tiết →</a>
        </div>
      </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">
      <div style="font-size: 40px;">🔧</div>
      <div class="fw-bold mt-2">Chưa có yêu cầu nào</div>
      <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-brand px-4 py-2 mt-3">+ Tạo yêu cầu mới</a>
    </div>
    @endforelse

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 600); 
  });
</script>
</body>
</html>