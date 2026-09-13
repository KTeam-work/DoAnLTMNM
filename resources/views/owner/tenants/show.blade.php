<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hồ sơ người thuê | Trọ Ơi</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- CSS chung -->
    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}"
    >

    <style>

        html,
        body {
            background-color: #f7f9f8 !important;
        }

        body {
            min-height: 100vh;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .page-wrap {
            max-width: 1100px;
            margin: 30px auto 60px;
            padding: 0 20px;
        }

        /* =========================
           BACK
        ========================= */

        .back-link {
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 20px;
        }

        .back-link:hover {
            color: #198754;
        }


        /* =========================
           PROFILE HEADER
        ========================= */

        .profile-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .profile-left {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .avatar-large {
            width: 76px;
            height: 76px;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #e0f2fe;
            color: #0284c7;

            font-size: 25px;
            font-weight: 700;

            flex-shrink: 0;
        }

        .profile-name {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .profile-subtitle {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 10px;
        }

        .badge-soft-success {
            background: #dcfce7;
            color: #16a34a;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            font-size: 13px;
        }

        .badge-soft-primary {
            background: #e0f2fe;
            color: #0284c7;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 6px;
            display: inline-block;
            font-size: 13px;
        }


        /* =========================
           BUTTON
        ========================= */

        .btn-edit {
            border: 1px solid #e2e8f0;
            background: #fff;
            color: #475569;

            border-radius: 8px;
            padding: 9px 16px;

            font-size: 14px;
            font-weight: 600;

            text-decoration: none;
        }

        .btn-edit:hover {
            background: #0f172a;
            color: #fff;
            border-color: #0f172a;
        }


        /* =========================
           GRID
        ========================= */

        .content-grid {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 20px;
        }


        /* =========================
           PANEL
        ========================= */

        .panel-modern {
            background: #fff;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
            padding: 22px;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
        }


        /* =========================
           INFO
        ========================= */

        .info-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;

            padding: 13px 0;

            border-bottom: 1px solid #f1f5f9;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: #64748b;
            font-size: 14px;
        }

        .info-value {
            color: #1e293b;
            font-size: 14px;
            font-weight: 600;
            text-align: right;
        }


        /* =========================
           CONTRACT
        ========================= */

        .contract-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 16px;
        }

        .contract-code {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }

        .contract-item {
            display: flex;
            justify-content: space-between;

            font-size: 13px;
            margin-bottom: 8px;
        }

        .contract-item:last-child {
            margin-bottom: 0;
        }

        .contract-label {
            color: #64748b;
        }

        .contract-value {
            color: #334155;
            font-weight: 600;
        }


        /* =========================
           MEMBER
        ========================= */

        .member-item {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 13px 0;

            border-bottom: 1px solid #f1f5f9;
        }

        .member-item:last-child {
            border-bottom: none;
        }

        .member-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .member-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f3e8ff;
            color: #9333ea;

            font-size: 13px;
            font-weight: 700;
        }

        .member-name {
            font-size: 14px;
            font-weight: 600;
            color: #334155;
        }

        .member-role {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }


        /* =========================
           NOTE
        ========================= */

        .note-box {
            background: #fffbeb;
            border: 1px solid #fde68a;
            border-radius: 10px;
            padding: 14px;

            color: #92400e;
            font-size: 13px;
            line-height: 1.6;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .content-grid {
                grid-template-columns: 1fr;
            }

            .profile-header {
                align-items: flex-start;
                flex-direction: column;
            }

            .profile-left {
                align-items: flex-start;
            }

            .profile-name {
                font-size: 20px;
            }

            .avatar-large {
                width: 65px;
                height: 65px;
                font-size: 21px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav class="navbar navbar-expand-lg app-navbar">

    <div class="container-fluid">

        <!-- LOGO -->

        <a
            class="logo"
            href="{{ url('/') }}"
        >
            Trọ <span>Ơi</span>
        </a>


        <!-- MOBILE -->

        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#mainMenu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>


        <!-- MENU -->

        <div
            class="collapse navbar-collapse"
            id="mainMenu"
        >

            <ul class="navbar-nav mx-auto align-items-lg-center">

                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ url('/') }}"
                    >
                        Tổng quan
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Nhà & Phòng
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Tin đăng
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="#"
                    >
                        Lịch xem
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="app-nav-link active"
                        href="{{ url('/owner/tenants') }}"
                    >
                        Người thuê
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="app-nav-link"
                        href="{{ url('/owner/contracts') }}"
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


            <!-- USER -->

            <div class="navbar-actions">

                <button class="notif-btn">
                    🔔
                    <span class="notif-dot"></span>
                </button>


                <a
                    href="#"
                    class="user-chip"
                >

                    <span class="user-avatar">
                        MT
                    </span>

                    <span class="user-meta">

                        <span class="user-name d-block">
                            Minh Tuấn
                        </span>

                        <span class="user-role">
                            Chủ trọ
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



<!-- =====================================================
     PAGE
===================================================== -->

