  @php
      $currentUser = auth()->user() ?? $user ?? $tenant ?? null;
      $userName = $currentUser?->name ?? 'Người dùng';
      $userEmail = $currentUser?->email ?? '';
      $userRole = match ($currentUser?->role ?? '') {
          'owner' => 'Chủ trọ',
          'admin' => 'Quản trị viên',
          default => 'Người thuê',
      };

      $nameParts = preg_split('/\s+/', trim($userName));
      $lastName = $nameParts[count($nameParts) - 1] ?? $userName;

      $userInitials =
          count($nameParts) >= 2
              ? mb_substr($nameParts[0], 0, 1) . mb_substr($lastName, 0, 1)
              : mb_substr($userName, 0, 2);

      $userInitials = mb_strtoupper($userInitials);
  @endphp

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg app-navbar">
      <div class="container-fluid">
          <a class="logo" href="{{ route('tenant.dashboard') }}">
              Trọ <span>Ơi</span>
          </a>

          <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu"
              aria-controls="mainMenu" aria-expanded="false" aria-label="Mở menu">
              <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse" id="mainMenu">
              <ul class="navbar-nav mx-auto align-items-lg-center">
                  <li class="nav-item">
                      <a class="app-nav-link text-decoration-none
                            {{ request()->routeIs('tenant.dashboard') ? 'active' : '' }}"
                          href="{{ route('tenant.dashboard') }}">
                          Trang chủ
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="app-nav-link text-decoration-none
                                {{ request()->routeIs('rooms.index', 'rooms.show') ? 'active' : '' }}"
                          href="{{ route('rooms.index') }}">
                          Tìm phòng
                      </a>
                  </li>

                  <li class="nav-item">
                      @php $compareCount = count(session('compare_rooms', [])); @endphp
                      <a class="app-nav-link text-decoration-none
                                {{ request()->routeIs('rooms.compare') ? 'active' : '' }}"
                          href="{{ route('rooms.compare') }}" data-no-ajax="true">
                          So sánh @if($compareCount > 0)<span class="badge bg-primary ms-1">{{ $compareCount }}/3</span>@endif
                      </a>
                  </li>
                  <li class="nav-item">
                      <a class="app-nav-link text-decoration-none
                        {{ request()->routeIs('favorites.*') ? 'active' : '' }}"
                          href="{{ route('favorites.index') }}">
                          Yêu thích
                      </a>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="app-nav-link dropdown-toggle text-decoration-none
                          {{ request()->routeIs('appointments.*', 'chat.*', 'chatbot.*') ? 'active' : '' }}"
                          href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Lịch &amp; hỗ trợ
                      </a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item {{ request()->routeIs('appointments.*') ? 'active' : '' }}" href="{{ route('appointments.index') }}">Lịch xem phòng</a></li>
                          @if($currentUser && $currentUser->role === 'tenant')
                              @php
                                  $unreadChatCount = \App\Models\ChatMessage::whereNull('read_at')->where('sender_id', '!=', $currentUser->id)->whereHas('conversation', fn ($q) => $q->where('tenant_id', $currentUser->id)->orWhere('owner_id', $currentUser->id))->count();
                              @endphp
                              <li><a class="dropdown-item {{ request()->routeIs('chat.*') ? 'active' : '' }}" href="{{ route('chat.index') }}" data-no-ajax="true">Tin nhắn @if($unreadChatCount)<span class="badge bg-danger">{{ $unreadChatCount }}</span>@endif</a></li>
                          @endif
                          <li><a class="dropdown-item {{ request()->routeIs('chatbot.*') ? 'active' : '' }}" href="{{ route('chatbot.index') }}">Trợ lý AI</a></li>
                      </ul>
                  </li>
                  <li class="nav-item dropdown">
                      <a class="app-nav-link dropdown-toggle text-decoration-none
                          {{ request()->routeIs('tenant.contracts.*', 'tenant.invoices.*', 'tenant.maintenance.*', 'tenant.reviews.*') ? 'active' : '' }}"
                          href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Dịch vụ
                      </a>
                      <ul class="dropdown-menu">
                          <li><a class="dropdown-item {{ request()->routeIs('tenant.contracts.*') ? 'active' : '' }}" href="{{ route('tenant.contracts.index') }}">Hợp đồng</a></li>
                          <li><a class="dropdown-item {{ request()->routeIs('tenant.invoices.*') ? 'active' : '' }}" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a></li>
                          <li><a class="dropdown-item {{ request()->routeIs('tenant.maintenance.*') ? 'active' : '' }}" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
                          <li><a class="dropdown-item {{ request()->routeIs('tenant.reviews.*') ? 'active' : '' }}" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
                      </ul>
                  </li>
              </ul>

            
            <div class="navbar-actions">
                @if($currentUser)
                    {{-- THÔNG BÁO --}}
                    @php
                        $unreadNotifCount = $currentUser ? \App\Models\Notification::where('user_id', $currentUser->id)->whereNull('read_at')->count() : 0;
                    @endphp
                    <a class="notif-btn text-decoration-none" href="{{ route('tenant.notifications.index') }}" title="Thông báo ({{ $unreadNotifCount }} chưa đọc)">
                        🔔
                        @if($unreadNotifCount > 0)
                            <span class="notif-dot"></span>
                        @endif
                    </a>

                    {{-- TÀI KHOẢN NGƯỜI DÙNG --}}
                    <div class="dropdown user-dropdown">
                        <button
                            class="user-chip dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            data-bs-auto-close="true"
                            aria-expanded="false"
                        >
                            <span class="user-avatar">
                                {{ $userInitials }}
                            </span>

                            <span class="user-meta">
                                <span class="user-name">
                                    {{ $userName }}
                                </span>
                                <span class="user-role">
                                    {{ $userRole }}
                                </span>
                            </span>
                        </button>

                        {{-- MENU CHỈ HIỆN KHI BẤM --}}
                        <div class="dropdown-menu dropdown-menu-end user-dropdown-menu">
                            <div class="dropdown-user-info">
                                <div class="dropdown-user-name">
                                    {{ $userName }}
                                </div>

                                <div class="dropdown-user-email">
                                    {{ $userEmail }}
                                </div>
                            </div>

                            <div class="dropdown-divider-custom"></div>

                            <a class="dropdown-item-custom text-decoration-none" href="{{ route('tenant.reports.index') }}">
                                Báo cáo của tôi
                            </a>

                            <div class="dropdown-divider-custom"></div>

                            <form method="POST" action="{{ route('logout') }}" class="m-0">
                                @csrf

                                <button
                                    type="submit"
                                    class="dropdown-item-custom dropdown-logout"
                                >
                                    <span class="logout-icon">↪</span>
                                    Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn-brand text-decoration-none" style="padding: 8px 18px; font-size: 13px; border-radius: 10px;">
                        Đăng nhập
                    </a>
                @endif
            </div>
          </div>
      </div>
  </nav>
