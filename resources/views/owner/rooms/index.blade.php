<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Quản lý phòng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f8f5eb;
            color: #213430;
            font-family: "Be Vietnam Pro", sans-serif;
        }

        

        .heading {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .eyebrow {
            color: #20584f;
            font-size: 13px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .08em;
            margin-bottom: 6px;
        }

        .heading h1 {
            margin: 0;
            font-size: 30px;
            font-weight: 800;
        }

        .heading p {
            margin: 8px 0 0;
            color: #78837e;
            font-size: 14px;
        }

        .btn-owner {
            border: none;
            background: #20584f;
            color: white;
            text-decoration: none;

            padding: 12px 18px;
            border-radius: 12px;

            font-size: 14px;
            font-weight: 700;

            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-owner:hover {
            background: #17463e;
            color: white;
        }

        /* =========================
           STATS
        ========================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat {
            background: white;
            border: 1px solid #e6dcc2;
            border-radius: 18px;
            padding: 20px;

            display: flex;
            align-items: center;
            gap: 15px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eaf3ef;
            font-size: 21px;
        }

        .stat-label {
            color: #78837e;
            font-size: 13px;
            margin-bottom: 3px;
        }

        .stat-number {
            font-size: 22px;
            font-weight: 800;
        }

        /* =========================
           ROOM CONTAINER
        ========================= */

        .room-container {
            background: #ffffff;
            border: 1px solid #e6dcc2;
            border-radius: 22px;
            padding: 22px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 22px;
        }

        .search-box {
            flex: 1;
            position: relative;
        }

        .search-box span {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #78837e;
        }

        .search-box input,
        .filter-select {
            width: 100%;
            height: 44px;

            border: 1px solid #e6dcc2;
            border-radius: 11px;
            outline: none;

            background: #fff;
            color: #213430;

            padding: 0 14px;
            font-family: inherit;
            font-size: 13px;
        }

        .search-box input {
            padding-left: 42px;
        }

        .search-box input:focus,
        .filter-select:focus,
        .edit-input:focus,
        .edit-select:focus {
            border-color: #20584f;
            box-shadow: 0 0 0 3px rgba(32, 88, 79, .08);
        }

        .filter-select {
            width: 190px;
        }

        /* =========================
           ROOM GRID
        ========================= */

        .room-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .room-card {
            border: 1px solid #e6dcc2;
            border-radius: 18px;
            overflow: hidden;
            background: #fff;
            transition: .2s;
        }

        .room-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(33, 52, 48, .08);
        }

        .room-img {
            height: 190px;
            position: relative;
            overflow: hidden;
        }

        .room-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-status {
            position: absolute;
            top: 13px;
            right: 13px;

            padding: 7px 10px;
            border-radius: 999px;

            font-size: 11px;
            font-weight: 800;
            background: white;
        }

        .room-status.available {
            color: #20584f;
            background: #eaf3ef;
        }

        .room-status.rented {
            color: #8b6810;
            background: #fff7d7;
        }

        .room-status.maintenance {
            color: #a33f35;
            background: #fae8e5;
        }

        .room-code {
            position: absolute;
            left: 13px;
            top: 13px;

            background: rgba(255,255,255,.94);
            color: #20584f;

            border-radius: 8px;
            padding: 6px 9px;

            font-size: 11px;
            font-weight: 800;
        }

        .room-body {
            padding: 18px;
        }

        .room-body h3 {
            margin: 0 0 5px;
            font-size: 17px;
            font-weight: 800;
        }

        .room-property {
            color: #78837e;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .room-price {
            color: #20584f;
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .room-info {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-bottom: 13px;
        }

        .room-info span {
            background: #f8f5eb;
            border-radius: 8px;
            padding: 7px 9px;

            color: #53635e;
            font-size: 11px;
            font-weight: 600;
        }

        .amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-height: 28px;
        }

        .amenity {
            padding: 5px 8px;
            border-radius: 7px;
            background: #eaf3ef;
            color: #20584f;
            font-size: 10px;
            font-weight: 700;
        }

        .room-actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 9px;
            margin-top: 17px;
        }

        .room-actions button {
            height: 40px;
            border-radius: 10px;
            font-family: inherit;
            font-weight: 700;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-edit {
            border: 1px solid #20584f;
            background: #fff;
            color: #20584f;
        }

        .btn-edit:hover {
            background: #eaf3ef;
        }

        .btn-more {
            border: none;
            background: #20584f;
            color: white;
        }

        .btn-more:hover {
            background: #17463e;
        }

        /* =========================
           MODAL CHUNG
        ========================= */

        .custom-modal {
            display: none;

            position: fixed;
            inset: 0;

            z-index: 2000;

            background: rgba(23, 70, 62, .45);

            padding: 20px;

            overflow-y: auto;

            align-items: center;
            justify-content: center;
        }

        .custom-modal.show {
            display: flex;
        }

        .modal-box {
            width: min(720px, 100%);
            max-height: 92vh;

            overflow-y: auto;

            background: #fff;
            border-radius: 24px;
            border: 1px solid #e6dcc2;

            box-shadow: 0 25px 70px rgba(33, 52, 48, .20);
        }

        .modal-header {
            padding: 22px 24px 17px;

            display: flex;
            align-items: flex-start;
            justify-content: space-between;

            border-bottom: 1px solid #eee7d8;
        }

        .modal-title-area {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .modal-title-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #eaf3ef;
            color: #20584f;
            font-size: 20px;
        }

        .modal-title {
            margin: 0;
            color: #213430;
            font-size: 19px;
            font-weight: 800;
        }

        .modal-subtitle {
            margin: 4px 0 0;
            color: #78837e;
            font-size: 12px;
        }

        .modal-close {
            border: none;
            background: transparent;
            color: #78837e;

            width: 34px;
            height: 34px;
            border-radius: 9px;

            font-size: 22px;
            cursor: pointer;
        }

        .modal-close:hover {
            background: #f8f5eb;
            color: #213430;
        }

        .modal-body {
            padding: 24px;
        }

        .modal-footer {
            padding: 17px 24px;

            display: flex;
            justify-content: flex-end;
            gap: 10px;

            border-top: 1px solid #eee7d8;
        }

        /* =========================
           EDIT MODAL
        ========================= */

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 17px;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 7px;

            color: #53635e;
            font-size: 12px;
            font-weight: 700;
        }

        .edit-input,
        .edit-select {
            width: 100%;
            height: 43px;

            border: 1px solid #e6dcc2;
            border-radius: 10px;

            outline: none;
            padding: 0 12px;

            font-family: inherit;
            color: #213430;
            background: white;
        }

        textarea.edit-input {
            height: 90px;
            padding: 11px 12px;
            resize: vertical;
        }

        .amenity-checks {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 9px;
        }

        .amenity-check {
            border: 1px solid #e6dcc2;
            border-radius: 10px;
            padding: 10px;

            display: flex;
            align-items: center;
            gap: 7px;

            cursor: pointer;

            font-size: 12px;
            color: #53635e;
        }

        .amenity-check:hover {
            background: #f8f5eb;
        }

        .amenity-check input {
            accent-color: #20584f;
        }

        .btn-cancel,
        .btn-save {
            border: none;
            height: 42px;

            padding: 0 18px;
            border-radius: 10px;

            font-family: inherit;
            font-weight: 700;
            font-size: 12px;

            cursor: pointer;
        }

        .btn-cancel {
            background: #f8f5eb;
            color: #53635e;
        }

        .btn-cancel:hover {
            background: #eaf3ef;
            color: #20584f;
        }

        .btn-save {
            background: #20584f;
            color: white;
        }

        .btn-save:hover {
            background: #17463e;
        }

        /* =========================
           DETAIL MODAL
        ========================= */

        .detail-image-wrap {
            width: 100%;
            height: 260px;

            border-radius: 16px;
            overflow: hidden;

            background: #f8f5eb;
            margin-bottom: 24px;
        }

        .detail-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px 35px;
        }

        .detail-item.full {
            grid-column: 1 / -1;
        }

        .detail-label {
            color: #78837e;
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #213430;
            font-size: 14px;
            font-weight: 700;
        }

        .detail-value.price {
            color: #20584f;
            font-size: 16px;
        }

        .detail-property {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .detail-status {
            display: inline-flex;

            padding: 7px 11px;
            border-radius: 999px;

            font-size: 11px;
            font-weight: 800;
        }

        .detail-status.available {
            background: #eaf3ef;
            color: #20584f;
        }

        .detail-status.rented {
            background: #fff7d7;
            color: #8b6810;
        }

        .detail-status.maintenance {
            background: #fae8e5;
            color: #a33f35;
        }

        .detail-amenities {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .detail-amenity {
            background: #eaf3ef;
            color: #20584f;

            padding: 8px 11px;
            border-radius: 9px;

            font-size: 12px;
            font-weight: 700;
        }

        .detail-empty {
            color: #78837e;
            font-size: 12px;
            font-style: italic;
        }

        /* =========================
           TOAST
        ========================= */

        .toast-message {
            position: fixed;
            right: 25px;
            bottom: 25px;

            z-index: 3000;

            background: #20584f;
            color: white;

            padding: 13px 18px;
            border-radius: 12px;

            font-size: 13px;
            font-weight: 700;

            box-shadow: 0 12px 30px rgba(33, 52, 48, .18);

            opacity: 0;
            transform: translateY(15px);
            pointer-events: none;

            transition: .25s;
        }

        .toast-message.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1100px) {
            .room-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .owner-nav {
                display: none;
            }
        }

        @media (max-width: 700px) {
            .owner-main {
                padding: 25px 15px 45px;
            }

            .owner-navbar-inner {
                padding: 0 15px;
            }

            .heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .toolbar {
                flex-direction: column;
            }

            .filter-select {
                width: 100%;
            }

            .room-grid {
                grid-template-columns: 1fr;
            }

            .form-grid,
            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-group.full,
            .detail-item.full {
                grid-column: auto;
            }

            .amenity-checks {
                grid-template-columns: 1fr 1fr;
            }

            .detail-image-wrap {
                height: 210px;
            }
        }

        @media (max-width: 450px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .owner-user-name,
            .owner-user-role {
                display: none;
            }

            .room-container {
                padding: 14px;
            }

            .modal-body,
            .modal-header,
            .modal-footer {
                padding-left: 17px;
                padding-right: 17px;
            }

            .amenity-checks {
                grid-template-columns: 1fr;
            }
        }
        /* =========================
   ADD ROOM
========================= */

.add-modal-box {
    width: min(720px, 100%);
}

.room-id-note {
    margin-top: 6px;
    color: #78837e;
    font-size: 11px;
}

.required {
    color: #c65b4a;
}

.empty-room-message {
    grid-column: 1 / -1;
    text-align: center;
    padding: 50px 20px;
    color: #78837e;
    font-size: 14px;
}

.empty-room-icon {
    font-size: 40px;
    margin-bottom: 10px;
}

.btn-add-save {
    border: none;
    height: 42px;
    padding: 0 18px;
    border-radius: 10px;
    font-family: inherit;
    font-weight: 700;
    font-size: 12px;
    cursor: pointer;
    background: #20584f;
    color: white;
}

.btn-add-save:hover {
    background: #17463e;
}

.btn-back {
    border: 1px solid #e6dcc2;
    background: #fff;
    color: #53635e;

    height: 42px;
    padding: 0 18px;
    border-radius: 10px;

    font-family: inherit;
    font-weight: 700;
    font-size: 12px;

    cursor: pointer;
}

.btn-back:hover {
    background: #f8f5eb;
    color: #20584f;
}

@media (max-width: 700px) {
    .add-modal-box {
        width: 100%;
    }
}
    </style>
</head>

<body>

<!-- =========================
     NAVBAR
========================= -->

<!-- =========================
     NAVBAR
========================= -->

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid">

        <!-- LOGO -->
        <a class="logo" href="{{ route('landlord') }}">
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
            aria-label="Mở menu">

            <span class="navbar-toggler-icon"></span>

        </button>

        <div class="collapse navbar-collapse" id="mainMenu">

            <!-- MENU OWNER -->
            <ul class="navbar-nav mx-auto align-items-lg-center">

                <!-- TỔNG QUAN -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ route('landlord') }}">

                        Tổng quan

                    </a>

                </li>


                <!-- NHÀ & PHÒNG -->
                <li class="nav-item dropdown">

                    <a
                        class="app-nav-link dropdown-toggle active"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">

                        Nhà &amp; Phòng

                    </a>

                    <ul class="dropdown-menu">

                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route('owner.properties.index') }}">

                                🏠 Quản lý nhà

                            </a>

                        </li>

                        <li>

                            <a
                                class="dropdown-item"
                                href="{{ route('owner.rooms.index') }}">

                                🚪 Quản lý phòng

                            </a>

                        </li>

                    </ul>

                </li>


                <!-- TIN ĐĂNG -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ route('owner.rental-posts.index') }}">

                        Tin đăng

                    </a>

                </li>


                <!-- LỊCH XEM -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ route('owner.appointments.index') }}">

                        Lịch xem

                    </a>

                </li>


                <!-- NGƯỜI THUÊ -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#">

                        Người thuê

                    </a>

                </li>


                <!-- HỢP ĐỒNG -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#">

                        Hợp đồng

                    </a>

                </li>


                <!-- HÓA ĐƠN -->
                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#">

                        Hóa đơn

                    </a>

                </li>

            </ul>


            <!-- USER AREA -->
            <div class="navbar-actions">

                <button
                    class="notif-btn"
                    type="button">

                    🔔

                    <span class="notif-dot"></span>

                </button>


                <a
                    href="#"
                    class="user-chip">

                    <span class="user-avatar">
                        AT
                    </span>


                    <span class="user-meta">

                        <span class="user-name d-block">
                            Anh Tuấn
                        </span>

                        <span class="user-role">
                            Chủ trọ
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


