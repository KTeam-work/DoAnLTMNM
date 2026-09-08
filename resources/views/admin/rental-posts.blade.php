<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kiểm duyệt tin đăng - Trọ Ơi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="app-navbar">

    <div class="container-fluid h-100">

        <div class="d-flex align-items-center justify-content-between h-100">

            <!-- LOGO -->

            <a href="{{ url('/admin/dashboard') }}"
               class="logo text-decoration-none">

                Trọ <span>Ơi</span>
                <small>ADMIN</small>

            </a>


            <!-- MENU -->

            <div class="d-flex align-items-center gap-1">

                <a href="{{ url('/admin/dashboard') }}"
                   class="app-nav-link text-decoration-none">
                    Dashboard
                </a>

                <a href="{{ url('/admin/users') }}"
                   class="app-nav-link text-decoration-none">
                    Tài khoản
                </a>

                <a href="{{ url('/admin/rental-posts') }}"
                   class="app-nav-link active text-decoration-none">
                    Kiểm duyệt tin
                </a>

                <a href="{{ url('/admin/statistics') }}"
                   class="app-nav-link text-decoration-none">
                    Thống kê
                </a>

            </div>


            <!-- ADMIN -->

            <div class="navbar-actions">

                <div class="user-chip">

                    <div class="user-avatar">
                        AD
                    </div>

                    <div class="user-meta">

                        <div class="user-name">
                            Admin
                        </div>

                        <div class="user-role">
                            Quản trị viên
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</nav>


<!-- ================= CONTENT ================= -->

