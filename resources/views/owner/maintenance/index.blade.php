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
  .app-nav-link{ color: rgba(255,255,255,.82) !important; font-size: 13.5px; font-weight: 600; padding: 9px 13px !important; border-radius: 10px; transition: .2s; white-space: nowrap; }
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

  /* SỬA: Đổi class từ .board-col.in-progress thành .board-col.progress */
  .board-col.pending{ --col-accent: var(--amber); --col-accent-tint: var(--amber-tint); }
  .board-col.in-progress{
    --col-accent: var(--blue);
    --col-accent-tint: var(--blue-tint);
  }
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

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ url('/') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="#">Tổng quan</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Nhà &amp; Phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Tin đăng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Người thuê</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('owner.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('owner.payments.index') }}">Giao dịch</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ url('/owner/maintenance') }}">Sửa chữa</a></li>
      </ul>

      <div class="navbar-actions">
        <a href="#" class="notif-btn"><i class="bi bi-bell-fill"></i><span class="notif-dot"></span></a>
        <a href="#" class="user-chip">
          <span class="user-avatar">MT</span>
          <span class="user-meta">
            <span class="user-name d-block">{{ Auth::user()->name ?? 'Minh Tuấn' }}</span>
            <span class="user-role">Chủ trọ</span>
          </span>
          <span class="caret">▾</span>
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
    <div class="d-flex gap-2 hide-on-skeleton">
      <button class="btn-outline-ledger"><i class="bi bi-file-earmark-excel me-1"></i>Xuất báo cáo</button>
      <button class="btn-brand" data-bs-toggle="modal" data-bs-target="#addMaintenanceModal">
        <i class="bi bi-plus-lg me-1"></i> Tạo yêu cầu
      </button>
    </div>
  </div>

  <!-- ALERT STRIP -->
  <div class="alert-strip mb-4 skeleton-box animate-up delay-2" id="alertStrip">
    <div class="hide-on-skeleton d-flex align-items-center gap-3 flex-wrap w-100">
      <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
      <div class="alert-text">
        <div class="alert-headline" id="alertHeadline">2 yêu cầu khẩn cấp đang chờ xử lý</div>
        <div class="alert-sub" id="alertSub">Trong đó có 1 yêu cầu chưa được phân công thợ sửa</div>
      </div>
      <div class="alert-breakdown">
        <div class="figure"><div class="n mono-num" id="statTotal">12</div><div class="l">Tổng yêu cầu</div></div>
        <div class="figure"><div class="n mono-num" id="statPending">4</div><div class="l">Chờ xử lý</div></div>
        <div class="figure"><div class="n mono-num" id="statProgress">3</div><div class="l">Đang sửa</div></div>
        <div class="figure"><div class="n mono-num" id="statResolved">5</div><div class="l">Hoàn thành</div></div>
      </div>
    </div>
  </div>

  <!-- FILTER BAR -->
  <div class="filter-bar mb-3 hide-on-skeleton">
    <div class="input-group input-group-sm" style="width: 220px;">
      <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
      <input type="text" id="searchInput" class="form-control border-start-0 ps-0 shadow-none" placeholder="Tìm theo mã, phòng...">
    </div>
    <button class="chip-filter active" data-priority="all">Tất cả</button>
    <button class="chip-filter" data-priority="high"><i class="bi bi-exclamation-triangle-fill"></i> Khẩn cấp</button>
    <button class="chip-filter" data-priority="medium">Trung bình</button>
    <button class="chip-filter" data-priority="low">Thấp</button>
    <a href="#" class="link-muted" id="archiveLinkToggle">Đã hủy (1) <i class="bi bi-chevron-down"></i></a>
  </div>

  <!-- BOARD -->
  <div class="board hide-on-skeleton animate-up delay-3" id="board">

    <!-- Cột 1: Chờ xử lý -->
    <div class="board-col pending">
      <div class="board-col-head">
        <span class="title">Chờ xử lý</span>
        <span class="count" id="countPending">2</span>
      </div>
      <div class="board-col-body" data-status="pending">

        <div class="ticket-card pri-high" data-priority="high">
          <div class="ticket-top">
            <span class="ticket-id">#MTC-202609-001</span>
            <span class="ticket-pri"><i class="bi bi-exclamation-triangle-fill"></i> Khẩn cấp</span>
          </div>
          <div class="ticket-title">Máy lạnh không mát</div>
          <div class="ticket-room">Phòng 12A · Thanh Huyền</div>
          <div class="ticket-bottom">
            <span class="ticket-assignee"><i class="bi bi-person-x"></i> Chưa phân công</span>
            <span class="ticket-date">05/09</span>
            <div class="ticket-actions">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon primary assign-btn" data-bs-toggle="tooltip" title="Phân công thợ"><i class="bi bi-person-plus"></i></button>
            </div>
          </div>
        </div>

        <div class="ticket-card pri-high" data-priority="high">
          <div class="ticket-top">
            <span class="ticket-id">#MTC-202609-004</span>
            <span class="ticket-pri"><i class="bi bi-exclamation-triangle-fill"></i> Khẩn cấp</span>
          </div>
          <div class="ticket-title">Cửa phòng bị khóa kẹt</div>
          <div class="ticket-room">Phòng 02D · Quốc Bảo</div>
          <div class="ticket-bottom">
            <span class="ticket-assignee has-name"><i class="bi bi-person-check"></i> Lê Văn Khoa</span>
            <span class="ticket-date">01/09</span>
            <div class="ticket-actions">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon success start-btn" data-bs-toggle="tooltip" title="Bắt đầu sửa"><i class="bi bi-tools"></i></button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Cột 2: Đang sửa -->
    <div class="board-col in-progress">
      <div class="board-col-head">
        <span class="title">Đang sửa</span>
        <span class="count" id="countProgress">1</span>
      </div>
      <div class="board-col-body" data-status="progress">

        <div class="ticket-card pri-medium" data-priority="medium">
          <div class="ticket-top">
            <span class="ticket-id">#MTC-202609-002</span>
            <span class="ticket-pri"><i class="bi bi-arrow-up-circle"></i> Trung bình</span>
          </div>
          <div class="ticket-title">Vòi nước bị rò rỉ</div>
          <div class="ticket-room">Phòng 14B · Hoàng Nam</div>
          <div class="ticket-bottom">
            <span class="ticket-assignee has-name"><i class="bi bi-person-check"></i> Nguyễn Văn Thợ</span>
            <span class="ticket-date">03/09</span>
            <div class="ticket-actions">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon success complete-btn" data-bs-toggle="tooltip" title="Hoàn thành"><i class="bi bi-check-lg"></i></button>
            </div>
          </div>
        </div>

      </div>
    </div>

    <!-- Cột 3: Hoàn thành -->
    <div class="board-col resolved">
      <div class="board-col-head">
        <span class="title">Hoàn thành</span>
        <span class="count" id="countResolved">1</span>
      </div>
      <div class="board-col-body" data-status="resolved">

        <div class="ticket-card pri-low" data-priority="low">
          <div class="ticket-top">
            <span class="ticket-id">#MTC-202609-003</span>
            <span class="ticket-pri"><i class="bi bi-arrow-down-circle"></i> Thấp</span>
          </div>
          <div class="ticket-title">Đèn chiếu sáng hỏng</div>
          <div class="ticket-room">Phòng 01C · Ngọc Ánh</div>
          <div class="ticket-bottom">
            <span class="ticket-assignee has-name"><i class="bi bi-person-check"></i> Trần Văn Sửa</span>
            <span class="ticket-date">28/08</span>
            <div class="ticket-actions">
              <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
              <button class="btn-icon" data-bs-toggle="tooltip" title="In phiếu"><i class="bi bi-printer"></i></button>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>

  <!-- ARCHIVE -->
  <div class="archive-tray mt-3 hide-on-skeleton" id="archiveTray">
    <div class="archive-row">
      <i class="bi bi-x-circle text-muted"></i>
      <div class="flex-grow-1">
        <span class="archive-title">Quạt trần kêu ồn</span>
        <span class="mono-num ms-2" style="font-size:0.72rem;">#MTC-202608-005</span>
        — Phòng 03B · Thu Trang
      </div>
      <span class="ticket-date">20/08</span>
      <button class="btn-icon view-btn" data-bs-toggle="tooltip" title="Xem chi tiết"><i class="bi bi-eye-fill"></i></button>
    </div>
  </div>

</div>

<!-- MODAL: TẠO YÊU CẦU -->
<div class="modal fade" id="addMaintenanceModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <h5 class="modal-title fw-bold" style="color: var(--forest);">
          <i class="bi bi-plus-circle me-2"></i>Tạo yêu cầu sửa chữa
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold small">Phòng / Khách thuê <span class="text-danger">*</span></label>
          <select class="form-select">
            <option value="">Chọn phòng...</option>
            <option>Phòng 12A - Thanh Huyền</option>
            <option>Phòng 14B - Hoàng Nam</option>
            <option>Phòng 01C - Ngọc Ánh</option>
            <option>Phòng 02D - Quốc Bảo</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold small">Tiêu đề sự cố <span class="text-danger">*</span></label>
          <input type="text" class="form-control" placeholder="VD: Máy lạnh không mát, vòi nước bị rò...">
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold small">Mô tả chi tiết</label>
          <textarea class="form-control" rows="3" placeholder="Mô tả chi tiết sự cố cần sửa chữa..."></textarea>
        </div>
        <div class="row g-3">
          <div class="col-6">
            <label class="form-label fw-bold small">Mức độ ưu tiên <span class="text-danger">*</span></label>
            <select class="form-select">
              <option value="low">Thấp</option>
              <option value="medium" selected>Trung bình</option>
              <option value="high">Khẩn cấp</option>
            </select>
          </div>
          <div class="col-6">
            <label class="form-label fw-bold small">Ngày báo</label>
            <input type="date" class="form-control" value="{{ date('Y-m-d') }}">
          </div>
        </div>
        <div class="mt-3">
          <label class="form-label fw-bold small">Phân công người sửa</label>
          <select class="form-select">
            <option value="">Chưa phân công</option>
            <option>Nguyễn Văn Thợ - Điện lạnh</option>
            <option>Trần Văn Sửa - Điện nước</option>
            <option>Lê Văn Khoa - Sửa chữa tổng hợp</option>
            <option>Phạm Văn Đức - Mộc &amp; Khóa</option>
          </select>
          <div class="text-muted small mt-1">
            <i class="bi bi-info-circle"></i> Có thể để trống và phân công sau
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-brand fw-bold px-4"><i class="bi bi-save me-1"></i>Tạo yêu cầu</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: PHÂN CÔNG -->
<div class="modal fade" id="assignModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title fw-bold" style="color: var(--forest);">
          <i class="bi bi-person-plus me-2"></i>Phân công sửa chữa
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label fw-bold small">Yêu cầu</label>
          <div class="bg-light p-3 rounded" style="font-size:0.9rem;">
            <div class="fw-bold">#MTC-202609-001 - Máy lạnh không mát</div>
            <div class="text-muted small">Phòng 12A - Thanh Huyền</div>
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label fw-bold small">Chọn người sửa <span class="text-danger">*</span></label>
          <select class="form-select">
            <option value="">Chọn thợ...</option>
            <option>Nguyễn Văn Thợ - Điện lạnh</option>
            <option>Trần Văn Sửa - Điện nước</option>
            <option>Lê Văn Khoa - Sửa chữa tổng hợp</option>
            <option>Phạm Văn Đức - Mộc &amp; Khóa</option>
          </select>
        </div>
        <div class="mb-0">
          <label class="form-label fw-bold small">Ghi chú phân công</label>
          <textarea class="form-control" rows="2" placeholder="Nhập ghi chú cho thợ (nếu có)..."></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-brand fw-bold px-4"><i class="bi bi-check-lg me-1"></i>Phân công</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: CHI TIẾT -->
<div class="modal fade" id="detailModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <div>
          <h5 class="modal-title fw-bold" style="color: var(--forest);" id="detailCode">#MTC-202609-001</h5>
          <div class="text-muted small" id="detailDate">05/09/2026</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-12">
            <div class="text-muted small">Tiêu đề</div>
            <div class="fw-bold fs-6" id="detailTitle">Máy lạnh không mát</div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Phòng</div>
            <div class="fw-bold" id="detailRoom">Phòng 12A</div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Khách thuê</div>
            <div class="fw-bold" id="detailTenant">Thanh Huyền</div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Mức độ</div>
            <div id="detailPriority"><span class="badge-priority high"><i class="bi bi-exclamation-triangle-fill"></i> Khẩn cấp</span></div>
          </div>
          <div class="col-6">
            <div class="text-muted small">Trạng thái</div>
            <div id="detailStatus"><span class="badge-status pending"><i class="bi bi-clock"></i> Chờ xử lý</span></div>
          </div>
          <div class="col-12">
            <div class="text-muted small">Người sửa chữa</div>
            <div class="fw-bold" id="detailAssignee">
              <span class="text-muted"><i class="bi bi-person-x me-1"></i>Chưa phân công</span>
            </div>
          </div>
          <div class="col-12">
            <div class="text-muted small">Mô tả</div>
            <div class="fw-bold" id="detailDesc">Máy lạnh Daikin 1.5HP không mát, đã bật full công suất nhưng vẫn không lạnh. Khách đã vệ sinh lưới lọc nhưng không cải thiện.</div>
          </div>
          <div class="col-12">
            <hr>
            <div class="text-muted small">Lịch sử cập nhật</div>
            <div style="font-size:0.85rem;">
              <div class="d-flex justify-content-between py-1 border-bottom">
                <span><i class="bi bi-plus-circle text-success me-1"></i> Tạo yêu cầu</span>
                <span class="text-muted small">05/09/26 14:30</span>
              </div>
              <div class="d-flex justify-content-between py-1">
                <span><i class="bi bi-clock text-warning me-1"></i> Đang chờ xử lý</span>
                <span class="text-muted small">05/09/26 14:30</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-sm btn-light border fw-bold"><i class="bi bi-printer-fill me-1"></i>In phiếu</button>
        <div class="d-flex gap-2">
          <button class="btn btn-sm btn-primary fw-bold"><i class="bi bi-person-plus me-1"></i>Phân công</button>
          <button class="btn btn-sm btn-success fw-bold"><i class="bi bi-check-lg me-1"></i>Hoàn thành</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 500);

    // Tooltips
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));

    // ==================== UPDATE COUNTS ====================
    function updateAllCounts() {
      document.querySelectorAll('.board-col-body').forEach(col => {
        const visible = [...col.children].filter(c => c.style.display !== 'none').length;
        const status = col.dataset.status;
        const map = { pending: 'countPending', progress: 'countProgress', resolved: 'countResolved' };
        const el = document.getElementById(map[status]);
        if (el) el.textContent = visible;
      });

      const total = document.querySelectorAll('.ticket-card').length;
      const pending = document.querySelectorAll('.board-col-body[data-status="pending"] .ticket-card:not([style*="display: none"])').length;
      const progress = document.querySelectorAll('.board-col-body[data-status="progress"] .ticket-card:not([style*="display: none"])').length;
      const resolved = document.querySelectorAll('.board-col-body[data-status="resolved"] .ticket-card:not([style*="display: none"])').length;

      document.getElementById('statTotal').textContent = total;
      document.getElementById('statPending').textContent = pending;
      document.getElementById('statProgress').textContent = progress;
      document.getElementById('statResolved').textContent = resolved;

      const highPriority = document.querySelectorAll('.ticket-card.pri-high:not([style*="display: none"])');
      const highUnassigned = document.querySelectorAll('.ticket-card.pri-high:not([style*="display: none"]) .ticket-assignee:not(.has-name)');

      if (highPriority.length > 0) {
        document.getElementById('alertHeadline').textContent = highPriority.length + ' yêu cầu khẩn cấp đang chờ xử lý';
        document.getElementById('alertSub').textContent = 'Trong đó có ' + highUnassigned.length + ' yêu cầu chưa được phân công thợ sửa';
        document.getElementById('alertStrip').className = 'alert-strip mb-4 skeleton-box animate-up delay-2';
      } else {
        document.getElementById('alertHeadline').textContent = 'Không có yêu cầu khẩn cấp nào đang chờ';
        document.getElementById('alertSub').textContent = 'Có ' + pending + ' yêu cầu đang chờ xử lý';
        document.getElementById('alertStrip').className = 'alert-strip calm mb-4 skeleton-box animate-up delay-2';
      }
    }

    // ==================== VIEW DETAIL ====================
    document.querySelectorAll('.view-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('detailModal')).show();
      });
    });

    // ==================== ASSIGN ====================
    document.querySelectorAll('.assign-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        new bootstrap.Modal(document.getElementById('assignModal')).show();
      });
    });

    // ==================== START (Chờ → Đang sửa) ====================
    document.querySelectorAll('.start-btn').forEach(btn => {
      btn.addEventListener('click', function() {
        if (!confirm('Xác nhận bắt đầu sửa chữa yêu cầu này?')) return;
        const card = this.closest('.ticket-card');
        const targetCol = document.querySelector('.board-col-body[data-status="progress"]');
        targetCol.appendChild(card);
        this.outerHTML = `<button class="btn-icon success complete-btn" data-bs-toggle="tooltip" title="Hoàn thành"><i class="bi bi-check-lg"></i></button>`;
        document.querySelectorAll('.complete-btn').forEach(el => {
          el.removeEventListener('click', completeHandler);
          el.addEventListener('click', completeHandler);
        });
        updateAllCounts();
        alert('Đã bắt đầu sửa chữa!');
      });
    });

    // ==================== COMPLETE (Đang sửa → Hoàn thành) ====================
    function completeHandler(e) {
      if (!confirm('Xác nhận hoàn thành yêu cầu này?')) return;
      const btn = e.currentTarget;
      const card = btn.closest('.ticket-card');
      const targetCol = document.querySelector('.board-col-body[data-status="resolved"]');
      targetCol.appendChild(card);
      btn.outerHTML = `<button class="btn-icon" data-bs-toggle="tooltip" title="In phiếu"><i class="bi bi-printer"></i></button>`;
      new bootstrap.Tooltip(btn.parentElement.querySelector('.btn-icon'));
      updateAllCounts();
      alert('Đã hoàn thành yêu cầu!');
    }

    document.querySelectorAll('.complete-btn').forEach(btn => {
      btn.addEventListener('click', completeHandler);
    });

    // ==================== SEARCH ====================
    document.getElementById('searchInput').addEventListener('input', function() {
      const search = this.value.toLowerCase();
      document.querySelectorAll('.ticket-card').forEach(card => {
        const match = card.textContent.toLowerCase().includes(search);
        card.style.display = match ? '' : 'none';
      });
      updateAllCounts();
    });

    // ==================== PRIORITY FILTER ====================
    document.querySelectorAll('.chip-filter').forEach(chip => {
      chip.addEventListener('click', function() {
        document.querySelectorAll('.chip-filter').forEach(c => c.classList.remove('active'));
        this.classList.add('active');
        const priority = this.dataset.priority;
        document.querySelectorAll('.ticket-card').forEach(card => {
          const match = priority === 'all' || card.dataset.priority === priority;
          card.style.display = match ? '' : 'none';
        });
        updateAllCounts();
      });
    });

    // ==================== ARCHIVE TOGGLE ====================
    document.getElementById('archiveLinkToggle').addEventListener('click', function(e) {
      e.preventDefault();
      const tray = document.getElementById('archiveTray');
      tray.classList.toggle('open');
      const icon = this.querySelector('i');
      icon.className = tray.classList.contains('open') ? 'bi bi-chevron-up' : 'bi bi-chevron-down';
    });

    updateAllCounts();
  });
</script>
</body>
</html>