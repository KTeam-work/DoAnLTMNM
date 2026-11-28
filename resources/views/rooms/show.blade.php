@php
    /*
    |--------------------------------------------------------------------------
    | DỮ LIỆU PHÒNG TỪ DATABASE (controller truyền $room)
    |--------------------------------------------------------------------------
    */
    if (isset($room) && $room instanceof \App\Models\Room) {
        $property = $room->property;
        $owner = optional($property)->owner;
        $panoramaUrl = $room->panorama_path ? asset('storage/' . ltrim($room->panorama_path, '/')) : null;
        $activePost = $room->rentalPosts->first() ?? \App\Models\RentalPost::where('room_id', $room->id)->latest()->first();

        $lat = optional($property)->latitude;
        $lng = optional($property)->longitude;
        $hasCoords = !empty($lat) && !empty($lng) && is_numeric($lat) && is_numeric($lng);

        $fullAddress = trim(implode(', ', array_filter([
            optional($property)->address,
            optional($property)->ward,
            optional($property)->district,
        ])));

        $mapQuery = $hasCoords ? "{$lat},{$lng}" : ($fullAddress ?: (optional($property)->address ?? 'Thành phố Hồ Chí Minh'));

        $rawImages = [];

        // 1. Lấy ảnh từ bài đăng cho thuê (Rental Post images)
        if ($activePost) {
            $postImages = $activePost->relationLoaded('images') ? $activePost->images : $activePost->images()->get();
            foreach ($postImages as $postImg) {
                if (!empty($postImg->image_path)) {
                    $rawImages[] = $postImg->image_path;
                }
            }
        }

        // 2. Lấy ảnh từ trường images của Room (nếu có)
        $roomImgs = is_array($room->images) ? $room->images : (json_decode($room->images ?? '[]', true) ?: []);
        if (!empty($roomImgs)) {
            foreach ($roomImgs as $rImg) {
                if (!empty($rImg)) {
                    $rawImages[] = $rImg;
                }
            }
        }

        // 3. Lấy ảnh từ khu trọ (Property images) nếu chưa có ảnh
        if (empty($rawImages) && $property) {
            $propImages = $property->relationLoaded('images') ? $property->images : $property->images()->get();
            foreach ($propImages as $pImg) {
                if (!empty($pImg->image_path)) {
                    $rawImages[] = $pImg->image_path;
                }
            }
        }

        // 4. Chuẩn hóa đường dẫn thành URL hợp lệ
        $finalImages = [];
        foreach ($rawImages as $img) {
            if (empty($img)) continue;
            if (\Illuminate\Support\Str::startsWith($img, ['http://', 'https://', '//'])) {
                $finalImages[] = $img;
            } elseif (\Illuminate\Support\Str::startsWith($img, ['storage/', '/storage/'])) {
                $finalImages[] = asset(ltrim($img, '/'));
            } else {
                $finalImages[] = asset('storage/' . ltrim($img, '/'));
            }
        }

        // 5. Nếu vẫn chưa có ảnh -> Dùng ảnh mẫu chất lượng cao
        if (empty($finalImages)) {
            $finalImages = [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80',
            ];
        }

        $room = [
            'title' => $room->name,
            'id' => $room->id,
            'address' => $fullAddress ?: (optional($property)->address ?? 'Chưa cập nhật địa chỉ'),
            'price' => number_format((float) $room->price, 0, ',', '.') . 'đ',
            'area' => number_format((float) $room->area, 0, ',', '.') . ' m²',
            'people' => $room->max_people . ' người',
            'bathroom' => 'WC riêng',
            'type' => match ($room->status) {
                'available' => 'Còn trống',
                'rented' => 'Đang thuê',
                'pending' => 'Đang chờ',
                'maintenance' => 'Bảo trì',
                default => 'Chưa cập nhật',
            },
            'images' => $finalImages,
            'description' => $room->description ?? 'Không có mô tả chi tiết.',
            'amenities' => $property
                ? $room->amenities->whereIn('id', $property->amenities()->wherePivot('status', 'active')->pluck('amenities.id'))->pluck('name')->toArray()
                : [],
            'services' => optional(optional($property)->owner)->services ?? collect(),
            'landlord' => optional($owner)->name ?? 'Chủ trọ',
            'phone' => optional($owner)->phone ?? '',
            'latitude' => $lat,
            'longitude' => $lng,
            'has_coords' => $hasCoords,
            'map_query' => $mapQuery,
            'map' => $fullAddress ?: (optional($property)->address ?? 'Chưa cập nhật vị trí'),
        ];
    } else {
        $room = null;
    }
