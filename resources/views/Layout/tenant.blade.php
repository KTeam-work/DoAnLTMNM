<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Trang chủ người thuê</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        /* =========================================================
           TENANT DASHBOARD
        ========================================================= */

        * {
            box-sizing: border-box;
        }




        body {
            margin: 0;
            background: #f8f5eb;
            color: #17463e;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        a {
            text-decoration: none;
        }

        /* =========================================================
           HERO
        ========================================================= */

        .hero {
            position: relative;
            overflow: hidden;
            padding: 64px 24px 52px;
            background:
                radial-gradient(circle at 85% 10%, rgba(242, 223, 181, .35), transparent 28%),
                linear-gradient(135deg, #20584f 0%, #17463e 100%);
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 430px;
            height: 430px;
            right: -150px;
            bottom: -230px;
            border-radius: 50%;
            background: rgba(242, 223, 181, .08);
        }

        .hero-inner {
            position: relative;
            z-index: 1;
            max-width: 1200px;
            margin: 0 auto;
        }

        .hero-kicker {
            margin-bottom: 13px;
            color: #f2dfb5;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: .8px;
        }

        .hero h1 {
            margin: 0;
            color: #ffffff;
            font-size: clamp(32px, 4vw, 52px);
            line-height: 1.1;
            font-weight: 800;
            letter-spacing: -1.5px;
        }

        .hero h1 .highlight {
            color: #f2dfb5;
        }

        .hero-desc {
            max-width: 700px;
            margin: 18px 0 30px;
            color: rgba(255, 255, 255, .75);
            font-size: 14px;
            line-height: 1.8;
        }

        /* =========================================================
           SEARCH
        ========================================================= */

        .search-panel {
            overflow: hidden;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 18px 45px rgba(0, 0, 0, .15);
        }

        .search-field {
            min-height: 78px;
            padding: 15px 18px;
            border-right: 1px solid #eee9df;
        }

        .field-label {
            display: block;
            margin-bottom: 6px;
            color: #84908c;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .field-value {
            color: #17463e;
            font-size: 13px;
            font-weight: 700;
        }

        .field-muted {
            color: #596c67;
        }

        .search-btn {
            width: 100%;
            min-height: 58px;
            border: 0;
            border-radius: 13px;
            background: #20584f;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .search-btn:hover {
            background: #17463e;
            transform: translateY(-1px);
        }

        .quick-filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 16px;
        }

        .quick-filter {
            padding: 7px 12px;
            border: 1px solid rgba(255, 255, 255, .18);
            border-radius: 99px;
            background: rgba(255, 255, 255, .08);
            color: rgba(255, 255, 255, .8);
            font-size: 11px;
            font-weight: 600;
        }

        /* =========================================================
           CONTENT
        ========================================================= */

        .page-wrap {
            max-width: 1200px;
            margin: 0 auto;
            padding: 46px 24px 70px;
        }

        .page-header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 20px;
        }

        .page-title {
            margin: 0;
            color: #17463e;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -.4px;
        }

        .page-desc {
            margin-top: 5px;
            color: #899590;
            font-size: 12px;
        }

        .section-link,
        .panel-link {
            color: #20584f;
            font-size: 12px;
            font-weight: 700;
        }

        .section-link:hover,
        .panel-link:hover {
            color: #17463e;
        }

        /* =========================================================
           ROOM CARD
        ========================================================= */

        .room-card {
            position: relative;
            overflow: hidden;
            height: 100%;
            border: 1px solid #e9e3d8;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 8px 25px rgba(23, 70, 62, .05);
            transition: .25s ease;
        }

        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(23, 70, 62, .11);
        }

        .fav-btn {
            position: absolute;
            z-index: 2;
            top: 12px;
            right: 12px;
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, .92);
            color: #78908a;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, .08);
        }

        .fav-btn:hover {
            color: #b45454;
        }

        .room-img {
            width: 100%;
            height: 210px;
            display: block;
            object-fit: cover;
        }

        .room-body {
            padding: 17px;
        }

        .room-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 10px;
        }

        .badge-room {
            display: inline-flex;
            align-items: center;
            padding: 5px 8px;
            border-radius: 7px;
            background: #eef4f1;
            color: #32655c;
            font-size: 9px;
            font-weight: 800;
        }

        .badge-room.hot {
            background: #fff1df;
            color: #a96526;
        }

        .room-title {
            min-height: 44px;
            color: #17463e;
            font-size: 15px;
            font-weight: 800;
            line-height: 1.45;
        }

        .room-address {
            min-height: 36px;
            margin-top: 6px;
            color: #87928e;
            font-size: 11px;
            line-height: 1.55;
        }

        .room-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 13px;
            color: #647671;
            font-size: 10px;
        }

        .room-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin-top: 18px;
            padding-top: 14px;
            border-top: 1px solid #eeeae2;
        }

        .room-price {
            color: #20584f;
            font-size: 17px;
            font-weight: 800;
        }

        .room-price small {
            color: #8b9692;
            font-size: 9px;
            font-weight: 500;
        }

        .detail-btn {
            padding: 9px 13px;
            border: 0;
            border-radius: 9px;
            background: #eef4f1;
            color: #20584f;
            font-size: 10px;
            font-weight: 800;
            cursor: pointer;
        }

        .detail-btn:hover {
            background: #20584f;
            color: #ffffff;
        }

        /* =========================================================
           PANELS
        ========================================================= */

        .panel {
            height: 100%;
            overflow: hidden;
            border: 1px solid #e9e3d8;
            border-radius: 18px;
            background: #ffffff;
            box-shadow: 0 8px 25px rgba(23, 70, 62, .04);
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 19px;
            border-bottom: 1px solid #eeeae2;
        }

        .panel-title {
            margin: 0;
            color: #17463e;
            font-size: 14px;
            font-weight: 800;
        }

        .data-table {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
        }

        .data-table th {
            padding: 12px 18px;
            background: #fbfaf7;
            color: #899590;
            font-size: 9px;
            font-weight: 800;
            text-align: left;
            text-transform: uppercase;
        }

        .data-table td {
            padding: 15px 18px;
            border-top: 1px solid #f0ede7;
            color: #526863;
            font-size: 10px;
            vertical-align: middle;
        }

        .cell-title {
            color: #17463e;
            font-size: 11px;
            font-weight: 700;
        }

        .cell-sub {
            margin-top: 3px;
            color: #929d99;
            font-size: 9px;
        }

        .badge-status {
            display: inline-flex;
            padding: 5px 8px;
            border-radius: 7px;
            font-size: 8px;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-status.pending {
            background: #fff3df;
            color: #a66a28;
        }

        .badge-status.occupied {
            background: #e8f4ed;
            color: #3c7658;
        }

        .badge-status.overdue {
            background: #fff0f0;
            color: #a44b4b;
        }

        /* =========================================================
           RESPONSIVE
        ========================================================= */

        @media (max-width: 991px) {

            .app-navbar {
                padding: 10px 18px;
            }

            .app-nav-link {
                padding: 12px 10px !important;
            }

            .app-nav-link.active::after {
                display: none;
            }

            .navbar-actions {
                margin-top: 10px;
                padding-bottom: 8px;
            }

            .search-field {
                border-right: 0;
                border-bottom: 1px solid #eee9df;
            }

            .user-chip {
                min-width: 210px;
            }
        }

        @media (max-width: 575px) {

            .hero {
                padding: 42px 18px 35px;
            }

            .hero h1 {
                font-size: 34px;
            }

            .page-wrap {
                padding: 35px 16px 50px;
            }

            .page-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .room-img {
                height: 220px;
            }

            .data-table {
                min-width: 540px;
            }

            .panel {
                overflow-x: auto;
            }

            html {
                scroll-behavior: smooth;
            }

            #search-panel {
                scroll-margin-top: 25px;
            }
        }
    </style>
