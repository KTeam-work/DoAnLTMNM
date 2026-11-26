@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv2/favorites/index.css') }}?v={{ filemtime(public_path('css/tv2/favorites/index.css')) }}">
@endpush

@section('content')


    <div class="page-wrap favorites-page">



        <div class="page-header">

            <div>

                <span class="favorites-eyebrow">BỘ SƯU TẬP CỦA BẠN</span>

                <h1 class="page-title">
                    Phòng yêu thích
                </h1>

                <div class="page-desc">
                    Lưu lại những phòng bạn quan tâm để xem lại và so sánh sau.
                </div>

            </div>


            <a
                href="{{ route('rooms.index') }}"
                class="btn-brand text-decoration-none">
                Tìm thêm phòng
            </a>

        </div>



        <!-- =====================================================
         TOP SUMMARY
    ====================================================== -->

        <div class="row g-3 mb-4 favorite-stats">


            <!-- SỐ PHÒNG -->
            <div class="col-md-4">

                <div class="stat-card stat-favorites">
                    <span class="stat-icon" aria-hidden="true">♡</span>

                    <div class="stat-top">

                        <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">
                            Phòng yêu thích
                        </span>

                    </div>


                    <div class="stat-value favorite-count-value">
                        {{ $favoriteCount }}
                    </div>


                    <div class="stat-label">
                        Tổng số phòng đã lưu
                    </div>

                </div>

            </div>


            <!-- KHU VỰC -->
            <div class="col-md-4">

                <div class="stat-card stat-areas">
                    <span class="stat-icon" aria-hidden="true">⌖</span>

                    <div class="stat-top">

                        <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">
                            Khu vực
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $areaCount }}
                    </div>


                    <div class="stat-label">
                        Khu vực đang quan tâm
                    </div>

                </div>

            </div>


            <!-- GIÁ -->
            <div class="col-md-4">

                <div class="stat-card stat-prices">
                    <span class="stat-icon" aria-hidden="true">₫</span>

                    <div class="stat-top">

                        <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">
                            Mức giá
                        </span>

                    </div>


                    <div class="stat-value">
                        @if ($minPrice !== null && $maxPrice !== null)
                        {{ number_format((float) $minPrice / 1000000, 1, ',', '.') }}–{{ number_format((float) $maxPrice / 1000000, 1, ',', '.') }} triệu
                        @else
                        Chưa có dữ liệu
                        @endif
                    </div>


                    <div class="stat-label">
                        Khoảng giá phòng đã lưu
                    </div>

                </div>

            </div>

        </div>



        <!-- =====================================================
         FILTER / SORT
    ====================================================== -->

        <div class="panel favorites-toolbar mb-4">

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h2 class="panel-title">
                        Danh sách đã lưu
                    </h2>

                    <div class="page-desc">
                        Các phòng bạn đã đánh dấu yêu thích.
                    </div>

                </div>


                <div class="d-flex gap-2 flex-wrap">

                    <button
                        type="button"
                        class="btn-outline-brand active" data-favorite-sort="newest" aria-pressed="true">
                        Tất cả
                    </button>


                    <button
                        type="button"
                        class="btn-outline-brand" data-favorite-sort="price-asc" aria-pressed="false">
                        Giá thấp
                    </button>


                    <button
                        type="button"
                        class="btn-outline-brand" data-favorite-sort="newest" aria-pressed="false">
                        Mới lưu
                    </button>


                    <select
                        class="form-select" id="favorites-sort" aria-label="Sắp xếp phòng yêu thích"
                        style="
                        width:auto;
                        min-width:175px;
                        border-radius:11px;
                        border-color:var(--border);
                        font-size:12px;
                        font-weight:600;
                    ">

                        <option value="newest" selected>
                            Sắp xếp: Mới nhất
                        </option>

                        <option value="price-asc">
                            Giá thấp → cao
                        </option>

                        <option value="price-desc">
                            Giá cao → thấp
                        </option>

                        <option value="area-desc">
                            Diện tích lớn → nhỏ
                        </option>

                    </select>

                </div>

            </div>

        </div>



        <!-- =====================================================
         FAVORITE ROOM LIST
         
         Dữ liệu mẫu
    ====================================================== -->


        <div class="row g-4" id="favorites-grid" data-rooms-url="{{ route('rooms.index') }}">
            @forelse ($favorites as $favorite)
            @php
            $room = $favorite->room;
            $roomImage = null;
            if ($room) {
                foreach ($room->rentalPosts as $post) {
                    $image = $post->images->firstWhere('is_thumbnail', true) ?? $post->images->first();
                    if ($image?->image_path) { $roomImage = $image->image_path; break; }
                }
                $roomImages = is_array($room->images) ? $room->images : (json_decode($room->images ?? '[]', true) ?: []);
                $roomImage = $roomImage ?: ($roomImages[0] ?? null);
                $propertyImages = $room->property?->images;
                $propertyImage = $propertyImages?->firstWhere('is_thumbnail', true) ?? $propertyImages?->first();
                $roomImage = $roomImage ?: $propertyImage?->image_path;
                if ($roomImage && !\Illuminate\Support\Str::startsWith($roomImage, ['http://', 'https://', '//'])) {
                    $roomImage = \Illuminate\Support\Str::startsWith($roomImage, ['storage/', '/storage/'])
                        ? asset(ltrim($roomImage, '/')) : asset('storage/' . ltrim($roomImage, '/'));
                }
            }
            @endphp

            @if ($room)
            <div class="col-md-6 col-lg-4 favorite-card-column" data-price="{{ (float) $room->price }}" data-area="{{ (float) $room->area }}" data-order="{{ $loop->index }}">
                <div class="room-card">
                    <form action="{{ route('favorites.destroy', $favorite->id) }}"
                        method="POST"
                        class="favorite-form"
                        data-store-url="{{ route('favorites.store') }}"
                        data-destroy-url="{{ route('favorites.destroy', ['id' => '__FAVORITE_ID__']) }}"
                        data-room-id="{{ $room->id }}"
                        data-remove-on-unsave="true">
                        @csrf
                        @method('DELETE')
                        <button class="fav-btn" type="submit"
                            title="Bỏ yêu thích"
                            style="color: #e53935;">♥</button>
                    </form>

                    @if($roomImage)
                        <img class="room-img" src="{{ $roomImage }}" alt="{{ $room->name ?? 'Phòng trọ' }}" loading="lazy"
                            onerror="this.style.display='none';this.nextElementSibling.hidden=false;">
                    @endif
                    <div class="favorite-image-placeholder" {{ $roomImage ? 'hidden' : '' }}>Chưa có ảnh phòng</div>

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
                            {{ $room->property?->district ?? '' }}
                        </div>

                        <div class="room-stats">
                            <span>{{ $room->area }} m²</span>
                            @forelse ($room->amenities->take(2) as $amenity)
                            <span>{{ $amenity->name }}</span>
                            @empty
                            <span>Chưa có tiện ích</span>
                            @endforelse
                        </div>

                        <div class="room-bottom">
                            <div class="room-price">
                                {{ number_format((float) $room->price, 0, ',', '.') }} đ
                                <small>/ tháng</small>
                            </div>
                            <a href="{{ route('rooms.show', $room->id) }}"
                                class="detail-btn text-decoration-none">
                                Xem phòng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="col-12">
                <div class="panel favorite-empty text-center">
                    <div class="favorite-empty-icon" aria-hidden="true">♡</div>
                    <span class="favorite-empty-kicker">BẮT ĐẦU BỘ SƯU TẬP</span>
                    <h2>Chưa có phòng yêu thích</h2>
                    <p>Một nơi ở phù hợp đang chờ bạn khám phá.<br>Nhấn trái tim để lưu phòng và xem lại bất cứ lúc nào.</p>
                    <a href="{{ route('rooms.index') }}"
                        class="btn-brand text-decoration-none">
                        Tìm phòng trọ
                    </a>
                </div>
            </div>
            @endforelse
        </div>




        <!-- =====================================================
         BOTTOM INFO
    ====================================================== -->

        <div class="panel favorites-tip mt-4">

            <div class="d-flex align-items-center gap-3">

                <span class="favorites-tip-icon" aria-hidden="true">♡</span>
                <div>

                    <div class="cell-title">
                        Lưu phòng để xem lại bất cứ lúc nào
                    </div>

                    <div class="cell-sub">
                        Khi tìm thấy phòng phù hợp, hãy bấm nút yêu thích trên phòng để thêm vào danh sách theo dõi cá nhân.
                    </div>

                </div>

            </div>

        </div>

    </div>



@endsection

@push('scripts')
    <script src="{{ asset('js/favorite-toggle.js') }}"></script>
    <script src="{{ asset('js/favorites-sort.js') }}"></script>
@endpush
