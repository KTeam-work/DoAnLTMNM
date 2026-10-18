
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý phòng | Trọ Ơi</title>

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
            --border: #e6dcc2;
            --text: #1f2937;
            --muted: #6b7280;
        }

        body {
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
            color: var(--primary);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.6px;
            margin: 0;
        }

        .page-desc {
            color: var(--muted);
            font-size: 13px;
            margin-top: 6px;
        }

        .btn-brand {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            background: var(--yellow);
            color: var(--primary);
            font-weight: 800;
            font-size: 12px;
            border-radius: 11px;
            padding: 11px 16px;
            white-space: nowrap;
            text-decoration: none;
            transition: .2s;
        }

        .btn-brand:hover {
            background: #ffd968;
            color: var(--primary);
        }

        .btn-brand-light {
            background: var(--light-green);
        }

        .btn-brand-light:hover {
            background: #d5e7dd;
        }

        .stat-card {
            height: 100%;
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 20px 22px;
        }

        .stat-label {
            font-size: 12px;
            color: var(--muted);
            font-weight: 600;
        }

        .stat-value {
            font-size: 26px;
            font-weight: 800;
            color: var(--primary);
            margin-top: 4px;
        }

        .panel {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, .02);
        }

        .panel-heading {
            font-size: 16px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .panel-subheading {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 20px;
        }

        .toolbar {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 22px;
        }

        .search-input,
        .filter-select {
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 11px;
            outline: none;
            background: #fff;
            color: #213430;
            padding: 0 14px;
            font-family: inherit;
            font-size: 12px;
        }

        .search-input {
            flex: 1;
            min-width: 220px;
        }

        .filter-select {
            min-width: 175px;
        }

        .search-input:focus,
        .filter-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(0, 93, 75, .08);
        }

        .table-responsive {
            border-radius: 12px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 850px;
        }

        .data-table thead th {
            text-align: left;
            color: var(--muted);
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            padding: 0 12px 14px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .data-table tbody td {
            padding: 16px 12px;
            border-bottom: 1px solid #f0eadc;
            vertical-align: middle;
        }

        .data-table tbody tr:last-child td {
            border-bottom: 0;
        }

        .data-table tbody tr.room-row:hover {
            background: #fcfcf8;
        }

        .room-name {
            font-weight: 800;
            color: #213430;
        }

        .room-code {
            color: var(--muted);
            font-size: 10px;
            margin-top: 5px;
        }

        .room-price {
            color: var(--primary);
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 800;
            white-space: nowrap;
        }

        .badge-status.available {
            background: #e6f0eb;
            color: #005d4b;
        }

        .badge-status.rented {
            background: #e8efff;
            color: #3156a5;
        }

        .badge-status.pending {
            background: #fff4d6;
            color: #946b00;
        }

        .badge-status.maintenance {
            background: #fae8e5;
            color: #a33f35;
        }

        .badge-status.hidden,
        .badge-status.other {
            background: #f0eadc;
            color: #6b7280;
        }

        .action-group {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            padding: 7px 10px;
            font-family: inherit;
            font-size: 10px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            cursor: pointer;
            transition: .2s;
        }

        .btn-detail {
            border: 1px solid #d9e5df;
            background: #fff;
            color: var(--primary);
        }

        .btn-detail:hover {
            background: var(--light-green);
            color: var(--primary);
        }

        .btn-edit-room {
            border: 1px solid var(--primary);
            background: var(--primary);
            color: #fff;
        }

        .btn-edit-room:hover {
            background: var(--primary-dark);
            color: #fff;
        }

        .btn-delete-room {
            border: 1px solid #f1c5c1;
            background: #fff1f0;
            color: #a33f35;
        }

        .btn-delete-room:hover {
            background: #fae0dd;
        }

        .empty-state {
            text-align: center;
            color: var(--muted);
            padding: 45px 15px !important;
        }

        .empty-state-icon {
            font-size: 34px;
            margin-bottom: 10px;
        }

        .user-avatar-small {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--light-green);
            color: var(--primary);
            font-weight: 800;
        }

        .result-count {
            font-size: 11px;
            color: var(--muted);
            margin-bottom: 12px;
        }

        @media (max-width: 768px) {
            .page-wrap {
                padding: 24px 14px 45px;
            }

            .page-title {
                font-size: 23px;
            }

            .panel {
                padding: 16px;
            }

            .filter-select,
            .search-input {
                width: 100%;
                min-width: 0;
            }
        }
    </style>