<!-- =========================
     MAIN
========================= -->

<main class="owner-main">

    <div class="heading">

        <div>
            
            <h1>Danh sách phòng</h1>

            <p>
                Quản lý trạng thái và thông tin các phòng đang cho thuê.
            </p>
        </div>

        <button class="btn-owner"  onclick="openAddModal()">
            ＋ Thêm phòng
        </button>

    </div>


    <!-- =========================
         STATS
    ========================= -->

    <div class="stats">

        <div class="stat">
            <div class="stat-icon">🏠</div>

            <div>
                <div class="stat-label">Tổng số phòng</div>
                <div class="stat-number" id="totalRooms">06</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon">✓</div>

            <div>
                <div class="stat-label">Còn trống</div>
                <div class="stat-number" id="availableRooms">03</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon">👤</div>

            <div>
                <div class="stat-label">Đang thuê</div>
                <div class="stat-number" id="rentedRooms">03</div>
            </div>
        </div>

        <div class="stat">
            <div class="stat-icon">🔧</div>

            <div>
                <div class="stat-label">Bảo trì</div>
                <div class="stat-number" id="maintenanceRooms">00</div>
            </div>
        </div>

    </div>


    <!-- =========================
         ROOM LIST
    ========================= -->

    <section class="room-container">

        <div class="toolbar">

            <div class="search-box">
                <span>⌕</span>

                <input
                    type="text"
                    id="roomSearch"
                    placeholder="Tìm theo tên phòng, mã phòng, khu vực..."
                >
            </div>

            <select id="propertyFilter" class="filter-select">
                <option value="">Tất cả nhà trọ</option>
                <option value="Nguyễn Thị Thập">Nguyễn Thị Thập</option>
                <option value="An Phú">An Phú</option>
                <option value="Tân Phú">Tân Phú</option>
            </select>

            <select id="statusFilter" class="filter-select">
                <option value="">Tất cả trạng thái</option>
                <option value="available">Còn trống</option>
                <option value="rented">Đang thuê</option>
                <option value="maintenance">Bảo trì</option>
            </select>

        </div>


        <div class="room-grid" id="roomGrid">


            <!-- ROOM 101 -->

            <article
                class="room-card"
                data-room-id="101"
                data-property="Nguyễn Thị Thập"
                data-status="rented"
                data-search="phòng 101 phòng máy lạnh nguyễn thị thập p.101"
            >

                <div class="room-img">

                    <img src="https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=900&q=80">

                    <span class="room-code">P.101</span>

                    <span class="room-status rented">
                        ĐANG THUÊ
                    </span>

                </div>

                <div class="room-body">

                    <h3>Phòng 101</h3>

                    <div class="room-property">
                        Nhà trọ Nguyễn Thị Thập
                    </div>

                    <div class="room-price">
                        2.800.000đ / tháng
                    </div>

                    <div class="room-info">
                        <span>22 m²</span>
                        <span>2 người</span>
                        <span>1 WC</span>
                    </div>

                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">📶 Wifi</span>
                        <span class="amenity">🛏 Nội thất</span>
                    </div>

                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">
                            Chỉnh sửa
                        </button>

                        <button class="btn-more" onclick="openDetailModal(this)">
                            Chi tiết
                        </button>
                    </div>

                </div>

            </article>


            <!-- ROOM 102 -->

            <article
                class="room-card"
                data-room-id="102"
                data-property="Nguyễn Thị Thập"
                data-status="available"
                data-search="phòng 102 nguyễn thị thập p.102"
            >

                <div class="room-img">

                    <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=900&q=80">

                    <span class="room-code">P.102</span>

                    <span class="room-status available">
                        CÒN TRỐNG
                    </span>

                </div>

                <div class="room-body">

                    <h3>Phòng 102</h3>

                    <div class="room-property">
                        Nhà trọ Nguyễn Thị Thập
                    </div>

                    <div class="room-price">
                        3.000.000đ / tháng
                    </div>

                    <div class="room-info">
                        <span>24 m²</span>
                        <span>2 người</span>
                        <span>1 WC</span>
                    </div>

                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">📶 Wifi</span>
                        <span class="amenity">🛵 Chỗ xe</span>
                    </div>

                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">
                            Chỉnh sửa
                        </button>

                        <button class="btn-more" onclick="openDetailModal(this)">
                            Chi tiết
                        </button>
                    </div>

                </div>

            </article>


            <!-- ROOM 203 -->

            <article
                class="room-card"
                data-room-id="203"
                data-property="An Phú"
                data-status="available"
                data-search="phòng 203 căn hộ mini an phú p.203"
            >

                <div class="room-img">

                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=900&q=80">

                    <span class="room-code">P.203</span>

                    <span class="room-status available">
                        CÒN TRỐNG
                    </span>

                </div>

                <div class="room-body">

                    <h3>Phòng 203</h3>

                    <div class="room-property">
                        Căn hộ mini An Phú
                    </div>

                    <div class="room-price">
                        6.800.000đ / tháng
                    </div>

                    <div class="room-info">
                        <span>35 m²</span>
                        <span>3 người</span>
                        <span>1 WC</span>
                    </div>

                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">🛏 Full nội thất</span>
                        <span class="amenity">📶 Wifi</span>
                    </div>

                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">
                            Chỉnh sửa
                        </button>

                        <button class="btn-more" onclick="openDetailModal(this)">
                            Chi tiết
                        </button>
                    </div>

                </div>

            </article>


            <!-- ROOM 205 -->

            <article
                class="room-card"
                data-room-id="205"
                data-property="An Phú"
                data-status="rented"
                data-search="phòng 205 căn hộ mini an phú p.205"
            >

                <div class="room-img">

                    <img src="https://images.unsplash.com/photo-1615874694520-474822394e73?auto=format&fit=crop&w=900&q=80">

                    <span class="room-code">P.205</span>

                    <span class="room-status rented">
                        ĐANG THUÊ
                    </span>

                </div>

                <div class="room-body">

                    <h3>Phòng 205</h3>

                    <div class="room-property">
                        Căn hộ mini An Phú
                    </div>

                    <div class="room-price">
                        6.500.000đ / tháng
                    </div>

                    <div class="room-info">
                        <span>32 m²</span>
                        <span>3 người</span>
                        <span>1 WC</span>
                    </div>

                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">🛏 Nội thất</span>
                        <span class="amenity">🛗 Thang máy</span>
                    </div>

                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">
                            Chỉnh sửa
                        </button>

                        <button class="btn-more" onclick="openDetailModal(this)">
                            Chi tiết
                        </button>
                    </div>

                </div>

            </article>


            <!-- ROOM 301 -->

            <article
                class="room-card"
                data-room-id="301"
                data-property="Tân Phú"
                data-status="available"
                data-search="phòng 301 tân phú p.301"
            >

                <div class="room-img">

                    <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=900&q=80">

                    <span class="room-code">P.301</span>

                    <span class="room-status available">
                        CÒN TRỐNG
                    </span>

                </div>

                <div class="room-body">

                    <h3>Phòng 301</h3>

                    <div class="room-property">
                        Nhà trọ Tân Phú
                    </div>

                    <div class="room-price">
                        3.200.000đ / tháng
                    </div>

                    <div class="room-info">
                        <span>24 m²</span>
                        <span>2 người</span>
                        <span>1 WC</span>
                    </div>

                    <div class="amenities">
                        <span class="amenity">🌀 Quạt</span>
                        <span class="amenity">📶 Wifi</span>
                        <span class="amenity">🛵 Chỗ xe</span>
                    </div>

                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">
                            Chỉnh sửa
                        </button>

                        <button class="btn-more" onclick="openDetailModal(this)">
                            Chi tiết
                        </button>
                    </div>

                </div>

            </article>


            <!-- ROOM 302 -->

            <article
                class="room-card"
                data-room-id="302"
                data-property="Tân Phú"
                data-status="rented"
                data-search="phòng 302 tân phú p.302"
            >

                <div class="room-img">

                    <img src="https://images.unsplash.com/photo-1600566753086-00f18fb6b3ea?auto=format&fit=crop&w=900&q=80">

                    <span class="room-code">P.302</span>

                    <span class="room-status rented">
                        ĐANG THUÊ
                    </span>

                </div>

                <div class="room-body">

                    <h3>Phòng 302</h3>

                    <div class="room-property">
                        Nhà trọ Tân Phú
                    </div>

                    <div class="room-price">
                        3.400.000đ / tháng
                    </div>

                    <div class="room-info">
                        <span>25 m²</span>
                        <span>2 người</span>
                        <span>1 WC</span>
                    </div>

                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">📶 Wifi</span>
                        <span class="amenity">🛵 Chỗ xe</span>
                    </div>

                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">
                            Chỉnh sửa
                        </button>

                        <button class="btn-more" onclick="openDetailModal(this)">
                            Chi tiết
                        </button>
                    </div>

                </div>

            </article>

        </div>

    </section>

