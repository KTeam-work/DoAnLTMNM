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

        .owner-main {
            padding: 35px 30px 60px;
            max-width: 1400px;
            margin: 0 auto;
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
            cursor: pointer;
            transition: .2s;
        }

        .btn-owner:hover {
            background: #17463e;
            color: white;
        }

        /* STATS */
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

        /* ROOM CONTAINER */
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

        /* ROOM GRID */
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

        /* MODAL CHUNG */
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

        /* EDIT & ADD MODAL FORM */
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
        .btn-save,
        .btn-add-save,
        .btn-back {
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

        .btn-save, .btn-add-save {
            background: #20584f;
            color: white;
        }

        .btn-save:hover, .btn-add-save:hover {
            background: #17463e;
        }

        .btn-back {
            border: 1px solid #e6dcc2;
            background: #fff;
            color: #53635e;
        }

        .btn-back:hover {
            background: #f8f5eb;
            color: #20584f;
        }

        /* DETAIL MODAL */
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

        .detail-status {
            display: inline-flex;
            padding: 7px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800;
        }

        .detail-status.available { background: #eaf3ef; color: #20584f; }
        .detail-status.rented { background: #fff7d7; color: #8b6810; }
        .detail-status.maintenance { background: #fae8e5; color: #a33f35; }

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

        /* TOAST */
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

        .required { color: #c65b4a; }

        /* RESPONSIVE */
        @media (max-width: 1100px) {
            .room-grid { grid-template-columns: repeat(2, 1fr); }
            .stats { grid-template-columns: repeat(2, 1fr); }
        }

        @media (max-width: 700px) {
            .owner-main { padding: 25px 15px 45px; }
            .heading { align-items: flex-start; flex-direction: column; }
            .toolbar { flex-direction: column; }
            .filter-select { width: 100%; }
            .room-grid { grid-template-columns: 1fr; }
            .form-grid, .detail-grid { grid-template-columns: 1fr; }
            .form-group.full, .detail-item.full { grid-column: auto; }
            .amenity-checks { grid-template-columns: 1fr 1fr; }
            .detail-image-wrap { height: 210px; }
        }

        @media (max-width: 450px) {
            .stats { grid-template-columns: 1fr; }
            .room-container { padding: 14px; }
            .amenity-checks { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item">
          <a class="app-nav-link " href="{{  route('landlord.home')  }}">Tổng quan</a>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle active" href="#" id="navbarDrop1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Nhà &amp; Phòng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
            <li><a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Khách &amp; Hợp đồng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop2">
            <li><a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a></li>
            <li><a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a></li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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

<!-- MAIN CONTENT -->
<main class="owner-main">

    <div class="heading">
        <div>
            <h1>Danh sách phòng</h1>
            <p>Quản lý trạng thái và thông tin các phòng đang cho thuê.</p>
        </div>
        <button class="btn-owner" onclick="openAddModal()">＋ Thêm phòng</button>
    </div>

    <!-- STATS CARDS -->
    <div class="stats">
        <div class="stat">
            <div class="stat-icon">🏠</div>
            <div>
                <div class="stat-label">Tổng số phòng</div>
                <div class="stat-number" id="totalRooms">00</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon">✓</div>
            <div>
                <div class="stat-label">Còn trống</div>
                <div class="stat-number" id="availableRooms">00</div>
            </div>
        </div>
        <div class="stat">
            <div class="stat-icon">👤</div>
            <div>
                <div class="stat-label">Đang thuê</div>
                <div class="stat-number" id="rentedRooms">00</div>
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

    <!-- ROOM CONTAINER -->
    <section class="room-container">

        <!-- TOOLBAR -->
        <div class="toolbar">
            <div class="search-box">
                <span>⌕</span>
                <input type="text" id="roomSearch" placeholder="Tìm theo tên phòng, mã phòng, khu vực...">
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

        <!-- ROOM GRID -->
        <div class="room-grid" id="roomGrid">

            <!-- ROOM 101 -->
            <article class="room-card" data-room-id="101" data-property="Nguyễn Thị Thập" data-status="rented" data-search="phòng 101 p.101 nguyễn thị thập">
                <div class="room-img">
                    <img src="https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=900&q=80" alt="Phòng 101">
                    <span class="room-code">P.101</span>
                    <span class="room-status rented">ĐANG THUÊ</span>
                </div>
                <div class="room-body">
                    <h3>Phòng 101</h3>
                    <div class="room-property">Nhà trọ Nguyễn Thị Thập</div>
                    <div class="room-price">2.800.000đ / tháng</div>
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
                        <button class="btn-edit" onclick="openEditModal(this)">Chỉnh sửa</button>
                        <button class="btn-more" onclick="openDetailModal(this)">Chi tiết</button>
                    </div>
                </div>
            </article>

            <!-- ROOM 102 -->
            <article class="room-card" data-room-id="102" data-property="Nguyễn Thị Thập" data-status="available" data-search="phòng 102 p.102 nguyễn thị thập">
                <div class="room-img">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80" alt="Phòng 102">
                    <span class="room-code">P.102</span>
                    <span class="room-status available">CÒN TRỐNG</span>
                </div>
                <div class="room-body">
                    <h3>Phòng 102</h3>
                    <div class="room-property">Nhà trọ Nguyễn Thị Thập</div>
                    <div class="room-price">3.200.000đ / tháng</div>
                    <div class="room-info">
                        <span>25 m²</span>
                        <span>3 người</span>
                        <span>1 WC</span>
                    </div>
                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">📶 Wifi</span>
                        <span class="amenity">🌅 Ban công</span>
                    </div>
                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">Chỉnh sửa</button>
                        <button class="btn-more" onclick="openDetailModal(this)">Chi tiết</button>
                    </div>
                </div>
            </article>

            <!-- ROOM 201 -->
            <article class="room-card" data-room-id="201" data-property="An Phú" data-status="maintenance" data-search="phòng 201 p.201 an phú">
                <div class="room-img">
                    <img src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=900&q=80" alt="Phòng 201">
                    <span class="room-code">P.201</span>
                    <span class="room-status maintenance">BẢO TRÌ</span>
                </div>
                <div class="room-body">
                    <h3>Phòng 201</h3>
                    <div class="room-property">Căn hộ An Phú</div>
                    <div class="room-price">4.500.000đ / tháng</div>
                    <div class="room-info">
                        <span>35 m²</span>
                        <span>4 người</span>
                        <span>1 WC</span>
                    </div>
                    <div class="amenities">
                        <span class="amenity">❄ Máy lạnh</span>
                        <span class="amenity">🧺 Máy giặt</span>
                        <span class="amenity">🅿️ Chỗ để xe</span>
                    </div>
                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">Chỉnh sửa</button>
                        <button class="btn-more" onclick="openDetailModal(this)">Chi tiết</button>
                    </div>
                </div>
            </article>

        </div>
    </section>

</main>

<!-- =========================
     MODAL CHI TIẾT PHÒNG
========================= -->
<div class="custom-modal" id="detailModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-area">
                <div class="modal-title-icon">🚪</div>
                <div>
                    <h2 class="modal-title" id="detailTitle">Thông tin phòng</h2>
                    <p class="modal-subtitle" id="detailSubtitle">Mã phòng: ---</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
        </div>
        <div class="modal-body">
            <div class="detail-image-wrap">
                <img id="detailImg" src="" class="detail-image" alt="Ảnh phòng">
            </div>
            <div class="detail-grid">
                <div>
                    <div class="detail-label">Thuộc nhà trọ</div>
                    <div class="detail-value" id="detailProperty">---</div>
                </div>
                <div>
                    <div class="detail-label">Trạng thái</div>
                    <div><span class="detail-status" id="detailStatus">---</span></div>
                </div>
                <div>
                    <div class="detail-label">Giá thuê theo tháng</div>
                    <div class="detail-value price" id="detailPrice">---</div>
                </div>
                <div>
                    <div class="detail-label">Diện tích & Sức chứa</div>
                    <div class="detail-value" id="detailSpecs">---</div>
                </div>
                <div class="detail-item full">
                    <div class="detail-label">Tiện ích đi kèm</div>
                    <div class="detail-amenities" id="detailAmenities"></div>
                </div>
                <div class="detail-item full">
                    <div class="detail-label">Mô tả thêm</div>
                    <div class="detail-value" id="detailDescription" style="font-weight: 400; line-height: 1.5;">Chưa có thông tin mô tả chi tiết cho phòng này.</div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-back" onclick="closeModal('detailModal')">Đóng</button>
        </div>
    </div>
</div>

<!-- =========================
     MODAL CHỈNH SỬA PHÒNG
========================= -->
<div class="custom-modal" id="editModal">
    <div class="modal-box">
        <div class="modal-header">
            <div class="modal-title-area">
                <div class="modal-title-icon">✏️</div>
                <div>
                    <h2 class="modal-title">Chỉnh sửa thông tin phòng</h2>
                    <p class="modal-subtitle">Cập nhật thông tin chi tiết phòng trọ</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
        </div>
        <form id="editRoomForm" onsubmit="saveEditRoom(event)">
            <div class="modal-body">
                <input type="hidden" id="editCardId">
                <div class="form-grid">
                    <div>
                        <label class="form-label">Tên phòng <span class="required">*</span></label>
                        <input type="text" class="edit-input" id="editRoomName" required>
                    </div>
                    <div>
                        <label class="form-label">Mã phòng <span class="required">*</span></label>
                        <input type="text" class="edit-input" id="editRoomCode" required>
                    </div>
                    <div>
                        <label class="form-label">Thuộc nhà trọ</label>
                        <select class="edit-select" id="editProperty">
                            <option value="Nguyễn Thị Thập">Nguyễn Thị Thập</option>
                            <option value="An Phú">An Phú</option>
                            <option value="Tân Phú">Tân Phú</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Trạng thái</label>
                        <select class="edit-select" id="editStatus">
                            <option value="available">Còn trống</option>
                            <option value="rented">Đang thuê</option>
                            <option value="maintenance">Bảo trì</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Giá thuê (VNĐ/tháng) <span class="required">*</span></label>
                        <input type="number" class="edit-input" id="editPrice" required>
                    </div>
                    <div>
                        <label class="form-label">Diện tích (m²)</label>
                        <input type="number" class="edit-input" id="editArea" value="20">
                    </div>
                    <div>
                        <label class="form-label">Sức chứa (người)</label>
                        <input type="number" class="edit-input" id="editCapacity" value="2">
                    </div>
                    <div>
                        <label class="form-label">Số WC</label>
                        <input type="number" class="edit-input" id="editWc" value="1">
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Tiện ích</label>
                        <div class="amenity-checks">
                            <label class="amenity-check"><input type="checkbox" name="editAmenities" value="❄ Máy lạnh"> ❄ Máy lạnh</label>
                            <label class="amenity-check"><input type="checkbox" name="editAmenities" value="📶 Wifi"> 📶 Wifi</label>
                            <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🛏 Nội thất"> 🛏 Nội thất</label>
                            <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🌅 Ban công"> 🌅 Ban công</label>
                            <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🧺 Máy giặt"> 🧺 Máy giặt</label>
                            <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🅿️ Chỗ để xe"> 🅿️ Chỗ để xe</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Hủy bỏ</button>
                <button type="submit" class="btn-save">Lưu thay đổi</button>
            </div>
        </form>
    </div>
</div>

<!-- =========================
     MODAL THÊM PHÒNG MỚI
========================= -->
<div class="custom-modal" id="addModal">
    <div class="modal-box add-modal-box">
        <div class="modal-header">
            <div class="modal-title-area">
                <div class="modal-title-icon">➕</div>
                <div>
                    <h2 class="modal-title">Thêm phòng mới</h2>
                    <p class="modal-subtitle">Tạo phòng trọ mới vào danh sách quản lý</p>
                </div>
            </div>
            <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
        </div>
        <form id="addRoomForm" onsubmit="saveAddRoom(event)">
            <div class="modal-body">
                <div class="form-grid">
                    <div>
                        <label class="form-label">Tên phòng <span class="required">*</span></label>
                        <input type="text" class="edit-input" id="addRoomName" placeholder="VD: Phòng 103" required>
                    </div>
                    <div>
                        <label class="form-label">Mã phòng <span class="required">*</span></label>
                        <input type="text" class="edit-input" id="addRoomCode" placeholder="VD: P.103" required>
                    </div>
                    <div>
                        <label class="form-label">Thuộc nhà trọ</label>
                        <select class="edit-select" id="addProperty">
                            <option value="Nguyễn Thị Thập">Nguyễn Thị Thập</option>
                            <option value="An Phú">An Phú</option>
                            <option value="Tân Phú">Tân Phú</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Trạng thái ban đầu</label>
                        <select class="edit-select" id="addStatus">
                            <option value="available">Còn trống</option>
                            <option value="rented">Đang thuê</option>
                            <option value="maintenance">Bảo trì</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Giá thuê (VNĐ/tháng) <span class="required">*</span></label>
                        <input type="number" class="edit-input" id="addPrice" placeholder="VD: 3000000" required>
                    </div>
                    <div>
                        <label class="form-label">Diện tích (m²)</label>
                        <input type="number" class="edit-input" id="addArea" value="20">
                    </div>
                    <div>
                        <label class="form-label">Sức chứa (người)</label>
                        <input type="number" class="edit-input" id="addCapacity" value="2">
                    </div>
                    <div>
                        <label class="form-label">Số WC</label>
                        <input type="number" class="edit-input" id="addWc" value="1">
                    </div>
                    <div class="form-group full">
                        <label class="form-label">Tiện ích chọn sẵn</label>
                        <div class="amenity-checks">
                            <label class="amenity-check"><input type="checkbox" name="addAmenities" value="❄ Máy lạnh" checked> ❄ Máy lạnh</label>
                            <label class="amenity-check"><input type="checkbox" name="addAmenities" value="📶 Wifi" checked> 📶 Wifi</label>
                            <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🛏 Nội thất"> 🛏 Nội thất</label>
                            <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🌅 Ban công"> 🌅 Ban công</label>
                            <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🧺 Máy giặt"> 🧺 Máy giặt</label>
                            <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🅿️ Chỗ để xe"> 🅿️ Chỗ để xe</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Hủy bỏ</button>
                <button type="submit" class="btn-add-save">Tạo phòng</button>
            </div>
        </form>
    </div>
</div>

<!-- TOAST MESSAGE -->
<div class="toast-message" id="toastMessage">Thông báo từ hệ thống</div>

<!-- BOOTSTRAP 5 JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<!-- APPLICATION JAVASCRIPT LOGIC -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        updateStatistics();
        initFilters();
    });

    // 1. CẬP NHẬT THỐNG KÊ (STATS)
    function updateStatistics() {
        const cards = document.querySelectorAll('.room-card');
        let total = cards.length;
        let available = 0;
        let rented = 0;
        let maintenance = 0;

        cards.forEach(card => {
            const status = card.dataset.status;
            if (status === 'available') available++;
            else if (status === 'rented') rented++;
            else if (status === 'maintenance') maintenance++;
        });

        document.getElementById('totalRooms').innerText = total < 10 ? '0' + total : total;
        document.getElementById('availableRooms').innerText = available < 10 ? '0' + available : available;
        document.getElementById('rentedRooms').innerText = rented < 10 ? '0' + rented : rented;
        document.getElementById('maintenanceRooms').innerText = maintenance < 10 ? '0' + maintenance : maintenance;
    }

    // 2. LOGIC TÌM KIẾM VÀ LỌC
    function initFilters() {
        const searchInput = document.getElementById('roomSearch');
        const propertySelect = document.getElementById('propertyFilter');
        const statusSelect = document.getElementById('statusFilter');

        function filterRooms() {
            const searchVal = searchInput.value.toLowerCase().trim();
            const propertyVal = propertySelect.value;
            const statusVal = statusSelect.value;
            const cards = document.querySelectorAll('.room-card');
            let visibleCount = 0;

            cards.forEach(card => {
                const searchData = (card.dataset.search || '').toLowerCase();
                const propertyData = card.dataset.property;
                const statusData = card.dataset.status;

                const matchSearch = !searchVal || searchData.includes(searchVal);
                const matchProperty = !propertyVal || propertyData === propertyVal;
                const matchStatus = !statusVal || statusData === statusVal;

                if (matchSearch && matchProperty && matchStatus) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Xử lý thông báo khi tìm kiếm không ra kết quả
            let emptyMsg = document.getElementById('emptyRoomMsg');
            if (visibleCount === 0) {
                if (!emptyMsg) {
                    emptyMsg = document.createElement('div');
                    emptyMsg.id = 'emptyRoomMsg';
                    emptyMsg.className = 'empty-room-message';
                    emptyMsg.innerHTML = '<div class="empty-room-icon">🔍</div>Không tìm thấy phòng nào phù hợp với bộ lọc.';
                    document.getElementById('roomGrid').appendChild(emptyMsg);
                }
            } else if (emptyMsg) {
                emptyMsg.remove();
            }
        }

        searchInput.addEventListener('input', filterRooms);
        propertySelect.addEventListener('change', filterRooms);
        statusSelect.addEventListener('change', filterRooms);
    }

    // 3. XỬ LÝ TOAST
    function showToast(text) {
        const toast = document.getElementById('toastMessage');
        toast.innerText = text;
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 3000);
    }

    // 4. BẬT/TẮT MODAL
    function openModal(id) {
        document.getElementById(id).classList.add('show');
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('show');
    }

    // 5. MỞ MODAL CHI TIẾT PHÒNG
    function openDetailModal(btn) {
        const card = btn.closest('.room-card');
        const code = card.querySelector('.room-code').innerText;
        const name = card.querySelector('h3').innerText;
        const prop = card.querySelector('.room-property').innerText;
        const price = card.querySelector('.room-price').innerText;
        const img = card.querySelector('.room-img img').src;
        const statusText = card.querySelector('.room-status').innerText;
        const statusClass = card.dataset.status;

        // Điền dữ liệu
        document.getElementById('detailTitle').innerText = name;
        document.getElementById('detailSubtitle').innerText = "Mã phòng: " + code;
        document.getElementById('detailProperty').innerText = prop;
        document.getElementById('detailPrice').innerText = price;
        document.getElementById('detailImg').src = img;

        const infoSpans = card.querySelectorAll('.room-info span');
        let specs = [];
        infoSpans.forEach(s => specs.push(s.innerText));
        document.getElementById('detailSpecs').innerText = specs.join(' • ');

        const statusEl = document.getElementById('detailStatus');
        statusEl.innerText = statusText;
        statusEl.className = 'detail-status ' + statusClass;

        const amenities = card.querySelectorAll('.amenity');
        const amenitiesContainer = document.getElementById('detailAmenities');
        amenitiesContainer.innerHTML = '';
        amenities.forEach(a => {
            const span = document.createElement('span');
            span.className = 'detail-amenity';
            span.innerText = a.innerText;
            amenitiesContainer.appendChild(span);
        });

        openModal('detailModal');
    }

    // 6. MỞ & LƯU EDIT MODAL
    function openEditModal(btn) {
        const card = btn.closest('.room-card');
        document.getElementById('editCardId').value = card.dataset.roomId;

        const name = card.querySelector('h3').innerText;
        const code = card.querySelector('.room-code').innerText;
        const priceText = card.querySelector('.room-price').innerText.replace(/\D/g, '');

        document.getElementById('editRoomName').value = name;
        document.getElementById('editRoomCode').value = code;
        document.getElementById('editProperty').value = card.dataset.property;
        document.getElementById('editStatus').value = card.dataset.status;
        document.getElementById('editPrice').value = priceText;

        // Checkbox tiện ích
        const currentAmenities = Array.from(card.querySelectorAll('.amenity')).map(a => a.innerText.trim());
        const checkboxes = document.querySelectorAll('input[name="editAmenities"]');
        checkboxes.forEach(cb => {
            cb.checked = currentAmenities.includes(cb.value);
        });

        openModal('editModal');
    }

    function saveEditRoom(e) {
        e.preventDefault();
        const roomId = document.getElementById('editCardId').value;
        const card = document.querySelector(`.room-card[data-room-id="${roomId}"]`);

        if (card) {
            const name = document.getElementById('editRoomName').value;
            const code = document.getElementById('editRoomCode').value;
            const property = document.getElementById('editProperty').value;
            const status = document.getElementById('editStatus').value;
            const price = parseInt(document.getElementById('editPrice').value).toLocaleString('vi-VN') + 'đ / tháng';

            // Cập nhật DOM
            card.dataset.property = property;
            card.dataset.status = status;
            card.dataset.search = `${name} ${code} ${property}`.toLowerCase();

            card.querySelector('h3').innerText = name;
            card.querySelector('.room-code').innerText = code;
            card.querySelector('.room-property').innerText = "Nhà trọ " + property;
            card.querySelector('.room-price').innerText = price;

            const statusTag = card.querySelector('.room-status');
            statusTag.className = 'room-status ' + status;
            statusTag.innerText = status === 'available' ? 'CÒN TRỐNG' : status === 'rented' ? 'ĐANG THUÊ' : 'BẢO TRÌ';

            // Tiện ích
            const amenitiesContainer = card.querySelector('.amenities');
            amenitiesContainer.innerHTML = '';
            const checkedBoxes = document.querySelectorAll('input[name="editAmenities"]:checked');
            checkedBoxes.forEach(cb => {
                const span = document.createElement('span');
                span.className = 'amenity';
                span.innerText = cb.value;
                amenitiesContainer.appendChild(span);
            });

            updateStatistics();
            closeModal('editModal');
            showToast('Đã lưu thông tin phòng thành công!');
        }
    }

    // 7. MỞ & LƯU ADD MODAL
    function openAddModal() {
        document.getElementById('addRoomForm').reset();
        openModal('addModal');
    }

    function saveAddRoom(e) {
        e.preventDefault();
        const name = document.getElementById('addRoomName').value;
        const code = document.getElementById('addRoomCode').value;
        const property = document.getElementById('addProperty').value;
        const status = document.getElementById('addStatus').value;
        const priceNum = parseInt(document.getElementById('addPrice').value) || 0;
        const price = priceNum.toLocaleString('vi-VN') + 'đ / tháng';
        const area = document.getElementById('addArea').value || '20';
        const capacity = document.getElementById('addCapacity').value || '2';
        const wc = document.getElementById('addWc').value || '1';

        const roomId = Date.now().toString(); // Tạo ID tạm thời
        const statusText = status === 'available' ? 'CÒN TRỐNG' : status === 'rented' ? 'ĐANG THUÊ' : 'BẢO TRÌ';

        // Lấy danh sách tiện ích được chọn
        const checkedBoxes = document.querySelectorAll('input[name="addAmenities"]:checked');
        let amenitiesHTML = '';
        checkedBoxes.forEach(cb => {
            amenitiesHTML += `<span class="amenity">${cb.value}</span>`;
        });

        const newCardHTML = `
            <article class="room-card" data-room-id="${roomId}" data-property="${property}" data-status="${status}" data-search="${name.toLowerCase()} ${code.toLowerCase()} ${property.toLowerCase()}">
                <div class="room-img">
                    <img src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80" alt="${name}">
                    <span class="room-code">${code}</span>
                    <span class="room-status ${status}">${statusText}</span>
                </div>
                <div class="room-body">
                    <h3>${name}</h3>
                    <div class="room-property">Nhà trọ ${property}</div>
                    <div class="room-price">${price}</div>
                    <div class="room-info">
                        <span>${area} m²</span>
                        <span>${capacity} người</span>
                        <span>${wc} WC</span>
                    </div>
                    <div class="amenities">
                        ${amenitiesHTML}
                    </div>
                    <div class="room-actions">
                        <button class="btn-edit" onclick="openEditModal(this)">Chỉnh sửa</button>
                        <button class="btn-more" onclick="openDetailModal(this)">Chi tiết</button>
                    </div>
                </div>
            </article>
        `;

        document.getElementById('roomGrid').insertAdjacentHTML('afterbegin', newCardHTML);
        updateStatistics();
        closeModal('addModal');
        showToast('Đã thêm phòng mới thành công!');
    }
</script>
</body>
</html>