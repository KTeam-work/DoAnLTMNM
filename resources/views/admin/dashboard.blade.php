<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Admin - Trọ Ơi</title>

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- CSS chung của nhóm -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>

<body>

<!-- ================= NAVBAR ================= -->

<nav class="app-navbar">

    <div class="container-fluid h-100">

        <div class="d-flex align-items-center justify-content-between h-100">

            <!-- Logo -->
            <a href="{{ url('/admin/dashboard') }}" class="logo">
                Trọ <span>Ơi</span>
                <small>ADMIN</small>
            </a>


            <!-- Menu -->
            <div class="d-flex align-items-center gap-1">

                <a href="{{ url('/admin/dashboard') }}"
                   class="app-nav-link active text-decoration-none">
                    Dashboard
                </a>

                <a href="{{ url('/admin/users') }}"
                   class="app-nav-link text-decoration-none">
                    Tài khoản
                </a>

                <a href="{{ url('/admin/rental-posts') }}"
                   class="app-nav-link text-decoration-none">
                    Kiểm duyệt tin
                </a>

                <a href="{{ url('/admin/statistics') }}"
                   class="app-nav-link text-decoration-none">
                    Thống kê
                </a>

            </div>


            <!-- User -->
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


    <!-- Header -->

    <div class="page-header">

        <div>

            <h1 class="page-title">
                Dashboard Admin
            </h1>

            <div class="page-desc">
                Quản lý tài khoản và kiểm duyệt tin đăng
            </div>

        </div>

    </div>


    <!-- ================= STATISTICS ================= -->

    <div class="row g-3 mb-4">


        <!-- Tổng tài khoản -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon blue">
                        👥
                    </div>

                </div>

                <div class="stat-value">
                    1.248
                </div>

                <div class="stat-label">
                    Tổng tài khoản
                </div>

            </div>

        </div>


        <!-- Hoạt động -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon green">
                        ✓
                    </div>

                </div>

                <div class="stat-value">
                    1.230
                </div>

                <div class="stat-label">
                    Tài khoản hoạt động
                </div>

            </div>

        </div>


        <!-- Bị khóa -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon red">
                        🔒
                    </div>

                </div>

                <div class="stat-value">
                    18
                </div>

                <div class="stat-label">
                    Tài khoản bị khóa
                </div>

            </div>

        </div>


        <!-- Chờ duyệt -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="stat-card">

                <div class="stat-top">

                    <div class="stat-icon">
                        📝
                    </div>

                </div>

                <div class="stat-value">
                    14
                </div>

                <div class="stat-label">
                    Tin đăng chờ duyệt
                </div>

            </div>

        </div>

    </div>


    <!-- ================= MAIN PANELS ================= -->

    <div class="row g-3">


        <!-- Tin đăng chờ duyệt -->

        <div class="col-12 col-lg-7">

            <div class="panel">

                <div class="panel-head">

                    <h2 class="panel-title">
                        Tin đăng chờ duyệt
                    </h2>

                    <a href="{{ url('/admin/rental-posts') }}"
                       class="panel-link">
                        Xem tất cả
                    </a>

                </div>


                <div class="table-responsive">

                    <table class="data-table">

                        <thead>

                        <tr>
                            <th>Tin đăng</th>
                            <th>Chủ trọ</th>
                            <th>Trạng thái</th>
                        </tr>

                        </thead>


                        <tbody>

                        <tr>

                            <td>

                                <div class="cell-title">
                                    Phòng trọ full nội thất Quận 7
                                </div>

                                <div class="cell-sub">
                                    2,8 triệu/tháng
                                </div>

                            </td>

                            <td>
                                Nguyễn Văn A
                            </td>

                            <td>

                                <span class="badge-status pending">
                                    Chờ duyệt
                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="cell-title">
                                    Phòng trọ gần HUIT
                                </div>

                                <div class="cell-sub">
                                    2,5 triệu/tháng
                                </div>

                            </td>

                            <td>
                                Trần Văn B
                            </td>

                            <td>

                                <span class="badge-status pending">
                                    Chờ duyệt
                                </span>

                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="cell-title">
                                    Phòng trọ có máy lạnh
                                </div>

                                <div class="cell-sub">
                                    3,2 triệu/tháng
                                </div>

                            </td>

                            <td>
                                Lê Văn C
                            </td>

                            <td>

                                <span class="badge-status pending">
                                    Chờ duyệt
                                </span>

                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>


        <!-- Tài khoản gần đây -->

        <div class="col-12 col-lg-5">

            <div class="panel">

                <div class="panel-head">

                    <h2 class="panel-title">
                        Tài khoản gần đây
                    </h2>

                    <a href="{{ url('/admin/users') }}"
                       class="panel-link">
                        Xem tất cả
                    </a>

                </div>


                <div class="table-responsive">

                    <table class="data-table">

                        <thead>

                        <tr>
                            <th>Tài khoản</th>
                            <th>Vai trò</th>
                        </tr>

                        </thead>


                        <tbody>

                        <tr>

                            <td>

                                <div class="cell-title">
                                    Nguyễn Minh
                                </div>

                                <div class="cell-sub">
                                    minh@gmail.com
                                </div>

                            </td>

                            <td>
                                Owner
                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="cell-title">
                                    Trần Anh
                                </div>

                                <div class="cell-sub">
                                    anh@gmail.com
                                </div>

                            </td>

                            <td>
                                Tenant
                            </td>

                        </tr>


                        <tr>

                            <td>

                                <div class="cell-title">
                                    Lê Hoàng
                                </div>

                                <div class="cell-sub">
                                    hoang@gmail.com
                                </div>

                            </td>

                            <td>
                                Owner
                            </td>

                        </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>


    <!-- ================= QUICK ACTION ================= -->

    <div class="d-flex flex-wrap gap-2 mt-4">

        <a href="{{ url('/admin/users') }}"
           class="btn-brand text-decoration-none">
            Quản lý tài khoản
        </a>

        <a href="{{ url('/admin/rental-posts') }}"
           class="btn-brand text-decoration-none">
            Kiểm duyệt tin đăng
        </a>

        <a href="{{ url('/admin/statistics') }}"
           class="btn-outline-brand text-decoration-none">
            Xem thống kê
        </a>

    </div>


</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>