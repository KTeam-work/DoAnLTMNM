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
        rel="stylesheet"
    >

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
           NAVBAR
        ========================================================= */

        .app-navbar {
            background: #20584f;
            min-height: 76px;
            padding: 0 28px;
            box-shadow: 0 4px 18px rgba(23, 70, 62, .12);
        }

        .app-navbar .container-fluid {
            max-width: 1500px;
        }

        .logo {
            color: #ffffff;
            font-size: 25px;
            font-weight: 800;
            letter-spacing: -.5px;
            white-space: nowrap;
        }

        .logo span {
            color: #f2dfb5;
        }

        .app-nav-link {
            color: rgba(255, 255, 255, .78);
            font-size: 14px;
            font-weight: 600;
            padding: 28px 15px !important;
            transition: .2s ease;
        }

        .app-nav-link:hover,
        .app-nav-link.active {
            color: #ffffff;
        }

        .app-nav-link.active {
            position: relative;
        }

        .app-nav-link.active::after {
            content: "";
            position: absolute;
            left: 15px;
            right: 15px;
            bottom: 16px;
            height: 3px;
            border-radius: 99px;
            background: #f2dfb5;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        /* =========================================================
           NOTIFICATION
        ========================================================= */

        .notif-btn {
            position: relative;
            width: 42px;
            height: 42px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 12px;
            background: rgba(255,255,255,.08);
            color: #fff;
            font-size: 18px;
            cursor: pointer;
            transition: .2s ease;
        }

        .notif-btn:hover {
            background: rgba(255,255,255,.15);
        }

        .notif-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #f2dfb5;
            border: 2px solid #20584f;
        }

        /* =========================================================
           USER DROPDOWN
        ========================================================= */

        .user-dropdown {
            position: relative;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 185px;
            padding: 7px 10px;
            border-radius: 14px;
            border: 1px solid rgba(255,255,255,.14);
            background: rgba(255,255,255,.07);
            color: #fff;
            cursor: pointer;
            transition: .2s ease;
        }

        .user-chip:hover,
        .user-chip.show {
            background: rgba(255,255,255,.14);
            color: #fff;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            flex: 0 0 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f2dfb5;
            color: #17463e;
            font-size: 13px;
            font-weight: 800;
        }

        .user-meta {
            min-width: 0;
            line-height: 1.2;
        }

        .user-name {
            max-width: 115px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
            font-weight: 700;
        }

        .user-role {
            display: block;
            margin-top: 3px;
            color: rgba(255,255,255,.65);
            font-size: 11px;
            font-weight: 500;
        }

        .caret {
            margin-left: auto;
            color: rgba(255,255,255,.7);
            font-size: 13px;
            transition: transform .2s ease;
        }

        .user-chip.show .caret {
            transform: rotate(180deg);
        }

        .user-dropdown-menu {
            width: 250px;
            margin-top: 10px !important;
            padding: 10px;
            border: 1px solid #e8e1d4;
            border-radius: 16px;
            background: #ffffff;
            box-shadow: 0 18px 45px rgba(23,70,62,.16);
        }

        .dropdown-user-info {
            padding: 10px 12px 12px;
        }

        .dropdown-user-name {
            color: #17463e;
            font-size: 14px;
            font-weight: 800;
        }

        .dropdown-user-email {
            margin-top: 3px;
            color: #7c8985;
            font-size: 11px;
            word-break: break-word;
        }

        .dropdown-divider-custom {
            height: 1px;
            margin: 4px 0 8px;
            background: #eee8dc;
        }

        .dropdown-item-custom {
            display: flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            padding: 10px 12px;
            border: 0;
            border-radius: 10px;
            background: transparent;
            color: #315b54;
            font-size: 13px;
            font-weight: 600;
            text-align: left;
            transition: .2s ease;
        }

        .dropdown-item-custom:hover {
            background: #f3f6f4;
            color: #17463e;
        }

        .dropdown-logout {
            color: #a04444;
        }

        .dropdown-logout:hover {
            background: #fff1f1;
            color: #9a3030;
        }

        .logout-icon {
            width: 27px;
            height: 27px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            background: #f4f5f3;
            font-size: 13px;
        }

        .dropdown-logout .logout-icon {
            background: #fff0f0;
        }

        /* =========================================================
           HERO
        ========================================================= */

        .hero {
            position: relative;
            overflow: hidden;
            padding: 64px 24px 52px;
            background:
                radial-gradient(circle at 85% 10%, rgba(242,223,181,.35), transparent 28%),
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
            background: rgba(242,223,181,.08);
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
            color: rgba(255,255,255,.75);
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
            box-shadow: 0 18px 45px rgba(0,0,0,.15);
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
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 99px;
            background: rgba(255,255,255,.08);
            color: rgba(255,255,255,.8);
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
            box-shadow: 0 8px 25px rgba(23,70,62,.05);
            transition: .25s ease;
        }

        .room-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(23,70,62,.11);
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
            background: rgba(255,255,255,.92);
            color: #78908a;
            font-size: 18px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,.08);
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
            box-shadow: 0 8px 25px rgba(23,70,62,.04);
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
        }
    </style>
