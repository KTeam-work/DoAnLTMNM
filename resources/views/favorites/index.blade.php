```blade
<!DOCTYPE html>
<html lang="vi">
<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Trọ Ơi | Phòng yêu thích</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- CSS chính -->
    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}"
    >

</head>

<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-expand-lg app-navbar">

    <div class="container-fluid">

        <!-- LOGO -->
        <a
            class="logo"
            href="{{ route('tenant') }}"
        >
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
        <div
            class="collapse navbar-collapse"
            id="mainMenu"
        >

            <ul class="navbar-nav mx-auto align-items-lg-center">

                <!-- TRANG CHỦ -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ route('tenant') }}"
                    >
                        Trang chủ
                    </a>

                </li>


                <!-- TÌM PHÒNG -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ route('rooms.index') }}"
                    >
                        Tìm phòng
                    </a>

                </li>


                <!-- YÊU THÍCH -->
                <li class="nav-item">

                    <a
                        class="app-nav-link active"
                        href="{{ route('favorites.index') }}"
                    >
                        Yêu thích
                    </a>

                </li>


                <!-- LỊCH XEM PHÒNG -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ route('appointments.index') }}"
                    >
                        Lịch xem phòng
                    </a>

                </li>


                <!-- HỢP ĐỒNG -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Hợp đồng
                    </a>

                </li>


                <!-- HÓA ĐƠN -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Hóa đơn
                    </a>

                </li>

            </ul>


            <!-- RIGHT USER -->
            <div class="navbar-actions">

                <button
                    class="notif-btn"
                    type="button"
                    title="Thông báo"
                >
                    🔔
                    <span class="notif-dot"></span>
                </button>


                <a
                    href="#"
                    class="user-chip"
                >

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
     MAIN
========================================================= -->

<div class="page-wrap">


    <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

    <div class="page-header">

        <div>

            <h1 class="page-title">
                Phòng yêu thích
            </h1>

            <div class="page-desc">
                Lưu lại những phòng bạn quan tâm để xem lại và so sánh sau.
            </div>

        </div>


        <a
            href="{{ route('rooms.index') }}"
            class="btn-brand text-decoration-none"
        >
            🔎 Tìm thêm phòng
        </a>

    </div>



    <!-- =====================================================
         TOP SUMMARY
    ====================================================== -->

    <div class="row g-3 mb-4">


        <!-- SỐ PHÒNG -->
        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon red">
                        ♥
                    </div>

                    <span class="stat-trend">
                        Đã lưu
                    </span>

                </div>


                <div class="stat-value">
                    6
                </div>


                <div class="stat-label">
                    Phòng yêu thích
                </div>

            </div>

        </div>


        <!-- KHU VỰC -->
        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon green">
                        📍
                    </div>

                    <span class="stat-trend">
                        Quan tâm
                    </span>

                </div>


                <div class="stat-value">
                    4
                </div>


                <div class="stat-label">
                    Khu vực đang quan tâm
                </div>

            </div>

        </div>


        <!-- GIÁ -->
        <div class="col-md-4">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        💰
                    </div>

                    <span class="stat-trend">
                        Tham khảo
                    </span>

                </div>


                <div class="stat-value">
                    2,8–6,8tr
                </div>


                <div class="stat-label">
                    Khoảng giá phòng đã lưu
                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         FILTER / SORT
    ====================================================== -->

    <div class="panel mb-4">

        <div
            class="d-flex justify-content-between align-items-center flex-wrap gap-3"
        >

            <div>

                <h2 class="panel-title">
                    Danh sách đã lưu
                </h2>

                <div class="page-desc">
                    Các phòng bạn đã đánh dấu yêu thích.
                </div>

            </div>


            <div class="d-flex gap-2 flex-wrap">

                <button
                    type="button"
                    class="btn-outline-brand"
                >
                    Tất cả
                </button>


                <button
                    type="button"
                    class="btn-outline-brand"
                >
                    Giá thấp
                </button>


                <button
                    type="button"
                    class="btn-outline-brand"
                >
                    Mới lưu
                </button>


                <select
                    class="form-select"
                    style="
                        width:auto;
                        min-width:175px;
                        border-radius:11px;
                        border-color:var(--border);
                        font-size:12px;
                        font-weight:600;
                    "
                >

                    <option selected>
                        Sắp xếp: Mới nhất
                    </option>

                    <option>
                        Giá thấp → cao
                    </option>

                    <option>
                        Giá cao → thấp
                    </option>

                    <option>
                        Diện tích lớn → nhỏ
                    </option>

                </select>

            </div>

        </div>

    </div>



    <!-- =====================================================
         FAVORITE ROOM LIST
         
         Dữ liệu mẫu
    ====================================================== -->

    <div class="row g-4">


        <!-- =================================================
             ROOM 1
        ================================================== -->

        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    class="fav-btn"
                    type="button"
                    title="Bỏ yêu thích"
                >
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
                            ✓ Đã xác thực
                        </span>

                    </div>


                    <div class="room-title">
                        Phòng trọ máy lạnh gần Q.7
                    </div>


                    <div class="room-address">
                        Đường Nguyễn Thị Thập,
                        Quận 7, TP.HCM
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


                        <a
                            href="{{ route('rooms.show', 1) }}"
                            class="detail-btn text-decoration-none"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             ROOM 2
        ================================================== -->

        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    class="fav-btn"
                    type="button"
                    title="Bỏ yêu thích"
                >
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
                            ✓ Đã xác thực
                        </span>

                    </div>


                    <div class="room-title">
                        Căn hộ mini đầy đủ nội thất
                    </div>


                    <div class="room-address">
                        Phường An Phú,
                        TP. Thủ Đức, TP.HCM
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


                        <a
                            href="{{ route('rooms.show', 2) }}"
                            class="detail-btn text-decoration-none"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             ROOM 3
        ================================================== -->

        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    class="fav-btn"
                    type="button"
                    title="Bỏ yêu thích"
                >
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
                            ✓ Đã xác thực
                        </span>

                    </div>


                    <div class="room-title">
                        Phòng cửa sổ lớn, giờ giấc tự do
                    </div>


                    <div class="room-address">
                        Phường Tân Thành,
                        Tân Phú, TP.HCM
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


                        <a
                            href="{{ route('rooms.show', 3) }}"
                            class="detail-btn text-decoration-none"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             ROOM 4
        ================================================== -->

        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    class="fav-btn"
                    type="button"
                    title="Bỏ yêu thích"
                >
                    ♥
                </button>


                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng có gác rộng"
                >


                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room">
                            ✓ Đã xác thực
                        </span>

                    </div>


                    <div class="room-title">
                        Phòng có gác rộng, thoáng mát
                    </div>


                    <div class="room-address">
                        Đường Lê Văn Việt,
                        TP. Thủ Đức, TP.HCM
                    </div>


                    <div class="room-stats">

                        <span>
                            📐 28 m²
                        </span>

                        <span>
                            🛏️ Có gác
                        </span>

                        <span>
                            🚗 Bãi xe
                        </span>

                    </div>


                    <div class="room-bottom">

                        <div class="room-price">
                            3,5 triệu
                            <small>/ tháng</small>
                        </div>


                        <a
                            href="{{ route('rooms.show', 4) }}"
                            class="detail-btn text-decoration-none"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             ROOM 5
        ================================================== -->

        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    class="fav-btn"
                    type="button"
                    title="Bỏ yêu thích"
                >
                    ♥
                </button>


                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1000&q=80"
                    alt="Phòng ban công riêng"
                >


                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room hot">
                            🔥 HOT
                        </span>

                    </div>


                    <div class="room-title">
                        Phòng ban công riêng,
                        nhiều ánh sáng
                    </div>


                    <div class="room-address">
                        Nguyễn Gia Trí,
                        Bình Thạnh, TP.HCM
                    </div>


                    <div class="room-stats">

                        <span>
                            📐 30 m²
                        </span>

                        <span>
                            🌤️ Ban công
                        </span>

                        <span>
                            ❄️ Máy lạnh
                        </span>

                    </div>


                    <div class="room-bottom">

                        <div class="room-price">
                            4,2 triệu
                            <small>/ tháng</small>
                        </div>


                        <a
                            href="{{ route('rooms.show', 5) }}"
                            class="detail-btn text-decoration-none"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>



        <!-- =================================================
             ROOM 6
        ================================================== -->

        <div class="col-md-6 col-lg-4">

            <div class="room-card">

                <button
                    class="fav-btn"
                    type="button"
                    title="Bỏ yêu thích"
                >
                    ♥
                </button>


                <img
                    class="room-img"
                    src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80"
                    alt="Căn hộ nhỏ đầy đủ tiện nghi"
                >


                <div class="room-body">

                    <div class="room-badges">

                        <span class="badge-room">
                            ✓ Đã xác thực
                        </span>

                    </div>


                    <div class="room-title">
                        Căn hộ nhỏ đầy đủ tiện nghi
                    </div>


                    <div class="room-address">
                        Phường 4,
                        Quận 3, TP.HCM
                    </div>


                    <div class="room-stats">

                        <span>
                            📐 32 m²
                        </span>

                        <span>
                            🛋️ Nội thất
                        </span>

                        <span>
                            🧺 Máy giặt
                        </span>

                    </div>


                    <div class="room-bottom">

                        <div class="room-price">
                            5,5 triệu
                            <small>/ tháng</small>
                        </div>


                        <a
                            href="{{ route('rooms.show', 6) }}"
                            class="detail-btn text-decoration-none"
                        >
                            Xem phòng
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>



    <!-- =====================================================
         BOTTOM INFO
    ====================================================== -->

    <div class="panel mt-4">

        <div class="d-flex align-items-center gap-3">

            <div class="stat-icon red">
                ♥
            </div>

            <div>

                <div class="cell-title">
                    Lưu phòng để xem lại bất cứ lúc nào
                </div>

                <div class="cell-sub">
                    Khi tìm thấy phòng phù hợp, hãy bấm biểu tượng
                    ♥ trên phòng để thêm vào danh sách yêu thích.
                </div>

            </div>

        </div>

    </div>

</div>


<!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>

</body>
</html>
```
