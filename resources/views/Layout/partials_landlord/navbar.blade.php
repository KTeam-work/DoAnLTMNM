
    {{-- =========================
         NAVBAR - CHỦ TRỌ
    ========================== --}}
    <nav class="navbar navbar-expand-lg app-navbar">
        <div class="container-fluid">
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

            <div class="collapse navbar-collapse" id="ownerMainMenu">
                  <a class="logo" href="{{ route('landlord.dashboard') }}">Trọ <span>Ơi</span></a>

                <ul class="navbar-nav mx-auto align-items-lg-center">

                    {{-- Tổng quan --}}
                    <li class="nav-item">
                        <a class="app-nav-link active text-decoration-none"
                           href="{{ route('landlord.dashboard') }}">
                            Tổng quan
                        </a>
                    </li>

                    {{-- Nhà và phòng --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Nhà &amp; Phòng
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.properties.index') }}">
                                    🏠 Quản lý nhà
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.rooms.index') }}">
                                    🚪 Quản lý phòng
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Khách thuê --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Khách &amp; Hợp đồng
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.tenants.manage') }}">
                                    👤 Người thuê
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.contracts.index') }}">
                                    📝 Hợp đồng
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Tài chính --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Tài chính
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.services.index') }}">
                                    ✨ Dịch vụ
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.utilities.index') }}">
                                    ⚡ Điện nước
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.invoices.index') }}">
                                    🧾 Hóa đơn
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.payments.index') }}">
                                    💰 Giao dịch
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- Vận hành --}}
                    <li class="nav-item dropdown">
                        <a class="app-nav-link dropdown-toggle text-decoration-none"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            Vận hành
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a>
                             
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.appointments.index') }}">
                                    📅 Lịch xem phòng
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ url('/owner/maintenance') }}">
                                    🛠️ Sửa chữa
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item"
                                   href="{{ route('owner.reviews.index') }}">
                                    ⭐ Đánh giá
                                </a>
                            </li>
                        </ul>
                    </li>

                </ul>

                {{-- Thông tin tài khoản --}}
                <div class="navbar-actions">

                    <button
                        class="notif-btn"
                        type="button"
                        aria-label="Thông báo"
                        title="Thông báo">
                        🔔
                    </button>

                    <div class="dropdown">
                        <button
                            class="user-chip border-0 bg-transparent p-0"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">

                            <span class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name ?? 'CT', 0, 2)) }}
                            </span>

                            <span class="user-meta text-start">
                                <span class="user-name d-block">
                                    {{ auth()->user()->name ?? 'Chủ trọ' }}
                                </span>
                                <span class="user-role">Chủ trọ</span>
                            </span>

                            <span class="caret">▾</span>
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <span class="dropdown-item-text">
                                    {{ auth()->user()->email ?? '' }}
                                </span>
                            </li>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <form method="POST"
                                      action="{{ route('logout') }}"
                                      class="m-0">
                                    @csrf

                                    <button type="submit" class="dropdown-item">
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