@endphp


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
         <link rel="stylesheet"
          href="{{ asset('css/tv2/rooms/show.css') }}?v={{ filemtime(public_path('css/tv2/rooms/show.css')) }}">
         <link rel="stylesheet" href="{{ asset('css/tv2/rooms/compare.css') }}?v={{ filemtime(public_path('css/tv2/rooms/compare.css')) }}">
         @if($panoramaUrl ?? false)<link rel="stylesheet" href="{{ asset('vendor/pannellum/pannellum.css') }}">@endif
@endpush 

@section('content')

<main class="room-detail-page">

    <div class="room-detail-container">



        @if(!$room)



            <!-- =========================
                 KHÔNG TÌM THẤY
            ========================== -->

            <div class="not-found">

                <h1>
                    Không tìm thấy phòng
                </h1>

                <p>
                    Phòng bạn đang tìm không tồn tại
                    hoặc đã được gỡ khỏi hệ thống.
                </p>

                <a
                    href="{{ route('rooms.index') }}"
                    class="btn-home"
                >
                    ← Quay lại danh sách phòng
                </a>

            </div>



        @else

            <!-- =========================
                 QUAY LẠI DANH SÁCH
            ========================== -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(session('compare_message'))<div class="alert alert-success">{{ session('compare_message') }} <a href="{{ route('rooms.compare') }}" data-no-ajax="true">Xem so sánh</a></div>@endif
            @if(session('compare_error'))<div class="alert alert-warning">{{ session('compare_error') }} <a href="{{ route('rooms.compare') }}" data-no-ajax="true">Xem danh sách</a></div>@endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if(isset($errors) && $errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="mb-3">
                <a
                    href="{{ route('rooms.index') }}"
                    class="back-room"
                >
                    ← Quay lại danh sách phòng
                </a>
            </div>

            <!-- =========================
                 HÌNH ẢNH
            ========================== -->
            @php
                $imageCount = count($room['images']);
            @endphp

            <div class="detail-showcase">
              <div class="detail-showcase-gallery">
            @if($imageCount <= 1)
                <div class="room-gallery-single">
                    <img
                        src="{{ $room['images'][0] ?? 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80' }}"
                        alt="{{ $room['title'] }}"
                        loading="lazy"
                        onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';"
                    >
                    <div class="gallery-badge-clean">
                        Ảnh đại diện
                    </div>
                </div>
            @elseif($imageCount === 2)
                <div class="room-gallery-two">
                    @foreach($room['images'] as $idx => $img)
                        <div class="gallery-item-two">
                            <img
                                src="{{ $img }}"
                                alt="{{ $room['title'] }}"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';"
                            >
                            @if($idx === 0)
                                <div class="gallery-badge-clean">
                                    Ảnh đại diện
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="room-gallery-grid">
                    <div class="gallery-item-main">
                        <img
                            id="mainGalleryImg"
                            src="{{ $room['images'][0] }}"
                            alt="{{ $room['title'] }}"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';"
                        >
                        <div class="gallery-badge-clean">
                            Ảnh đại diện
                        </div>
                    </div>
                    <div class="gallery-thumbs-col">
                        @foreach(array_slice($room['images'], 1, 4) as $subIdx => $subImg)
                            <button type="button" class="gallery-thumb-item" data-gallery-src="{{ $subImg }}" onclick="document.getElementById('mainGalleryImg').src = this.dataset.gallerySrc" aria-label="Xem ảnh chi tiết {{ $subIdx + 1 }}">
                                <img
                                    src="{{ $subImg }}"
                                    alt="Ảnh chi tiết {{ $subIdx + 1 }}"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';"
                                >
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif
              </div>
              <aside class="detail-showcase-summary" aria-label="Thông tin phòng">
                <div class="showcase-summary-top">
                    <span class="showcase-eyebrow">KHÁM PHÁ NƠI Ở</span>
                    <h1>{{ $room['title'] }}</h1>
                    <p>{{ $room['address'] }}</p>
                </div>
                <div class="showcase-summary-price"><span>Giá thuê mỗi tháng</span><strong>{{ $room['price'] }} <small>/ tháng</small></strong></div>
                <div class="showcase-summary-facts"><div><span>Diện tích</span><strong>{{ $room['area'] }}</strong></div><div><span>Sức chứa</span><strong>{{ $room['people'] }}</strong></div></div>
                <form action="{{ route('rooms.compare.store', $room['id']) }}" method="POST" data-no-ajax="true" class="compare-detail-form">@csrf<button class="compare-add-btn" type="submit">{{ in_array($room['id'], session('compare_rooms', [])) ? 'Đã thêm vào so sánh' : '+ Thêm vào so sánh' }}</button><a href="{{ route('rooms.compare') }}" data-no-ajax="true">Xem danh sách ({{ count(session('compare_rooms', [])) }}/3)</a></form>
                <a href="{{ route('appointments.create', ['id' => $room['id']]) }}" class="showcase-appointment">Đặt lịch xem phòng</a>
                @if(!auth()->check() || auth()->user()->role === 'tenant')
                    @auth
                        <form action="{{ route('chat.start', $room['id']) }}" method="POST" class="showcase-chat-form" data-no-ajax="true">
                            @csrf
                            <button type="submit" class="showcase-chat">Nhắn tin cho chủ trọ</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="showcase-chat">Nhắn tin cho chủ trọ</a>
                    @endauth
                @endif
                <a href="#room-information" class="showcase-details-link">Xem thông tin chi tiết</a>
              </aside>
            </div>

            @if($panoramaUrl ?? false)
              <section class="panorama-section" aria-labelledby="panorama-title"><div><span class="panorama-kicker">KHÁM PHÁ KHÔNG GIAN</span><h2 id="panorama-title">Xem phòng 360°</h2><p>Kéo chuột hoặc vuốt để nhìn quanh. Dùng nút + / − để phóng to, thu nhỏ.</p></div><div id="room-panorama-viewer" data-panorama-url="{{ $panoramaUrl }}" data-library-url="{{ asset('vendor/pannellum/pannellum.js') }}" aria-label="Ảnh toàn cảnh phòng 360 độ"></div><p id="panorama-error" class="panorama-error" hidden>Không thể tải ảnh 360°. Vui lòng thử tải lại trang.</p></section>
              <script src="{{ asset('js/room-panorama.js') }}" defer></script>
            @endif



            <div class="row g-4">



                <!-- =================================================
                     LEFT
                ================================================== -->

                <div class="col-12">



                    <!-- =========================
                         THÔNG TIN CHÍNH
                    ========================== -->

                    <section class="room-title-section" id="room-information">

                        <div class="detail-facts-heading">
                            <h2>Thông tin phòng</h2>
                            @if(isset($totalReviews) && $totalReviews > 0)
                                <a href="#reviewsSection">★ {{ $avgRating }} · {{ $totalReviews }} đánh giá</a>
                            @endif
                        </div>

                        <div class="room-info-box">
                            <div class="room-info-item">
                                <div class="label">Diện tích</div>
                                <div class="value">{{ $room['area'] }}</div>
                            </div>

                            <div class="room-info-item">
                                <div class="label">Số người</div>
                                <div class="value">{{ $room['people'] }}</div>
                            </div>

                            <div class="room-info-item">
                                <div class="label">Phòng tắm</div>
                                <div class="value">{{ $room['bathroom'] }}</div>
                            </div>

                            <div class="room-info-item">
                                <div class="label">Trạng thái</div>
                                <div class="value">{{ $room['type'] }}</div>
                            </div>
                        </div>
                    </section>

                    <!-- =========================
                         MÔ TẢ
                    ========================== -->
                    <section class="room-content-card">
                        <h2>Mô tả phòng</h2>
                        <div class="section-line"></div>
                        <p>{{ $room['description'] }}</p>
                    </section>

                    <!-- =========================
                         TIỆN ÍCH
                    ========================== -->
                    <section class="room-content-card">
                        <h2>Tiện ích phòng</h2>
                        <div class="section-line"></div>

                        <div class="amenities">
                            @forelse($room['amenities'] as $amenity)
                                <div class="amenity-item">
                                    {{ $amenity }}
                                </div>
                            @empty
                                <p class="amenities-empty">Chưa có thông tin tiện ích.</p>
                            @endforelse
                        </div>
                    </section>

                    <!-- =========================
                         BẢNG GIÁ DỊCH VỤ / CHI PHÍ HÀNG THÁNG
                    ========================== -->
                    <section class="room-content-card">
                        <h2>Chi phí dịch vụ hàng tháng</h2>
                        <div class="section-line"></div>

                        @if(!empty($room['services']) && count($room['services']) > 0)
                            <div class="services-list" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 12px; margin-top: 15px;">
                                @foreach($room['services'] as $svc)
                                    <div class="service-item" style="padding: 14px 18px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; display: flex; justify-content: space-between; align-items: center; transition: all 0.2s ease;">
                                        <div>
                                            <div style="font-weight: 700; color: #0f172a; font-size: 15px;">
                                                {{ $svc->name }}
                                            </div>
                                            @if($svc->description)
                                                <div style="font-size: 12px; color: #64748b; margin-top: 2px;">{{ $svc->description }}</div>
                                            @endif
                                        </div>
                                        <div style="text-align: right;">
                                            <span style="font-weight: 700; color: #0c2340; font-size: 15px;">{{ number_format((float)$svc->price, 0, ',', '.') }} ₫</span>
                                            <span style="font-size: 12px; color: #64748b;">/ {{ $svc->unit }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-secondary mb-0" style="font-size: 14px; color: #64748b; margin-top: 10px;">
                                Các dịch vụ cơ bản đã bao gồm trong giá phòng hoặc sẽ được thỏa thuận trực tiếp với chủ trọ khi làm hợp đồng.
                            </p>
                        @endif
                    </section>

                    <!-- =========================
                         VỊ TRÍ PHÒNG TRỌ (GOOGLE MAPS)
                    ========================== -->
                    <section class="room-content-card">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <h2 style="margin: 0;">
                                Vị trí phòng trọ
                            </h2>

                            @if(!empty($room['has_coords']))
                                <span style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; background: rgba(12, 35, 64, 0.08); border: 1px solid rgba(12, 35, 64, 0.15); border-radius: 999px; font-size: 12px; font-weight: 700; color: #0c2340;">
                                    Tọa độ GPS: {{ number_format((float)$room['latitude'], 5) }}, {{ number_format((float)$room['longitude'], 5) }}
                                </span>
                            @endif
                        </div>

                        <div class="section-line"></div>

                        <div class="map-address" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px;">
                            <div>
                                <span>{{ $room['address'] }}</span>
                            </div>

                            <a
                                href="https://www.google.com/maps/search/?api=1&query={{ urlencode($room['map_query']) }}"
                                target="_blank"
                                rel="noopener noreferrer"
                                style="font-size: 13px; font-weight: 700; color: #0c2340; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;"
                            >
                                Mở chỉ đường trên Google Maps
                            </a>
                        </div>

                        <div class="map-wrapper">
                            <iframe
                                class="room-map"
                                src="https://maps.google.com/maps?q={{ urlencode($room['map_query']) }}&hl=vi&z=16&output=embed"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>
                        </div>
                    </section>

                    <!-- =========================================================
                         ĐÁNH GIÁ & NHẬN XÉT TỪ KHÁCH THUÊ TRƯỚC (HỢP ĐỒNG ĐÃ THANH LÝ)
                         ========================================================= -->
                    <section class="room-content-card" id="reviewsSection">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                            <h2 style="margin: 0;">
                                Đánh giá từ khách thuê trước
                            </h2>
                            @if(isset($totalReviews) && $totalReviews > 0)
                                <div style="display: inline-flex; align-items: center; gap: 6px; padding: 5px 14px; background: #fefce8; border: 1px solid #fef08a; border-radius: 999px;">
                                    <span style="color: #f59e0b; font-size: 16px;">★</span>
                                    <span style="font-weight: 800; color: #854d0e; font-size: 15px;">{{ $avgRating }}</span>
                                    <span style="font-size: 12.5px; color: #a16207;">/ 5.0 ({{ $totalReviews }} đánh giá)</span>
                                </div>
                            @endif
                        </div>
                        <div class="section-line"></div>

                        @if(isset($totalReviews) && $totalReviews > 0)
                            {{-- BẢNG TỔNG QUAN ĐIỂM ĐÁNH GIÁ --}}
                            <div class="p-3 mb-4 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                                <div class="row align-items-center g-3">
                                    <div class="col-md-4 text-center border-md-end">
                                        <div style="font-size: 40px; font-weight: 800; color: #0c2340; line-height: 1;">
                                            {{ $avgRating }}
                                        </div>
                                        <div class="mt-1" style="color: #f59e0b; font-size: 18px; letter-spacing: 2px;">
                                            @for($i = 1; $i <= 5; $i++)
                                                {{ $i <= round($avgRating) ? '★' : '☆' }}
                                            @endfor
                                        </div>
                                        <div class="small text-muted mt-1">Dựa trên {{ $totalReviews }} lượt đánh giá thực tế</div>
                                    </div>
                                    <div class="col-md-8">
                                        @foreach([5, 4, 3, 2, 1] as $star)
                                            @php
                                                $count = $ratingCounts[$star] ?? 0;
                                                $pct = $totalReviews > 0 ? round(($count / $totalReviews) * 100) : 0;
                                            @endphp
                                            <div class="d-flex align-items-center gap-2 mb-1" style="font-size: 12.5px;">
                                                <span style="width: 42px; font-weight: 600; color: #475569;">{{ $star }} sao</span>
                                                <div class="progress flex-grow-1" style="height: 7px; background: #e2e8f0; border-radius: 99px;">
                                                    <div class="progress-bar" role="progressbar" style="width: {{ $pct }}%; background: #f59e0b; border-radius: 99px;" aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                                <span class="text-muted" style="width: 32px; text-align: right;">{{ $count }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mt-2 pt-2 border-top text-center" style="font-size: 11.5px; color: #15803d; font-weight: 600;">
                                    ✓ 100% đánh giá minh bạch từ những người thuê đã ký hợp đồng và từng ở tại phòng trọ này.
                                </div>
                            </div>

                            {{-- DANH SÁCH CHI TIẾT CÁC ĐÁNH GIÁ --}}
                            <div class="reviews-list d-flex flex-column gap-3">
                                @foreach($reviews as $review)
                                    <div class="p-3 rounded-3 bg-white border shadow-sm">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 38px; height: 38px; background: #0c2340; font-size: 14px;">
                                                    {{ strtoupper(substr($review->tenant->name ?? 'K', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark" style="font-size: 14px;">
                                                        {{ $review->tenant->name ?? 'Khách thuê trọ' }}
                                                    </div>
                                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-0" style="font-size: 10.5px; border-radius: 4px;">
                                                        ✓ Đã từng thuê phòng này
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="text-end">
                                                <div style="color: #f59e0b; font-size: 14px; letter-spacing: 1px;">
                                                    @for($s = 1; $s <= 5; $s++)
                                                        {{ $s <= $review->rating ? '★' : '☆' }}
                                                    @endfor
                                                </div>
                                                <div class="text-muted mt-1" style="font-size: 11px;">
                                                    {{ $review->created_at?->format('d/m/Y') }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="mb-0 text-dark" style="font-size: 13.5px; line-height: 1.6; background: #f8fafc; padding: 10px 14px; border-radius: 8px;">
                                                "{{ $review->comment }}"
                                            </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 px-3 rounded-3 bg-light border">
                                <div style="font-size: 32px; margin-bottom: 6px;">💬</div>
                                <div class="fw-bold text-dark" style="font-size: 14px;">Chưa có đánh giá nào cho phòng trọ này</div>
                                <div class="text-muted mt-1" style="font-size: 12.5px; max-width: 460px; margin: 0 auto; line-height: 1.5;">
                                    Đánh giá sẽ được hiển thị công khai khi những người thuê trước hoàn tất thời gian thuê hoặc thanh lý hợp đồng và chia sẻ cảm nhận trải nghiệm thực tế.
                                </div>
                            </div>
                        @endif
                    </section>

                    <!-- =========================
                         CHỦ TRỌ
                    ========================== -->
                    <section class="room-content-card">
                        <h2>
                            Thông tin chủ trọ
                        </h2>
                        <div class="section-line"></div>

                        <div class="landlord-box">
                            <div class="landlord-info">
                                <div class="landlord-avatar">
                                    {{ strtoupper(substr($room['landlord'], 0, 1)) }}
                                </div>

                                <div>
                                    <div class="landlord-name">
                                        {{ $room['landlord'] }}
                                    </div>

                                    <div class="landlord-status">
                                        <span class="badge bg-success-subtle text-success fw-bold px-2 py-1 rounded-pill" style="font-size: 11.5px;">Đang hoạt động</span>
                                    </div>
                                </div>
                            </div>

                            <div class="landlord-contact">
                                <span class="badge bg-primary-subtle text-primary fw-bold px-3 py-2 rounded-pill" style="font-size: 12px;">Chủ trọ đã xác minh</span>
                            </div>
                        </div>
                        @if(isset($activePost) && $activePost)
                            <div class="mt-3 border-top pt-3">
                                @auth
                                    <button type="button" class="btn btn-link text-muted text-decoration-none small p-0" data-bs-toggle="modal" data-bs-target="#reportModal" style="font-size: 12.5px;">
                                        Báo cáo tin đăng vi phạm
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="text-muted text-decoration-none small" style="font-size: 12.5px;">
                                        Đăng nhập để báo cáo vi phạm
                                    </a>
                                @endauth
                            </div>
                        @endif
                    </section>
                </div>

            </div>

        @endif

    </div>

</main>

@if(isset($activePost) && $activePost && auth()->check())
<!-- MODAL BÁO CÁO VI PHẠM -->
<div class="modal fade" id="reportModal" tabindex="-1" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #fff8f8; border-bottom: 1px solid #fee2e2; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title text-danger fw-bold fs-6" id="reportModalLabel">
                    Báo cáo tin đăng vi phạm
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <form action="{{ route('tenant.reports.store', $activePost->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">
                        Ý kiến đóng góp của bạn giúp cộng đồng Trọ Ơi an toàn, minh bạch hơn. Vui lòng chọn lý do và mô tả sự việc.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Lý do vi phạm <span class="text-danger">*</span></label>
                        <select name="reason" class="form-select form-select-sm" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="fake_info">Tin giả, hình ảnh/địa chỉ sai sự thật</option>
                            <option value="price_mismatch">Giá thực tế không đúng với giá đăng</option>
                            <option value="fraud_scam">Dấu hiệu lừa đảo / yêu cầu chuyển cọc mờ ám</option>
                            <option value="contact_failed">Số điện thoại ảo, không thể liên hệ</option>
                            <option value="inappropriate">Nội dung, hình ảnh thô tục, phản cảm</option>
                            <option value="other">Lý do khác</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Mô tả chi tiết <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control form-control-sm" rows="3" placeholder="Mô tả cụ thể vấn đề bạn gặp phải (tối thiểu 10 ký tự)..." required minlength="10"></textarea>
                    </div>

                    <div class="mb-2">
                        <label class="form-label fw-semibold small">Ảnh chụp bằng chứng (nếu có)</label>
                        <input type="file" name="evidence_image" class="form-control form-control-sm" accept="image/*">
                        <div class="form-text small" style="font-size: 11px;">Chụp ảnh tin nhắn, biên nhận cọc hoặc thực tế phòng (tối đa 5MB).</div>
                    </div>
                </div>
                <div class="modal-footer p-3 bg-light" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-sm btn-danger px-3">Gửi báo cáo</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
