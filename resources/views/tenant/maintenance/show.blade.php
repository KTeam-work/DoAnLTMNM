
@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/maintenance/show.css') }}">
@endpush

@section('content')


<div class="page-wrap" style="padding-top: 40px; max-width: 1000px;">

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
      <strong>✓ {{ session('success') }}</strong>
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  @php
    $categoryLabels = ['dien_nuoc' => '💧 Hệ thống Điện / Nước', 'dien_lanh' => '❄️ Thiết bị điện lạnh', 'noi_that' => '🚪 Nội thất', 'khac' => '🔧 Vấn đề khác'];
    $priorityLabels = ['low' => 'Thấp', 'medium' => 'Bình thường', 'high' => 'Cao', 'urgent' => 'Khẩn cấp'];
    $statusLabels = ['pending' => '⏳ Chờ xử lý', 'processing' => '🔧 Đang xử lý', 'completed' => '✅ Hoàn thành', 'rejected' => '✖ Đã từ chối'];
  @endphp

  <!-- Breadcrumb & Header -->
  <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <a href="{{ route('tenant.maintenance.index') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 13px;">← QUAY LẠI DANH SÁCH</a>
      <div class="d-flex align-items-center gap-3 mt-2 flex-wrap">
        <h2 class="page-title mb-0" style="font-size: 28px;">Chi tiết Ticket #TCK-{{ $maintenance->id }}</h2>
        <span class="badge-status {{ $maintenance->status }}">{{ $statusLabels[$maintenance->status] ?? $maintenance->status }}</span>
      </div>
    </div>
  </div>

  <div class="row g-4">
    <!-- Cột Trái: Thông tin chi tiết sự cố -->
    <div class="col-lg-7">
      <div class="detail-panel">
        <h4 class="fw-bold mb-4" style="color: var(--green-dark); font-size: 20px;">{{ $maintenance->title }}</h4>

        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Khu vực / Danh mục</div>
            <div class="info-value">{{ $categoryLabels[$maintenance->category] ?? 'Chưa phân loại' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Mức độ ưu tiên</div>
            <div class="info-value">{{ $priorityLabels[$maintenance->priority] ?? $maintenance->priority }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Phòng báo cáo</div>
            <div class="info-value">{{ $maintenance->room->name ?? '—' }}</div>
          </div>
          <div class="info-item">
            <div class="info-label">Người báo cáo</div>
            <div class="info-value">{{ $maintenance->tenant->name ?? '—' }}</div>
          </div>
          @if($maintenance->contract)
          <div class="info-item">
            <div class="info-label">Hợp đồng liên quan</div>
            <div class="info-value"><a href="{{ route('tenant.contracts.show', $maintenance->contract->id) }}">#{{ $maintenance->contract->contract_code }}</a></div>
          </div>
          @endif
        </div>

        <div class="mb-4">
          <div class="info-label">Mô tả hiện trạng</div>
          <div class="p-3 mt-2" style="background: #fafafa; border-radius: 12px; font-size: 14px; line-height: 1.6; border: 1px solid #eef0ef;">
            {{ $maintenance->description }}
          </div>
        </div>

        @if($maintenance->image)
        <div>
          <div class="info-label mb-2">Ảnh đính kèm</div>
          <div class="img-gallery d-flex gap-3 flex-wrap">
            <img src="{{ asset('storage/' . $maintenance->image) }}" alt="Ảnh hiện trường">
          </div>
        </div>
        @endif
      </div>
    </div>

    <!-- Cột Phải: Tiến độ xử lý (Timeline) -->
    <div class="col-lg-5">
      <div class="detail-panel">
        <h4 class="fw-bold mb-4" style="color: var(--green-dark); font-size: 18px;">📍 Tiến độ xử lý</h4>

        <ul class="timeline">
          <li class="timeline-item done">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="timeline-date">{{ $maintenance->created_at->format('H:i - d/m/Y') }}</div>
              <div class="fw-bold text-dark" style="font-size: 14px;">Gửi yêu cầu thành công</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Hệ thống đã ghi nhận sự cố của bạn.</div>
            </div>
          </li>

          @if(in_array($maintenance->status, ['processing', 'completed']))
          <li class="timeline-item done">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="timeline-date">{{ $maintenance->updated_at->format('H:i - d/m/Y') }}</div>
              <div class="fw-bold text-dark" style="font-size: 14px;">BQL đã tiếp nhận</div>
              @if($maintenance->owner_note)
              <div class="mt-2 p-2 rounded" style="background: var(--blue-soft); border-left: 3px solid var(--blue); font-size: 13px;">
                <strong>Ghi chú từ BQL:</strong> {{ $maintenance->owner_note }}
              </div>
              @endif
            </div>
          </li>
          @endif

          @if($maintenance->status === 'completed')
          <li class="timeline-item done">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="timeline-date">{{ ($maintenance->completed_at ?? $maintenance->updated_at)->format('H:i - d/m/Y') }}</div>
              <div class="fw-bold text-dark" style="font-size: 14px;">Nghiệm thu &amp; Hoàn thành</div>
            </div>
          </li>
          @elseif($maintenance->status === 'rejected')
          <li class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="fw-bold" style="font-size: 14px;">Yêu cầu đã bị từ chối</div>
            </div>
          </li>
          @else
          <li class="timeline-item {{ $maintenance->status === 'processing' ? 'active' : '' }}">
            <div class="timeline-dot"></div>
            <div class="timeline-content" style="background: transparent; border-color: transparent;">
              <div class="fw-bold" style="font-size: 14px; color: #ccc;">Nghiệm thu &amp; Hoàn thành</div>
            </div>
          </li>
          @endif
        </ul>

      </div>
    </div>
  </div>

</div>
@endsection