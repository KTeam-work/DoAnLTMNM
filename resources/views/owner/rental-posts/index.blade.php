@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/modal_premium.css') }}">
    <style>
        .segmented-filter-wrapper {
            display: flex !important;
            width: 100% !important;
            align-items: center !important;
            margin-bottom: 1.5rem !important;
            overflow-x: auto !important;
            padding-bottom: 4px !important;
        }
        .segmented-filter-bar {
            display: flex !important;
            width: 100% !important;
            align-items: center !important;
            gap: 6px !important;
            padding: 6px !important;
            background: #f2efe9 !important;
            border: 1px solid #e5e0d5 !important;
            border-radius: 12px !important;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03) !important;
        }
        .segmented-tab {
            flex: 1 1 0 !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            padding: 10px 16px !important;
            border: none !important;
            background: transparent !important;
            border-radius: 8px !important;
            color: #555c58 !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            cursor: pointer !important;
            white-space: nowrap !important;
            transition: all 0.18s ease !important;
            text-decoration: none !important;
            user-select: none !important;
            text-align: center !important;
        }
        .segmented-tab:hover {
            background: rgba(255, 255, 255, 0.6);
            color: #1a221e;
        }
        .segmented-tab.active {
            background: #ffffff;
            color: #162c21;
            font-weight: 600;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.03);
        }
        .tab-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            border-radius: 999px;
            font-size: 11.5px;
            font-weight: 700;
            background: #ded7cb;
            color: #525955;
            line-height: 1;
            transition: all 0.18s ease;
        }
        .segmented-tab.active .tab-count {
            background: #e4efe7;
            color: #134e2c;
        }
    </style>
@endpush

@section('content')




