@php
    /*
    |--------------------------------------------------------------------------
    | DỮ LIỆU MOCK PHÒNG
    | Sau này khi nối Database chỉ cần thay phần này bằng Room Model.
    |--------------------------------------------------------------------------
    */

    $rooms = [
        1 => [
            'title' => 'Phòng trọ máy lạnh gần Q.7',
            'address' => 'Đường Nguyễn Thị Thập, Quận 7, TP.HCM',
            'price' => '2,8 triệu',
            'area' => '22 m²',
            'people' => '2 người',
            'bathroom' => 'WC riêng',
            'type' => 'Phòng trọ',
            'images' => [
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80',
            ],
            'landlord' => 'Anh Tuấn',
            'phone' => '09xx xxx 123',
        ],

        2 => [
            'title' => 'Căn hộ mini full nội thất Quận 4',
            'address' => 'Đường Hoàng Diệu, Quận 4, TP.HCM',
            'price' => '4,2 triệu',
            'area' => '35 m²',
            'people' => '2 người',
            'bathroom' => 'WC riêng',
            'type' => 'Căn hộ mini',
            'images' => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1000&q=80',
            ],
            'landlord' => 'Chị Lan',
            'phone' => '09xx xxx 456',
        ],

        3 => [
            'title' => 'Studio hiện đại gần trung tâm',
            'address' => 'Đường Nguyễn Hữu Cảnh, Bình Thạnh, TP.HCM',
            'price' => '5,5 triệu',
            'area' => '40 m²',
            'people' => '2 người',
            'bathroom' => 'WC riêng',
            'type' => 'Studio',
            'images' => [
                'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1000&q=80',
            ],
            'landlord' => 'Anh Minh',
            'phone' => '09xx xxx 789',
        ],

        4 => [
            'title' => 'Phòng rộng có ban công',
            'address' => 'Đường Lê Văn Lương, Quận 7, TP.HCM',
            'price' => '3,6 triệu',
            'area' => '28 m²',
            'people' => '2 người',
            'bathroom' => 'WC riêng',
            'type' => 'Phòng trọ',
            'images' => [
                'https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=1000&q=80',
            ],
            'landlord' => 'Chị Hương',
            'phone' => '09xx xxx 321',
        ],

        5 => [
            'title' => 'Phòng trọ giá tốt gần trường',
            'address' => 'Đường D2, Bình Thạnh, TP.HCM',
            'price' => '2,5 triệu',
            'area' => '20 m²',
            'people' => '2 người',
            'bathroom' => 'WC riêng',
            'type' => 'Phòng trọ',
            'images' => [
                'https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=1000&q=80',
            ],
            'landlord' => 'Anh Phúc',
            'phone' => '09xx xxx 654',
        ],

        6 => [
            'title' => 'Căn hộ cao cấp đầy đủ tiện nghi',
            'address' => 'Đường Nguyễn Tất Thành, Quận 4, TP.HCM',
            'price' => '6,8 triệu',
            'area' => '50 m²',
            'people' => '3 người',
            'bathroom' => 'WC riêng',
            'type' => 'Căn hộ',
            'images' => [
                'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1000&q=80',
            ],
            'landlord' => 'Chị Mai',
            'phone' => '09xx xxx 987',
        ],
    ];


    /*
    |--------------------------------------------------------------------------
    | LẤY ID PHÒNG TỪ URL
    | Ví dụ:
    | /appointments/create?id=2
    |--------------------------------------------------------------------------
    */

    $roomId = (int) request('id', 1);

    $room = $rooms[$roomId] ?? null;
@endphp


<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Trọ Ơi | Đặt lịch xem phòng</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- CSS chính -->

    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}"
    >

</head>


<body>


<!-- =========================================================
     NAVBAR
========================================================= -->