</main>

<!-- =====================================================
     MODAL THÊM PHÒNG
===================================================== -->

<div class="custom-modal" id="addModal">

    <div class="modal-box add-modal-box">

        <div class="modal-header">

            <div class="modal-title-area">

                <div class="modal-title-icon">
                    ＋
                </div>

                <div>
                    <h2 class="modal-title">
                        Thêm phòng mới
                    </h2>

                    <p class="modal-subtitle">
                        Nhập thông tin phòng muốn thêm vào danh sách.
                    </p>
                </div>

            </div>

            <button
                type="button"
                class="modal-close"
                onclick="closeAddModal()"
            >
                ×
            </button>

        </div>


        <div class="modal-body">

            <div class="form-grid">

                <!-- TÊN -->

                <div class="form-group">

                    <label class="form-label">
                        Tên phòng <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="addName"
                        class="edit-input"
                        placeholder="Ví dụ: Phòng 103"
                    >

                </div>


                <!-- MÃ -->

                <div class="form-group">

                    <label class="form-label">
                        Mã phòng <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="addCode"
                        class="edit-input"
                        placeholder="Ví dụ: P.103"
                    >

                </div>


                <!-- NHÀ TRỌ -->

                <div class="form-group full">

                    <label class="form-label">
                        Nhà trọ <span class="required">*</span>
                    </label>

                    <select
                        id="addProperty"
                        class="edit-select"
                    >

                        <option value="Nguyễn Thị Thập">
                            Nhà trọ Nguyễn Thị Thập
                        </option>

                        <option value="An Phú">
                            Căn hộ mini An Phú
                        </option>

                        <option value="Tân Phú">
                            Nhà trọ Tân Phú
                        </option>

                    </select>

                </div>


                <!-- GIÁ -->

                <div class="form-group">

                    <label class="form-label">
                        Giá thuê / tháng <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="addPrice"
                        class="edit-input"
                        min="0"
                        placeholder="Ví dụ: 3000000"
                    >

                </div>


                <!-- DIỆN TÍCH -->

                <div class="form-group">

                    <label class="form-label">
                        Diện tích (m²) <span class="required">*</span>
                    </label>

                    <input
                        type="number"
                        id="addArea"
                        class="edit-input"
                        min="1"
                        placeholder="Ví dụ: 24"
                    >

                </div>


                <!-- SỐ NGƯỜI -->

                <div class="form-group">

                    <label class="form-label">
                        Số người
                    </label>

                    <input
                        type="number"
                        id="addPeople"
                        class="edit-input"
                        min="1"
                        value="2"
                    >

                </div>


                <!-- WC -->

                <div class="form-group">

                    <label class="form-label">
                        Số WC
                    </label>

                    <input
                        type="number"
                        id="addBathroom"
                        class="edit-input"
                        min="0"
                        value="1"
                    >

                </div>


                <!-- TRẠNG THÁI -->

                <div class="form-group full">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select
                        id="addStatus"
                        class="edit-select"
                    >

                        <option value="available">
                            Còn trống
                        </option>

                        <option value="rented">
                            Đang thuê
                        </option>

                        <option value="maintenance">
                            Bảo trì
                        </option>

                    </select>

                </div>


                <!-- TIỆN ÍCH -->

                <div class="form-group full">

                    <label class="form-label">
                        Tiện ích
                    </label>

                    <div class="amenity-checks">

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="❄ Máy lạnh"
                            >
                            ❄ Máy lạnh
                        </label>

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="🌀 Quạt"
                            >
                            🌀 Quạt
                        </label>

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="📶 Wifi"
                            >
                            📶 Wifi
                        </label>

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="🛏 Nội thất"
                            >
                            🛏 Nội thất
                        </label>

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="🛏 Full nội thất"
                            >
                            🛏 Full nội thất
                        </label>

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="🛵 Chỗ xe"
                            >
                            🛵 Chỗ xe
                        </label>

                        <label class="amenity-check">
                            <input
                                type="checkbox"
                                class="add-amenity"
                                value="🛗 Thang máy"
                            >
                            🛗 Thang máy
                        </label>

                    </div>

                </div>


                <!-- LINK ẢNH -->

                <div class="form-group full">

                    <label class="form-label">
                        Link ảnh phòng
                    </label>

                    <input
                        type="text"
                        id="addImage"
                        class="edit-input"
                        placeholder="https://..."
                    >

                    <div class="room-id-note">
                        Có thể bỏ trống nếu chưa có ảnh.
                    </div>

                </div>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-back"
                onclick="closeAddModal()"
            >
                ← Trở về
            </button>

            <button
                type="button"
                class="btn-add-save"
                onclick="addRoom()"
            >
                ✓ Lưu phòng
            </button>

        </div>

    </div>

