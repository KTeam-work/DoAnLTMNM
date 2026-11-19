
@extends('layout.tenant')

@section('title', 'Tìm phòng trọ | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv2/rooms/index.css') }}">
@endpush

@section('content')
<section class="hero">
    <div class="hero-inner">
        <div class="row align-items-center g-4 g-lg-5 mb-4">
            <!-- CỘT TRÁI: TIÊU ĐỀ & MÔ TẢ -->
            <div class="col-lg-6">
                <div class="hero-kicker">TÌM KIẾM PHÒNG TRỌ</div>

                <h1>
                    Tìm phòng phù hợp,<br>
                    <span class="highlight">ở đúng nơi bạn muốn.</span>
                </h1>

                <p class="hero-desc">
                    Khám phá phòng trọ phù hợp với ngân sách và nhu cầu của bạn.
                    Tra cứu nhanh chóng theo khu vực, tầm giá và các tiện ích sinh hoạt.
                </p>

                <div class="hero-features d-flex flex-wrap gap-2 pt-1">
                    <span class="hero-feature-pill">Phòng trọ thật 100%</span>
                    <span class="hero-feature-pill">Không qua trung gian</span>
                    <span class="hero-feature-pill">Hỗ trợ xem phòng miễn phí</span>
                </div>
            </div>

            <!-- CỘT PHẢI: SLIDER ẢNH NHÀ ĐỔI QUA LẠI -->
            <div class="col-lg-6">
                <div id="roomHouseCarousel" class="carousel slide carousel-fade hero-slider" data-bs-ride="carousel" data-bs-interval="3500">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#roomHouseCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#roomHouseCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#roomHouseCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                        <button type="button" data-bs-target="#roomHouseCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
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

                    <button class="carousel-control-prev slider-control" type="button" data-bs-target="#roomHouseCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Trước</span>
                    </button>
                    <button class="carousel-control-next slider-control" type="button" data-bs-target="#roomHouseCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Sau</span>
                    </button>
                </div>
            </div>
        </div>

        <form id="room-search-form"
              action="{{ route('rooms.index') }}"
              method="GET">

            <div class="search-panel">
                <!-- HÀNG 1: THÔNG TIN TÌM KIẾM CHÍNH -->
                <div class="search-row-main">
                    <div class="row g-0 align-items-center">

                        <!-- TỪ KHÓA -->
                        <div class="col-lg-3 col-md-6">
                            <div class="search-field">
                                <label for="keyword" class="field-label">
                                    Từ khóa
                                </label>
                                <input
                                    id="keyword"
                                    name="keyword"
                                    type="search"
                                    class="room-filter-input"
                                    value="{{ request('keyword') }}"
                                    placeholder="Tên phòng, địa chỉ..."
                                >
                            </div>
                        </div>

                        <!-- KHU VỰC -->
                        <div class="col-lg-3 col-md-6">
                            <div class="search-field">
                                <label for="district" class="field-label">
                                    Khu vực
                                </label>
                                <select id="district"
                                        name="district"
                                        class="room-filter-input">
                                    <option value="">Tất cả khu vực</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district }}"
                                            @selected(request('district') == $district)>
                                            {{ $district }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- KHOẢNG GIÁ -->
                        <div class="col-lg-4 col-md-6">
                            <div class="search-field">
                                <label class="field-label">
                                    Khoảng giá (VNĐ/tháng)
                                </label>
                                <div class="d-flex align-items-center gap-2">
                                    <input
                                        id="min_price"
                                        name="min_price"
                                        type="number"
                                        min="0"
                                        class="room-filter-input"
                                        value="{{ request('min_price') }}"
                                        placeholder="Từ: 2000000"
                                    >
                                    <span class="text-muted" style="font-size: 13px;">-</span>
                                    <input
                                        id="max_price"
                                        name="max_price"
                                        type="number"
                                        min="0"
                                        class="room-filter-input"
                                        value="{{ request('max_price') }}"
                                        placeholder="Đến: 5000000"
                                    >
                                </div>
                            </div>
                        </div>

                        <!-- NÚT TÌM KIẾM -->
                        <div class="col-lg-2 col-md-6 search-btn-col p-2 text-end">
                            <button type="submit" class="search-btn">
                                Tìm kiếm phòng
                            </button>
                        </div>
                    </div>
                </div>

                <!-- HÀNG 2: BỘ LỌC MỞ RỘNG (DIỆN TÍCH) -->
                <div class="search-row-secondary">
                    <div class="d-flex flex-wrap align-items-center gap-3">
                        <span class="field-label mb-0">Diện tích (m²):</span>
                        <div class="d-flex align-items-center gap-2">
                            <input
                                id="min_area"
                                name="min_area"
                                type="number"
                                min="0"
                                step="0.1"
                                class="secondary-input"
                                value="{{ request('min_area') }}"
                                placeholder="Tối thiểu (m²)"
                            >
                            <span class="text-muted">-</span>
                            <input
                                id="max_area"
                                name="max_area"
                                type="number"
                                min="0"
                                step="0.1"
                                class="secondary-input"
                                value="{{ request('max_area') }}"
                                placeholder="Tối đa (m²)"
                            >
                        </div>
                    </div>
                </div>

                <!-- HÀNG 3: TIỆN ÍCH -->
                @if ($amenities->isNotEmpty())
                    <div class="room-amenities-filter">
                        <div class="field-label">TIỆN ÍCH PHÒNG</div>

                        <div class="room-amenities-options">
                            @foreach ($amenities as $amenity)
                                <label class="room-amenity-option">
                                    <input
                                        type="checkbox"
                                        name="amenities[]"
                                        value="{{ $amenity->id }}"
                                        @checked(in_array(
                                            (string) $amenity->id,
                                            array_map('strval', (array) request('amenities', []))
                                        ))
                                    >
                                    <span>{{ $amenity->name }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </form>

        <div class="quick-filters">
            <button type="button" class="quick-filter"
                    data-min-price="0" data-max-price="2000000">
                Dưới 2 triệu
            </button>

            <button type="button" class="quick-filter"
                    data-min-price="2000000" data-max-price="3000000">
                2–3 triệu
            </button>

            <button type="button" class="quick-filter"
                    data-min-price="3000000" data-max-price="5000000">
                3–5 triệu
            </button>

            <button type="button" class="quick-filter" id="clear-room-filters">
                Xóa bộ lọc
            </button>
        </div>
    </div>
</section>

<div class="page-wrap">
    <div class="page-header">
        <div>
            <h2 class="page-title">Danh sách phòng</h2>
            <div class="page-desc" id="room-result-count"
                 aria-live="polite">
                Tìm thấy {{ $rooms->total() }} phòng phù hợp.
            </div>
        </div>

        <a href="{{ url('/tenant') }}" class="section-link">
            ← Về trang chủ
        </a>
    </div>

    <div id="room-results"
         aria-live="polite"
         aria-busy="false">
        @include('rooms.partials.results', [
            'rooms' => $rooms,
            'favoriteIdsByRoom' => $favoriteIdsByRoom
        ])
    </div>

    <div id="room-search-error" class="alert alert-danger d-none"
         role="alert">
        Không thể tải danh sách phòng. Em thử lại nhé.
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/favorite-toggle.js') }}" defer></script>
    <script src="{{ asset('js/tv2/rooms/index.js') }}" defer></script>
@endpush