</head>


<body>
    @php
        $userName = $user->name ?? 'Người dùng';
        $nameParts = preg_split('/\s+/', trim($userName));
        $lastName = $nameParts[count($nameParts) - 1] ?? $userName;

        $userInitials = count($nameParts) >= 2
            ? mb_substr($nameParts[0], 0, 1)
                . mb_substr($lastName, 0, 1)
            : mb_substr($userName, 0, 2);

        $userInitials = mb_strtoupper($userInitials);
    @endphp

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <a class="logo" href="{{ route('tenant.dashboard') }}">
                Trọ <span>Ơi</span>
            </a>

            <button class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu"
                aria-controls="mainMenu"
                aria-expanded="false"
                aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="app-nav-link active text-decoration-none"
                           href="{{ route('tenant.dashboard') }}">
                            Trang chủ
                        </a>
                    </li>

                   <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('rooms.index') }}">Tìm phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('favorites.index') }}">Yêu thích</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('appointments.index') }}">Lịch xem phòng</a>
                    </li>
                    <!-- HỢP ĐỒNG -->
                    <li class="nav-item">
                        <a class="app-nav-link active text-decoration-none" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a>
                    </li>
                    <!-- HÓA ĐƠN -->
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a>
                    </li>
                    <!-- SỬA CHỮA -->
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a>
                    </li>
                    <!-- ĐÁNH GIÁ -->
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('tenant.reviews.index') }}">Đánh giá</a>
                    </li>
                </ul>

                <div class="navbar-actions">
                    <button
                        class="notif-btn"
                        type="button"
                        title="Thông báo">
                        🔔
                        <span class="notif-dot"></span>
                    </button>

                    <div class="dropdown user-dropdown">
                        <button class="user-chip dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="user-avatar">
                                {{ $userInitials }}
                            </span>

                            <span class="user-meta">
                                <span class="user-name d-block">
                                    {{ $userName }}
                                </span>
                                <span class="user-role">Người thuê</span>
                            </span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">
                                    {{ $userName }}
                                </div>
                                <div class="dropdown-user-email">
                                    {{ $user->email ?? '' }}
                                </div>
                            </div>

                            <div class="dropdown-divider-custom"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item-custom dropdown-logout">
                                    <span class="logout-icon">↪</span>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-kicker">
                👋 CHÀO {{ mb_strtoupper($lastName) }},
                TÌM PHÒNG MỚI HÔM NAY?
            </div>

            <h1>
                Tìm phòng phù hợp,<br>
                <span class="highlight">ở đúng nơi bạn muốn.</span>
            </h1>

            <p class="hero-desc">
                Khám phá phòng trọ đang được đăng cho thuê.
                Lọc theo khu vực, giá và diện tích để tìm nơi ở phù hợp.
            </p>

            <!-- FORM TÌM KIẾM -->
            <form method="GET"
                action="{{ route('tenant.dashboard') }}"
                class="search-panel"
                id="search-panel">

                <div class="row g-0 align-items-center">
                    <div class="col-lg-4">
                        <div class="search-field">
                            <label class="field-label" for="location">
                                Bạn muốn tìm ở đâu?
                            </label>
                            <input class="form-control"
                                id="location"
                                name="location"
                                value="{{ request('location') }}"
                                placeholder="Tên đường, quận, khu vực">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="search-field">
                            <label class="field-label" for="max_price">
                                Giá tối đa mỗi tháng (VNĐ)
                            </label>
                            <input class="form-control"
                                id="max_price"
                                name="max_price"
                                type="number"
                                min="0"
                                value="{{ request('max_price') }}"
                                placeholder="Ví dụ: 3000000">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="search-field">
                            <label class="field-label" for="min_area">
                                Diện tích tối thiểu (m²)
                            </label>
                            <input class="form-control"
                                id="min_area"
                                name="min_area"
                                type="number"
                                min="0"
                                step="0.1"
                                value="{{ request('min_area') }}"
                                placeholder="Ví dụ: 20">
                        </div>
                    </div>

                    <div class="col-lg-3 p-2">
                        <button type="submit" class="search-btn">
                            🔎 Tìm kiếm phòng
                        </button>
                    </div>
                </div>
            </form>

            <div class="quick-filters">
                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 2000000]) }}">
                    ≤ 2 triệu
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 3000000]) }}">
                    ≤ 3 triệu
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 5000000]) }}">
                    ≤ 5 triệu
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard') }}">
                    Xóa bộ lọc
                </a>
            </div>
        </div>
    </section>

    <!-- PHÒNG GỢI Ý -->
    <main class="page-wrap" id="rooms">
        <div class="page-header">
            <div>
                <h2 class="page-title">Phòng gợi ý cho bạn</h2>
                <div class="page-desc">
                    Phòng đang trống và có bài đăng cho thuê hợp lệ.
                </div>
            </div>

            <a href="{{ route('rooms.index') }}" class="section-link">
                Xem tất cả →
            </a>
        </div>

        <div class="row g-3">
            @forelse ($rooms as $room)
                @php
                    $images = json_decode($room->images ?? '[]', true);

                    $roomImage = is_array($images)
                        ? ($images[0] ?? null)
                        : null;

                    $isFavorite = in_array(
                        (int) $room->id,
                        $favoriteRoomIds,
                        true
                    );
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="room-card">
                        <a href="{{ route('favorites.index') }}"
                            class="fav-btn d-flex align-items-center justify-content-center"
                            title="{{ $isFavorite ? 'Đã yêu thích' : 'Xem danh sách yêu thích' }}"
                            style="{{ $isFavorite ? 'color:#b45454;' : 'color:#ccc;' }}">
                            {{ $isFavorite ? '♥' : '♡' }}
                        </a>

                        @if ($roomImage)
                            <img class="room-img"
                                src="{{ $roomImage }}"
                                alt="{{ $room->name }}"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';">
                        @else
                            <img class="room-img"
                                src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80"
                                alt="Hình phòng trọ">
                        @endif

                        <div class="room-body">
                            <div class="room-badges">
                                <span class="badge-room">
                                    Đang cho thuê
                                </span>
                            </div>

                            <div class="room-title">
                                {{ $room->name ?: 'Phòng ' . $room->room_code }}
                            </div>

                            <div class="room-address">
                                {{ $room->property_address ?: 'Chưa cập nhật địa chỉ' }}
                            </div>

                            <div class="room-stats">
                                <span>📐 {{ $room->area }} m²</span>
                                <span>👥 Tối đa {{ $room->max_people }} người</span>
                                <span>🏷️ {{ $room->room_code }}</span>
                            </div>

                            <div class="room-bottom">
                                <div class="room-price">
                                    {{ number_format((float) $room->price, 0, ',', '.') }}
                                    <small>đ/tháng</small>
                                </div>

                                <a href="{{ route('rooms.show', $room->id) }}"
                                    class="detail-btn">
                                    Xem phòng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="panel p-4 text-center">
                        <div style="font-size:36px;">🏠</div>
                        <h3 class="panel-title mt-3">
                            Chưa tìm thấy phòng phù hợp
                        </h3>
                        <p class="page-desc mb-3">
                            Hiện chưa có phòng thỏa mãn điều kiện tìm kiếm.
                            Em có thể thử đổi khu vực, giá hoặc diện tích.
                        </p>
                        <a href="{{ route('tenant.dashboard') }}"
                            class="detail-btn">
                            Xem tất cả phòng gợi ý
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- LỊCH XEM PHÒNG -->
        <div class="row g-3 mt-4">
            <div class="col-lg-6" id="appointments">
                <div class="panel">
                    <div class="panel-head">
                        <h3 class="panel-title">Lịch xem phòng gần đây</h3>
                        <a href="{{ route('appointments.index') }}"
                            class="panel-link">
                            Xem tất cả →
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Phòng</th>
                                    <th>Ngày giờ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($appointments as $appointment)
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'rescheduled' => 'Đề xuất đổi lịch',
                                            'rejected' => 'Bị từ chối',
                                            'cancelled' => 'Đã hủy',
                                            'completed' => 'Đã hoàn thành',
                                        ];

                                        $statusClasses = [
                                            'pending' => 'pending',
                                            'confirmed' => 'occupied',
                                            'rescheduled' => 'pending',
                                            'rejected' => 'overdue',
                                            'cancelled' => 'overdue',
                                            'completed' => 'occupied',
                                        ];

                                        $status = $appointment->status;
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="cell-title">
                                                {{ $appointment->room_name ?: 'Phòng ' . $appointment->room_code }}
                                            </div>
                                            <div class="cell-sub">
                                                {{ $appointment->property_address ?: 'Chưa cập nhật địa chỉ' }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                            <div class="cell-sub">
                                                {{ substr($appointment->appointment_time, 0, 5) }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge-status {{ $statusClasses[$status] ?? 'pending' }}">
                                                {{ $statusLabels[$status] ?? $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            Em chưa có lịch xem phòng nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- HÓA ĐƠN: CHƯA HIỂN THỊ DỮ LIỆU GIẢ -->
            <div class="col-lg-6" id="invoices">
                <div class="panel">
                    <div class="panel-head">
                        <h3 class="panel-title">Hóa đơn</h3>
                    </div>

                    <div class="p-4 text-center">
                        <div style="font-size:32px;">🧾</div>
                        <p class="page-desc mt-2 mb-0">
                            Phần hóa đơn sẽ được kết nối với dữ liệu
                            thực tế sau khi xác định cấu trúc bảng hóa đơn.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- HỢP ĐỒNG VÀ THÔNG BÁO -->
        <div class="row g-3 mt-1">
            <div class="col-lg-6" id="contracts">
                <div class="panel p-4">
                    <h3 class="panel-title">Hợp đồng thuê</h3>
                    <p class="page-desc mb-0">
                        Phần hợp đồng sẽ được kết nối với hợp đồng
                        của người thuê sau khi bổ sung truy vấn backend.
                    </p>
                </div>
            </div>

            <div class="col-lg-6" id="notifications">
                <div class="panel p-4">
                    <h3 class="panel-title">Thông báo</h3>
                    <p class="page-desc mb-0">
                        Phần thông báo sẽ được kết nối với dữ liệu
                        thông báo của tài khoản đang đăng nhập.
                    </p>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

</html>