<nav class="navbar navbar-expand-lg app-navbar">

    <div class="container-fluid">

        <a
            class="logo"
            href="{{ route('tenant') }}"
        >
            Trọ <span>Ơi</span>
        </a>


        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainMenu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <div
            class="collapse navbar-collapse"
            id="mainMenu"
        >

            <ul class="navbar-nav mx-auto align-items-lg-center">

                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="{{ route('tenant') }}"
                    >
                        Trang chủ
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="{{ route('rooms.index') }}"
                    >
                        Tìm phòng
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="{{ route('favorites.index') }}"
                    >
                        Yêu thích
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="app-nav-link active"
                        href="{{ route('appointments.index') }}"
                    >
                        Lịch xem phòng
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Hợp đồng
                    </a>
                </li>


                <li class="nav-item">
                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Hóa đơn
                    </a>
                </li>

            </ul>


            <div class="navbar-actions">

                <button
                    class="notif-btn"
                    type="button"
                >
                    🔔
                    <span class="notif-dot"></span>
                </button>


                <a
                    href="#"
                    class="user-chip"
                >

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


    <!-- BACK -->

    <div class="mb-4">

        @if($room)

            <a
                href="{{ route('rooms.show', $roomId) }}"
                class="section-link"
            >
                ← Quay lại phòng
            </a>

        @else

            <a
                href="{{ route('rooms.index') }}"
                class="section-link"
            >
                ← Quay lại danh sách phòng
            </a>

        @endif

    </div>



    <!-- HEADER -->

    <div class="page-header">

        <div>

            <div class="badge-status pending mb-2">
                📅 ĐẶT LỊCH XEM PHÒNG
            </div>


            <h1 class="page-title">
                Chọn thời gian bạn muốn xem phòng
            </h1>


            <div class="page-desc">
                Gửi lịch hẹn để chủ nhà xác nhận thời gian phù hợp.
            </div>

        </div>

    </div>



    <!-- =====================================================
         CHECK ROOM
    ====================================================== -->

    @if($room)

        <div class="row g-4">


            <!-- =================================================
                 LEFT
            ================================================== -->

            <div class="col-lg-5">


                <!-- ROOM -->

                <div class="panel">

                    <div class="panel-head">

                        <h2 class="panel-title">
                            Phòng bạn muốn xem
                        </h2>

                        <span class="badge-status vacant">
                            Còn phòng
                        </span>

                    </div>


                    <!-- IMAGE -->

                    <img
                        src="{{ $room['images'][0] }}"
                        alt="{{ $room['title'] }}"
                        class="room-img"
                        style="height:230px;border-radius:14px;"
                    >


                    <div class="mt-3">


                        <div class="room-badges">

                            <span class="badge-room hot">
                                🔥 HOT
                            </span>

                            <span class="badge-room">
                                ✓ Đã xác thực
                            </span>

                        </div>


                        <div class="room-title">

                            {{ $room['title'] }}

                        </div>


                        <div class="room-address">

                            📍 {{ $room['address'] }}

                        </div>


                        <div class="room-stats">

                            <span>
                                📐 {{ $room['area'] }}
                            </span>

                            <span>
                                👥 {{ $room['people'] }}
                            </span>

                            <span>
                                🚿 {{ $room['bathroom'] }}
                            </span>

                        </div>


                        <div class="room-bottom">

                            <div class="room-price">

                                {{ $room['price'] }}

                                <small>
                                    / tháng
                                </small>

                            </div>


                            <a
                                href="{{ route('rooms.show', $roomId) }}"
                                class="detail-btn text-decoration-none"
                            >
                                Xem phòng
                            </a>

                        </div>

                    </div>

                </div>



                <!-- OWNER -->

                <div class="panel mt-4">

                    <div class="panel-head">

                        <h3 class="panel-title">
                            Thông tin chủ nhà
                        </h3>

                    </div>


                    <div class="d-flex align-items-center gap-3">

                        <div class="avatar-sm">
                            {{ collect(explode(' ', $room['landlord']))->map(fn($word) => strtoupper(substr($word, 0, 1)))->join('') }}
                        </div>


                        <div>

                            <div class="cell-title">
                                {{ $room['landlord'] }}
                            </div>

                            <div class="cell-sub">
                                Chủ nhà
                            </div>

                        </div>

                    </div>


                    <div class="mt-3">

                        <div class="d-flex gap-2 mb-2">

                            <span>
                                📞
                            </span>

                            <span class="cell-sub">
                                {{ $room['phone'] }}
                            </span>

                        </div>


                        <div class="d-flex gap-2">

                            <span>
                                📍
                            </span>

                            <span class="cell-sub">
                                {{ $room['address'] }}
                            </span>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =================================================
                 RIGHT
            ================================================== -->

            <div class="col-lg-7">


                <!-- FORM -->

                <div class="panel">

                    <div class="panel-head">

                        <div>

                            <h2 class="panel-title">
                                Thông tin lịch hẹn
                            </h2>

                            <div class="page-desc">
                                Vui lòng điền đầy đủ thông tin.
                            </div>

                        </div>

                    </div>


                    <form>


                        <!-- ROOM ID -->

                        <input
                            type="hidden"
                            name="room_id"
                            value="{{ $roomId }}"
                        >


                        <!-- DATE + TIME -->

                        <div class="row g-3">


                            <div class="col-md-6">

                                <label
                                    for="appointment_date"
                                    class="form-label fw-bold small"
                                >
                                    Ngày xem phòng
                                </label>


                                <input
                                    type="date"
                                    id="appointment_date"
                                    name="appointment_date"
                                    class="form-control"
                                >


                                <div class="form-text">
                                    Không chọn ngày trong quá khứ.
                                </div>

                            </div>



                            <div class="col-md-6">

                                <label
                                    for="appointment_time"
                                    class="form-label fw-bold small"
                                >
                                    Giờ xem phòng
                                </label>


                                <input
                                    type="time"
                                    id="appointment_time"
                                    name="appointment_time"
                                    class="form-control"
                                >

                            </div>

                        </div>



                        <!-- PHONE -->

                        <div class="mt-3">

                            <label
                                for="phone"
                                class="form-label fw-bold small"
                            >
                                Số điện thoại liên hệ
                            </label>


                            <input
                                type="tel"
                                id="phone"
                                name="phone"
                                class="form-control"
                                placeholder="Nhập số điện thoại của bạn"
                            >


                            <div class="form-text">
                                Chủ nhà sẽ sử dụng số này để liên hệ xác nhận lịch.
                            </div>

                        </div>



                        <!-- MESSAGE -->

                        <div class="mt-3">

                            <label
                                for="message"
                                class="form-label fw-bold small"
                            >
                                Lời nhắn cho chủ nhà
                            </label>


                            <textarea
                                id="message"
                                name="message"
                                rows="5"
                                class="form-control"
                                placeholder="Ví dụ: Em muốn xem phòng vào buổi chiều. Cho em hỏi phòng có chỗ để xe không ạ?"
                            ></textarea>


                            <div class="form-text">
                                Bạn có thể để lại câu hỏi hoặc yêu cầu đặc biệt.
                            </div>

                        </div>



                        <!-- NOTE -->

                        <div class="panel mt-4">

                            <div class="d-flex gap-3">

                                <div class="stat-icon">
                                    💡
                                </div>


                                <div>

                                    <div class="cell-title">
                                        Lưu ý trước khi gửi lịch
                                    </div>


                                    <div class="cell-sub mt-1">

                                        Sau khi gửi, lịch hẹn sẽ ở trạng thái
                                        <strong>Chờ xác nhận</strong>.
                                        Chủ nhà có thể xác nhận, từ chối hoặc đề xuất
                                        một thời gian khác.

                                    </div>

                                </div>

                            </div>

                        </div>



                        <!-- ACTION -->

                        <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">


                            <a
                                href="{{ route('rooms.show', $roomId) }}"
                                class="btn-outline-brand text-decoration-none"
                            >
                                Hủy
                            </a>


                            <button
                                type="submit"
                                class="btn-brand"
                            >
                                📅 Gửi lịch hẹn
                            </button>

                        </div>

                    </form>

                </div>



                <!-- PROCESS -->

                <div class="panel mt-4">

                    <div class="panel-head">

                        <h3 class="panel-title">
                            Quy trình đặt lịch
                        </h3>

                    </div>


                    <div class="row g-3">


                        <div class="col-md-4">

                            <div class="d-flex gap-2">

                                <div class="avatar-sm">
                                    1
                                </div>

                                <div>

                                    <div class="cell-title">
                                        Gửi lịch
                                    </div>

                                    <div class="cell-sub">
                                        Chọn ngày và giờ bạn muốn xem.
                                    </div>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-4">

                            <div class="d-flex gap-2">

                                <div class="avatar-sm">
                                    2
                                </div>

                                <div>

                                    <div class="cell-title">
                                        Chủ nhà xử lý
                                    </div>

                                    <div class="cell-sub">
                                        Xác nhận hoặc đề xuất giờ khác.
                                    </div>

                                </div>

                            </div>

                        </div>



                        <div class="col-md-4">

                            <div class="d-flex gap-2">

                                <div class="avatar-sm">
                                    3
                                </div>

                                <div>

                                    <div class="cell-title">
                                        Đến xem phòng
                                    </div>

                                    <div class="cell-sub">
                                        Lịch hoàn thành sau khi xem phòng.
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>


    @else


        <!-- =================================================
             ROOM NOT FOUND
        ================================================== -->

        <div class="panel text-center py-5">

            <div class="stat-icon mx-auto mb-3">
                🏠
            </div>


            <h2 class="panel-title mb-2">
                Không tìm thấy phòng
            </h2>


            <div class="page-desc mb-4">
                Phòng bạn đang chọn không tồn tại hoặc đã được thay đổi.
            </div>


            <a
                href="{{ route('rooms.index') }}"
                class="btn-brand text-decoration-none d-inline-block"
            >
                ← Chọn phòng khác
            </a>

        </div>

    @endif

</div>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>
</html>