@php
    /*
    |--------------------------------------------------------------------------
    | DỮ LIỆU PHÒNG TỪ DATABASE (controller truyền $room)
    |--------------------------------------------------------------------------
    */
    if (isset($room) && $room instanceof \App\Models\Room) {
        $property = $room->property;
        $owner = optional($property)->owner;
        $activePost = $room->rentalPosts->first() ?? \App\Models\RentalPost::where('room_id', $room->id)->latest()->first();

        $room = [
            'title' => $room->name,
            'id' => $room->id,
            'address' => optional($property)->address ?? 'Chưa cập nhật địa chỉ',
            'price' => number_format((float) $room->price, 0, ',', '.') . 'đ',
            'area' => number_format((float) $room->area, 0, ',', '.') . ' m²',
            'people' => $room->max_people . ' người',
            'bathroom' => 'WC riêng',
            'type' => ucfirst($room->status ?? 'Phòng trọ'),
            'images' => is_array($room->images)
                ? $room->images
                : (json_decode($room->images ?? '[]', true) ?: []),
            'description' => $room->description ?? 'Không có mô tả chi tiết.',
            'amenities' => optional($room->amenities)->pluck('name')->toArray()
                ?? ['WC riêng', 'Wi-Fi', 'Chỗ để xe'],
            'landlord' => optional($owner)->name ?? 'Chủ trọ',
            'phone' => optional($owner)->phone ?? '',
            'map' => optional($property)->address ?? 'Chưa cập nhật vị trí',
        ];
    } else {
        $room = null;
    }
@endphp


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
         <link rel="stylesheet"
          href="{{ asset('css/tv2/rooms/show.css') }}">
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

            <div class="room-gallery">

                @foreach($room['images'] as $index => $image)

                    <div class="room-gallery-item">

                        <img
                            src="{{ $image }}"
                            alt="{{ $room['title'] }}"
                        >



                        @if($index === 0)

                            <div class="gallery-main-badge">
                                📷 Hình ảnh phòng
                            </div>

                        @endif

                    </div>

                @endforeach

            </div>



            <div class="row g-4">



                <!-- =================================================
                     LEFT
                ================================================== -->

                <div class="col-lg-8">



                    <!-- =========================
                         THÔNG TIN CHÍNH
                    ========================== -->

                    <section class="room-title-section">

                        <div class="room-title-top">



                            <div>

                                <h1>
                                    {{ $room['title'] }}
                                </h1>



                                <div class="room-address">

                                    📍

                                    <span>
                                        {{ $room['address'] }}
                                    </span>

                                </div>

                            </div>



                            <div class="room-price-box">

                                <div class="room-price">

                                    {{ $room['price'] }}

                                    <span>
                                        / tháng
                                    </span>

                                </div>



                                <div class="available-badge">

                                    <span class="available-dot"></span>

                                    Còn phòng

                                </div>

                            </div>

                        </div>



                        <div class="room-info-box">



                            <div class="room-info-item">

                                <div class="label">
                                    Diện tích
                                </div>

                                <div class="value">
                                    {{ $room['area'] }}
                                </div>

                            </div>



                            <div class="room-info-item">

                                <div class="label">
                                    Số người
                                </div>

                                <div class="value">
                                    {{ $room['people'] }}
                                </div>

                            </div>



                            <div class="room-info-item">

                                <div class="label">
                                    Phòng tắm
                                </div>

                                <div class="value">
                                    {{ $room['bathroom'] }}
                                </div>

                            </div>



                            <div class="room-info-item">

                                <div class="label">
                                    Loại phòng
                                </div>

                                <div class="value">
                                    {{ $room['type'] }}
                                </div>

                            </div>

                        </div>

                    </section>



                    <!-- =========================
                         MÔ TẢ
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            📝 Mô tả phòng
                        </h2>

                        <div class="section-line"></div>

                        <p>
                            {{ $room['description'] }}
                        </p>

                    </section>



                    <!-- =========================
                         TIỆN ÍCH
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            ✨ Tiện ích phòng
                        </h2>

                        <div class="section-line"></div>



                        <div class="amenities">

                            @foreach($room['amenities'] as $amenity)

                                <div class="amenity-item">
                                    {{ $amenity }}
                                </div>

                            @endforeach

                        </div>

                    </section>



                    <!-- =========================
                         GOOGLE MAP
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            📍 Vị trí phòng
                        </h2>

                        <div class="section-line"></div>



                        <div class="map-address">

                            📌

                            {{ $room['map'] }}

                        </div>



                        <div class="map-wrapper">

                            <iframe
                                class="room-map"
                                src="https://www.google.com/maps?q={{ urlencode($room['map']) }}&output=embed"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>

                        </div>

                    </section>



                    <!-- =========================
                         CHỦ TRỌ
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            👤 Thông tin chủ trọ
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

                                        ● Đang hoạt động

                                    </div>

                                </div>

                            </div>



                            <div class="landlord-contact">

                                ✓ Chủ trọ xác minh

                            </div>

                        </div>

                    </section>

                </div>



                <!-- =================================================
                     RIGHT
                ================================================== -->

                <div class="col-lg-4">



                    <div class="booking-card">



                        <div class="booking-top">



                            <h2>
                                📅 Đặt lịch xem phòng
                            </h2>



                            <div class="booking-note">

                                Chọn thời gian phù hợp để đến xem phòng.
                                Chủ trọ sẽ xác nhận lịch hẹn của bạn.

                            </div>



                            <div class="booking-room-price">

                                {{ $room['price'] }}

                                <span>
                                    / tháng
                                </span>

                            </div>

                        </div>



                        <!--
                        |-----------------------------------------------------------
                        | QUAN TRỌNG:
                        | Truyền room_id sang trang appointments/create.
                        | Ví dụ:
                        | /appointments/create?id=1
                        | /appointments/create?id=2
                        |-----------------------------------------------------------
                        -->

                        <a
                            href="{{ route('appointments.create', ['id' => $room['id'] ?? 0]) }}"
                            class="btn-booking text-decoration-none d-flex align-items-center justify-content-center"
                        >
                            📅 Đặt lịch xem phòng
                        </a>



                        <div class="booking-secure-note">

                            🔒 Thông tin của bạn được bảo mật

                        </div>



                        <!-- LIÊN HỆ NHANH -->

                        <div class="contact-buttons">

                            <a
                                href="tel:{{ $room['phone'] }}"
                                class="btn-contact"
                            >
                                📞 Gọi chủ trọ
                            </a>

                            <a
                                href="#"
                                class="btn-contact"
                            >
                                💬 Zalo
                            </a>

                        </div>

                        @if(isset($activePost) && $activePost)
                            <div class="mt-3 text-center border-top pt-2">
                                @auth
                                    <button type="button" class="btn btn-link text-muted text-decoration-none small p-0" data-bs-toggle="modal" data-bs-target="#reportModal" style="font-size: 12.5px;">
                                        🚩 Báo cáo tin đăng vi phạm
                                    </button>
                                @else
                                    <a href="{{ route('login') }}" class="text-muted text-decoration-none small" style="font-size: 12.5px;">
                                        🚩 Đăng nhập để báo cáo vi phạm
                                    </a>
                                @endauth
                            </div>
                        @endif

                    </div>

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
                    🚩 Báo cáo tin đăng vi phạm
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
  