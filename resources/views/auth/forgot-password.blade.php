
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quên mật khẩu - Trọ Ơi</title>

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

        .success {
            color: #217a48;
            background: #eef9f3;
            border: 1px solid #c7e8d5;
        }

        .error {
            color: #a93636;
            background: #fff1f1;
            border: 1px solid #f0cccc;
        }

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

        button {
            width: 100%;
            height: 50px;
            margin-top: 20px;
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
