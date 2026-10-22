@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/notificaitons/index.css') }}">
@endpush

@section('content')

<div class="page-wrap" style="padding-top: 40px; max-width: 850px; margin: 0 auto;">
  
  <div class="mb-4 animate-up delay-1">
    <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Thông báo của bạn</span></h2>
    <div class="text-muted mt-2 skeleton-box"><span class="hide-on-skeleton">Cập nhật nhanh các thông tin quan trọng từ Ban quản lý và Chủ nhà.</span></div>
  </div>

  <!-- Thông báo thành công sẽ hiện ở đây khi búng về -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3 animate-up delay-1" role="alert" style="background-color: #d1e7dd; color: #0f5132; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <strong>✓ Thành công!</strong> {{ session('success') }}
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="filter-tabs animate-up delay-2 skeleton-box rounded-pill border-0 p-0 mb-4">
    <a href="{{ route('tenant.notifications.index') }}" class="filter-tab hide-on-skeleton {{ $filter === 'all' ? 'active' : '' }}">Tất cả</a>
    <a href="{{ route('tenant.notifications.index', ['filter' => 'unread']) }}" class="filter-tab hide-on-skeleton {{ $filter === 'unread' ? 'active' : '' }}">Chưa đọc ({{ $unreadCount }})</a>
    <a href="{{ route('tenant.notifications.index', ['filter' => 'read']) }}" class="filter-tab hide-on-skeleton {{ $filter === 'read' ? 'active' : '' }}">Đã đọc</a>
  </div>

  <div class="notify-panel animate-up delay-3 skeleton-box">
    <div class="hide-on-skeleton">

      <div class="notify-header">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Mới nhất</h5>

        <form action="{{ route('tenant.notifications.mark_read') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="mark-read-btn" style="background: transparent; border: none; padding: 0;">
            ✓ Đánh dấu tất cả đã đọc
          </button>
        </form>
      </div>

      @php
        $typeIcons = ['invoice' => ['icon-invoice', '💰'], 'maintenance' => ['icon-maintenance', '🔧'], 'contract' => ['icon-contract', '📜'], 'payment' => ['icon-invoice', '💳'], 'review' => ['icon-contract', '⭐']];
      @endphp
      @forelse($notifications as $notification)
        @php
          [$iconClass, $icon] = $typeIcons[$notification->type] ?? ['icon-contract', '🔔'];
          $target = $notification->targetUrl();
        @endphp
        <div class="notify-item {{ $notification->read_at ? '' : 'unread' }}">
          <div class="notify-icon {{ $iconClass }}">{{ $icon }}</div>
          <div class="flex-grow-1">
            @if($target)
              <a href="{{ $target }}" class="fw-bold text-dark text-decoration-none" style="font-size: 15px;">{{ $notification->title }}</a>
            @else
              <div class="fw-bold text-dark" style="font-size: 15px;">{{ $notification->title }}</div>
            @endif
            <div class="text-muted mt-1" style="font-size: 14px; line-height: 1.5;">{{ $notification->message }}</div>
            <div class="notify-time">{{ $notification->created_at->diffForHumans() }} · {{ $notification->created_at->format('d/m/Y H:i') }}</div>
          </div>
          @if($notification->read_at)
            <span class="text-muted" style="font-size: 12px;">Đã đọc</span>
          @else
            <div class="d-flex flex-column align-items-end gap-2">
              <div class="notify-dot"></div>
              <form action="{{ route('tenant.notifications.read', $notification->id) }}" method="POST" class="m-0">
                @csrf
                @method('PUT')
                <button type="submit" class="mark-read-btn" style="background: transparent; border: none; padding: 0;">Đánh dấu đã đọc</button>
              </form>
            </div>
          @endif
        </div>
      @empty
        <div class="text-center text-muted py-5">Bạn chưa có thông báo nào.</div>
      @endforelse

    </div>
  </div>
  <div class="mt-3">{{ $notifications->links() }}</div>

</div>


@push('scripts')
    <script src="{{ asset('js/tv3/tenant/notificaiton/index.js') }}"></script>
@endpush


@endsection