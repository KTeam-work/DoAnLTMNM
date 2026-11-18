@extends('Layout.landlord')

@section('title', 'Thông báo của chủ trọ | Trọ Ơi')

@push('styles')
<style>
    .notif-container {
        max-width: 900px;
        margin: 30px auto 60px auto;
        padding: 0 16px;
    }

    .notif-heading {
        margin-bottom: 24px;
    }

    .notif-heading h1 {
        font-size: 26px;
        font-weight: 800;
        color: #1a202c;
        margin-bottom: 6px;
    }

    .notif-heading p {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 0;
    }

    .filter-tabs {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
        overflow-x: auto;
        padding-bottom: 4px;
    }

    .filter-tab {
        padding: 8px 18px;
        border-radius: 99px;
        font-size: 13px;
        font-weight: 600;
        color: #475569;
        background: #fff;
        border: 1px solid #e2e8f0;
        text-decoration: none;
        transition: all 0.2s ease;
        white-space: nowrap;
    }

    .filter-tab:hover,
    .filter-tab.active {
        background: #198754;
        color: #fff;
        border-color: #198754;
    }

    .notify-panel {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #edf2f7;
        overflow: hidden;
    }

    .notify-header {
        padding: 18px 24px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }

    .notify-item {
        padding: 20px 24px;
        border-bottom: 1px solid #f8fafc;
        display: flex;
        gap: 18px;
        transition: background 0.2s ease;
        align-items: flex-start;
    }

    .notify-item:last-child {
        border-bottom: none;
    }

    .notify-item:hover {
        background: #f8fafc;
    }

    .notify-item.unread {
        background: #f0fdf4;
        position: relative;
    }

    .notify-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #198754;
    }

    .notify-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .icon-warning { background: #fffbeb; color: #b45309; }
    .icon-danger  { background: #fef2f2; color: #dc2626; }
    .icon-success { background: #ecfdf5; color: #059669; }
    .icon-info    { background: #eff6ff; color: #2563eb; }
    .icon-invoice { background: #fefce8; color: #ca8a04; }
    .icon-maintenance { background: #f0f9ff; color: #0284c7; }
    .icon-contract { background: #f3f4f6; color: #4b5563; }
    .icon-general { background: #f1f5f9; color: #334155; }

    .notify-time {
        font-size: 12px;
        color: #94a3b8;
        margin-top: 6px;
        font-weight: 500;
    }

    .mark-read-btn {
        font-size: 13px;
        font-weight: 600;
        color: #198754;
        cursor: pointer;
        text-decoration: none;
        background: none;
        border: none;
        padding: 0;
    }

    .mark-read-btn:hover {
        color: #0f5132;
        text-decoration: underline;
    }

    .unread-badge-dot {
        width: 8px;
        height: 8px;
        background: #198754;
        border-radius: 50%;
        display: inline-block;
    }
</style>
@endpush

@section('content')
<div class="notif-container">

    <div class="notif-heading">
        <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 11px;">Kênh chủ trọ</span>
        </div>
        <h1>🔔 Thông báo từ hệ thống</h1>
        <p>Cập nhật trạng thái tin đăng, cảnh cáo vi phạm, thanh toán và các phản ánh liên quan tới phòng trọ của bạn.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 rounded-3 shadow-sm mb-4" role="alert">
            <strong>✓ Thành công!</strong> {{ session('success') }}
            <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="filter-tabs">
        <a href="{{ route('owner.notifications.index') }}" class="filter-tab {{ $filter === 'all' ? 'active' : '' }}">
            Tất cả
        </a>
        <a href="{{ route('owner.notifications.index', ['filter' => 'unread']) }}" class="filter-tab {{ $filter === 'unread' ? 'active' : '' }}">
            Chưa đọc ({{ $unreadCount }})
        </a>
        <a href="{{ route('owner.notifications.index', ['filter' => 'read']) }}" class="filter-tab {{ $filter === 'read' ? 'active' : '' }}">
            Đã đọc
        </a>
    </div>

    <div class="notify-panel">
        <div class="notify-header">
            <h5 class="fw-bold mb-0 text-dark" style="font-size: 15px;">Danh sách thông báo</h5>

            @if($unreadCount > 0)
                <form action="{{ route('owner.notifications.mark_read') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="mark-read-btn">
                        ✓ Đánh dấu tất cả đã đọc
                    </button>
                </form>
            @endif
        </div>

        @php
            $typeIcons = [
                'warning' => ['icon-warning', '⚠️'],
                'danger' => ['icon-danger', '🚫'],
                'success' => ['icon-success', '✅'],
                'info' => ['icon-info', 'ℹ️'],
                'invoice' => ['icon-invoice', '💰'],
                'payment' => ['icon-invoice', '💳'],
                'maintenance' => ['icon-maintenance', '🔧'],
                'review' => ['icon-contract', '⭐'],
                'contract' => ['icon-contract', '📜'],
                'general' => ['icon-general', '🔔'],
            ];
        @endphp

        @forelse($notifications as $notification)
            @php
                [$iconClass, $icon] = $typeIcons[$notification->type] ?? ['icon-general', '🔔'];
                $target = $notification->targetUrl();
            @endphp
            <div class="notify-item {{ $notification->read_at ? '' : 'unread' }}">
                <div class="notify-icon {{ $iconClass }}">{{ $icon }}</div>

                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-2">
                        @if($target)
                            <a href="{{ $target }}" class="fw-bold text-dark text-decoration-none" style="font-size: 15px;">
                                {{ $notification->title }}
                            </a>
                        @else
                            <div class="fw-bold text-dark" style="font-size: 15px;">{{ $notification->title }}</div>
                        @endif

                        @if(!$notification->read_at)
                            <span class="badge bg-danger rounded-pill" style="font-size: 10px; padding: 2px 6px;">Mới</span>
                        @endif
                    </div>

                    <div class="text-secondary mt-1" style="font-size: 14px; line-height: 1.5;">
                        {{ $notification->message }}
                    </div>

                    <div class="notify-time">
                        {{ $notification->created_at->diffForHumans() }} · {{ $notification->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>

                <div class="d-flex flex-column align-items-end justify-content-between gap-2" style="min-width: 110px;">
                    @if($notification->read_at)
                        <span class="text-muted" style="font-size: 12px;">Đã đọc</span>
                    @else
                        <form action="{{ route('owner.notifications.read', $notification->id) }}" method="POST" class="m-0">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="mark-read-btn">
                                Đánh dấu đã đọc
                            </button>
                        </form>
                    @endif

                    @if($target)
                        <a href="{{ $target }}" class="text-primary text-decoration-none fw-semibold" style="font-size: 12px;">
                            Xem chi tiết &rarr;
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-5">
                <div style="font-size: 40px;" class="mb-2">📭</div>
                <div>Bạn không có thông báo nào.</div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>

</div>
@endsection

