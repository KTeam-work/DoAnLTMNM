<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">

```
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Đăng ký - Trọ Ơi</title>

<link
    href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>

<style>

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        min-height: 100vh;

        font-family: "Be Vietnam Pro", sans-serif;

        background: #dfe5e1;
    }

    .page {
        min-height: 100vh;

        padding: 24px;

        position: relative;

        display: flex;
        align-items: center;
        justify-content: center;

        overflow: hidden;
    }

    .background {
        position: absolute;

        inset: 24px;

        border-radius: 28px;

        overflow: hidden;

        background: #d8dedb;
    }


    /* =====================================================
       NGƯỜI THUÊ BACKGROUND
    ===================================================== */

    .tenant-bg {
        position: absolute;

        inset: 0;

        background:
            linear-gradient(
                90deg,
                rgba(23, 70, 62, .88) 0%,
                rgba(23, 70, 62, .55) 42%,
                rgba(23, 70, 62, .12) 75%,
                rgba(23, 70, 62, .20) 100%
            ),
            linear-gradient(
                135deg,
                #d7d0c0,
                #eee8dc
            );

        transition: .5s ease;
    }

    .tenant-window {
        position: absolute;

        width: 31%;
        height: 58%;

        top: 12%;
        left: 15%;

        background:
            linear-gradient(
                135deg,
                #dcebe8,
                #b8d4cf
            );

        border: 12px solid #f8f4ea;

        box-shadow:
            inset 0 0 0 2px rgba(0, 0, 0, .04),
            0 18px 35px rgba(0, 0, 0, .10);
    }

    .tenant-window::before {
        content: "";

        position: absolute;

        left: 50%;
        top: 0;
        bottom: 0;

        width: 5px;

        background: #f8f4ea;
    }

    .tenant-window::after {
        content: "";

        position: absolute;

        left: 0;
        right: 0;
        top: 50%;

        height: 5px;

        background: #f8f4ea;
    }

    .tenant-floor {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        height: 27%;

        background:
            linear-gradient(
                135deg,
                #b9aa91,
                #a9967b
            );
    }

    .tenant-sofa {
        position: absolute;

        left: 34%;
        bottom: 17%;

        width: 25%;
        height: 18%;

        background: #e8dfcd;

        border-radius: 16px 16px 8px 8px;

        box-shadow:
            0 18px 30px rgba(0, 0, 0, .14);
    }

    .tenant-sofa::before {
        content: "";

        position: absolute;

        left: 5%;
        right: 5%;
        top: -32%;

        height: 48%;

        background: #f4eddf;

        border-radius: 14px 14px 6px 6px;
    }

    .tenant-table {
        position: absolute;

        width: 15%;
        height: 7%;

        left: 17%;
        bottom: 22%;

        background: #735a43;

        border-radius: 50%;

        box-shadow:
            0 12px 18px rgba(0, 0, 0, .12);
    }

    .tenant-plant {
        position: absolute;

        left: 8%;
        bottom: 22%;

        width: 90px;
        height: 150px;
    }

    .plant-pot {
        position: absolute;

        bottom: 0;
        left: 25px;

        width: 40px;
        height: 45px;

        background: #c7a681;

        border-radius: 5px 5px 12px 12px;
    }

    .plant-leaves {
        position: absolute;

        bottom: 35px;
        left: 22px;

        width: 48px;
        height: 90px;

        background:
            radial-gradient(
                ellipse at center,
                #638275 0%,
                #42685c 70%
            );

        border-radius: 50% 50% 40% 40%;
    }


    /* =====================================================
       CHỦ TRỌ BACKGROUND
    ===================================================== */

    .owner-bg {
        position: absolute;

        inset: 0;

        opacity: 0;

        transform: scale(1.03);

        background:
            linear-gradient(
                90deg,
                rgba(23, 70, 62, .94) 0%,
                rgba(23, 70, 62, .72) 42%,
                rgba(23, 70, 62, .22) 78%,
                rgba(23, 70, 62, .34) 100%
            ),
            linear-gradient(
                135deg,
                #c6c0b0,
                #e7e0d2
            );

        transition:
            opacity .45s ease,
            transform .45s ease;
    }

    .owner-building {
        position: absolute;

        left: 12%;
        top: 11%;

        width: 43%;
        height: 65%;

        background:
            linear-gradient(
                90deg,
                #d7d1c4,
                #eee9df
            );

        border-radius: 10px 10px 0 0;

        box-shadow:
            0 20px 45px rgba(0, 0, 0, .16);
    }

    .building-title {
        height: 22%;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 24px;

        font-weight: 800;

        color: #20584f;

        letter-spacing: 2px;

        background: #f4efe4;
    }

    .building-rooms {
        padding: 25px;

        display: grid;

        grid-template-columns: repeat(2, 1fr);

        gap: 18px;
    }

    .building-rooms span {
        height: 75px;

        background:
            linear-gradient(
                135deg,
                #b8d0cb,
                #d9e6e2
            );

        border: 7px solid #f5f0e6;

        box-shadow:
            inset 0 0 0 2px rgba(32, 88, 79, .10);
    }

    .owner-desk {
        position: absolute;

        right: 18%;
        bottom: 20%;

        width: 24%;
        height: 8%;

        background: #745b45;

        border-radius: 8px;

        box-shadow:
            0 15px 25px rgba(0, 0, 0, .15);
    }

    .owner-desk::before {
        content: "";

        position: absolute;

        left: 10%;
        right: 10%;
        top: 15px;

        height: 70px;

        background: #e9e1d1;

        border-radius: 6px;
    }

    .owner-key {
        position: absolute;

        right: 14%;
        bottom: 31%;

        width: 70px;
        height: 70px;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 34px;

        background: rgba(242, 223, 181, .92);

        border-radius: 50%;

        box-shadow:
            0 15px 30px rgba(0, 0, 0, .15);

        transform: rotate(-12deg);
    }

    .owner-floor {
        position: absolute;

        left: 0;
        right: 0;
        bottom: 0;

        height: 24%;

        background:
            linear-gradient(
                135deg,
                #a99b82,
                #bcae94
            );
    }


    /* =====================================================
       ROLE BACKGROUND
    ===================================================== */

    .background.owner-active .tenant-bg {
        opacity: 0;

        transform: scale(.98);
    }

    .background.owner-active .owner-bg {
        opacity: 1;

        transform: scale(1);
    }


    /* =====================================================
       CONTENT
    ===================================================== */

    .content {
        width: 100%;

        max-width: 1200px;

        min-height: calc(100vh - 48px);

        position: relative;

        z-index: 5;

        display: flex;

        flex-direction: column;

        justify-content: space-between;

        padding: 38px 42px;
    }


    /* =====================================================
       LOGO TRỌ ƠI
    ===================================================== */

    .logo {
        display: block;

        color: #ffffff;

        font-size: 23px;

        font-weight: 800;

        letter-spacing: -0.8px;

        line-height: 1;

        text-decoration: none;
    }

    .logo span {
        color: #ef7d27;
    }


    /* =====================================================
       INTRO
    ===================================================== */

    .intro {
        color: white;

        width: 45%;

        margin-bottom: 40px;
    }

    .intro-label {
        display: inline-block;

        padding: 7px 12px;

        border-radius: 20px;

        background: rgba(255, 255, 255, .13);

        font-size: 10px;

        font-weight: 600;

        margin-bottom: 18px;

        backdrop-filter: blur(5px);
    }

    .intro h1 {
        margin: 0;

        font-size: 44px;

        line-height: 1.2;

        letter-spacing: -1.5px;

        font-weight: 800;
    }

    .intro h1 span {
        color: #f2dfb5;
    }

    .intro p {
        margin-top: 20px;

        max-width: 440px;

        color: rgba(255, 255, 255, .75);

        font-size: 13px;

        line-height: 1.8;
    }


    /* =====================================================
       REGISTER CARD
    ===================================================== */

    .register-card {
        position: absolute;

        z-index: 10;

        right: 6%;
        top: 50%;

        transform: translateY(-50%);

        width: 440px;

        padding: 34px 38px;

        background: rgba(255, 255, 255, .97);

        border-radius: 22px;

        box-shadow:
            0 25px 70px rgba(20, 45, 39, .22),
            0 5px 15px rgba(0, 0, 0, .05);
    }


    /* =====================================================
       HEADER
    ===================================================== */

    .card-header {
        margin-bottom: 22px;
    }

    .card-header h2 {
        margin: 0 0 6px;

        font-size: 25px;

        letter-spacing: -.5px;

        font-weight: 800;

        color: #1b2925;
    }

    .card-header p {
        margin: 0;

        font-size: 11px;

        color: #8a9490;
    }


    /* =====================================================
       MESSAGE
    ===================================================== */

    .error-box {
        margin-bottom: 15px;

        padding: 10px 12px;

        border-radius: 9px;

        background: #fff1f1;

        border: 1px solid #f2cccc;

        color: #b33a3a;

        font-size: 10px;

        line-height: 1.6;
    }

    .success-box {
        margin-bottom: 15px;

        padding: 10px 12px;

        border-radius: 9px;

        background: #eef9f3;

        border: 1px solid #c7e8d5;

        color: #217a48;

        font-size: 10px;

        line-height: 1.6;
    }


    /* =====================================================
       FORM
    ===================================================== */

    .field {
        margin-bottom: 14px;
    }

    .field label {
        display: block;

        margin-bottom: 6px;

        font-size: 10px;

        font-weight: 700;

        color: #394640;
    }

    .field input {
        width: 100%;

        height: 43px;

        border: 1px solid #e0e5e2;

        border-radius: 9px;

        padding: 0 13px;

        outline: none;

        font-family: inherit;

        font-size: 11px;

        color: #26342f;

        background: #fff;

        transition: .2s;
    }

    .field input::placeholder {
        color: #b2bab7;
    }

    .field input:focus {
        border-color: #20584f;

        box-shadow:
            0 0 0 3px rgba(32, 88, 79, .07);
    }

    .field input.input-error {
        border-color: #d9534f;

        background: #fffafa;
    }

    .field input.input-error:focus {
        border-color: #c0392b;

        box-shadow:
            0 0 0 3px rgba(192, 57, 43, .08);
    }

    .field-error {
        margin-top: 5px;

        font-size: 9px;

        line-height: 1.4;

        color: #c0392b;
    }


    /* =====================================================
       HỌ VÀ TÊN
    ===================================================== */

    #name {
        ime-mode: auto;

        spellcheck: false;
    }


    /* =====================================================
       ROW
    ===================================================== */

    .row {
        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: 12px;
    }


    /* =====================================================
       ROLE
    ===================================================== */

    .role-title {
        font-size: 10px;

        font-weight: 700;

        color: #394640;

        margin-bottom: 7px;
    }

    .roles {
        display: grid;

        grid-template-columns: 1fr 1fr;

        gap: 10px;

        margin-bottom: 14px;
    }

    .role-input {
        display: none;
    }

    .role {
        border: 1px solid #e0e5e2;

        border-radius: 10px;

        padding: 10px;

        display: flex;

        align-items: center;

        gap: 9px;

        cursor: pointer;

        transition: .2s;
    }

    .role:hover {
        border-color: #b8c9c4;
    }

    .role-input:checked + .role {
        border-color: #20584f;

        background: #f1f7f5;
    }

    .role-icon {
        width: 30px;
        height: 30px;

        border-radius: 8px;

        background: #f0f2f1;

        display: flex;

        align-items: center;
        justify-content: center;

        font-size: 13px;
    }

    .role-input:checked + .role .role-icon {
        background: #20584f;
    }

    .role-text strong {
        display: block;

        font-size: 10px;

        color: #27352f;
    }

    .role-text small {
        font-size: 8px;

        color: #949d9a;
    }


    /* =====================================================
       TERMS
    ===================================================== */

    .terms {
        display: flex;

        gap: 7px;

        align-items: flex-start;

        margin: 3px 0 17px;

        color: #8a9490;

        font-size: 9px;

        line-height: 1.55;
    }

    .terms input {
        margin-top: 2px;

        accent-color: #20584f;
    }

    .terms a {
        color: #20584f;

        font-weight: 600;

        text-decoration: none;
    }


    /* =====================================================
       BUTTON
    ===================================================== */

    .register-button {
        width: 100%;

        height: 44px;

        border: 0;

        border-radius: 9px;

        background: #20584f;

        color: white;

        font-family: inherit;

        font-size: 11px;

        font-weight: 700;

        cursor: pointer;

        box-shadow:
            0 7px 16px rgba(32, 88, 79, .16);

        transition: .2s;
    }

    .register-button:hover {
        background: #17463e;

        transform: translateY(-1px);
    }


    /* =====================================================
       LOGIN
    ===================================================== */

    .login {
        text-align: center;

        margin-top: 17px;

        font-size: 10px;

        color: #8b9491;
    }

    .login a {
        color: #20584f;

        font-weight: 700;

        text-decoration: none;
    }

    .login a:hover {
        color: #17463e;

        text-decoration: underline;
    }


    /* =====================================================
       RESPONSIVE
    ===================================================== */

    @media(max-width: 900px) {

        .page {
            padding: 0;
        }

        .background {
            inset: 0;

            border-radius: 0;
        }

        .content {
            min-height: 100vh;

            padding: 28px;
        }

        .intro {
            width: 100%;

            margin-top: 80px;
        }

        .intro h1 {
            font-size: 34px;
        }

        .register-card {
            position: relative;

            right: auto;
            top: auto;

            transform: none;

            width: 100%;

            max-width: 500px;

            margin: 0 auto;
        }

        .tenant-window {
            left: 8%;

            width: 45%;
        }

        .tenant-sofa {
            left: 30%;

            width: 35%;
        }

        .owner-building {
            left: 5%;

            width: 50%;
        }

        .owner-desk {
            right: 8%;

            width: 30%;
        }
    }

    @media(max-width: 500px) {

        .row {
            grid-template-columns: 1fr;

            gap: 0;
        }

        .roles {
            grid-template-columns: 1fr;
        }

        .register-card {
            padding: 28px 22px;
        }

        .intro {
            display: none;
        }

        .tenant-window,
        .owner-building {
            opacity: .65;
        }

        .tenant-sofa {
            left: 25%;

            width: 50%;
        }

        .owner-building {
            width: 65%;
        }

        .owner-key {
            right: 8%;

            width: 55px;
            height: 55px;

            font-size: 27px;
        }
    }

    /* =====================================================
   PASSWORD TOGGLE
===================================================== */

.password-wrapper {
    position: relative;
}

.password-wrapper input {
    padding-right: 42px;
}

.toggle-password {
    position: absolute;

    top: 50%;
    right: 10px;

    transform: translateY(-50%);

    width: 26px;
    height: 26px;

    display: flex;
    align-items: center;
    justify-content: center;

    padding: 0;

    border: none;
    background: transparent;

    color: #64736f;

    font-size: 14px;

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

```
<!-- =====================================================
     BACKGROUND
