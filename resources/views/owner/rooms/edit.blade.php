
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa phòng | Trọ Ơi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        :root {
            --primary: #005d4b;
            --primary-dark: #004537;
            --light-green: #e6f0eb;
            --yellow: #ffb800;
            --background: #f9f9f6;
            --text: #1f2937;
            --muted: #6b7280;
            --border: #e6dcc2;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Be Vietnam Pro', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .page-wrap {
            max-width: 1360px;
            margin: 0 auto;
            padding: 30px 28px 70px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 26px;
        }

        .page-title {
            margin: 0;
            color: var(--primary);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.6px;
        }

        .page-desc {
            margin-top: 6px;
            color: var(--muted);
            font-size: 13px;
        }

        .btn-brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            padding: 11px 18px;
            border: 0;
            border-radius: 11px;
            background: var(--yellow);
            color: var(--primary);
            font-size: 13px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-brand:hover {
            background: #ffd968;
            color: var(--primary);
            transform: translateY(-1px);
        }

        .btn-secondary-custom {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 18px;
            border: 1px solid var(--border);
            border-radius: 11px;
            background: #fff;
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            transition: .2s ease;
        }

        .btn-secondary-custom:hover {
            background: #f5f0e5;
            color: var(--primary);
        }

        .edit-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 22px;
            align-items: start;
        }

        .panel {
            min-width: 0;
            padding: 26px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .025);
        }

        .panel-heading {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 23px;
            padding-bottom: 18px;
            border-bottom: 1px solid #f0eadc;
        }

        .heading-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            width: 44px;
            height: 44px;
            border-radius: 13px;
            background: var(--light-green);
            font-size: 21px;
        }

        .panel-title {
            margin: 0;
            color: var(--primary);
            font-size: 16px;
            font-weight: 800;
        }

        .panel-subtitle {
            margin-top: 4px;
            color: var(--muted);
            font-size: 12px;
        }

        .form-label {
            margin-bottom: 8px;
            color: #34423d;
            font-size: 12px;
            font-weight: 700;
        }

        .required {
            color: #c24135;
        }

        .form-control,
        .form-select {
            min-height: 45px;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: #213430;
            font-family: inherit;
            font-size: 13px;
            box-shadow: none;
        }

        textarea.form-control {
            min-height: 115px;
            resize: vertical;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 93, 75, .09);
        }

        .form-text {
            color: var(--muted);
            font-size: 11px;
        }

        .form-footer {
            display: flex;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f0eadc;
        }

        .side-card {
            padding: 21px;
            border: 1px solid var(--border);
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .02);
        }

        .side-card + .side-card {
            margin-top: 16px;
        }

        .side-title {
            margin-bottom: 16px;
            color: var(--primary);
            font-size: 14px;
            font-weight: 800;
        }

        .room-preview {
            padding: 18px;
            border: 1px solid #d9e5df;
            border-radius: 14px;
            background: var(--light-green);
        }

        .preview-label {
            margin-bottom: 7px;
            color: var(--muted);
            font-size: 11px;
            font-weight: 600;
        }

        .preview-name {
            overflow-wrap: anywhere;
            color: var(--primary);
            font-size: 18px;
            font-weight: 800;
        }

        .preview-price {
            margin-top: 12px;
            color: var(--primary);
            font-size: 20px;
            font-weight: 800;
        }

        .preview-meta {
            margin-top: 6px;
            color: #50645c;
            font-size: 12px;
        }

        .info-list {
            display: grid;
            gap: 14px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            color: #52615b;
            font-size: 12px;
            line-height: 1.7;
        }

        .info-icon {
            flex-shrink: 0;
            font-size: 16px;
        }

        .status-note {
            margin-top: 10px;
            padding: 11px 12px;
            border-radius: 10px;
            background: #fff7df;
            color: #775800;
            font-size: 11px;
            line-height: 1.7;
        }

        .invalid-feedback {
            font-size: 11px;
        }

        @media (max-width: 992px) {
            .edit-layout {
                grid-template-columns: 1fr;
            }

            .side-column {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 16px;
            }

            .side-card + .side-card {
                margin-top: 0;
            }
        }

        @media (max-width: 768px) {
            .page-wrap {
                padding: 24px 14px 45px;
            }

            .page-title {
                font-size: 23px;
            }

            .panel {
                padding: 18px;
            }

            .side-column {
                grid-template-columns: 1fr;
            }

            .form-footer > * {
                width: 100%;
            }
        }
    </style>
</head>

<body>

