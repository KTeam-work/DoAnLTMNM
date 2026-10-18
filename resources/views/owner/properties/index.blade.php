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

        .owner-main {
            max-width: 1500px;
            margin: auto;
            padding: 35px 30px;
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
            box-shadow: 0 8px 28px rgba(32, 88, 79, .05);
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
            box-shadow: 0 10px 32px rgba(32, 88, 79, .05);
            overflow: hidden;
        }

        .card-header-owner {
            padding: 22px 24px;
            border-bottom: 1px solid #eee7d8;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
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
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .property-card {
            border: 1px solid #e6dcc2;
            border-radius: 20px;
            overflow: hidden;
            background: #fff;
            transition: .25s;
            min-width: 0;
        }

        .property-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 14px 35px rgba(32, 88, 79, .10);
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
            overflow-wrap: anywhere;
        }

        .property-address {
            color: #78837e;
            font-size: 12px;
            min-height: 38px;
            overflow-wrap: anywhere;
        }

        .property-description {
            color: #78837e;
            font-size: 12px;
            margin-top: 10px;
            overflow-wrap: anywhere;
        }

        .property-meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            border-top: 1px solid #eee7d8;
            border-bottom: 1px solid #eee7d8;
            margin: 16px 0;
            padding: 12px 0;
            text-align: center;
        }

        .property-meta div {
            border-right: 1px solid #eee7d8;
            padding: 0 4px;
        }

        .property-meta div:last-child {
            border-right: none;
        }

        .property-meta strong {
            display: block;
            font-size: 14px;
            overflow-wrap: anywhere;
        }

        .property-meta span {
            color: #78837e;
            font-size: 10px;
        }

        .property-actions {
            display: flex;
            gap: 8px;
            align-items: stretch;
        }

        .property-actions a,
        .property-actions button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            flex: 1;
            border: none;
            border-radius: 11px;
            padding: 9px 7px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-light-owner {
            background: #eaf3ef;
            color: #20584f;
        }

        .btn-light-owner:hover {
            background: #dcebe4;
            color: #17463e;
        }

        .btn-delete-owner {
            background: #fff0ed;
            color: #c65b4a;
        }

        .btn-delete-owner:hover {
            background: #ffe0da;
            color: #a83c2d;
        }

        .property-actions form {
            display: flex;
            flex: 1;
            min-width: 0;
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

        .modal-content {
            border: none;
            border-radius: 24px;
            overflow: hidden;
        }

        .modal-header {
            background: #20584f;
            color: white;
            border: none;
            padding: 20px 24px;
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
            box-shadow: 0 0 0 .2rem rgba(32, 88, 79, .10);
        }

        .required {
            color: #c65b4a;
        }

        .form-label {
            font-size: 13px;
        }

        .alert {
            border-radius: 14px;
        }

        @media (max-width: 1100px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .property-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 650px) {
            .owner-main {
                padding: 24px 15px;
            }

            .stats-grid,
            .property-grid {
                grid-template-columns: 1fr;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .card-header-owner {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <div class="owner-page">

        <!-- NAVBAR -->
        <nav class="navbar navbar-expand-xl app-navbar">
            <div class="container-fluid">
                <a class="logo" href="{{ route('landlord.dashboard') }}">Trọ <span>Ơi</span></a>

                <button class="navbar-toggler shadow-none border-0"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mainMenu"
                    aria-controls="mainMenu"
                    aria-expanded="false"
                    aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
                </button>

                <div class="collapse navbar-collapse" id="mainMenu">
                    <ul class="navbar-nav mx-auto align-items-lg-center">

                        <li class="nav-item">
                            <a class="app-nav-link text-decoration-none" href="{{ route('landlord.dashboard') }}">
                                Tổng quan
                            </a>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="app-nav-link dropdown-toggle active text-decoration-none"
                                href="#"
                                id="navbarDrop1"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Nhà &amp; Phòng
                            </a>

                            <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
                                <li>
                                    <a class="dropdown-item" href="{{ route('owner.properties.index') }}">
                                        🏠 Quản lý nhà
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('owner.rooms.index') }}">
                                        🚪 Quản lý phòng
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="app-nav-link dropdown-toggle text-decoration-none"
                                href="#"
                                id="navbarDrop2"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Khách &amp; Hợp đồng
                            </a>

                            <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop2">
                                <li><a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="app-nav-link dropdown-toggle text-decoration-none"
                                href="#"
                                id="navbarDrop3"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Tài chính
                            </a>

                            <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop3">
                                <li><a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.utilities.index') }}">⚡ Điện nước</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a></li>
                                <li><a class="dropdown-item" href="{{ route('owner.payments.index') }}">💰 Giao dịch</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="app-nav-link dropdown-toggle text-decoration-none"
                                href="#"
                                id="navbarDrop4"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Vận hành
                            </a>

                            <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop4">
                                <li>
                                    <a class="dropdown-item"
                                        href="{{ route('owner.rental-posts.index') }}">
                                        📢 Tin đăng
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('owner.appointments.index') }}">
                                        📅 Lịch xem
                                    </a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('owner.maintenance.index') }}">🛠️ Sửa chữa</a></li>
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

            <!-- TIÊU ĐỀ -->
            <div class="page-heading">
                <div>
                    <div class="eyebrow">Owner workspace</div>
                    <h1>Quản lý nhà trọ</h1>
                    <p>Theo dõi và quản lý toàn bộ bất động sản của bạn.</p>
                </div>

                <button
                    class="btn-owner"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#propertyModal">
                    ＋ Thêm nhà trọ
                </button>
            </div>

            <!-- THÔNG BÁO -->
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

            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Dữ liệu chưa hợp lệ:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- THỐNG KÊ -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Nhà trọ</span>
                        <div class="stat-icon">🏠</div>
                    </div>
                    <strong>{{ $properties->count() }}</strong>
                    <span>Nhà trọ đang quản lý</span>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Tổng phòng</span>
                        <div class="stat-icon">🚪</div>
                    </div>
                    <strong>{{ $properties->sum('rooms_count') }}</strong>
                    <span>Tổng số phòng thuộc các nhà</span>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Phòng trống</span>
                        <div class="stat-icon">🔑</div>
                    </div>
                    <strong>—</strong>
                    <span>Cần đối chiếu trạng thái bảng rooms</span>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Đang thuê</span>
                        <div class="stat-icon">👥</div>
                    </div>
                    <strong>—</strong>
                    <span>Cần đối chiếu trạng thái bảng rooms</span>
                </div>

            </div>

            <!-- DANH SÁCH NHÀ TRỌ -->
            <section class="content-card">

                <div class="card-header-owner">
                    <div>
                        <h2>Danh sách nhà trọ</h2>
                        <p>Quản lý các bất động sản thuộc tài khoản của bạn.</p>
                    </div>

                    <span class="badge rounded-pill text-bg-light">
                        {{ $properties->count() }} bất động sản
                    </span>
                </div>

                <div class="property-grid">

                    @forelse ($properties as $property)
                    <article class="property-card">

                        <div class="property-image">
                            <img
                                src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80"
                                alt="{{ $property->name }}">

                            <span class="property-status {{ $property->status === 'inactive' ? 'inactive' : '' }}">
                                {{ $property->status === 'active' ? 'Đang hoạt động' : 'Tạm ngưng' }}
                            </span>
                        </div>

                        <div class="property-body">

                            <h3>{{ $property->name }}</h3>

                            <div class="property-address">
                                📍 {{ $property->address }},
                                {{ $property->ward }},
                                {{ $property->district }}
                            </div>

                            <div class="property-meta">
                                <div>
                                    <strong>{{ $property->rooms_count }}</strong>
                                    <span>Số phòng</span>
                                </div>

                                <div>
                                    <strong>
                                        @if ($property->type === 'rooming_house')
                                        Nhà trọ
                                        @elseif ($property->type === 'apartment')
                                        Căn hộ
                                        @else
                                        Nhà
                                        @endif
                                    </strong>
                                    <span>Loại hình</span>
                                </div>

                                <div>
                                    <strong>{{ $property->status === 'active' ? 'Có' : 'Không' }}</strong>
                                    <span>Hoạt động</span>
                                </div>
                            </div>

                            @if ($property->description)
                            <div class="property-description">
                                {{ $property->description }}
                            </div>
                            @endif

                            <div class="property-actions mt-3">

                                <a class="btn-light-owner"
                                    href="{{ route('owner.properties.show', $property->id) }}">
                                    Chi tiết
                                </a>

                                <a
                                    class="btn-light-owner"
                                    href="{{ route('owner.properties.edit', $property) }}">
                                    Sửa
                                </a>

                                <form
                                    action="{{ route('owner.properties.destroy', $property) }}"
                                    method="POST"
                                    onsubmit="return confirm('Em có chắc muốn xóa nhà trọ này không?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-delete-owner">
                                        Xóa
                                    </button>
                                </form>

                            </div>
                        </div>
                    </article>

                    @empty
                    <div class="empty-property">
                        <div class="empty-property-icon">🏠</div>
                        <strong>Chưa có nhà trọ nào</strong>
                        <div class="mt-2">
                            Bấm “Thêm nhà trọ” để bắt đầu quản lý bất động sản của bạn.
                        </div>
                    </div>
                    @endforelse

                </div>
            </section>

        </main>
    </div>

    <!-- MODAL THÊM NHÀ TRỌ -->
    <div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="propertyModalTitle">
                        Thêm nhà trọ
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Đóng">
                    </button>
                </div>

                <form
                    id="propertyForm"
                    action="{{ route('owner.properties.store') }}"
                    method="POST">
                    @csrf

                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="propertyName" class="form-label fw-semibold">
                                Tên nhà trọ <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="propertyName"
                                name="name"
                                value="{{ old('name') }}"
                                maxlength="150"
                                placeholder="VD: Nhà trọ Nguyễn Thị Thập"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="propertyType" class="form-label fw-semibold">
                                Loại hình <span class="required">*</span>
                            </label>

                            <select class="form-select" id="propertyType" name="type" required>
                                <option value="rooming_house" {{ old('type', 'rooming_house') === 'rooming_house' ? 'selected' : '' }}>
                                    Nhà trọ
                                </option>
                                <option value="apartment" {{ old('type') === 'apartment' ? 'selected' : '' }}>
                                    Căn hộ
                                </option>
                                <option value="house" {{ old('type') === 'house' ? 'selected' : '' }}>
                                    Nhà nguyên căn
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="propertyAddress" class="form-label fw-semibold">
                                Địa chỉ chi tiết <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="propertyAddress"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Số nhà, tên đường"
                                required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="propertyDistrict" class="form-label fw-semibold">
                                    Quận/huyện <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="propertyDistrict"
                                    name="district"
                                    value="{{ old('district') }}"
                                    maxlength="100"
                                    placeholder="VD: Quận 7"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label for="propertyWard" class="form-label fw-semibold">
                                    Phường/xã <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="propertyWard"
                                    name="ward"
                                    value="{{ old('ward') }}"
                                    maxlength="100"
                                    placeholder="VD: Tân Phong"
                                    required>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="propertyLatitude" class="form-label fw-semibold">
                                    Vĩ độ (không bắt buộc)
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    class="form-control"
                                    id="propertyLatitude"
                                    name="latitude"
                                    value="{{ old('latitude') }}"
                                    min="-90"
                                    max="90"
                                    placeholder="VD: 10.729">
                            </div>

                            <div class="col-md-6">
                                <label for="propertyLongitude" class="form-label fw-semibold">
                                    Kinh độ (không bắt buộc)
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    class="form-control"
                                    id="propertyLongitude"
                                    name="longitude"
                                    value="{{ old('longitude') }}"
                                    min="-180"
                                    max="180"
                                    placeholder="VD: 106.721">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="propertyStatus" class="form-label fw-semibold">
                                Trạng thái <span class="required">*</span>
                            </label>

                            <select class="form-select" id="propertyStatus" name="status" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    Đang hoạt động
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Tạm ngưng
                                </option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="propertyDescription" class="form-label fw-semibold">
                                Mô tả
                            </label>

                            <textarea
                                class="form-control"
                                id="propertyDescription"
                                name="description"
                                rows="3"
                                placeholder="Mô tả về nhà trọ...">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-text">
                            Số lượng phòng sẽ được quản lý riêng trong mục “Quản lý phòng”.
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4">
                        <button
                            type="button"
                            class="btn btn-light rounded-3"
                            data-bs-dismiss="modal">
                            Hủy
                        </button>

                        <button type="submit" class="btn-owner">
                            Lưu nhà trọ
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>




    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalElement = document.getElementById('propertyModal');

            if (modalElement) {
                const hasErrors = modalElement.querySelector('.is-invalid');

                if (hasErrors && typeof bootstrap !== 'undefined') {
                    bootstrap.Modal.getOrCreateInstance(modalElement).show();
                }
            }
        });
    </script>
</body>

</html>