</head>

<body>

@php
    $user = auth()->user();

    $userName = $user?->name ?? 'Người dùng';

    $nameParts = preg_split('/\s+/', trim($userName));

    if (count($nameParts) >= 2) {
        $userInitials =
            mb_substr($nameParts[0], 0, 1) .
            mb_substr($nameParts[count($nameParts) - 1], 0, 1);
    } else {
        $userInitials = mb_substr($userName, 0, 2);
    }

    $userInitials = mb_strtoupper($userInitials);
@endphp


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-expand-lg app-navbar">

    <div class="container-fluid">

        <!-- LOGO -->
        <a class="logo" href="{{ route('tenant.dashboard') }}">
            Trọ <span>Ơi</span>
        </a>


        <!-- MOBILE BUTTON -->
        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainMenu"
            aria-controls="mainMenu"
            aria-expanded="false"
            aria-label="Mở menu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- MENU -->
        <div class="collapse navbar-collapse" id="mainMenu">

            <ul class="navbar-nav mx-auto align-items-lg-center">

                <li class="nav-item">
                    <a
                        class="app-nav-link active"
                        href="{{ route('tenant.dashboard') }}"
                    >
                        Trang chủ
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#rooms"
                    >
                        Tìm phòng
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Yêu thích
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Lịch xem phòng
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Hợp đồng
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Hóa đơn
                    </a>
                </li>

            </ul>


            <!-- RIGHT ACTIONS -->
            <div class="navbar-actions">

                <!-- THÔNG BÁO -->
                <button
                    type="button"
                    class="notif-btn"
                    title="Thông báo"
                >
                    🔔
                    <span class="notif-dot"></span>
                </button>


                <!-- USER DROPDOWN -->
                <div class="dropdown user-dropdown">

                    <button
                        type="button"
                        class="user-chip dropdown-toggle"
                        data-bs-toggle="dropdown"
                        aria-expanded="false"
                    >

                        <span class="user-avatar">
                            {{ $userInitials }}
                        </span>

                        <span class="user-meta">

                            <span class="user-name d-block">
                                {{ $userName }}
                            </span>

                            <span class="user-role">
                                Người thuê
                            </span>

                        </span>



                    </button>


                    <!-- DROPDOWN -->
                    <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">

                        <!-- USER INFO -->
                        <div class="dropdown-user-info">

                            <div class="dropdown-user-name">
                                {{ $userName }}
                            </div>

                            <div class="dropdown-user-email">
                                {{ $user?->email }}
                            </div>

                        </div>


                        <div class="dropdown-divider-custom"></div>


                        <!-- THÔNG TIN -->
                        <a
                            href="#"
                            class="dropdown-item-custom"
                        >
                            <span class="logout-icon">
                                👤
                            </span>

                            Thông tin tài khoản
                        </a>


                        <!-- ĐĂNG XUẤT -->
                        <form
                            method="POST"
                            action="{{ route('logout') }}"
                            class="m-0"
                        >

                            @csrf

                            <button
                                type="submit"
                                class="dropdown-item-custom dropdown-logout"
                            >

                                <span class="logout-icon">
                                    ↪
                                </span>

                                Đăng xuất

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>



<!-- =========================================================
     HERO
========================================================= -->

<section class="hero">

    <div class="hero-inner">

        <div class="hero-kicker">
            👋 CHÀO {{ mb_strtoupper($nameParts[count($nameParts) - 1]) }},
            TÌM PHÒNG MỚI HÔM NAY?
        </div>


        <h1>
            Tìm phòng phù hợp,
            <br>
            <span class="highlight">
                ở đúng nơi bạn muốn.
            </span>
        </h1>


        <p class="hero-desc">
            Khám phá hàng trăm phòng trọ, căn hộ và nhà cho thuê.
            Lọc theo khu vực, mức giá, diện tích và tiện ích để tìm đúng nơi bạn cần.
        </p>


        <!-- SEARCH -->
        <div class="search-panel">

            <div class="row g-0 align-items-center">

                <div class="col-lg-3">

                    <div class="search-field">

                        <span class="field-label">
                            Bạn muốn tìm ở đâu?
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

                    <button
                        type="button"
                        class="search-btn"
                    >
                        🔎 Tìm kiếm phòng
                    </button>

                </div>

            </div>

        </div>


        <!-- QUICK FILTERS -->
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
     CONTENT
========================================================= -->

