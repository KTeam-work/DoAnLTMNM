
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trọ Ơi | Lịch xem phòng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">

    <style>
        body {
            background: #f8f5eb;
            color: #213430;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .owner-main {
            max-width: 1500px;
            margin: auto;
            padding: 30px 24px 50px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            gap: 20px;
            margin-bottom: 24px;
        }

        .eyebrow {
            color: #20584f;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }

        .heading h1 {
            font-size: 30px;
            font-weight: 800;
            margin: 6px 0;
        }

        .heading p, .summary-card small {
            color: #78837e;
        }

        .summary-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 22px;
        }

        .summary-card {
            background: white;
            border: 1px solid #e6dcc2;
            border-radius: 18px;
            padding: 20px;
        }

        .summary-card-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #64746e;
            font-size: 13px;
            font-weight: 600;
        }

        .summary-icon {
            width: 42px;
            height: 42px;
            border-radius: 13px;
            background: #eaf3ef;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .summary-card strong {
            display: block;
            font-size: 28px;
            margin: 12px 0 4px;
        }

        .filter-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 18px;
        }

        .filter-link {
            padding: 9px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            background: white;
            border: 1px solid #e6dcc2;
            color: #53635e;
        }

        .filter-link.active, .filter-link:hover {
            background: #20584f;
            border-color: #20584f;
            color: white;
        }

        .panel {
            background: white;
            border: 1px solid #e6dcc2;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 32px rgba(32, 88, 79, .06);
            margin-bottom: 22px;
        }

        .panel-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 22px;
            border-bottom: 1px solid #eee7d8;
        }

        .panel-title {
            font-size: 18px;
            font-weight: 800;
            margin: 0;
        }

        .calendar-content {
            padding: 22px;
        }

        #appointmentCalendar {
            min-height: 550px;
        }

        .calendar-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding: 0 22px 22px;
            color: #64746e;
            font-size: 12px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .dot-pending { background: #d69e00; }
        .dot-confirmed { background: #23856d; }
        .dot-completed { background: #718096; }
        .dot-cancelled { background: #cf6254; }

        .fc {
            --fc-border-color: #eee7d8;
            --fc-today-bg-color: #f0f7f3;
            color: #213430;
        }

        .fc .fc-toolbar-title {
            font-size: 19px;
            font-weight: 800;
        }

        .fc .fc-button {
            background: #20584f;
            border-color: #20584f;
            border-radius: 9px;
            font-weight: 700;
            box-shadow: none !important;
        }

        .fc .fc-button:hover,
        .fc .fc-button:active,
        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background: #17463e;
            border-color: #17463e;
        }

        .fc .fc-event {
            border: none;
            border-radius: 6px;
            padding: 3px 5px;
            cursor: pointer;
            font-size: 11px;
        }

        .fc .fc-timegrid-slot {
            height: 38px;
        }

        .fc .fc-col-header-cell-cushion,
        .fc .fc-timegrid-axis-cushion,
        .fc .fc-daygrid-day-number {
            color: #53635e;
            text-decoration: none;
            font-size: 12px;
        }

        .empty-state {
            text-align: center;
            padding: 22px;
            color: #78837e;
        }

        .empty-state .emoji {
            font-size: 38px;
            display: block;
            margin-bottom: 10px;
        }

        .alert {
            border: 0;
            border-radius: 12px;
        }

        .modal-content {
            border: 0;
            border-radius: 22px;
            overflow: hidden;
        }

        .modal-header {
            background: #20584f;
            color: white;
            padding: 20px 24px;
        }

        .modal-header .btn-close {
            filter: brightness(0) invert(1);
        }

        .modal-title {
            font-weight: 800;
        }

        .detail-body {
            padding: 24px;
        }

        .detail-row {
            padding: 13px 0;
            border-bottom: 1px solid #eee7d8;
        }

        .detail-row:last-child {
            border-bottom: 0;
        }

        .detail-label {
            color: #78837e;
            font-size: 12px;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 11px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-pending {
            background: #fff7d7;
            color: #765d00;
        }

        .status-confirmed {
            background: #eaf3ef;
            color: #20584f;
        }

        .status-completed {
            background: #edf1f0;
            color: #60706c;
        }

        .status-cancelled {
            background: #fff0ed;
            color: #a24f43;
        }

        .btn-owner, .btn-reject {
            border: 0;
            border-radius: 10px;
            padding: 10px 16px;
            font-weight: 700;
        }

        .btn-owner {
            background: #20584f;
            color: white;
        }

        .btn-owner:hover {
            background: #17463e;
            color: white;
        }

        .btn-reject {
            background: #fff0ed;
            color: #c65b4a;
        }

        .btn-reject:hover {
            background: #fee2e2;
            color: #a24f43;
        }

        @media (max-width: 900px) {
            .summary-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .calendar-content {
                padding: 12px;
            }

            #appointmentCalendar {
                min-height: 450px;
            }

            .fc .fc-toolbar {
                flex-wrap: wrap;
                gap: 10px;
            }

            .fc .fc-toolbar-title {
                font-size: 16px;
            }
        }

        @media (max-width: 600px) {
            .owner-main {
                padding: 22px 12px 35px;
            }

            .heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .heading h1 {
                font-size: 25px;
            }

            .summary-grid {
                gap: 10px;
            }

            .summary-card {
                padding: 14px;
            }

            .summary-card strong {
                font-size: 23px;
            }

            .calendar-legend {
                padding: 0 14px 18px;
                gap: 10px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid">
        <a class="logo" href="{{ route('landlord.dashboard') }}">
            Trọ <span>Ơi</span>
        </a>

        <button class="navbar-toggler shadow-none border-0"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu"
                aria-controls="mainMenu"
                aria-expanded="false"
                aria-label="Mở menu">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item">
                    <a class="app-nav-link text-decoration-none"
                       href="{{ route('landlord.dashboard') }}">
                        Tổng quan
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none"
                       href="#" data-bs-toggle="dropdown">
                        Nhà &amp; Phòng
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm">
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
                       href="#" data-bs-toggle="dropdown">
                        Khách &amp; Hợp đồng
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">
                                👤 Người thuê
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.contracts.index') }}">
                                📝 Hợp đồng
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none"
                       href="#" data-bs-toggle="dropdown">
                        Tài chính
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.services.index') }}">
                                ✨ Dịch vụ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.utilities.index') }}">
                                ⚡ Điện nước
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.invoices.index') }}">
                                🧾 Hóa đơn
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.payments.index') }}">
                                💰 Giao dịch
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item dropdown">
                    <a class="app-nav-link dropdown-toggle text-decoration-none active"
                       href="#" data-bs-toggle="dropdown">
                        Vận hành
                    </a>
                    <ul class="dropdown-menu border-0 shadow-sm">
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">
                                📢 Tin đăng
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.appointments.index') }}">
                                📅 Lịch xem phòng
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ url('/owner/maintenance') }}">
                                🛠️ Sửa chữa
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('owner.reviews.index') }}">
                                ⭐ Đánh giá
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

<main class="owner-main">
    <div class="heading">
        <div>
            <div class="eyebrow">Viewing appointments</div>
            <h1>Lịch xem phòng</h1>
            <p>Xem lịch hẹn theo tuần và xử lý yêu cầu của khách thuê.</p>
        </div>
        <div class="text-muted small">Bấm vào một lịch hẹn để xem chi tiết.</div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="filter-links">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
           class="filter-link {{ $statusFilter === 'all' ? 'active' : '' }}">
            Tất cả ({{ $counts['total'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
           class="filter-link {{ $statusFilter === 'pending' ? 'active' : '' }}">
            Chờ xác nhận ({{ $counts['pending'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'confirmed']) }}"
           class="filter-link {{ $statusFilter === 'confirmed' ? 'active' : '' }}">
            Đã xác nhận ({{ $counts['confirmed'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}"
           class="filter-link {{ $statusFilter === 'completed' ? 'active' : '' }}">
            Hoàn thành ({{ $counts['completed'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
           class="filter-link {{ $statusFilter === 'cancelled' ? 'active' : '' }}">
            Đã hủy ({{ $counts['cancelled'] }})
        </a>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-top">
                <span>Tổng lịch</span>
                <div class="summary-icon">📅</div>
            </div>
            <strong>{{ $counts['total'] }}</strong>
            <small>Tất cả lịch xem phòng</small>
        </div>

        <div class="summary-card">
            <div class="summary-card-top">
                <span>Chờ xác nhận</span>
                <div class="summary-icon">⏳</div>
            </div>
            <strong>{{ $counts['pending'] }}</strong>
            <small>Cần xử lý</small>
        </div>

        <div class="summary-card">
            <div class="summary-card-top">
                <span>Đã xác nhận</span>
                <div class="summary-icon">✓</div>
            </div>
            <strong>{{ $counts['confirmed'] }}</strong>
            <small>Lịch đã được chấp nhận</small>
        </div>

        <div class="summary-card">
            <div class="summary-card-top">
                <span>Hoàn thành</span>
                <div class="summary-icon">★</div>
            </div>
            <strong>{{ $counts['completed'] }}</strong>
            <small>Đã xem phòng</small>
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h3 class="panel-title">Lịch xem phòng theo tuần</h3>
            <span class="text-muted small">
                Tổng {{ $appointments->count() }} lịch đang hiển thị
            </span>
        </div>

        @if ($appointments->isEmpty())
            <div class="empty-state">
                <span class="emoji">📅</span>
                <p class="mb-0">Chưa có lịch hẹn ở trạng thái này.</p>
            </div>
        @endif

        <div class="calendar-content">
            <!-- Dữ liệu Blade được truyền qua data-* thay vì nhúng trong JavaScript -->
            <div
                id="appointmentCalendar"
                data-events="{{ json_encode($calendarEvents) }}"
                data-confirm-url="{{ route('owner.appointments.confirm', ['appointment' => '__ID__']) }}"
                data-reject-url="{{ route('owner.appointments.reject', ['appointment' => '__ID__']) }}">
            </div>
        </div>

        <div class="calendar-legend">
            <div class="legend-item">
                <span class="legend-dot dot-pending"></span> Chờ xác nhận
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-confirmed"></span> Đã xác nhận
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-completed"></span> Hoàn thành
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-cancelled"></span> Đã hủy
            </div>
        </div>
    </section>
</main>

<!-- Modal chi tiết lịch hẹn -->
<div class="modal fade"
     id="appointmentDetailModal"
     tabindex="-1"
     aria-labelledby="appointmentDetailTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="appointmentDetailTitle">
                        Chi tiết lịch xem phòng
                    </h5>
                    <small class="text-white-50">Thông tin lịch hẹn của khách thuê</small>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="detail-body">
                <div class="detail-row">
                    <div class="detail-label">Khách thuê</div>
                    <div class="detail-value" id="detailTenant">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Phòng</div>
                    <div class="detail-value" id="detailRoom">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Địa chỉ</div>
                    <div class="detail-value" id="detailAddress">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Số điện thoại</div>
                    <div class="detail-value" id="detailPhone">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ngày xem phòng</div>
                    <div class="detail-value" id="detailDate">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Giờ xem phòng</div>
                    <div class="detail-value" id="detailTime">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Lời nhắn</div>
                    <div class="detail-value" id="detailMessage">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Trạng thái</div>
                    <span id="detailStatus" class="status-badge">—</span>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4" id="appointmentActions">
                    <form id="confirmForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-owner">✓ Xác nhận lịch</button>
                    </form>

                    <form id="rejectForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-reject">✕ Từ chối lịch</button>
                    </form>
                </div>

                <div id="processedNotice" class="alert alert-light mt-3 mb-0 d-none">
                    Lịch hẹn này đã được xử lý, không thể xác nhận hoặc từ chối lại.
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales-all.global.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarElement = document.getElementById('appointmentCalendar');

    if (!calendarElement) {
        console.error('Không tìm thấy phần tử appointmentCalendar.');
        return;
    }

    // Đọc dữ liệu và URL từ data-*; không đặt cú pháp Blade trong JavaScript.
    let appointments = [];

    try {
        appointments = JSON.parse(calendarElement.dataset.events || '[]');
    } catch (error) {
        console.error('Không thể đọc dữ liệu lịch hẹn:', error);
        appointments = [];
    }

    const confirmUrlTemplate = calendarElement.dataset.confirmUrl;
    const rejectUrlTemplate = calendarElement.dataset.rejectUrl;

    const modalElement = document.getElementById('appointmentDetailModal');
    const detailModal = new bootstrap.Modal(modalElement);

    const confirmForm = document.getElementById('confirmForm');
    const rejectForm = document.getElementById('rejectForm');
    const appointmentActions = document.getElementById('appointmentActions');
    const processedNotice = document.getElementById('processedNotice');

    function setText(id, value) {
        document.getElementById(id).textContent = value || '—';
    }

    function formatDate(dateString) {
        if (!dateString) return '—';

        const parts = dateString.split('-');

        if (parts.length !== 3) return dateString;

        return `${parts[2]}/${parts[1]}/${parts[0]}`;
    }

    function formatTime(timeString) {
        if (!timeString) return '—';

        return timeString.substring(0, 5);
    }

    function showAppointment(event) {
        const data = event.extendedProps || {};
        const appointmentId = event.id;

        setText('detailTenant', data.tenant);
        setText('detailRoom', data.room);
        setText('detailAddress', data.address);
        setText('detailPhone', data.phone);
        setText('detailDate', formatDate(data.date));
        setText('detailTime', formatTime(data.time));
        setText('detailMessage', data.message);

        const statusElement = document.getElementById('detailStatus');

        statusElement.textContent = data.statusLabel || 'Không xác định';
        statusElement.className = 'status-badge';

        const statusClassMap = {
            pending: 'status-pending',
            confirmed: 'status-confirmed',
            completed: 'status-completed',
            cancelled: 'status-cancelled'
        };

        statusElement.classList.add(
            statusClassMap[data.status] || 'status-completed'
        );

        if (data.status === 'pending') {
            confirmForm.action = confirmUrlTemplate.replace(
                '__ID__',
                encodeURIComponent(appointmentId)
            );

            rejectForm.action = rejectUrlTemplate.replace(
                '__ID__',
                encodeURIComponent(appointmentId)
            );

            appointmentActions.classList.remove('d-none');
            processedNotice.classList.add('d-none');
        } else {
            appointmentActions.classList.add('d-none');
            processedNotice.classList.remove('d-none');
        }

        detailModal.show();
    }

    const statusColors = {
        pending: '#d69e00',
        confirmed: '#23856d',
        completed: '#718096',
        cancelled: '#cf6254'
    };

    const events = appointments
        .filter(function (appointment) {
            return appointment && appointment.start;
        })
        .map(function (appointment) {
            const props = appointment.extendedProps || {};
            const color = statusColors[props.status] || '#718096';

            return {
                id: String(appointment.id),
                title: appointment.title || 'Lịch xem phòng',
                start: appointment.start,
                allDay: false,
                extendedProps: props,
                backgroundColor: color,
                borderColor: color,
                textColor: '#ffffff'
            };
        });

    const calendar = new FullCalendar.Calendar(calendarElement, {
        locale: 'vi',
        initialView: 'timeGridWeek',
        firstDay: 1,
        nowIndicator: true,
        height: 'auto',
        expandRows: true,
        allDaySlot: false,
        weekends: true,
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        slotDuration: '00:30:00',
        scrollTime: '08:00:00',

        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },

        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay,dayGridMonth'
        },

        buttonText: {
            today: 'Hôm nay',
            week: 'Tuần',
            day: 'Ngày',
            month: 'Tháng'
        },

        noEventsContent: 'Không có lịch hẹn trong khoảng thời gian này',
        events: events,

        eventClick: function (info) {
            info.jsEvent.preventDefault();
            showAppointment(info.event);
        }
    });

    calendar.render();
});
</script>

</body>
</html>
