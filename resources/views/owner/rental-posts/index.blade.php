@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/index.css') }}">
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

        <a href="{{ route('owner.rental-posts.create') }}" class="btn-owner">
            <span>＋</span> Tạo tin đăng
        </a>
    </div>

    <section class="post-stats">
        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">TỔNG TIN ĐĂNG</span>
                <div class="post-stat-icon">📢</div>
            </div>
            <strong class="stat-number">{{ $posts->count() }}</strong>
            <span class="stat-label">Tin đăng thuộc các phòng của em</span>
        </div>

        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">ĐANG HIỂN THỊ</span>
                <div class="post-stat-icon">🟢</div>
            </div>
            <strong class="stat-number">{{ $posts->where('status', 'published')->count() }}</strong>
            <span class="stat-label">Tin đã được đăng</span>
        </div>

        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">CHỜ DUYỆT</span>
                <div class="post-stat-icon">⏳</div>
            </div>
            <strong class="stat-number">{{ $posts->where('status', 'pending')->count() }}</strong>
            <span class="stat-label">Tin đang chờ xử lý</span>
        </div>

        <div class="post-stat">
            <div class="stat-top">
                <span class="stat-label">BẢN NHÁP</span>
                <div class="post-stat-icon">📝</div>
            </div>
            <strong class="stat-number">{{ $posts->where('status', 'draft')->count() }}</strong>
            <span class="stat-label">Tin chưa gửi duyệt</span>
        </div>
    </section>

    <section class="posts-container">
        <div class="list-heading">
            <h2>Danh sách tin đăng</h2>
            <p>Thông tin được lấy từ dữ liệu tin đăng và phòng trọ.</p>
        </div>

        <div class="toolbar">
            <div class="search-wrap">
                <span>🔎</span>
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
                        <div class="image-placeholder">🏠</div>
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
                            <a href="{{ route('owner.rental-posts.show', $post) }}"
                               class="btn-view">Xem</a>

                            <a href="{{ route('owner.rental-posts.edit', $post) }}"
                               class="btn-edit">Sửa</a>

                            <form action="{{ route('owner.rental-posts.destroy', $post) }}"
                                  method="POST"
                                  onsubmit="return confirm('Em có chắc muốn xóa tin đăng này không?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete">Xóa</button>
                            </form>
                        </div>
                    </div>
                </article>
            @empty
                <div class="empty-state">
                    <div class="empty-icon">📢</div>
                    <h3>Chưa có tin đăng nào</h3>
                    <p>Tạo tin đăng cho phòng trọ để bắt đầu quản lý tại đây.</p>
                    <a href="{{ route('owner.rental-posts.create') }}" class="btn-owner">
                        ＋ Tạo tin đăng đầu tiên
                    </a>
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

@push('scripts')
    <script src="{{ asset('js/tv3/rental_protal/index.js') }}"></script>
@endpush


@endsection