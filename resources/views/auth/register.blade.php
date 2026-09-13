<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký - Trọ Ơi</title>

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

    <style>
        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: #f8f5eb;
            min-height: 100vh;
        }

        .auth-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
        }

        .auth-brand {
            text-align: center;
            margin-bottom: 24px;
        }

        .auth-logo {
            display: inline-block;
            font-size: 32px;
            font-weight: 800;
            color: #20584f;
            text-decoration: none;
            letter-spacing: -1px;
        }

        .auth-logo span {
            color: #f5c84b;
        }

        .auth-logo small {
            display: block;
            margin-top: -3px;
            font-size: 10px;
            letter-spacing: 2px;
            color: #17463e;
            font-weight: 700;
        }

        .auth-card {
            background: #ffffff;
            border: 1px solid #e6dcc2;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(32, 88, 79, 0.08);
        }

        .auth-title {
            color: #17463e;
            font-size: 25px;
            font-weight: 800;
            margin-bottom: 8px;
            text-align: center;
        }

        .auth-desc {
            color: #6c757d;
            font-size: 14px;
            text-align: center;
            margin-bottom: 28px;
        }

        .form-label {
            color: #17463e;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border: 1px solid #e6dcc2;
            border-radius: 10px;
            padding: 10px 13px;
            font-size: 14px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #20584f;
            box-shadow: 0 0 0 3px rgba(32, 88, 79, 0.10);
        }

        .role-title {
            color: #17463e;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .role-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .role-option {
            position: relative;
        }

        .role-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .role-option label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 86px;
            padding: 12px;
            border: 1px solid #e6dcc2;
            border-radius: 12px;
            cursor: pointer;
            text-align: center;
            transition: 0.2s ease;
        }

        .role-icon {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .role-name {
            color: #17463e;
            font-size: 13px;
            font-weight: 700;
        }

        .role-desc {
            color: #8a8a8a;
            font-size: 11px;
            margin-top: 2px;
        }

        .role-option input:checked + label {
            border-color: #20584f;
            background: rgba(32, 88, 79, 0.06);
            box-shadow: 0 0 0 2px rgba(32, 88, 79, 0.08);
        }

        .btn-auth {
            width: 100%;
            min-height: 46px;
            border: none;
            border-radius: 10px;
            background: #20584f;
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .btn-auth:hover {
            background: #17463e;
            color: #ffffff;
        }

        .terms {
            color: #8a8a8a;
            font-size: 11px;
            line-height: 1.6;
            text-align: center;
            margin: 15px 0 0;
        }

        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0;
            color: #9a9a9a;
            font-size: 12px;
        }

        .auth-divider::before,
        .auth-divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e6dcc2;
        }

        .login-text {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .login-link {
            color: #20584f;
            font-weight: 700;
            text-decoration: none;
        }

        .login-link:hover {
            text-decoration: underline;
        }

        .alert {
            border-radius: 10px;
            font-size: 13px;
        }

        @media (max-width: 480px) {

            .auth-page {
                padding: 24px 15px;
            }

            .auth-card {
                padding: 25px 20px;
            }

            .auth-title {
                font-size: 22px;
            }

            .role-group {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>

<div class="auth-page">

    <div class="auth-container">

        <!-- Logo -->
        <div class="auth-brand">

            <a href="{{ url('/') }}" class="auth-logo">
                Trọ <span>Ơi</span>
                <small>QUẢN LÝ & CHO THUÊ PHÒNG TRỌ</small>
            </a>

        </div>


        <!-- Register Card -->
        <div class="auth-card">

            <h1 class="auth-title">
                Tạo tài khoản
            </h1>

            <p class="auth-desc">
                Đăng ký tài khoản để bắt đầu sử dụng Trọ Ơi
            </p>


            <!-- Validation Errors -->
            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0 ps-3">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ url('/register') }}" method="POST">

                @csrf


                <!-- Họ tên -->
                <div class="mb-3">

                    <label for="name" class="form-label">
                        Họ và tên
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        placeholder="Nhập họ và tên"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                    >

                </div>


                <!-- Email -->
                <div class="mb-3">

                    <label for="email" class="form-label">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        placeholder="Nhập email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >

                </div>


                <!-- Số điện thoại -->
                <div class="mb-3">

                    <label for="phone" class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-control"
                        placeholder="Nhập số điện thoại"
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                    >

                </div>


                <!-- Vai trò -->
                <div class="mb-3">

                    <div class="role-title">
                        Bạn muốn sử dụng Trọ Ơi với vai trò
                    </div>

                    <div class="role-group">

                        <div class="role-option">

                            <input
                                type="radio"
                                id="owner"
                                name="role"
                                value="owner"
                                {{ old('role', 'owner') === 'owner' ? 'checked' : '' }}
                            >

                            <label for="owner">

                                <div class="role-icon">
                                    🏠
                                </div>

                                <div class="role-name">
                                    Chủ trọ
                                </div>

                                <div class="role-desc">
                                    Đăng và quản lý phòng
                                </div>

                            </label>

                        </div>


                        <div class="role-option">

                            <input
                                type="radio"
                                id="tenant"
                                name="role"
                                value="tenant"
                                {{ old('role') === 'tenant' ? 'checked' : '' }}
                            >

                            <label for="tenant">

                                <div class="role-icon">
                                    👤
                                </div>

                                <div class="role-name">
                                    Người thuê
                                </div>

                                <div class="role-desc">
                                    Tìm kiếm và thuê phòng
                                </div>

                            </label>

                        </div>

                    </div>

                </div>


                <!-- Password -->
                <div class="mb-3">

                    <label for="password" class="form-label">
                        Mật khẩu
                    </label>

                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control"
                        placeholder="Nhập mật khẩu"
                        required
                        autocomplete="new-password"
                    >

                </div>


                <!-- Confirm Password -->
                <div class="mb-3">

                    <label for="password_confirmation" class="form-label">
                        Xác nhận mật khẩu
                    </label>

                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Nhập lại mật khẩu"
                        required
                        autocomplete="new-password"
                    >

                </div>


                <!-- Button -->
                <button type="submit" class="btn-auth">
                    Đăng ký tài khoản
                </button>


                <p class="terms">
                    Bằng việc đăng ký, bạn đồng ý với các quy định
                    sử dụng của Trọ Ơi.
                </p>

            </form>


            <div class="auth-divider">
                HOẶC
            </div>


            <!-- Login -->
            <p class="login-text">

                Đã có tài khoản?

                <a href="{{ url('/login') }}" class="login-link">
                    Đăng nhập
                </a>

            </p>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>