<div class="page-wrap">


    <!-- QUAY LẠI -->

    <a
        href="{{ url('/owner/tenants') }}"
        class="back-link"
    >
        ← Quay lại danh sách người thuê
    </a>



    <!-- =================================================
         PROFILE HEADER
    ================================================= -->

    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-left">

                <div class="avatar-large">
                    TH
                </div>


                <div>

                    <div class="profile-name">
                        Nguyễn Thanh Huyền
                    </div>

                    <div class="profile-subtitle">
                        Người đứng tên hợp đồng · Phòng 12A
                    </div>

                    <span class="badge-soft-success">
                        ● Đang ở
                    </span>

                    <span class="badge-soft-primary ms-2">
                        Người thuê chính
                    </span>

                </div>

            </div>


            <a
                href="{{ route('owner.tenants.edit') }}"
                class="btn-edit"
            >
                ✎ Chỉnh sửa hồ sơ
            </a>

        </div>

    </div>



    <!-- =================================================
         CONTENT
    ================================================= -->

    <div class="content-grid">


        <!-- =================================================
             LEFT
        ================================================= -->

        <div>


            <!-- THÔNG TIN CÁ NHÂN -->

            <div class="panel-modern">

                <div class="panel-title">
                    Thông tin cá nhân
                </div>


                <div class="info-row">

                    <div class="info-label">
                        Họ và tên
                    </div>

                    <div class="info-value">
                        Nguyễn Thanh Huyền
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Giới tính
                    </div>

                    <div class="info-value">
                        Nữ
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Ngày sinh
                    </div>

                    <div class="info-value">
                        15/06/1998
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Số CCCD
                    </div>

                    <div class="info-value">
                        07919800****1234
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Số điện thoại
                    </div>

                    <div class="info-value">
                        0901 234 567
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Email
                    </div>

                    <div class="info-value">
                        huyen.nt@email.com
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Địa chỉ thường trú
                    </div>

                    <div class="info-value">
                        TP. Hồ Chí Minh
                    </div>

                </div>

            </div>



            <!-- HỢP ĐỒNG -->

            <div class="panel-modern">

                <div class="panel-title">
                    Hợp đồng thuê hiện tại
                </div>


                <div class="contract-box">

                    <div class="contract-code">
                        Hợp đồng #HD-2026-01
                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Phòng
                        </span>

                        <span class="contract-value">
                            Phòng 12A
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Ngày bắt đầu
                        </span>

                        <span class="contract-value">
                            01/02/2026
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Ngày kết thúc
                        </span>

                        <span class="contract-value">
                            01/02/2027
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Tiền thuê
                        </span>

                        <span class="contract-value">
                            2.800.000 đ / tháng
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Tiền cọc
                        </span>

                        <span class="contract-value">
                            2.800.000 đ
                        </span>

                    </div>

                </div>


                <div class="mt-3">

                    <a
                        href="{{ url('/owner/contracts/1') }}"
                        class="btn btn-action"
                    >
                        Xem chi tiết hợp đồng →
                    </a>

                </div>

            </div>

        </div>



        <!-- =================================================
             RIGHT
        ================================================= -->

        <div>


            <!-- THÔNG TIN LƯU TRÚ -->

            <div class="panel-modern">

                <div class="panel-title">
                    Thông tin lưu trú
                </div>


                <div class="info-row">

                    <div class="info-label">
                        Phòng đang thuê
                    </div>

                    <div class="info-value">
                        12A
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Ngày vào ở
                    </div>

                    <div class="info-value">
                        01/02/2026
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Thời hạn
                    </div>

                    <div class="info-value">
                        12 tháng
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Trạng thái
                    </div>

                    <div class="info-value">

                        <span class="badge-soft-success">
                            Đang ở
                        </span>

                    </div>

                </div>

            </div>



            <!-- NGƯỜI Ở CÙNG -->

            <div class="panel-modern">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="panel-title mb-0">
                        Người ở cùng
                    </div>

                    <span class="text-muted small">
                        1 người
                    </span>

                </div>


                <div class="member-item">

                    <div class="member-left">

                        <div class="member-avatar">
                            TB
                        </div>

                        <div>

                            <div class="member-name">
                                Trần Minh Bình
                            </div>

                            <div class="member-role">
                                Bạn · 0912 345 678
                            </div>

                        </div>

                    </div>


                    <a
                        href="#"
                        class="btn btn-sm btn-action"
                    >
                        Hồ sơ
                    </a>

                </div>


                <a
                    href="{{ url('/owner/contracts/1/members/create') }}"
                    class="btn btn-action w-100 mt-3"
                >
                    + Thêm người ở cùng
                </a>

            </div>



            <!-- GHI CHÚ -->

            <div class="panel-modern">

                <div class="panel-title">
                    Ghi chú
                </div>

                <div class="note-box">

                    Người thuê chính đang đứng tên hợp đồng
                    #HD-2026-01. Các thông tin người ở cùng
                    được quản lý theo hợp đồng thuê.

                </div>

            </div>

        </div>

    </div>

</div>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>