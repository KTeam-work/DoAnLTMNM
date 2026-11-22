
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đặt lại mật khẩu - Trọ Ơi</title>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth/reset-password.css') }}">
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

    <script src="{{ asset('js/auth/reset-password.js') }}"></script>
</body>
</html>
