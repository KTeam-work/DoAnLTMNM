<!DOCTYPE html>

<html lang="vi">

<head>
    <meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Đăng ký - Trọ Ơi</title>

<link
    href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
    rel="stylesheet"
>

<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">

</head>

<body>

<div class="page">



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
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ef7d27" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>
        </div>

        <div class="owner-floor"></div>

    </div>

    <div class="room-spotlight"></div>

</div>


<!-- =====================================================
     CONTENT
===================================================== -->

<div class="content">


    <!-- BÓNG ĐÈN TƯƠNG TÁC GIỮA MÀN HÌNH -->
    <div class="hanging-lamp" id="hangingLamp">
        <div class="lamp-wire"></div>
        <div class="lamp-fixture"></div>
        <div class="lamp-bulb" id="lampBulb" title="Nhấn vào bóng đèn để bật / tắt">
            <div class="bulb-reflection"></div>
            <div class="filament"></div>
        </div>
        <div class="pull-chain" id="pullChain" title="Kéo dây để bật / tắt đèn">
            <div class="chain-line"></div>
            <div class="chain-handle"></div>
        </div>
        <div class="light-beam" id="lightBeam"></div>
    </div>


    <!-- LOGO -->

    <a href="{{ url('/') }}" class="logo">
        Trọ <span>Ơi</span>
    </a>


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

        <div class="intro-features">
            <div class="intro-feature-item">
                <span class="intro-feature-check">✓</span>
                <span>Tìm trọ nhanh chóng, an tâm</span>
            </div>
            <div class="intro-feature-item">
                <span class="intro-feature-check">✓</span>
                <span>Kết nối trực tiếp chủ trọ</span>
            </div>
            <div class="intro-feature-item">
                <span class="intro-feature-check">✓</span>
                <span>Đăng tin cho thuê dễ dàng</span>
            </div>
        </div>

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
                    type="text"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="example@gmail.com"
                    autocomplete="email"
                    autocapitalize="off"
                    spellcheck="false"
                    inputmode="email"
                    class="@error('email') input-error @enderror"
                    required
                    oninput="validateEmail(this)"
                    onblur="validateEmail(this)"
                    oninvalid="validateEmail(this)"
                >

                <div id="email-error" class="field-error" aria-live="polite"></div>

                @error('email')
                    <div class="field-error">{{ $message }}</div>
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
                placeholder="Nhập 10 chữ số"
                autocomplete="tel"
                inputmode="numeric"
                maxlength="10"
                class="@error('phone') input-error @enderror"
                required
                oninput="handlePhoneInput(this)"
                onblur="validatePhone(this)"
                oninvalid="validatePhone(this)"
            >

            <div id="phone-error" class="field-error" aria-live="polite"></div>

            @error('phone')
                <div class="field-error">{{ $message }}</div>
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
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
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
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="7.5" cy="15.5" r="5.5"/><path d="m21 2-9.6 9.6"/><path d="m15.5 7.5 3 3L22 7l-3-3"/></svg>
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
                minlength="8"
                required
                oninput="checkPasswordStrength(this)"
            >

            <button
                type="button"
                class="toggle-password"
                data-target="password"
                aria-label="Hiện mật khẩu"
                tabindex="-1"
            >
                <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
            </button>

        </div>

        <div id="password-error" class="field-error" aria-live="polite"></div>

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
                tabindex="-1"
            >
                <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
                <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                    <line x1="1" y1="1" x2="23" y2="23"></line>
                </svg>
            </button>

        </div>

        <div id="confirm-password-error" class="field-error" aria-live="polite"></div>

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

</div>

<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script src="{{ asset('js/auth/register.js') }}"></script>

</body>

</html>