</div>
<!-- =====================================================
     MODAL CHỈNH SỬA
===================================================== -->

<div class="custom-modal" id="editModal">

    <div class="modal-box">

        <div class="modal-header">

            <div class="modal-title-area">

                <div class="modal-title-icon">
                    ✏️
                </div>

                <div>
                    <h2 class="modal-title">
                        Chỉnh sửa phòng
                    </h2>

                    <p class="modal-subtitle">
                        Thông tin hiện tại của phòng được điền sẵn.
                    </p>
                </div>

            </div>

            <button class="modal-close" onclick="closeEditModal()">
                ×
            </button>

        </div>


        <div class="modal-body">

            <div class="form-grid">

                <div class="form-group">
                    <label class="form-label">Tên phòng</label>

                    <input
                        type="text"
                        id="editName"
                        class="edit-input"
                    >
                </div>


                <div class="form-group">
                    <label class="form-label">Mã phòng</label>

                    <input
                        type="text"
                        id="editCode"
                        class="edit-input"
                    >
                </div>


                <div class="form-group full">
                    <label class="form-label">Nhà trọ</label>

                    <select id="editProperty" class="edit-select">
                        <option value="Nguyễn Thị Thập">
                            Nhà trọ Nguyễn Thị Thập
                        </option>

                        <option value="An Phú">
                            Căn hộ mini An Phú
                        </option>

                        <option value="Tân Phú">
                            Nhà trọ Tân Phú
                        </option>
                    </select>
                </div>


                <div class="form-group">
                    <label class="form-label">
                        Giá thuê / tháng
                    </label>

                    <input
                        type="number"
                        id="editPrice"
                        class="edit-input"
                    >
                </div>


                <div class="form-group">
                    <label class="form-label">
                        Diện tích (m²)
                    </label>

                    <input
                        type="number"
                        id="editArea"
                        class="edit-input"
                    >
                </div>


                <div class="form-group">
                    <label class="form-label">
                        Số người
                    </label>

                    <input
                        type="number"
                        id="editPeople"
                        class="edit-input"
                    >
                </div>


                <div class="form-group">
                    <label class="form-label">
                        Số WC
                    </label>

                    <input
                        type="number"
                        id="editBathroom"
                        class="edit-input"
                    >
                </div>


                <div class="form-group full">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select id="editStatus" class="edit-select">

                        <option value="available">
                            Còn trống
                        </option>

                        <option value="rented">
                            Đang thuê
                        </option>

                        <option value="maintenance">
                            Bảo trì
                        </option>

                    </select>

                </div>


                <div class="form-group full">

                    <label class="form-label">
                        Tiện ích
                    </label>

                    <div class="amenity-checks">

                        <label class="amenity-check">
                            <input type="checkbox" value="❄ Máy lạnh">
                            ❄ Máy lạnh
                        </label>

                        <label class="amenity-check">
                            <input type="checkbox" value="🌀 Quạt">
                            🌀 Quạt
                        </label>

                        <label class="amenity-check">
                            <input type="checkbox" value="📶 Wifi">
                            📶 Wifi
                        </label>

                        <label class="amenity-check">
                            <input type="checkbox" value="🛏 Nội thất">
                            🛏 Nội thất
                        </label>

                        <label class="amenity-check">
                            <input type="checkbox" value="🛏 Full nội thất">
                            🛏 Full nội thất
                        </label>

                        <label class="amenity-check">
                            <input type="checkbox" value="🛵 Chỗ xe">
                            🛵 Chỗ xe
                        </label>

                        <label class="amenity-check">
                            <input type="checkbox" value="🛗 Thang máy">
                            🛗 Thang máy
                        </label>

                    </div>

                </div>


                <div class="form-group full">

                    <label class="form-label">
                        Link ảnh phòng
                    </label>

                    <input
                        type="text"
                        id="editImage"
                        class="edit-input"
                    >

                </div>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-cancel"
                onclick="closeEditModal()"
            >
                Hủy
            </button>

            <button
                type="button"
                class="btn-save"
                onclick="saveRoom()"
            >
                ✓ Lưu thay đổi
            </button>

        </div>

    </div>

