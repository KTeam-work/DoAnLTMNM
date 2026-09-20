
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Quản lý lịch xem phòng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background:#f8f5eb;
            color:#213430;
            font-family:'Be Vietnam Pro',sans-serif;
        }

        .heading {
            display:flex;
            justify-content:space-between;
            align-items:end;
            margin-bottom:22px;
        }

        .eyebrow {
            color:#20584f;
            font-size:12px;
            font-weight:800;
            letter-spacing:1.5px;
            text-transform:uppercase;
        }

        .heading h1 {
            font-size:30px;
            font-weight:800;
            margin:6px 0;
        }

        .heading p {
            margin:0;
            color:#78837e;
        }

        .btn-owner {
            border:0;
            background:#20584f;
            color:white;
            border-radius:14px;
            padding:12px 18px;
            font-weight:700;
        }

        .btn-owner:hover {
            background:#17463e;
            color:white;
        }

        .summary-grid {
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:15px;
            margin-bottom:22px;
        }

        .summary-card {
            background:white;
            border:1px solid #e6dcc2;
            border-radius:20px;
            padding:18px;
        }

        .summary-card-top {
            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .summary-icon {
            width:44px;
            height:44px;
            border-radius:14px;
            background:#eaf3ef;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .summary-card strong {
            display:block;
            font-size:26px;
            margin-top:12px;
        }

        .summary-card span {
            color:#78837e;
            font-size:12px;
        }

        .calendar-shell {
            background:white;
            border:1px solid #e6dcc2;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 10px 32px rgba(32,88,79,.06);
        }

        .calendar-toolbar {
            padding:19px 22px;
            border-bottom:1px solid #eee7d8;
            display:flex;
            align-items:center;
            gap:9px;
            flex-wrap:wrap;
        }

        .calendar-toolbar h2 {
            font-size:18px;
            font-weight:800;
            margin:0 15px 0 8px;
            min-width:205px;
        }

        .cal-btn {
            border:1px solid #e6dcc2;
            background:white;
            color:#53635e;
            border-radius:11px;
            padding:8px 13px;
            font-size:12px;
            font-weight:700;
        }

        .cal-btn:hover {
            background:#eaf3ef;
            color:#20584f;
        }

        .cal-btn.active {
            background:#20584f;
            color:white;
            border-color:#20584f;
        }

        .calendar-scroll {
            overflow-x:auto;
        }

        .week-grid {
            min-width:950px;
            display:grid;
            grid-template-columns:72px repeat(7,1fr);
        }

        .week-head {
            min-height:72px;
            background:#faf9f4;
            border-bottom:1px solid #eee7d8;
            border-right:1px solid #eee7d8;
            padding:12px 8px;
            text-align:center;
        }

        .week-head small {
            display:block;
            color:#78837e;
            font-size:10px;
            font-weight:700;
        }

        .week-head strong {
            display:block;
            margin-top:5px;
            font-size:17px;
        }

        .week-head.today {
            background:#eaf3ef;
            color:#20584f;
        }

        .time-cell {
            height:78px;
            border-right:1px solid #eee7d8;
            border-bottom:1px solid #eee7d8;
            display:flex;
            justify-content:center;
            align-items:flex-start;
            padding-top:10px;
            color:#78837e;
            font-size:10px;
            font-weight:700;
            background:#faf9f4;
        }

        .day-cell {
            height:78px;
            border-right:1px solid #eee7d8;
            border-bottom:1px solid #eee7d8;
            padding:5px;
            position:relative;
            background:white;
        }

        .day-cell.today {
            background:#fbfdfc;
        }

        .appointment {
            width:100%;
            height:68px;
            border-radius:10px;
            padding:8px;
            cursor:pointer;
            overflow:hidden;
            transition:.18s;
        }

        .appointment:hover {
            transform:translateY(-1px);
            box-shadow:0 5px 14px rgba(32,88,79,.12);
        }

        .appointment strong {
            display:block;
            font-size:10px;
            margin-bottom:3px;
        }

        .appointment span {
            display:block;
            font-size:9px;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .appointment.pending {
            background:#fff7d7;
            color:#765d00;
            border-left:3px solid #f5c84b;
        }

        .appointment.confirmed {
            background:#eaf3ef;
            color:#20584f;
            border-left:3px solid #20584f;
        }

        .appointment.completed {
            background:#edf1f0;
            color:#60706c;
            border-left:3px solid #78837e;
        }

        .appointment.cancelled {
            background:#fff0ed;
            color:#a24f43;
            border-left:3px solid #c65b4a;
        }

        .legend {
            padding:16px 22px;
            border-top:1px solid #eee7d8;
            display:flex;
            gap:18px;
            flex-wrap:wrap;
        }

        .legend-item {
            display:flex;
            align-items:center;
            gap:7px;
            font-size:11px;
            color:#78837e;
        }

        .legend-dot {
            width:10px;
            height:10px;
            border-radius:50%;
        }

        .dot-pending {
            background:#f5c84b;
        }

        .dot-confirmed {
            background:#20584f;
        }

        .dot-completed {
            background:#78837e;
        }

        .dot-cancelled {
            background:#c65b4a;
        }

        .month-grid {
            padding:20px;
            display:grid;
            grid-template-columns:repeat(7,1fr);
            gap:1px;
            background:#e6dcc2;
        }

        .month-head {
            background:#20584f;
            color:white;
            padding:12px;
            text-align:center;
            font-size:11px;
            font-weight:800;
        }

        .month-cell {
            background:white;
            min-height:125px;
            padding:9px;
        }

        .month-cell.muted {
            background:#faf9f4;
            color:#c2c6c3;
        }

        .month-cell.today {
            box-shadow:inset 0 0 0 2px #f5c84b;
        }

        .month-number {
            font-size:12px;
            font-weight:800;
            margin-bottom:7px;
        }

        .month-event {
            border-radius:7px;
            padding:5px 7px;
            margin-bottom:4px;
            font-size:9px;
            cursor:pointer;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .month-event.pending {
            background:#fff7d7;
            color:#765d00;
        }

        .month-event.confirmed {
            background:#eaf3ef;
            color:#20584f;
        }

        .month-event.completed {
            background:#edf1f0;
            color:#60706c;
        }

        .month-event.cancelled {
            background:#fff0ed;
            color:#a24f43;
        }

        .appointment-modal-icon {
            width:52px;
            height:52px;
            border-radius:16px;
            background:#eaf3ef;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:22px;
            color:#20584f;
        }

        .detail-row {
            display:flex;
            justify-content:space-between;
            gap:20px;
            padding:11px 0;
            border-bottom:1px solid #eee7d8;
            font-size:13px;
        }

        .detail-row span:first-child {
            color:#78837e;
        }

        .detail-row strong {
            text-align:right;
        }

        .modal-content {
            border:0;
            border-radius:24px;
            overflow:hidden;
        }

        .modal-header {
            background:#20584f;
            color:white;
            border:0;
        }

        .modal-header .btn-close {
            filter:brightness(0) invert(1);
        }

        .form-control,
        .form-select {
            border-radius:12px;
            padding:11px 13px;
            border-color:#e6dcc2;
        }

        .form-control:focus,
        .form-select:focus {
            border-color:#20584f;
            box-shadow:0 0 0 .2rem rgba(32,88,79,.10);
        }

        .required {
            color:#c65b4a;
        }

        .status-badge {
            display:inline-block;
            padding:5px 10px;
            border-radius:20px;
            font-size:11px;
            font-weight:700;
        }

        .status-badge.pending {
            background:#fff7d7;
            color:#765d00;
        }

        .status-badge.confirmed {
            background:#eaf3ef;
            color:#20584f;
        }

        .status-badge.completed {
            background:#edf1f0;
            color:#60706c;
        }

        .status-badge.cancelled {
            background:#fff0ed;
            color:#a24f43;
        }

        @media(max-width:1000px) {
            .summary-grid {
                grid-template-columns:repeat(2,1fr);
            }
        }

        @media(max-width:650px) {
            .summary-grid {
                grid-template-columns:1fr;
            }

            .heading {
                flex-direction:column;
                align-items:flex-start;
                gap:15px;
            }

            .heading .btn-owner {
                width:100%;
            }

            .month-grid {
                min-width:800px;
            }
        }
    </style>
</head>

<body>

<!-- NAVBAR ĐÃ SỬA LỖI -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        
        <!-- 1. TỔNG QUAN -->
        <li class="nav-item">
          <a class="app-nav-link active" href="{{ url('/landlord') }}">Tổng quan</a>
        </li>

        <!-- 2. QUẢN LÝ TÀI SẢN -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Nhà &amp; Phòng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
            <li>
              <a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a>
            </li>
          </ul>
        </li>

        <!-- 3. KHÁCH THUÊ -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Khách &amp; Hợp đồng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop2">
            <li>
              <a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a>
            </li>
          </ul>
        </li>

        <!-- 4. TÀI CHÍNH -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tài chính
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop3">
            <li>
              <a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.utilities.index') }}">⚡ Điện nước</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route( 'owner.payments.index') }}">💰 Giao dịch</a>
            </li>
          </ul>
        </li>

        <!-- 5. VẬN HÀNH & TƯƠNG TÁC -->
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Vận hành
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop4">
            <li>
              <a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.appointments.index') }}">📅 Lịch xem</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ url('/owner/maintenance') }}">🛠️ Sửa chữa</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.reviews.index') }}">⭐ Đánh giá</a>
            </li>
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


