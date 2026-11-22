
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
    <div class="alert alert-dismissible fade show d-flex align-items-center gap-3 p-3 mb-4 shadow-sm border-0" 
         style="background: #ecfdf5; border-left: 5px solid #10b981 !important; border-radius: 14px; color: #065f46;" role="alert">
      <div class="d-flex align-items-center justify-content-center rounded-circle flex-shrink-0" style="width: 38px; height: 38px; background: #10b981; color: white; font-size: 18px;">
        <i class="bi bi-check-lg"></i>
      </div>
      <div class="flex-grow-1">
        <div class="fw-bold" style="font-size: 14px;">Thành công!</div>
        <div style="font-size: 13px; color: #047857;">{{ session('success') }}</div>
      </div>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger" style="border-radius: 14px;"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
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
                <button type="button" class="btn-icon success btn-start-repair" 
                        data-ticket-id="{{ $ticket->id }}"
                        data-ticket-title="{{ $ticket->title }}"
                        data-ticket-room="{{ $ticket->room->name ?? '—' }}"
                        data-ticket-tenant="{{ $ticket->tenant->name ?? '—' }}"
                        data-action="{{ route('owner.maintenance.status', $ticket->id) }}"
                        data-bs-toggle="tooltip" title="Bắt đầu sửa"><i class="bi bi-tools"></i></button>
              @elseif($ticket->status === 'processing')
                <a href="{{ route('owner.maintenance.show', $ticket->id) }}" class="btn-icon success" data-bs-toggle="tooltip" title="Hoàn thành & Phân loại chi phí"><i class="bi bi-check-lg"></i></a>
              @endif
              @if(in_array($ticket->status, ['pending', 'processing']))
                <button type="button" class="btn-icon danger btn-reject-repair" 
                        data-ticket-id="{{ $ticket->id }}"
                        data-ticket-title="{{ $ticket->title }}"
                        data-action="{{ route('owner.maintenance.status', $ticket->id) }}"
                        data-bs-toggle="tooltip" title="Từ chối"><i class="bi bi-x-lg"></i></button>
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

<!-- MODAL XÁC NHẬN BẮT ĐẦU SỬA CHỮA (MODERN) -->
<div class="modal fade" id="startRepairModal" tabindex="-1" aria-labelledby="startRepairModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 440px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-body p-4 text-center">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
             style="width: 60px; height: 60px; background: #ecfdf5; color: #059669; font-size: 26px; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);">
          <i class="bi bi-tools"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" id="startRepairModalLabel" style="font-size: 18px;">Bắt đầu sửa chữa</h5>
        <p class="text-muted small mb-3" style="line-height: 1.5;">
          Bạn chuẩn bị tiếp nhận sự cố này. Hệ thống sẽ chuyển trạng thái sang <span class="badge bg-primary-subtle text-primary fw-bold">Đang sửa</span> và tự động gửi thông báo đến khách thuê.
        </p>

        <div class="p-3 mb-3 text-start rounded-3 border" style="background: #f8fafc; font-size: 13px;">
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Mã yêu cầu:</span>
            <span class="fw-bold text-dark" id="modalTicketCode">#TCK-—</span>
          </div>
          <div class="d-flex justify-content-between mb-1">
            <span class="text-muted">Sự cố:</span>
            <span class="fw-bold text-dark text-truncate" style="max-width: 220px;" id="modalTicketTitle">—</span>
          </div>
          <div class="d-flex justify-content-between">
            <span class="text-muted">Phòng / Khách:</span>
            <span class="fw-semibold text-secondary text-truncate" style="max-width: 220px;" id="modalTicketRoom">—</span>
          </div>
        </div>

        <form id="startRepairForm" method="POST" action="">
          @csrf
          @method('PUT')
          <input type="hidden" name="status" value="processing">
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-light flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal" style="border-radius: 12px; font-size: 13.5px;">
              Để sau
            </button>
            <button type="submit" class="btn flex-grow-1 py-2 fw-bold text-white" style="background: #059669; border-radius: 12px; font-size: 13.5px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.3);">
              Bắt đầu sửa ngay
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL XÁC NHẬN TỪ CHỐI (MODERN) -->
<div class="modal fade" id="rejectRepairModal" tabindex="-1" aria-labelledby="rejectRepairModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width: 420px;">
    <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
      <div class="modal-body p-4 text-center">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center rounded-circle" 
             style="width: 60px; height: 60px; background: #fef2f2; color: #dc2626; font-size: 26px;">
          <i class="bi bi-x-circle"></i>
        </div>
        <h5 class="fw-bold text-dark mb-2" id="rejectRepairModalLabel" style="font-size: 18px;">Từ chối yêu cầu sửa chữa</h5>
        <p class="text-muted small mb-3">
          Bạn có chắc chắn muốn từ chối yêu cầu này? Trạng thái sẽ được đổi thành <span class="badge bg-danger-subtle text-danger fw-bold">Đã từ chối</span>.
        </p>
        <form id="rejectRepairForm" method="POST" action="">
          @csrf
          @method('PUT')
          <input type="hidden" name="status" value="rejected">
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-light flex-grow-1 py-2 fw-semibold" data-bs-dismiss="modal" style="border-radius: 12px; font-size: 13.5px;">
              Hủy bỏ
            </button>
            <button type="submit" class="btn btn-danger flex-grow-1 py-2 fw-bold" style="border-radius: 12px; font-size: 13.5px;">
              Xác nhận từ chối
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



@push('scripts')
    <script src="{{ asset('js/tv4/maintenance/index.js') }}"></script>
@endpush


@endsection
