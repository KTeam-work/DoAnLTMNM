  @php
        $userName = $user->name ?? 'Người dùng';
        $nameParts = preg_split('/\s+/', trim($userName));
        $lastName = $nameParts[count($nameParts) - 1] ?? $userName;

        $userInitials = count($nameParts) >= 2
            ? mb_substr($nameParts[0], 0, 1)
                . mb_substr($lastName, 0, 1)
            : mb_substr($userName, 0, 2);

        $userInitials = mb_strtoupper($userInitials);
    @endphp

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
            <a class="logo" href="{{ route('tenant.dashboard') }}">
                Trọ <span>Ơi</span>
            </a>

            <button class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu"
                aria-controls="mainMenu"
                aria-expanded="false"
                aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="app-nav-link active text-decoration-none"
                           href="{{ route('tenant.dashboard') }}">
                            Trang chủ
                        </a>
                    </li>

                   <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('rooms.index') }}">Tìm phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('favorites.index') }}">Yêu thích</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('appointments.index') }}">Lịch xem phòng</a>
                    </li>
                    <!-- HỢP ĐỒNG -->
                    <li class="nav-item">
                        <a class="app-nav-link  text-decoration-none" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a>
                    </li>
                    <!-- HÓA ĐƠN -->
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a>
                    </li>
                    <!-- SỬA CHỮA -->
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a>
                    </li>
                    <!-- ĐÁNH GIÁ -->
                    <li class="nav-item">
                        <a class="app-nav-link text-decoration-none" href="{{ route('tenant.reviews.index') }}">Đánh giá</a>
                    </li>
                </ul>

                <div class="navbar-actions">
                    <button
                        class="notif-btn"
                        type="button"
                        title="Thông báo">
                        🔔
                        <span class="notif-dot"></span>
                    </button>

                    <div class="dropdown user-dropdown">
                        <button class="user-chip dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span class="user-avatar">
                                {{ $userInitials }}
                            </span>

                            <span class="user-meta">
                                <span class="user-name d-block">
                                    {{ $userName }}
                                </span>
                                <span class="user-role">Người thuê</span>
                            </span>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">
                                    {{ $userName }}
                                </div>
                                <div class="dropdown-user-email">
                                    {{ $user->email ?? '' }}
                                </div>
                            </div>

                            <div class="dropdown-divider-custom"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="dropdown-item-custom dropdown-logout">
                                    <span class="logout-icon">↪</span>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>