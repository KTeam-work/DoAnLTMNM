<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Quản lý nhà trọ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f8f5eb;
            color: #213430;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 28px;
        }

        .eyebrow {
            color: #20584f;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .page-heading h1 {
            font-size: 30px;
            font-weight: 800;
            margin: 0;
        }

        .page-heading p {
            color: #78837e;
            margin: 8px 0 0;
        }

        .btn-owner {
            border: none;
            background: #20584f;
            color: white;
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-owner:hover {
            background: #17463e;
            color: white;
            transform: translateY(-1px);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: white;
            border: 1px solid #e6dcc2;
            border-radius: 22px;
            padding: 20px;
            box-shadow: 0 8px 28px rgba(32,88,79,.05);
        }

        .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .stat-icon {
            width: 46px;
            height: 46px;
            border-radius: 15px;
            background: #eaf3ef;
            color: #20584f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .stat-card strong {
            display: block;
            font-size: 28px;
            margin-top: 15px;
        }

        .stat-card span {
            color: #78837e;
            font-size: 13px;
        }

        .content-card {
            background: white;
            border: 1px solid #e6dcc2;
            border-radius: 24px;
            box-shadow: 0 10px 32px rgba(32,88,79,.05);
            overflow: hidden;
        }

        .card-header-owner {
            padding: 22px 24px;
            border-bottom: 1px solid #eee7d8;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header-owner h2 {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
        }

        .card-header-owner p {
            margin: 4px 0 0;
            color: #78837e;
            font-size: 13px;
        }

        .property-grid {
            padding: 22px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .property-card {
            border: 1px solid #e6dcc2;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            transition: .25s;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(32,88,79,.10);
        }

        .property-image {
            height: 175px;
            position: relative;
            overflow: hidden;
        }

        .property-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .property-status {
            position: absolute;
            top: 12px;
            right: 12px;
            background: #eaf3ef;
            color: #20584f;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 800;
        }

        .property-status.inactive {
            background: #f1f1ef;
            color: #78837e;
        }

        .property-body {
            padding: 18px;
        }

        .property-body h3 {
            font-size: 17px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .property-address {
            color: #78837e;
            font-size: 12px;
            min-height: 38px;
        }

        .property-meta {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            border-top: 1px solid #eee7d8;
            border-bottom: 1px solid #eee7d8;
            margin: 16px 0;
            padding: 12px 0;
            text-align: center;
        }

        .property-meta div {
            border-right: 1px solid #eee7d8;
        }

        .property-meta div:last-child {
            border-right: none;
        }

        .property-meta strong {
            display: block;
            font-size: 15px;
        }

        .property-meta span {
            color: #78837e;
            font-size: 10px;
        }

        .property-actions {
            display: flex;
            gap: 8px;
        }

        .property-actions button {
            flex: 1;
            border: none;
            border-radius: 11px;
            padding: 9px;
            font-size: 12px;
            font-weight: 700;
        }

        .btn-light-owner {
            background: #eaf3ef;
            color: #20584f;
        }

        .btn-delete-owner {
            background: #fff0ed;
            color: #c65b4a;
        }

        .modal-content {
            border: none;
            border-radius: 24px;
            overflow: hidden;
        }

        .modal-header {
            background: #20584f;
            color: white;
            border: none;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 11px 13px;
            border-color: #e6dcc2;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #20584f;
            box-shadow: 0 0 0 .2rem rgba(32,88,79,.10);
        }

        .required {
            color: #c65b4a;
        }

        .empty-property {
            grid-column: 1 / -1;
            text-align: center;
            padding: 50px 20px;
            color: #78837e;
        }

        .empty-property-icon {
            font-size: 45px;
            margin-bottom: 12px;
        }

        @media (max-width: 1000px) {
            .stats-grid,
            .property-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 650px) {
            .stats-grid,
            .property-grid {
                grid-template-columns: 1fr;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="owner-page">

    <!-- ================= NAVBAR ================= -->
   <!-- NAVBAR ĐÃ SỬA LỖI -->
<!-- ================= NAVBAR ĐÃ SỬA ================= -->
<nav class="navbar navbar-expand-xl app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler shadow-none border-0" type="button"
            data-bs-toggle="collapse" data-bs-target="#mainMenu"
            aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">

        <!-- 1. TỔNG QUAN -->
        <li class="nav-item">
          <a class="app-nav-link 
             href="{{ url('/landlord') }}">Tổng quan</a>
        </li>

        <!-- 2. NHÀ & PHÒNG -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle active {{ request()->routeIs('owner.properties.*') || request()->routeIs('owner.rooms.*') ? 'active' : '' }}"
             href="#" id="navbarDrop1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Nhà &amp; Phòng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
            <li><a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a></li>
          </ul>
        </li>

        <!-- 3. KHÁCH & HỢP ĐỒNG -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle {{ request()->routeIs('owner.tenants.*') || request()->routeIs('owner.contracts.*') ? 'active' : '' }}"
             href="#" id="navbarDrop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Khách &amp; Hợp đồng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop2">
            <li><a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a></li>
          </ul>
        </li>

        <!-- 4. TÀI CHÍNH -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle {{ request()->routeIs('owner.services.*') || request()->routeIs('owner.utilities.*') || request()->routeIs('owner.payments.*') || request()->routeIs('owner.invoices.*') ? 'active' : '' }}"
             href="#" id="navbarDrop3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tài chính
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop3">
            <li><a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.utilities.index') }}">⚡ Điện nước</a></li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route( 'owner.payments.index') }}">💰 Giao dịch</a>
            </li>
          </ul>
        </li>

        <!-- 5. VẬN HÀNH -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle {{ request()->routeIs('owner.rental-posts.*') || request()->routeIs('owner.appointments.*') || request()->routeIs('owner.reviews.*') || request()->is('owner/maintenance*') ? 'active' : '' }}"
             href="#" id="navbarDrop4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Vận hành
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop4">
            <li><a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.appointments.index') }}">📅 Lịch xem</a></li>
            <li><a class="dropdown-item" href="{{ url('/owner/maintenance') }}">🛠️ Sửa chữa</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.reviews.index') }}">⭐ Đánh giá</a></li>
          </ul>
        </li>

      </ul>

      <!-- ===== KHỐI BÊN PHẢI: CHUÔNG + USER ===== -->
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

    <!-- ================= MAIN ================= -->

    <main class="owner-main">

        <div class="page-heading">

            <div>

                <div class="eyebrow">
                    Owner workspace
                </div>

                <h1>
                    Quản lý nhà trọ
                </h1>

                <p>
                    Theo dõi và quản lý toàn bộ bất động sản của bạn.
                </p>

            </div>

            <button
                class="btn-owner"
                data-bs-toggle="modal"
                data-bs-target="#propertyModal"
                onclick="prepareAddProperty()">

                ＋ Thêm nhà trọ

            </button>

        </div>


        <!-- ================= STATS ================= -->

        <div class="stats-grid">

            <div class="stat-card">

                <div class="stat-top">

                    <span>
                        Nhà trọ
                    </span>

                    <div class="stat-icon">
                        🏠
                    </div>

                </div>

                <strong id="totalProperties">
                    03
                </strong>

                <span>
                    Đang quản lý
                </span>

            </div>


            <div class="stat-card">

                <div class="stat-top">

                    <span>
                        Tổng phòng
                    </span>

                    <div class="stat-icon">
                        🚪
                    </div>

                </div>

                <strong id="totalRooms">
                    18
                </strong>

                <span>
                    Tất cả các nhà
                </span>

            </div>


            <div class="stat-card">

                <div class="stat-top">

                    <span>
                        Phòng trống
                    </span>

                    <div class="stat-icon">
                        🔑
                    </div>

                </div>

                <strong id="totalVacant">
                    06
                </strong>

                <span>
                    Có thể cho thuê
                </span>

            </div>


            <div class="stat-card">

                <div class="stat-top">

                    <span>
                        Đang thuê
                    </span>

                    <div class="stat-icon">
                        👥
                    </div>

                </div>

                <strong id="totalOccupied">
                    12
                </strong>

                <span>
                    Phòng đang có người
                </span>

            </div>

        </div>


        <!-- ================= PROPERTY LIST ================= -->

        <section class="content-card">

            <div class="card-header-owner">

                <div>

                    <h2>
                        Danh sách nhà trọ
                    </h2>

                    <p>
                        Quản lý các bất động sản thuộc tài khoản của bạn.
                    </p>

                </div>

                <span
                    class="badge rounded-pill text-bg-light"
                    id="propertyCount">

                    03 bất động sản

                </span>

            </div>


            <div
                class="property-grid"
                id="propertyGrid">


                <!-- NHÀ 1 -->

                <article class="property-card">

                    <div class="property-image">

                        <img
                            src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80"
                            alt="Nhà trọ Nguyễn Thị Thập">

                        <span class="property-status">
                            Đang hoạt động
                        </span>

                    </div>

                    <div class="property-body">

                        <h3>
                            Nhà trọ Nguyễn Thị Thập
                        </h3>

                        <div class="property-address">
                            📍 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh
                        </div>

                        <div class="property-meta">

                            <div>
                                <strong>08</strong>
                                <span>Phòng</span>
                            </div>

                            <div>
                                <strong>05</strong>
                                <span>Đang thuê</span>
                            </div>

                            <div>
                                <strong>03</strong>
                                <span>Còn trống</span>
                            </div>

                        </div>

                        <div class="property-actions">

                            <button
                                class="btn-light-owner"
                                type="button"
                                onclick="viewRooms()">
                                Xem phòng
                            </button>

                        </div>

                    </div>

                </article>


                <!-- NHÀ 2 -->

                <article class="property-card">

                    <div class="property-image">

                        <img
                            src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80"
                            alt="Căn hộ mini An Phú">

                        <span class="property-status">
                            Đang hoạt động
                        </span>

                    </div>

                    <div class="property-body">

                        <h3>
                            Căn hộ mini An Phú
                        </h3>

                        <div class="property-address">
                            📍 An Phú, TP. Thủ Đức, TP. Hồ Chí Minh
                        </div>

                        <div class="property-meta">

                            <div>
                                <strong>06</strong>
                                <span>Phòng</span>
                            </div>

                            <div>
                                <strong>04</strong>
                                <span>Đang thuê</span>
                            </div>

                            <div>
                                <strong>02</strong>
                                <span>Còn trống</span>
                            </div>

                        </div>

                        <div class="property-actions">

                            <button
                                class="btn-light-owner"
                                type="button"
                                onclick="viewRooms()">
                                Xem phòng
                            </button>

                        </div>

                    </div>

                </article>


                <!-- NHÀ 3 -->

                <article class="property-card">

                    <div class="property-image">

                        <img
                            src="https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=900&q=80"
                            alt="Nhà trọ Tân Phú">

                        <span class="property-status">
                            Đang hoạt động
                        </span>

                    </div>

                    <div class="property-body">

                        <h3>
                            Nhà trọ Tân Phú
                        </h3>

                        <div class="property-address">
                            📍 Tân Phú, TP. Hồ Chí Minh
                        </div>

                        <div class="property-meta">

                            <div>
                                <strong>04</strong>
                                <span>Phòng</span>
                            </div>

                            <div>
                                <strong>03</strong>
                                <span>Đang thuê</span>
                            </div>

                            <div>
                                <strong>01</strong>
                                <span>Còn trống</span>
                            </div>

                        </div>

                        <div class="property-actions">

                            <button
                                class="btn-light-owner"
                                type="button"
                                onclick="viewRooms()">
                                Xem phòng
                            </button>

                        </div>

                    </div>

                </article>


            </div>

        </section>

    </main>

</div>


<!-- ================= MODAL THÊM NHÀ ================= -->

<div
    class="modal fade"
    id="propertyModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5
                    class="modal-title fw-bold"
                    id="propertyModalTitle">

                    Thêm nhà trọ

                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body p-4">

                <form id="propertyForm">

                    <!-- TÊN -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tên nhà trọ
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="propertyName"
                            placeholder="VD: Nhà trọ Nguyễn Thị Thập"
                            required>

                    </div>


                    <!-- ĐỊA CHỈ -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Địa chỉ
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="propertyAddress"
                            placeholder="VD: Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh"
                            required>

                    </div>


                    <!-- SỐ PHÒNG -->

                    <div class="row g-3">

                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Tổng phòng
                                <span class="required">*</span>
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="propertyRooms"
                                min="1"
                                value="1"
                                required>

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Đang thuê
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="propertyOccupied"
                                min="0"
                                value="0">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label fw-semibold">
                                Còn trống
                            </label>

                            <input
                                type="number"
                                class="form-control"
                                id="propertyVacant"
                                min="0"
                                value="1">

                        </div>

                    </div>


                    <!-- TRẠNG THÁI -->

                    <div class="mb-3 mt-3">

                        <label class="form-label fw-semibold">
                            Trạng thái
                        </label>

                        <select
                            class="form-select"
                            id="propertyStatus">

                            <option value="active">
                                Đang hoạt động
                            </option>

                            <option value="inactive">
                                Tạm ngưng
                            </option>

                        </select>

                    </div>


                    <!-- ẢNH -->

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Link hình ảnh
                        </label>

                        <input
                            type="url"
                            class="form-control"
                            id="propertyImage"
                            placeholder="https://...">

                        <div class="form-text">
                            Có thể bỏ trống để sử dụng ảnh mặc định.
                        </div>

                    </div>


                    <!-- MÔ TẢ -->

                    <div class="mb-2">

                        <label class="form-label fw-semibold">
                            Mô tả
                        </label>

                        <textarea
                            class="form-control"
                            id="propertyDescription"
                            rows="3"
                            placeholder="Mô tả về nhà trọ..."></textarea>

                    </div>

                </form>

            </div>


            <div class="modal-footer border-0 px-4 pb-4">

                <button
                    type="button"
                    class="btn btn-light rounded-3"
                    data-bs-dismiss="modal">

                    Hủy

                </button>

                <button
                    type="button"
                    class="btn-owner"
                    onclick="saveProperty()">

                    Lưu nhà trọ

                </button>

            </div>

        </div>

    </div>

</div>


<!-- ================= TOAST ================= -->

<div
    class="toast-container position-fixed bottom-0 end-0 p-3">

    <div
        id="successToast"
        class="toast"
        role="alert">

        <div class="toast-header">

            <span class="me-2">✅</span>

            <strong class="me-auto">
                Trọ Ơi
            </strong>

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="toast">
            </button>

        </div>

        <div class="toast-body">
            Đã thêm nhà trọ thành công!
        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<script>

    /* =====================================================
       DỮ LIỆU MẶC ĐỊNH
    ===================================================== */

    const defaultProperties = [

        {
            id: 1,
            name: "Nhà trọ Nguyễn Thị Thập",
            address: "Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh",
            rooms: 8,
            occupied: 5,
            vacant: 3,
            status: "active",
            image: "https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80",
            description: ""
        },

        {
            id: 2,
            name: "Căn hộ mini An Phú",
            address: "An Phú, TP. Thủ Đức, TP. Hồ Chí Minh",
            rooms: 6,
            occupied: 4,
            vacant: 2,
            status: "active",
            image: "https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=900&q=80",
            description: ""
        },

        {
            id: 3,
            name: "Nhà trọ Tân Phú",
            address: "Tân Phú, TP. Hồ Chí Minh",
            rooms: 4,
            occupied: 3,
            vacant: 1,
            status: "active",
            image: "https://images.unsplash.com/photo-1600566753190-17f0baa2a6c3?auto=format&fit=crop&w=900&q=80",
            description: ""
        }

    ];


    let properties = [];


    /* =====================================================
       LOAD DỮ LIỆU
    ===================================================== */

    function loadProperties() {

        const saved =
            localStorage.getItem('troOiOwnerProperties');

        if (saved) {

            try {

                properties = JSON.parse(saved);

            } catch (error) {

                properties = [...defaultProperties];

            }

        } else {

            properties = [...defaultProperties];

            saveProperties();

        }

        renderProperties();

        updateStats();

    }


    /* =====================================================
       SAVE LOCAL STORAGE
    ===================================================== */

    function saveProperties() {

        localStorage.setItem(
            'troOiOwnerProperties',
            JSON.stringify(properties)
        );

    }


    /* =====================================================
       RENDER CARD
    ===================================================== */

    function renderProperties() {

        const grid =
            document.getElementById('propertyGrid');

        grid.innerHTML = '';


        if (properties.length === 0) {

            grid.innerHTML = `

                <div class="empty-property">

                    <div class="empty-property-icon">
                        🏠
                    </div>

                    <strong>
                        Chưa có nhà trọ nào
                    </strong>

                    <div class="mt-2">
                        Bấm "Thêm nhà trọ" để bắt đầu.
                    </div>

                </div>

            `;

            return;

        }


        properties.forEach(property => {

            const statusText =
                property.status === 'active'
                    ? 'Đang hoạt động'
                    : 'Tạm ngưng';


            const statusClass =
                property.status === 'active'
                    ? ''
                    : 'inactive';


            const image =
                property.image ||
                'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80';


            grid.innerHTML += `

                <article class="property-card">

                    <div class="property-image">

                        <img
                            src="${escapeHtml(image)}"
                            alt="${escapeHtml(property.name)}">

                        <span class="property-status ${statusClass}">
                            ${statusText}
                        </span>

                    </div>


                    <div class="property-body">

                        <h3>
                            ${escapeHtml(property.name)}
                        </h3>


                        <div class="property-address">
                            📍 ${escapeHtml(property.address)}
                        </div>


                        <div class="property-meta">

                            <div>
                                <strong>
                                    ${formatNumber(property.rooms)}
                                </strong>

                                <span>
                                    Phòng
                                </span>
                            </div>


                            <div>
                                <strong>
                                    ${formatNumber(property.occupied)}
                                </strong>

                                <span>
                                    Đang thuê
                                </span>
                            </div>


                            <div>
                                <strong>
                                    ${formatNumber(property.vacant)}
                                </strong>

                                <span>
                                    Còn trống
                                </span>
                            </div>

                        </div>


                        <div class="property-actions">

                            <button
                                class="btn-light-owner"
                                type="button"
                                onclick="viewRooms()">

                                Xem phòng

                            </button>

                            <button
                                class="btn-delete-owner"
                                type="button"
                                onclick="deleteProperty(${property.id})">

                                Xóa

                            </button>

                        </div>

                    </div>

                </article>

            `;

        });


        document.getElementById('propertyCount').textContent =
            `${formatNumber(properties.length)} bất động sản`;

    }


    /* =====================================================
       THÊM NHÀ TRỌ
    ===================================================== */

    function prepareAddProperty() {

        document.getElementById('propertyModalTitle').textContent =
            'Thêm nhà trọ';


        document.getElementById('propertyForm').reset();


        document.getElementById('propertyRooms').value = 1;
        document.getElementById('propertyOccupied').value = 0;
        document.getElementById('propertyVacant').value = 1;
        document.getElementById('propertyStatus').value = 'active';

    }


    function saveProperty() {

        const form =
            document.getElementById('propertyForm');


        if (!form.checkValidity()) {

            form.reportValidity();

            return;

        }


        const name =
            document.getElementById('propertyName')
                .value.trim();


        const address =
            document.getElementById('propertyAddress')
                .value.trim();


        const rooms =
            parseInt(
                document.getElementById('propertyRooms').value
            ) || 0;


        const occupied =
            parseInt(
                document.getElementById('propertyOccupied').value
            ) || 0;


        let vacant =
            parseInt(
                document.getElementById('propertyVacant').value
            );


        const status =
            document.getElementById('propertyStatus').value;


        const image =
            document.getElementById('propertyImage')
                .value.trim();


        const description =
            document.getElementById('propertyDescription')
                .value.trim();


        /* Kiểm tra số phòng */

        if (rooms <= 0) {

            alert('Tổng số phòng phải lớn hơn 0.');

            return;

        }


        if (occupied < 0) {

            alert('Số phòng đang thuê không hợp lệ.');

            return;

        }


        /*
         * Nếu số phòng trống bỏ trống
         * thì tự tính.
         */

        if (isNaN(vacant)) {

            vacant = rooms - occupied;

        }


        /* Kiểm tra dữ liệu */

        if (occupied > rooms) {

            alert(
                'Số phòng đang thuê không được lớn hơn tổng số phòng.'
            );

            return;

        }


        if (vacant < 0) {

            alert('Số phòng trống không hợp lệ.');

            return;

        }


        if (occupied + vacant > rooms) {

            alert(
                'Số phòng đang thuê + số phòng trống không được vượt quá tổng số phòng.'
            );

            return;

        }


        /*
         * Nếu người dùng nhập thiếu số phòng
         * thì tự điều chỉnh phòng trống.
         */

        if (occupied + vacant < rooms) {

            vacant = rooms - occupied;

        }


        const newProperty = {

            id: Date.now(),

            name: name,

            address: address,

            rooms: rooms,

            occupied: occupied,

            vacant: vacant,

            status: status,

            image: image,

            description: description

        };


        properties.push(newProperty);


        saveProperties();

        renderProperties();

        updateStats();


        /* Đóng modal */

        const modalElement =
            document.getElementById('propertyModal');

        const modal =
            bootstrap.Modal.getInstance(modalElement);

        if (modal) {

            modal.hide();

        }


        /* Hiện thông báo */

        showSuccessToast();

    }


    /* =====================================================
       XÓA NHÀ TRỌ
    ===================================================== */

    function deleteProperty(id) {

        const property =
            properties.find(item => item.id === id);


        if (!property) {
            return;
        }


        const confirmed =
            confirm(
                `Bạn có chắc muốn xóa "${property.name}" không?`
            );


        if (!confirmed) {
            return;
        }


        properties =
            properties.filter(
                item => item.id !== id
            );


        saveProperties();

        renderProperties();

        updateStats();

        showSuccessToast(
            'Đã xóa nhà trọ thành công!'
        );

    }


    /* =====================================================
       CẬP NHẬT THỐNG KÊ
    ===================================================== */

    function updateStats() {

        const totalProperties =
            properties.length;


        const totalRooms =
            properties.reduce(
                (sum, property) =>
                    sum + Number(property.rooms || 0),
                0
            );


        const totalOccupied =
            properties.reduce(
                (sum, property) =>
                    sum + Number(property.occupied || 0),
                0
            );


        const totalVacant =
            properties.reduce(
                (sum, property) =>
                    sum + Number(property.vacant || 0),
                0
            );


        document.getElementById('totalProperties')
            .textContent =
            formatNumber(totalProperties);


        document.getElementById('totalRooms')
            .textContent =
            formatNumber(totalRooms);


        document.getElementById('totalVacant')
            .textContent =
            formatNumber(totalVacant);


        document.getElementById('totalOccupied')
            .textContent =
            formatNumber(totalOccupied);


        document.getElementById('propertyCount')
            .textContent =
            `${formatNumber(totalProperties)} bất động sản`;

    }


    /* =====================================================
       XEM PHÒNG
    ===================================================== */

    function viewRooms() {

        window.location.href =
            "{{ route('owner.rooms.index') }}";

    }


    /* =====================================================
       FORMAT SỐ
    ===================================================== */

    function formatNumber(number) {

        return String(number).padStart(2, '0');

    }


    /* =====================================================
       ESCAPE HTML
    ===================================================== */

    function escapeHtml(value) {

        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    /* =====================================================
       TOAST
    ===================================================== */

    function showSuccessToast(
        message = 'Đã thêm nhà trọ thành công!'
    ) {

        document.querySelector(
            '#successToast .toast-body'
        ).textContent = message;


        const toastElement =
            document.getElementById('successToast');


        const toast =
            bootstrap.Toast.getOrCreateInstance(
                toastElement,
                {
                    delay: 2500
                }
            );


        toast.show();

    }


    /* =====================================================
       TỰ ĐỘNG TÍNH PHÒNG TRỐNG
    ===================================================== */

    document
        .getElementById('propertyRooms')
        .addEventListener('input', updateVacant);


    document
        .getElementById('propertyOccupied')
        .addEventListener('input', updateVacant);


    function updateVacant() {

        const rooms =
            parseInt(
                document.getElementById('propertyRooms').value
            ) || 0;


        const occupied =
            parseInt(
                document.getElementById('propertyOccupied').value
            ) || 0;


        if (occupied <= rooms) {

            document.getElementById('propertyVacant').value =
                Math.max(0, rooms - occupied);

        }

    }


    /* =====================================================
       KHỞI ĐỘNG
    ===================================================== */

    loadProperties();

</script>

</body>
</html>