

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid">

        {{-- LOGO --}}
        <a class="logo" href="{{ route('landlord.dashboard') }}">
            Trọ <span>Ơi</span>
        </a>

   
        <button
            class="navbar-toggler bg-light"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#ownerMainMenu"
            aria-controls="ownerMainMenu"
            aria-expanded="false"
            aria-label="Mở menu">

            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- MENU CHÍNH --}}
        <div class="collapse navbar-collapse" id="ownerMainMenu">

            <ul class="navbar-nav mx-auto align-items-lg-center">

                {{-- TỔNG QUAN --}}
                <li class="nav-item">
                    <a
                        class="app-nav-link text-decoration-none {{ request()->routeIs('landlord.dashboard') ? 'active' : '' }}"
                        href="{{ route('landlord.dashboard') }}">
                        Tổng quan
                    </a>
                </li>

                {{-- NHÀ VÀ PHÒNG --}}
                <li class="nav-item dropdown">
                    <a
                        class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.properties.*', 'owner.rooms.*') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Nhà &amp; Phòng
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.properties.*') ? 'active' : '' }}"
                                href="{{ route('owner.properties.index') }}">
                                🏠 Quản lý nhà
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.rooms.*') ? 'active' : '' }}"
                                href="{{ route('owner.rooms.index') }}">
                                🚪 Quản lý phòng
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- KHÁCH VÀ HỢP ĐỒNG --}}
                <li class="nav-item dropdown">
                    <a
                        class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.tenants.*', 'owner.contracts.*') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Khách &amp; Hợp đồng
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.tenants.*') ? 'active' : '' }}"
                                href="{{ route('owner.tenants.manage') }}">
                                👤 Người thuê
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.contracts.*') ? 'active' : '' }}"
                                href="{{ route('owner.contracts.index') }}">
                                📝 Hợp đồng
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- TÀI CHÍNH --}}
                <li class="nav-item dropdown">
                    <a
                        class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.services.*', 'owner.utilities.*', 'owner.invoices.*', 'owner.payments.*') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Tài chính
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.services.*') ? 'active' : '' }}"
                                href="{{ route('owner.services.index') }}">
                                ✨ Dịch vụ
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.utilities.*') ? 'active' : '' }}"
                                href="{{ route('owner.utilities.index') }}">
                                ⚡ Điện nước
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.invoices.*') ? 'active' : '' }}"
                                href="{{ route('owner.invoices.index') }}">
                                🧾 Hóa đơn
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.payments.*') ? 'active' : '' }}"
                                href="{{ route('owner.payments.index') }}">
                                💰 Giao dịch
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- VẬN HÀNH --}}
                <li class="nav-item dropdown">
                    <a
                        class="app-nav-link dropdown-toggle text-decoration-none {{ request()->routeIs('owner.rental-posts.*', 'owner.appointments.*', 'owner.reviews.*') || request()->is('owner/maintenance*') ? 'active' : '' }}"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Vận hành
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.rental-posts.*') ? 'active' : '' }}"
                                href="{{ route('owner.rental-posts.index') }}">
                                📢 Tin đăng
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.appointments.*') ? 'active' : '' }}"
                                href="{{ route('owner.appointments.index') }}">
                                📅 Lịch xem phòng
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->is('owner/maintenance*') ? 'active' : '' }}"
                                href="{{ url('/owner/maintenance') }}">
                                🛠️ Sửa chữa
                            </a>
                        </li>

                        <li>
                            <a
                                class="dropdown-item {{ request()->routeIs('owner.reviews.*') ? 'active' : '' }}"
                                href="{{ route('owner.reviews.index') }}">
                                ⭐ Đánh giá
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>

            {{-- THÔNG TIN TÀI KHOẢN --}}
            <div class="navbar-actions">

                {{-- THÔNG BÁO --}}
                <button
                    class="notif-btn"
                    type="button"
                    aria-label="Thông báo"
                    title="Thông báo">
                    🔔
                </button>

                {{-- DROPDOWN TÀI KHOẢN --}}
                <div class="dropdown user-dropdown">

                    <button
                        class="user-chip"
                        type="button"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="true"
                        aria-expanded="false">

                        <span class="user-avatar">
                            {{ strtoupper(mb_substr(auth()->user()->name ?? 'CT', 0, 2)) }}
                        </span>

                        <span class="user-meta">
                            <span class="user-name">
                                {{ auth()->user()->name ?? 'Chủ trọ' }}
                            </span>

                            <span class="user-role">Chủ trọ</span>
                        </span>

                        <span class="caret">▾</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end user-dropdown-menu">

                        <li class="dropdown-user-info">
                            <div class="dropdown-user-name">
                                {{ auth()->user()->name ?? 'Chủ trọ' }}
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
