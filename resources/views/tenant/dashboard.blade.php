@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/dasboard.css') }}">
@endpush

@section('content')
 
 <section class="hero">
        <div class="hero-inner">
            <div class="row align-items-center g-4 g-lg-5 mb-4">
                <!-- CỘT TRÁI: TIÊU ĐỀ & GIỚI THIỆU -->
                <div class="col-lg-6">
                    <div class="hero-kicker">
                        CHÀO {{ mb_strtoupper($lastName) }}, TÌM PHÒNG MỚI HÔM NAY?
                    </div>

                    <h1>
                        Tìm phòng phù hợp,<br>
                        <span class="highlight">ở đúng nơi bạn muốn.</span>
                    </h1>

                    <p class="hero-desc">
                        Khám phá phòng trọ đang được đăng cho thuê.
                        Lọc theo khu vực, giá và diện tích để tìm nơi ở phù hợp nhất với bạn.
                    </p>

                    <div class="hero-features d-flex flex-wrap gap-2 pt-1">
                        <span class="hero-feature-pill">Xác thực chính chủ</span>
                        <span class="hero-feature-pill">Xem phòng trực tiếp</span>
                        <span class="hero-feature-pill">Hợp đồng điện tử</span>
                    </div>
                </div>

                <!-- CỘT PHẢI: SLIDER ẢNH NHÀ / PHÒNG TRỌ ĐỔI QUA LẠI -->
                <div class="col-lg-6">
                    <div id="heroHouseCarousel" class="carousel slide carousel-fade hero-slider" data-bs-ride="carousel" data-bs-interval="3500">
                        <div class="carousel-indicators">
                            <button type="button" data-bs-target="#heroHouseCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                            <button type="button" data-bs-target="#heroHouseCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                            <button type="button" data-bs-target="#heroHouseCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                            <button type="button" data-bs-target="#heroHouseCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                        </div>

                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1200&q=80" class="hero-slider-img" alt="Căn hộ cao cấp">
                                <div class="hero-slider-overlay">
                                    <span class="slider-tag">Nổi bật</span>
                                    <h5 class="slider-title">Căn Hộ Duplex Cao Cấp</h5>
                                    <div class="slider-price">Từ 4.500.000 đ/tháng</div>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80" class="hero-slider-img" alt="Studio hiện đại">
                                <div class="hero-slider-overlay">
                                    <span class="slider-tag">Phòng mới</span>
                                    <h5 class="slider-title">Studio Full Nội Thất Hiện Đại</h5>
                                    <div class="slider-price">Từ 3.800.000 đ/tháng</div>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80" class="hero-slider-img" alt="Nhà trọ ban công">
                                <div class="hero-slider-overlay">
                                    <span class="slider-tag">Xem nhiều nhất</span>
                                    <h5 class="slider-title">Phòng Trọ Ban Công Thoáng Mát</h5>
                                    <div class="slider-price">Từ 2.600.000 đ/tháng</div>
                                </div>
                            </div>

                            <div class="carousel-item">
                                <img src="https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80" class="hero-slider-img" alt="Căn hộ mini">
                                <div class="hero-slider-overlay">
                                    <span class="slider-tag">An ninh 24/7</span>
                                    <h5 class="slider-title">Căn Hộ Mini Tiện Nghi Đầy Đủ</h5>
                                    <div class="slider-price">Từ 3.200.000 đ/tháng</div>
                                </div>
                            </div>
                        </div>

                        <button class="carousel-control-prev slider-control" type="button" data-bs-target="#heroHouseCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Trước</span>
                        </button>
                        <button class="carousel-control-next slider-control" type="button" data-bs-target="#heroHouseCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Sau</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- FORM TÌM KIẾM -->
            <form method="GET"
                action="{{ route('tenant.dashboard') }}"
                class="search-panel search-panel-pill"
                id="search-panel">

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

            <div class="quick-filters">
                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 2000000]) }}">
                    ≤ 2 triệu
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 3000000]) }}">
                    ≤ 3 triệu
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard', ['max_price' => 5000000]) }}">
                    ≤ 5 triệu
                </a>

                <a class="quick-filter"
                   href="{{ route('tenant.dashboard') }}">
                    Xóa bộ lọc
                </a>
            </div>
        </div>
    </section>

    <!-- PHÒNG GỢI Ý -->
    <main class="page-wrap" id="rooms">
        <div class="page-header">
            <div>
                <h2 class="page-title">Phòng gợi ý cho bạn</h2>
                <div class="page-desc">
                    Phòng đang trống và có bài đăng cho thuê hợp lệ.
                </div>
            </div>

            <a href="{{ route('rooms.index') }}" class="section-link">
                Xem tất cả →
            </a>
        </div>

        <div class="row g-3">
            @forelse ($rooms as $room)
                @php
                    $images = json_decode($room->images ?? '[]', true);

                    $roomImage = is_array($images)
                        ? ($images[0] ?? null)
                        : null;

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
                                <span>📐 {{ $room->area }} m²</span>
                                <span>👥 Tối đa {{ $room->max_people }} người</span>
                                <span>🏷️ {{ $room->room_code }}</span>
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

        <!-- LỊCH XEM PHÒNG -->
        <div class="row g-3 mt-4">
            <div class="col-lg-6" id="appointments">
                <div class="panel">
                    <div class="panel-head">
                        <h3 class="panel-title">Lịch xem phòng gần đây</h3>
                        <a href="{{ route('appointments.index') }}"
                            class="panel-link">
                            Xem tất cả →
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

            <!-- HÓA ĐƠN: CHƯA HIỂN THỊ DỮ LIỆU GIẢ -->
            <div class="col-lg-6" id="invoices">
                <div class="panel">
                    <div class="panel-head">
                        <h3 class="panel-title">Hóa đơn</h3>
                    </div>

                    <div class="p-4 text-center">
                        <div style="font-size:32px;">🧾</div>
                        <p class="page-desc mt-2 mb-0">
                            Phần hóa đơn sẽ được kết nối với dữ liệu
                            thực tế sau khi xác định cấu trúc bảng hóa đơn.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- HỢP ĐỒNG VÀ THÔNG BÁO -->
        <div class="row g-3 mt-1">
            <div class="col-lg-6" id="contracts">
                <div class="panel p-4">
                    <h3 class="panel-title">Hợp đồng thuê</h3>
                    <p class="page-desc mb-0">
                        Phần hợp đồng sẽ được kết nối với hợp đồng
                        của người thuê sau khi bổ sung truy vấn backend.
                    </p>
                </div>
            </div>

            <div class="col-lg-6" id="notifications">
                <div class="panel p-4">
                    <h3 class="panel-title">Thông báo</h3>
                    <p class="page-desc mb-0">
                        Phần thông báo sẽ được kết nối với dữ liệu
                        thông báo của tài khoản đang đăng nhập.
                    </p>
                </div>
            </div>
        </div>
    </main>

@endsection

@push('scripts')
    <script src="{{ asset('js/favorite-toggle.js') }}"></script>
@endpush
