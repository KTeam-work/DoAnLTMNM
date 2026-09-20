<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng nhập - Trọ Ơi</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

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
            overflow: hidden;
        }

        .page {
            position: relative;
            min-height: 100vh;
            padding: 24px;
        }

        .background {
            position: absolute;
            inset: 24px;
            overflow: hidden;
            border-radius: 28px;

            background:
                linear-gradient(
                    110deg,
                    #d8c9ac 0%,
                    #cdbb9b 48%,
                    #eee5d4 100%
                );
        }

        /* =========================
           BACKGROUND
        ========================= */

        .warm-light {
            position: absolute;
            width: 500px;
            height: 500px;
            left: 19%;
            top: 12%;
            border-radius: 50%;

            background:
                radial-gradient(
                    circle,
                    rgba(255, 224, 160, .6),
                    rgba(255, 224, 160, .15) 45%,
                    transparent 70%
                );

            filter: blur(10px);
        }

        .wall {
            position: absolute;
            left: 0;
            top: 0;
            width: 68%;
            height: 72%;

            background:
                linear-gradient(
                    135deg,
                    #eee4d1,
                    #d9c9aa
                );
        }

        .window {
            position: absolute;
            left: 10%;
            top: 13%;
            width: 245px;
            height: 260px;

            border: 13px solid #f1e8d7;
            border-radius: 12px;

            background:
                linear-gradient(
                    135deg,
                    #7ca49c,
                    #b6c9bd
                );

            box-shadow:
                0 20px 45px rgba(68, 71, 61, .18);

            z-index: 3;
        }

        .window::before {
            content: "";

            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 8px;

            transform: translateX(-50%);
            background: #f1e8d7;
        }

        .window::after {
            content: "";

            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 8px;

            transform: translateY(-50%);
            background: #f1e8d7;
        }

        .window-light {
            position: absolute;

            width: 75px;
            height: 75px;

            right: 27px;
            top: 30px;

            border-radius: 50%;

            background: #f4d99f;

            box-shadow:
                0 0 40px rgba(244, 217, 159, .7);
        }

        .curtain {
            position: absolute;

            top: 7%;
            left: 6%;

            width: 70px;
            height: 330px;

            background:
                linear-gradient(
                    90deg,
                    #7e8170,
                    #a9a88d,
                    #777866
                );

            border-radius: 0 0 20px 20px;
            opacity: .92;

            z-index: 4;

            box-shadow:
                12px 8px 20px rgba(60, 60, 50, .1);
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

            box-shadow:
                0 12px 25px rgba(70, 64, 51, .14);

            transform: rotate(2deg);

            z-index: 3;
        }

        .picture-inner {
            width: 100%;
            height: 100%;

            border-radius: 3px;

            background:
                linear-gradient(
                    145deg,
                    #7d9b8d,
                    #d9b87d
                );
        }

        .floor {
            position: absolute;

            left: 0;
            bottom: 0;

            width: 100%;
            height: 31%;

            background:
                linear-gradient(
                    160deg,
                    #9c8064,
                    #765d4a
                );

            clip-path: polygon(
                0 15%,
                68% 0,
                100% 12%,
                100% 100%,
                0 100%
            );

            z-index: 2;
        }

        .rug {
            position: absolute;

            left: 17%;
            bottom: 7%;

            width: 380px;
            height: 145px;

            border-radius: 50%;

            background:
                radial-gradient(
                    ellipse,
                    #d7c5a4,
                    #b29a76
                );

            transform: rotate(-4deg);

            z-index: 4;

            box-shadow:
                0 15px 25px rgba(50, 40, 30, .14);
        }

        /* =========================
           BED
        ========================= */

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

            box-shadow:
                0 20px 30px rgba(50, 40, 30, .22);
        }

        .mattress {
            position: absolute;

            left: 27px;
            bottom: 71px;

            width: 350px;
            height: 62px;

            border-radius: 10px 10px 5px 5px;

            background:
                linear-gradient(
                    180deg,
                    #f1eadc,
                    #ded2bd
                );

            box-shadow:
                0 8px 15px rgba(50, 40, 30, .13);
        }

        .blanket {
            position: absolute;

            right: 23px;
            bottom: 73px;

            width: 205px;
            height: 55px;

            border-radius: 7px;

            background:
                linear-gradient(
                    135deg,
                    #78958a,
                    #4f756c
                );
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

        /* =========================
           DESK
        ========================= */

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

            box-shadow:
                0 10px 15px rgba(40, 30, 20, .16);
        }

        .desk-leg {
            position: absolute;

            top: 20px;

            width: 13px;
            height: 100px;

            background: #604a3c;
        }

        .desk-leg.left {
            left: 18px;
        }

        .desk-leg.right {
            right: 18px;
        }

        /* =========================
           CHAIR
        ========================= */

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

        .chair-leg.one {
            left: 12px;
        }

        .chair-leg.two {
            right: 12px;
        }

        /* =========================
           LAMP
        ========================= */

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

            box-shadow:
                0 0 45px rgba(239, 202, 119, .75);
        }

        /* =========================
           PLANT
        ========================= */

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

            background:
                linear-gradient(
                    135deg,
                    #719678,
                    #315e52
                );
        }

        .leaf.one {
            left: 7px;
            top: 56px;
            transform: rotate(-25deg);
        }

        .leaf.two {
            left: 57px;
            top: 25px;
            transform: rotate(22deg);
        }

        .leaf.three {
            left: 55px;
            top: 76px;
            transform: rotate(12deg);
        }

        .leaf.four {
            left: 0;
            top: 95px;
            transform: rotate(-30deg);
        }

        .leaf.five {
            left: 46px;
            top: 120px;
            transform: rotate(30deg);
        }

        /* =========================
           CONTENT
        ========================= */

        .content {
            position: relative;
            z-index: 20;

            min-height: calc(100vh - 48px);

            padding: 38px 42px;
        }

        /* =========================
           LOGO TRỌ ƠI
        ========================= */

        .logo {
            position: absolute;

            top: 38px;
            left: 42px;

            color: #17463e;

            font-size: 23px;
            font-weight: 800;

            letter-spacing: -0.8px;
            line-height: 1;

            text-decoration: none;
        }

        .logo span {
            color: #ef7d27;
        }

        /* =========================
           INTRO
        ========================= */

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

            background: rgba(32, 88, 79, .12);
            border: 1px solid rgba(32, 88, 79, .20);

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

            color: #17463e;
        }

        .intro h1 span {
            color: #72543f;
        }

        /* ĐOẠN MÔ TẢ ĐƯỢC TĂNG ĐỘ TƯƠNG PHẢN */
        .intro p {
            margin-top: 18px;

            max-width: 370px;

            color: #118337;

            font-size: 13px;
            font-weight: 600;
            line-height: 1.8;

            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.2);
        }

        /* =========================
           LOGIN CARD
        ========================= */

        .login-card {
            position: absolute;

            z-index: 50;

            top: 50%;
            right: 3.5%;

            width: 420px;

            transform: translateY(-50%);

            padding: 38px;

            background: rgba(255, 255, 255, .97);

            border: 1px solid rgba(255, 255, 255, .7);

            border-radius: 24px;

            box-shadow:
                0 30px 70px rgba(50, 45, 35, .22);
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

        /* =========================
           MESSAGE
        ========================= */

        .message {
            display: none;

            margin-bottom: 18px;

            padding: 11px 13px;

            border-radius: 10px;

            font-size: 11px;

            line-height: 1.6;
        }

        .message.error {
            display: block;

            color: #a93636;

            background: #fff1f1;

            border: 1px solid #f0cccc;
        }

        .message.success {
            display: block;

            color: #217a48;

            background: #eef9f3;

            border: 1px solid #c7e8d5;
        }

        /* =========================
           INPUT
        ========================= */

        .form-group {
            margin-bottom: 17px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            color: #31423e;

            font-size: 12px;
            font-weight: 700;
        }

        .input {
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

            transition: .2s ease;
        }

        .input:focus {
            border-color: #20584f;

            box-shadow:
                0 0 0 3px rgba(32, 88, 79, .08);

            background: white;
        }

        /* =========================
           OPTIONS
        ========================= */

        .options {
            display: flex;

            align-items: center;
            justify-content: space-between;

            margin: 5px 0 22px;

            font-size: 11px;
        }

        .remember {
            display: flex;

            align-items: center;

            gap: 7px;

            color: #64736f;
        }

        .remember input {
            accent-color: #20584f;
        }

        .forgot {
            color: #20584f;

            font-weight: 700;

            text-decoration: none;
        }

        /* =========================
           BUTTON
        ========================= */

        .btn-login {
            width: 100%;
            height: 50px;

            border: none;

            border-radius: 11px;

            background: #20584f;

            color: white;

            font-family: inherit;

            font-size: 13px;

            font-weight: 700;

            cursor: pointer;

            transition: .2s ease;
        }

        .btn-login:hover {
            background: #17463e;

            transform: translateY(-1px);

            box-shadow:
                0 8px 20px rgba(32, 88, 79, .2);
        }

        .btn-login:disabled {
            opacity: .7;

            cursor: not-allowed;

            transform: none;
        }

        /* =========================
           DIVIDER
        ========================= */

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

        /* =========================
           REGISTER
        ========================= */

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

        .register a:hover {
            text-decoration: underline;
        }

        /* =========================
           RESPONSIVE
        ========================= */

        @media (max-width: 1050px) {

            .login-card {
                right: 2.5%;

                width: 390px;
            }

            .intro {
                left: 5%;

                width: 330px;
            }

            .intro h1 {
                font-size: 44px;
            }
        }

        @media (max-width: 850px) {

            body {
                overflow: auto;
            }

            .page {
                padding: 0;
            }

            .background {
                inset: 0;

                border-radius: 0;
            }

            .content {
                min-height: 100vh;

                padding: 30px 20px;
            }

            .logo {
                top: 25px;
                left: 25px;
            }

            .intro {
                display: none;
            }

            .login-card {
                position: relative;

                top: auto;
                right: auto;

                width: min(420px, 100%);

                margin: 85px auto 0;

                transform: none;
            }
        }

        @media (max-width: 480px) {

            .login-card {
                padding: 28px 23px;

                border-radius: 19px;
            }

            .login-card h2 {
                font-size: 25px;
            }
        }

        /* =========================
   PASSWORD TOGGLE
========================= */

.password-wrapper {
    position: relative;
}

.password-input {
    padding-right: 48px;
}

.toggle-password {
    position: absolute;

    top: 50%;
    right: 14px;

    transform: translateY(-50%);

    width: 28px;
    height: 28px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    border: none;
    background: transparent;

    color: #64736f;

    font-size: 16px;

    cursor: pointer;

    transition: .2s ease;
}

.toggle-password:hover {
    color: #20584f;
    transform: translateY(-50%) scale(1.08);
}

.toggle-password:focus {
    outline: none;
}
    </style>

</head>

<body>

<div class="page">

    <!-- =========================
         BACKGROUND
    ========================= -->

    <div class="background">

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

        <!-- BED -->

        <div class="bed">

            <div class="bed-head"></div>

            <div class="bed-frame"></div>

            <div class="mattress"></div>

            <div class="pillow"></div>

            <div class="blanket"></div>

        </div>

        <!-- DESK -->

        <div class="desk">

            <div class="desk-top"></div>

            <div class="desk-leg left"></div>
            <div class="desk-leg right"></div>

        </div>

        <!-- CHAIR -->

        <div class="chair">

            <div class="chair-back"></div>

            <div class="chair-seat"></div>

            <div class="chair-leg one"></div>
            <div class="chair-leg two"></div>

        </div>

        <!-- LAMP -->

        <div class="lamp">

            <div class="lamp-head"></div>

            <div class="lamp-neck"></div>

            <div class="lamp-base"></div>

        </div>

        <!-- PLANT -->

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

    <!-- =========================
         CONTENT
    ========================= -->

    <div class="content">

        <!-- LOGO -->

        <div class="logo">
            Trọ <span>Ơi</span>
        </div>

        <!-- INTRO -->

        <div class="intro">

            <div class="intro-label">
                CHÀO MỪNG BẠN TRỞ LẠI
            </div>

            <h1>
                Không gian
                <span>của bạn.</span>
            </h1>

            <p>
                Đăng nhập để tiếp tục tìm kiếm phòng trọ,
                quản lý tin đăng và kết nối với cộng đồng
                trên Trọ Ơi.
            </p>

        </div>

        <!-- =========================
             LOGIN CARD
        ========================= -->

        <div class="login-card">

            <h2>
                Đăng nhập
            </h2>

            <p class="subtitle">
                Chào mừng bạn quay trở lại với Trọ Ơi
            </p>

            <!-- ERROR -->

            @if ($errors->any())

                <div class="message error">

                    @foreach ($errors->all() as $error)

                        <div>
                            {{ $error }}
                        </div>

                    @endforeach

                </div>

            @endif

            <!-- SUCCESS -->

            @if (session('success'))

                <div class="message success">
                    {{ session('success') }}
                </div>

            @endif

            <!-- SESSION ERROR -->

            @if (session('error'))

                <div class="message error">
                    {{ session('error') }}
                </div>

            @endif

            <!-- =========================
                 LOGIN FORM
            ========================= -->

            <form
                method="POST"
                action="{{ route('login.post') }}"
            >

                @csrf

                <!-- EMAIL -->

                <div class="form-group">

                    <label for="email">
                        Email
                    </label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input"
                        placeholder="Nhập email của bạn"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                    >

                </div>

                <!-- PASSWORD -->

<div class="form-group">

    <label for="password">
        Mật khẩu
    </label>

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
        >
            👁
        </button>

    </div>

</div>

                <!-- OPTIONS -->

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

                    <a
                        href="#"
                        class="forgot"
                    >
                        Quên mật khẩu?
                    </a>

                </div>

                <!-- BUTTON -->

                <button
                    type="submit"
                    class="btn-login"
                    id="loginButton"
                >
                    Đăng nhập
                </button>

            </form>

            <!-- DIVIDER -->

            <div class="divider">
                hoặc
            </div>

            <!-- REGISTER -->

            <div class="register">

                Chưa có tài khoản?

                <a href="{{ route('register') }}">
                    Đăng ký ngay
                </a>

            </div>

        </div>

    </div>

</div>

<script>

    const loginForm = document.querySelector("form");
    const loginButton = document.getElementById("loginButton");

    if (loginForm && loginButton) {

        loginForm.addEventListener("submit", function () {

            loginButton.disabled = true;

            loginButton.innerText = "Đang đăng nhập...";

        });

    }

const passwordInput = document.getElementById("password");
const togglePassword = document.getElementById("togglePassword");

if (passwordInput && togglePassword) {

    togglePassword.addEventListener("click", function () {

        if (passwordInput.type === "password") {

            passwordInput.type = "text";

            togglePassword.innerText = "🙈";
            togglePassword.setAttribute(
                "aria-label",
                "Ẩn mật khẩu"
            );

        } else {

            passwordInput.type = "password";

            togglePassword.innerText = "👁";
            togglePassword.setAttribute(
                "aria-label",
                "Hiện mật khẩu"
            );

        }

    });

}
</script>

</body>
</html>

