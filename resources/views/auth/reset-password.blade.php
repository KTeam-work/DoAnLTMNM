
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu - Trọ Ơi</title>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: "Be Vietnam Pro", sans-serif;
            background: linear-gradient(135deg, #071526 0%, #0c2340 50%, #163b65 100%);
            color: #0c2340;
        }

        .card {
            width: 100%;
            max-width: 450px;
            padding: 40px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 60px rgba(0,0,0,.3);
            border-top: 5px solid #ef7d27;
        }

        .logo {
            display: block;
            margin-bottom: 30px;
            color: #0c2340;
            font-size: 27px;
            font-weight: 800;
            text-decoration: none;
        }

        .logo span { color: #ef7d27; }

        h1 { font-size: 27px; font-weight: 800; }

        .subtitle {
            margin: 12px 0 25px;
            color: #64736f;
            font-size: 13px;
            line-height: 1.7;
        }

        .message {
            margin-bottom: 18px;
            padding: 12px;
            border-radius: 9px;
            font-size: 12px;
            line-height: 1.6;
        }

        .error {
            color: #a93636;
            background: #fff1f1;
            border: 1px solid #f0cccc;
        }

        .form-group { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 12px;
            font-weight: 700;
        }

        input {
            width: 100%;
            height: 49px;
            padding: 0 14px;
            border: 1px solid #dce3df;
            border-radius: 10px;
            outline: none;
            background: #fafcfb;
            font-family: inherit;
            font-size: 13px;
        }

        input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,.15);
            background: #ffffff;
        }

        /* Ẩn con mắt tự động của trình duyệt Edge/IE khi gõ mật khẩu */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear,
        input::-ms-reveal,
        input::-ms-clear {
            display: none !important;
            width: 0 !important;
            height: 0 !important;
        }

        .password-wrapper {
            position: relative;
            width: 100%;
        }

        .password-wrapper input {
            padding-right: 44px;
        }

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
            padding: 0;
            border: none;
            background: transparent;
            color: #64736f;
            cursor: pointer;
            transition: color .2s ease;
        }

        .toggle-password:hover {
            color: #0c2340;
        }

        .toggle-password:focus {
            outline: none;
        }

        .toggle-password svg {
            width: 19px;
            height: 19px;
            pointer-events: none;
        }

        button {
            width: 100%;
            height: 50px;
            margin-top: 5px;
            border: none;
            border-radius: 10px;
            background: #0c2340;
            color: white;
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s ease;
        }

        button:hover {
            background: #163b65;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(12,35,64,.25);
        }

        .back {
            display: block;
            margin-top: 22px;
            color: #0c2340;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
        }

        @media (max-width: 480px) {
            .card { padding: 28px 22px; }
        }
    </style>
</head>

<body>
    <main class="card">
        <a href="{{ url('/') }}" class="logo">Trọ <span>Ơi</span></a>

        <h1>Đặt lại mật khẩu</h1>

        <p class="subtitle">
            Hãy tạo mật khẩu mới cho tài khoản của bạn.
            Mật khẩu cần có ít nhất 8 ký tự.
        </p>

        @if ($errors->any())
            <div class="message error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <div class="form-group">
                <label for="email">Địa chỉ email</label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $email) }}"
                    autocomplete="email"
                    required
                    readonly
                >
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu mới</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Nhập mật khẩu mới"
                        minlength="8"
                        autocomplete="new-password"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password"
                        data-target="password"
                        aria-label="Hiện mật khẩu"
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

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>

                <div class="password-wrapper">
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Nhập lại mật khẩu mới"
                        minlength="8"
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

            <button type="submit">Cập nhật mật khẩu</button>
        </form>

        <a href="{{ route('login') }}" class="back">
            ← Quay lại đăng nhập
        </a>
    </main>

    <script>
    document.querySelectorAll(".toggle-password").forEach(function (button) {
        button.addEventListener("click", function () {
            const targetId = button.dataset.target || button.getAttribute("data-target");
            const input = targetId ? document.getElementById(targetId) : null;
            if (!input) return;

            const isPassword = input.type === "password";
            input.type = isPassword ? "text" : "password";

            const eyeOpen = button.querySelector(".eye-open");
            const eyeClosed = button.querySelector(".eye-closed");
            if (eyeOpen && eyeClosed) {
                eyeOpen.style.display = isPassword ? "none" : "block";
                eyeClosed.style.display = isPassword ? "block" : "none";
            }
            button.setAttribute("aria-label", isPassword ? "Ẩn mật khẩu" : "Hiện mật khẩu");
            button.setAttribute("aria-pressed", String(isPassword));
        });
    });
    </script>
</body>
</html>
