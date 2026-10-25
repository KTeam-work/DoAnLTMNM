
@extends('layout.tenant')

@section('title', 'Tìm phòng trọ | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv2/rooms/index.css') }}">
@endpush

@section('content')
<section class="hero">
    <div class="hero-inner">
        <div class="hero-kicker">🔎 TÌM KIẾM PHÒNG TRỌ</div>

        <h1>
            Tìm phòng phù hợp,<br>
            <span class="highlight">ở đúng nơi bạn muốn.</span>
        </h1>

        <p class="hero-desc">
            Khám phá phòng trọ phù hợp với ngân sách và nhu cầu của bạn.
        </p>

        <form id="room-search-form"
              action="{{ route('rooms.index') }}"
              method="GET">

            <div class="search-panel">
                <div class="row g-0 align-items-center">

                    <div class="col-lg-3">
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

                    <div class="col-lg-2">
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

                    <div class="col-lg-2">
                        <div class="search-field">
                            <label for="min_price" class="field-label">
                                Giá từ (đ/tháng)
                            </label>
                            <input
                                id="min_price"
                                name="min_price"
                                type="number"
                                min="0"
                                class="room-filter-input"
                                value="{{ request('min_price') }}"
                                placeholder="Ví dụ: 2000000"
                            >
                        </div>
                    </div>

                    <div class="col-lg-2">
                        <div class="search-field">
                            <label for="max_price" class="field-label">
                                Giá đến (đ/tháng)
                            </label>
                            <input
                                id="max_price"
                                name="max_price"
                                type="number"
                                min="0"
                                class="room-filter-input"
                                value="{{ request('max_price') }}"
                                placeholder="Ví dụ: 5000000"
                            >
                        </div>
                    </div>

                    <div class="col-lg-3 p-2">
                        <button type="submit" class="search-btn">
                            🔎 Tìm kiếm phòng
                        </button>
                    </div>

                    <div class="col-md-6">
                        <div class="search-field">
                            <label for="min_area" class="field-label">
                                Diện tích tối thiểu (m²)
                            </label>
                            <input
                                id="min_area"
                                name="min_area"
                                type="number"
                                min="0"
                                step="0.1"
                                class="room-filter-input"
                                value="{{ request('min_area') }}"
                                placeholder="Ví dụ: 20"
                            >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="search-field">
                            <label for="max_area" class="field-label">
                                Diện tích tối đa (m²)
                            </label>
                            <input
                                id="max_area"
                                name="max_area"
                                type="number"
                                min="0"
                                step="0.1"
                                class="room-filter-input"
                                value="{{ request('max_area') }}"
                                placeholder="Ví dụ: 40"
                            >
                        </div>
                    </div>
                </div>

                <div class="room-amenities-filter">
                    <div class="field-label">TIỆN ÍCH</div>

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
    <script src="{{ asset('js/tv2/rooms/index.js') }}" defer></script>
@endpush
