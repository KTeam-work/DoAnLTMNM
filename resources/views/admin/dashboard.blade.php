<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard - Trọ Ơi</title>

    {{-- FONT GIỐNG CÁC TRANG ADMIN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link rel="preconnect"
          href="https://fonts.gstatic.com"
          crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    {{-- BOOTSTRAP --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    {{-- CSS CHUNG --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">


    <style>

        /* =====================================================
           DASHBOARD
        ===================================================== */

        body.dashboard-body {
            background: #f6f7f9;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .dashboard-page {
            padding: 32px 0 50px;
        }


        /* =====================================================
           HEADING
        ===================================================== */

        .dashboard-heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            margin-bottom: 24px;
        }

        .dashboard-heading h1 {
            margin: 0;
            color: #202124;
            font-size: 29px;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .dashboard-heading p {
            margin: 7px 0 0;
            color: #777;
            font-size: 13px;
        }

        .dashboard-date {
            padding: 9px 14px;
            background: #fff;
            border: 1px solid #e9e9e9;
            border-radius: 10px;
            color: #777;
            font-size: 12px;
        }


        /* =====================================================
           HERO
        ===================================================== */

        .dashboard-hero {
            position: relative;
            overflow: hidden;

            min-height: 235px;

            padding: 30px 34px;

            margin-bottom: 22px;

            border-radius: 22px;

            background:
                radial-gradient(
                    circle at 82% 18%,
                    rgba(255,255,255,.24),
                    transparent 28%
                ),
                linear-gradient(
                    120deg,
                    #ed7923,
                    #f29a49
                );

            color: #fff;
        }

        .dashboard-hero-content {
            position: relative;
            z-index: 3;

            max-width: 56%;
        }

        .dashboard-hero-label {
            display: inline-flex;
            align-items: center;
            gap: 7px;

            padding: 6px 11px;

            margin-bottom: 16px;

            border-radius: 30px;

            background: rgba(255,255,255,.16);

            font-size: 11px;
            font-weight: 650;
        }

        .dashboard-hero-label span {
            font-size: 9px;
        }

        .dashboard-hero h2 {
            margin: 0 0 10px;

            font-size: 29px;
            line-height: 1.28;

            font-weight: 800;
            letter-spacing: -.5px;
        }

        .dashboard-hero p {
            max-width: 540px;

            margin: 0;

            color: rgba(255,255,255,.91);

            font-size: 13px;
            line-height: 1.7;
        }


        /* =====================================================
           HOUSE ILLUSTRATION
        ===================================================== */

        .dashboard-house {
            position: absolute;

            right: 48px;
            bottom: 0;

            width: 310px;
            height: 205px;
        }

        .house-back {
            position: absolute;

            right: 15px;
            bottom: 0;

            width: 215px;
            height: 145px;

            border-radius: 8px 8px 0 0;

            background: rgba(255,255,255,.15);

            transform: skewY(-5deg);
        }

        .house-main {
            position: absolute;

            right: 58px;
            bottom: 0;

            width: 180px;
            height: 125px;

            border-radius: 7px 7px 0 0;

            background: #fff;

            box-shadow:
                0 15px 35px rgba(100,45,8,.15);
        }

        .house-roof {
            position: absolute;

            right: 43px;
            bottom: 114px;

            width: 210px;
            height: 70px;

            background: #fff;

            clip-path: polygon(
                50% 0,
                100% 100%,
                0 100%
            );
        }

        .house-door {
            position: absolute;

            left: 72px;
            bottom: 0;

            width: 39px;
            height: 65px;

            border-radius: 5px 5px 0 0;

            background: #f1a05e;
        }

        .house-window {
            position: absolute;

            width: 34px;
            height: 34px;

            border-radius: 5px;

            background: #ffe7d2;

            border: 4px solid #ef7d27;
        }

        .house-window.one {
            left: 25px;
            top: 33px;
        }

        .house-window.two {
            right: 25px;
            top: 33px;
        }

        .house-tree {
            position: absolute;

            left: 5px;
            bottom: 0;

            width: 65px;
            height: 105px;
        }

        .tree-top {
            position: absolute;

            left: 0;
            top: 0;

            width: 65px;
            height: 65px;

            border-radius: 50%;

            background: rgba(255,255,255,.29);
        }

        .tree-trunk {
            position: absolute;

            left: 28px;
            bottom: 0;

            width: 10px;
            height: 55px;

            border-radius: 5px;

            background: rgba(255,255,255,.55);
        }


        /* =====================================================
           MAIN GRID
        ===================================================== */

        .dashboard-grid {
            display: grid;

            grid-template-columns: 1.55fr .85fr;

            gap: 22px;

            margin-bottom: 22px;
        }

        .dashboard-card {
            background: #fff;

            border: 1px solid #ebebeb;

            border-radius: 18px;

            padding: 22px;

            box-shadow:
                0 4px 16px rgba(0,0,0,.025);
        }

        .dashboard-card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;

            margin-bottom: 19px;
        }

        .dashboard-card-title {
            display: flex;
            align-items: center;
            gap: 9px;
        }

        .dashboard-card-title h3 {
            margin: 0;

            color: #222;

            font-size: 16px;
            font-weight: 750;
        }

        .dashboard-card-title span {
            color: #999;
            font-size: 11px;
        }

        .dashboard-more {
            color: #ef7d27;

            font-size: 11px;
            font-weight: 650;
        }


        /* =====================================================
           AREA / MAP
        ===================================================== */

        .area-layout {
            display: grid;

            grid-template-columns: 1fr 185px;

            gap: 22px;

            align-items: center;
        }

        .fake-map {
            position: relative;

            height: 220px;

            overflow: hidden;

            border-radius: 15px;

            background:
                linear-gradient(
                    120deg,
                    transparent 48%,
                    #fff 49%,
                    #fff 51%,
                    transparent 52%
                ),
                linear-gradient(
                    30deg,
                    transparent 47%,
                    #fff 48%,
                    #fff 50%,
                    transparent 51%
                ),
                #eef1ef;
        }

        .fake-map::before,
        .fake-map::after {
            content: "";

            position: absolute;

            background: #dce4df;

            opacity: .8;
        }

        .fake-map::before {
            width: 150%;
            height: 12px;

            top: 80px;
            left: -30px;

            transform: rotate(-17deg);
        }

        .fake-map::after {
            width: 150%;
            height: 9px;

            top: 155px;
            left: -30px;

            transform: rotate(22deg);
        }

        .map-label {
            position: absolute;

            color: #717775;

            font-size: 10px;
            font-weight: 600;

            z-index: 2;
        }

        .map-label.q1 {
            top: 35px;
            left: 28px;
        }

        .map-label.q3 {
            top: 94px;
            left: 102px;
        }

        .map-label.q5 {
            bottom: 35px;
            left: 35px;
        }

        .map-label.bt {
            top: 45px;
            right: 35px;
        }

        .map-label.td {
            bottom: 45px;
            right: 25px;
        }

        .map-point {
            position: absolute;

            width: 12px;
            height: 12px;

            border-radius: 50%;

            background: #ef7d27;

            border: 3px solid #fff;

            box-shadow:
                0 2px 8px rgba(239,125,39,.35);

            z-index: 3;
        }

        .map-point.p1 {
            top: 61px;
            left: 72px;
        }

        .map-point.p2 {
            top: 105px;
            left: 140px;
        }

        .map-point.p3 {
            top: 76px;
            left: 205px;
        }

        .map-point.p4 {
            top: 145px;
            left: 105px;
        }

        .map-point.p5 {
            top: 130px;
            left: 220px;
        }

        .map-point.p6 {
            top: 172px;
            left: 175px;
        }


        /* AREA LIST */

        .area-list {
            display: flex;

            flex-direction: column;

            gap: 16px;
        }

        .area-item {
            display: grid;

            grid-template-columns:
                32px
                1fr
                auto;

            align-items: center;

            gap: 8px;
        }

        .area-number {
            width: 29px;
            height: 29px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 8px;

            background: #fff1e5;

            color: #ef7d27;

            font-size: 10px;
            font-weight: 800;
        }

        .area-name {
            color: #333;

            font-size: 12px;
            font-weight: 650;
        }

        .area-count {
            color: #999;

            font-size: 10px;
        }


        /* =====================================================
           USER OVERVIEW
        ===================================================== */

        .user-overview {
            text-align: center;

            padding: 5px;
        }

        .user-circle {
            width: 112px;
            height: 112px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin: 4px auto 17px;

            border-radius: 50%;

            background:
                conic-gradient(
                    #ef7d27 0deg 339deg,
                    #eeeeee 339deg 360deg
                );
        }

        .user-circle-inner {
            width: 85px;
            height: 85px;

            display: flex;

            flex-direction: column;

            align-items: center;
            justify-content: center;

            border-radius: 50%;

            background: #fff;
        }

        .user-circle-inner strong {
            color: #222;

            font-size: 21px;
            font-weight: 800;
        }

        .user-circle-inner span {
            margin-top: 2px;

            color: #999;

            font-size: 9px;
        }

        .user-overview h4 {
            margin: 0;

            color: #222;

            font-size: 16px;
            font-weight: 750;
        }

        .user-overview p {
            margin: 5px 0 18px;

            color: #999;

            font-size: 11px;
        }

        .user-types {
            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 9px;
        }

        .user-type {
            padding: 11px 7px;

            border-radius: 10px;

            background: #f8f8f8;
        }

        .user-type strong {
            display: block;

            color: #333;

            font-size: 14px;
        }

        .user-type span {
            color: #999;

            font-size: 9px;
        }


        /* =====================================================
           CATEGORY
        ===================================================== */

        .category-grid {
            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 12px;
        }

        .category {
            position: relative;

            min-height: 142px;

            overflow: hidden;

            padding: 16px;

            border-radius: 15px;

            background: #f8f8f8;

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }

        .category:hover {
            transform: translateY(-3px);

            box-shadow:
                0 8px 22px rgba(0,0,0,.06);
        }

        .category-icon {
            width: 39px;
            height: 39px;

            display: flex;

            align-items: center;
            justify-content: center;

            margin-bottom: 26px;

            border-radius: 11px;

            background: #fff;

            color: #ef7d27;
        }

        .category strong {
            display: block;

            margin-bottom: 4px;

            color: #292929;

            font-size: 13px;
            font-weight: 700;
        }

        .category small {
            color: #999;

            font-size: 10px;
        }

        /* SỐ ĐƯỢC LÀM RÕ HƠN */

        .category-number {
            position: absolute;

            right: 14px;
            bottom: 10px;

            color: #e97824;

            font-size: 25px;
            font-weight: 800;

            letter-spacing: -.5px;

            opacity: .82;
        }


        /* =====================================================
           INSIGHT
        ===================================================== */

        .insight-list {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 12px;
        }

        .insight {
            padding: 16px;

            border: 1px solid #ededed;

            border-radius: 14px;
        }

        .insight-label {
            display: flex;

            align-items: center;

            gap: 7px;

            margin-bottom: 9px;

            color: #888;

            font-size: 11px;
        }

        .insight-label span {
            color: #ef7d27;
        }

        .insight-value {
            color: #242424;

            font-size: 21px;
            font-weight: 800;
        }

        .insight-change {
            margin-top: 4px;

            color: #35a56b;

            font-size: 10px;
            font-weight: 650;
        }


        /* =====================================================
           BOTTOM
        ===================================================== */

        .bottom-grid {
            display: grid;

            grid-template-columns:
                .9fr 1.1fr;

            gap: 22px;
        }


        /* TREND */

        .trend-box {
            padding-top: 4px;
        }

        .trend-row {
            margin-bottom: 18px;
        }

        .trend-info {
            display: flex;

            justify-content: space-between;

            margin-bottom: 7px;
        }

        .trend-info span {
            color: #666;

            font-size: 11px;
        }

        .trend-info strong {
            color: #333;

            font-size: 11px;
        }

        .trend-line {
            height: 7px;

            overflow: hidden;

            border-radius: 10px;

            background: #f0f0f0;
        }

        .trend-fill {
            height: 100%;

            border-radius: inherit;

            background: #ef7d27;
        }


        /* =====================================================
           ACTIVITY
        ===================================================== */

        .activity-list {
            display: flex;

            flex-direction: column;
        }

        .activity {
            display: grid;

            grid-template-columns:
                39px
                1fr
                auto;

            align-items: center;

            gap: 10px;

            padding: 11px 0;

            border-bottom: 1px solid #f0f0f0;
        }

        .activity:last-child {
            border-bottom: none;
        }

        .activity-icon {
            width: 37px;
            height: 37px;

            display: flex;

            align-items: center;
            justify-content: center;

            border-radius: 10px;

            background: #fff1e5;

            color: #ef7d27;
        }

        .activity-text strong {
            display: block;

            margin-bottom: 3px;

            color: #333;

            font-size: 11px;
        }

        .activity-text span,
        .activity-time {
            color: #999;

            font-size: 9px;
        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 992px) {

            .dashboard-grid,
            .bottom-grid {
                grid-template-columns: 1fr;
            }

            .category-grid {
                grid-template-columns:
                    repeat(2, 1fr);
            }

            .dashboard-house {
                opacity: .4;
                right: -20px;
            }

            .dashboard-hero-content {
                max-width: 75%;
            }
        }


        @media (max-width: 650px) {

            .dashboard-page {
                padding: 22px 12px 35px;
            }

            .dashboard-heading {
                align-items: flex-start;

                flex-direction: column;

                gap: 10px;
            }

            .dashboard-hero {
                padding: 25px;
            }

            .dashboard-hero-content {
                max-width: 100%;
            }

            .dashboard-hero h2 {
                font-size: 24px;
            }

            .dashboard-house {
                display: none;
            }

            .area-layout {
                grid-template-columns: 1fr;
            }

            .category-grid,
            .insight-list {
                grid-template-columns: 1fr 1fr;
            }
        }

    </style>

</head>


<body class="dashboard-body">


    {{-- =====================================================
         NAVBAR
         GIỐNG TRANG TÀI KHOẢN
    ====================================================== --}}

    <nav class="app-navbar">

        <div class="container-fluid h-100">

            <div class="d-flex align-items-center justify-content-between h-100">


                {{-- LOGO --}}

                <a href="{{ url('/admin/dashboard') }}"
                   class="logo text-decoration-none">

                    Trọ <span>Ơi</span>

                    <small>ADMIN</small>

                </a>


                {{-- MENU --}}

                <div class="d-flex align-items-center gap-1">

                    <a href="{{ url('/admin/dashboard') }}"
                       class="app-nav-link active text-decoration-none">

                        Dashboard

                    </a>


                    <a href="{{ url('/admin/users') }}"
                       class="app-nav-link text-decoration-none">

                        Tài khoản

                    </a>


                    <a href="{{ url('/admin/rental-posts') }}"
                       class="app-nav-link text-decoration-none">

                        Kiểm duyệt tin

                    </a>


                    <a href="{{ url('/admin/statistics') }}"
                       class="app-nav-link text-decoration-none">

                        Thống kê

                    </a>

                </div>


                {{-- ADMIN --}}

                <div class="navbar-actions">

                    <div class="user-chip">

                        <div class="user-avatar">
                            AD
                        </div>


                        <div class="user-meta">

                            <div class="user-name">
                                Admin
                            </div>

                            <div class="user-role">
                                Quản trị viên
                            </div>

                        </div>

                    </div>

                </div>


            </div>

        </div>

    </nav>



    {{-- =====================================================
         DASHBOARD CONTENT
    ====================================================== --}}

    <main class="dashboard-page">

        <div class="container-fluid px-4">


            {{-- HEADING --}}

            <div class="page-header">

    <div>
        <h1 class="page-title">
            Tổng quan Trọ Ơi
        </h1>

        <p class="page-desc">
            Cái nhìn tổng thể về nền tảng cho thuê và tìm kiếm phòng trọ.
        </p>
    </div>

</div>



            {{-- =================================================
                 HERO
            ================================================== --}}

            <section class="dashboard-hero">


                <div class="dashboard-hero-content">


                    <div class="dashboard-hero-label">

                        <span>●</span>

                        Nền tảng đang hoạt động

                    </div>


                    <h2>

                        Kết nối đúng chỗ,<br>

                        tìm đúng nơi ở.

                    </h2>


                    <p>

                        Trọ Ơi giúp người tìm trọ khám phá những lựa chọn
                        phù hợp và giúp chủ trọ tiếp cận người thuê một cách
                        nhanh chóng, thuận tiện.

                    </p>


                </div>



                {{-- NHÀ --}}

                <div class="dashboard-house">


                    <div class="house-tree">

                        <div class="tree-top"></div>

                        <div class="tree-trunk"></div>

                    </div>


                    <div class="house-back"></div>


                    <div class="house-roof"></div>


                    <div class="house-main">

                        <div class="house-window one"></div>

                        <div class="house-window two"></div>

                        <div class="house-door"></div>

                    </div>


                </div>


            </section>



            {{-- =================================================
                 AREA + USERS
            ================================================== --}}

            <div class="dashboard-grid">


                {{-- KHU VỰC --}}

                <section class="dashboard-card">


                    <div class="dashboard-card-header">

                        <div class="dashboard-card-title">

                            <h3>
                                Khu vực hoạt động
                            </h3>

                            <span>
                                TP.HCM & khu vực lân cận
                            </span>

                        </div>


                        <span class="dashboard-more">
                            Xem chi tiết →
                        </span>

                    </div>



                    <div class="area-layout">


                        {{-- MAP --}}

                        <div class="fake-map">


                            <span class="map-label q1">
                                Quận 1
                            </span>


                            <span class="map-label q3">
                                Quận 3
                            </span>


                            <span class="map-label q5">
                                Quận 5
                            </span>


                            <span class="map-label bt">
                                Bình Thạnh
                            </span>


                            <span class="map-label td">
                                Thủ Đức
                            </span>


                            <span class="map-point p1"></span>

                            <span class="map-point p2"></span>

                            <span class="map-point p3"></span>

                            <span class="map-point p4"></span>

                            <span class="map-point p5"></span>

                            <span class="map-point p6"></span>


                        </div>



                        {{-- AREA LIST --}}

                        <div class="area-list">


                            <div class="area-item">

                                <div class="area-number">
                                    01
                                </div>

                                <div class="area-name">
                                    Thủ Đức
                                </div>

                                <div class="area-count">
                                    312 tin
                                </div>

                            </div>


                            <div class="area-item">

                                <div class="area-number">
                                    02
                                </div>

                                <div class="area-name">
                                    Bình Thạnh
                                </div>

                                <div class="area-count">
                                    241 tin
                                </div>

                            </div>


                            <div class="area-item">

                                <div class="area-number">
                                    03
                                </div>

                                <div class="area-name">
                                    Quận 7
                                </div>

                                <div class="area-count">
                                    186 tin
                                </div>

                            </div>


                            <div class="area-item">

                                <div class="area-number">
                                    04
                                </div>

                                <div class="area-name">
                                    Tân Bình
                                </div>

                                <div class="area-count">
                                    154 tin
                                </div>

                            </div>


                        </div>


                    </div>


                </section>



                {{-- USERS --}}

                <section class="dashboard-card">


                    <div class="dashboard-card-header">

                        <div class="dashboard-card-title">

                            <h3>
                                Cộng đồng người dùng
                            </h3>

                        </div>

                    </div>


                    <div class="user-overview">


                        <div class="user-circle">

                            <div class="user-circle-inner">

                                <strong>
                                    94.2%
                                </strong>

                                <span>
                                    hoạt động
                                </span>

                            </div>

                        </div>


                        <h4>
                            1,248 người dùng
                        </h4>


                        <p>
                            Đang sử dụng nền tảng
                        </p>


                        <div class="user-types">


                            <div class="user-type">

                                <strong>
                                    876
                                </strong>

                                <span>
                                    Người tìm trọ
                                </span>

                            </div>


                            <div class="user-type">

                                <strong>
                                    372
                                </strong>

                                <span>
                                    Chủ trọ
                                </span>

                            </div>


                        </div>


                    </div>


                </section>


            </div>



            {{-- =================================================
                 CATEGORY
            ================================================== --}}

            <section class="dashboard-card mb-4">


                <div class="dashboard-card-header">


                    <div class="dashboard-card-title">

                        <h3>
                            Thị trường nhà ở
                        </h3>

                        <span>
                            Phân loại tin đăng hiện có
                        </span>

                    </div>


                    <span class="dashboard-more">
                        1,230 tin
                    </span>


                </div>



                <div class="category-grid">


                    {{-- PHÒNG TRỌ --}}

                    <div class="category">


                        <div class="category-icon">

                            <svg width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M3 21V9l9-6 9 6v12"/>

                                <path d="M9 21v-7h6v7"/>

                            </svg>

                        </div>


                        <strong>
                            Phòng trọ
                        </strong>

                        <small>
                            55% tổng tin đăng
                        </small>


                        <span class="category-number">
                            684
                        </span>


                    </div>



                    {{-- CĂN HỘ --}}

                    <div class="category">


                        <div class="category-icon">

                            <svg width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <rect x="4"
                                      y="3"
                                      width="16"
                                      height="18"
                                      rx="2"/>

                                <path d="M8 7h2M14 7h2M8 11h2M14 11h2M8 15h2M14 15h2"/>

                            </svg>

                        </div>


                        <strong>
                            Căn hộ
                        </strong>

                        <small>
                            25% tổng tin đăng
                        </small>


                        <span class="category-number">
                            312
                        </span>


                    </div>



                    {{-- NHÀ NGUYÊN CĂN --}}

                    <div class="category">


                        <div class="category-icon">

                            <svg width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <path d="M3 11l9-7 9 7"/>

                                <path d="M5 10v10h14V10"/>

                                <path d="M9 20v-6h6v6"/>

                            </svg>

                        </div>


                        <strong>
                            Nhà nguyên căn
                        </strong>

                        <small>
                            13% tổng tin đăng
                        </small>


                        <span class="category-number">
                            156
                        </span>


                    </div>



                    {{-- Ở GHÉP --}}

                    <div class="category">


                        <div class="category-icon">

                            <svg width="20"
                                 height="20"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">

                                <circle cx="9"
                                        cy="8"
                                        r="3"/>

                                <circle cx="17"
                                        cy="9"
                                        r="2.5"/>

                                <path d="M3 20c0-3.2 2.7-5 6-5s6 1.8 6 5"/>

                                <path d="M14 15c3 0 5 1.5 5 5"/>

                            </svg>

                        </div>


                        <strong>
                            Ở ghép
                        </strong>

                        <small>
                            6% tổng tin đăng
                        </small>


                        <span class="category-number">
                            78
                        </span>


                    </div>


                </div>


            </section>



            {{-- =================================================
                 INSIGHT
            ================================================== --}}

            <section class="dashboard-card mb-4">


                <div class="dashboard-card-header">


                    <div class="dashboard-card-title">

                        <h3>
                            Nhịp phát triển nền tảng
                        </h3>

                        <span>
                            So với tháng trước
                        </span>

                    </div>


                </div>



                <div class="insight-list">


                    <div class="insight">


                        <div class="insight-label">

                            <span>●</span>

                            Người dùng mới

                        </div>


                        <div class="insight-value">
                            +18%
                        </div>


                        <div class="insight-change">
                            Tăng trưởng ổn định
                        </div>


                    </div>



                    <div class="insight">


                        <div class="insight-label">

                            <span>●</span>

                            Tin đăng mới

                        </div>


                        <div class="insight-value">
                            +12%
                        </div>


                        <div class="insight-change">
                            Nguồn cung đang tăng
                        </div>


                    </div>



                    <div class="insight">


                        <div class="insight-label">

                            <span>●</span>

                            Lượt xem

                        </div>


                        <div class="insight-value">
                            +24%
                        </div>


                        <div class="insight-change">
                            Mức quan tâm cao
                        </div>


                    </div>


                </div>


            </section>



            {{-- =================================================
                 BOTTOM
            ================================================== --}}

            <div class="bottom-grid">


                {{-- PHÂN BỐ --}}

                <section class="dashboard-card">


                    <div class="dashboard-card-header">

                        <div class="dashboard-card-title">

                            <h3>
                                Phân bố tin đăng
                            </h3>

                        </div>

                    </div>


                    <div class="trend-box">


                        <div class="trend-row">

                            <div class="trend-info">

                                <span>
                                    TP. Thủ Đức
                                </span>

                                <strong>
                                    25%
                                </strong>

                            </div>


                            <div class="trend-line">

                                <div class="trend-fill"
                                     style="width:25%">
                                </div>

                            </div>

                        </div>



                        <div class="trend-row">

                            <div class="trend-info">

                                <span>
                                    Bình Thạnh
                                </span>

                                <strong>
                                    20%
                                </strong>

                            </div>


                            <div class="trend-line">

                                <div class="trend-fill"
                                     style="width:20%">
                                </div>

                            </div>

                        </div>



                        <div class="trend-row">

                            <div class="trend-info">

                                <span>
                                    Quận 7
                                </span>

                                <strong>
                                    15%
                                </strong>

                            </div>


                            <div class="trend-line">

                                <div class="trend-fill"
                                     style="width:15%">
                                </div>

                            </div>

                        </div>



                        <div class="trend-row">

                            <div class="trend-info">

                                <span>
                                    Tân Bình
                                </span>

                                <strong>
                                    13%
                                </strong>

                            </div>


                            <div class="trend-line">

                                <div class="trend-fill"
                                     style="width:13%">
                                </div>

                            </div>

                        </div>


                    </div>


                </section>



                {{-- ACTIVITY --}}

                <section class="dashboard-card">


                    <div class="dashboard-card-header">


                        <div class="dashboard-card-title">

                            <h3>
                                Hoạt động mới
                            </h3>

                            <span>
                                Trên nền tảng
                            </span>

                        </div>


                    </div>



                    <div class="activity-list">


                        <div class="activity">


                            <div class="activity-icon">

                                <svg width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>

                                    <circle cx="12"
                                            cy="7"
                                            r="4"/>

                                </svg>

                            </div>


                            <div class="activity-text">

                                <strong>
                                    Người dùng mới tham gia
                                </strong>

                                <span>
                                    Thêm 18 tài khoản trong hôm nay
                                </span>

                            </div>


                            <span class="activity-time">
                                10 phút
                            </span>


                        </div>



                        <div class="activity">


                            <div class="activity-icon">

                                <svg width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M3 10l9-7 9 7"/>

                                    <path d="M5 9v11h14V9"/>

                                </svg>

                            </div>


                            <div class="activity-text">

                                <strong>
                                    Nguồn tin đăng tăng
                                </strong>

                                <span>
                                    32 tin mới được thêm vào hệ thống
                                </span>

                            </div>


                            <span class="activity-time">
                                25 phút
                            </span>


                        </div>



                        <div class="activity">


                            <div class="activity-icon">

                                <svg width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6-10-6-10-6z"/>

                                    <circle cx="12"
                                            cy="12"
                                            r="3"/>

                                </svg>

                            </div>


                            <div class="activity-text">

                                <strong>
                                    Lượt xem tăng
                                </strong>

                                <span>
                                    Nhu cầu tìm kiếm phòng đang tăng
                                </span>

                            </div>


                            <span class="activity-time">
                                1 giờ
                            </span>


                        </div>



                        <div class="activity">


                            <div class="activity-icon">

                                <svg width="17"
                                     height="17"
                                     viewBox="0 0 24 24"
                                     fill="none"
                                     stroke="currentColor"
                                     stroke-width="2">

                                    <path d="M21 15a4 4 0 0 1-4 4H8l-5 3V7a4 4 0 0 1 4-4h10a4 4 0 0 1 4 4z"/>

                                </svg>

                            </div>


                            <div class="activity-text">

                                <strong>
                                    Tương tác người dùng
                                </strong>

                                <span>
                                    186 lượt tương tác trong hôm nay
                                </span>

                            </div>


                            <span class="activity-time">
                                2 giờ
                            </span>


                        </div>


                    </div>


                </section>


            </div>


        </div>

    </main>


    {{-- BOOTSTRAP JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>