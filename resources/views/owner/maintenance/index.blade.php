<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Quản lý Sửa chữa</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<style>
  :root{
    --forest: #20584f;
    --forest-deep: #163e37;
    --forest-tint: #eaf3ef;
    --amber: #b5792a;
    --amber-tint: #fbf1e3;
    --cream: #faf8f3;
    --line: #e7e2d6;
    --ink: #23281f;
    --ink-muted: #6c7266;
    --red: #c65b4a;
    --red-tint: #fdf0ed;
    --blue: #356d9e;
    --blue-tint: #e8f2ff;
    --green: #20584f;
    --green-tint: #eaf3ef;
  }

  body{ background: var(--cream); font-family: 'Be Vietnam Pro', sans-serif; color: var(--ink); }
  .mono-num{ font-family: 'JetBrains Mono', monospace; font-feature-settings: "tnum" 1; font-variant-numeric: tabular-nums; }

  /* ---- Skeleton loading ---- */
  .skeleton-mode .hide-on-skeleton{ opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box{ background: linear-gradient(90deg, #e9e5d8 25%, #f3f1e9 50%, #e9e5d8 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 10px; color: transparent !important; border-color: transparent !important; pointer-events: none; }
  @keyframes shimmer{ 0%{ background-position: 200% 0; } 100%{ background-position: -200% 0; } }

  /* ---- Navbar ---- */
  .app-navbar{ background: var(--forest); min-height: 74px; box-shadow: 0 3px 14px rgba(20,55,47,.12); position: sticky; top: 0; z-index: 1000; }
  .app-navbar .container-fluid{ max-width: 1360px; padding: 0 28px; }
  .logo{ color: #fff; text-decoration: none; font-size: 26px; font-weight: 800; letter-spacing: -1.2px; white-space: nowrap; }
  .logo span{ color: #f5c84b; }
  .app-nav-link{ color: rgba(255,255,255,.82) !important; font-size: 13.5px; font-weight: 600; padding: 9px 13px !important; border-radius: 10px; transition: .2s; white-space: nowrap; text-decoration: none; }
  .app-nav-link:hover, .app-nav-link.active{ color: var(--forest) !important; background: #fff; }
  .navbar-actions{ display: flex; align-items: center; gap: 6px; }
  .notif-btn{ position: relative; width: 40px; height: 40px; border-radius: 11px; border: 1px solid rgba(255,255,255,.18); background: rgba(255,255,255,.08); color: #fff; font-size: 16px; display: flex; align-items: center; justify-content: center; text-decoration: none; }
  .notif-dot{ position: absolute; top: 7px; right: 7px; width: 8px; height: 8px; border-radius: 50%; background: #f5c84b; border: 2px solid var(--forest); }
  .user-chip{ display: flex; align-items: center; gap: 9px; padding: 6px 12px 6px 6px; border-radius: 12px; background: rgba(255,255,255,.08); border: 1px solid rgba(255,255,255,.16); color: #fff; text-decoration: none; margin-left: 6px; }
  .user-avatar{ width: 32px; height: 32px; border-radius: 9px; background: #f5c84b; color: var(--forest-deep); font-weight: 800; font-size: 13px; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; }
  .user-meta{ line-height: 1.2; }
  .user-name{ font-size: 12.5px; font-weight: 700; color: #fff; }
  .user-role{ font-size: 10px; color: rgba(255,255,255,.65); }
  .caret{ font-size: 9px; color: rgba(255,255,255,.6); margin-left: 2px; }
  @media(max-width: 991px){ .app-nav-link{ margin: 2px 0; } }

  /* ---- Page header ---- */
  .page-wrap{ max-width: 1280px; margin: 0 auto; padding: 1.5rem 1.5rem 3rem; }
  .page-title{ font-size: 1.5rem; font-weight: 700; letter-spacing: -0.01em; margin: 0; color: var(--forest-deep); }
  .page-desc{ color: var(--ink-muted); font-size: 0.92rem; }

  .btn-brand{ background: var(--forest); color: #fff; border: none; border-radius: 10px; padding: 0.55rem 1.1rem; font-weight: 600; font-size: 0.88rem; transition: background 0.15s ease; }
  .btn-brand:hover{ background: var(--forest-deep); color: #fff; }
  .btn-outline-ledger{ background: #fff; color: var(--ink); border: 1px solid var(--line); border-radius: 10px; padding: 0.55rem 1.1rem; font-weight: 600; font-size: 0.88rem; transition: border-color 0.15s ease, color 0.15s ease; }
  .btn-outline-ledger:hover{ border-color: var(--forest); color: var(--forest); }

  /* ---- Alert strip ---- */
  .alert-strip{
    background: linear-gradient(120deg, var(--red) 0%, #a8493a 100%);
    border-radius: 16px; padding: 1.15rem 1.5rem; color: #fff;
    display: flex; align-items: center; gap: 1.25rem; flex-wrap: wrap;
  }
  .alert-strip.calm{ background: linear-gradient(120deg, var(--forest) 0%, var(--forest-deep) 100%); }
  .alert-icon{ width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,.16); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0; }
  .alert-text .alert-headline{ font-weight: 700; font-size: 1.02rem; }
  .alert-text .alert-sub{ font-size: 0.82rem; color: rgba(255,255,255,.8); }
  .alert-breakdown{ display: flex; gap: 1.5rem; margin-left: auto; flex-wrap: wrap; }
  .alert-breakdown .figure{ text-align: right; }
  .alert-breakdown .figure .n{ font-size: 1.1rem; font-weight: 700; }
  .alert-breakdown .figure .l{ font-size: 0.72rem; color: rgba(255,255,255,.75); }

  /* ---- Filter bar ---- */
  .filter-bar{ display: flex; align-items: center; gap: 0.6rem; flex-wrap: wrap; }
  .chip-filter{ border: 1px solid var(--line); background: #fff; color: var(--ink-muted); border-radius: 99px; padding: 0.4rem 0.95rem; font-size: 0.82rem; font-weight: 600; transition: 0.15s; cursor: pointer; }
  .chip-filter:hover{ border-color: var(--forest); color: var(--forest); }
  .chip-filter.active{ background: var(--forest); border-color: var(--forest); color: #fff; }
  .form-control, .form-select{ border-color: var(--line); font-size: 0.85rem; }
  .form-control:focus, .form-select:focus{ border-color: var(--forest); box-shadow: 0 0 0 3px rgba(32,88,79,0.12); }
  .link-muted{ color: var(--ink-muted); font-size: 0.82rem; font-weight: 600; text-decoration: none; margin-left: auto; cursor: pointer; }
  .link-muted:hover{ color: var(--forest); }

  /* ---- Board ---- */
  .board{ display: flex; gap: 1.1rem; align-items: flex-start; overflow-x: auto; padding-bottom: 0.5rem; }
  .board-col{ flex: 1 1 0; min-width: 270px; }
  .board-col-head{ display: flex; align-items: center; gap: 0.5rem; padding: 0 0.15rem 0.75rem; border-top: 3px solid var(--col-accent, var(--line)); padding-top: 0.7rem; }
  .board-col-head .title{ font-weight: 700; font-size: 0.92rem; }
  .board-col-head .count{ background: var(--col-accent-tint, var(--line)); color: var(--col-accent, var(--ink-muted)); font-size: 0.74rem; font-weight: 700; padding: 0.12rem 0.5rem; border-radius: 99px; }

  .board-col.pending{ --col-accent: var(--amber); --col-accent-tint: var(--amber-tint); }
  .board-col.in-progress{ --col-accent: var(--blue); --col-accent-tint: var(--blue-tint); }
  .board-col.resolved{ --col-accent: var(--forest); --col-accent-tint: var(--forest-tint); }

  .board-col-body{ display: flex; flex-direction: column; gap: 0.65rem; min-height: 80px; }

  /* ---- Ticket card ---- */
  .ticket-card{ background: #fff; border: 1px solid var(--line); border-left: 4px solid var(--pri-color, var(--line)); border-radius: 10px; padding: 0.85rem 0.95rem; transition: box-shadow 0.15s ease, transform 0.15s ease; }
  .ticket-card:hover{ box-shadow: 0 6px 16px rgba(35,40,31,0.07); }
  .ticket-card.pri-high{ --pri-color: var(--red); }
  .ticket-card.pri-medium{ --pri-color: var(--amber); }
  .ticket-card.pri-low{ --pri-color: var(--blue); }
  .ticket-top{ display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.35rem; }
  .ticket-id{ font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: var(--ink-muted); }
  .ticket-pri{ font-size: 0.7rem; font-weight: 700; color: var(--pri-color, var(--ink-muted)); display: flex; align-items: center; gap: 3px; }
  .ticket-title{ font-weight: 600; font-size: 0.92rem; margin-bottom: 0.15rem; }
  .ticket-room{ color: var(--ink-muted); font-size: 0.78rem; margin-bottom: 0.6rem; }
  .ticket-bottom{ display: flex; align-items: center; justify-content: space-between; padding-top: 0.55rem; border-top: 1px dashed var(--line); }
  .ticket-assignee{ font-size: 0.76rem; color: var(--ink-muted); display: flex; align-items: center; gap: 5px; }
  .ticket-assignee.has-name{ color: var(--forest); font-weight: 600; }
  .ticket-date{ font-family: 'JetBrains Mono', monospace; font-size: 0.7rem; color: var(--ink-muted); }
  .ticket-actions{ display: flex; gap: 4px; flex-shrink: 0; }
  .btn-icon{ width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; border-radius: 8px; transition: 0.15s; border: 1px solid var(--line); background: #fff; color: var(--ink-muted); font-size: 0.78rem; cursor: pointer; }
  .btn-icon:hover{ background: var(--forest-tint); color: var(--forest); border-color: var(--forest); }
  .btn-icon.success:hover{ background: var(--green-tint); color: var(--green); border-color: var(--green); }
  .btn-icon.danger:hover{ background: var(--red-tint); color: var(--red); border-color: var(--red); }
  .btn-icon.primary:hover{ background: var(--blue-tint); color: var(--blue); border-color: var(--blue); }
  .board-empty{ text-align: center; color: var(--ink-muted); font-size: 0.82rem; padding: 1.5rem 0.5rem; border: 1px dashed var(--line); border-radius: 10px; }

  /* ---- Archive ---- */
  .archive-tray{ display: none; }
  .archive-tray.open{ display: block; }
  .archive-row{ display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 0.2rem; border-bottom: 1px solid var(--line); font-size: 0.83rem; color: var(--ink-muted); }
  .archive-row .archive-title{ color: var(--ink); font-weight: 600; }

  /* ---- Modal ---- */
  .modal-content{ border-radius: 16px; border: none; box-shadow: 0 20px 50px rgba(0,0,0,0.1); }
  .modal-header{ border-bottom: 1px solid var(--line); padding: 1.25rem 1.5rem; }
  .modal-footer{ border-top: 1px solid var(--line); padding: 1rem 1.5rem; }
  .badge-priority{ display: inline-flex; align-items: center; gap: 4px; padding: 3px 10px; border-radius: 99px; font-size: 10px; font-weight: 700; }
  .badge-priority.high{ background: var(--red-tint); color: var(--red); }
  .badge-priority.medium{ background: var(--amber-tint); color: var(--amber); }
  .badge-priority.low{ background: var(--blue-tint); color: var(--blue); }
  .badge-status{ display: inline-flex; align-items: center; gap: 4px; padding: 4px 12px; border-radius: 99px; font-size: 11px; font-weight: 700; }
  .badge-status.pending{ background: var(--amber-tint); color: var(--amber); }
  .badge-status.in-progress{ background: var(--blue-tint); color: var(--blue); }
  .badge-status.resolved{ background: var(--green-tint); color: var(--green); }
  .badge-status.cancelled{ background: #f0f0f0; color: #999; }

  /* ---- Animation ---- */
  @keyframes fadeInUp{ from{ opacity: 0; transform: translateY(15px); } to{ opacity: 1; transform: translateY(0); } }
  .animate-up{ animation: fadeInUp 0.4s ease forwards; opacity: 0; }
  .delay-1{ animation-delay: 0.1s; }
  .delay-2{ animation-delay: 0.15s; }
  .delay-3{ animation-delay: 0.2s; }
</style>
</head>
<body class="skeleton-mode">

<!-- NAVBAR ĐÃ ĐỒNG BỘ -->
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
          <a class="app-nav-link" href="{{ route('landlord.home') }}">Tổng quan</a>
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
              <a class="dropdown-item active" href="{{ url('/owner/maintenance') }}">🛠️ Sửa chữa</a>
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

<div class="page-wrap pt-4 pb-5">
  <!-- Page Header -->
  <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Quản lý sửa chữa</span></h2>
      <div class="page-desc mt-1 skeleton-box"><span class="hide-on-skeleton">Theo dõi và xử lý các yêu cầu sửa chữa từ khách thuê.</span></div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <!-- ALERT STRIP -->
  <div class="alert-strip mb-4 skeleton-box animate-up delay-2 {{ $counts['urgent'] > 0 ? '' : 'calm' }}">
    <div class="hide-on-skeleton d-flex align-items-center gap-3 flex-wrap w-100">
      <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
      <div class="alert-text">
        <div class="alert-headline">{{ $counts['urgent'] > 0 ? $counts['urgent'] . ' yêu cầu khẩn cấp đang chờ xử lý' : 'Không có yêu cầu khẩn cấp nào đang chờ' }}</div>
        <div class="alert-sub">Có {{ $counts['pending'] }} yêu cầu đang chờ xử lý</div>
      </div>
      <div class="alert-breakdown">
        <div class="figure"><div class="n mono-num">{{ $counts['total'] }}</div><div class="l">Tổng yêu cầu</div></div>
        <div class="figure"><div class="n mono-num">{{ $counts['pending'] }}</div><div class="l">Chờ xử lý</div></div>
        <div class="figure"><div class="n mono-num">{{ $counts['processing'] }}</div><div class="l">Đang sửa</div></div>
        <div class="figure"><div class="n mono-num">{{ $counts['completed'] }}</div><div class="l">Hoàn thành</div></div>
      </div>
    </div>
  </div>

  <!-- FILTER BAR -->
  <div class="filter-bar mb-3 hide-on-skeleton">
    <a href="{{ route('owner.maintenance.index') }}" class="chip-filter {{ $statusFilter === 'all' ? 'active' : '' }}" style="text-decoration: none;">Tất cả</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'pending']) }}" class="chip-filter {{ $statusFilter === 'pending' ? 'active' : '' }}" style="text-decoration: none;">Chờ xử lý</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'processing']) }}" class="chip-filter {{ $statusFilter === 'processing' ? 'active' : '' }}" style="text-decoration: none;">Đang sửa</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'completed']) }}" class="chip-filter {{ $statusFilter === 'completed' ? 'active' : '' }}" style="text-decoration: none;">Hoàn thành</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'rejected']) }}" class="chip-filter {{ $statusFilter === 'rejected' ? 'active' : '' }}" style="text-decoration: none;">Đã từ chối</a>
  </div>

  <!-- BOARD -->
  <div class="board hide-on-skeleton animate-up delay-3">
    @php
      $priLabels = ['low' => 'Thấp', 'medium' => 'Trung bình', 'high' => 'Khẩn cấp', 'urgent' => 'Khẩn cấp'];
      $grouped = $requests->getCollection()->groupBy('status');
    @endphp
    @foreach([['pending', 'Chờ xử lý', 'pending'], ['processing', 'Đang sửa', 'in-progress'], ['completed', 'Hoàn thành', 'resolved'], ['rejected', 'Đã từ chối', 'resolved']] as [$status, $title, $colClass])
    <div class="board-col {{ $colClass }}">
      <div class="board-col-head">
        <span class="title">{{ $title }}</span>
        <span class="count">{{ ($grouped[$status] ?? collect())->count() }}</span>
      </div>
      <div class="board-col-body">
        @forelse($grouped[$status] ?? [] as $ticket)
        <div class="ticket-card pri-{{ in_array($ticket->priority, ['high', 'urgent']) ? 'high' : ($ticket->priority === 'medium' ? 'medium' : 'low') }}">
          <div class="ticket-top">
            <span class="ticket-id">#TCK-{{ $ticket->id }}</span>
            <span class="ticket-pri">{{ $priLabels[$ticket->priority] ?? $ticket->priority }}</span>
          </div>
          <div class="ticket-title">{{ $ticket->title }}</div>
          <div class="ticket-room">{{ $ticket->room->name ?? '' }} · {{ $ticket->tenant->name ?? '' }}</div>
          <div class="ticket-bottom">
            <span class="ticket-date">{{ $ticket->created_at->format('d/m') }}</span>
            <div class="ticket-actions">
              <a href="{{ route('owner.maintenance.show', $ticket->id) }}" class="btn-icon" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></a>
              @if($ticket->status === 'pending')
                <form action="{{ route('owner.maintenance.status', $ticket->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="processing">
                  <button type="submit" class="btn-icon success" data-bs-toggle="tooltip" title="Bắt đầu sửa" onclick="return confirm('Bắt đầu sửa yêu cầu này?')"><i class="bi bi-tools"></i></button>
                </form>
              @elseif($ticket->status === 'processing')
                <form action="{{ route('owner.maintenance.status', $ticket->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="completed">
                  <button type="submit" class="btn-icon success" data-bs-toggle="tooltip" title="Hoàn thành" onclick="return confirm('Xác nhận hoàn thành?')"><i class="bi bi-check-lg"></i></button>
                </form>
              @endif
              @if(in_array($ticket->status, ['pending', 'processing']))
                <form action="{{ route('owner.maintenance.status', $ticket->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="rejected">
                  <button type="submit" class="btn-icon danger" data-bs-toggle="tooltip" title="Từ chối" onclick="return confirm('Từ chối yêu cầu này?')"><i class="bi bi-x-lg"></i></button>
                </form>
              @endif
            </div>
          </div>
        </div>
        @empty
        <div class="board-empty">Không có yêu cầu nào.</div>
        @endforelse
      </div>
    </div>
    @endforeach
  </div>
  <div class="mt-3">{{ $requests->links() }}</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Fallback: luôn hiện nội dung sau 2.5s kể cả khi JS phía dưới lỗi
  setTimeout(function () { document.body.classList.remove('skeleton-mode'); }, 2500);
</script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
  });
</script>
</body>
</html>