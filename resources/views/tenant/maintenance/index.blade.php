
@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/maintenance/index.css') }}">
@endpush

@section('content')


<div class="page-wrap" style="padding-top: 30px; max-width: 1000px;">
  
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" role="alert" style="background-color: #d1e7dd; color: #0f5132; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <strong>✓ Đã tiếp nhận!</strong> {{ session('success') }}
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <!-- Header -->
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Trung tâm Hỗ trợ & Sửa chữa</span></h2>
      <div class="text-muted mt-2 skeleton-box"><span class="hide-on-skeleton">Quản lý và theo dõi tiến độ khắc phục sự cố tại phòng của bạn.</span></div>
    </div>
    <div class="skeleton-box rounded">
      <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-brand px-4 py-2 hide-on-skeleton shadow-sm">
        + Tạo Ticket Mới
      </a>
    </div>
  </div>

  <!-- Metrics Dashboard -->
  @php
    $urgentCount = $requests->whereIn('priority', ['high', 'urgent'])->whereIn('status', ['pending', 'processing'])->count();
    $processingCount = $requests->where('status', 'processing')->count();
    $doneCount = $requests->where('status', 'completed')->count();
    $categoryLabels = ['dien_nuoc' => 'Điện / Nước', 'dien_lanh' => 'Thiết bị điện lạnh', 'noi_that' => 'Nội thất', 'khac' => 'Vấn đề khác'];
    $statusLabels = ['pending' => '⏳ Đang chờ xác nhận', 'processing' => '🔧 Đang xử lý', 'completed' => '✅ Hoàn thành', 'rejected' => '✖ Đã từ chối'];
  @endphp
  <div class="row g-3 mb-4 animate-up delay-2">
    <div class="col-md-4">
      <div class="metric-card skeleton-box">
        <div class="metric-icon hide-on-skeleton" style="background: var(--red-soft); color: var(--red);">🚨</div>
        <div class="hide-on-skeleton">
          <div class="text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Cần xử lý gấp</div>
          <div style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ $urgentCount }} <span style="font-size: 13px; color: var(--muted); font-weight: 500;">yêu cầu</span></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card skeleton-box">
        <div class="metric-icon hide-on-skeleton" style="background: var(--blue-soft); color: var(--blue);">🔧</div>
        <div class="hide-on-skeleton">
          <div class="text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Đang xử lý</div>
          <div style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ $processingCount }} <span style="font-size: 13px; color: var(--muted); font-weight: 500;">yêu cầu</span></div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="metric-card skeleton-box">
        <div class="metric-icon hide-on-skeleton" style="background: var(--green-soft); color: var(--green);">✓</div>
        <div class="hide-on-skeleton">
          <div class="text-muted" style="font-size: 12px; font-weight: 700; text-transform: uppercase;">Đã hoàn thành</div>
          <div style="font-size: 22px; font-weight: 800; color: var(--green-dark);">{{ $doneCount }} <span style="font-size: 13px; color: var(--muted); font-weight: 500;">yêu cầu</span></div>
        </div>
      </div>
    </div>
  </div>

  <!-- Filter Tabs -->
  <div class="filter-tabs animate-up delay-3 skeleton-box rounded-pill border-0 p-0 mb-4">
    <a href="{{ route('tenant.maintenance.index') }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'all' ? 'active' : '' }}">Tất cả ({{ $requests->count() }})</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'pending']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'pending' ? 'active' : '' }}">Chờ xử lý</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'processing']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'processing' ? 'active' : '' }}">Đang xử lý</a>
    <a href="{{ route('tenant.maintenance.index', ['status' => 'completed']) }}" class="filter-tab hide-on-skeleton {{ $statusFilter === 'completed' ? 'active' : '' }}">Hoàn thành</a>
  </div>

  <!-- Ticket List -->
  <div class="animate-up delay-3">

    @forelse($requests as $ticket)
    <div class="ticket-card {{ in_array($ticket->priority, ['high', 'urgent']) ? 'priority-urgent' : 'priority-normal' }} skeleton-box">
      <div class="ticket-icon {{ $ticket->category === 'dien_lanh' ? 'dien-lanh' : ($ticket->category === 'noi_that' ? 'noi-that' : 'dien-nuoc') }} hide-on-skeleton">{{ $ticket->category === 'dien_lanh' ? '❄️' : ($ticket->category === 'noi_that' ? '🚪' : '💧') }}</div>
      <div class="flex-grow-1 hide-on-skeleton d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
          <div class="d-flex align-items-center gap-2 mb-1">
            <span class="badge {{ in_array($ticket->priority, ['high', 'urgent']) ? 'bg-danger text-white' : 'bg-secondary bg-opacity-10 text-secondary' }} rounded-1" style="font-size: 10px; letter-spacing: 0.5px;">{{ strtoupper($ticket->priority) }}</span>
            <span class="text-muted" style="font-size: 12px; font-weight: 600;">#TCK-{{ $ticket->id }} • {{ $ticket->created_at->format('H:i, d/m') }}</span>
          </div>
          <h5 class="fw-bold text-dark mb-1" style="font-size: 16px;">{{ $ticket->title }}</h5>
          <p class="text-muted mb-0" style="font-size: 13px;">Khu vực: {{ $categoryLabels[$ticket->category] ?? 'Khác' }} • Phòng {{ $ticket->room->name ?? '' }}{{ $ticket->contract ? ' • HĐ ' . $ticket->contract->contract_code : '' }}</p>
        </div>
        <div class="text-end d-flex flex-column align-items-end">
          <span class="badge-status {{ $ticket->status }} px-3 py-2 rounded-pill mb-2" style="font-size: 11.5px; font-weight: 700;">{{ $statusLabels[$ticket->status] ?? $ticket->status }}</span>
          <a href="{{ route('tenant.maintenance.show', $ticket->id) }}" class="text-decoration-none fw-bold" style="font-size: 13px; color: var(--green);">Xem chi tiết →</a>
        </div>
      </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">
      <div style="font-size: 40px;">🔧</div>
      <div class="fw-bold mt-2">Chưa có yêu cầu nào</div>
      <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-brand px-4 py-2 mt-3">+ Tạo yêu cầu mới</a>
    </div>
    @endforelse

  </div>
</div>


@push('scripts')
    <script src="{{ asset('js/tv3/tenant/maintencance/index.js') }}"></script>
@endpush


@endsection