<main class="owner-main">

    <div class="heading">

        <div>

            <div class="eyebrow">
                Viewing appointments
            </div>

            <h1>
                Lịch xem phòng
            </h1>

            <p>
                Quản lý lịch hẹn xem phòng của khách thuê.
            </p>

        </div>

        <button
            type="button"
            class="btn-owner"
            data-bs-toggle="modal"
            data-bs-target="#createAppointmentModal">

            ＋ Tạo lịch

        </button>

    </div>


    <!-- SUMMARY -->

    <div class="summary-grid">

        <div class="summary-card">

            <div class="summary-card-top">

                <span>
                    Tổng lịch
                </span>

                <div class="summary-icon">
                    📅
                </div>

            </div>

            <strong id="totalCount">
                0
            </strong>

            <span>
                Tất cả lịch xem
            </span>

        </div>


        <div class="summary-card">

            <div class="summary-card-top">

                <span>
                    Chờ xác nhận
                </span>

                <div class="summary-icon">
                    ⏳
                </div>

            </div>

            <strong id="pendingCount">
                0
            </strong>

            <span>
                Cần xử lý
            </span>

        </div>


        <div class="summary-card">

            <div class="summary-card-top">

                <span>
                    Đã xác nhận
                </span>

                <div class="summary-icon">
                    ✓
                </div>

            </div>

            <strong id="confirmedCount">
                0
            </strong>

            <span>
                Lịch sắp diễn ra
            </span>

        </div>


        <div class="summary-card">

            <div class="summary-card-top">

                <span>
                    Hoàn thành
                </span>

                <div class="summary-icon">
                    ★
                </div>

            </div>

            <strong id="completedCount">
                0
            </strong>

            <span>
                Đã xem phòng
            </span>

        </div>

    </div>


    <!-- CALENDAR -->

    <section class="calendar-shell">

        <div class="calendar-toolbar">

            <button
                type="button"
                class="cal-btn"
                id="prevBtn">

                ‹

            </button>


            <button
                type="button"
                class="cal-btn"
                id="todayBtn">

                Hôm nay

            </button>


            <button
                type="button"
                class="cal-btn"
                id="nextBtn">

                ›

            </button>


            <h2 id="calendarTitle">
                Tháng 8, 2026
            </h2>


            <div class="ms-auto d-flex gap-1">

                <button
                    type="button"
                    class="cal-btn active"
                    id="weekBtn">

                    Tuần

                </button>


                <button
                    type="button"
                    class="cal-btn"
                    id="monthBtn">

                    Tháng

                </button>

            </div>

        </div>


        <div id="calendarContent"></div>


        <div class="legend">

            <div class="legend-item">
                <span class="legend-dot dot-pending"></span>
                Chờ xác nhận
            </div>

            <div class="legend-item">
                <span class="legend-dot dot-confirmed"></span>
                Đã xác nhận
            </div>

            <div class="legend-item">
                <span class="legend-dot dot-completed"></span>
                Hoàn thành
            </div>

            <div class="legend-item">
                <span class="legend-dot dot-cancelled"></span>
                Đã hủy
            </div>

        </div>

    </section>