<div class="page-wrap">


    <!-- HEADER -->

    <div class="page-header">

        <div>

            <h1 class="page-title">
                Kiểm duyệt tin đăng
            </h1>

            <div class="page-desc">
                Xem xét, phê duyệt hoặc từ chối các tin cho thuê phòng
            </div>

        </div>

    </div>


    <!-- ================= FILTER ================= -->

    <div class="panel mb-4">

        <div class="panel-head">

            <h2 class="panel-title">
                Tìm kiếm và lọc tin đăng
            </h2>

        </div>


        <form method="GET" action="{{ url('/admin/rental-posts') }}">

            <div class="row g-3">

                <!-- Tìm kiếm -->

                <div class="col-12 col-lg-6">

                    <label class="form-label">
                        Tìm kiếm
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Tên tin đăng hoặc tên chủ trọ..."
                        value="{{ request('search') }}"
                    >

                </div>


                <!-- Trạng thái -->

                <div class="col-12 col-md-6 col-lg-3">

                    <label class="form-label">
                        Trạng thái
                    </label>

                    <select name="status" class="form-select">

                        <option value="">
                            Tất cả
                        </option>

                        <option value="pending"
                            {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Chờ duyệt
                        </option>

                        <option value="approved"
                            {{ request('status') == 'approved' ? 'selected' : '' }}>
                            Đã duyệt
                        </option>

                        <option value="rejected"
                            {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            Từ chối
                        </option>

                        <option value="hidden"
                            {{ request('status') == 'hidden' ? 'selected' : '' }}>
                            Đã ẩn
                        </option>

                    </select>

                </div>


                <!-- Sắp xếp -->

                <div class="col-12 col-md-6 col-lg-3">

                    <label class="form-label">
                        Sắp xếp
                    </label>

                    <select name="sort" class="form-select">

                        <option value="newest">
                            Mới nhất
                        </option>

                        <option value="oldest">
                            Cũ nhất
                        </option>

                    </select>

                </div>


                <!-- BUTTON -->

                <div class="col-12">

                    <button type="submit" class="btn-brand">
                        Tìm kiếm
                    </button>

                    <a href="{{ url('/admin/rental-posts') }}"
                       class="btn-outline-brand text-decoration-none">
                        Đặt lại
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- ================= RENTAL POSTS ================= -->

    <div class="panel">

        <div class="panel-head">

            <div>

                <h2 class="panel-title">
                    Danh sách tin đăng
                </h2>

                <div class="page-desc">
                    Các tin đăng được gửi lên hệ thống
                </div>

            </div>

            <span class="badge-status pending">
                14 tin chờ duyệt
            </span>

        </div>


        <div class="table-responsive">

            <table class="data-table">

                <thead>

                <tr>

                    <th>
                        Tin đăng
                    </th>

                    <th>
                        Chủ trọ
                    </th>

                    <th>
                        Giá thuê
                    </th>

                    <th>
                        Ngày đăng
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


                <!-- ================= POST 1 ================= -->

                <tr>

                    <td>

                        <div class="cell-title">
                            Phòng trọ full nội thất Quận 7
                        </div>

                        <div class="cell-sub">
                            25m² · Có máy lạnh · Có wifi
                        </div>

                    </td>


                    <td>

                        <div class="cell-title">
                            Nguyễn Văn A
                        </div>

                        <div class="cell-sub">
                            nguyenvana@gmail.com
                        </div>

                    </td>


                    <td>
                        2,8 triệu/tháng
                    </td>


                    <td>
                        07/09/2026
                    </td>


                    <td>

                        <span class="badge-status pending">
                            Chờ duyệt
                        </span>

                    </td>


                    <td>

                        <div class="d-flex gap-1">

                            <button class="btn-brand btn-sm">
                                Duyệt
                            </button>

                            <button class="btn-outline-brand btn-sm">
                                Từ chối
                            </button>

                        </div>

                    </td>

                </tr>


                <!-- ================= POST 2 ================= -->

                <tr>

                    <td>

                        <div class="cell-title">
                            Phòng trọ gần HUIT
                        </div>

                        <div class="cell-sub">
                            20m² · Có gác · Giờ giấc tự do
                        </div>

                    </td>


                    <td>

                        <div class="cell-title">
                            Trần Văn B
                        </div>

                        <div class="cell-sub">
                            tranvanb@gmail.com
                        </div>

                    </td>


                    <td>
                        2,5 triệu/tháng
                    </td>


                    <td>
                        07/09/2026
                    </td>


                    <td>

                        <span class="badge-status pending">
                            Chờ duyệt
                        </span>

                    </td>


                    <td>

                        <div class="d-flex gap-1">

                            <button class="btn-brand btn-sm">
                                Duyệt
                            </button>

                            <button class="btn-outline-brand btn-sm">
                                Từ chối
                            </button>

                        </div>

                    </td>

                </tr>


                <!-- ================= POST 3 ================= -->

                <tr>

                    <td>

                        <div class="cell-title">
                            Phòng trọ có máy lạnh
                        </div>

                        <div class="cell-sub">
                            30m² · Full nội thất · Ban công
                        </div>

                    </td>


                    <td>

                        <div class="cell-title">
                            Lê Văn C
                        </div>

                        <div class="cell-sub">
                            levanc@gmail.com
                        </div>

                    </td>


                    <td>
                        3,2 triệu/tháng
                    </td>


                    <td>
                        06/09/2026
                    </td>


                    <td>

                        <span class="badge-status pending">
                            Chờ duyệt
                        </span>

                    </td>


                    <td>

                        <div class="d-flex gap-1">

                            <button class="btn-brand btn-sm">
                                Duyệt
                            </button>

                            <button class="btn-outline-brand btn-sm">
                                Từ chối
                            </button>

                        </div>

                    </td>

                </tr>


                <!-- ================= POST 4 ================= -->

                <tr>

                    <td>

                        <div class="cell-title">
                            Phòng trọ giá rẻ Bình Thạnh
                        </div>

                        <div class="cell-sub">
                            18m² · Gần chợ · Có camera
                        </div>

                    </td>


                    <td>

                        <div class="cell-title">
                            Phạm Minh T
                        </div>

                        <div class="cell-sub">
                            minht@gmail.com
                        </div>

                    </td>


                    <td>
                        2,2 triệu/tháng
                    </td>


                    <td>
                        05/09/2026
                    </td>


                    <td>

                        <span class="badge-status approved">
                            Đã duyệt
                        </span>

                    </td>


                    <td>

                        <button class="btn-outline-brand btn-sm">
                            Ẩn tin
                        </button>

                    </td>

                </tr>


                <!-- ================= POST 5 ================= -->

                <tr>

                    <td>

                        <div class="cell-title">
                            Căn hộ mini gần trường
                        </div>

                        <div class="cell-sub">
                            35m² · Nội thất cơ bản · Có thang máy
                        </div>

                    </td>


                    <td>

                        <div class="cell-title">
                            Hoàng Tuấn
                        </div>

                        <div class="cell-sub">
                            tuan@gmail.com
                        </div>

                    </td>

                    <td>
                        4 triệu/tháng
                    </td>


                    <td>
                        04/09/2026
                    </td>


                    <td>

                        <span class="badge-status rejected">
                            Từ chối
                        </span>

                    </td>


                    <td>

                        <button class="btn-outline-brand btn-sm">
                            Xem lý do
                        </button>

                    </td>

                </tr>


                </tbody>

            </table>

        </div>


        <!-- ================= PAGINATION ================= -->

        <div class="d-flex justify-content-between align-items-center mt-4">

            <div class="page-desc">
                Hiển thị 1 - 5 trong 128 tin đăng
            </div>


            <div class="d-flex gap-1">

                <button class="btn-outline-brand btn-sm">
                    ‹
                </button>

                <button class="btn-brand btn-sm">
                    1
                </button>

                <button class="btn-outline-brand btn-sm">
                    2
                </button>

                <button class="btn-outline-brand btn-sm">
                    3
                </button>

                <button class="btn-outline-brand btn-sm">
                    ›
                </button>

            </div>

        </div>

    </div>


</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>