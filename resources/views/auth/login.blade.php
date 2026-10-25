<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Đăng nhập - Trọ Ơi</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            font-family: "Be Vietnam Pro", sans-serif;
            background: #dfe5e1;
            color: #17463e;
        }

        .page {
            position: relative;
            min-height: 100vh;
            padding: 24px;
            isolation: isolate;
        }

        .background {
            position: absolute;
            z-index: -1;
            inset: 24px;
            overflow: hidden;
            border-radius: 28px;
            background: linear-gradient(110deg, #d8c9ac, #cdbb9b 48%, #eee5d4);
        }

        .wall {
            position: absolute;
            inset: 0 auto auto 0;
            width: 68%;
            height: 72%;
            background: linear-gradient(135deg, #eee4d1, #d9c9aa);
        }

        .warm-light {
            position: absolute;
            width: 500px;
            height: 500px;
            left: 19%;
            top: 12%;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,224,160,.6), rgba(255,224,160,.15) 45%, transparent 70%);
            filter: blur(10px);
        }

        .window {
            position: absolute;
            left: 10%;
            top: 13%;
            width: 245px;
            height: 260px;
            border: 13px solid #f1e8d7;
            border-radius: 12px;
            background: linear-gradient(135deg, #7ca49c, #b6c9bd);
            box-shadow: 0 20px 45px rgba(68,71,61,.18);
            z-index: 3;
        }

        .window::before,
        .window::after {
            content: "";
            position: absolute;
            background: #f1e8d7;
        }

        .window::before {
            left: 50%;
            top: 0;
            bottom: 0;
            width: 8px;
            transform: translateX(-50%);
        }

        .window::after {
            top: 50%;
            left: 0;
            right: 0;
            height: 8px;
            transform: translateY(-50%);
        }

        .window-light {
            position: absolute;
            width: 75px;
            height: 75px;
            right: 27px;
            top: 30px;
            border-radius: 50%;
            background: #f4d99f;
            box-shadow: 0 0 40px rgba(244,217,159,.7);
        }

        .curtain {
            position: absolute;
            top: 7%;
            left: 6%;
            width: 70px;
            height: 330px;
            background: linear-gradient(90deg, #7e8170, #a9a88d, #777866);
            border-radius: 0 0 20px 20px;
            opacity: .92;
            z-index: 4;
        }

        .curtain-right {
            left: 27%;
            width: 48px;
            opacity: .65;
        }

        .picture {
            position: absolute;
            left: 43%;
            top: 14%;
            width: 150px;
            height: 105px;
            padding: 8px;
            background: #f3ead8;
            border: 2px solid #b9a987;
            box-shadow: 0 12px 25px rgba(70,64,51,.14);
            transform: rotate(2deg);
            z-index: 3;
        }

        .picture-inner {
            width: 100%;
            height: 100%;
            border-radius: 3px;
            background: linear-gradient(145deg, #7d9b8d, #d9b87d);
        }

        .floor {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 31%;
            background: linear-gradient(160deg, #9c8064, #765d4a);
            clip-path: polygon(0 15%, 68% 0, 100% 12%, 100% 100%, 0 100%);
            z-index: 2;
        }

        .rug {
            position: absolute;
            left: 17%;
            bottom: 7%;
            width: 380px;
            height: 145px;
            border-radius: 50%;
            background: radial-gradient(ellipse, #d7c5a4, #b29a76);
            transform: rotate(-4deg);
            z-index: 4;
            box-shadow: 0 15px 25px rgba(50,40,30,.14);
        }

        .bed {
            position: absolute;
            left: 24%;
            bottom: 23%;
            width: 400px;
            height: 150px;
            z-index: 6;
        }

        .bed-head {
            position: absolute;
            left: 0;
            top: -45px;
            width: 28px;
            height: 190px;
            border-radius: 10px;
            background: #6f5746;
        }

        .bed-frame {
            position: absolute;
            left: 20px;
            bottom: 0;
            width: 365px;
            height: 105px;
            border-radius: 10px;
            background: #765b49;
            box-shadow: 0 20px 30px rgba(50,40,30,.22);
        }

        .mattress {
            position: absolute;
            left: 27px;
            bottom: 71px;
            width: 350px;
            height: 62px;
            border-radius: 10px 10px 5px 5px;
            background: linear-gradient(180deg, #f1eadc, #ded2bd);
        }

        .blanket {
            position: absolute;
            right: 23px;
            bottom: 73px;
            width: 205px;
            height: 55px;
            border-radius: 7px;
            background: linear-gradient(135deg, #78958a, #4f756c);
        }

        .pillow {
            position: absolute;
            left: 48px;
            bottom: 82px;
            width: 110px;
            height: 38px;
            border-radius: 50%;
            background: #f4ecdc;
        }

        .desk {
            position: absolute;
            left: 4%;
            bottom: 20%;
            width: 210px;
            height: 115px;
            z-index: 7;
        }

        .desk-top {
            position: absolute;
            top: 0;
            width: 210px;
            height: 24px;
            border-radius: 6px;
            background: #765b49;
        }

        .desk-leg {
            position: absolute;
            top: 20px;
            width: 13px;
            height: 100px;
            background: #604a3c;
        }

        .desk-leg.left { left: 18px; }
        .desk-leg.right { right: 18px; }

        .chair {
            position: absolute;
            left: 10%;
            bottom: 14%;
            width: 85px;
            height: 100px;
            z-index: 8;
        }

        .chair-seat {
            position: absolute;
            top: 18px;
            width: 85px;
            height: 25px;
            border-radius: 8px;
            background: #526d64;
        }

        .chair-back {
            position: absolute;
            left: 7px;
            top: -20px;
            width: 70px;
            height: 50px;
            border-radius: 10px;
            background: #5f7d73;
        }

        .chair-leg {
            position: absolute;
            top: 39px;
            width: 8px;
            height: 65px;
            background: #57483d;
        }

        .chair-leg.one { left: 12px; }
        .chair-leg.two { right: 12px; }

        .lamp {
            position: absolute;
            left: 11%;
            bottom: 42%;
            z-index: 9;
        }

        .lamp-base {
            width: 50px;
            height: 10px;
            border-radius: 10px;
            background: #59483c;
        }

        .lamp-neck {
            width: 5px;
            height: 65px;
            margin-left: 22px;
            background: #59483c;
        }

        .lamp-head {
            width: 55px;
            height: 35px;
            margin-left: -3px;
            border-radius: 50% 50% 10px 10px;
            background: #e6c77f;
            box-shadow: 0 0 45px rgba(239,202,119,.75);
        }

        .plant {
            position: absolute;
            right: 4%;
            bottom: 13%;
            width: 125px;
            height: 210px;
            z-index: 7;
        }

        .plant-pot {
            position: absolute;
            bottom: 0;
            left: 30px;
            width: 70px;
            height: 65px;
            border-radius: 8px 8px 25px 25px;
            background: #b78661;
        }

        .plant-stem {
            position: absolute;
            left: 61px;
            bottom: 50px;
            width: 7px;
            height: 125px;
            background: #42695c;
        }

        .leaf {
            position: absolute;
            width: 62px;
            height: 30px;
            border-radius: 100% 0 100% 0;
            background: linear-gradient(135deg, #719678, #315e52);
        }

        .leaf.one { left: 7px; top: 56px; transform: rotate(-25deg); }
        .leaf.two { left: 57px; top: 25px; transform: rotate(22deg); }
        .leaf.three { left: 55px; top: 76px; transform: rotate(12deg); }
        .leaf.four { left: 0; top: 95px; transform: rotate(-30deg); }
        .leaf.five { left: 46px; top: 120px; transform: rotate(30deg); }

        .content {
            position: relative;
            min-height: calc(100vh - 48px);
            padding: 38px 42px;
            z-index: 20;
        }

        .logo {
            position: absolute;
            top: 38px;
            left: 42px;
            color: #17463e;
            font-size: 23px;
            font-weight: 800;
            letter-spacing: -.8px;
            line-height: 1;
            text-decoration: none;
        }

        .logo span { color: #ef7d27; }

        .intro {
            position: absolute;
            left: 6%;
            top: 31%;
            width: 390px;
            color: #17463e;
            z-index: 15;
        }

        .intro-label {
            display: inline-block;
            padding: 7px 13px;
            margin-bottom: 15px;
            border-radius: 50px;
            background: rgba(32,88,79,.12);
            border: 1px solid rgba(32,88,79,.2);
            color: #17463e;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1px;
        }

        .intro h1 {
            font-size: clamp(38px, 4vw, 58px);
            line-height: 1.08;
            letter-spacing: -2.4px;
            font-weight: 800;
        }

        .intro h1 span { color: #72543f; }

        .intro p {
            margin-top: 18px;
            max-width: 370px;
            color: #17463e;
            font-size: 13px;
            font-weight: 600;
            line-height: 1.8;
        }

        .login-card {
            position: absolute;
            z-index: 50;
            top: 50%;
            right: 3.5%;
            width: 420px;
            max-height: calc(100vh - 48px);
            overflow-y: auto;
            transform: translateY(-50%);
            padding: 38px;
            background: rgba(255,255,255,.97);
            border: 1px solid rgba(255,255,255,.7);
            border-radius: 24px;
            box-shadow: 0 30px 70px rgba(50,45,35,.22);
        }

        .login-card h2 {
            color: #17463e;
            font-size: 29px;
            font-weight: 800;
        }

        .subtitle {
            margin-top: 7px;
            margin-bottom: 27px;
            color: #4f615d;
            font-size: 13px;
            font-weight: 500;
            line-height: 1.6;
        }

        .message {
            margin-bottom: 18px;
            padding: 11px 13px;
            border-radius: 10px;
            font-size: 12px;
            line-height: 1.6;
        }

        .message.error {
            color: #a93636;
            background: #fff1f1;
            border: 1px solid #f0cccc;
        }

        .message.success {
            color: #217a48;
            background: #eef9f3;
            border: 1px solid #c7e8d5;
        }

        .form-group { margin-bottom: 17px; }

        .form-group label {
            display: block;
            margin-bottom: 7px;
            color: #31423e;
            font-size: 12px;
            font-weight: 700;
        }

        .input {
            display: block;
            width: 100%;
            height: 48px;
            padding: 0 15px;
            border: 1px solid #dce3df;
            border-radius: 11px;
            outline: none;
            background: #fafcfb;
            color: #263b36;
            font-family: inherit;
            font-size: 13px;
            transition: border-color .2s, box-shadow .2s;
        }

        .input:focus {
            border-color: #20584f;
            box-shadow: 0 0 0 3px rgba(32,88,79,.1);
            background: #fff;
        }

        .password-wrapper { position: relative; }
        .password-input { padding-right: 48px; }

        .toggle-password {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 6px;
            background: transparent;
            color: #64736f;
            font-size: 16px;
            cursor: pointer;
        }

        .toggle-password:focus-visible,
        .btn-login:focus-visible,
        a:focus-visible {
            outline: 3px solid #ef7d27;
            outline-offset: 3px;
        }

        .options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 5px 0 22px;
            font-size: 11px;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 7px;
            color: #64736f;
        }

        .remember input { accent-color: #20584f; }

        .forgot {
            color: #20584f;
            font-weight: 700;
            text-decoration: none;
        }

        .btn-login {
            width: 100%;
            min-height: 50px;
            border: none;
            border-radius: 11px;
            background: #20584f;
            color: white;
            font-family: inherit;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .btn-login:hover {
            background: #17463e;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(32,88,79,.2);
        }

        .btn-login:disabled {
            opacity: .7;
            cursor: wait;
            transform: none;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 23px 0;
            color: #7f8c88;
            font-size: 10px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e4e8e6;
        }

        .register {
            text-align: center;
            color: #5d6b67;
            font-size: 12px;
        }

        .register a {
            color: #20584f;
            font-weight: 800;
            text-decoration: none;
        }

        .register a:hover { text-decoration: underline; }

        @media (max-width: 1050px) {
            .login-card {
                right: 2.5%;
                width: 390px;
                padding: 30px;
            }

            .intro {
                left: 5%;
                width: 330px;
            }

            .intro h1 { font-size: 44px; }
        }

        @media (max-width: 850px) {
            .page { padding: 0; }

            .background {
                inset: 0;
                border-radius: 0;
                position: fixed;
            }

            .content {
                min-height: 100vh;
                padding: 25px 20px;
            }

            .logo {
                top: 25px;
                left: 25px;
            }

            .intro { display: none; }

            .login-card {
                position: relative;
                top: auto;
                right: auto;
                width: min(420px, 100%);
                max-height: none;
                margin: 85px auto 30px;
                transform: none;
                padding: 32px;
            }
        }

        @media (max-width: 480px) {
            .login-card {
                padding: 27px 22px;
                border-radius: 19px;
            }

            .login-card h2 { font-size: 25px; }

            .options { flex-wrap: wrap; }
        }

        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                transition-duration: .01ms !important;
            }
        }
    </style>
</head>

<body>
<div class="page">

    <div class="background" aria-hidden="true">
        <div class="wall"></div>
        <div class="warm-light"></div>

        <div class="window">
            <div class="window-light"></div>
        </div>

        <div class="curtain"></div>
        <div class="curtain curtain-right"></div>

        <div class="picture">
            <div class="picture-inner"></div>
        </div>

        <div class="floor"></div>
        <div class="rug"></div>

        <div class="bed">
            <div class="bed-head"></div>
            <div class="bed-frame"></div>
            <div class="mattress"></div>
            <div class="pillow"></div>
            <div class="blanket"></div>
        </div>

        <div class="desk">
            <div class="desk-top"></div>
            <div class="desk-leg left"></div>
            <div class="desk-leg right"></div>
        </div>

        <div class="chair">
            <div class="chair-back"></div>
            <div class="chair-seat"></div>
            <div class="chair-leg one"></div>
            <div class="chair-leg two"></div>
        </div>

        <div class="lamp">
            <div class="lamp-head"></div>
            <div class="lamp-neck"></div>
            <div class="lamp-base"></div>
        </div>

        <div class="plant">
            <div class="leaf one"></div>
            <div class="leaf two"></div>
            <div class="leaf three"></div>
            <div class="leaf four"></div>
            <div class="leaf five"></div>
            <div class="plant-stem"></div>
            <div class="plant-pot"></div>
        </div>
    </div>

    <main class="content">
        <a href="{{ url('/') }}" class="logo">
            Trọ <span>Ơi</span>
        </a>

        <section class="intro">
            <div class="intro-label">CHÀO MỪNG BẠN TRỞ LẠI</div>

            <h1>
                Không gian<br>
                <span>của bạn.</span>
            </h1>

            <p>
                Đăng nhập để tiếp tục tìm kiếm phòng trọ,
                quản lý tin đăng và kết nối với cộng đồng
                trên Trọ Ơi.
            </p>
        </section>

        <section class="login-card" aria-labelledby="loginTitle">
            <h2 id="loginTitle">Đăng nhập</h2>

            <p class="subtitle">
                Chào mừng bạn quay trở lại với Trọ Ơi
            </p>

            @if ($errors->any())
                <div class="message error" role="alert">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="message success" role="status">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="message error" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form
                id="loginForm"
                method="POST"
                action="{{ route('login.post') }}"
            >
                @csrf

                <div class="form-group">
                    <label for="email">Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input"
                        placeholder="Nhập email của bạn"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>

                    <div class="password-wrapper">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="input password-input"
                            placeholder="Nhập mật khẩu"
                            required
                            autocomplete="current-password"
                        >

                        <button
                            type="button"
                            class="toggle-password"
                            id="togglePassword"
                            aria-label="Hiện mật khẩu"
                            aria-pressed="false"
                        >👁</button>
                    </div>
                </div>

                <div class="options">
                    <label class="remember">
                        <input
                            type="checkbox"
                            name="remember"
                            value="1"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        Ghi nhớ đăng nhập
                    </label>

                    <a href="#" class="forgot" id="forgotLink">
                        Quên mật khẩu?
                    </a>
                </div>

                <button
                    type="submit"
                    class="btn-login"
                    id="loginButton"
                >
                    Đăng nhập
                </button>
            </form>

            <div class="divider">hoặc</div>

            <div class="register">
                Chưa có tài khoản?
                <a href="{{ route('register') }}">Đăng ký ngay</a>
            </div>
        </section>
    </main>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const loginButton = document.getElementById("loginButton");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const forgotLink = document.getElementById("forgotLink");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const showing = passwordInput.type === "password";

            passwordInput.type = showing ? "text" : "password";
            togglePassword.textContent = showing ? "🙈" : "👁";
            togglePassword.setAttribute(
                "aria-label",
                showing ? "Ẩn mật khẩu" : "Hiện mật khẩu"
            );
            togglePassword.setAttribute("aria-pressed", String(showing));
        });
    }

    if (form && loginButton) {
        form.addEventListener("submit", function (event) {
            if (!form.checkValidity()) {
                return;
            }

            loginButton.disabled = true;
            loginButton.textContent = "Đang đăng nhập...";

            // Nếu trình duyệt hủy gửi form, cho phép thử lại.
            window.setTimeout(function () {
                if (document.visibilityState === "visible") {
                    loginButton.disabled = false;
                    loginButton.textContent = "Đăng nhập";
                }
            }, 10000);
        });
    }

    if (forgotLink) {
        forgotLink.addEventListener("click", function (event) {
            event.preventDefault();
            alert("Chức năng quên mật khẩu chưa được cấu hình.");
        });
    }
});
</script>
</body>
</html>