</main>


<!-- =====================================================
     MODAL TẠO LỊCH
===================================================== -->

<div
    class="modal fade"
    id="createAppointmentModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Tạo lịch xem phòng
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <form id="createAppointmentForm">

                <div class="modal-body p-4">

                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Tên khách thuê
                            <span class="required">*</span>
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            id="createTenant"
                            placeholder="VD: Nguyễn Văn An"
                            required>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Số điện thoại
                            <span class="required">*</span>
                        </label>

                        <input
                            type="tel"
                            class="form-control"
                            id="createPhone"
                            placeholder="VD: 0901234567"
                            required>

                    </div>


                    <div class="mb-3">

                        <label class="form-label fw-semibold">
                            Phòng
                            <span class="required">*</span>
                        </label>

                        <select
                            class="form-select"
                            id="createRoom"
                            required>

                            <option value="">
                                -- Chọn phòng --
                            </option>

                            <option value="Phòng 101">
                                Phòng 101
                            </option>

                            <option value="Phòng 102">
                                Phòng 102
                            </option>

                            <option value="Phòng 104">
                                Phòng 104
                            </option>

                            <option value="Phòng 106">
                                Phòng 106
                            </option>

                            <option value="Phòng 203">
                                Phòng 203
                            </option>

                            <option value="Phòng 205">
                                Phòng 205
                            </option>

                            <option value="Phòng 301">
                                Phòng 301
                            </option>

                            <option value="Phòng 302">
                                Phòng 302
                            </option>

                        </select>

                    </div>


                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Ngày xem
                                <span class="required">*</span>
                            </label>

                            <input
                                type="date"
                                class="form-control"
                                id="createDate"
                                required>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="form-label fw-semibold">
                                Giờ xem
                                <span class="required">*</span>
                            </label>

                            <input
                                type="time"
                                class="form-control"
                                id="createTime"
                                min="07:00"
                                max="18:00"
                                required>

                        </div>

                    </div>


                    <div class="mb-2">

                        <label class="form-label fw-semibold">
                            Lời nhắn
                        </label>

                        <textarea
                            class="form-control"
                            id="createMessage"
                            rows="3"
                            placeholder="Nhập lời nhắn hoặc ghi chú..."></textarea>

                    </div>

                    <div class="small text-muted mt-2">
                        Lịch mới sẽ ở trạng thái <strong>Chờ xác nhận</strong>.
                    </div>

                </div>


                <div class="modal-footer border-0 px-4 pb-4">

                    <button
                        type="button"
                        class="btn btn-light rounded-3"
                        data-bs-dismiss="modal">

                        Hủy

                    </button>


                    <button
                        type="submit"
                        class="btn-owner">

                        Tạo lịch

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