</div>


<!-- =====================================================
     MODAL CHI TIẾT
===================================================== -->

<div class="custom-modal" id="detailModal">

    <div class="modal-box">

        <div class="modal-header">

            <div class="modal-title-area">

                <div class="modal-title-icon">
                    🏠
                </div>

                <div>
                    <h2 class="modal-title">
                        Chi tiết phòng
                    </h2>

                    <p class="modal-subtitle">
                        Thông tin đầy đủ của phòng
                    </p>
                </div>

            </div>

            <button
                class="modal-close"
                onclick="closeDetailModal()"
            >
                ×
            </button>

        </div>


        <div class="modal-body">

            <div class="detail-image-wrap">

                <img
                    id="detailImage"
                    class="detail-image"
                    src=""
                    alt="Ảnh phòng"
                >

            </div>


            <div class="detail-grid">

                <div class="detail-item">

                    <div class="detail-label">
                        Tên phòng
                    </div>

                    <div
                        class="detail-value"
                        id="detailName"
                    ></div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Mã phòng
                    </div>

                    <div
                        class="detail-value"
                        id="detailCode"
                    ></div>

                </div>


                <div class="detail-item full">

                    <div class="detail-label">
                        Nhà trọ
                    </div>

                    <div
                        class="detail-value detail-property"
                        id="detailProperty"
                    ></div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Giá thuê
                    </div>

                    <div
                        class="detail-value price"
                        id="detailPrice"
                    ></div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Diện tích
                    </div>

                    <div
                        class="detail-value"
                        id="detailArea"
                    ></div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Số người
                    </div>

                    <div
                        class="detail-value"
                        id="detailPeople"
                    ></div>

                </div>


                <div class="detail-item">

                    <div class="detail-label">
                        Số WC
                    </div>

                    <div
                        class="detail-value"
                        id="detailBathroom"
                    ></div>

                </div>


                <div class="detail-item full">

                    <div class="detail-label">
                        Trạng thái
                    </div>

                    <div id="detailStatus"></div>

                </div>


                <div class="detail-item full">

                    <div class="detail-label">
                        Tiện ích
                    </div>

                    <div
                        class="detail-amenities"
                        id="detailAmenities"
                    ></div>

                </div>

            </div>

        </div>


        <div class="modal-footer">

            <button
                type="button"
                class="btn-save"
                onclick="closeDetailModal()"
            >
                Đóng
            </button>

        </div>

    </div>

