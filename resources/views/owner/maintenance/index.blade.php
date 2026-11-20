
@extends('Layout.landlord')

@section('title', 'Quản lý sửa chữa | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/maintenance/index.css') }}">
@endpush

@section('content')



<div class="page-wrap pt-4 pb-5">
  <!-- Page Header -->
  <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-3 animate-up delay-1">
    <div>
      <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Quản lý sửa chữa</span></h2>
      <div class="page-desc mt-1 skeleton-box"><span class="hide-on-skeleton">Theo dõi và xử lý các yêu cầu sửa chữa từ khách thuê.</span></div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <!-- ALERT STRIP -->
  <div class="alert-strip mb-4 skeleton-box animate-up delay-2 {{ $counts['urgent'] > 0 ? '' : 'calm' }}">
    <div class="hide-on-skeleton d-flex align-items-center gap-3 flex-wrap w-100">
      <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
      <div class="alert-text">
        <div class="alert-headline">{{ $counts['urgent'] > 0 ? $counts['urgent'] . ' yêu cầu khẩn cấp đang chờ xử lý' : 'Không có yêu cầu khẩn cấp nào đang chờ' }}</div>
        <div class="alert-sub">Có {{ $counts['pending'] }} yêu cầu đang chờ xử lý</div>
      </div>
      <div class="alert-breakdown">
        <div class="figure"><div class="n mono-num">{{ $counts['total'] }}</div><div class="l">Tổng yêu cầu</div></div>
        <div class="figure"><div class="n mono-num">{{ $counts['pending'] }}</div><div class="l">Chờ xử lý</div></div>
        <div class="figure"><div class="n mono-num">{{ $counts['processing'] }}</div><div class="l">Đang sửa</div></div>
        <div class="figure"><div class="n mono-num">{{ $counts['completed'] }}</div><div class="l">Hoàn thành</div></div>
      </div>
    </div>
  </div>

  <!-- FILTER BAR -->
  <div class="filter-bar mb-3 hide-on-skeleton">
    <a href="{{ route('owner.maintenance.index') }}" class="chip-filter {{ $statusFilter === 'all' ? 'active' : '' }}" style="text-decoration: none;">Tất cả</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'pending']) }}" class="chip-filter {{ $statusFilter === 'pending' ? 'active' : '' }}" style="text-decoration: none;">Chờ xử lý</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'processing']) }}" class="chip-filter {{ $statusFilter === 'processing' ? 'active' : '' }}" style="text-decoration: none;">Đang sửa</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'completed']) }}" class="chip-filter {{ $statusFilter === 'completed' ? 'active' : '' }}" style="text-decoration: none;">Hoàn thành</a>
    <a href="{{ route('owner.maintenance.index', ['status' => 'rejected']) }}" class="chip-filter {{ $statusFilter === 'rejected' ? 'active' : '' }}" style="text-decoration: none;">Đã từ chối</a>
  </div>

  <!-- BOARD -->
  <div class="board hide-on-skeleton animate-up delay-3">
    @php
      $priLabels = ['low' => 'Thấp', 'medium' => 'Trung bình', 'high' => 'Khẩn cấp', 'urgent' => 'Khẩn cấp'];
      $grouped = $requests->getCollection()->groupBy('status');
    @endphp
    @foreach([['pending', 'Chờ xử lý', 'pending'], ['processing', 'Đang sửa', 'in-progress'], ['completed', 'Hoàn thành', 'resolved'], ['rejected', 'Đã từ chối', 'resolved']] as [$status, $title, $colClass])
    <div class="board-col {{ $colClass }}">
      <div class="board-col-head">
        <span class="title">{{ $title }}</span>
        <span class="count">{{ ($grouped[$status] ?? collect())->count() }}</span>
      </div>
      <div class="board-col-body">
        @forelse($grouped[$status] ?? [] as $ticket)
        <div class="ticket-card pri-{{ in_array($ticket->priority, ['high', 'urgent']) ? 'high' : ($ticket->priority === 'medium' ? 'medium' : 'low') }}">
          <div class="ticket-top">
            <span class="ticket-id">#TCK-{{ $ticket->id }}</span>
            <span class="ticket-pri">{{ $priLabels[$ticket->priority] ?? $ticket->priority }}</span>
          </div>
          <div class="ticket-title">{{ $ticket->title }}</div>
          <div class="ticket-room">{{ $ticket->room->name ?? '' }} · {{ $ticket->tenant->name ?? '' }}</div>
          
          @if($ticket->status === 'completed')
            <div class="mt-2 pt-2 border-top d-flex align-items-center justify-content-between flex-wrap gap-1">
              @if($ticket->damage_type === 'tenant')
                <span class="badge bg-danger-subtle text-danger border border-danger-subtle" style="font-size: 11px;">
                  Khách chịu: {{ number_format($ticket->cost) }}đ
                </span>
                @if($ticket->invoice_id)
                  <a href="{{ route('owner.invoices.show', $ticket->invoice_id) }}" class="badge bg-light text-primary border text-decoration-none" style="font-size: 10.5px;">
                    <i class="bi bi-receipt"></i> Hóa đơn #{{ $ticket->invoice_id }}
                  </a>
                @endif
              @elseif($ticket->damage_type === 'landlord')
                <span class="badge bg-success-subtle text-success border border-success-subtle" style="font-size: 11px;">
                  Chủ trọ chi trả (0đ)
                </span>
              @else
                <a href="{{ route('owner.maintenance.show', $ticket->id) }}" class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle text-decoration-none" style="font-size: 11px;" title="Bấm vào để phân loại và tạo hóa đơn">
                  Chưa phân loại chi phí
                </a>
              @endif
            </div>
          @endif

          <div class="ticket-bottom mt-2">
            <span class="ticket-date">{{ $ticket->created_at->format('d/m') }}</span>
            <div class="ticket-actions">
              <a href="{{ route('owner.maintenance.show', $ticket->id) }}" class="btn-icon" data-bs-toggle="tooltip" title="Xem chi tiết & Cập nhật"><i class="bi bi-eye-fill"></i></a>
              @if($ticket->status === 'pending')
                <form action="{{ route('owner.maintenance.status', $ticket->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="processing">
                  <button type="submit" class="btn-icon success" data-bs-toggle="tooltip" title="Bắt đầu sửa" onclick="return confirm('Bắt đầu sửa yêu cầu này?')"><i class="bi bi-tools"></i></button>
                </form>
              @elseif($ticket->status === 'processing')
                <a href="{{ route('owner.maintenance.show', $ticket->id) }}" class="btn-icon success" data-bs-toggle="tooltip" title="Hoàn thành & Phân loại chi phí"><i class="bi bi-check-lg"></i></a>
              @endif
              @if(in_array($ticket->status, ['pending', 'processing']))
                <form action="{{ route('owner.maintenance.status', $ticket->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('PUT')
                  <input type="hidden" name="status" value="rejected">
                  <button type="submit" class="btn-icon danger" data-bs-toggle="tooltip" title="Từ chối" onclick="return confirm('Từ chối yêu cầu này?')"><i class="bi bi-x-lg"></i></button>
                </form>
              @endif
            </div>
          </div>
        </div>
        @empty
        <div class="board-empty">Không có yêu cầu nào.</div>
        @endforelse
      </div>
    </div>
    @endforeach
  </div>
  <div class="mt-3">{{ $requests->links() }}</div>

</div>



@push('scripts')
    <script src="{{ asset('js/tv4/maintenance/index.js') }}"></script>
@endpush


@endsection
