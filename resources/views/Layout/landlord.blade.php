
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Kênh chủ trọ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

    {{-- =========================
         NAVBAR - CHỦ TRỌ
    ========================== --}}
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <button
                class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#ownerMainMenu"
                aria-controls="ownerMainMenu"
                aria-expanded="false"
                aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="ownerMainMenu">
                  <a class="logo" href="{{ route('landlord.dashboard') }}">Trọ <span>Ơi</span></a>

                <ul class="navbar-nav mx-auto align-items-lg-center">

                    {{-- Tổng quan --}}
                    <li class="nav-item">
                        <a class="app-nav-link active text-decoration-none"
                           href="{{ route('landlord.dashboard') }}">
                            Tổng quan
                        </a>
                    </li>

                    {{-- Nhà và phòng --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Nhà &amp; Phòng
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.properties.index') }}">
                                    🏠 Quản lý nhà
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.rooms.index') }}">
                                    🚪 Quản lý phòng
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Khách thuê --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Khách &amp; Hợp đồng
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.tenants.manage') }}">
                                    👤 Người thuê
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.contracts.index') }}">
                                    📝 Hợp đồng
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Tài chính --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Tài chính
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.services.index') }}">
                                    ✨ Dịch vụ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.utilities.index') }}">
                                    ⚡ Điện nước
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.invoices.index') }}">
                                    🧾 Hóa đơn
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.payments.index') }}">
                                    💰 Giao dịch
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Vận hành --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Vận hành
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a>
                             
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.appointments.index') }}">
                                    📅 Lịch xem phòng
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ url('/owner/maintenance') }}">
                                    🛠️ Sửa chữa
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.reviews.index') }}">
                                    ⭐ Đánh giá
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>

                {{-- Thông tin tài khoản --}}
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


    {{-- =========================
         NỘI DUNG DASHBOARD
    ========================== --}}
    <main class="page-wrap">

        {{-- Tiêu đề --}}
        <section class="page-header">
            <div>
                <h2 class="page-title">Tổng quan hoạt động</h2>

                <div class="page-desc">
                    Xin chào {{ auth()->user()->name ?? 'Chủ trọ' }},
                    đây là tình hình cho thuê của bạn.
                </div>
            </div>

            <a class="btn-brand"
               href="{{ route('owner.rooms.index') }}"
               style="text-decoration: none;">
                + Quản lý phòng
            </a>
        </section>


        {{-- =========================
             THỐNG KÊ
        ========================== --}}
        <section class="row g-3 mb-3">

            {{-- Tổng số phòng --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon">🏠</div>
                    </div>

                    <div class="stat-value">
                        {{ $totalRooms }}
                    </div>

                    <div class="stat-label">Tổng số phòng</div>
                </div>
            </div>

            {{-- Phòng đang thuê --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon green">✅</div>
                    </div>

                    <div class="stat-value">
                        {{ $rentedRooms }}
                    </div>

                    <div class="stat-label">Phòng đang thuê</div>
                </div>
            </div>

            {{-- Phòng còn trống --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon blue">🔑</div>
                    </div>

                    <div class="stat-value">
                        {{ $availableRooms }}
                    </div>

                    <div class="stat-label">Phòng còn trống</div>
                </div>
            </div>

            {{-- Doanh thu --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon red">💰</div>
                    </div>

                    <div class="stat-value">
                        {{ number_format((float) $monthlyRevenue, 0, ',', '.') }}đ
                    </div>

                    <div class="stat-label">Doanh thu tháng này</div>
                </div>
            </div>

        </section>


        {{-- =========================
             BẢNG DỮ LIỆU
        ========================== --}}
        <section class="row g-3">

            {{-- DANH SÁCH PHÒNG --}}
            <div class="col-lg-7">
                <div class="panel">

                    <div class="panel-head">
                        <h3 class="panel-title">Danh sách phòng</h3>

                        <a class="panel-link"
                           href="{{ route('owner.rooms.index') }}">
                            Quản lý tất cả
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Phòng</th>
                                    <th>Người thuê</th>
                                    <th>Giá thuê</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($rooms as $room)
                                    @php
                                        $activeContract = $room->contracts->first();
                                        $tenantName = $activeContract?->tenant?->name;
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="cell-title">
                                                {{ $room->name }}

                                                @if ($room->property)
                                                    · {{ $room->property->name }}
                                                @endif
                                            </div>

                                            <div class="cell-sub">
                                                {{ $room->property?->district
                                                    ?? 'Chưa cập nhật quận/huyện' }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ $tenantName ?? '—' }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $room->price, 0, ',', '.') }}đ
                                        </td>

                                        <td>
                                            @switch($room->status)
                                                @case('rented')
                                                    <span class="badge-status occupied">
                                                        Đang thuê
                                                    </span>
                                                    @break

                                                @case('available')
                                                    <span class="badge-status vacant">
                                                        Còn trống
                                                    </span>
                                                    @break

                                                @case('pending')
                                                    <span class="badge-status">
                                                        Đang chờ
                                                    </span>
                                                    @break

                                                @case('maintenance')
                                                    <span class="badge-status overdue">
                                                        Đang bảo trì
                                                    </span>
                                                    @break

                                                @case('hidden')
                                                    <span class="badge-status">
                                                        Đang ẩn
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="badge-status">
                                                        {{ $room->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="cell-title">
                                                Chưa có phòng nào
                                            </div>

                                            <div class="cell-sub">
                                                Hãy thêm phòng để dữ liệu hiển thị tại đây.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


            {{-- LỊCH XEM PHÒNG --}}
            <div class="col-lg-5">
                <div class="panel">

                    <div class="panel-head">
                        <h3 class="panel-title">Lịch xem phòng gần nhất</h3>

                        <a class="panel-link"
                           href="{{ route('owner.appointments.index') }}">
                            Xem tất cả
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Khách xem</th>
                                    <th>Phòng</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($appointments as $appointment)
                                    <tr>
                                        <td>
                                            <div class="avatar-sm mb-1">
                                                {{ strtoupper(substr(
                                                    $appointment->tenant?->name ?? 'KH',
                                                    0,
                                                    2
                                                )) }}
                                            </div>

                                            {{ $appointment->tenant?->name ?? 'Khách thuê' }}

                                            <div class="cell-sub">
                                                @switch($appointment->status)
                                                    @case('pending')
                                                        Chờ xác nhận
                                                        @break

                                                    @case('confirmed')
                                                        Đã xác nhận
                                                        @break

                                                    @case('rescheduled')
                                                        Đề xuất đổi lịch
                                                        @break

                                                    @case('rejected')
                                                        Đã từ chối
                                                        @break

                                                    @case('cancelled')
                                                        Đã hủy
                                                        @break

                                                    @case('completed')
                                                        Đã hoàn thành
                                                        @break

                                                    @default
                                                        {{ $appointment->status }}
                                                @endswitch
                                            </div>
                                        </td>

                                        <td>
                                            {{ $appointment->room?->name ?? 'Phòng không tồn tại' }}
                                        </td>

                                        <td>
                                            @if ($appointment->appointment_date)
                                                {{ $appointment->appointment_date->format('d/m/Y') }}
                                            @else
                                                Chưa có ngày
                                            @endif

                                            <br>

                                            {{ substr(
                                                (string) $appointment->appointment_time,
                                                0,
                                                5
                                            ) }}
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="cell-title">
                                                Chưa có lịch xem phòng
                                            </div>

                                            <div class="cell-sub">
                                                Lịch hẹn mới sẽ xuất hiện tại đây.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </section>

    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