{{-- NAVBAR THEO THEME TRỌ ƠI --}}
<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid">
        <a class="logo" href="{{ route('landlord.dashboard') }}">
            Trọ <span>Ơi</span>
        </a>

        <button
            class="navbar-toggler shadow-none border-0"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#ownerMenu"
            aria-controls="ownerMenu"
            aria-expanded="false"
            aria-label="Mở menu">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="ownerMenu">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="app-nav-link text-decoration-none" href="{{ route('landlord.dashboard') }}">
                        Tổng quan
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle active text-decoration-none"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Nhà &amp; Phòng
                    </a>

                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.properties.index') }}">
                                Quản lý nhà trọ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item active"
                               href="{{ route('owner.rooms.index') }}">
                                Quản lý phòng
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Khách &amp; Hợp đồng
                    </a>

                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.tenants.manage') }}">
                                Người thuê
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.contracts.index') }}">
                                Hợp đồng
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.appointments.index') }}">
                                Lịch xem phòng
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none"
                       href="#"
                       role="button"
                       data-bs-toggle="dropdown"
                       aria-expanded="false">
                        Tài chính
                    </a>

                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.invoices.index') }}">
                                Hóa đơn
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.payments.index') }}">
                                Thanh toán
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.utilities.index') }}">
                                Điện nước
                            </a>
                        </li>
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