<!-- =====================================================
     MODAL CHI TIẾT
===================================================== -->

<div
    class="modal fade"
    id="appointmentModal"
    tabindex="-1"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title fw-bold">
                    Chi tiết lịch xem
                </h5>

                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body p-4">

                <div class="d-flex align-items-center gap-3 mb-4">

                    <div class="appointment-modal-icon">
                        📅
                    </div>

                    <div>

                        <div class="text-muted small">
                            Lịch xem phòng
                        </div>

                        <h5
                            class="fw-bold mb-0"
                            id="modalRoom">
                            Phòng 101
                        </h5>

                    </div>

                </div>


                <div class="detail-row">

                    <span>
                        Khách thuê
                    </span>

                    <strong id="modalTenant"></strong>

                </div>


                <div class="detail-row">

                    <span>
                        Ngày
                    </span>

                    <strong id="modalDate"></strong>

                </div>


                <div class="detail-row">

                    <span>
                        Thời gian
                    </span>

                    <strong id="modalTime"></strong>

                </div>


                <div class="detail-row">

                    <span>
                        Số điện thoại
                    </span>

                    <strong id="modalPhone"></strong>

                </div>


                <div class="detail-row">

                    <span>
                        Trạng thái
                    </span>

                    <strong id="modalStatus"></strong>

                </div>


                <div class="mt-4">

                    <div class="text-muted small mb-2">
                        Lời nhắn
                    </div>

                    <div
                        class="p-3 rounded-3"
                        style="background:#f8f5eb"
                        id="modalMessage">
                    </div>

                </div>

            </div>


            <div class="modal-footer border-0 px-4 pb-4">

                <button
                    type="button"
                    class="btn btn-light rounded-3"
                    data-bs-dismiss="modal">

                    Đóng

                </button>


                <button
                    type="button"
                    class="btn btn-outline-danger rounded-3"
                    id="rejectBtn">

                    Từ chối

                </button>


                <button
                    type="button"
                    class="btn-owner"
                    id="confirmBtn">

                    Xác nhận lịch

                </button>

            </div>

        </div>

    </div>

</div>


