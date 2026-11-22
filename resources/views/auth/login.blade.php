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

    <link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
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
        <div class="room-spotlight"></div>
    </div>

    <main class="content">
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

            <div class="intro-features">
                <div class="intro-feature-item">
                    <span class="intro-feature-check">✓</span>
                    <span>Phòng trọ xác thực 100%</span>
                </div>
                <div class="intro-feature-item">
                    <span class="intro-feature-check">✓</span>
                    <span>Giá thuê và tiện ích minh bạch</span>
                </div>
                <div class="intro-feature-item">
                    <span class="intro-feature-check">✓</span>
                    <span>Kết nối trực tiếp chủ trọ</span>
                </div>
            </div>
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
                    @if (session('locked_unlock_url'))
                        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px dashed rgba(220, 38, 38, 0.3);">
                            <a href="{{ session('locked_unlock_url') }}" style="color: #ef7d27; font-weight: 700; text-decoration: underline;">
                                Bấm vào đây để gửi yêu cầu xin mở khóa tài khoản
                            </a>
                        </div>
                    @endif
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
                            data-target="password"
                            aria-label="Hiện mật khẩu"
                            aria-pressed="false"
                            tabindex="-1"
                        >
                            <svg class="eye-open" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                <circle cx="12" cy="12" r="3"></circle>
                            </svg>
                            <svg class="eye-closed" style="display:none;" xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                <line x1="1" y1="1" x2="23" y2="23"></line>
                            </svg>
                        </button>
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

                  <a href="{{ route('password.request') }}" class="forgot">
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

<script src="{{ asset('js/auth/login.js') }}"></script>
</body>
</html>