===================================================== -->

<div class="background" id="background">


    <!-- NGƯỜI THUÊ -->

    <div class="tenant-bg">

        <div class="tenant-window"></div>

        <div class="tenant-sofa"></div>

        <div class="tenant-table"></div>

        <div class="tenant-plant">

            <div class="plant-leaves"></div>

            <div class="plant-pot"></div>

        </div>

        <div class="tenant-floor"></div>

    </div>


    <!-- CHỦ TRỌ -->

    <div class="owner-bg">

        <div class="owner-building">

            <div class="building-title">
                TRỌ ƠI
            </div>

            <div class="building-rooms">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>

        </div>

        <div class="owner-desk"></div>

        <div class="owner-key">
            🔑
        </div>

        <div class="owner-floor"></div>

    </div>

</div>


<!-- =====================================================
     CONTENT
===================================================== -->

<div class="content">


    <!-- LOGO -->

    <div class="logo">
        Trọ <span>Ơi</span>
    </div>


    <!-- INTRO -->

    <div class="intro">

        <div class="intro-label">
            KHÔNG GIAN CỦA BẠN
        </div>

        <h1>
            Tìm nơi ở
            <br>
            <span>theo cách của bạn.</span>
        </h1>

        <p>
            Một tài khoản duy nhất để khám phá phòng trọ,
            lưu lại những nơi bạn yêu thích và kết nối trực tiếp
            với chủ trọ.
        </p>

    </div>

