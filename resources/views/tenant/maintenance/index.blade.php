
@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/maintenance/index.css') }}">
@endpush

@section('content')


<div class="page-wrap" style="padding-top: 30px; max-width: 900px;">
  
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" role="alert" style="background-color: #d1e7dd; color: #0f5132; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <strong>Đã tiếp nhận!</strong> {{ session('success') }}
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4 animate-up delay-1">
    <div>
      <h2 class="page-title mb-1 fw-bold text-dark" style="font-size: 26px;">Yêu cầu sửa chữa</h2>
      <div class="text-muted" style="font-size: 14px;">Quản lý và theo dõi tiến độ khắc phục sự cố tại phòng của bạn.</div>
    </div>
    <div>
      <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-success px-4 py-2 rounded-pill fw-medium shadow-sm text-decoration-none">
        + Gửi yêu cầu
      </a>
    </div>
  </div>

  @php
    $categoryLabels = [
        'dien_nuoc' => 'Điện / Nước',
        'dien_lanh' => 'Thiết bị điện lạnh',
        'noi_that' => 'Nội thất',
        'khac' => 'Vấn đề khác'
    ];
    $statusLabels = [
        'pending' => 'Chờ xác nhận',
        'processing' => 'Đang xử lý',
        'completed' => 'Hoàn thành',
        'rejected' => 'Đã từ chối'
    ];
  @endphp

  <!-- Filter Tabs -->
  <div class="filter-tabs animate-up delay-2 skeleton-box rounded-pill border-0 p-0 mb-4">
    <a href="{{ route('tenant.maintenance.index') }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'all' ? 'active' : '' }}">Tất cả ({{ $requests->count() }})</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'pending']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'pending' ? 'active' : '' }}">Chờ xử lý</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'processing']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'processing' ? 'active' : '' }}">Đang xử lý</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'completed']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'completed' ? 'active' : '' }}">Hoàn thành</a>
  </div>

  <!-- Ticket List -->
  <div class="animate-up delay-2">

    @forelse($requests as $ticket)
    @php
        $isUrgent = in_array($ticket->priority, ['high', 'urgent']);
    @endphp
    <div class="ticket-card {{ $isUrgent ? 'priority-urgent' : 'priority-normal' }} skeleton-box" style="padding: 18px 22px;">
      <div class="flex-grow-1 hide-on-skeleton d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            @if($isUrgent)
              <span class="badge bg-danger bg-opacity-10 text-danger border border-danger-subtle rounded-pill px-2 py-1" style="font-size: 11px; font-weight: 700;">Khẩn cấp</span>
            @endif
            <span class="text-muted" style="font-size: 12px; font-weight: 500;">{{ $ticket->created_at->format('H:i - d/m/Y') }}</span>
          </div>
          <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">{{ $ticket->title }}</h5>
          <p class="text-muted mb-0" style="font-size: 13px;">Phòng: <strong>{{ $ticket->room->name ?? '—' }}</strong> · {{ $categoryLabels[$ticket->category] ?? 'Sự cố' }}</p>
        </div>
        <div class="text-end d-flex flex-column align-items-end">
          <span class="badge-status {{ $ticket->status }} px-3 py-1 rounded-pill mb-2" style="font-size: 12px; font-weight: 600;">{{ $statusLabels[$ticket->status] ?? $ticket->status }}</span>
          <a href="{{ route('tenant.maintenance.show', $ticket->id) }}" class="text-decoration-none fw-bold" style="font-size: 13px; color: #16834b;">Xem chi tiết →</a>
        </div>
      </div>
    </div>
    @empty
    <div class="text-center text-muted py-5 bg-white rounded-4 border">
      <div class="fw-bold fs-5 text-dark mb-1">Chưa có yêu cầu sửa chữa nào</div>
      <p class="text-muted small mb-3">Khi phòng gặp sự cố về điện, nước hoặc nội thất, bạn có thể gửi yêu cầu tại đây.</p>
      <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-success px-4 py-2 rounded-pill fw-medium">+ Gửi yêu cầu mới</a>
    </div>
    @endforelse

  </div>
</div>


@push('scripts')
    <script src="{{ asset('js/tv3/tenant/maintencance/index.js') }}"></script>
@endpush


@endsection