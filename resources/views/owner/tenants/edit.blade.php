<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chỉnh sửa hồ sơ | Trọ Ơi</title>

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
            max-width: 900px;
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
           HEADER
        ========================= */

        .page-title {
            font-size: 26px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 6px;
        }

        .page-desc {
            color: #64748b;
            font-size: 14px;
            margin-bottom: 25px;
        }


        /* =========================
           CARD
        ========================= */

        .form-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;

            padding: 28px;

            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.03);
        }


        /* =========================
           SECTION
        ========================= */

        .section-title {
            font-size: 17px;
            font-weight: 700;
            color: #1e293b;

            margin-bottom: 5px;
        }

        .section-desc {
            color: #64748b;
            font-size: 13px;

            margin-bottom: 22px;
        }

        .section-divider {
            border: 0;
            border-top: 1px solid #eef2f5;
            margin: 30px 0;
        }


        /* =========================
           LABEL
        ========================= */

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #334155;

            margin-bottom: 8px;
        }

        .required {
            color: #dc3545;
        }


        /* =========================
           INPUT
        ========================= */

        .form-control,
        .form-select {
            min-height: 46px;

            border-radius: 9px;
            border: 1px solid #dbe1e6;

            font-size: 14px;

            padding: 10px 13px;
        }

        .form-control:focus,
        .form-select:focus {

            border-color: #198754;

            box-shadow:
                0 0 0 3px rgba(25, 135, 84, 0.10);
        }

        .form-control::placeholder {
            color: #a0a8b2;
        }


        /* =========================
           DISABLED
        ========================= */

        .form-control:disabled {
            background: #f8fafc;
            color: #64748b;
        }

        .input-note {
            color: #94a3b8;
            font-size: 12px;
            margin-top: 6px;
        }


        /* =========================
           ALERT
        ========================= */

        .info-box {
            background: #f0fdf4;

            border: 1px solid #d1fae5;

            border-radius: 10px;

            padding: 13px 15px;

            color: #166534;

            font-size: 13px;

            line-height: 1.6;

            margin-bottom: 25px;
        }


        /* =========================
           BUTTON
        ========================= */

        .form-actions {
            display: flex;
            justify-content: flex-end;

            gap: 10px;

            padding-top: 5px;
        }

        .btn-cancel {
            min-height: 45px;

            padding: 10px 22px;

            border-radius: 9px;

            border: 1px solid #dbe1e6;

            background: #fff;

            color: #475569;

            font-size: 14px;

            font-weight: 600;

            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #f8fafc;
            color: #334155;
        }

        .btn-save {
            min-height: 45px;

            padding: 10px 24px;

            border-radius: 9px;

            border: none;

            background: #198754;

            color: #fff;

            font-size: 14px;

            font-weight: 600;
        }

        .btn-save:hover {
            background: #157347;
            color: #fff;
        }


        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 768px) {

            .page-wrap {
                margin-top: 20px;
            }

            .form-card {
                padding: 20px;
            }

            .page-title {
                font-size: 22px;
            }

            .form-actions {
                flex-direction: column-reverse;
            }

            .btn-cancel,
            .btn-save {
                width: 100%;
                text-align: center;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
===================================================== -->

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
          <a class="app-nav-link" href="{{ url('/landlord') }}">Tổng quan</a>
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
              <a class="dropdown-item active" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a>
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

<!-- =====================================================
     PAGE
===================================================== -->

<div class="page-wrap">


    <!-- QUAY LẠI -->

    <a
        href="{{ url('/owner/tenants/1') }}"
        class="back-link"
    >
        ← Quay lại hồ sơ người thuê
    </a>


    <h1 class="page-title">
        Chỉnh sửa hồ sơ người thuê
    </h1>

    <div class="page-desc">
        Cập nhật thông tin cá nhân của người thuê.
    </div>



    <!-- =================================================
         FORM
    ================================================= -->

    <div class="form-card">


        <!-- THÔNG BÁO -->

        <div class="info-box">

            💡 Các thông tin bên dưới là thông tin hồ sơ
            của <strong>người thuê chính</strong>.
            Thay đổi thông tin tại đây sẽ cập nhật hồ sơ
            người thuê.

        </div>



        <form
            action="#"
            method="POST"
        >

            @csrf

            @method('PUT')



            <!-- =========================================
                 THÔNG TIN CÁ NHÂN
            ========================================== -->

            <div class="section-title">
                Thông tin cá nhân
            </div>

            <div class="section-desc">
                Thông tin cơ bản của người thuê.
            </div>


            <div class="row g-4">


                <!-- HỌ TÊN -->

                <div class="col-md-6">

                    <label class="form-label">

                        Họ và tên

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="Nguyễn Thanh Huyền"
                        placeholder="Nhập họ và tên"
                        required
                    >

                </div>



                <!-- GIỚI TÍNH -->

                <div class="col-md-6">

                    <label class="form-label">
                        Giới tính
                    </label>


                    <select
                        name="gender"
                        class="form-select"
                    >

                        <option value="">
                            -- Chọn giới tính --
                        </option>

                        <option value="female" selected>
                            Nữ
                        </option>

                        <option value="male">
                            Nam
                        </option>

                        <option value="other">
                            Khác
                        </option>

                    </select>

                </div>



                <!-- NGÀY SINH -->

                <div class="col-md-6">

                    <label class="form-label">
                        Ngày sinh
                    </label>


                    <input
                        type="date"
                        name="date_of_birth"
                        class="form-control"
                        value="1998-06-15"
                    >

                </div>



                <!-- CCCD -->

                <div class="col-md-6">

                    <label class="form-label">
                        Số CCCD
                    </label>


                    <input
                        type="text"
                        name="identity_card"
                        class="form-control"
                        value="079198001234"
                        placeholder="Nhập số CCCD"
                    >

                </div>

            </div>



            <hr class="section-divider">



            <!-- =========================================
                 LIÊN HỆ
            ========================================== -->

            <div class="section-title">
                Thông tin liên hệ
            </div>

            <div class="section-desc">
                Thông tin dùng để liên lạc với người thuê.
            </div>


            <div class="row g-4">


                <!-- SĐT -->

                <div class="col-md-6">

                    <label class="form-label">
                        Số điện thoại
                    </label>


                    <input
                        type="tel"
                        name="phone"
                        class="form-control"
                        value="0901234567"
                        placeholder="Nhập số điện thoại"
                    >

                </div>



                <!-- EMAIL -->

                <div class="col-md-6">

                    <label class="form-label">
                        Email
                    </label>


                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="huyen.nt@email.com"
                        placeholder="Nhập email"
                    >

                </div>



                <!-- ĐỊA CHỈ -->

                <div class="col-12">

                    <label class="form-label">
                        Địa chỉ thường trú
                    </label>


                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        value="TP. Hồ Chí Minh"
                        placeholder="Nhập địa chỉ thường trú"
                    >

                </div>

            </div>



            <hr class="section-divider">



            <!-- =========================================
                 THÔNG TIN TÀI KHOẢN
            ========================================== -->

            <div class="section-title">
                Thông tin tài khoản
            </div>

            <div class="section-desc">
                Thông tin tài khoản đăng nhập của người thuê.
            </div>


            <div class="row g-4">


                <!-- EMAIL ĐĂNG NHẬP -->

                <div class="col-md-6">

                    <label class="form-label">
                        Email đăng nhập
                    </label>


                    <input
                        type="email"
                        class="form-control"
                        value="huyen.nt@email.com"
                        disabled
                    >


                    <div class="input-note">
                        Email đăng nhập được quản lý riêng.
                    </div>

                </div>



                <!-- VAI TRÒ -->

                <div class="col-md-6">

                    <label class="form-label">
                        Vai trò
                    </label>


                    <input
                        type="text"
                        class="form-control"
                        value="Người thuê"
                        disabled
                    >


                    <div class="input-note">
                        Vai trò tài khoản không thể thay đổi tại đây.
                    </div>

                </div>

            </div>



            <!-- =========================================
                 BUTTON
            ========================================== -->

            <hr class="section-divider">


            <div class="form-actions">

                <a
                    href="{{ url('/owner/tenants/1') }}"
                    class="btn-cancel"
                >
                    Hủy
                </a>


                <button
                    type="submit"
                    class="btn-save"
                >
                    ✓ Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

</div>



<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>