<div class="page-wrap" id="rooms">


    <!-- SECTION HEADER -->
    <div class="page-header">

        <div>

            <h2 class="page-title">
                Phòng gợi ý cho bạn
            </h2>

            <div class="page-desc">
                Dựa trên khu vực và mức giá bạn đã tìm gần đây.
            </div>

        </div>

        <a
            class="section-link"
            href="#"
        >
            Xem tất cả →
        </a>

    </div>



    <!-- =====================================================
         ROOM CARDS
    ====================================================== -->

    <div class="row g-3">


        <!-- ROOM 1 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    type="button"
                    class="fav-btn"
                    title="Thêm vào yêu thích"
                >
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng trọ"
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

                        <span>
                            📐 22 m²
                        </span>

                        <span>
                            ❄️ Máy lạnh
                        </span>

                        <span>
                            🚗 Bãi xe
                        </span>

                    </div>


                    <div class="room-bottom">

                        <div class="room-price">
                            2,8 triệu
                            <small>/ tháng</small>
                        </div>

                        <button
                            type="button"
                            class="detail-btn"
                        >
                            Xem phòng
                        </button>

                    </div>

                </div>

            </div>

        </div>



        <!-- ROOM 2 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    type="button"
                    class="fav-btn"
                    title="Thêm vào yêu thích"
                >
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1000&q=80"
                    alt="Căn hộ"
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

                        <span>
                            📐 35 m²
                        </span>

                        <span>
                            🛋️ Full nội thất
                        </span>

                        <span>
                            🚗 Bãi xe
                        </span>

                    </div>


                    <div class="room-bottom">

                        <div class="room-price">
                            6,8 triệu
                            <small>/ tháng</small>
                        </div>

                        <button
                            type="button"
                            class="detail-btn"
                        >
                            Xem phòng
                        </button>

                    </div>

                </div>

            </div>

        </div>



        <!-- ROOM 3 -->
        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    type="button"
                    class="fav-btn"
                    title="Thêm vào yêu thích"
                >
                    ♥
                </button>

                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng"
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

                        <span>
                            📐 24 m²
                        </span>

                        <span>
                            🪟 Cửa sổ
                        </span>

                        <span>
                            🕐 Tự do
                        </span>

                    </div>


                    <div class="room-bottom">

                        <div class="room-price">
                            3,2 triệu
                            <small>/ tháng</small>
                        </div>

                        <button
                            type="button"
                            class="detail-btn"
                        >
                            Xem phòng
                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         BOTTOM PANELS
    ====================================================== -->

    <div class="row g-3 mt-1">


        <!-- LỊCH XEM PHÒNG -->
        <div class="col-lg-6">

            <div class="panel">

                <div class="panel-head">

                    <h3 class="panel-title">
                        Lịch xem phòng sắp tới
                    </h3>

                    <a
                        class="panel-link"
                        href="#"
                    >
                        Xem tất cả
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

                            <tr>

                                <td>

                                    <div class="cell-title">
                                        Phòng trọ máy lạnh Q.7
                                    </div>

                                    <div class="cell-sub">
                                        Chủ nhà: Anh Tuấn
                                    </div>

                                </td>

                                <td>
                                    28/08 · 15:00
                                </td>

                                <td>

                                    <span class="badge-status pending">
                                        Chờ xác nhận
                                    </span>

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    <div class="cell-title">
                                        Căn hộ mini An Phú
                                    </div>

                                    <div class="cell-sub">
                                        Chủ nhà: Chị Lan
                                    </div>

                                </td>

                                <td>
                                    30/08 · 09:30
                                </td>

                                <td>

                                    <span class="badge-status occupied">
                                        Đã xác nhận
                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>



        <!-- HÓA ĐƠN -->
        <div class="col-lg-6">

            <div class="panel">

                <div class="panel-head">

                    <h3 class="panel-title">
                        Hóa đơn gần đây
                    </h3>

                    <a
                        class="panel-link"
                        href="#"
                    >
                        Xem tất cả
                    </a>

                </div>


                <div class="table-responsive">

                    <table class="data-table">

                        <thead>

                            <tr>
                                <th>Kỳ hóa đơn</th>
                                <th>Số tiền</th>
                                <th>Trạng thái</th>
                            </tr>

                        </thead>


                        <tbody>

                            <tr>

                                <td>

                                    <div class="cell-title">
                                        Tháng 08/2026
                                    </div>

                                    <div class="cell-sub">
                                        Phòng 12A, Q.7
                                    </div>

                                </td>

                                <td>
                                    3.450.000 đ
                                </td>

                                <td>

                                    <span class="badge-status occupied">
                                        Đã thanh toán
                                    </span>

                                </td>

                            </tr>


                            <tr>

                                <td>

                                    <div class="cell-title">
                                        Tháng 07/2026
                                    </div>

                                    <div class="cell-sub">
                                        Phòng 12A, Q.7
                                    </div>

                                </td>

                                <td>
                                    3.200.000 đ
                                </td>

                                <td>

                                    <span class="badge-status overdue">
                                        Quá hạn
                                    </span>

                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>



<!-- =========================================================
     BOOTSTRAP
========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>