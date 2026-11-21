@extends('Layout.landlord')

@section('title', 'Chi Tiết Tin Đăng #' . $post->id . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/show.css') }}">
@endpush

@section('content')

@php
    $labels = [
        'draft' => ['text' => 'Bản nháp', 'class' => 'status-draft', 'bg' => '#f1f5f9', 'color' => '#475569', 'border' => '#e2e8f0'],
        'pending' => ['text' => 'Chờ duyệt', 'class' => 'status-pending', 'bg' => '#fef3c7', 'color' => '#b45309', 'border' => '#fde68a'],
        'published' => ['text' => 'Đang hiển thị', 'class' => 'status-published', 'bg' => '#dcfce7', 'color' => '#15803d', 'border' => '#bbf7d0'],
        'rejected' => ['text' => 'Bị từ chối', 'class' => 'status-rejected', 'bg' => '#fee2e2', 'color' => '#b91c1c', 'border' => '#fecaca'],
        'hidden' => ['text' => 'Đã ẩn', 'class' => 'status-hidden', 'bg' => '#f1f5f9', 'color' => '#64748b', 'border' => '#e2e8f0'],
    ];
    $statusConfig = $labels[$post->status] ?? [
        'text' => $post->status,
        'class' => '',
        'bg' => '#f1f5f9',
        'color' => '#475569',
        'border' => '#e2e8f0',
    ];

    $images = $post->images;
    $thumbnailImage = $images->firstWhere('is_thumbnail', true) ?? $images->first();
    $room = $post->room;
    $property = $room?->property;
@endphp

<div class="rental-detail-page">
    {{-- NÚT QUAY LẠI --}}
    <a href="{{ route('owner.rental-posts.index') }}" class="back-nav-link">
        <span>←</span>
        <span>Quay lại danh sách tin đăng</span>
    </a>

    {{-- THÔNG BÁO FLASH NẾU CÓ --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-4 mb-4 shadow-sm" role="alert">
            <strong>Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- CẢNH BÁO BỊ TỪ CHỐI --}}
    @if ($post->status === 'rejected' && $post->rejection_reason)
        <div class="alert-rejected-custom">
            <div class="title">
                <span>⚠️ Tin đăng này đã bị quản trị viên từ chối</span>
            </div>
            <div>
                <strong>Lý do từ chối:</strong> {{ $post->rejection_reason }}
            </div>
            <div class="mt-2 small text-muted">
                Bạn có thể nhấn <strong>Chỉnh sửa</strong> để cập nhật lại nội dung theo góp ý và gửi duyệt lại.
            </div>
        </div>
    @endif

    {{-- HEADER CARD (TIÊU ĐỀ & THAO TÁC) --}}
    <div class="detail-header-card">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div style="flex: 1; min-width: 280px;">
                <div class="post-meta-top">
                    <span class="post-id-badge">Mã tin #{{ $post->id }}</span>
                    <span class="post-status-pill {{ $statusConfig['class'] }}" style="background: {{ $statusConfig['bg'] }}; color: {{ $statusConfig['color'] }}; border: 1px solid {{ $statusConfig['border'] }};">
                        <span class="status-dot"></span>
                        <span>{{ $statusConfig['text'] }}</span>
                    </span>
                    @if ($room)
                        <span class="badge bg-light text-dark border">
                            {{ $room->name ?? ('Phòng #' . $room->id) }}
                        </span>
                    @endif
                </div>

                <h1 class="post-title-main">{{ $post->title }}</h1>

                <p class="post-location-subtitle">
                    <span>
                        {{ $property?->name ?? 'Chưa rõ khu trọ' }}
                        @if ($property)
                            • {{ $property->address }}, {{ $property->ward }}, {{ $property->district }}
                        @endif
                    </span>
                </p>
            </div>

            {{-- CÁC NÚT THAO TÁC --}}
            <div class="action-buttons-group">
                <a href="{{ route('owner.rental-posts.edit', $post) }}" class="btn-primary-dark">
                    <span>Chỉnh sửa</span>
                </a>

                @if ($post->status === 'hidden')
                    <form action="{{ route('owner.rental-posts.resubmit', $post) }}"
                          method="POST"
                          class="m-0"
                          onsubmit="return confirm('Bạn có chắc muốn đăng lại tin này để Admin kiểm duyệt?')">
                        @csrf
                        <button type="submit" class="btn-soft-primary">
                            <span>Đăng lại tin</span>
                        </button>
                    </form>
                @elseif ($post->status === 'published')
                    <form action="{{ route('owner.rental-posts.destroy', $post) }}"
                          method="POST"
                          class="m-0"
                          onsubmit="return confirm('Bạn có chắc muốn ẩn tin đăng này không? Tin sẽ không còn hiển thị với người thuê nhưng bạn có thể đăng lại bất kỳ lúc nào.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-soft-danger">
                            <span>Ẩn tin đăng</span>
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- THANH CHỈ SỐ NHANH (QUICK SPECS) --}}
    <div class="quick-specs-grid">
        <div class="spec-card">
            <div>
                <div class="spec-label">Giá thuê niêm yết</div>
                <div class="spec-value">
                    @if ($room?->price !== null)
                        {{ number_format((float) $room->price, 0, ',', '.') }} <span class="spec-unit">đ/tháng</span>
                    @else
                        Chưa đặt
                    @endif
                </div>
            </div>
        </div>

        <div class="spec-card">
            <div>
                <div class="spec-label">Tiền đặt cọc</div>
                <div class="spec-value">
                    @if ($room?->deposit !== null)
                        {{ number_format((float) $room->deposit, 0, ',', '.') }} <span class="spec-unit">đ</span>
                    @else
                        0 <span class="spec-unit">đ</span>
                    @endif
                </div>
            </div>
        </div>

        <div class="spec-card">
            <div>
                <div class="spec-label">Diện tích phòng</div>
                <div class="spec-value">
                    {{ $room?->area !== null ? $room->area : '—' }} <span class="spec-unit">m²</span>
                </div>
            </div>
        </div>

        <div class="spec-card">
            <div>
                <div class="spec-label">Sức chứa tối đa</div>
                <div class="spec-value">
                    {{ $room?->max_people ?? 1 }} <span class="spec-unit">người</span>
                </div>
            </div>
        </div>
    </div>

    {{-- LAYOUT CHÍNH: 2 CỘT (8 CỘT BÊN TRÁI + 4 CỘT BÊN PHẢI) --}}
    <div class="row g-4">
        {{-- CỘT TRÁI (8 CỘT) --}}
        <div class="col-lg-8">
            {{-- THƯ VIỆN HÌNH ẢNH PHÒNG --}}
            <div class="gallery-card">
                <div class="section-head">
                    <h3 class="section-title">
                        <span>Hình ảnh phòng trọ</span>
                    </h3>
                    @if ($images->isNotEmpty())
                        <span class="badge bg-light text-secondary border">
                            {{ $images->count() }} hình ảnh
                        </span>
                    @endif
                </div>

                @if ($images->isNotEmpty())
                    {{-- Khung hiển thị ảnh lớn tương tác --}}
                    <div class="main-image-viewport">
                        <img id="mainGalleryImg"
                             src="{{ asset('storage/' . $thumbnailImage->image_path) }}"
                             alt="{{ $post->title }}">

                        <div id="thumbBadge" class="main-image-badge" style="{{ $thumbnailImage->is_thumbnail ? '' : 'display: none;' }}">
                            <span>Ảnh đại diện</span>
                        </div>

                        <div class="image-counter-badge" id="imageCounter">
                            Ảnh 1 / {{ $images->count() }}
                        </div>
                    </div>

                    {{-- Dải thumbnail cuộn bên dưới --}}
                    @if ($images->count() > 1)
                        <div class="thumbnails-track">
                            @foreach ($images as $index => $image)
                                <div class="thumbnail-item {{ $image->id === $thumbnailImage->id ? 'active' : '' }}"
                                     data-full-src="{{ asset('storage/' . $image->image_path) }}"
                                     data-is-thumb="{{ $image->is_thumbnail ? '1' : '0' }}"
                                     data-index="{{ $index + 1 }}"
                                     onclick="selectGalleryImage(this)">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="Ảnh {{ $index + 1 }}"
                                         loading="lazy">
                                    @if ($image->is_thumbnail)
                                        <span class="thumb-featured-dot" title="Ảnh đại diện"></span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="empty-gallery-box">
                        <h6 class="fw-bold mb-1">Chưa có hình ảnh nào cho tin đăng này</h6>
                        <p class="small text-muted mb-3">Hình ảnh thực tế giúp phòng trọ được thuê nhanh hơn và tăng độ tin cậy.</p>
                        <a href="{{ route('owner.rental-posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                            + Tải ảnh lên ngay
                        </a>
                    </div>
                @endif
            </div>

            {{-- NỘI DUNG MÔ TẢ TIN ĐĂNG --}}
            <div class="content-detail-card">
                <div class="section-head">
                    <h3 class="section-title">
                        <span>Mô tả chi tiết bài đăng</span>
                    </h3>
                </div>

                @if (filled($post->description))
                    <div class="description-body">
                        {{ $post->description }}
                    </div>
                @else
                    <div class="text-muted p-3 bg-light rounded text-center small">
                        Tin đăng này chưa có phần mô tả chi tiết.
                    </div>
                @endif

                {{-- TIỆN ÍCH PHÒNG TRỌ NẾU CÓ --}}
                @if ($room && $room->amenities && $room->amenities->isNotEmpty())
                    <div class="mt-4 pt-3 border-top">
                        <h5 class="fw-bold fs-6 mb-2">Tiện ích phòng có sẵn:</h5>
                        <div class="amenities-badges-wrap">
                            @foreach ($room->amenities as $amenity)
                                <span class="amenity-chip">
                                    <span class="amenity-chip-check">✓</span>
                                    <span>{{ $amenity->name }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- CỘT PHẢI (4 CỘT) --}}
        <div class="col-lg-4">
            {{-- THÔNG TIN PHÒNG VÀ KHU TRỌ --}}
            <div class="side-panel-card">
                <h4 class="side-panel-title">
                    <span>Thông tin phòng & Nhà trọ</span>
                </h4>

                <div class="side-info-list">
                    <div class="side-info-row">
                        <span class="side-info-label">Khu trọ</span>
                        <span class="side-info-value">{{ $property?->name ?? '—' }}</span>
                    </div>

                    <div class="side-info-row">
                        <span class="side-info-label">Phòng liên kết</span>
                        <span class="side-info-value">
                            {{ $room?->name ?? ('Phòng #' . ($room?->id ?? '—')) }}
                            @if ($room?->room_code)
                                <small class="text-secondary fw-normal">({{ $room->room_code }})</small>
                            @endif
                        </span>
                    </div>

                    <div class="side-info-row">
                        <span class="side-info-label">Vị trí tầng</span>
                        <span class="side-info-value">
                            {{ $room?->floor ? 'Tầng ' . $room->floor : 'Trệt / Không áp dụng' }}
                        </span>
                    </div>

                    <div class="side-info-row">
                        <span class="side-info-label">Trạng thái phòng</span>
                        <span class="side-info-value">
                            @switch($room?->status)
                                @case('available')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle">Còn trống</span>
                                    @break
                                @case('rented')
                                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle">Đang thuê</span>
                                    @break
                                @case('maintenance')
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle">Bảo trì</span>
                                    @break
                                @case('hidden')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle">Đang ẩn</span>
                                    @break
                                @default
                                    <span class="badge bg-light text-dark border">{{ $room?->status ?? '—' }}</span>
                            @endswitch
                        </span>
                    </div>

                    <div class="side-info-row">
                        <span class="side-info-label">Địa chỉ khu trọ</span>
                        <span class="side-info-value fw-normal small text-secondary">
                            {{ $property?->address ? $property->address . ', ' . $property->ward . ', ' . $property->district : 'Chưa cập nhật' }}
                        </span>
                    </div>
                </div>

                @if ($room)
                    <a href="{{ route('owner.rooms.edit', $room) }}" class="side-link-btn">
                        <span>Quản lý chi tiết phòng này →</span>
                    </a>
                @endif
            </div>

            {{-- THÔNG TIN ĐĂNG BÀI & THỜI GIAN --}}
            <div class="side-panel-card">
                <h4 class="side-panel-title">
                    <span>Lịch trình tin đăng</span>
                </h4>

                <div class="side-info-list">
                    <div class="side-info-row">
                        <span class="side-info-label">Ngày tạo tin</span>
                        <span class="side-info-value">
                            {{ $post->created_at ? $post->created_at->format('d/m/Y H:i') : '—' }}
                        </span>
                    </div>

                    <div class="side-info-row">
                        <span class="side-info-label">Ngày xuất bản</span>
                        <span class="side-info-value">
                            {{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Chưa xuất bản' }}
                        </span>
                    </div>

                    <div class="side-info-row">
                        <span class="side-info-label">Ngày hết hạn</span>
                        <span class="side-info-value">
                            @if ($post->expired_at)
                                {{ $post->expired_at->format('d/m/Y') }}
                                @if ($post->expired_at->isPast())
                                    <span class="badge bg-danger-subtle text-danger ms-1">Đã hết hạn</span>
                                @else
                                    <span class="badge bg-info-subtle text-info ms-1">Còn {{ $post->expired_at->diffInDays(now()) }} ngày</span>
                                @endif
                            @else
                                <span class="text-secondary fw-normal">Vô thời hạn</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- MẸO CHO CHỦ TRỌ --}}
            <div class="tip-box-container">
                <div class="tip-box-header">
                    <span>Mẹo giúp phòng cho thuê nhanh:</span>
                </div>
                <p class="tip-box-text">
                    • Cung cấp đầy đủ từ 3 - 5 hình ảnh rõ nét các góc phòng, nhà vệ sinh và chỗ để xe.<br>
                    • Ghi rõ chi phí điện, nước và các dịch vụ khác trong mô tả để khách thuê dễ quyết định.
                </p>
            </div>
        </div>
    </div>
</div>

{{-- JAVASCRIPT ĐIỀU HƯỚNG BỘ SƯU TẬP ẢNH TƯƠNG TÁC --}}
<script>
    function selectGalleryImage(el) {
        const fullSrc = el.getAttribute('data-full-src');
        const isThumb = el.getAttribute('data-is-thumb') === '1';
        const index = el.getAttribute('data-index');
        const total = {{ $images->count() }};

        const mainImg = document.getElementById('mainGalleryImg');
        const thumbBadge = document.getElementById('thumbBadge');
        const counter = document.getElementById('imageCounter');

        if (mainImg && fullSrc) {
            mainImg.style.opacity = '0.4';
            setTimeout(() => {
                mainImg.src = fullSrc;
                mainImg.style.opacity = '1';
            }, 120);
        }

        if (thumbBadge) {
            thumbBadge.style.display = isThumb ? 'flex' : 'none';
        }

        if (counter) {
            counter.textContent = `Ảnh ${index} / ${total}`;
        }

        document.querySelectorAll('.thumbnail-item').forEach(item => {
            item.classList.remove('active');
        });
        el.classList.add('active');
    }
</script>

@endsection
