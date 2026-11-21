<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}?v={{ file_exists(public_path('css/styles.css')) ? filemtime(public_path('css/styles.css')) : time() }}">
    <link rel="stylesheet" href="{{ asset('css/tv1/rental-posts.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv1/users.css') }}?v={{ file_exists(public_path('css/tv1/users.css')) ? filemtime(public_path('css/tv1/users.css')) : time() }}">

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
                            class="app-nav-link text-decoration-none {{ request()->routeIs('admin.reports*') ? 'active' : '' }}"
                            href="{{ route('admin.reports.index') }}"
                        >
                            Báo cáo vi phạm
                            @php
                                $pendingReportsCount = \Illuminate\Support\Facades\Schema::hasTable('post_reports') ? \App\Models\PostReport::where('status', 'pending')->count() : 0;
                            @endphp
                            @if($pendingReportsCount > 0)
                                <span class="badge bg-danger rounded-pill ms-1" style="font-size: 10px;">{{ $pendingReportsCount }}</span>
                            @endif
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

                    <li class="nav-item">
                        <a
                            class="app-nav-link text-decoration-none {{ request()->routeIs('chatbot.*') ? 'active' : '' }}"
                            href="{{ route('chatbot.index') }}"
                        >
                            Trợ lý hỗ trợ
                        </a>
                    </li>

                </ul>

                <div class="navbar-actions">

                    {{-- DROPDOWN TÀI KHOẢN --}}
                    <div class="dropdown user-dropdown">

                        <button
                            class="user-chip"
                            type="button"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="true"
                            aria-expanded="false">

                            <span class="user-avatar">
                                {{ strtoupper(mb_substr(auth()->user()->name ?? 'AD', 0, 2)) }}
                            </span>

                            <span class="user-meta text-start">
                                <span class="user-name">
                                    {{ auth()->user()->name ?? 'Admin hệ thống' }}
                                </span>

                                <span class="user-role">Quản trị viên</span>
                            </span>

                            <span class="caret">▾</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">

                            <li class="dropdown-user-info">
                                <div class="dropdown-user-name">
                                    {{ auth()->user()->name ?? 'Admin hệ thống' }}
                                </div>

                                <div class="dropdown-user-email">
                                    {{ auth()->user()->email ?? '' }}
                                </div>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form
                                    method="POST"
                                    action="{{ route('logout') }}"
                                    class="m-0">

                                    @csrf

                                    <button
                                        type="submit"
                                        class="dropdown-item dropdown-logout">
                                        ↪ Đăng xuất
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </div>

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

    {{-- Chuyển tab & phân trang mượt mà không reload trang --}}
    <script src="{{ asset('js/app-ajax-nav.js') }}"></script>

    {{-- JS riêng của từng trang --}}
    @stack('scripts')

</body>
</html>