</div>


<!-- =====================================================
     REGISTER CARD
===================================================== -->

<div class="register-card">


    <div class="card-header">

        <h2>
            Tạo tài khoản
        </h2>

        <p>
            Điền thông tin bên dưới để bắt đầu với Trọ Ơi.
        </p>

    </div>


    <!-- =================================================
         THÔNG BÁO LỖI
    ================================================= -->

    @if ($errors->any())

        <div class="error-box">

            <strong>
                Vui lòng kiểm tra lại thông tin.
            </strong>

            <ul style="margin: 7px 0 0 18px; padding: 0;">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    <!-- =================================================
         THÔNG BÁO THÀNH CÔNG
    ================================================= -->

    @if (session('success'))

        <div class="success-box">
            {{ session('success') }}
        </div>

    @endif


    <!-- =================================================
         FORM
    ================================================= -->

    <form
        id="registerForm"
        method="POST"
        action="{{ route('register.post') }}"
        accept-charset="UTF-8"
    >

        @csrf


        <!-- =================================================
             NAME + EMAIL
        ================================================= -->

        <div class="row">


            <!-- HỌ VÀ TÊN -->

            <div class="field">

                <label for="name">
                    HỌ VÀ TÊN
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Nguyễn Văn A"
                    autocomplete="name"
                    lang="vi"
                    spellcheck="false"
                    autocorrect="off"
                    autocapitalize="words"
                    class="@error('name') input-error @enderror"
                    required
                >

                @error('name')

                    <div class="field-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>


            <!-- EMAIL -->

            <div class="field">

                <label for="email">
                    EMAIL
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="example@email.com"
                    autocomplete="email"
                    class="@error('email') input-error @enderror"
                    required
                >

                @error('email')

                    <div class="field-error">
                        {{ $message }}
                    </div>

                @enderror

            </div>

        </div>


        <!-- =================================================
             PHONE
        ================================================= -->

        <div class="field">

            <label for="phone">
                SỐ ĐIỆN THOẠI
            </label>

            <input
                type="tel"
                id="phone"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="Nhập số điện thoại"
                autocomplete="tel"
                class="@error('phone') input-error @enderror"
                required
            >

            @error('phone')

                <div class="field-error">
                    {{ $message }}
                </div>

            @enderror

        </div>


        <!-- =================================================
             ROLE
        ================================================= -->

        <div class="role-title">
            BẠN MUỐN SỬ DỤNG VỚI VAI TRÒ
        </div>

        <div class="roles">


            <!-- NGƯỜI THUÊ -->

            <div>

                <input
                    class="role-input"
                    type="radio"
                    name="role"
                    id="tenant"
                    value="tenant"
                    {{ old('role', 'tenant') === 'tenant' ? 'checked' : '' }}
                >

                <label
                    class="role"
                    for="tenant"
                >

                    <div class="role-icon">
                        🏠
                    </div>

                    <div class="role-text">

                        <strong>
                            Người thuê
                        </strong>

                        <small>
                            Tìm kiếm phòng
                        </small>

                    </div>

                </label>

            </div>


            <!-- CHỦ TRỌ -->

            <div>

                <input
                    class="role-input"
                    type="radio"
                    name="role"
                    id="owner"
                    value="owner"
                    {{ old('role') === 'owner' ? 'checked' : '' }}
                >

                <label
                    class="role"
                    for="owner"
                >

                    <div class="role-icon">
                        🔑
                    </div>

                    <div class="role-text">

                        <strong>
                            Chủ trọ
                        </strong>

                        <small>
                            Đăng tin cho thuê
                        </small>

                    </div>

                </label>

            </div>

        </div>

        @error('role')

            <div
                class="field-error"
                style="margin-top: -8px; margin-bottom: 12px;"
            >
                {{ $message }}
            </div>

        @enderror


        <!-- =================================================
             PASSWORD
        ================================================= -->

        <div class="row">

    <!-- MẬT KHẨU -->

    <div class="field">

        <label for="password">
            MẬT KHẨU
        </label>

        <div class="password-wrapper">

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Nhập mật khẩu"
                autocomplete="new-password"
                class="@error('password') input-error @enderror"
                required
            >

            <button
                type="button"
                class="toggle-password"
                data-target="password"
                aria-label="Hiện mật khẩu"
            >
                👁
            </button>

        </div>

        @error('password')

            <div class="field-error">
                {{ $message }}
            </div>

        @enderror

    </div>


    <!-- XÁC NHẬN -->

    <div class="field">

        <label for="password_confirmation">
            XÁC NHẬN MẬT KHẨU
        </label>

        <div class="password-wrapper">

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                placeholder="Nhập lại mật khẩu"
                autocomplete="new-password"
                required
            >

            <button
                type="button"
                class="toggle-password"
                data-target="password_confirmation"
                aria-label="Hiện mật khẩu"
            >
                👁
            </button>

        </div>

    </div>

