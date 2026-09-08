<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập - Trọ Ơi</title>

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
            max-width: 440px;
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

        .auth-extra {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            margin: 15px 0 20px;
            font-size: 13px;
        }

        .form-check-label {
            color: #6c757d;
        }

        .form-check-input {
            border-color: #cfc6ad;
        }

        .form-check-input:checked {
            background-color: #20584f;
            border-color: #20584f;
        }

        .forgot-link {
            color: #20584f;
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
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

        .register-text {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin: 0;
        }

        .register-link {
            color: #20584f;
            font-weight: 700;
            text-decoration: none;
        }

        .register-link:hover {
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


        <!-- Login Card -->
        <div class="auth-card">

            <h1 class="auth-title">
                Đăng nhập
            </h1>

            <p class="auth-desc">
                Đăng nhập để tiếp tục sử dụng Trọ Ơi
            </p>


            <!-- Thông báo lỗi -->
            @if ($errors->any())

                <div class="alert alert-danger">

                    <ul class="mb-0 ps-3">

                        @foreach ($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <!-- Thông báo -->
            @if (session('success'))

                <div class="alert alert-success">
                    {{ session('success') }}
                </div>

            @endif


            <form action="{{ url('/login') }}" method="POST">

                @csrf


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
                        placeholder="Nhập email của bạn"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                    >

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
                        autocomplete="current-password"
                    >

                </div>


                <!-- Remember + Forgot -->
                <div class="auth-extra">

                    <div class="form-check">

                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="remember"
                            id="remember"
                        >

                        <label
                            class="form-check-label"
                            for="remember"
                        >
                            Ghi nhớ đăng nhập
                        </label>

                    </div>

                    <a href="#" class="forgot-link">
                        Quên mật khẩu?
                    </a>

                </div>


                <!-- Button -->
                <button type="submit" class="btn-auth">
                    Đăng nhập
                </button>

            </form>


            <div class="auth-divider">
                HOẶC
            </div>


            <!-- Register -->
            <p class="register-text">

                Chưa có tài khoản?

                <a href="{{ url('/register') }}" class="register-link">
                    Đăng ký ngay
                </a>

            </p>

        </div>

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>