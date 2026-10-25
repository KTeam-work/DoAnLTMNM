<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Trọ Ơi Admin')</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    {{-- CSS dùng chung --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    {{-- CSS riêng của từng trang --}}
    @stack('styles')
</head>

<body>

    {{-- NAVBAR ADMIN --}}
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">

            <a class="logo" href="{{ route('admin.dashboard') }}">
                Trọ <span>Ơi</span>
                <small>ADMIN</small>
            </a>

            <button
                class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu"
                aria-controls="mainMenu"
                aria-expanded="false"
                aria-label="Mở menu"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainMenu">

                <ul class="navbar-nav mx-auto align-items-lg-center">

                    <li class="nav-item">
                        <a
                            class="app-nav-link text-decoration-none {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}"
                        >
                            Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="app-nav-link text-decoration-none {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                            href="{{ route('admin.users') }}"
                        >
                            Người dùng
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="app-nav-link text-decoration-none {{ request()->routeIs('admin.rental-posts') ? 'active' : '' }}"
                            href="{{ route('admin.rental-posts') }}"
                        >
                            Kiểm duyệt tin
                        </a>
                    </li>

                    <li class="nav-item">
                        <a
                            class="app-nav-link text-decoration-none {{ request()->routeIs('admin.statistics') ? 'active' : '' }}"
                            href="{{ route('admin.statistics') }}"
                        >
                            Thống kê
                        </a>
                    </li>

                </ul>

                <div class="navbar-actions">

                    <div class="user-chip">
                        <span class="user-avatar">
                            {{ strtoupper(substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                        </span>

                        <span class="user-meta">
                            <span class="user-name d-block">
                                {{ auth()->user()->name ?? 'Admin hệ thống' }}
                            </span>

                            <span class="user-role">
                                Quản trị viên
                            </span>
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="ms-2">
                        @csrf

                        <button type="submit" class="btn-outline-brand">
                            Đăng xuất
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </nav>

    {{-- NỘI DUNG TỪNG TRANG ADMIN --}}
    <main class="page-wrap">
        @yield('content')
    </main>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- JS riêng của từng trang --}}
    @stack('scripts')

</body>
</html>