<script>

    /* =====================================================
       KEY LOCAL STORAGE
    ===================================================== */

    const appointmentStorageKey =
        'troOiOwnerAppointments';


    /* =====================================================
       DỮ LIỆU LỊCH MẶC ĐỊNH
    ===================================================== */

    const defaultAppointments = [

        {
            id: 1,
            date: '2026-08-26',
            time: '14:00',
            room: 'Phòng 101',
            tenant: 'Nguyễn Văn An',
            phone: '0901234567',
            status: 'pending',
            message: 'Em muốn xem phòng vào buổi chiều.'
        },

        {
            id: 2,
            date: '2026-08-27',
            time: '15:00',
            room: 'Phòng 203',
            tenant: 'Trần Minh Khoa',
            phone: '0912345678',
            status: 'confirmed',
            message: 'Anh cho em xem phòng và hỏi thêm về tiền điện nước.'
        },

        {
            id: 3,
            date: '2026-08-28',
            time: '09:00',
            room: 'Phòng 102',
            tenant: 'Lê Thị Hương',
            phone: '0923456789',
            status: 'confirmed',
            message: 'Em muốn xem phòng vào sáng thứ sáu.'
        },

        {
            id: 4,
            date: '2026-08-28',
            time: '16:00',
            room: 'Phòng 205',
            tenant: 'Phạm Quốc Bảo',
            phone: '0934567890',
            status: 'completed',
            message: 'Đã đến xem phòng.'
        },

        {
            id: 5,
            date: '2026-08-29',
            time: '10:00',
            room: 'Phòng 301',
            tenant: 'Nguyễn Ngọc Mai',
            phone: '0945678901',
            status: 'pending',
            message: 'Cho em hỏi phòng còn trống không ạ?'
        },

        {
            id: 6,
            date: '2026-08-30',
            time: '09:30',
            room: 'Phòng 302',
            tenant: 'Võ Thành Đạt',
            phone: '0956789012',
            status: 'confirmed',
            message: 'Em sẽ đến đúng giờ ạ.'
        },

        {
            id: 7,
            date: '2026-08-30',
            time: '14:00',
            room: 'Phòng 104',
            tenant: 'Hoàng Gia Hân',
            phone: '0967890123',
            status: 'cancelled',
            message: 'Khách đã hủy lịch.'
        },

        {
            id: 8,
            date: '2026-08-31',
            time: '15:30',
            room: 'Phòng 106',
            tenant: 'Đặng Minh Quân',
            phone: '0978901234',
            status: 'confirmed',
            message: 'Muốn xem phòng và khu vực để xe.'
        }

    ];


    /* =====================================================
       LẤY DỮ LIỆU TỪ LOCAL STORAGE
    ===================================================== */

    let appointments = [];


    function loadAppointments() {

        const saved =
            localStorage.getItem(
                appointmentStorageKey
            );


        if (saved) {

            try {

                const parsed =
                    JSON.parse(saved);


                if (Array.isArray(parsed)) {

                    appointments = parsed;

                    return;

                }

            } catch (error) {

                console.warn(
                    'Không thể đọc dữ liệu lịch:',
                    error
                );

            }

        }


        appointments =
            JSON.parse(
                JSON.stringify(defaultAppointments)
            );


        saveAppointments();

    }


    /* =====================================================
       LƯU DỮ LIỆU
    ===================================================== */

    function saveAppointments() {

        localStorage.setItem(
            appointmentStorageKey,
            JSON.stringify(appointments)
        );

    }


    /* =====================================================
       NGÀY HIỆN TẠI DEMO
    ===================================================== */

    const demoToday =
        new Date(2026, 7, 27);


    let currentDate =
        new Date(2026, 7, 27);


    let currentView =
        'week';


    let selectedAppointmentId =
        null;


    const calendarContent =
        document.getElementById(
            'calendarContent'
        );


    const calendarTitle =
        document.getElementById(
            'calendarTitle'
        );


    const statusNames = {

        pending: 'Chờ xác nhận',

        confirmed: 'Đã xác nhận',

        completed: 'Hoàn thành',

        cancelled: 'Đã hủy'

    };


    /* =====================================================
       HÀM NGÀY
    ===================================================== */

    function pad(n) {

        return String(n).padStart(2, '0');

    }


    function dateKey(date) {

        return date.getFullYear() +
            '-' +
            pad(date.getMonth() + 1) +
            '-' +
            pad(date.getDate());

    }


    function formatDate(date) {

        return pad(date.getDate()) +
            '/' +
            pad(date.getMonth() + 1) +
            '/' +
            date.getFullYear();

    }


    function addDays(date, days) {

        const result =
            new Date(date);

        result.setDate(
            result.getDate() + days
        );

        return result;

    }


    function getMonday(date) {

        const result =
            new Date(date);

        const day =
            result.getDay();

        const diff =
            day === 0
                ? -6
                : 1 - day;

        result.setDate(
            result.getDate() + diff
        );

        result.setHours(
            0,
            0,
            0,
            0
        );

        return result;

    }


    function sameDate(a, b) {

        return dateKey(a) ===
            dateKey(b);

    }


    /* =====================================================
       LẤY LỊCH THEO NGÀY
    ===================================================== */

    function getAppointments(date) {

        return appointments
            .filter(
                item =>
                    item.date === dateKey(date)
            )
            .sort(
                (a, b) =>
                    a.time.localeCompare(b.time)
            );

    }


    /* =====================================================
       THỐNG KÊ
    ===================================================== */

    function renderSummary() {

        document.getElementById(
            'totalCount'
        ).textContent =
            appointments.length;


        document.getElementById(
            'pendingCount'
        ).textContent =
            appointments.filter(
                item =>
                    item.status === 'pending'
            ).length;


        document.getElementById(
            'confirmedCount'
        ).textContent =
            appointments.filter(
                item =>
                    item.status === 'confirmed'
            ).length;


        document.getElementById(
            'completedCount'
        ).textContent =
            appointments.filter(
                item =>
                    item.status === 'completed'
            ).length;

    }


    /* =====================================================
       MỞ CHI TIẾT
    ===================================================== */

    function openAppointment(item) {

        selectedAppointmentId =
            item.id;


        document.getElementById(
            'modalRoom'
        ).textContent =
            item.room;


        document.getElementById(
            'modalTenant'
        ).textContent =
            item.tenant;


        const itemDate =
            new Date(
                item.date + 'T00:00:00'
            );


        document.getElementById(
            'modalDate'
        ).textContent =
            formatDate(itemDate);


        document.getElementById(
            'modalTime'
        ).textContent =
            item.time;


        document.getElementById(
            'modalPhone'
        ).textContent =
            item.phone;


        const statusElement =
            document.getElementById(
                'modalStatus'
            );


        statusElement.innerHTML =
            `<span class="status-badge ${item.status}">
                ${statusNames[item.status]}
            </span>`;


        document.getElementById(
            'modalMessage'
        ).textContent =
            item.message ||
            'Không có lời nhắn.';


        /*
         * Chỉ lịch đang chờ mới có
         * nút Xác nhận / Từ chối.
         */

        const canProcess =
            item.status === 'pending';


        document.getElementById(
            'confirmBtn'
        ).style.display =
            canProcess
                ? ''
                : 'none';


        document.getElementById(
            'rejectBtn'
        ).style.display =
            canProcess
                ? ''
                : 'none';


        const modal =
            bootstrap.Modal.getOrCreateInstance(
                document.getElementById(
                    'appointmentModal'
                )
            );


        modal.show();

    }


    /* =====================================================
       MỞ LỊCH BẰNG ID
    ===================================================== */

    function openAppointmentById(id) {

        const item =
            appointments.find(
                appointment =>
                    Number(appointment.id) ===
                    Number(id)
            );


        if (item) {

            openAppointment(item);

        }

    }


    /* =====================================================
       RENDER TUẦN
    ===================================================== */

    function renderWeek() {

        const monday =
            getMonday(currentDate);


        const sunday =
            addDays(
                monday,
                6
            );


        calendarTitle.textContent =
            `${pad(monday.getDate())}/${pad(monday.getMonth() + 1)}
             — ${pad(sunday.getDate())}/${pad(sunday.getMonth() + 1)}/${sunday.getFullYear()}`;


        let html = `

            <div class="calendar-scroll">

                <div class="week-grid">

                    <div class="week-head">

                        <small>
                            GIỜ
                        </small>

                    </div>

        `;


        const weekdays = [

            'Thứ 2',
            'Thứ 3',
            'Thứ 4',
            'Thứ 5',
            'Thứ 6',
            'Thứ 7',
            'Chủ nhật'

        ];


        for (
            let i = 0;
            i < 7;
            i++
        ) {

            const date =
                addDays(
                    monday,
                    i
                );


            html += `

                <div class="week-head
                    ${sameDate(date, demoToday) ? 'today' : ''}">

                    <small>
                        ${weekdays[i]}
                    </small>

                    <strong>
                        ${date.getDate()}
                    </strong>

                </div>

            `;

        }


        const hours = [];


        for (
            let h = 7;
            h <= 18;
            h++
        ) {

            hours.push(h);

        }


        hours.forEach(hour => {

            html += `

                <div class="time-cell">
                    ${pad(hour)}:00
                </div>

            `;


            for (
                let day = 0;
                day < 7;
                day++
            ) {

                const date =
                    addDays(
                        monday,
                        day
                    );


                const events =
                    getAppointments(date)
                        .filter(item => {

                            return parseInt(
                                item.time
                                    .split(':')[0]
                            ) === hour;

                        });


                html += `

                    <div class="day-cell
                        ${sameDate(date, demoToday) ? 'today' : ''}">

                `;


                events.forEach(item => {

                    html += `

                        <div
                            class="appointment ${item.status}"
                            onclick="openAppointmentById(${item.id})">

                            <strong>
                                ${item.time} · ${item.room}
                            </strong>

                            <span>
                                ${item.tenant}
                            </span>

                            <span>
                                ${statusNames[item.status]}
                            </span>

                        </div>

                    `;

                });


                html += `
                    </div>
                `;

            }

        });


        html += `

                </div>

            </div>

        `;


        calendarContent.innerHTML =
            html;

    }


    /* =====================================================
       RENDER THÁNG
    ===================================================== */

    function renderMonth() {

        const year =
            currentDate.getFullYear();


        const month =
            currentDate.getMonth();


        const firstDay =
            new Date(
                year,
                month,
                1
            );


        const monday =
            getMonday(firstDay);


        calendarTitle.textContent =
            firstDay.toLocaleDateString(
                'vi-VN',
                {
                    month: 'long',
                    year: 'numeric'
                }
            );


        let html = `

            <div class="month-grid">

                <div class="month-head">
                    THỨ 2
                </div>

                <div class="month-head">
                    THỨ 3
                </div>

                <div class="month-head">
                    THỨ 4
                </div>

                <div class="month-head">
                    THỨ 5
                </div>

                <div class="month-head">
                    THỨ 6
                </div>

                <div class="month-head">
                    THỨ 7
                </div>

                <div class="month-head">
                    CHỦ NHẬT
                </div>

        `;


        for (
            let i = 0;
            i < 42;
            i++
        ) {

            const date =
                addDays(
                    monday,
                    i
                );


            const muted =
                date.getMonth() !== month;


            html += `

                <div class="month-cell
                    ${muted ? 'muted' : ''}
                    ${sameDate(date, demoToday) ? 'today' : ''}">

                    <div class="month-number">
                        ${date.getDate()}
                    </div>

            `;


            const events =
                getAppointments(date);


            events
                .slice(0, 3)
                .forEach(item => {

                    html += `

                        <div
                            class="month-event ${item.status}"
                            onclick="openAppointmentById(${item.id})">

                            ${item.time} · ${item.room}

                        </div>

                    `;

                });


            if (events.length > 3) {

                html += `

                    <div
                        class="text-muted"
                        style="font-size:9px">

                        +${events.length - 3} lịch khác

                    </div>

                `;

            }


            html += `
                </div>
            `;

        }


        html += `
            </div>
        `;


        calendarContent.innerHTML =
            html;

    }


    /* =====================================================
       RENDER
    ===================================================== */

    function render() {

        renderSummary();


        if (
            currentView === 'week'
        ) {

            renderWeek();

        } else {

            renderMonth();

        }

    }


    /* =====================================================
       NÚT TRƯỚC
    ===================================================== */

    document.getElementById(
        'prevBtn'
    ).addEventListener(
        'click',
        function() {

            if (
                currentView === 'week'
            ) {

                currentDate =
                    addDays(
                        currentDate,
                        -7
                    );

            } else {

                currentDate =
                    new Date(
                        currentDate.getFullYear(),
                        currentDate.getMonth() - 1,
                        1
                    );

            }


            render();

        }
    );


    /* =====================================================
       NÚT SAU
    ===================================================== */

    document.getElementById(
        'nextBtn'
    ).addEventListener(
        'click',
        function() {

            if (
                currentView === 'week'
            ) {

                currentDate =
                    addDays(
                        currentDate,
                        7
                    );

            } else {

                currentDate =
                    new Date(
                        currentDate.getFullYear(),
                        currentDate.getMonth() + 1,
                        1
                    );

            }


            render();

        }
    );


    /* =====================================================
       HÔM NAY
    ===================================================== */

    document.getElementById(
        'todayBtn'
    ).addEventListener(
        'click',
        function() {

            currentDate =
                new Date(demoToday);


            render();

        }
    );


    /* =====================================================
       CHẾ ĐỘ TUẦN
    ===================================================== */

    document.getElementById(
        'weekBtn'
    ).addEventListener(
        'click',
        function() {

            currentView =
                'week';


            document.getElementById(
                'weekBtn'
            ).classList.add('active');


            document.getElementById(
                'monthBtn'
            ).classList.remove('active');


            render();

        }
    );


    /* =====================================================
       CHẾ ĐỘ THÁNG
    ===================================================== */

    document.getElementById(
        'monthBtn'
    ).addEventListener(
        'click',
        function() {

            currentView =
                'month';


            document.getElementById(
                'monthBtn'
            ).classList.add('active');


            document.getElementById(
                'weekBtn'
            ).classList.remove('active');


            render();

        }
    );


    /* =====================================================
       XÁC NHẬN LỊCH
    ===================================================== */

    document.getElementById(
        'confirmBtn'
    ).addEventListener(
        'click',
        function() {

            const appointment =
                appointments.find(
                    item =>
                        Number(item.id) ===
                        Number(selectedAppointmentId)
                );


            if (!appointment) {

                return;

            }


            if (
                appointment.status !== 'pending'
            ) {

                return;

            }


            appointment.status =
                'confirmed';


            /*
             * LƯU TRẠNG THÁI MỚI
             */

            saveAppointments();


            /*
             * Cập nhật lịch + thống kê
             */

            render();


            const modal =
                bootstrap.Modal.getInstance(
                    document.getElementById(
                        'appointmentModal'
                    )
                );


            if (modal) {

                modal.hide();

            }


            alert(
                'Đã xác nhận lịch xem phòng.'
            );

        }
    );


    /* =====================================================
       TỪ CHỐI LỊCH
    ===================================================== */

    document.getElementById(
        'rejectBtn'
    ).addEventListener(
        'click',
        function() {

            const appointment =
                appointments.find(
                    item =>
                        Number(item.id) ===
                        Number(selectedAppointmentId)
                );


            if (!appointment) {

                return;

            }


            if (
                appointment.status !== 'pending'
            ) {

                return;

            }


            appointment.status =
                'cancelled';


            /*
             * LƯU TRẠNG THÁI MỚI
             */

            saveAppointments();


            /*
             * Cập nhật lịch + thống kê
             */

            render();


            const modal =
                bootstrap.Modal.getInstance(
                    document.getElementById(
                        'appointmentModal'
                    )
                );


            if (modal) {

                modal.hide();

            }


            alert(
                'Đã từ chối lịch xem phòng.'
            );

        }
    );


    /* =====================================================
       TẠO LỊCH MỚI
    ===================================================== */

    document.getElementById(
        'createAppointmentForm'
    ).addEventListener(
        'submit',
        function(event) {

            event.preventDefault();


            const tenant =
                document.getElementById(
                    'createTenant'
                ).value.trim();


            const phone =
                document.getElementById(
                    'createPhone'
                ).value.trim();


            const room =
                document.getElementById(
                    'createRoom'
                ).value;


            const date =
                document.getElementById(
                    'createDate'
                ).value;


            const time =
                document.getElementById(
                    'createTime'
                ).value;


            const message =
                document.getElementById(
                    'createMessage'
                ).value.trim();


            /*
             * KIỂM TRA DỮ LIỆU
             */

            if (
                !tenant ||
                !phone ||
                !room ||
                !date ||
                !time
            ) {

                alert(
                    'Vui lòng nhập đầy đủ thông tin bắt buộc.'
                );

                return;

            }


            /*
             * Chỉ cho tạo lịch trong khung
             * giờ đang hiển thị.
             */

            const selectedHour =
                parseInt(
                    time.split(':')[0]
                );


            if (
                selectedHour < 7 ||
                selectedHour > 18
            ) {

                alert(
                    'Vui lòng chọn giờ từ 07:00 đến 18:00.'
                );

                return;

            }


            /*
             * Kiểm tra trùng phòng + ngày + giờ
             */

            const duplicate =
                appointments.some(
                    item =>
                        item.room === room &&
                        item.date === date &&
                        item.time === time &&
                        item.status !== 'cancelled'
                );


            if (duplicate) {

                alert(
                    'Phòng này đã có lịch vào thời gian bạn chọn.'
                );

                return;

            }


            /*
             * Tạo ID mới an toàn.
             */

            const newId =
                appointments.length > 0
                    ? Math.max(
                        ...appointments.map(
                            item =>
                                Number(item.id) || 0
                        )
                    ) + 1
                    : 1;


            /*
             * Tạo lịch.
             */

            const newAppointment = {

                id: newId,

                date: date,

                time: time,

                room: room,

                tenant: tenant,

                phone: phone,

                status: 'pending',

                message:
                    message ||
                    'Không có lời nhắn.'

            };


            /*
             * THÊM VÀO MẢNG
             */

            appointments.push(
                newAppointment
            );


            /*
             * QUAN TRỌNG:
             * LƯU NGAY VÀO LOCAL STORAGE
             */

            saveAppointments();


            /*
             * Chuyển tới đúng ngày vừa tạo.
             */

            const parts =
                date.split('-');


            currentDate =
                new Date(
                    Number(parts[0]),
                    Number(parts[1]) - 1,
                    Number(parts[2])
                );


            /*
             * Sau khi tạo luôn hiển thị tuần.
             */

            currentView =
                'week';


            document.getElementById(
                'weekBtn'
            ).classList.add('active');


            document.getElementById(
                'monthBtn'
            ).classList.remove('active');


            /*
             * VẼ LẠI LỊCH NGAY LẬP TỨC
             */

            render();


            /*
             * Đóng modal.
             */

            const createModal =
                bootstrap.Modal.getInstance(
                    document.getElementById(
                        'createAppointmentModal'
                    )
                );


            if (createModal) {

                createModal.hide();

            }


            /*
             * Reset form.
             */

            document.getElementById(
                'createAppointmentForm'
            ).reset();


            /*
             * Thông báo.
             */

            alert(
                'Đã tạo lịch xem phòng thành công. Lịch đang chờ xác nhận.'
            );

        }
    );


    /* =====================================================
       LOAD BAN ĐẦU
    ===================================================== */

    loadAppointments();

    render();

</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>