</div>


        <!-- =================================================
             TERMS
        ================================================= -->

        <label class="terms">

            <input
                type="checkbox"
                name="terms"
                value="1"
                required
            >

            <span>

                Tôi đồng ý với

                <a href="#">
                    Điều khoản sử dụng
                </a>

                và

                <a href="#">
                    Chính sách bảo mật
                </a>

                của Trọ Ơi.

            </span>

        </label>


        <!-- =================================================
             BUTTON
        ================================================= -->

        <button
            type="submit"
            class="register-button"
            id="registerButton"
        >
            Tạo tài khoản
        </button>

    </form>


    <!-- =================================================
         LOGIN
    ================================================= -->

    <div class="login">

        Đã có tài khoản?

        <a href="{{ route('login') }}">
            Đăng nhập
        </a>

    </div>

</div>
```

</div>

<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

    /*
    |--------------------------------------------------------------------------
    | Đổi background theo role
    |--------------------------------------------------------------------------
    */

    const tenantRadio = document.getElementById("tenant");
    const ownerRadio = document.getElementById("owner");
    const background = document.getElementById("background");

    function updateBackground() {

        if (ownerRadio.checked) {

            background.classList.add("owner-active");

        } else {

            background.classList.remove("owner-active");

        }

    }

    tenantRadio.addEventListener("change", updateBackground);

    ownerRadio.addEventListener("change", updateBackground);

    updateBackground();


    /*
    |--------------------------------------------------------------------------
    | Chặn submit nhiều lần
    |--------------------------------------------------------------------------
    */

    const registerForm = document.getElementById("registerForm");
    const registerButton = document.getElementById("registerButton");

    if (registerForm && registerButton) {

        registerForm.addEventListener("submit", function () {

            registerButton.disabled = true;

            registerButton.innerText = "Đang tạo tài khoản...";

        });

    }

    /*
|--------------------------------------------------------------------------
| Ẩn / hiện mật khẩu
|--------------------------------------------------------------------------
*/

document.querySelectorAll(".toggle-password").forEach(function (button) {

    button.addEventListener("click", function () {

        const input = document.getElementById(button.dataset.target);

        if (!input) return;

        if (input.type === "password") {

            input.type = "text";

            button.innerText = "🙈";
            button.setAttribute("aria-label", "Ẩn mật khẩu");

        } else {

            input.type = "password";

            button.innerText = "👁";
            button.setAttribute("aria-label", "Hiện mật khẩu");

        }

    });

});

</script>

</body>

</html>
