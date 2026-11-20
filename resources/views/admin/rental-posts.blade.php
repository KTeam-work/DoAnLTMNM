
@extends('Layout.admin')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv1/rental-posts.css') }}">
@endpush

@section('content')
@php
    $query = trim(request('q', ''));
    $status = request('status', '');

    $posts = \App\Models\RentalPost::with('room')
        ->when($query !== '', function ($builder) use ($query) {
            $builder->where(function ($sub) use ($query) {
                $sub->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhereHas('room', function ($roomQuery) use ($query) {
                        $roomQuery->where('name', 'like', "%{$query}%")
                            ->orWhere('room_code', 'like', "%{$query}%");
                    });
            });
        })
        ->when($status !== '', fn ($builder) => $builder->where('status', $status))
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $statusLabels = [
        'draft' => 'Bản nháp',
        'pending' => 'Chờ duyệt',
        'published' => 'Đã đăng',
        'rejected' => 'Từ chối',
        'hidden' => 'Đang ẩn',
    ];
@endphp

<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý tin đăng</h2>
        <div class="page-desc">Kiểm tra và quản lý tin cho thuê trên Trọ Ơi.</div>
    </div>
</div>

{{-- BỘ LỌC DẠNG TAB PHÂN ĐOẠN --}}
@php
    $totalPosts = \App\Models\RentalPost::count();
    $pendingPosts = \App\Models\RentalPost::where('status', 'pending')->count();
    $publishedPosts = \App\Models\RentalPost::where('status', 'published')->count();
    $rejectedPosts = \App\Models\RentalPost::where('status', 'rejected')->count();
    $hiddenPosts = \App\Models\RentalPost::where('status', 'hidden')->count();
@endphp

<div class="segmented-filter-wrapper mb-4">
    <div class="segmented-filter-bar" role="tablist">
        <a href="{{ route('admin.rental-posts') }}" class="segmented-tab {{ $status === '' ? 'active' : '' }}">
            <span class="tab-label">Tất cả</span>
            <span class="tab-count">{{ $totalPosts }}</span>
        </a>
        <a href="{{ route('admin.rental-posts', ['status' => 'pending']) }}" class="segmented-tab {{ $status === 'pending' ? 'active' : '' }}">
            <span class="tab-label">Chờ duyệt</span>
            <span class="tab-count">{{ $pendingPosts }}</span>
        </a>
        <a href="{{ route('admin.rental-posts', ['status' => 'published']) }}" class="segmented-tab {{ $status === 'published' ? 'active' : '' }}">
            <span class="tab-label">Đang hiển thị</span>
            <span class="tab-count">{{ $publishedPosts }}</span>
        </a>
        <a href="{{ route('admin.rental-posts', ['status' => 'rejected']) }}" class="segmented-tab {{ $status === 'rejected' ? 'active' : '' }}">
            <span class="tab-label">Bị từ chối</span>
            <span class="tab-count">{{ $rejectedPosts }}</span>
        </a>
        <a href="{{ route('admin.rental-posts', ['status' => 'hidden']) }}" class="segmented-tab {{ $status === 'hidden' ? 'active' : '' }}">
            <span class="tab-label">Đang ẩn</span>
            <span class="tab-count">{{ $hiddenPosts }}</span>
        </a>
    </div>
</div>

<div class="rp-panel">
    <form method="GET" action="{{ route('admin.rental-posts') }}" class="rp-filters">
        <input
            type="search"
            name="q"
            value="{{ $query }}"
            placeholder="Tìm tên tin hoặc phòng..."
            aria-label="Tìm tin đăng"
        >

        <select name="status" aria-label="Lọc trạng thái">
            <option value="">Tất cả trạng thái</option>
            @foreach ($statusLabels as $key => $label)
                <option value="{{ $key }}" @selected($status === $key)>
                    {{ $label }}
                </option>
            @endforeach
        </select>

        <button type="submit" class="rp-btn rp-btn-primary">Lọc tin</button>
        <a href="{{ route('admin.rental-posts') }}" class="rp-btn rp-btn-light">Đặt lại</a>
    </form>

    @if (session('success'))
        <div class="rp-alert success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="rp-alert error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="table-responsive">
        <table class="rp-table">
            <thead>
                <tr>
                    <th>Tin đăng</th>
                    <th>Phòng</th>
                    <th>Giá thuê</th>
                    <th>Ngày tạo</th>
                    <th>Trạng thái</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($posts as $post)
                    <tr>
                        <td>
                            <strong>{{ $post->title }}</strong>
                            <div class="rp-description">
                                {{ \Illuminate\Support\Str::limit($post->description, 100) }}
                            </div>
                            @if ($post->rejection_reason)
                                <div class="rp-reason">
                                    Lý do từ chối: {{ $post->rejection_reason }}
                                </div>
                            @endif
                        </td>
                        <td>
                            {{ $post->room?->name ?? 'Phòng không còn tồn tại' }}
                            @if ($post->room?->room_code)
                                <div class="rp-description">{{ $post->room->room_code }}</div>
                            @endif
                        </td>
                        <td>
                            @if ($post->room)
                                {{ number_format((float) $post->room->price, 0, ',', '.') }} đ
                                <div class="rp-description">
                                    {{ number_format((float) $post->room->area, 2, ',', '.') }} m²
                                </div>
                            @else
                                —
                            @endif
                        </td>
                        <td>{{ $post->created_at?->format('d/m/Y') ?? '—' }}</td>
                        <td>
                            <span class="rp-status status-{{ $post->status }}">
                                {{ $statusLabels[$post->status] ?? $post->status }}
                            </span>
                        </td>
                        <td>
                            <details class="rp-actions">
                                <summary>Quản lý</summary>
                                <form method="POST"
                                      action="{{ route('admin.rental-posts.update-status', $post->id) }}"
                                      class="rp-action-form"
                                      data-confirm-status>
                                    @csrf
                                    @method('PATCH')

                                    <label for="status-{{ $post->id }}">Trạng thái mới</label>
                                    <select name="status"
                                            id="status-{{ $post->id }}"
                                            required>
                                        @foreach ($statusLabels as $key => $label)
                                            <option value="{{ $key }}"
                                                @selected($post->status === $key)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label for="reason-{{ $post->id }}">
                                        Lý do từ chối (nếu có)
                                    </label>
                                    <textarea name="rejection_reason"
                                              id="reason-{{ $post->id }}"
                                              rows="2"
                                              maxlength="500"
                                              placeholder="Nhập lý do nếu từ chối">{{ $post->rejection_reason }}</textarea>

                                    <button type="submit" class="rp-btn rp-btn-primary">
                                        Lưu trạng thái
                                    </button>
                                </form>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="rp-empty">Không tìm thấy tin đăng phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="rp-pagination">{{ $posts->links() }}</div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/rental-posts.js') }}" defer></script>
@endpush