</head>

<body>

    {{-- NAVBAR --}}
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
                                <a class="dropdown-item" href="{{ route('owner.properties.index') }}">
                                    Quản lý nhà trọ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item active" href="{{ route('owner.rooms.index') }}">
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
                            Vận hành
                        </a>

                        <ul class="dropdown-menu border-0 shadow-sm">
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.appointments.index') }}">
                                    Lịch xem phòng
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ url('/owner/maintenance') }}">
                                    Yêu cầu bảo trì
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.reviews.index') }}">
                                    Đánh giá
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
                                <a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">
                                    Người thuê
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.contracts.index') }}">
                                    Hợp đồng
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
                                <a class="dropdown-item" href="{{ route('owner.invoices.index') }}">
                                    Hóa đơn
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.payments.index') }}">
                                    Thanh toán
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.utilities.index') }}">
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

    {{-- NỘI DUNG --}}
    <main class="page-wrap">

        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý phòng</h1>
                <div class="page-desc">
                    Quản lý thông tin và trạng thái các phòng thuộc nhà trọ của bạn.
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn-brand btn-brand-light"
                    href="{{ route('owner.rental-posts.index') }}">
                    Quản lý tin đăng
                </a>

                <a class="btn-brand"
                    href="{{ route('owner.rental-posts.create') }}">
                    + Tạo tin đăng
                </a>

                <a class="btn-brand"
                    href="{{ route('owner.rooms.create') }}">
                    + Thêm phòng
                </a>
            </div>
        </div>

        {{-- THÔNG BÁO --}}
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
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Dữ liệu chưa hợp lệ!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- THỐNG KÊ --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Tổng số phòng</div>
                    <div class="stat-value">{{ $rooms->count() }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Còn trống</div>
                    <div class="stat-value">{{ $rooms->where('status', 'available')->count() }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Đang cho thuê</div>
                    <div class="stat-value">{{ $rooms->where('status', 'rented')->count() }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Bảo trì</div>
                    <div class="stat-value">{{ $rooms->where('status', 'maintenance')->count() }}</div>
                </div>
            </div>
        </div>

        {{-- DANH SÁCH PHÒNG --}}
        <section class="panel">
            <div class="panel-heading">Danh sách phòng</div>
            <div class="panel-subheading">
                Theo dõi giá thuê, sức chứa và trạng thái từng phòng.
            </div>

            <div class="toolbar">
                <input
                    type="search"
                    id="roomSearch"
                    class="search-input"
                    placeholder="Tìm theo tên phòng, mã phòng hoặc nhà trọ..."
                    aria-label="Tìm phòng">

                <select id="statusFilter" class="filter-select" aria-label="Lọc trạng thái">
                    <option value="">Tất cả trạng thái</option>
                    <option value="available">Còn trống</option>
                    <option value="rented">Đang thuê</option>
                    <option value="pending">Đang chờ</option>
                    <option value="maintenance">Đang bảo trì</option>
                    <option value="hidden">Đang ẩn</option>
                </select>
            </div>

            <div class="result-count" id="resultCount">
                Tổng cộng {{ $rooms->count() }} phòng
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Thông tin phòng</th>
                            <th>Nhà trọ</th>
                            <th>Diện tích</th>
                            <th>Giá thuê</th>
                            <th>Sức chứa</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="roomTableBody">
                        @forelse ($rooms as $room)
                            <tr
                                class="room-row"
                                data-search="{{ $room->name }} {{ $room->room_code ?? '' }} {{ $room->property?->name ?? '' }}"
                                data-status="{{ $room->status }}">

                                <td>
                                    <div class="room-name">{{ $room->name }}</div>
                                    <div class="room-code">
                                        Mã phòng: {{ $room->room_code ?: 'Chưa có mã' }}
                                    </div>
                                </td>

                                <td>{{ $room->property?->name ?? 'Chưa có nhà trọ' }}</td>

                                <td>
                                    {{ $room->area !== null ? number_format((float) $room->area, 2, ',', '.') : 'Chưa cập nhật' }}
                                    @if ($room->area !== null)
                                        m²
                                    @endif
                                </td>

                                <td>
                                    <span class="room-price">
                                        {{ number_format((float) ($room->price ?? 0), 0, ',', '.') }}đ
                                    </span>
                                    <div class="room-code">/ tháng</div>
                                </td>

                                <td>{{ $room->max_people ?? '—' }} người</td>

                                <td>
                                    @switch($room->status)
                                        @case('available')
                                            <span class="badge-status available">Còn trống</span>
                                            @break

                                        @case('rented')
                                            <span class="badge-status rented">Đang thuê</span>
                                            @break

                                        @case('pending')
                                            <span class="badge-status pending">Đang chờ</span>
                                            @break

                                        @case('maintenance')
                                            <span class="badge-status maintenance">Bảo trì</span>
                                            @break

                                        @case('hidden')
                                            <span class="badge-status hidden">Đang ẩn</span>
                                            @break

                                        @default
                                            <span class="badge-status other">
                                                {{ $room->status ?? 'Chưa xác định' }}
                                            </span>
                                    @endswitch
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            class="btn-action btn-detail"
                                            href="{{ route('owner.rooms.show', $room) }}">
                                            Chi tiết
                                        </a>

                                        <a
                                            class="btn-action btn-edit-room"
                                            href="{{ route('owner.rooms.edit', $room) }}">
                                            Sửa
                                        </a>

                                        <form
                                            action="{{ route('owner.rooms.destroy', $room) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng {{ $room->name }} không?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-action btn-delete-room">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRoomsRow">
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">🏠</div>
                                    <div class="fw-bold mb-2">Chưa có phòng nào</div>
                                    <div class="mb-3">
                                        Hãy thêm phòng đầu tiên để bắt đầu quản lý.
                                    </div>
                                    <a class="btn-brand" href="{{ route('owner.rooms.create') }}">
                                        + Thêm phòng
                                    </a>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noSearchResults" style="display: none;">
                            <td colspan="7" class="empty-state">
                                Không tìm thấy phòng phù hợp với bộ lọc.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('roomSearch');
            const statusFilter = document.getElementById('statusFilter');
            const rows = Array.from(document.querySelectorAll('.room-row'));
            const noResults = document.getElementById('noSearchResults');
            const resultCount = document.getElementById('resultCount');

            function normalizeText(value) {
                return String(value || '')
                    .toLowerCase()
                    .normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '')
                    .replace(/đ/g, 'd')
                    .trim();
            }

            function filterRooms() {
                const keyword = normalizeText(searchInput.value);
                const status = statusFilter.value;
                let visibleCount = 0;

                rows.forEach(function (row) {
                    const searchText = normalizeText(row.dataset.search);
                    const rowStatus = row.dataset.status || '';

                    const matchesKeyword = searchText.includes(keyword);
                    const matchesStatus = !status || rowStatus === status;
                    const visible = matchesKeyword && matchesStatus;

                    row.style.display = visible ? '' : 'none';

                    if (visible) {
                        visibleCount++;
                    }
                });

                if (noResults) {
                    noResults.style.display =
                        rows.length > 0 && visibleCount === 0 ? '' : 'none';
                }

                if (resultCount) {
                    resultCount.textContent =
                        'Đang hiển thị ' + visibleCount + '/' + rows.length + ' phòng';
                }
            }

            searchInput.addEventListener('input', filterRooms);
            statusFilter.addEventListener('change', filterRooms);
        });
    </script>

</body>
</html>
