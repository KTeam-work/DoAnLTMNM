<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Lịch xem phòng</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font -->
    <link
        rel="preconnect"
        href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- CSS chính -->
    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}">

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
                href="{{ route('tenant.home') }}">
                Trọ <span>Ơi</span>
            </a>


            <!-- MOBILE BUTTON -->
            <button
                class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div
                class="collapse navbar-collapse"
                id="mainMenu">

                <!-- MENU -->
                <ul class="navbar-nav mx-auto align-items-lg-center">

                    <li class="nav-item">

                        <a
                            class="app-nav-link"
                            href="{{ route('tenant.home') }}">
                            Trang chủ
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="app-nav-link"
                            href="{{ route('rooms.index') }}">
                            Tìm phòng
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="app-nav-link"
                            href="{{ route('favorites.index') }}">
                            Yêu thích
                        </a>

                    </li>


                    <li class="nav-item">

                        <a
                            class="app-nav-link active"
                            href="{{ route('appointments.index') }}">
                            Lịch xem phòng
                        </a>

                    </li>


                    <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a></li>
                    <li class="nav-item"><a class="app-nav-link" href="{{ url('/tenant/invoices/index') }}">Hóa đơn</a></li>

                    <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
                    <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>

                </ul>


                <!-- USER -->
                <div class="navbar-actions">

                    <button
                        class="notif-btn"
                        type="button"
                        title="Thông báo">
                        🔔
                        <span class="notif-dot"></span>
                    </button>


                    <a
                        href="#"
                        class="user-chip">

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
         HEADER
    ====================================================== -->

        <div class="page-header">

            <div>

                <div
                    class="badge-status pending mb-2"
                    style="display:inline-block;">
                    📅 LỊCH XEM CỦA BẠN
                </div>

                <h1 class="page-title">
                    Lịch xem phòng
                </h1>

                <div class="page-desc">
                    Theo dõi các lịch hẹn xem phòng bạn đã gửi cho chủ nhà.
                </div>

            </div>


            <!-- TẠO LỊCH MỚI -->

            <a
                href="{{ route('appointments.create') }}"
                class="btn-brand text-decoration-none">
                + Đặt lịch xem phòng
            </a>

        </div>



        <!-- =====================================================
         STATISTICS
    ====================================================== -->

        <div class="row g-3 mb-4">


            <!-- TỔNG -->
            <div class="col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon blue">
                            📅
                        </div>

                        <span class="stat-trend">
                            Tổng lịch
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $totalAppointments }}
                    </div>


                    <div class="stat-label">
                        Lịch xem phòng
                    </div>

                </div>

            </div>


            <!-- CHỜ XÁC NHẬN -->
            <div class="col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            ⏳
                        </div>

                        <span class="stat-trend">
                            Đang chờ
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $pendingCount }}
                    </div>


                    <div class="stat-label">
                        Chờ chủ nhà xác nhận
                    </div>

                </div>

            </div>


            <!-- ĐÃ XÁC NHẬN -->
            <div class="col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon green">
                            ✓
                        </div>

                        <span class="stat-trend">
                            Thành công
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $confirmedCount }}
                    </div>


                    <div class="stat-label">
                        Lịch đã xác nhận
                    </div>

                </div>

            </div>


            <!-- ĐÃ XEM -->
            <div class="col-sm-6 col-xl-3">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon red">
                            🏠
                        </div>

                        <span class="stat-trend">
                            Hoàn thành
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $completedCount }}
                    </div>


                    <div class="stat-label">
                        Đã xem phòng
                    </div>

                </div>

            </div>

        </div>



        <!-- LỊCH XEM SẮP TỚI -->
        <div class="panel mb-4">

            <div class="panel-head">
                <div>
                    <h2 class="panel-title">Lịch xem sắp tới</h2>
                    <div class="page-desc">
                        Lịch hẹn gần nhất của bạn.
                    </div>
                </div>

                @if ($upcomingAppointments->isNotEmpty())
                @php
                $nextAppointment = $upcomingAppointments->first();

                $upcomingStatusLabels = [
                'pending' => ['Chờ xác nhận', 'pending'],
                'confirmed' => ['Đã xác nhận', 'occupied'],
                ];

                $upcomingStatus = $upcomingStatusLabels[
                $nextAppointment->status
                ] ?? ['Chưa xác định', 'pending'];
                @endphp

                <span class="badge-status {{ $upcomingStatus[1] }}">
                    {{ $upcomingStatus[0] }}
                </span>
                @endif
            </div>

            @if ($upcomingAppointments->isNotEmpty())

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">

                        <div class="stat-icon green">📅</div>

                        <div>
                            <div class="cell-title">
                                {{ $nextAppointment->room?->name
                                ?? 'Phòng đã cập nhật' }}
                            </div>

                            <div class="cell-sub mb-2">
                                📍
                                {{ $nextAppointment->room?->property?->address
                                ?? 'Chưa có địa chỉ' }}
                            </div>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge-status occupied">
                                    📅
                                    {{ $nextAppointment->appointment_date
                                    ->format('d/m/Y') }}
                                </span>

                                <span class="badge-status pending">
                                    🕒
                                    {{ substr(
                                    (string) $nextAppointment->appointment_time,
                                    0,
                                    5
                                ) }}
                                </span>

                                <span class="badge-status new">
                                    ☎️ {{ $nextAppointment->phone }}
                                </span>

                            </div>

                            @if ($nextAppointment->message)
                            <div class="cell-sub mt-2">
                                Ghi chú: {{ $nextAppointment->message }}
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a
                        href="{{ route('appointments.create') }}"
                        class="btn-outline-brand text-decoration-none">
                        Đặt lịch khác
                    </a>
                </div>
            </div>

            @else

            <div class="text-center py-4">
                <div class="stat-icon mx-auto mb-3">📅</div>

                <div class="cell-title">
                    Bạn chưa có lịch xem phòng sắp tới
                </div>

                <div class="cell-sub mt-2 mb-3">
                    Những lịch đã qua, đã hủy hoặc đã từ chối
                    sẽ không hiển thị ở khu vực này.
                </div>

                <a
                    href="{{ route('appointments.create') }}"
                    class="btn-brand text-decoration-none">
                    + Đặt lịch xem phòng
                </a>
            </div>

            @endif

        </div>



        <!-- =====================================================
         FILTER
    ====================================================== -->

        <div class="panel mb-4">

            <div class="panel-head">

                <div>

                    <h2 class="panel-title">
                        Danh sách lịch hẹn
                    </h2>

                    <div class="page-desc">
                        Lịch xem được sắp xếp theo thời gian mới nhất.
                    </div>

                </div>


                <select
                    class="form-select"
                    style="
                    width:auto;
                    min-width:180px;
                    border-color:var(--border);
                    border-radius:11px;
                    font-size:12px;
                    font-weight:600;
                ">

                    <option selected>
                        Tất cả trạng thái
                    </option>

                    <option>
                        Chờ xác nhận
                    </option>

                    <option>
                        Đã xác nhận
                    </option>

                    <option>
                        Đề xuất giờ khác
                    </option>

                    <option>
                        Đã xem phòng
                    </option>

                    <option>
                        Đã từ chối
                    </option>

                    <option>
                        Đã hủy
                    </option>

                </select>

            </div>



            <!-- APPOINTMENT TABLE -->

            <div class="table-responsive">

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>
                                Phòng
                            </th>

                            <th>
                                Ngày
                            </th>

                            <th>
                                Giờ
                            </th>

                            <th>
                                Liên hệ
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Thao tác
                            </th>

                        </tr>

                    </thead>



                    <tbody>
                        @forelse ($appointments as $appointment)
                        <tr>
                            <td>
                                <div class="cell-title">
                                    {{ $appointment->room?->name ?? 'Phòng đã cập nhật' }}
                                </div>
                                <div class="cell-sub">
                                    {{ $appointment->room?->property?->address ?? 'Chưa có địa chỉ' }}
                                </div>
                            </td>

                            <td>
                                {{ $appointment->appointment_date?->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ substr((string) $appointment->appointment_time, 0, 5) }}
                            </td>

                            <td>{{ $appointment->phone }}</td>

                            <td>
                                @php
                                $statusLabels = [
                                'pending' => ['Chờ xác nhận', 'pending'],
                                'confirmed' => ['Đã xác nhận', 'occupied'],
                                'rescheduled' => ['Đề xuất giờ khác', 'new'],
                                'completed' => ['Đã xem phòng', 'resolved'],
                                'rejected' => ['Đã từ chối', 'overdue'],
                                'cancelled' => ['Đã hủy', 'overdue'],
                                ];

                                $statusInfo = $statusLabels[$appointment->status]
                                ?? [$appointment->status, 'pending'];
                                @endphp

                                <span class="badge-status {{ $statusInfo[1] }}">
                                    {{ $statusInfo[0] }}
                                </span>
                            </td>

                            <td>
                                @if (in_array($appointment->status, ['pending', 'confirmed']))
                                <form
                                    action="{{ route('appointments.destroy', $appointment->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Em có chắc muốn hủy lịch hẹn này không?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-outline-brand">
                                        Hủy lịch
                                    </button>
                                </form>
                                @else
                                <span class="cell-sub">
                                    {{ $appointment->owner_note ?? 'Không có ghi chú' }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3" style="font-size: 40px;">
                                    📅
                                </div>

                                <div class="cell-title mb-2">
                                    Chưa có lịch xem phòng nào
                                </div>

                                <div class="cell-sub mb-3">
                                    Hãy đặt lịch để chủ nhà xác nhận thời gian xem phòng phù hợp.
                                </div>

                                <a
                                    href="{{ route('appointments.create') }}"
                                    class="btn-brand text-decoration-none d-inline-flex">
                                    + Đặt lịch xem phòng
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>


                </table>

            </div>

        </div>



        <!-- =====================================================
         STATUS GUIDE
    ====================================================== -->

        <div class="panel">

            <div class="panel-head">

                <h3 class="panel-title">
                    Trạng thái lịch xem phòng
                </h3>

            </div>


            <div class="row g-3">


                <div class="col-md-4">

                    <div
                        class="p-3"
                        style="
                        background:var(--yellow-light);
                        border-radius:14px;
                    ">

                        <span class="badge-status pending">
                            PENDING
                        </span>

                        <div class="cell-title mt-2">
                            Chờ xác nhận
                        </div>

                        <div class="cell-sub">
                            Lịch đang chờ chủ nhà xử lý.
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div
                        class="p-3"
                        style="
                        background:var(--green-soft);
                        border-radius:14px;
                    ">

                        <span class="badge-status occupied">
                            CONFIRMED
                        </span>

                        <div class="cell-title mt-2">
                            Đã xác nhận
                        </div>

                        <div class="cell-sub">
                            Chủ nhà đã xác nhận lịch xem.
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div
                        class="p-3"
                        style="
                        background:var(--blue-soft);
                        border-radius:14px;
                    ">

                        <span class="badge-status new">
                            RESCHEDULED
                        </span>

                        <div class="cell-title mt-2">
                            Đề xuất giờ khác
                        </div>

                        <div class="cell-sub">
                            Chủ nhà đã đề xuất thời gian mới.
                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>


    <!-- Bootstrap JS -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```