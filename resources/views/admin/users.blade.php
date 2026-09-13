<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý tài khoản - Trọ Ơi</title>

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

            <a href="{{ url('/admin/dashboard') }}" class="logo text-decoration-none">
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
                   class="app-nav-link active text-decoration-none">
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
                Quản lý tài khoản
            </h1>

            <div class="page-desc">
                Quản lý người dùng, vai trò và trạng thái tài khoản
            </div>

        </div>

    </div>


    <!-- ================= FILTER ================= -->

    <div class="panel mb-4">

        <div class="panel-head">

            <h2 class="panel-title">
                Tìm kiếm và lọc
            </h2>

        </div>


        <form method="GET" action="{{ url('/admin/users') }}">

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
                        placeholder="Tên, email hoặc số điện thoại..."
                        value="{{ request('search') }}"
                    >

                </div>


                <!-- Vai trò -->

                <div class="col-12 col-md-6 col-lg-3">

                    <label class="form-label">
                        Vai trò
                    </label>

                    <select name="role" class="form-select">

                        <option value="">
                            Tất cả
                        </option>

                        <option value="admin"
                            {{ request('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="owner"
                            {{ request('role') == 'owner' ? 'selected' : '' }}>
                            Owner
                        </option>

                        <option value="tenant"
                            {{ request('role') == 'tenant' ? 'selected' : '' }}>
                            Tenant
                        </option>

                    </select>

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

                        <option value="active"
                            {{ request('status') == 'active' ? 'selected' : '' }}>
                            Hoạt động
                        </option>

                        <option value="blocked"
                            {{ request('status') == 'blocked' ? 'selected' : '' }}>
                            Bị khóa
                        </option>

                    </select>

                </div>


                <!-- BUTTON -->

                <div class="col-12">

                    <button type="submit" class="btn-brand">
                        Tìm kiếm
                    </button>

                    <a href="{{ url('/admin/users') }}"
                       class="btn-outline-brand text-decoration-none">
                        Đặt lại
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- ================= USER TABLE ================= -->

    <div class="panel">

        <div class="panel-head">

            <div>

                <h2 class="panel-title">
                    Danh sách tài khoản
                </h2>

                <div class="page-desc">
                    Tổng cộng 1.248 tài khoản
                </div>

            </div>

        </div>


        <div class="table-responsive">

            <table class="data-table">

                <thead>

                <tr>

                    <th>
                        Tài khoản
                    </th>

                    <th>
                        Số điện thoại
                    </th>

                    <th>
                        Vai trò
                    </th>

                    <th>
                        Trạng thái
                    </th>

                    <th>
                        Ngày tạo
                    </th>

                    <th>
                        Thao tác
                    </th>

                </tr>

                </thead>


                <tbody>


                <!-- USER 1 -->

                <tr>

                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <div class="avatar-sm">
                                NM
                            </div>

                            <div>

                                <div class="cell-title">
                                    Nguyễn Minh
                                </div>

                                <div class="cell-sub">
                                    minh@gmail.com
                                </div>

                            </div>

                        </div>

                    </td>


                    <td>
                        0901234567
                    </td>


                    <td>
                        Owner
                    </td>


                    <td>

                        <span class="badge-status active">
                            Hoạt động
                        </span>

                    </td>


                    <td>
                        05/09/2026
                    </td>


                    <td>

                        <button class="btn-outline-brand btn-sm">
                            Khóa
                        </button>

                    </td>

                </tr>


                <!-- USER 2 -->

                <tr>

                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <div class="avatar-sm">
                                TA
                            </div>

                            <div>

                                <div class="cell-title">
                                    Trần Anh
                                </div>

                                <div class="cell-sub">
                                    anh@gmail.com
                                </div>

                            </div>

                        </div>

                    </td>


                    <td>
                        0912345678
                    </td>


                    <td>
                        Tenant
                    </td>


                    <td>

                        <span class="badge-status active">
                            Hoạt động
                        </span>

                    </td>


                    <td>
                        04/09/2026
                    </td>


                    <td>

                        <button class="btn-outline-brand btn-sm">
                            Khóa
                        </button>

                    </td>

                </tr>


                <!-- USER 3 -->

                <tr>

                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <div class="avatar-sm">
                                LH
                            </div>

                            <div>

                                <div class="cell-title">
                                    Lê Hoàng
                                </div>

                                <div class="cell-sub">
                                    hoang@gmail.com
                                </div>

                            </div>

                        </div>

                    </td>


                    <td>
                        0987654321
                    </td>


                    <td>
                        Owner
                    </td>


                    <td>

                        <span class="badge-status blocked">
                            Bị khóa
                        </span>

                    </td>


                    <td>
                        02/09/2026
                    </td>


                    <td>

                        <button class="btn-brand btn-sm">
                            Mở khóa
                        </button>

                    </td>

                </tr>


                <!-- USER 4 -->

                <tr>

                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <div class="avatar-sm">
                                PV
                            </div>

                            <div>

                                <div class="cell-title">
                                    Phạm Văn Nam
                                </div>

                                <div class="cell-sub">
                                    nam@gmail.com
                                </div>

                            </div>

                        </div>

                    </td>


                    <td>
                        0934567890
                    </td>


                    <td>
                        Tenant
                    </td>


                    <td>

                        <span class="badge-status active">
                            Hoạt động
                        </span>

                    </td>


                    <td>
                        01/09/2026
                    </td>


                    <td>

                        <button class="btn-outline-brand btn-sm">
                            Khóa
                        </button>

                    </td>

                </tr>


                <!-- USER 5 -->

                <tr>

                    <td>

                        <div class="d-flex align-items-center gap-2">

                            <div class="avatar-sm">
                                HT
                            </div>

                            <div>

                                <div class="cell-title">
                                    Hoàng Tuấn
                                </div>

                                <div class="cell-sub">
                                    tuan@gmail.com
                                </div>

                            </div>

                        </div>

                    </td>


                    <td>
                        0978123456
                    </td>


                    <td>
                        Owner
                    </td>


                    <td>

                        <span class="badge-status active">
                            Hoạt động
                        </span>

                    </td>


                    <td>
                        30/08/2026
                    </td>


                    <td>

                        <button class="btn-outline-brand btn-sm">
                            Khóa
                        </button>

                    </td>

                </tr>


                </tbody>

            </table>

        </div>


        <!-- ================= PAGINATION ================= -->

        <div class="d-flex justify-content-between align-items-center mt-4">

            <div class="page-desc">
                Hiển thị 1 - 5 trong 1.248 tài khoản
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