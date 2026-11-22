@extends('Layout.landlord')

@section('title', 'Chi Tiết Nhà Trọ | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/properties/show.css') }}">
@endpush

@section('content')

@php
    $rooms = $property->rooms ?? collect();
    $totalRooms = $rooms->count();
    $availableRooms = $rooms->where('status', 'available')->count();
    $rentedRooms = $rooms->where('status', 'rented')->count();
    $occupancyRate = $totalRooms > 0 ? round(($rentedRooms / $totalRooms) * 100) : 0;

    $typeNames = [
        'rooming_house' => 'Nhà trọ',
        'apartment' => 'Căn hộ chung cư',
        'house' => 'Nhà nguyên căn',
        'homestay' => 'Homestay / Ký túc xá',
    ];
    $propertyType = $typeNames[$property->type] ?? ($property->type ?: 'Nhà trọ');

    $propLat = $property->latitude;
    $propLng = $property->longitude;
    $propHasCoords = !empty($propLat) && !empty($propLng) && is_numeric($propLat) && is_numeric($propLng);
    $propAddress = trim(implode(', ', array_filter([$property->address, $property->ward, $property->district])));
    $propMapQuery = $propHasCoords ? "{$propLat},{$propLng}" : ($propAddress ?: null);
@endphp

<div class="property-detail-page">

    {{-- QUAY LẠI --}}
    <a href="{{ route('owner.properties.index') }}" class="back-nav-btn">
        <span>←</span>
        <span>Quay lại danh sách nhà trọ</span>
    </a>

    {{-- THÔNG BÁO FLASH --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- HEADER CARD --}}
    <div class="property-header-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-light text-secondary border">Mã nhà #{{ $property->id }}</span>
                    @if ($property->status === 'active')
                        <span class="status-pill-active">Đang hoạt động</span>
                    @else
                        <span class="status-pill-inactive">Tạm ngưng</span>
                    @endif
                    <span class="badge bg-light text-dark border">{{ $propertyType }}</span>
                </div>

                <h1 class="page-main-title">{{ $property->name }}</h1>
                <p class="page-main-desc">
                    {{ $property->address }}, {{ $property->ward }}, {{ $property->district }}
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('owner.rooms.create', ['property_id' => $property->id]) }}" class="btn-outline-custom">
                    <span>+ Thêm phòng mới</span>
                </a>
                <a href="{{ route('owner.properties.edit', $property->id) }}" class="btn-primary-dark">
                    <span>Sửa thông tin</span>
                </a>
            </div>
        </div>
    </div>

    {{-- THANH 4 CHỈ SỐ NHANH --}}
    <div class="property-stats-grid">
        <div class="stat-metric-card">
            <div class="stat-metric-label">Tổng số phòng</div>
            <div class="stat-metric-value">
                {{ $totalRooms }} <span class="stat-metric-unit">phòng</span>
            </div>
        </div>

        <div class="stat-metric-card">
            <div class="stat-metric-label">Phòng đang trống</div>
            <div class="stat-metric-value text-success">
                {{ $availableRooms }} <span class="stat-metric-unit">phòng</span>
            </div>
        </div>

        <div class="stat-metric-card">
            <div class="stat-metric-label">Đang cho thuê</div>
            <div class="stat-metric-value text-primary">
                {{ $rentedRooms }} <span class="stat-metric-unit">phòng</span>
            </div>
        </div>

        <div class="stat-metric-card">
            <div class="stat-metric-label">Tỷ lệ lấp đầy</div>
            <div class="stat-metric-value">
                {{ $occupancyRate }}<span class="stat-metric-unit">%</span>
            </div>
        </div>
    </div>

    {{-- THƯ VIỆN HÌNH ẢNH NHÀ TRỌ --}}
    <div class="content-panel-card">
        <div class="panel-card-head">
            <h3 class="panel-card-title">Hình ảnh nhà trọ</h3>
            @if ($property->images->isNotEmpty())
                <span class="badge bg-light text-secondary border">
                    {{ $property->images->count() }} hình ảnh
                </span>
            @endif
        </div>

        @if ($property->images->isNotEmpty())
            @php
                $thumbImg = $property->images->firstWhere('is_thumbnail', true) ?? $property->images->first();
            @endphp
            {{-- Khung hiển thị ảnh lớn tương tác --}}
            <div class="property-main-img-viewport">
                <img id="propertyMainImg"
                     src="{{ asset('storage/' . $thumbImg->image_path) }}"
                     alt="{{ $property->name }}">

                <div id="propThumbBadge" class="prop-thumb-badge" style="{{ $thumbImg->is_thumbnail ? '' : 'display: none;' }}">
                    <span>Ảnh đại diện</span>
                </div>

                <div class="prop-counter-badge" id="propImgCounter">
                    Ảnh 1 / {{ $property->images->count() }}
                </div>
            </div>

            {{-- Dải thumbnail cuộn bên dưới --}}
            @if ($property->images->count() > 1)
                <div class="prop-thumbnails-track">
                    @foreach ($property->images as $index => $img)
                        <div class="prop-thumb-item {{ $img->id === $thumbImg->id ? 'active' : '' }}"
                             data-full-src="{{ asset('storage/' . $img->image_path) }}"
                             data-is-thumb="{{ $img->is_thumbnail ? '1' : '0' }}"
                             data-index="{{ $index + 1 }}"
                             onclick="selectPropertyGalleryImage(this)">
                            <img src="{{ asset('storage/' . $img->image_path) }}"
                                 alt="Ảnh {{ $index + 1 }}"
                                 loading="lazy">
                            @if ($img->is_thumbnail)
                                <span class="prop-thumb-dot" title="Ảnh đại diện"></span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        @else
            <div class="empty-rooms-box">
                <h6 class="fw-bold mb-1">Nhà trọ này chưa có hình ảnh</h6>
                <p class="small text-muted mb-3">Tải ảnh mặt tiền, lối đi và khuôn viên nhà trọ để tăng tính trực quan.</p>
                <a href="{{ route('owner.properties.edit', $property->id) }}" class="btn-primary-dark">
                    + Tải ảnh lên ngay
                </a>
            </div>
        @endif
    </div>

    {{-- THÔNG TIN CHI TIẾT & BẢN ĐỒ VỊ TRÍ --}}
    <div class="content-panel-card">
        <div class="panel-card-head">
            <h3 class="panel-card-title">Thông tin tổng quan & Vị trí</h3>
            @if ($propMapQuery)
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($propMapQuery) }}" target="_blank" rel="noopener noreferrer" class="small text-decoration-none fw-bold text-primary">
                    Mở Google Maps ↗
                </a>
            @endif
        </div>

        <div class="row g-4">
            {{-- CỘT THÔNG TIN --}}
            <div class="col-lg-7">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Tên nhà trọ</span>
                            <span class="info-field-value">{{ $property->name }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Loại hình bất động sản</span>
                            <span class="info-field-value">{{ $propertyType }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-field-group">
                            <span class="info-field-label">Địa chỉ chi tiết</span>
                            <span class="info-field-value">{{ $property->address }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Phường / Xã</span>
                            <span class="info-field-value">{{ $property->ward ?: '—' }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Quận / Huyện</span>
                            <span class="info-field-value">{{ $property->district ?: '—' }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Tọa độ Vĩ độ (Lat)</span>
                            <span class="info-field-value fw-normal text-secondary">{{ $property->latitude ?: 'Chưa cập nhật' }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Tọa độ Kinh độ (Lng)</span>
                            <span class="info-field-value fw-normal text-secondary">{{ $property->longitude ?: 'Chưa cập nhật' }}</span>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="info-field-group">
                            <span class="info-field-label">Mô tả</span>
                            <div class="info-field-value fw-normal text-secondary" style="line-height: 1.6; white-space: pre-line;">
                                {{ $property->description ?: 'Chưa có mô tả cho nhà trọ này.' }}
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Ngày tạo hồ sơ</span>
                            <span class="info-field-value fw-normal text-secondary">{{ $property->created_at?->format('d/m/Y H:i') ?? '—' }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="info-field-group">
                            <span class="info-field-label">Cập nhật lần cuối</span>
                            <span class="info-field-value fw-normal text-secondary">{{ $property->updated_at?->format('d/m/Y H:i') ?? '—' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CỘT BẢN ĐỒ VỊ TRÍ --}}
            <div class="col-lg-5">
                <div class="info-field-label mb-2">Bản đồ vệ tinh & vị trí</div>
                @if ($propMapQuery)
                    <div class="property-map-wrapper">
                        <iframe
                            style="width: 100%; height: 280px; border: 0; display: block;"
                            src="https://maps.google.com/maps?q={{ urlencode($propMapQuery) }}&hl=vi&z=16&output=embed"
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                        ></iframe>
                    </div>
                @else
                    <div class="p-4 bg-light rounded-3 text-center text-muted small">
                        Chưa có thông tin địa chỉ hoặc tọa độ để hiển thị bản đồ.
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- BẢNG GIÁ DỊCH VỤ ĐANG ÁP DỤNG RIÊNG CHO NHÀ TRỌ NÀY --}}
    <div class="content-panel-card">
        <div class="panel-card-head">
            <div>
                <h3 class="panel-card-title">Dịch vụ & Biểu phí áp dụng</h3>
                <div class="small text-muted mt-1">Đơn giá điện, nước và dịch vụ cấu hình riêng cho nhà trọ này</div>
            </div>
            <a href="{{ route('owner.services.index', ['property_id' => $property->id]) }}" class="btn-outline-custom" style="padding: 6px 14px; font-size: 13px;">
                Cấu hình dịch vụ →
            </a>
        </div>

        @if(isset($propertyServices) && $propertyServices->isNotEmpty())
            <div class="row g-3">
                @foreach($propertyServices as $srv)
                    <div class="col-md-3 col-sm-6">
                        <div class="service-chip-item">
                            <div>
                                <div class="service-chip-name">{{ $srv->name }}</div>
                                <div class="small text-muted">{{ $srv->description ?: $srv->unit }}</div>
                            </div>
                            <div class="text-end">
                                <div class="service-chip-price">{{ number_format((float)$srv->price) }} đ</div>
                                <div class="service-chip-unit">/ {{ $srv->unit }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-3 bg-light rounded-3 text-center text-muted small">
                Chưa cài đặt biểu phí riêng cho nhà trọ này. Hệ thống sẽ áp dụng theo mức giá dịch vụ chung của tài khoản.
                <a href="{{ route('owner.services.index', ['property_id' => $property->id]) }}" class="fw-bold ms-1 text-decoration-none">Cài đặt ngay</a>
            </div>
        @endif
    </div>

    {{-- DANH SÁCH PHÒNG THUỘC NHÀ TRỌ --}}
    <div class="content-panel-card">
        <div class="panel-card-head">
            <div>
                <h3 class="panel-card-title">Danh sách phòng thuộc nhà trọ</h3>
                <div class="small text-muted mt-1">Tổng cộng {{ $totalRooms }} phòng</div>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('owner.rooms.create', ['property_id' => $property->id]) }}" class="btn-primary-dark" style="padding: 8px 16px; font-size: 13px;">
                    + Thêm phòng mới
                </a>
                <a href="{{ route('owner.rooms.index', ['property_id' => $property->id]) }}" class="btn-outline-custom" style="padding: 8px 16px; font-size: 13px;">
                    Quản lý tất cả phòng
                </a>
            </div>
        </div>

        @if ($rooms->isEmpty())
            <div class="empty-rooms-box">
                <h6 class="fw-bold mb-1">Nhà trọ này chưa có phòng nào</h6>
                <p class="small text-muted mb-3">Hãy tạo các phòng để bắt đầu quản lý người thuê và xuất bản tin đăng.</p>
                <a href="{{ route('owner.rooms.create', ['property_id' => $property->id]) }}" class="btn-primary-dark">
                    + Thêm phòng đầu tiên
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="room-table-custom">
                    <thead>
                        <tr>
                            <th>Mã phòng</th>
                            <th>Tên phòng</th>
                            <th>Diện tích</th>
                            <th>Giá thuê niêm yết</th>
                            <th>Tiền đặt cọc</th>
                            <th>Sức chứa</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rooms as $room)
                            <tr>
                                <td>
                                    <span class="room-code-badge">{{ $room->room_code ?? ('#' . $room->id) }}</span>
                                </td>
                                <td>
                                    <strong>{{ $room->name }}</strong>
                                    @if ($room->floor)
                                        <div class="small text-muted">Tầng {{ $room->floor }}</div>
                                    @endif
                                </td>
                                <td>{{ number_format((float)$room->area, 1, ',', '.') }} m²</td>
                                <td>
                                    <strong class="text-dark">{{ number_format((float)$room->price, 0, ',', '.') }} đ</strong>
                                    <span class="small text-muted">/tháng</span>
                                </td>
                                <td>{{ number_format((float)$room->deposit, 0, ',', '.') }} đ</td>
                                <td>{{ $room->max_people ?? 1 }} người</td>
                                <td>
                                    @switch($room->status)
                                        @case('available')
                                            <span class="room-status-badge status-available">Còn trống</span>
                                            @break
                                        @case('rented')
                                            <span class="room-status-badge status-rented">Đang thuê</span>
                                            @break
                                        @case('maintenance')
                                            <span class="room-status-badge status-maintenance">Bảo trì</span>
                                            @break
                                        @case('hidden')
                                            <span class="room-status-badge status-hidden">Đang ẩn</span>
                                            @break
                                        @default
                                            <span class="badge bg-light text-dark border">{{ $room->status }}</span>
                                    @endswitch
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('owner.rooms.edit', $room) }}" class="btn-table-action">
                                        Chi tiết & Sửa
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>

@if ($property->images->isNotEmpty())
<script>
    function selectPropertyGalleryImage(el) {
        const fullSrc = el.getAttribute('data-full-src');
        const isThumb = el.getAttribute('data-is-thumb') === '1';
        const index = el.getAttribute('data-index');
        const total = {{ $property->images->count() }};

        const mainImg = document.getElementById('propertyMainImg');
        const thumbBadge = document.getElementById('propThumbBadge');
        const counter = document.getElementById('propImgCounter');

        if (mainImg && fullSrc) {
            mainImg.style.opacity = '0.35';
            setTimeout(() => {
                mainImg.src = fullSrc;
                mainImg.style.opacity = '1';
            }, 120);
        }

        if (thumbBadge) {
            thumbBadge.style.display = isThumb ? 'block' : 'none';
        }

        if (counter) {
            counter.textContent = `Ảnh ${index} / ${total}`;
        }

        document.querySelectorAll('.prop-thumb-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
    }
</script>
@endif

@endsection
