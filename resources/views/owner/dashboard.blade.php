@extends('Layout.landlord')

@section('title', 'Tổng quan hoạt động | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/dashboard.css') }}?v={{ file_exists(public_path('css/tv3/dashboard.css')) ? filemtime(public_path('css/tv3/dashboard.css')) : time() }}">
@endpush

@section('content')

@php
    $defaultHouseImages = [asset('images/owner-showcase-apartment-1.png')];
    $heroBgImage = asset('images/owner-showcase-apartment-1.png');
@endphp

<div class="owner-page">
    <div class="owner-main">

        {{-- =========================================================
             1. HERO CARD (DARK LUXURY ARCHITECTURAL HERO - NO ICONS)
             ========================================================= --}}
        <div class="dashboard-hero-card owner-showcase">
            <div id="ownerPropertyCarousel" class="carousel slide owner-showcase-slides" data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover" aria-label="Ảnh khu trọ">
                <div class="carousel-inner">
                    <div class="carousel-item active"><img src="{{ asset('images/owner-showcase-apartment-1.png') }}" alt="Ảnh minh họa chung cư hiện đại"><div class="showcase-property"><span>Ảnh minh họa</span><strong>Không gian sống hiện đại</strong></div></div>
                    <div class="carousel-item"><img src="{{ asset('images/owner-showcase-apartment-2.png') }}" alt="Ảnh minh họa nhà trọ hiện đại"><div class="showcase-property"><span>Ảnh minh họa</span><strong>Không gian sống tiện nghi</strong></div></div>
                    @foreach ($properties as $property)
                        @php
                            $heroImage = $property->thumbnail ?? $property->images->first();
                            $heroImageUrl = $heroImage ? asset('storage/' . $heroImage->image_path) : $heroBgImage;
                        @endphp
                        <div class="carousel-item">
                            <img src="{{ $heroImageUrl }}" alt="{{ $property->name }}">
                            <div class="showcase-property"><span>Khu trọ của bạn</span><strong>{{ $property->name }}</strong><small>{{ $property->district ?? $property->city ?? 'Chưa cập nhật địa chỉ' }}</small></div>
                        </div>
                    @endforeach
                </div>
                    <div class="showcase-controls" aria-label="Điều khiển ảnh khu trọ">
                        <button type="button" data-bs-target="#ownerPropertyCarousel" data-bs-slide="prev" aria-label="Khu trọ trước"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
                        <button type="button" data-bs-target="#ownerPropertyCarousel" data-bs-slide="next" aria-label="Khu trọ tiếp theo"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
                    </div>
            </div>
            <div class="hero-inner">
                <div class="row align-items-center g-4">
                    
                    {{-- CỘT TRÁI: TIÊU ĐỀ, MÔ TẢ & FLOATING ACTION PILL BAR --}}
                    <div class="col-lg-7 showcase-copy">
                        <div class="hero-tag-pill">
                            Không gian của chủ trọ
                        </div>

                        <h1 class="hero-title">
                            Quản lý nhà trọ<br>
                            <span class="title-accent">vận hành trong tầm tay.</span>
                        </h1>

                        <p class="hero-desc">
                            Theo dõi tình hình cho thuê và xử lý công việc hằng ngày ngay tại đây.
                        </p>

                        {{-- FLOATING WHITE PILL ACTION BAR (NO ICONS) --}}
                        <div class="hero-pill-bar">
                            <div class="pill-item">
                                <span class="pill-label">Nhà trọ</span>
                                <span class="pill-value">{{ $propertiesCount }} khu</span>
                            </div>
                            <div class="pill-item">
                                <span class="pill-label">Quy mô</span>
                                <span class="pill-value">{{ $totalRooms }} phòng</span>
                            </div>
                            <div class="pill-item">
                                <span class="pill-label">Còn trống</span>
                                <span class="pill-value text-primary">{{ $availableRooms }} phòng</span>
                            </div>
                            <a href="{{ route('owner.rooms.create') }}" class="btn-pill-action">
                                + Thêm phòng
                            </a>
                        </div>

                        {{-- CÁC PHÍM TẮT THAO TÁC NHANH (NO ICONS) --}}
                        <div class="hero-sublinks">
                            <a href="{{ route('owner.rental-posts.create') }}" class="hero-sublink">
                                Đăng tin cho thuê
                            </a>
                            <a href="{{ route('owner.utilities.create') }}" class="hero-sublink">
                                Ghi điện nước
                            </a>
                            <a href="{{ route('owner.invoices.index') }}" class="hero-sublink">
                                Lập hóa đơn
                            </a>
                        </div>
                    </div>

                    {{-- CỘT PHẢI: THẺ BẤT ĐỘNG SẢN TIÊU BIỂU (THAY THẾ TỶ LỆ LẤP ĐẦY & DOANH THU) --}}
                </div>
            </div>
            <div class="showcase-summary">
                <div><span>Tỷ lệ lấp đầy</span><strong>{{ $occupancyRate }}%</strong></div>
                <div><span>Phòng đang thuê</span><strong>{{ $rentedRooms }} / {{ $totalRooms }}</strong></div>
                <div><span>Thực thu tháng {{ now()->format('m/Y') }}</span><strong>{{ number_format((float) $monthlyRevenue, 0, ',', '.') }} ₫</strong></div>
            </div>
        </div>

        {{-- =========================================================
             2. BOTTOM CRISP WHITE SECTION (KHU VỰC QUẢN LÝ DỮ LIỆU)
             ========================================================= --}}
        <div class="dashboard-white-section">
            
            {{-- DANH SÁCH CÁC NGÔI NHÀ / KHU TRỌ THỰC TẾ (HÌNH ẢNH NGÔI NHÀ) --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h3 class="box-title fs-5 fw-bold text-dark">Khu nhà trọ đang quản lý</h3>
                    <p class="box-sub">Danh sách các bất động sản và tình trạng khai thác</p>
                </div>
                <a href="{{ route('owner.rooms.index') }}" class="box-link">
                    Xem tất cả nhà trọ →
                </a>
            </div>

            <div class="house-showcase-grid">
                @forelse($properties as $idx => $property)
                    @php
                        $thumb = $property->thumbnail ?? $property->images->first();
                        $houseImg = $thumb 
                            ? asset('storage/' . $thumb->image_path) 
                            : ($defaultHouseImages[$idx % count($defaultHouseImages)]);
                        $pRoomsCount = $property->rooms->count();
                        $pRentedCount = $property->rooms->where('status', 'rented')->count();
                    @endphp
                    <a href="{{ route('owner.rooms.index') }}" class="house-card">
                        <div class="house-card-img-wrapper">
                            <img src="{{ $houseImg }}" alt="{{ $property->name }}" class="house-card-img">
                            <span class="house-card-badge">{{ $pRoomsCount }} phòng</span>
                        </div>
                        <div class="house-card-body">
                            <div class="house-card-title">{{ $property->name }}</div>
                            <div class="house-card-address">{{ $property->district ?? $property->city ?? 'Hồ Chí Minh' }}</div>
                            <div class="house-card-footer">
                                <span class="house-card-stats">{{ $pRentedCount }} phòng đang ở</span>
                                <span class="house-card-status">Đang vận hành</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="dashboard-empty">Chưa có khu trọ nào. Hãy thêm khu trọ để bắt đầu quản lý.</div>
                @endforelse
            </div>

            {{-- THANH ĐIỂM TIN PHÂN ĐOẠN (GIỐNG HÌNH 3 - SEGMENTED FILTER BAR) --}}
            <div class="segmented-filter-wrapper mb-4">
                <div class="segmented-filter-bar">
                    <a href="{{ route('owner.rooms.index') }}" class="segmented-tab active">
                        <span class="tab-label">Tổng số phòng</span>
                        <span class="tab-count">{{ $totalRooms }}</span>
                    </a>
                    <a href="{{ route('owner.rooms.index', ['status' => 'rented']) }}" class="segmented-tab">
                        <span class="tab-label">Phòng đang thuê</span>
                        <span class="tab-count">{{ $rentedRooms }}</span>
                    </a>
                    <a href="{{ route('owner.rooms.index', ['status' => 'available']) }}" class="segmented-tab">
                        <span class="tab-label">Phòng còn trống</span>
                        <span class="tab-count">{{ $availableRooms }}</span>
                    </a>
                    <a href="{{ route('owner.appointments.index') }}" class="segmented-tab">
                        <span class="tab-label">Lịch xem gần đây</span>
                        <span class="tab-count">{{ $appointments->count() }}</span>
                    </a>
                </div>
            </div>

            {{-- NỘI DUNG CHI TIẾT: 2 CỘT BẢNG (NO ICONS) --}}
            <div class="row g-4">
                
                {{-- CỘT TRÁI (7 COLS): DANH SÁCH PHÒNG GẦN ĐÂY --}}
                <div class="col-lg-7">
                    <div class="content-box">
                        <div class="box-head">
                            <div>
                                <h3 class="box-title">Danh sách phòng</h3>
                                <p class="box-sub">Trạng thái các phòng cho thuê gần đây</p>
                            </div>
                            <a href="{{ route('owner.rooms.index') }}" class="box-link">
                                Quản lý tất cả →
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="data-table-modern">
                                <thead>
                                    <tr>
                                        <th>Phòng</th>
                                        <th>Người thuê</th>
                                        <th>Giá thuê</th>
                                        <th>Trạng thái</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($rooms as $room)
                                        @php
                                            $activeContract = $room->contracts->first();
                                            $tenantName = $activeContract?->tenant?->name;
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="room-main-title">
                                                    {{ $room->name }}
                                                    @if ($room->property)
                                                        <span class="text-muted fw-normal" style="font-size: 12px;">· {{ $room->property->name }}</span>
                                                    @endif
                                                </div>
                                                <div class="room-sub-district">
                                                    {{ $room->property?->district ?? 'Chưa cập nhật khu vực' }}
                                                </div>
                                            </td>
                                            <td>
                                                @if ($tenantName)
                                                    <span class="tenant-chip-name">{{ $tenantName }}</span>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="price-badge-num">{{ number_format((float) $room->price, 0, ',', '.') }}đ</span>
                                            </td>
                                            <td>
                                                @switch($room->status)
                                                    @case('rented')
                                                        <span class="badge-pill-state rented">Đang thuê</span>
                                                        @break
                                                    @case('available')
                                                        <span class="badge-pill-state available">Còn trống</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge-pill-state pending">Đang chờ</span>
                                                        @break
                                                    @case('maintenance')
                                                        <span class="badge-pill-state maintenance">Bảo trì</span>
                                                        @break
                                                    @default
                                                        <span class="badge-pill-state">{{ $room->status }}</span>
                                                @endswitch
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-5">
                                                Chưa có phòng nào. Hãy thêm phòng để bắt đầu quản lý.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI (5 COLS): LỊCH HẸN XEM PHÒNG GẦN ĐÂY --}}
                <div class="col-lg-5">
                    <div class="content-box">
                        <div class="box-head">
                            <div>
                                <h3 class="box-title">Lịch xem phòng</h3>
                                <p class="box-sub">Khách đăng ký lịch xem mới nhất</p>
                            </div>
                            <a href="{{ route('owner.appointments.index') }}" class="box-link">
                                Xem tất cả →
                            </a>
                        </div>

                        <div class="table-responsive">
                            <table class="data-table-modern">
                                <thead>
                                    <tr>
                                        <th>Khách xem</th>
                                        <th>Phòng</th>
                                        <th>Thời gian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($appointments as $appointment)
                                        @php
                                            $tName = $appointment->tenant?->name ?? 'Khách xem';
                                        @endphp
                                        <tr>
                                            <td>
                                                <div class="tenant-chip-name">{{ $tName }}</div>
                                                <div class="small mt-1">
                                                    @switch($appointment->status)
                                                        @case('pending')
                                                            <span class="text-warning fw-semibold" style="font-size: 11px;">Chờ xác nhận</span>
                                                            @break
                                                        @case('confirmed')
                                                            <span class="text-success fw-semibold" style="font-size: 11px;">Đã xác nhận</span>
                                                            @break
                                                        @case('rejected')
                                                            <span class="text-danger fw-semibold" style="font-size: 11px;">Từ chối</span>
                                                            @break
                                                        @default
                                                            <span class="text-muted fw-semibold" style="font-size: 11px;">{{ $appointment->status }}</span>
                                                    @endswitch
                                                </div>
                                            </td>
                                            <td>
                                                <div class="room-main-title" style="font-size: 13px;">
                                                    {{ $appointment->room?->name ?? 'Phòng không tồn tại' }}
                                                </div>
                                                @if ($appointment->room?->property)
                                                    <div class="room-sub-district">
                                                        {{ $appointment->room->property->name }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="date-time-box">
                                                    <span class="date-text">{{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : '—' }}</span>
                                                    <span class="time-text">{{ substr((string) $appointment->appointment_time, 0, 5) }}</span>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-5">
                                                Chưa có lịch xem phòng mới.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

@endsection