<main class="page-wrap">
    <div class="page-header">
        <div>
            <h1 class="page-title">Chỉnh sửa phòng</h1>
            <div class="page-desc">
                Cập nhật thông tin và trạng thái phòng đang quản lý.
            </div>
        </div>

        <a class="btn-secondary-custom" href="{{ route('owner.rooms.index') }}">
            ← Quay lại danh sách
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-bold mb-2">Vui lòng kiểm tra lại thông tin:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-layout">
        {{-- FORM --}}
        <section class="panel">
            <div class="panel-heading">
                <div class="heading-icon">🏠</div>
                <div>
                    <h2 class="panel-title">Thông tin phòng</h2>
                    <div class="panel-subtitle">
                        Mã bản ghi: #{{ $room->id }}
                    </div>
                </div>
            </div>

            <form
                id="editRoomForm"
                method="POST"
                action="{{ route('owner.rooms.update', $room) }}"
                novalidate>
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="property_id" class="form-label">
                        Nhà trọ <span class="required">*</span>
                    </label>
                    <select
                        id="property_id"
                        name="property_id"
                        class="form-select @error('property_id') is-invalid @enderror"
                        required>
                        <option value="">Chọn nhà trọ</option>
                        @foreach ($properties as $property)
                            <option
                                value="{{ $property->id }}"
                                @selected((string) old('property_id', $room->property_id) === (string) $property->id)>
                                {{ $property->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="name" class="form-label">
                            Tên phòng <span class="required">*</span>
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            maxlength="100"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $room->name) }}"
                            placeholder="Ví dụ: Phòng 101"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="room_code" class="form-label">Mã phòng</label>
                        <input
                            id="room_code"
                            name="room_code"
                            type="text"
                            maxlength="20"
                            class="form-control @error('room_code') is-invalid @enderror"
                            value="{{ old('room_code', $room->room_code) }}"
                            placeholder="Ví dụ: P101">
                        @error('room_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="area" class="form-label">
                            Diện tích (m²) <span class="required">*</span>
                        </label>
                        <input
                            id="area"
                            name="area"
                            type="number"
                            min="0.01"
                            step="0.01"
                            class="form-control @error('area') is-invalid @enderror"
                            value="{{ old('area', $room->area) }}"
                            required>
                        @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="max_people" class="form-label">
                            Sức chứa (người) <span class="required">*</span>
                        </label>
                        <input
                            id="max_people"
                            name="max_people"
                            type="number"
                            min="1"
                            step="1"
                            class="form-control @error('max_people') is-invalid @enderror"
                            value="{{ old('max_people', $room->max_people) }}"
                            required>
                        @error('max_people')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label">
                            Giá thuê (VNĐ/tháng) <span class="required">*</span>
                        </label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', (int) $room->price) }}"
                            required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="pricePreview"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="deposit" class="form-label">
                            Tiền đặt cọc (VNĐ) <span class="required">*</span>
                        </label>
                        <input
                            id="deposit"
                            name="deposit"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control @error('deposit') is-invalid @enderror"
                            value="{{ old('deposit', (int) $room->deposit) }}"
                            required>
                        @error('deposit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="depositPreview"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="floor" class="form-label">Tầng</label>
                        <input
                            id="floor"
                            name="floor"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control @error('floor') is-invalid @enderror"
                            value="{{ old('floor', $room->floor) }}"
                            placeholder="Để trống nếu không áp dụng">
                        @error('floor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>
                            @foreach ([
                                'available' => 'Còn trống',
                                'pending' => 'Đang chờ',
                                'rented' => 'Đang thuê',
                                'maintenance' => 'Bảo trì',
                                'hidden' => 'Đang ẩn',
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(old('status', $room->status) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="statusNote" class="status-note" hidden></div>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Mô tả phòng</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Mô tả nội thất, tiện nghi và lưu ý về phòng...">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('owner.rooms.index') }}" class="btn-secondary-custom">
                        Hủy
                    </a>
                    <button type="submit" id="saveButton" class="btn-brand">
                        <span>✓</span> Lưu thay đổi
                    </button>
                </div>
            </form>
        </section>

        {{-- CỘT THÔNG TIN BÊN PHẢI --}}
        <aside class="side-column">
            <section class="side-card">
                <h3 class="side-title">Xem trước thông tin</h3>

                <div class="room-preview">
                    <div class="preview-label">Tên phòng</div>
                    <div class="preview-name" id="previewName">
                        {{ old('name', $room->name) }}
                    </div>

                    <div class="preview-price" id="previewPrice">
                        {{ number_format((float) old('price', $room->price), 0, ',', '.') }}đ
                    </div>

                    <div class="preview-meta">
                        Diện tích: <strong id="previewArea">{{ old('area', $room->area) }}</strong> m²
                    </div>
                    <div class="preview-meta">
                        Sức chứa: <strong id="previewPeople">{{ old('max_people', $room->max_people) }}</strong> người
                    </div>
                    <div class="preview-meta">
                        Trạng thái: <strong id="previewStatus"></strong>
                    </div>
                </div>
            </section>

            <section class="side-card">
                <h3 class="side-title">Lưu ý khi chỉnh sửa</h3>

                <div class="info-list">
                    <div class="info-item">
                        <span class="info-icon">🔐</span>
                        <span>Chỉ chủ trọ sở hữu phòng mới được phép cập nhật thông tin.</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">💰</span>
                        <span>Kiểm tra giá thuê và tiền đặt cọc trước khi lưu.</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">📋</span>
                        <span>Thông tin chỉ được lưu khi em bấm nút Lưu thay đổi.</span>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('editRoomForm');
    const saveButton = document.getElementById('saveButton');

    const fields = {
        name: document.getElementById('name'),
        area: document.getElementById('area'),
        people: document.getElementById('max_people'),
        price: document.getElementById('price'),
        deposit: document.getElementById('deposit'),
        status: document.getElementById('status'),
        description: document.getElementById('description')
    };

    const initialValues = new FormData(form);
    const initialSnapshot = JSON.stringify(Array.from(initialValues.entries()));
    let submitted = false;

    const statusLabels = {
        available: 'Còn trống',
        pending: 'Đang chờ',
        rented: 'Đang thuê',
        maintenance: 'Bảo trì',
        hidden: 'Đang ẩn'
    };

    function formatMoney(value) {
        const number = Number(value);
        if (value === '' || !Number.isFinite(number)) return '0đ';

        return new Intl.NumberFormat('vi-VN', {
            maximumFractionDigits: 0
        }).format(number) + 'đ';
    }

    function updatePreview() {
        document.getElementById('previewName').textContent =
            fields.name.value.trim() || 'Chưa đặt tên phòng';

        document.getElementById('previewPrice').textContent =
            formatMoney(fields.price.value);

        document.getElementById('previewArea').textContent =
            fields.area.value || '0';

        document.getElementById('previewPeople').textContent =
            fields.people.value || '0';

        document.getElementById('previewStatus').textContent =
            statusLabels[fields.status.value] || fields.status.value;

        document.getElementById('pricePreview').textContent =
            'Hiển thị: ' + formatMoney(fields.price.value);

        document.getElementById('depositPreview').textContent =
            'Hiển thị: ' + formatMoney(fields.deposit.value);

        const statusNote = document.getElementById('statusNote');
        const notes = {
            rented: 'Phòng đang thuê. Hãy kiểm tra hợp đồng hiện tại trước khi đổi trạng thái.',
            maintenance: 'Phòng bảo trì nên không phù hợp để tiếp nhận người thuê mới.',
            hidden: 'Phòng ẩn có thể không xuất hiện trong danh sách công khai.',
            pending: 'Phòng đang chờ xử lý. Hãy kiểm tra trạng thái nghiệp vụ liên quan.'
        };

        statusNote.hidden = !notes[fields.status.value];
        statusNote.textContent = notes[fields.status.value] || '';
    }

    function hasChanges() {
        return JSON.stringify(
            Array.from(new FormData(form).entries())
        ) !== initialSnapshot;
    }

    Object.values(fields).forEach(function (field) {
        field.addEventListener('input', updatePreview);
        field.addEventListener('change', updatePreview);
    });

    form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            form.classList.add('was-validated');
            form.reportValidity();
            return;
        }

        const area = Number(fields.area.value);
        const price = Number(fields.price.value);
        const deposit = Number(fields.deposit.value);
        const people = Number(fields.people.value);

        if (area <= 0 || price < 0 || deposit < 0 || people < 1) {
            event.preventDefault();
            alert('Vui lòng kiểm tra diện tích, giá thuê, tiền cọc và sức chứa.');
            return;
        }

        submitted = true;
        saveButton.disabled = true;
        saveButton.textContent = 'Đang lưu...';
    });

    window.addEventListener('beforeunload', function (event) {
        if (!submitted && hasChanges()) {
            event.preventDefault();
            event.returnValue = '';
        }
    });

    updatePreview();
});
</script>

</body>
</html>
