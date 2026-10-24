@php
    /*
    |--------------------------------------------------------------------------
    | DỮ LIỆU PHÒNG TỪ DATABASE (controller truyền $room)
    |--------------------------------------------------------------------------
    */
    if (isset($room) && $room instanceof \App\Models\Room) {
        $property = $room->property;
        $owner = optional($property)->owner;

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



<!-- =========================================================
     HEADER
============================================================== -->

<header class="room-detail-header">

    <div class="room-detail-header-inner">



        <a
            href="{{ route('tenant') }}"
            class="logo"
        >
            Trọ <span>Ơi</span>
        </a>



        <a
            href="{{ route('rooms.index') }}"
            class="back-room"
        >
            ← Quay lại danh sách phòng
        </a>

    </div>

</header>




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

                    </div>

                </div>

            </div>

        @endif

    </div>

</main>



@endsection
  