</div>


<!-- TOAST -->

<div
    class="toast-message"
    id="toastMessage"
>
    ✓ Đã lưu thay đổi phòng
</div>


<script>

    /* =====================================================
       BIẾN
    ===================================================== */

    let currentEditCard = null;

    const STORAGE_KEY = "troOiOwnerRooms";


    /* =====================================================
       HELPER
    ===================================================== */

    function formatMoney(value) {

        const number = Number(value) || 0;

        return number.toLocaleString("vi-VN") + "đ / tháng";
    }


    function getStatusText(status) {

        const statusMap = {
            available: "CÒN TRỐNG",
            rented: "ĐANG THUÊ",
            maintenance: "BẢO TRÌ"
        };

        return statusMap[status] || status;
    }


    function getPropertyDisplay(property) {

        const propertyMap = {
            "Nguyễn Thị Thập": "Nhà trọ Nguyễn Thị Thập",
            "An Phú": "Căn hộ mini An Phú",
            "Tân Phú": "Nhà trọ Tân Phú"
        };

        return propertyMap[property] || property;
    }


    function getAmenities(card) {

        return [...card.querySelectorAll(".amenity")]
            .map(item => item.textContent.trim());
    }


    function getRoomData(card) {

        const info = card.querySelectorAll(".room-info span");

        return {

            id: card.dataset.roomId,

            name: card.querySelector(".room-body h3").textContent.trim(),

            code: card.querySelector(".room-code").textContent.trim(),

            property: card.dataset.property,

            propertyDisplay:
                card.querySelector(".room-property").textContent.trim(),

            price:
                parseInt(
                    card.querySelector(".room-price")
                        .textContent
                        .replace(/\D/g, "")
                ) || 0,

            area:
                parseInt(
                    info[0]?.textContent.replace(/\D/g, "")
                ) || 0,

            people:
                parseInt(
                    info[1]?.textContent.replace(/\D/g, "")
                ) || 0,

            bathroom:
                parseInt(
                    info[2]?.textContent.replace(/\D/g, "")
                ) || 0,

            status: card.dataset.status,

            amenities: getAmenities(card),

            image: card.querySelector(".room-img img").src

        };

    }


    /* =====================================================
       UPDATE CARD
    ===================================================== */

    function applyRoomData(card, data) {

        card.querySelector(".room-body h3").textContent =
            data.name;

        card.querySelector(".room-code").textContent =
            data.code;

        card.querySelector(".room-property").textContent =
            getPropertyDisplay(data.property);

        card.querySelector(".room-price").textContent =
            formatMoney(data.price);


        const info = card.querySelectorAll(".room-info span");

        info[0].textContent =
            data.area + " m²";

        info[1].textContent =
            data.people + " người";

        info[2].textContent =
            data.bathroom + " WC";


        /* Status */

        const statusBadge =
            card.querySelector(".room-status");

        statusBadge.className =
            "room-status " + data.status;

        statusBadge.textContent =
            getStatusText(data.status);

        card.dataset.status =
            data.status;


        /* Property */

        card.dataset.property =
            data.property;


        /* Image */

        card.querySelector(".room-img img").src =
            data.image;


        /* Amenities */

        const amenitiesBox =
            card.querySelector(".amenities");

        amenitiesBox.innerHTML = "";

        data.amenities.forEach(amenity => {

            const span =
                document.createElement("span");

            span.className = "amenity";

            span.textContent = amenity;

            amenitiesBox.appendChild(span);

        });


        /* Search */

        card.dataset.search = [

            data.name,
            data.code,
            data.property,
            data.propertyDisplay,
            ...data.amenities

        ].join(" ").toLowerCase();

    }

    /* =====================================================
   THÊM PHÒNG
===================================================== */

function openAddModal() {

    /* Reset toàn bộ form */

    document.getElementById("addName").value = "";
    document.getElementById("addCode").value = "";

    document.getElementById("addProperty").value =
        "Nguyễn Thị Thập";

    document.getElementById("addPrice").value = "";
    document.getElementById("addArea").value = "";
    document.getElementById("addPeople").value = "2";
    document.getElementById("addBathroom").value = "1";
    document.getElementById("addStatus").value = "available";
    document.getElementById("addImage").value = "";

    document
        .querySelectorAll(".add-amenity")
        .forEach(checkbox => {
            checkbox.checked = false;
        });

    document
        .getElementById("addModal")
        .classList.add("show");

    setTimeout(() => {
        document.getElementById("addName").focus();
    }, 100);
}


function closeAddModal() {

    document
        .getElementById("addModal")
        .classList.remove("show");

}


/* =====================================================
   LƯU PHÒNG MỚI
===================================================== */

