
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu - Trọ Ơi</title>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/auth/forgot-password.css') }}">
</head>

<body>
    <main class="card">
        <a href="{{ url('/') }}" class="logo">Trọ <span>Ơi</span></a>

        <h1>Quên mật khẩu?</h1>

        <p class="subtitle">
            Đừng lo lắng! Hãy nhập email đã đăng ký.
            Chúng tôi sẽ gửi cho bạn liên kết để đặt lại mật khẩu.
        </p>

        @if (session('status'))
            <div class="message success" role="status">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="message error" role="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label for="email">Địa chỉ email</label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Nhập email đã đăng ký"
                value="{{ old('email') }}"
                autocomplete="email"
                required
                autofocus
            >

            <button type="submit">Gửi liên kết đặt lại mật khẩu</button>
        </form>

        <a href="{{ route('login') }}" class="back">
            ← Quay lại đăng nhập
        </a>
    </main>
</body>
</html>
