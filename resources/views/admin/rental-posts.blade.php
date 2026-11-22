
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
                                      data-confirm-status
                                      data-post-id="{{ $post->id }}"
                                      data-post-title="{{ $post->title }}"
                                      data-room-name="{{ $post->room?->name ?? 'Phòng không còn tồn tại' }}"
                                      data-current-status="{{ $post->status }}"
                                      data-current-label="{{ $statusLabels[$post->status] ?? $post->status }}">
                                    @csrf
                                    @method('PATCH')

                                    <label for="status-{{ $post->id }}">Trạng thái mới</label>
                                    <select name="status"
                                            id="status-{{ $post->id }}"
                                            onchange="toggleRejectionReason(this)"
                                            required>
                                        @foreach ($statusLabels as $key => $label)
                                            @continue($key === 'draft')
                                            <option value="{{ $key }}"
                                                @selected($post->status === $key)>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <label for="reason-{{ $post->id }}"
                                           id="reason-label-{{ $post->id }}"
                                           style="{{ $post->status === 'rejected' ? '' : 'display: none;' }}">
                                        Lý do từ chối (bắt buộc)
                                    </label>
                                    <textarea name="rejection_reason"
                                              id="reason-{{ $post->id }}"
                                              rows="2"
                                              maxlength="500"
                                              style="{{ $post->status === 'rejected' ? '' : 'display: none;' }}"
                                              placeholder="Nhập lý do từ chối">{{ $post->rejection_reason }}</textarea>

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

{{-- MODAL THÔNG BÁO XÁC NHẬN CHUYỂN TRẠNG THÁI --}}
<div class="modal fade" id="statusConfirmModal" tabindex="-1" aria-labelledby="statusConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
            {{-- HEADER DARK NAVY --}}
            <div class="modal-header border-0 px-4 py-3" style="background: #0c2340; color: #ffffff;">
                <div class="d-flex align-items-center gap-2">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 32px; height: 32px; border-radius: 8px; background: rgba(255, 255, 255, 0.15); font-size: 15px;">
                        🔔
                    </span>
                    <h5 class="modal-title fw-bold text-white mb-0" id="statusConfirmModalLabel" style="font-size: 17px;">
                        Thông báo xác nhận
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="modal-body p-4">
                {{-- DIỄN TIẾN TRẠNG THÁI --}}
                <div class="d-flex align-items-center justify-content-between p-3 rounded-3 mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                    <div class="text-center flex-grow-1">
                        <div class="text-muted fw-semibold mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Trạng thái cũ</div>
                        <span id="modalCurrentStatusBadge" class="rp-status">Chờ duyệt</span>
                    </div>

                    <div class="px-2 text-muted fw-bold" style="font-size: 18px;">➔</div>

                    <div class="text-center flex-grow-1">
                        <div class="text-muted fw-semibold mb-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Trạng thái mới</div>
                        <span id="modalNewStatusBadge" class="rp-status">Đã đăng</span>
                    </div>
                </div>

                {{-- TÓM TẮT TIN ĐĂNG --}}
                <div class="p-3 rounded-3 mb-3" style="background: #fdfdfd; border: 1px solid #edf2f7;">
                    <div class="text-muted fw-semibold mb-1" style="font-size: 11.5px; text-transform: uppercase;">Tin đăng áp dụng</div>
                    <div class="fw-bold text-dark" id="modalPostTitle" style="font-size: 15px; color: #0c2340;">—</div>
                    <div class="small text-muted mt-1" id="modalPostRoom">Phòng: —</div>
                </div>

                {{-- CÂU XÁC NHẬN --}}
                <div class="mb-3" style="font-size: 14.5px; color: #334155; line-height: 1.6;">
                    Bạn có chắc chắn muốn chuyển tin đăng này sang trạng thái <strong id="modalTargetStatusName" class="text-dark">"Đã đăng"</strong>?
                </div>

                {{-- NHẬP LÝ DO TỪ CHỐI (KHI TRẠNG THÁI LÀ TỪ CHỐI) --}}
                <div id="modalRejectionReasonGroup" style="display: none;" class="mb-3">
                    <label for="modalRejectionReasonInput" class="form-label fw-bold text-danger" style="font-size: 13px;">
                        Lý do từ chối tin đăng <span class="text-danger">*</span>
                    </label>
                    <textarea id="modalRejectionReasonInput" class="form-control" rows="3" placeholder="Nhập lý do từ chối để thông báo cho chủ trọ..." style="border-radius: 10px; font-size: 13.5px;"></textarea>
                    <div id="modalRejectionError" class="text-danger small mt-1" style="display: none;">Vui lòng nhập lý do từ chối tin đăng.</div>
                </div>

                {{-- GHI CHÚ GIẢI THÍCH TRẠNG THÁI --}}
                <div id="modalStatusHint" class="p-2 px-3 rounded text-muted" style="background: #f1f5f9; font-size: 12.5px;">
                    <!-- Sẽ cập nhật theo trạng thái mới -->
                </div>
            </div>

            <div class="modal-footer px-4 py-3" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-light rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                    Hủy bỏ
                </button>
                <button type="button" id="btnModalConfirmStatus" class="btn text-white rounded-3 px-4 py-2 fw-bold" style="background: #0c2340; border-color: #0c2340;">
                    ✓ Đồng ý thay đổi
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/rental-posts.js') }}?v={{ file_exists(public_path('js/tv1/rental-posts.js')) ? filemtime(public_path('js/tv1/rental-posts.js')) : time() }}" defer></script>
@endpush