function addRoom() {

    const name =
        document.getElementById("addName").value.trim();

    const code =
        document.getElementById("addCode").value.trim();

    const property =
        document.getElementById("addProperty").value;

    const price =
        Number(document.getElementById("addPrice").value);

    const area =
        Number(document.getElementById("addArea").value);

    const people =
        Number(document.getElementById("addPeople").value) || 0;

    const bathroom =
        Number(document.getElementById("addBathroom").value) || 0;

    const status =
        document.getElementById("addStatus").value;

    const image =
        document.getElementById("addImage").value.trim();


    /* Kiểm tra */

    if (!name) {
        alert("Vui lòng nhập tên phòng.");
        document.getElementById("addName").focus();
        return;
    }

    if (!code) {
        alert("Vui lòng nhập mã phòng.");
        document.getElementById("addCode").focus();
        return;
    }

    if (!price || price < 0) {
        alert("Vui lòng nhập giá thuê hợp lệ.");
        document.getElementById("addPrice").focus();
        return;
    }

    if (!area || area <= 0) {
        alert("Vui lòng nhập diện tích hợp lệ.");
        document.getElementById("addArea").focus();
        return;
    }


    /* Kiểm tra trùng mã phòng */

    const cards =
        [...document.querySelectorAll(".room-card")];

    const duplicate =
        cards.some(card =>
            card.querySelector(".room-code")
                .textContent
                .trim()
                .toLowerCase() === code.toLowerCase()
        );

    if (duplicate) {

        alert("Mã phòng này đã tồn tại. Vui lòng nhập mã khác.");

        document.getElementById("addCode").focus();

        return;
    }


    /* Tiện ích */

    const amenities =
        [...document.querySelectorAll(".add-amenity:checked")]
            .map(input => input.value);


    /* ID mới */

    const roomId =
        Date.now().toString();


    /* Nếu không có ảnh */

    const roomImage =
        image ||
        "https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=900&q=80";


    const data = {

        id: roomId,

        name: name,

        code: code,

        property: property,

        price: price,

        area: area,

        people: people,

        bathroom: bathroom,

        status: status,

        amenities: amenities,

        image: roomImage

    };


    /* Tạo card */

    const card =
        createRoomCard(data);


    document
        .getElementById("roomGrid")
        .appendChild(card);


    /* Lưu localStorage */

    saveNewRoomToLocalStorage(data);


    /* Cập nhật thống kê */

    updateStats();


    /* Đóng */

    closeAddModal();


    /* Chạy lại bộ lọc */

    filterRooms();


    /* Thông báo */

    showAddToast();

}


/* =====================================================
   TẠO CARD PHÒNG
===================================================== */

function createRoomCard(data) {

    const card =
        document.createElement("article");

    card.className = "room-card";

    card.dataset.roomId =
        data.id;

    card.dataset.property =
        data.property;

    card.dataset.status =
        data.status;

    card.dataset.search =
        [
            data.name,
            data.code,
            data.property,
            getPropertyDisplay(data.property),
            ...data.amenities
        ]
        .join(" ")
        .toLowerCase();


    /* Card HTML */

    card.innerHTML = `

        <div class="room-img">

            <img
                src="${escapeHtml(data.image)}"
                alt="${escapeHtml(data.name)}"
            >

            <span class="room-code">
                ${escapeHtml(data.code)}
            </span>

            <span class="room-status ${data.status}">
                ${getStatusText(data.status)}
            </span>

        </div>


        <div class="room-body">

            <h3>
                ${escapeHtml(data.name)}
            </h3>

            <div class="room-property">
                ${escapeHtml(
                    getPropertyDisplay(data.property)
                )}
            </div>

            <div class="room-price">
                ${formatMoney(data.price)}
            </div>

            <div class="room-info">

                <span>
                    ${data.area} m²
                </span>

                <span>
                    ${data.people} người
                </span>

                <span>
                    ${data.bathroom} WC
                </span>

            </div>


            <div class="amenities">

                ${
                    data.amenities.length
                    ?
                    data.amenities.map(amenity => `
                        <span class="amenity">
                            ${escapeHtml(amenity)}
                        </span>
                    `).join("")
                    :
                    ""
                }

            </div>


            <div class="room-actions">

                <button
                    class="btn-edit"
                    onclick="openEditModal(this)"
                >
                    Chỉnh sửa
                </button>

                <button
                    class="btn-more"
                    onclick="openDetailModal(this)"
                >
                    Chi tiết
                </button>

            </div>

        </div>
    `;


    return card;
}


/* =====================================================
   ESCAPE HTML
===================================================== */

function escapeHtml(value) {

    return String(value)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");

}


/* =====================================================
   LƯU PHÒNG MỚI
===================================================== */

