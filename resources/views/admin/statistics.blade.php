<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Thống kê - Trọ Ơi</title>


    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}"
    >


    <script
        src="https://cdn.jsdelivr.net/npm/chart.js"
    ></script>


    <style>

        body {

            background: #f8f5eb;

            font-family:
                'Be Vietnam Pro',
                sans-serif;

        }


        .stats-page {

            padding-bottom: 50px;

        }


        /* =====================================================
           HEADER
        ===================================================== */

        .statistics-header {

            display: flex;

            align-items: flex-end;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 22px;

        }


        .statistics-filter {

            display: flex;

            align-items: flex-end;

            gap: 10px;

            background: #ffffff;

            border: 1px solid #e6dcc2;

            border-radius: 12px;

            padding: 10px;

        }


        .date-group {

            display: flex;

            flex-direction: column;

            gap: 4px;

        }


        .date-label {

            font-size: 10px;

            font-weight: 600;

            color: #938a7c;

        }


        .date-input {

            height: 34px;

            border: 1px solid #e6dcc2;

            border-radius: 7px;

            padding: 0 9px;

            font-size: 11px;

            color: #17463e;

            background: white;

            outline: none;

        }


        .date-input:focus {

            border-color: #20584f;

        }


        .filter-button {

            height: 34px;

            border: none;

            border-radius: 7px;

            padding: 0 13px;

            background: #20584f;

            color: white;

            font-size: 11px;

            font-weight: 600;

            cursor: pointer;

        }


        .filter-button:hover {

            background: #17463e;

        }


        /* =====================================================
           SUMMARY
        ===================================================== */

        .summary-grid {

            display: grid;

            grid-template-columns:
                repeat(4, 1fr);

            gap: 16px;

            margin-bottom: 16px;

        }


        .summary-card {

            background: #ffffff;

            border: 1px solid #e6dcc2;

            border-radius: 15px;

            padding: 18px 20px;

            min-height: 125px;

            position: relative;

            overflow: hidden;

        }


        .summary-card::after {

            content: "";

            position: absolute;

            right: -25px;

            bottom: -35px;

            width: 100px;

            height: 100px;

            border-radius: 50%;

            background:
                rgba(32, 88, 79, 0.05);

        }


        .summary-top {

            display: flex;

            justify-content: space-between;

            align-items: center;

        }


        .summary-label {

            font-size: 13px;

            color: #8b8172;

            font-weight: 500;

        }


        .summary-icon {

            width: 38px;

            height: 38px;

            border-radius: 10px;

            display: flex;

            align-items: center;

            justify-content: center;

            background: #edf4f1;

            color: #20584f;

            font-size: 18px;

        }


        .summary-value {

            font-size: 28px;

            font-weight: 800;

            color: #17463e;

            margin-top: 10px;

        }


        .summary-change {

            font-size: 11px;

            margin-top: 3px;

        }


        .increase {

            color: #20584f;

        }


        /* =====================================================
           ANALYTICS CARD
        ===================================================== */

        .analytics-card {

            background: #ffffff;

            border: 1px solid #e6dcc2;

            border-radius: 15px;

            padding: 20px;

        }


        .analytics-header {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 15px;

        }


        .analytics-title {

            font-size: 16px;

            font-weight: 700;

            color: #17463e;

            margin-bottom: 4px;

        }


        .analytics-desc {

            font-size: 12px;

            color: #938a7c;

        }


        /* =====================================================
           2 BIỂU ĐỒ TRÒN - CÙNG HÀNG
        ===================================================== */

        .top-chart-grid {

            display: grid;

            grid-template-columns: 1fr 1fr;

            gap: 16px;

            margin-bottom: 16px;

        }


        .circle-chart-area {

            height: 230px;

            display: flex;

            align-items: center;

            justify-content: center;

            position: relative;

        }


        /*
            QUAN TRỌNG:

            Không để Chart.js tự co canvas
            theo chiều rộng card.

            Ép canvas thành hình vuông.
        */

        .circle-chart-area canvas {

            width: 210px !important;

            height: 210px !important;

            max-width: 210px !important;

            max-height: 210px !important;

        }


        .circle-center {

            position: absolute;

            left: 50%;

            top: 50%;

            transform:
                translate(-50%, -50%);

            text-align: center;

            pointer-events: none;

        }


        .circle-center-value {

            display: block;

            color: #17463e;

            font-size: 28px;

            font-weight: 800;

            line-height: 1.1;

        }


        .circle-center-label {

            display: block;

            margin-top: 4px;

            color: #938a7c;

            font-size: 10px;

        }


        /* =====================================================
           LEGEND
        ===================================================== */

        .custom-legend {

            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 8px;

            margin-top: 3px;

        }


        .legend-box {

            background: #f8f5eb;

            border-radius: 9px;

            padding: 9px 5px;

            text-align: center;

        }


        .legend-dot {

            display: inline-block;

            width: 7px;

            height: 7px;

            border-radius: 50%;

            margin-right: 3px;

        }


        .legend-name {

            font-size: 10px;

            color: #71695d;

        }


        .legend-value {

            display: block;

            margin-top: 3px;

            font-size: 12px;

            font-weight: 700;

            color: #17463e;

        }


        .green-dot {

            background: #20584f;

        }


        .yellow-dot {

            background: #f5c84b;

        }


        .red-dot {

            background: #9a3324;

        }


        .light-green-dot {

            background: #8ab7ad;

        }


        /* =====================================================
           ACTIVITY
        ===================================================== */

        .activity-card {

            margin-bottom: 16px;

        }


        .activity-period {

            display: inline-flex;

            align-items: center;

            padding: 7px 10px;

            background: #edf4f1;

            color: #20584f;

            border-radius: 8px;

            font-size: 10px;

            font-weight: 600;

            white-space: nowrap;

        }


        .activity-chart {

            height: 330px;

            position: relative;

        }


        /* =====================================================
           BOTTOM
        ===================================================== */

        .bottom-grid {

            display: grid;

            grid-template-columns:
                1fr 1fr 1fr;

            gap: 16px;

        }


        .small-chart {

            height: 230px;

            position: relative;

            margin-top: 8px;

        }


        /* =====================================================
           RANKING
        ===================================================== */

        .ranking-list {

            margin-top: 12px;

        }


        .ranking-item {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 10px 0;

            border-bottom:
                1px solid #f0eadc;

        }


        .ranking-item:last-child {

            border-bottom: none;

        }


        .ranking-number {

            width: 27px;

            height: 27px;

            border-radius: 8px;

            background: #edf4f1;

            color: #20584f;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 12px;

            font-weight: 700;

            flex-shrink: 0;

        }


        .ranking-info {

            flex: 1;

        }


        .ranking-name {

            font-size: 13px;

            font-weight: 600;

            color: #17463e;

        }


        .ranking-sub {

            font-size: 10px;

            color: #938a7c;

            margin-top: 2px;

        }


        .ranking-value {

            font-size: 13px;

            font-weight: 700;

            color: #20584f;

        }


        /* =====================================================
           APPROVAL
        ===================================================== */

        .progress-item {

            margin-top: 18px;

        }


        .progress-info {

            display: flex;

            justify-content: space-between;

            margin-bottom: 7px;

        }


        .progress-name {

            font-size: 12px;

            color: #5f594f;

        }


        .progress-number {

            font-size: 12px;

            font-weight: 700;

            color: #17463e;

        }


        .custom-progress {

            height: 7px;

            background: #eee9dc;

            border-radius: 20px;

            overflow: hidden;

        }


        .custom-progress-bar {

            height: 100%;

            background: #20584f;

            border-radius: 20px;

        }


        .custom-progress-bar.yellow {

            background: #f5c84b;

        }


        .custom-progress-bar.red {

            background: #9a3324;

        }


        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 1050px) {

            .summary-grid {

                grid-template-columns:
                    repeat(2, 1fr);

            }


            .bottom-grid {

                grid-template-columns:
                    1fr 1fr;

            }

        }


        @media (max-width: 800px) {

            .statistics-header {

                flex-direction: column;

                align-items: stretch;

            }


            .statistics-filter {

                width: 100%;

            }


            .top-chart-grid {

                grid-template-columns: 1fr;

            }

        }


        @media (max-width: 600px) {

            .summary-grid {

                grid-template-columns: 1fr;

            }


            .bottom-grid {

                grid-template-columns: 1fr;

            }


            .statistics-filter {

                flex-wrap: wrap;

            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="app-navbar">

    <div class="container-fluid h-100">

        <div
            class="d-flex align-items-center justify-content-between h-100"
        >


            <a
                href="{{ url('/admin/dashboard') }}"
                class="logo text-decoration-none"
            >

        Trọ <span>Ơi</span>

                <small>
                    ADMIN
                </small>

            </a>


            <div
                class="d-flex align-items-center gap-1"
            >

                <a
                    href="{{ url('/admin/dashboard') }}"
                    class="app-nav-link text-decoration-none"
                >
                    Dashboard
                </a>


                <a
                    href="{{ url('/admin/users') }}"
                    class="app-nav-link text-decoration-none"
                >
                    Tài khoản
                </a>


                <a
                    href="{{ url('/admin/rental-posts') }}"
                    class="app-nav-link text-decoration-none"
                >
                    Kiểm duyệt tin
                </a>


                <a
                    href="{{ url('/admin/statistics') }}"
                    class="app-nav-link active text-decoration-none"
                >
                    Thống kê
                </a>

            </div>


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


<!-- =====================================================
     PAGE
===================================================== -->

<div class="page-wrap stats-page">


    <!-- HEADER -->

    <div class="statistics-header">


        <div>

            <h1 class="page-title">
                Tổng quan thống kê
            </h1>


            <div class="page-desc">

                Theo dõi tình hình hoạt động
                của hệ thống Trọ Ơi

            </div>

        </div>


        <!-- DATE FILTER -->

        <div class="statistics-filter">


            <div class="date-group">

                <label
                    for="fromDate"
                    class="date-label"
                >
                    Từ ngày
                </label>

                <input
                    type="date"
                    id="fromDate"
                    class="date-input"
                    value="2026-09-01"
                >

            </div>


            <div class="date-group">

                <label
                    for="toDate"
                    class="date-label"
                >
                    Đến ngày
                </label>

                <input
                    type="date"
                    id="toDate"
                    class="date-input"
                    value="2026-09-07"
                >

            </div>


            <button
                type="button"
                class="filter-button"
                onclick="updateActivityChart()"
            >
                Xem thống kê
            </button>

        </div>

    </div>


    <!-- =================================================
         SUMMARY
    ================================================== -->

    <div class="summary-grid">


        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-label">
                    Tổng tài khoản
                </div>

                <div class="summary-icon">
                    👥
                </div>

            </div>


            <div class="summary-value">
                1,248
            </div>


            <div class="summary-change increase">
                ↑ 12.5% so với tháng trước
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-label">
                    Tổng số phòng
                </div>

                <div class="summary-icon">
                    🏠
                </div>

            </div>


            <div class="summary-value">
                356
            </div>


            <div class="summary-change increase">
                ↑ 8.2% so với tháng trước
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-label">
                    Tin đăng
                </div>

                <div class="summary-icon">
                    📋
                </div>

            </div>


            <div class="summary-value">
                128
            </div>


            <div class="summary-change increase">
                ↑ 15.8% so với tháng trước
            </div>

        </div>


        <div class="summary-card">

            <div class="summary-top">

                <div class="summary-label">
                    Hợp đồng
                </div>

                <div class="summary-icon">
                    📝
                </div>

            </div>


            <div class="summary-value">
                64
            </div>


            <div class="summary-change increase">
                ↑ 6.4% so với tháng trước
            </div>

        </div>

    </div>


    <!-- =================================================
         2 BIỂU ĐỒ TRÒN
    ================================================== -->

    <div class="top-chart-grid">


        <!-- LẤP ĐẦY PHÒNG -->

        <div class="analytics-card">


            <div class="analytics-header">

                <div>

                    <div class="analytics-title">
                        Tỷ lệ lấp đầy phòng
                    </div>

                    <div class="analytics-desc">
                        Tình trạng sử dụng phòng hiện tại
                    </div>

                </div>

            </div>


            <div class="circle-chart-area">

                <canvas
                    id="occupancyChart"
                ></canvas>


                <div class="circle-center">

                    <span class="circle-center-value">
                        78%
                    </span>

                    <span class="circle-center-label">
                        Đang thuê
                    </span>

                </div>

            </div>


            <div class="custom-legend">


                <div class="legend-box">

                    <span
                        class="legend-dot green-dot"
                    ></span>

                    <span class="legend-name">
                        Đang thuê
                    </span>

                    <span class="legend-value">
                        278
                    </span>

                </div>


                <div class="legend-box">

                    <span
                        class="legend-dot yellow-dot"
                    ></span>

                    <span class="legend-name">
                        Còn trống
                    </span>

                    <span class="legend-value">
                        61
                    </span>

                </div>


                <div class="legend-box">

                    <span
                        class="legend-dot red-dot"
                    ></span>

                    <span class="legend-name">
                        Bảo trì
                    </span>

                    <span class="legend-value">
                        17
                    </span>

                </div>

            </div>

        </div>


        <!-- CƠ CẤU NGƯỜI DÙNG -->

        <div class="analytics-card">


            <div class="analytics-header">

                <div>

                    <div class="analytics-title">
                        Cơ cấu người dùng
                    </div>

                    <div class="analytics-desc">
                        Phân bố tài khoản theo vai trò
                    </div>

                </div>

            </div>


            <div class="circle-chart-area">

                <canvas
                    id="userChart"
                ></canvas>


                <div class="circle-center">

                    <span
                        class="circle-center-value"
                        style="font-size:23px;"
                    >
                        1,248
                    </span>

                    <span class="circle-center-label">
                        Tài khoản
                    </span>

                </div>

            </div>


            <div class="custom-legend">


                <div class="legend-box">

                    <span
                        class="legend-dot green-dot"
                    ></span>

                    <span class="legend-name">
                        Admin
                    </span>

                    <span class="legend-value">
                        8
                    </span>

                </div>


                <div class="legend-box">

                    <span
                        class="legend-dot yellow-dot"
                    ></span>

                    <span class="legend-name">
                        Chủ trọ
                    </span>

                    <span class="legend-value">
                        412
                    </span>

                </div>


                <div class="legend-box">

                    <span
                        class="legend-dot light-green-dot"
                    ></span>

                    <span class="legend-name">
                        Người thuê
                    </span>

                    <span class="legend-value">
                        828
                    </span>

                </div>

            </div>

        </div>

    </div>


    <!-- =================================================
         NHỊP ĐỘ HOẠT ĐỘNG
    ================================================== -->

    <div class="analytics-card activity-card">


        <div class="analytics-header">


            <div>

                <div class="analytics-title">
                    Nhịp độ hoạt động
                </div>


                <div class="analytics-desc">
                    Tin đăng và hợp đồng theo từng ngày
                </div>

            </div>


            <span
                class="activity-period"
                id="activityPeriod"
            >
                01/09/2026 – 07/09/2026
            </span>

        </div>


        <div class="activity-chart">

            <canvas
                id="activityChart"
            ></canvas>

        </div>

    </div>


    <!-- =================================================
         BOTTOM
    ================================================== -->

    <div class="bottom-grid">


        <!-- TÌNH TRẠNG PHÒNG -->

        <div class="analytics-card">

            <div class="analytics-title">
                Tình trạng phòng
            </div>

            <div class="analytics-desc">
                Phân loại trạng thái phòng
            </div>


            <div class="small-chart">

                <canvas
                    id="roomChart"
                ></canvas>

            </div>

        </div>


        <!-- KHU VỰC -->

        <div class="analytics-card">

            <div class="analytics-title">
                Khu vực nổi bật
            </div>

            <div class="analytics-desc">
                Khu vực có nhiều phòng nhất
            </div>


            <div class="ranking-list">


                <div class="ranking-item">

                    <div class="ranking-number">
                        1
                    </div>

                    <div class="ranking-info">

                        <div class="ranking-name">
                            Thủ Đức
                        </div>

                        <div class="ranking-sub">
                            113 phòng
                        </div>

                    </div>

                    <div class="ranking-value">
                        31.7%
                    </div>

                </div>


                <div class="ranking-item">

                    <div class="ranking-number">
                        2
                    </div>

                    <div class="ranking-info">

                        <div class="ranking-name">
                            Bình Thạnh
                        </div>

                        <div class="ranking-sub">
                            85 phòng
                        </div>

                    </div>

                    <div class="ranking-value">
                        23.9%
                    </div>

                </div>


                <div class="ranking-item">

                    <div class="ranking-number">
                        3
                    </div>

                    <div class="ranking-info">

                        <div class="ranking-name">
                            Quận 7
                        </div>

                        <div class="ranking-sub">
                            71 phòng
                        </div>

                    </div>

                    <div class="ranking-value">
                        19.9%
                    </div>

                </div>


                <div class="ranking-item">

                    <div class="ranking-number">
                        4
                    </div>

                    <div class="ranking-info">

                        <div class="ranking-name">
                            Gò Vấp
                        </div>

                        <div class="ranking-sub">
                            52 phòng
                        </div>

                    </div>

                    <div class="ranking-value">
                        14.6%
                    </div>

                </div>


                <div class="ranking-item">

                    <div class="ranking-number">
                        5
                    </div>

                    <div class="ranking-info">

                        <div class="ranking-name">
                            Tân Bình
                        </div>

                        <div class="ranking-sub">
                            35 phòng
                        </div>

                    </div>

                    <div class="ranking-value">
                        9.9%
                    </div>

                </div>

            </div>

        </div>


        <!-- KIỂM DUYỆT -->

        <div class="analytics-card">

            <div class="analytics-title">
                Kiểm duyệt tin đăng
            </div>

            <div class="analytics-desc">
                Tỷ lệ xử lý tin đăng
            </div>


            <div class="progress-item">

                <div class="progress-info">

                    <span class="progress-name">
                        Đã duyệt
                    </span>

                    <span class="progress-number">
                        76%
                    </span>

                </div>


                <div class="custom-progress">

                    <div
                        class="custom-progress-bar"
                        style="width:76%"
                    ></div>

                </div>

            </div>


            <div class="progress-item">

                <div class="progress-info">

                    <span class="progress-name">
                        Chờ duyệt
                    </span>

                    <span class="progress-number">
                        14%
                    </span>

                </div>


                <div class="custom-progress">

                    <div
                        class="custom-progress-bar yellow"
                        style="width:14%"
                    ></div>

                </div>

            </div>


            <div class="progress-item">

                <div class="progress-info">

                    <span class="progress-name">
                        Từ chối
                    </span>

                    <span class="progress-number">
                        7%
                    </span>

                </div>


                <div class="custom-progress">

                    <div
                        class="custom-progress-bar red"
                        style="width:7%"
                    ></div>

                </div>

            </div>


            <div class="progress-item">

                <div class="progress-info">

                    <span class="progress-name">
                        Đã ẩn
                    </span>

                    <span class="progress-number">
                        3%
                    </span>

                </div>


                <div class="custom-progress">

                    <div
                        class="custom-progress-bar"
                        style="width:3%"
                    ></div>

                </div>

            </div>

        </div>

    </div>

</div>


<script>


/* =====================================================
   TỶ LỆ LẤP ĐẦY PHÒNG
===================================================== */

const occupancyCtx =
    document.getElementById(
        'occupancyChart'
    );


new Chart(

    occupancyCtx,

    {

        type: 'doughnut',


        data: {

            labels: [

                'Đang thuê',

                'Còn trống',

                'Bảo trì'

            ],


            datasets: [{

                data: [

                    278,

                    61,

                    17

                ],


                backgroundColor: [

                    '#20584f',

                    '#f5c84b',

                    '#9a3324'

                ],


                borderWidth: 0,

                hoverOffset: 5

            }]

        },


        options: {

            /*
                Tắt responsive của Chart.js
                để canvas giữ đúng 210 x 210.
            */

            responsive: false,

            cutout: '68%',


            plugins: {

                legend: {

                    display: false

                },


                tooltip: {

                    callbacks: {

                        label:
                            function(context) {

                                return (
                                    context.label +
                                    ': ' +
                                    context.raw +
                                    ' phòng'
                                );

                            }

                    }

                }

            }

        }

    }

);


/* =====================================================
   CƠ CẤU NGƯỜI DÙNG
===================================================== */

const userCtx =
    document.getElementById(
        'userChart'
    );


new Chart(

    userCtx,

    {

        type: 'doughnut',


        data: {

            labels: [

                'Admin',

                'Chủ trọ',

                'Người thuê'

            ],


            datasets: [{

                data: [

                    8,

                    412,

                    828

                ],


                backgroundColor: [

                    '#17463e',

                    '#f5c84b',

                    '#8ab7ad'

                ],


                borderWidth: 0,

                hoverOffset: 5

            }]

        },


        options: {

            responsive: false,

            cutout: '65%',


            plugins: {

                legend: {

                    display: false

                }

            }

        }

    }

);


/* =====================================================
   NHỊP ĐỘ HOẠT ĐỘNG
===================================================== */

const activityCtx =
    document.getElementById(
        'activityChart'
    );


let activityChart;


/* YYYY-MM-DD → DD/MM/YYYY */

function formatDate(date) {

    const day =
        String(
            date.getDate()
        ).padStart(2, '0');


    const month =
        String(
            date.getMonth() + 1
        ).padStart(2, '0');


    const year =
        date.getFullYear();


    return (
        day +
        '/' +
        month +
        '/' +
        year
    );

}


/* YYYY-MM-DD → DD/MM */

function formatShortDate(date) {

    const day =
        String(
            date.getDate()
        ).padStart(2, '0');


    const month =
        String(
            date.getMonth() + 1
        ).padStart(2, '0');


    return (
        day +
        '/' +
        month
    );

}


/* Tạo danh sách ngày */

function getDateList(start, end) {

    const dates = [];

    const current =
        new Date(start);


    while (
        current <= end
    ) {

        dates.push(
            new Date(current)
        );


        current.setDate(
            current.getDate() + 1
        );

    }


    return dates;

}


/* Dữ liệu demo */

function createDemoData(count, base) {

    const data = [];


    for (
        let i = 0;
        i < count;
        i++
    ) {

        const variation =
            ((i * 7) % 13) - 5;


        data.push(
            Math.max(
                0,
                base + variation
            )
        );

    }


    return data;

}


/* =====================================================
   UPDATE ACTIVITY
===================================================== */

function updateActivityChart() {


    const fromValue =
        document.getElementById(
            'fromDate'
        ).value;


    const toValue =
        document.getElementById(
            'toDate'
        ).value;


    if (
        !fromValue ||
        !toValue
    ) {

        return;

    }


    const start =
        new Date(
            fromValue + 'T00:00:00'
        );


    const end =
        new Date(
            toValue + 'T00:00:00'
        );


    if (
        start > end
    ) {

        alert(
            'Ngày bắt đầu không được lớn hơn ngày kết thúc.'
        );

        return;

    }


    const dates =
        getDateList(
            start,
            end
        );


    const labels =
        dates.map(
            function(date) {

                return formatShortDate(
                    date
                );

            }
        );


    const postData =
        createDemoData(
            dates.length,
            18
        );


    const contractData =
        createDemoData(
            dates.length,
            7
        );


    document.getElementById(
        'activityPeriod'
    ).textContent =

        formatDate(start) +
        ' – ' +
        formatDate(end);


    if (activityChart) {


        activityChart.data.labels =
            labels;


        activityChart.data.datasets[0]
            .data =
            postData;


        activityChart.data.datasets[1]
            .data =
            contractData;


        activityChart.update();


        return;

    }


    activityChart =
        new Chart(

            activityCtx,

            {

                type: 'line',


                data: {

                    labels: labels,


                    datasets: [

                        {

                            label:
                                'Tin đăng',


                            data:
                                postData,


                            borderColor:
                                '#20584f',


                            backgroundColor:
                                'rgba(32, 88, 79, 0.12)',


                            borderWidth: 2,


                            fill: true,


                            tension: 0.4,


                            pointRadius: 4,


                            pointHoverRadius: 6

                        },


                        {

                            label:
                                'Hợp đồng',


                            data:
                                contractData,


                            borderColor:
                                '#f5c84b',


                            backgroundColor:
                                'rgba(245, 200, 75, 0.10)',


                            borderWidth: 2,


                            fill: true,


                            tension: 0.4,


                            pointRadius: 4,


                            pointHoverRadius: 6

                        }

                    ]

                },


                options: {

                    responsive: true,

                    maintainAspectRatio: false,


                    interaction: {

                        mode: 'index',

                        intersect: false

                    },


                    plugins: {

                        legend: {

                            position:
                                'bottom',


                            labels: {

                                usePointStyle:
                                    true,

                                padding: 20

                            }

                        }

                    },


                    scales: {

                        x: {

                            grid: {

                                display: false

                            },


                            ticks: {

                                maxRotation: 0,

                                autoSkip: true,

                                maxTicksLimit: 15

                            }

                        },


                        y: {

                            beginAtZero: true,


                            ticks: {

                                precision: 0

                            },


                            grid: {

                                color:
                                    '#eee9dc'

                            }

                        }

                    }

                }

            }

        );

}


/* =====================================================
   TÌNH TRẠNG PHÒNG
===================================================== */

const roomCtx =
    document.getElementById(
        'roomChart'
    );


new Chart(

    roomCtx,

    {

        type: 'doughnut',


        data: {

            labels: [

                'Đang thuê',

                'Còn trống',

                'Bảo trì'

            ],


            datasets: [{

                data: [

                    278,

                    61,

                    17

                ],


                backgroundColor: [

                    '#20584f',

                    '#f5c84b',

                    '#9a3324'

                ],


                borderWidth: 0

            }]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '58%',


            plugins: {

                legend: {

                    position:
                        'bottom',


                    labels: {

                        usePointStyle:
                            true,


                        padding: 12,


                        font: {

                            size: 10

                        }

                    }

                }

            }

        }

    }

);


/* =====================================================
   LOAD
===================================================== */

updateActivityChart();


</script>


</body>

</html>