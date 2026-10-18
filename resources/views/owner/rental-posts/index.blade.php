
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trọ Ơi | Quản lý tin đăng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --green: #20584f;
            --green-dark: #17463e;
            --green-light: #eaf3ef;
            --cream: #f8f5eb;
            --border: #e6dcc2;
            --text: #213430;
            --muted: #78837e;
            --yellow: #f5c84b;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            background: var(--cream);
            color: var(--text);
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        a { text-decoration: none; }

        /* NAVBAR */
        .app-navbar {
            background: var(--green);
            min-height: 74px;
            box-shadow: 0 3px 14px rgba(20,55,47,.12);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .app-navbar .container-fluid {
            max-width: 1440px;
            padding: 0 28px;
        }

        .logo {
            color: #fff;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -1.2px;
            white-space: nowrap;
        }

        .logo span { color: var(--yellow); }

        .app-nav-link {
            display: block;
            color: rgba(255,255,255,.84) !important;
            font-size: 12px;
            font-weight: 600;
            padding: 10px 12px !important;
            border-radius: 10px;
            white-space: nowrap;
            transition: .2s;
        }

        .app-nav-link:hover,
        .app-nav-link.active {
            color: var(--green) !important;
            background: #fff;
        }

        .app-navbar .dropdown-menu {
            border: 0;
            border-radius: 14px;
            padding: 8px;
            box-shadow: 0 12px 32px rgba(20,55,47,.14);
            min-width: 210px;
        }

        .app-navbar .dropdown-item {
            border-radius: 9px;
            padding: 10px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .app-navbar .dropdown-item:hover,
        .app-navbar .dropdown-item.active {
            color: var(--green);
            background: var(--green-light);
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .notif-btn {
            width: 40px;
            height: 40px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 11px;
            background: rgba(255,255,255,.08);
            color: #fff;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 6px 12px 6px 6px;
            border-radius: 12px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.16);
            color: #fff;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: var(--yellow);
            color: var(--green-dark);
            font-weight: 800;
        }

        .user-name { font-size: 12px; font-weight: 700; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.68); }

        /* PAGE */
        .owner-main {
            max-width: 1320px;
            margin: 0 auto;
            padding: 36px 24px 55px;
        }

        .heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
        }

        .eyebrow {
            color: var(--green);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.8px;
            text-transform: uppercase;
        }

        .heading h1 {
            margin: 7px 0;
            font-size: clamp(25px, 3vw, 32px);
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .heading p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .btn-owner {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            border: 0;
            border-radius: 13px;
            padding: 12px 18px;
            background: var(--green);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            transition: .2s;
            white-space: nowrap;
        }

        .btn-owner:hover {
            color: #fff;
            background: var(--green-dark);
            transform: translateY(-1px);
        }

        /* STATISTICS */
        .post-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .post-stat {
            padding: 20px;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 19px;
            box-shadow: 0 5px 20px rgba(32,88,79,.025);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .post-stat-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 43px;
            height: 43px;
            border-radius: 13px;
            background: var(--green-light);
            font-size: 20px;
        }

        .stat-number {
            display: block;
            margin-top: 15px;
            font-size: 29px;
            line-height: 1.2;
            font-weight: 800;
        }

        .stat-label {
            display: block;
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        /* POSTS */
        .posts-container {
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: 0 10px 30px rgba(32,88,79,.045);
        }

        .list-heading {
            padding: 22px 24px 0;
        }

        .list-heading h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
        }

        .list-heading p {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 12px;
        }

        .toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 11px;
            padding: 19px 24px;
            border-bottom: 1px solid #eee7d8;
        }

        .search-wrap {
            position: relative;
            flex: 1;
            min-width: 230px;
        }

        .search-wrap span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
        }

        .search,
        .filter {
            width: 100%;
            min-height: 44px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #fff;
            color: var(--text);
            font-family: inherit;
            font-size: 12px;
            outline: none;
        }

        .search {
            padding: 11px 14px 11px 39px;
        }

        .filter {
            width: auto;
            min-width: 180px;
            padding: 10px 35px 10px 13px;
        }

        .search:focus,
        .filter:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(32,88,79,.08);
        }

        .posts-list {
            display: grid;
            gap: 14px;
            padding: 20px 24px 24px;
        }

        .post-card {
            display: grid;
            grid-template-columns: 190px minmax(0, 1fr) 160px;
            align-items: center;
            gap: 19px;
            padding: 13px;
            border: 1px solid var(--border);
            border-radius: 17px;
            transition: .2s;
        }

        .post-card:hover {
            border-color: #c7d8ce;
            box-shadow: 0 8px 22px rgba(32,88,79,.065);
        }

        .post-image {
            height: 145px;
            overflow: hidden;
            border-radius: 12px;
            background: var(--green-light);
        }

        .post-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            color: var(--green);
            font-size: 38px;
        }

        .post-info { min-width: 0; }

        .post-info h3 {
            overflow-wrap: anywhere;
            margin: 0 0 7px;
            font-size: 16px;
            line-height: 1.5;
            font-weight: 800;
        }

        .post-room {
            margin-bottom: 10px;
            color: var(--muted);
            font-size: 11px;
        }

        .post-price {
            margin-bottom: 11px;
            color: var(--green);
            font-size: 17px;
            font-weight: 800;
        }

        .post-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 14px;
            color: var(--muted);
            font-size: 11px;
        }

        .post-right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 14px;
        }

        .post-status {
            display: inline-flex;
            justify-content: center;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 10px;
            line-height: 1.3;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-approved { background: #eaf3ef; color: #20584f; }
        .status-pending { background: #fff7d7; color: #8b6a00; }
        .status-rejected { background: #fff0ed; color: #c65b4a; }
        .status-hidden { background: #f1f1ef; color: #78837e; }

        .post-actions {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 6px;
        }

        .post-actions a,
        .post-actions button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            border: 0;
            border-radius: 9px;
            padding: 8px 10px;
            font-family: inherit;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
        }

        .btn-view { background: var(--green-light); color: var(--green); }
        .btn-edit { background: #f8f5eb; color: #53635e; }
        .btn-delete { background: #fff0ed; color: #c65b4a; }
        .post-actions form { margin: 0; }

        .empty-state {
            padding: 55px 20px;
            text-align: center;
        }

        .empty-icon { font-size: 43px; }

        .empty-state h3 {
            margin: 15px 0 8px;
            font-size: 18px;
            font-weight: 800;
        }

        .empty-state p {
            margin-bottom: 20px;
            color: var(--muted);
            font-size: 13px;
        }

        .alert {
            border-radius: 12px;
            font-size: 13px;
        }

        .results-count {
            padding: 0 24px 20px;
            color: var(--muted);
            font-size: 11px;
        }

        @media (max-width: 1199px) {
            .app-navbar .container-fluid { padding: 10px 20px; }
            .navbar-actions {
                padding-top: 13px;
                margin-top: 12px;
                border-top: 1px solid rgba(255,255,255,.15);
            }
        }

        @media (max-width: 1000px) {
            .post-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .post-card { grid-template-columns: 150px minmax(0, 1fr); }
            .post-right {
                grid-column: 1 / -1;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
            .post-image { height: 125px; }
        }

        @media (max-width: 650px) {
            .owner-main { padding: 25px 14px 35px; }
            .heading { align-items: flex-start; flex-direction: column; }
            .post-stats { gap: 10px; }
            .post-stat { padding: 14px; }
            .stat-number { font-size: 24px; }
            .stat-label { font-size: 11px; }
            .toolbar { padding: 15px; }
            .search-wrap, .filter { width: 100%; min-width: 0; }
            .posts-list { padding: 14px; }
            .post-card { grid-template-columns: 1fr; gap: 13px; }
            .post-image { height: 190px; }
            .post-right { grid-column: auto; align-items: flex-start; flex-direction: column; }
            .post-actions { justify-content: flex-start; }
            .list-heading { padding: 18px 16px 0; }
            .results-count { padding: 0 16px 16px; }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-xl app-navbar">
    <div class="container-fluid">
        <a class="logo" href="{{ route('landlord.dashboard') }}">Trọ <span>Ơi</span></a>

        <button class="navbar-toggler shadow-none border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#mainMenu"
                aria-controls="mainMenu" aria-expanded="false"
                aria-label="Mở menu">
            <span class="navbar-toggler-icon" style="filter:invert(1)"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav mx-auto align-items-xl-center">
                <li class="nav-item">
                    <a class="app-nav-link text-decoration-none {{ request()->routeIs('landlord.dashboard') ? 'active' : '' }}"
                       href="{{ route('landlord.dashboard') }}">Tổng quan</a>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.properties.*') || request()->routeIs('owner.rooms.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">Nhà &amp; Phòng</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.tenants.*') || request()->routeIs('owner.contracts.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">Khách &amp; Hợp đồng</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.services.*') || request()->routeIs('owner.utilities.*') || request()->routeIs('owner.invoices.*') || request()->routeIs('owner.payments.*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">Tài chính</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.utilities.index') }}">⚡ Điện nước</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.payments.index') }}">💰 Giao dịch</a></li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.rental-posts.*') || request()->routeIs('owner.appointments.*') || request()->routeIs('owner.reviews.*') || request()->is('owner/maintenance*') ? 'active' : '' }}"
                       href="#" data-bs-toggle="dropdown">Vận hành</a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('owner.rental-posts.*') ? 'active' : '' }}"
                               href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a>
                        </li>
                        <li>
                            <a class="dropdown-item {{ request()->routeIs('owner.appointments.*') ? 'active' : '' }}"
                               href="{{ route('owner.appointments.index') }}">📅 Lịch xem</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ url('/owner/maintenance') }}">🛠️ Sửa chữa</a></li>
                        <li><a class="dropdown-item" href="{{ route('owner.reviews.index') }}">⭐ Đánh giá</a></li>
                    </ul>
                </li>
            </ul>

           <div class="navbar-actions">

                    <button
                        class="notif-btn"
                        type="button"
                        aria-label="Thông báo"
                        title="Thông báo">
                        🔔
                    </button>

                    <div class="dropdown">
                        <button
                            class="user-chip border-0 bg-transparent p-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <span class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name ?? 'CT', 0, 2)) }}
                            </span>

                            <span class="user-meta text-start">
                                <span class="user-name d-block">
                                    {{ auth()->user()->name ?? 'Chủ trọ' }}
                                </span>
                                <span class="user-role">Chủ trọ</span>
                            </span>

                            <span class="caret">▾</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <span class="dropdown-item-text">
                                    {{ auth()->user()->email ?? '' }}
                                </span>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST"
                                      action="{{ route('logout') }}"
                                      class="m-0">
                                    @csrf

                                    <button type="submit" class="dropdown-item">
                                        ↪ Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>

                </div>
        </div>
    </div>
</nav>

<main class="owner-main">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    <div class="heading">
        <div>
            <div class="eyebrow">Rental posts</div>
            <h1>Quản lý tin đăng</h1>
            <p>Quản lý tin cho thuê, theo dõi trạng thái và cập nhật nội dung.</p>
        </div>

        <a href="{{ route('owner.rental-posts.create') }}" class="btn-owner">
            <span>＋</span> Tạo tin đăng
        </a>
    </div>

    <section class="post-stats">
        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">TỔNG TIN ĐĂNG</span>
                <div class="post-stat-icon">📢</div>
            </div>
            <strong class="stat-number">{{ $posts->count() }}</strong>
            <span class="stat-label">Tin đăng thuộc các phòng của em</span>
        </div>

        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">ĐANG HIỂN THỊ</span>
                <div class="post-stat-icon">🟢</div>
            </div>
            <strong class="stat-number">{{ $posts->where('status', 'published')->count() }}</strong>
            <span class="stat-label">Tin đã được đăng</span>
        </div>

        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">CHỜ DUYỆT</span>
                <div class="post-stat-icon">⏳</div>
            </div>
            <strong class="stat-number">{{ $posts->where('status', 'pending')->count() }}</strong>
            <span class="stat-label">Tin đang chờ xử lý</span>
        </div>

        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">BẢN NHÁP</span>
                <div class="post-stat-icon">📝</div>
            </div>
            <strong class="stat-number">{{ $posts->where('status', 'draft')->count() }}</strong>
            <span class="stat-label">Tin chưa gửi duyệt</span>
        </div>
    </section>

    <section class="posts-container">
        <div class="list-heading">
            <h2>Danh sách tin đăng</h2>
            <p>Thông tin được lấy từ dữ liệu tin đăng và phòng trọ.</p>
        </div>

        <div class="toolbar">
            <div class="search-wrap">
                <span>🔎</span>
                <input class="search" id="postSearch" type="search"
                       placeholder="Tìm theo tiêu đề hoặc phòng..."
                       aria-label="Tìm tin đăng">
            </div>

            <select class="filter" id="postStatus" aria-label="Lọc trạng thái">
                <option value="">Tất cả trạng thái</option>
                <option value="draft">Bản nháp</option>
                <option value="pending">Chờ duyệt</option>
                <option value="published">Đang hiển thị</option>
                <option value="rejected">Bị từ chối</option>
                <option value="hidden">Đã ẩn</option>
            </select>
        </div>

        <div class="posts-list" id="postsList">
            @forelse ($posts as $post)
                @php
                    $statusLabels = [
                        'draft' => 'BẢN NHÁP',
                        'pending' => 'CHỜ DUYỆT',
                        'published' => 'ĐANG HIỂN THỊ',
                        'rejected' => 'BỊ TỪ CHỐI',
                        'hidden' => 'ĐÃ ẨN',
                    ];

                    $statusClasses = [
                        'draft' => 'status-hidden',
                        'pending' => 'status-pending',
                        'published' => 'status-approved',
                        'rejected' => 'status-rejected',
                        'hidden' => 'status-hidden',
                    ];

                    $room = $post->room;
                    $property = $room?->property;

                    $searchText = $post->title . ' ' .
                        ($room?->room_number ?? '') . ' ' .
                        ($room?->name ?? '') . ' ' .
                        ($property?->name ?? '');
                @endphp

                <article class="post-card"
                         data-status="{{ $post->status }}"
                         data-search="{{ $searchText }}">

                    <div class="post-image">
                        <div class="image-placeholder">🏠</div>
                    </div>

                    <div class="post-info">
                        <h3>{{ $post->title }}</h3>

                        <div class="post-room">
                            Phòng:
                            {{ $room?->room_number ?? $room?->name ?? ('#' . ($room?->id ?? '—')) }}
                            · {{ $property?->name ?? 'Chưa có tên nhà trọ' }}
                        </div>

                        <div class="post-price">
                            @if ($room && $room->price !== null)
                                {{ number_format((float) $room->price, 0, ',', '.') }}đ / tháng
                            @else
                                Chưa cập nhật giá thuê
                            @endif
                        </div>

                        <div class="post-meta">
                            <span>
                                📐
                                @if ($room && $room->area !== null)
                                    {{ $room->area }} m²
                                @else
                                    Chưa có diện tích
                                @endif
                            </span>

                            @if ($post->published_at)
                                <span>📅 Đăng ngày {{ $post->published_at->format('d/m/Y') }}</span>
                            @endif

                            @if ($post->expired_at)
                                <span>⏰ Hết hạn {{ $post->expired_at->format('d/m/Y') }}</span>
                            @endif
                        </div>

                        @if ($post->status === 'rejected' && $post->rejection_reason)
                            <div class="mt-2 small text-danger">
                                Lý do: {{ $post->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="post-right">
                        <span class="post-status {{ $statusClasses[$post->status] ?? 'status-hidden' }}">
                            {{ $statusLabels[$post->status] ?? $post->status }}
                        </span>

                        <div class="post-actions">
                            <a href="{{ route('owner.rental-posts.show', $post) }}"
                               class="btn-view">Xem</a>

                            <a href="{{ route('owner.rental-posts.edit', $post) }}"
                               class="btn-edit">Sửa</a>

                            <form action="{{ route('owner.rental-posts.destroy', $post) }}"
                                  method="POST"
                                  onsubmit="return confirm('Em có chắc muốn xóa tin đăng này không?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete">Xóa</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📢</div>
                    <h3>Chưa có tin đăng nào</h3>
                    <p>Tạo tin đăng cho phòng trọ để bắt đầu quản lý tại đây.</p>
                    <a href="{{ route('owner.rental-posts.create') }}" class="btn-owner">
                        ＋ Tạo tin đăng đầu tiên
                    </a>
                </div>
            @endforelse
        </div>

        @if ($posts->isNotEmpty())
            <div class="results-count" id="resultsCount">
                Hiển thị {{ $posts->count() }} tin đăng
            </div>
        @endif
    </section>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('postSearch');
    const statusSelect = document.getElementById('postStatus');
    const cards = Array.from(document.querySelectorAll('.post-card'));
    const resultsCount = document.getElementById('resultsCount');

    function filterPosts() {
        const keyword = searchInput.value.trim().toLocaleLowerCase('vi');
        const selectedStatus = statusSelect.value;
        let visibleCount = 0;

        cards.forEach(function (card) {
            const searchText = (card.dataset.search || '').toLocaleLowerCase('vi');
            const cardStatus = card.dataset.status || '';

            const matchesKeyword = searchText.includes(keyword);
            const matchesStatus = !selectedStatus || cardStatus === selectedStatus;
            const visible = matchesKeyword && matchesStatus;

            card.style.display = visible ? '' : 'none';

            if (visible) visibleCount++;
        });

        if (resultsCount) {
            resultsCount.textContent = 'Hiển thị ' + visibleCount + ' / ' + cards.length + ' tin đăng';
        }
    }

    searchInput.addEventListener('input', filterPosts);
    statusSelect.addEventListener('change', filterPosts);
});
</script>

</body>
</html>
