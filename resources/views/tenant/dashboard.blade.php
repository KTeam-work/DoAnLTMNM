@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/dasboard.css') }}?v={{ filemtime(public_path('css/tv3/tenant/dasboard.css')) }}">
@endpush

@section('content')
 
 <section class="hero tenant-editorial-hero" style="--tenant-hero-image: url('{{ asset('images/owner-showcase-apartment-1.png') }}')">
        <div class="hero-inner">
            <div class="tenant-hero-intro">
                    <div class="hero-kicker">
                        CHÀO {{ mb_strtoupper($lastName) }} · TRỌ ƠI
                    </div>

                    <h1>
                        Tìm phòng phù hợp,<br>
                        <span class="highlight">ở đúng nơi bạn muốn.</span>
                    </h1>

                    <p class="hero-desc">
                        Khám phá phòng trọ đang được đăng cho thuê.
                        Lọc theo khu vực, giá và diện tích để tìm nơi ở phù hợp nhất với bạn.
                    </p>

                    <div class="tenant-hero-actions">
                        <a href="#rooms">Khám phá phòng</a>
                        <a href="{{ route('appointments.index') }}">Lịch xem của tôi</a>
                    </div>
            </div>

            <!-- FORM TÌM KIẾM -->
            <form method="GET"
                action="{{ route('tenant.dashboard') }}"
                class="search-panel search-panel-pill"
                id="search-panel" data-ajax-filter>

                <div class="row g-0 align-items-center">
                    <div class="col-lg-4">
                        <div class="search-field">
                            <label class="field-label" for="location">
                                Bạn muốn tìm ở đâu?
                            </label>
                            <input class="form-control"
                                id="location"
                                name="location"
                                value="{{ request('location') }}"
                                placeholder="Tên đường, quận, khu vực">
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="search-field">
                            <label class="field-label" for="max_price">
                                Giá tối đa mỗi tháng (VNĐ)
                            </label>
                            <input class="form-control"
                                id="max_price"
                                name="max_price"
                                type="number"
                                min="0"
                                value="{{ request('max_price') }}"
                                placeholder="Ví dụ: 3000000">
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="search-field">
                            <label class="field-label" for="min_area">
                                Diện tích tối thiểu (m²)
                            </label>
                            <input class="form-control"
                                id="min_area"
                                name="min_area"
                                type="number"
                                min="0"
                                step="0.1"
                                value="{{ request('min_area') }}"
                                placeholder="Ví dụ: 20">
                        </div>
                    </div>

                    <div class="col-lg-3 p-2">
                        <button type="submit" class="search-btn">
                            Tìm kiếm phòng
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </section>

    <section class="tenant-discover" aria-labelledby="discover-title">
        <div class="tenant-discover-inner">
            <div class="section-eyebrow">BẮT ĐẦU TỪ ĐÂY</div>
            <h2 id="discover-title">Tìm phòng theo nhu cầu</h2>
            <p>Chọn mức giá phù hợp để khám phá những phòng đang cho thuê.</p>
            <div class="quick-filters">
                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 2000000]) }}">
                    <span class="filter-symbol">01</span><strong>Dưới 2 triệu</strong><small>Tiết kiệm</small>
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 3000000]) }}">
                    <span class="filter-symbol">02</span><strong>Dưới 3 triệu</strong><small>Vừa túi tiền</small>
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 5000000]) }}">
                    <span class="filter-symbol">03</span><strong>Dưới 5 triệu</strong><small>Thêm lựa chọn</small>
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard') }}">
                    <span class="filter-symbol">⌂</span><strong>Tất cả phòng</strong><small>Xem mọi mức giá</small>
                </a>
            </div>
        </div>
    </section>

    <!-- PHÒNG GỢI Ý -->
    <div class="page-wrap" id="rooms">
        <div class="page-header">
            <div>
                <div class="section-eyebrow">KHÁM PHÁ NƠI Ở</div>
                <h2 class="page-title">Phòng gợi ý cho bạn</h2>
                <div class="page-desc">
                    Tìm một không gian phù hợp với cuộc sống của bạn.
                </div>
            </div>

            <a href="{{ route('rooms.index') }}" class="section-link">
                Xem tất cả phòng
            </a>
        </div>

        <div class="row g-4">
            @forelse ($rooms as $room)
                @php
                    $images = json_decode($room->images ?? '[]', true);

                    $roomImage = is_array($images)
                        ? ($images[0] ?? null)
                        : null;

                    if ($roomImage && !\Illuminate\Support\Str::startsWith($roomImage, ['http://', 'https://', '//'])) {
                        $roomImage = asset('storage/' . ltrim($roomImage, '/'));
                    }

                    if (!$roomImage && !empty($room->post_image)) {
                        $roomImage = \Illuminate\Support\Str::startsWith($room->post_image, ['http://', 'https://', '//'])
                            ? $room->post_image
                            : asset('storage/' . ltrim($room->post_image, '/'));
                    }

                    $favoriteId = $favoriteIdsByRoom[(int) $room->id] ?? null;
                    $isFavorite = $favoriteId !== null;
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="room-card">
                        <form
                            method="POST"
                            action="{{ $isFavorite ? route('favorites.destroy', $favoriteId) : route('favorites.store') }}"
                            data-store-url="{{ route('favorites.store') }}"
                            data-destroy-url="{{ route('favorites.destroy', ['id' => '__FAVORITE_ID__']) }}"
                            data-room-id="{{ $room->id }}"
                            class="favorite-form">
                            @csrf
                            @if ($isFavorite)
                                @method('DELETE')
                            @else
                                <input type="hidden" name="room_id" value="{{ $room->id }}">
                            @endif
                            <button type="submit"
                                class="fav-btn d-flex align-items-center justify-content-center {{ $isFavorite ? 'is-favorite' : '' }}"
                                title="{{ $isFavorite ? 'Bỏ yêu thích' : 'Lưu phòng yêu thích' }}"
                                aria-label="{{ $isFavorite ? 'Bỏ yêu thích' : 'Lưu phòng yêu thích' }}"
                                aria-pressed="{{ $isFavorite ? 'true' : 'false' }}"
                                style="color:{{ $isFavorite ? '#b45454' : '#ccc' }};">
                                @if ($isFavorite) &#9829; @else &#9825; @endif
                            </button>
                        </form>

                        @if ($roomImage)
                            <img class="room-img"
                                src="{{ $roomImage }}"
                                alt="{{ $room->name }}"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';">
                        @else
                            <img class="room-img"
                                src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80"
                                alt="Hình phòng trọ">
                        @endif

                        <div class="room-body">
                            <div class="room-badges">
                                <span class="badge-room">
                                    Đang cho thuê
                                </span>
                            </div>

                            <div class="room-title">
                                {{ $room->name ?: 'Phòng ' . $room->room_code }}
                            </div>

                            <div class="room-address">
                                {{ $room->property_address ?: 'Chưa cập nhật địa chỉ' }}
                            </div>

                            <div class="room-stats">
                                <span>{{ $room->area }} m²</span>
                                <span>Tối đa {{ $room->max_people }} người</span>
                                <span>{{ $room->room_code }}</span>
                            </div>

                            <div class="room-bottom">
                                <div class="room-price">
                                    {{ number_format((float) $room->price, 0, ',', '.') }}
                                    <small>đ/tháng</small>
                                </div>

                                <a href="{{ route('rooms.show', $room->id) }}"
                                    class="detail-btn">
                                    Xem phòng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="panel p-4 text-center">
                        <div style="font-size:36px;">🏠</div>
                        <h3 class="panel-title mt-3">
                            Chưa tìm thấy phòng phù hợp
                        </h3>
                        <p class="page-desc mb-3">
                            Hiện chưa có phòng thỏa mãn điều kiện tìm kiếm.
                            Em có thể thử đổi khu vực, giá hoặc diện tích.
                        </p>
                        <a href="{{ route('tenant.dashboard') }}"
                            class="detail-btn">
                            Xem tất cả phòng gợi ý
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="tenant-activity-heading">
            <div><div class="section-eyebrow">KHÔNG GIAN CỦA BẠN</div><h2>Quản lý thuê phòng</h2><p>Theo dõi lịch xem và truy cập nhanh thông tin của bạn.</p></div>
        </div>
        <div class="row g-4 tenant-activity">
            <div class="col-lg-7" id="appointments">
                <div class="panel activity-panel">
                    <div class="panel-head">
                        <div><h3 class="panel-title">Lịch xem phòng gần đây</h3><p class="activity-subtitle">{{ $appointments->count() }} lịch gần đây</p></div>
                        <a href="{{ route('appointments.index') }}"
                            class="panel-link">
                            Xem tất cả
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Phòng</th>
                                    <th>Ngày giờ</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($appointments as $appointment)
                                    @php
                                        $statusLabels = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'rescheduled' => 'Đề xuất đổi lịch',
                                            'rejected' => 'Bị từ chối',
                                            'cancelled' => 'Đã hủy',
                                            'completed' => 'Đã hoàn thành',
                                        ];

                                        $statusClasses = [
                                            'pending' => 'pending',
                                            'confirmed' => 'occupied',
                                            'rescheduled' => 'pending',
                                            'rejected' => 'overdue',
                                            'cancelled' => 'overdue',
                                            'completed' => 'occupied',
                                        ];

                                        $status = $appointment->status;
                                    @endphp

                                    <tr>
                                        <td>
                                            <div class="cell-title">
                                                {{ $appointment->room_name ?: 'Phòng ' . $appointment->room_code }}
                                            </div>
                                            <div class="cell-sub">
                                                {{ $appointment->property_address ?: 'Chưa cập nhật địa chỉ' }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d/m/Y') }}
                                            <div class="cell-sub">
                                                {{ substr($appointment->appointment_time, 0, 5) }}
                                            </div>
                                        </td>

                                        <td>
                                            <span class="badge-status {{ $statusClasses[$status] ?? 'pending' }}">
                                                {{ $statusLabels[$status] ?? $status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            Em chưa có lịch xem phòng nào.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
              <div class="activity-shortcuts">
                <div class="shortcuts-heading"><h3>Truy cập nhanh</h3><p>Mọi thứ bạn cần cho phòng thuê</p></div>
                <div id="invoices">
                <a href="{{ route('tenant.invoices.index') }}" class="tenant-shortcut">
                    <span class="shortcut-icon" aria-hidden="true">₫</span>
                    <span class="shortcut-content"><strong>Hóa đơn</strong><span>Theo dõi các khoản thanh toán</span></span><span class="shortcut-chevron" aria-hidden="true">›</span>
                </a>
                </div>
            <div id="contracts">
                <a href="{{ route('tenant.contracts.index') }}" class="tenant-shortcut">
                    <span class="shortcut-icon" aria-hidden="true">⌂</span>
                    <span class="shortcut-content"><strong>Hợp đồng thuê</strong><span>Thông tin và điều khoản thuê phòng</span></span><span class="shortcut-chevron" aria-hidden="true">›</span>
                </a>
            </div>

            <div id="notifications">
                <a href="{{ route('tenant.notifications.index') }}" class="tenant-shortcut">
                    <span class="shortcut-icon" aria-hidden="true">✦</span>
                    <span class="shortcut-content"><strong>Thông báo</strong><span>Cập nhật mới về phòng thuê</span></span><span class="shortcut-chevron" aria-hidden="true">›</span>
                </a>
            </div>
              </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('js/favorite-toggle.js') }}"></script>
@endpush
