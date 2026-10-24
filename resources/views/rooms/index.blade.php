
@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     
@endpush

@section('content')



    <section class="hero">

        <div class="hero-inner">

            <div class="hero-kicker">
                🔎 TÌM KIẾM PHÒNG TRỌ
            </div>

            <h1>
                Tìm phòng phù hợp,
                <br>
                <span class="highlight">
                    ở đúng nơi bạn muốn.
                </span>
            </h1>

            <p class="hero-desc">
                Khám phá phòng trọ, căn hộ và nhà cho thuê phù hợp
                với nhu cầu của bạn.
            </p>


            <!-- SEARCH -->
            <div class="search-panel">

                <div class="row g-0 align-items-center">

                    <div class="col-lg-3">
                        <div class="search-field">

                            <span class="field-label">
                                Khu vực
                            </span>

                            <div class="field-value">
                                📍 TP. Hồ Chí Minh
                            </div>

                        </div>
                    </div>


                    <div class="col-lg-2">

                        <div class="search-field">

                            <span class="field-label">
                                Khoảng giá
                            </span>

                            <div class="field-value field-muted">
                                Tất cả mức giá
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-2">

                        <div class="search-field">

                            <span class="field-label">
                                Diện tích
                            </span>

                            <div class="field-value field-muted">
                                Tất cả
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-2">

                        <div class="search-field">

                            <span class="field-label">
                                Tiện ích
                            </span>

                            <div class="field-value field-muted">
                                Tất cả
                            </div>

                        </div>

                    </div>


                    <div class="col-lg-3 p-2">

                        <button class="search-btn">
                            🔎 Tìm kiếm phòng
                        </button>

                    </div>

                </div>

            </div>


            <!-- QUICK FILTER -->
            <div class="quick-filters">

                <span class="quick-filter">
                    ≤ 2 triệu
                </span>

                <span class="quick-filter">
                    2 - 3 triệu
                </span>

                <span class="quick-filter">
                    3 - 5 triệu
                </span>

                <span class="quick-filter">
                    Có máy lạnh
                </span>

                <span class="quick-filter">
                    Có gác
                </span>

                <span class="quick-filter">
                    Có ban công
                </span>

                <span class="quick-filter">
                    Nuôi thú cưng
                </span>

            </div>

        </div>

    </section>


    <!-- =========================================================
     ROOM LIST
========================================================= -->
    <div class="page-wrap">

        <div class="page-header">

            <div>

                <h2 class="page-title">
                    Danh sách phòng
                </h2>

                <div class="page-desc">
                    Tìm thấy {{ $rooms->total() }} phòng phù hợp với nhu cầu của bạn.
                </div>

            </div>

            <a
                href="{{ url('/tenant') }}"
                class="section-link">
                ← Về trang chủ
            </a>

        </div>


        <!-- =====================================================
         ROOM CARDS
    ====================================================== -->

        <div class="row g-4">

            @forelse ($rooms as $room)

            @php
            $favoriteId = $favoriteIdsByRoom[$room->id] ?? null;
            $isFavorite = $favoriteId !== null;
            @endphp

            <div class="col-md-6 col-lg-4">
                <div class="room-card">

                    {{-- Nút thêm hoặc bỏ yêu thích --}}
                    <form
                        action="{{ $isFavorite
                        ? route('favorites.destroy', $favoriteId)
                        : route('favorites.store') }}"
                        method="POST"
                        class="favorite-form">
                        @csrf

                        @if ($isFavorite)
                        @method('DELETE')
                        @else
                        <input
                            type="hidden"
                            name="room_id"
                            value="{{ $room->id }}">
                        @endif

                        <button
                            type="submit"
                            class="fav-btn"
                            title="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"
                            aria-label="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}"
                            @if ($isFavorite)
                            style="color: #e53935;"
                            @endif>
                            {{ $isFavorite ? '♥' : '♡' }}
                        </button>
                    </form>

                    {{-- Ảnh minh họa --}}
                    <img
                        class="room-img"
                        src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80"
                        alt="{{ $room->name ?? 'Phòng trọ' }}"
                        loading="lazy">

                    <div class="room-body">

                        <div class="room-badges">
                            <span class="badge-room">
                                Mã phòng: {{ $room->room_code ?? $room->id }}
                            </span>
                        </div>

                        <div class="room-title">
                            {{ $room->name ?? 'Phòng trọ' }}
                        </div>

                        <div class="room-address">
                            {{ $room->property?->address ?? 'Chưa cập nhật địa chỉ' }}

                            @if ($room->property?->ward)
                            , {{ $room->property->ward }}
                            @endif

                            @if ($room->property?->district)
                            , {{ $room->property->district }}
                            @endif
                        </div>

                        <div class="room-stats">
                            <span>
                                📐 {{ number_format((float) $room->area, 0, ',', '.') }} m²
                            </span>

                            @forelse ($room->amenities->take(2) as $amenity)
                            <span>{{ $amenity->name }}</span>
                            @empty
                            <span>Chưa cập nhật tiện ích</span>
                            @endforelse
                        </div>

                        <div class="room-bottom">
                            <div class="room-price">
                                {{ number_format((float) $room->price, 0, ',', '.') }} đ
                                <small>/ tháng</small>
                            </div>

                            <a
                                href="{{ route('rooms.show', $room->id) }}"
                                class="detail-btn">
                                Xem phòng
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            @empty

            <div class="col-12">
                <div class="alert alert-light text-center py-5">
                    <h5>Chưa tìm thấy phòng phù hợp</h5>
                    <p class="mb-0">
                        Hiện chưa có phòng nào đáp ứng điều kiện tìm kiếm.
                    </p>
                </div>
            </div>

            @endforelse

        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $rooms->links() }}
        </div>


    </div>

@endsection
  