<main class="owner-main">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    <div class="heading">
        <div>
            <div class="eyebrow">Rental posts</div>
            <h1>Quản lý tin đăng</h1>
            <p>Quản lý tin cho thuê, theo dõi trạng thái và cập nhật nội dung.</p>
        </div>

        <button type="button" class="btn-owner" data-bs-toggle="modal" data-bs-target="#createPostModal">
            + Tạo tin đăng
        </button>
    </div>

    @php
        $totalPostsCount = $posts->count();
        $publishedCount = $posts->where('status', 'published')->count();
        $pendingCount = $posts->where('status', 'pending')->count();
        $draftCount = $posts->where('status', 'draft')->count();
        $rejectedCount = $posts->where('status', 'rejected')->count();
        $hiddenCount = $posts->where('status', 'hidden')->count();
    @endphp

    <div class="segmented-filter-wrapper mb-4">
        <div class="segmented-filter-bar" role="tablist">
            <button type="button" class="segmented-tab active" data-filter="">
                <span class="tab-label">Tất cả</span>
                <span class="tab-count">{{ $totalPostsCount }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="published">
                <span class="tab-label">Đang hiển thị</span>
                <span class="tab-count">{{ $publishedCount }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="pending">
                <span class="tab-label">Chờ duyệt</span>
                <span class="tab-count">{{ $pendingCount }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="rejected">
                <span class="tab-label">Bị từ chối</span>
                <span class="tab-count">{{ $rejectedCount }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="hidden">
                <span class="tab-label">Đã ẩn</span>
                <span class="tab-count">{{ $hiddenCount }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="draft">
                <span class="tab-label">Bản nháp</span>
                <span class="tab-count">{{ $draftCount }}</span>
            </button>
        </div>
    </div>

    <section class="posts-container">
        <div class="list-heading">
            <h2>Danh sách tin đăng</h2>
            <p>Thông tin được lấy từ dữ liệu tin đăng và phòng trọ.</p>
        </div>

        <div class="toolbar">
            <div class="search-wrap">
                <input class="search" id="postSearch" type="search"
                       placeholder="Tìm theo tiêu đề hoặc phòng..."
                       aria-label="Tìm tin đăng">
            </div>

            <select class="filter" id="postStatus" aria-label="Lọc trạng thái">
                <option value="">Tất cả trạng thái</option>
                <option value="draft">Bản nháp</option>
                <option value="pending">Chờ duyệt</option>
                <option value="published">Đang hiển thị</option>
                <option value="rejected">Bị từ chối</option>
                <option value="hidden">Đã ẩn</option>
            </select>
        </div>

        <div class="posts-list" id="postsList">
            @forelse ($posts as $post)
                @php
                    $statusLabels = [
                        'draft' => 'BẢN NHÁP',
                        'pending' => 'CHỜ DUYỆT',
                        'published' => 'ĐANG HIỂN THỊ',
                        'rejected' => 'BỊ TỪ CHỐI',
                        'hidden' => 'ĐÃ ẨN',
                    ];

                    $statusClasses = [
                        'draft' => 'status-hidden',
                        'pending' => 'status-pending',
                        'published' => 'status-approved',
                        'rejected' => 'status-rejected',
                        'hidden' => 'status-hidden',
                    ];

                    $room = $post->room;
                    $property = $room?->property;

                    $searchText = $post->title . ' ' .
                        ($room?->room_number ?? '') . ' ' .
                        ($room?->name ?? '') . ' ' .
                        ($property?->name ?? '');
                @endphp

                <article class="post-card"
                         data-status="{{ $post->status }}"
                         data-search="{{ $searchText }}">

                    <div class="post-image">
                        @php
                            $thumbnail = $post->images->firstWhere('is_thumbnail', true)
                                ?? $post->images->first();
                        @endphp

                        @if ($thumbnail)
                            <img
                                src="{{ asset('storage/' . $thumbnail->image_path) }}"
                                alt="{{ $post->title }}"
                                class="post-thumbnail"
                                loading="lazy"
                            >
                        @else
                            <div class="image-placeholder">🏠</div>
                        @endif
                    </div>
                    <div class="post-info">
                        <h3>{{ $post->title }}</h3>

                        <div class="post-room">
                            Phòng:
                            {{ $room?->room_number ?? $room?->name ?? ('#' . ($room?->id ?? '—')) }}
                            · {{ $property?->name ?? 'Chưa có tên nhà trọ' }}
                        </div>

                        <div class="post-price">
                            @if ($room && $room->price !== null)
                                {{ number_format((float) $room->price, 0, ',', '.') }}đ / tháng
                            @else
                                Chưa cập nhật giá thuê
                            @endif
                        </div>

                        <div class="post-meta">
                            <span>
                                📐
                                @if ($room && $room->area !== null)
                                    {{ $room->area }} m²
                                @else
                                    Chưa có diện tích
                                @endif
                            </span>

                            @if ($post->published_at)
                                <span>📅 Đăng ngày {{ $post->published_at->format('d/m/Y') }}</span>
                            @endif

                            @if ($post->expired_at)
                                <span>⏰ Hết hạn {{ $post->expired_at->format('d/m/Y') }}</span>
                            @endif
                        </div>

                        @if ($post->status === 'rejected' && $post->rejection_reason)
                            <div class="mt-2 small text-danger">
                                Lý do: {{ $post->rejection_reason }}
                            </div>
                        @endif
                    </div>

                    <div class="post-right">
                        <span class="post-status {{ $statusClasses[$post->status] ?? 'status-hidden' }}">
                            {{ $statusLabels[$post->status] ?? $post->status }}
                        </span>

                        <div class="post-actions">
                            @if ($post->status === 'hidden')
                                <form action="{{ route('owner.rental-posts.resubmit', $post) }}"
                                      method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn đăng lại tin này để Admin kiểm duyệt?')">
                                    @csrf
                                    <button type="submit" class="btn-resubmit" style="background: #e0f2fe; color: #0369a1;">
                                        Đăng lại
                                    </button>
                                </form>
                            @endif

                            <a href="{{ route('owner.rental-posts.show', $post) }}"
                               class="btn-view">Xem</a>

                            <a href="{{ route('owner.rental-posts.edit', $post) }}"
                               class="btn-edit">Sửa</a>

                            @if ($post->status !== 'hidden')
                                <form action="{{ route('owner.rental-posts.destroy', $post) }}"
                                      method="POST"
                                      onsubmit="return confirm('Bạn có chắc muốn ẩn tin đăng này không? Tin sẽ không còn hiển thị với người thuê nhưng bạn có thể đăng lại bất kỳ lúc nào.')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-delete" style="background: #fef2f2; color: #dc2626;">Ẩn tin</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <h3>Chưa có tin đăng nào</h3>
                    <p>Tạo tin đăng cho phòng trọ để bắt đầu quản lý tại đây.</p>
                    <button type="button" class="btn-owner" data-bs-toggle="modal" data-bs-target="#createPostModal">
                        + Tạo tin đăng đầu tiên
                    </button>
                </div>
            @endforelse
        </div>

        @if ($posts->isNotEmpty())
            <div class="results-count" id="resultsCount">
                Hiển thị {{ $posts->count() }} tin đăng
            </div>
        @endif
    </section>
</main>

<!-- MODAL TẠO TIN ĐĂNG MỚI (PREMIUM DARK HEADER NHƯ HÌNH 3) -->
<div class="modal fade modal-premium" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPostModalLabel">Tạo tin đăng mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            @if (!isset($availableRooms) || $availableRooms->isEmpty())
                <div class="modal-body p-4">
                    <div class="alert alert-warning mb-0" style="border-radius: 12px;">
                        <strong>Thông báo:</strong> Hiện tại bạn không có phòng nào ở trạng thái <strong>Còn trống</strong> (hoặc tất cả phòng trống đã có tin đăng). Vui lòng cập nhật trạng thái phòng trong <a href="{{ route('owner.rooms.index') }}" class="fw-bold">Quản lý phòng</a> trước.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Đóng</button>
                </div>
            @else
                <form action="{{ route('owner.rental-posts.store') }}" method="POST" enctype="multipart/form-data" id="modalCreatePostForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="mb-3 p-3 bg-light border rounded-3">
                            <label class="form-label fw-bold mb-2">Chọn phòng trọ cần đăng tin <span class="text-danger">*</span></label>
                            @if (isset($properties) && $properties->count() > 1)
                                <div class="mb-2">
                                    <select id="modalPropertyFilter" class="form-select form-select-sm mb-2">
                                        <option value="">-- Tất cả nhà trọ ({{ $availableRooms->count() }} phòng trống) --</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}">{{ $property->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <select name="room_id" id="modal_room_id" class="form-select" required>
                                <option value="">-- Chọn phòng trọ còn trống --</option>
                                @foreach ($availableRooms as $room)
                                    <option value="{{ $room->id }}"
                                        data-property-id="{{ $room->property_id }}"
                                        data-price="{{ number_format((float) $room->price, 0, ',', '.') }}đ/tháng"
                                        data-area="{{ $room->area }}"
                                        data-property="{{ $room->property->name ?? '' }}">
                                        {{ $room->name }} ({{ $room->property->name ?? '' }}) — Giá: {{ number_format((float) $room->price, 0, ',', '.') }}đ/tháng — {{ $room->area }}m²
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Tiêu đề tin đăng <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Ví dụ: Phòng trọ đầy đủ tiện nghi gần trường ĐH..." required maxlength="255">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Mô tả chi tiết <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Nhập mô tả vị trí, nội thất, giờ giấc, an ninh..." required></textarea>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Trạng thái tin</label>
                                <select name="status" class="form-select">
                                    <option value="draft">Bản nháp</option>
                                    <option value="pending" selected>Gửi duyệt</option>
                                    <option value="hidden">Ẩn tin</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hình ảnh phòng (1 - 10 ảnh) <span class="text-danger">*</span></label>
                                <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/webp" multiple required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="button" class="btn btn-light px-4 rounded-3 border fw-semibold" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold" style="background: #0c2340; border-color: #0c2340;">
                            Lưu &amp; Đăng tin
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/tv3/rental_protal/index.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var propFilter = document.getElementById('modalPropertyFilter');
            var roomSelect = document.getElementById('modal_room_id');
            if (propFilter && roomSelect) {
                propFilter.addEventListener('change', function () {
                    var selectedProp = this.value;
                    var opts = roomSelect.querySelectorAll('option');
                    opts.forEach(function (opt) {
                        if (!opt.value) return;
                        var propId = opt.getAttribute('data-property-id');
                        if (!selectedProp || propId === selectedProp) {
                            opt.style.display = '';
                        } else {
                            opt.style.display = 'none';
                        }
                    });
                    roomSelect.value = '';
                });
            }
        });
    </script>
@endpush


@endsection