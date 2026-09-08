<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Tìm phòng</title>

    <!-- Bootstrap có sẵn -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font có sẵn -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- CSS có sẵn -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

<!-- =========================================================
     NAVBAR
========================================================= -->
<nav class="navbar navbar-expand-lg app-navbar">

    <div class="container-fluid">

        <a class="logo" href="{{ url('/tenant') }}">
            Trọ <span>Ơi</span>
        </a>

        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainMenu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">

            <ul class="navbar-nav mx-auto align-items-lg-center">

            <li class="nav-item">
    <a class="app-nav-link" href="{{ route('tenant') }}">
        Trang chủ
    </a>
</li>

                <li class="nav-item">
                    <a class="app-nav-link active"
                       href="{{ route('rooms.index') }}">
                        Tìm phòng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="{{ route('favorites.index') }}">
                        Yêu thích
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="{{ route('appointments.index') }}">
                        Lịch xem phòng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="#">
                        Hợp đồng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="#">
                        Hóa đơn
                    </a>
                </li>

            </ul>


            <div class="navbar-actions">

                <button class="notif-btn">
                    🔔
                    <span class="notif-dot"></span>
                </button>

                <a href="#" class="user-chip">

                    <span class="user-avatar">
                        TH
                    </span>

                    <span class="user-meta">

                        <span class="user-name d-block">
                            Thanh Huyền
                        </span>

                        <span class="user-role">
                            Người thuê
                        </span>

                    </span>

                    <span class="caret">
                        ▾
                    </span>

                </a>

            </div>

        </div>

    </div>

</nav>


<!-- =========================================================
     HEADER
========================================================= -->
<section class="hero">

    <div class="hero-inner">

        <div class="hero-kicker">
            🔎 TÌM KIẾM PHÒNG TRỌ
        </div>

        <h1>
            Tìm phòng phù hợp,
            <br>
            <span class="highlight">
                ở đúng nơi bạn muốn.
            </span>
        </h1>

        <p class="hero-desc">
            Khám phá phòng trọ, căn hộ và nhà cho thuê phù hợp
            với nhu cầu của bạn.
        </p>


        <!-- SEARCH -->
        <div class="search-panel">

            <div class="row g-0 align-items-center">

                <div class="col-lg-3">
                    <div class="search-field">

                        <span class="field-label">
                            Khu vực
                        </span>

                        <div class="field-value">
                            📍 TP. Hồ Chí Minh
                        </div>

                    </div>
                </div>


                <div class="col-lg-2">

                    <div class="search-field">

                        <span class="field-label">
                            Khoảng giá
                        </span>

                        <div class="field-value field-muted">
                            Tất cả mức giá
                        </div>

                    </div>

                </div>


                <div class="col-lg-2">

                    <div class="search-field">

                        <span class="field-label">
                            Diện tích
                        </span>

                        <div class="field-value field-muted">
                            Tất cả
                        </div>

                    </div>

                </div>


                <div class="col-lg-2">

                    <div class="search-field">

                        <span class="field-label">
                            Tiện ích
                        </span>

                        <div class="field-value field-muted">
                            Tất cả
                        </div>

                    </div>

                </div>


                <div class="col-lg-3 p-2">

                    <button class="search-btn">
                        🔎 Tìm kiếm phòng
                    </button>

                </div>

            </div>

        </div>


        <!-- QUICK FILTER -->
        <div class="quick-filters">

            <span class="quick-filter">
                ≤ 2 triệu
            </span>

            <span class="quick-filter">
                2 - 3 triệu
            </span>

            <span class="quick-filter">
                3 - 5 triệu
            </span>

            <span class="quick-filter">
                Có máy lạnh
            </span>

            <span class="quick-filter">
                Có gác
            </span>

            <span class="quick-filter">
                Có ban công
            </span>

            <span class="quick-filter">
                Nuôi thú cưng
            </span>

        </div>

    </div>

</section>


<!-- =========================================================
     ROOM LIST