function saveNewRoomToLocalStorage(data) {

    let savedRooms =
        JSON.parse(
            localStorage.getItem(STORAGE_KEY)
        ) || {};

    savedRooms[data.id] = data;

    localStorage.setItem(
        STORAGE_KEY,
        JSON.stringify(savedRooms)
    );

}

    /* =====================================================
       EDIT MODAL
    ===================================================== */

    function openEditModal(button) {

        currentEditCard =
            button.closest(".room-card");

        const data =
            getRoomData(currentEditCard);


        document.getElementById("editName").value =
            data.name;

        document.getElementById("editCode").value =
            data.code;

        document.getElementById("editProperty").value =
            data.property;

        document.getElementById("editPrice").value =
            data.price;

        document.getElementById("editArea").value =
            data.area;

        document.getElementById("editPeople").value =
            data.people;

        document.getElementById("editBathroom").value =
            data.bathroom;

        document.getElementById("editStatus").value =
            data.status;

        document.getElementById("editImage").value =
            data.image;


        /* Reset checkbox */

        document
            .querySelectorAll(".amenity-check input")
            .forEach(checkbox => {

                checkbox.checked =
                    data.amenities.includes(
                        checkbox.value
                    );

            });


        document
            .getElementById("editModal")
            .classList.add("show");

    }


    function closeEditModal() {

        document
            .getElementById("editModal")
            .classList.remove("show");

        currentEditCard = null;

    }


    /* =====================================================
       SAVE ROOM
    ===================================================== */

    function saveRoom() {

        if (!currentEditCard) {
            return;
        }


        const amenities =
            [...document.querySelectorAll(".amenity-check input:checked")]
                .map(input => input.value);


        const data = {

            id:
                currentEditCard.dataset.roomId,

            name:
                document.getElementById("editName").value.trim(),

            code:
                document.getElementById("editCode").value.trim(),

            property:
                document.getElementById("editProperty").value,

            price:
                Number(
                    document.getElementById("editPrice").value
                ),

            area:
                Number(
                    document.getElementById("editArea").value
                ),

            people:
                Number(
                    document.getElementById("editPeople").value
                ),

            bathroom:
                Number(
                    document.getElementById("editBathroom").value
                ),

            status:
                document.getElementById("editStatus").value,

            amenities: amenities,

            image:
                document.getElementById("editImage").value.trim()

        };


        /* Cập nhật giao diện */

        applyRoomData(
            currentEditCard,
            data
        );


        /* Lưu localStorage */

        saveToLocalStorage(
            currentEditCard,
            data
        );


        /* Cập nhật thống kê */

        updateStats();


        /* Đóng modal */

        closeEditModal();


        /* Chạy lại filter */

        filterRooms();


        /* Thông báo */

        showToast();

    }


    /* =====================================================
       DETAIL MODAL
    ===================================================== */

    function openDetailModal(button) {

        const card =
            button.closest(".room-card");

        const data =
            getRoomData(card);


        document.getElementById("detailImage").src =
            data.image;


        document.getElementById("detailName").textContent =
            data.name;


        document.getElementById("detailCode").textContent =
            data.code;


        document.getElementById("detailProperty").innerHTML =
            "🏠 " + getPropertyDisplay(data.property);


        document.getElementById("detailPrice").textContent =
            formatMoney(data.price);


        document.getElementById("detailArea").textContent =
            data.area + " m²";


        document.getElementById("detailPeople").textContent =
            data.people + " người";


        document.getElementById("detailBathroom").textContent =
            data.bathroom + " WC";


        /* Status */

        const statusBox =
            document.getElementById("detailStatus");

        statusBox.innerHTML = "";

        const status =
            document.createElement("span");

        status.className =
            "detail-status " + data.status;

        status.textContent =
            getStatusText(data.status);

        statusBox.appendChild(status);


        /* Amenities */

        const amenitiesBox =
            document.getElementById("detailAmenities");

        amenitiesBox.innerHTML = "";


        if (data.amenities.length === 0) {

            const empty =
                document.createElement("span");

            empty.className =
                "detail-empty";

            empty.textContent =
                "Chưa có tiện ích";

            amenitiesBox.appendChild(empty);

        } else {

            data.amenities.forEach(amenity => {

                const item =
                    document.createElement("span");

                item.className =
                    "detail-amenity";

                item.textContent =
                    amenity;

                amenitiesBox.appendChild(item);

            });

        }


        document
            .getElementById("detailModal")
            .classList.add("show");

    }


    function closeDetailModal() {

        document
            .getElementById("detailModal")
            .classList.remove("show");

    }


    /* =====================================================
       LOCAL STORAGE
    ===================================================== */

    function saveToLocalStorage(card, data) {

        let savedRooms =
            JSON.parse(
                localStorage.getItem(STORAGE_KEY)
            ) || {};


        savedRooms[data.id] = data;


        localStorage.setItem(
            STORAGE_KEY,
            JSON.stringify(savedRooms)
        );

    }


    function loadSavedRooms() {

    const savedRooms =
        JSON.parse(
            localStorage.getItem(STORAGE_KEY)
        ) || {};


    document
        .querySelectorAll(".room-card")
        .forEach(card => {

            const roomId =
                card.dataset.roomId;

            if (savedRooms[roomId]) {

                applyRoomData(
                    card,
                    savedRooms[roomId]
                );

            }

        });


    /*
       Thêm lại những phòng đã tạo mới
       nhưng không có sẵn trong HTML
    */

    const existingIds =
        new Set(
            [...document.querySelectorAll(".room-card")]
                .map(card => card.dataset.roomId)
        );


    Object.values(savedRooms)
        .forEach(data => {

            if (!existingIds.has(String(data.id))) {

                const card =
                    createRoomCard(data);

                document
                    .getElementById("roomGrid")
                    .appendChild(card);

            }

        });

}


    /* =====================================================
       FILTER
    ===================================================== */

    const search =
        document.getElementById("roomSearch");

    const property =
        document.getElementById("propertyFilter");

    const status =
        document.getElementById("statusFilter");


    function filterRooms() {

        const keyword =
            search.value.toLowerCase().trim();

        const propertyValue =
            property.value;

        const statusValue =
            status.value;


        document
            .querySelectorAll(".room-card")
            .forEach(card => {

                const text =
                    card.dataset.search.toLowerCase();

                const cardProperty =
                    card.dataset.property;

                const cardStatus =
                    card.dataset.status;


                const matchKeyword =
                    text.includes(keyword);

                const matchProperty =
                    !propertyValue ||
                    cardProperty === propertyValue;

                const matchStatus =
                    !statusValue ||
                    cardStatus === statusValue;


                card.style.display =
                    matchKeyword &&
                    matchProperty &&
                    matchStatus
                        ? ""
                        : "none";

            });

    }


    search.addEventListener(
        "input",
        filterRooms
    );

    property.addEventListener(
        "change",
        filterRooms
    );

    status.addEventListener(
        "change",
        filterRooms
    );


    /* =====================================================
       STATS
    ===================================================== */

    function updateStats() {

        const cards =
            [...document.querySelectorAll(".room-card")];

        let available = 0;
        let rented = 0;
        let maintenance = 0;


        cards.forEach(card => {

            if (card.dataset.status === "available") {
                available++;
            }

            if (card.dataset.status === "rented") {
                rented++;
            }

            if (card.dataset.status === "maintenance") {
                maintenance++;
            }

        });


        document.getElementById("totalRooms")
            .textContent =
            String(cards.length).padStart(2, "0");

        document.getElementById("availableRooms")
            .textContent =
            String(available).padStart(2, "0");

        document.getElementById("rentedRooms")
            .textContent =
            String(rented).padStart(2, "0");

        document.getElementById("maintenanceRooms")
            .textContent =
            String(maintenance).padStart(2, "0");

    }


    /* =====================================================
       TOAST
    ===================================================== */

    function showToast() {

        const toast =
            document.getElementById("toastMessage");


        toast.classList.add("show");


        setTimeout(() => {

            toast.classList.remove("show");

        }, 2200);

    }
    function showAddToast() {

    const toast =
        document.getElementById("toastMessage");

    toast.textContent =
        "✓ Đã thêm phòng mới";

    toast.classList.add("show");

    setTimeout(() => {

        toast.classList.remove("show");

        toast.textContent =
            "✓ Đã lưu thay đổi phòng";

    }, 2200);

}


    /* =====================================================
       CLICK RA NGOÀI MODAL
    ===================================================== */

    document
        .getElementById("editModal")
        .addEventListener("click", function(event) {

            if (event.target === this) {
                closeEditModal();
            }

        });


    document
        .getElementById("detailModal")
        .addEventListener("click", function(event) {

            if (event.target === this) {
                closeDetailModal();
            }

        });


    /* =====================================================
       ESC ĐỂ ĐÓNG
    ===================================================== */

    document.addEventListener(
        "keydown",
        function(event) {

            if (event.key === "Escape") {

                closeEditModal();

                closeDetailModal();

            }

        }
    );

    // Đóng Add Modal khi bấm ra ngoài
document
    .getElementById("addModal")
    .addEventListener("click", function(event) {
        if (event.target === this) {
            closeAddModal();
        }
    });

// Nhấn ESC để đóng modal
document.addEventListener("keydown", function(event) {
    if (event.key === "Escape") {
        closeAddModal();
        closeEditModal();
        closeDetailModal();
    }
});

    /* =====================================================
       LOAD DỮ LIỆU ĐÃ LƯU
    ===================================================== */

    loadSavedRooms();

    updateStats();

</script>

</body>
</html>