========================================================= -->
<div class="page-wrap">

    <div class="page-header">

        <div>

            <h2 class="page-title">
                Danh sách phòng
            </h2>

            <div class="page-desc">
                Tìm thấy 6 phòng phù hợp với nhu cầu của bạn.
            </div>

        </div>

        <a
            href="{{ url('/tenant') }}"
            class="section-link"
        >
            ← Về trang chủ
        </a>

    </div>


    <!-- =====================================================
         ROOM CARDS
    ====================================================== -->
    <div class="row g-4">


        <!-- ROOM 1 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button class="fav-btn">
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng trọ máy lạnh gần Quận 7"
                >

                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room hot">
                            🔥 HOT
                        </span>

                        <span class="badge-room">
                            Đã xác thực
                        </span>

                    </div>

                    <div class="room-title">
                        Phòng trọ máy lạnh gần Q.7
                    </div>

                    <div class="room-address">
                        Đường Nguyễn Thị Thập, Quận 7, TP.HCM
                    </div>

                    <div class="room-stats">

                        <span>📐 22 m²</span>
                        <span>❄️ Máy lạnh</span>
                        <span>🚗 Bãi xe</span>

                    </div>

                    <div class="room-bottom">

                        <div class="room-price">
                            2,8 triệu
                            <small>/ tháng</small>
                        </div>

                        <a
                            href="{{ route('rooms.show', 1) }}"
                            class="detail-btn"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ROOM 2 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button class="fav-btn">
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1000&q=80"
                    alt="Căn hộ mini đầy đủ nội thất"
                >

                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room">
                            Đã xác thực
                        </span>

                    </div>

                    <div class="room-title">
                        Căn hộ mini đầy đủ nội thất
                    </div>

                    <div class="room-address">
                        Phường An Phú, TP. Thủ Đức, TP.HCM
                    </div>

                    <div class="room-stats">

                        <span>📐 35 m²</span>
                        <span>🛋️ Full nội thất</span>
                        <span>🚗 Bãi xe</span>

                    </div>

                    <div class="room-bottom">

                        <div class="room-price">
                            6,8 triệu
                            <small>/ tháng</small>
                        </div>

                        <a
                            href="{{ route('rooms.show', 2) }}"
                            class="detail-btn"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ROOM 3 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button class="fav-btn">
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng cửa sổ lớn"
                >

                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room hot">
                            🔥 HOT
                        </span>

                        <span class="badge-room">
                            Đã xác thực
                        </span>

                    </div>

                    <div class="room-title">
                        Phòng cửa sổ lớn, giờ giấc tự do
                    </div>

                    <div class="room-address">
                        Phường Tân Thành, Tân Phú, TP.HCM
                    </div>

                    <div class="room-stats">

                        <span>📐 24 m²</span>
                        <span>🪟 Cửa sổ</span>
                        <span>🕐 Tự do</span>

                    </div>

                    <div class="room-bottom">

                        <div class="room-price">
                            3,2 triệu
                            <small>/ tháng</small>
                        </div>

                        <a
                            href="{{ route('rooms.show', 3) }}"
                            class="detail-btn"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ROOM 4 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button class="fav-btn">
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng có gác"
                >

                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room">
                            Đã xác thực
                        </span>

                    </div>

                    <div class="room-title">
                        Phòng có gác rộng, thoáng mát
                    </div>

                    <div class="room-address">
                        Đường Lê Văn Việt, TP. Thủ Đức, TP.HCM
                    </div>

                    <div class="room-stats">

                        <span>📐 28 m²</span>
                        <span>🛏️ Có gác</span>
                        <span>🚗 Bãi xe</span>

                    </div>

                    <div class="room-bottom">

                        <div class="room-price">
                            3,5 triệu
                            <small>/ tháng</small>
                        </div>

                        <a
                            href="{{ route('rooms.show', 4) }}"
                            class="detail-btn"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ROOM 5 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button class="fav-btn">
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng có ban công"
                >

                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room hot">
                            🔥 HOT
                        </span>

                    </div>

                    <div class="room-title">
                        Phòng ban công riêng, nhiều ánh sáng
                    </div>

                    <div class="room-address">
                        Nguyễn Gia Trí, Bình Thạnh, TP.HCM
                    </div>

                    <div class="room-stats">

                        <span>📐 30 m²</span>
                        <span>🌤️ Ban công</span>
                        <span>❄️ Máy lạnh</span>

                    </div>

                    <div class="room-bottom">

                        <div class="room-price">
                            4,2 triệu
                            <small>/ tháng</small>
                        </div>

                        <a
                            href="{{ route('rooms.show', 5) }}"
                            class="detail-btn"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>


        <!-- ROOM 6 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button class="fav-btn">
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80"
                    alt="Căn hộ đầy đủ tiện nghi"
                >

                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room">
                            Đã xác thực
                        </span>

                    </div>

                    <div class="room-title">
                        Căn hộ nhỏ đầy đủ tiện nghi
                    </div>

                    <div class="room-address">
                        Phường 4, Quận 3, TP.HCM
                    </div>

                    <div class="room-stats">

                        <span>📐 32 m²</span>
                        <span>🛋️ Nội thất</span>
                        <span>🧺 Máy giặt</span>

                    </div>

                    <div class="room-bottom">

                        <div class="room-price">
                            5,5 triệu
                            <small>/ tháng</small>
                        </div>

                        <a
                            href="{{ route('rooms.show', 6) }}"
                            class="detail-btn"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>