@extends('layout.tenant')

@section('title', 'Lịch sử báo cáo vi phạm | Trọ Ơi')

@push('styles')
<style>
    .report-card {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #eef2f6;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.04);
        transition: transform 0.2s, box-shadow 0.2s;
        overflow: hidden;
    }
    .report-card:hover {
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }
    .report-card-header {
        padding: 14px 20px;
        background: #fafbfc;
        border-bottom: 1px solid #f0f2f5;
    }
    .report-thumb {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .report-thumb:hover {
        transform: scale(1.08);
    }
</style>
@endpush

@section('content')
<div class="page-wrap" style="padding-top: 30px; max-width: 1100px;">

    {{-- HEADER --}}
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
        <div>
            <h2 class="page-title">Báo cáo vi phạm của tôi</h2>
            <div class="page-desc text-muted mt-1">
                Theo dõi tiến độ và kết quả giải quyết khiếu nại, tố cáo tin đăng từ Ban quản trị Trọ Ơi.
            </div>
        </div>
        <div>
            <a href="{{ route('rooms.index') }}" class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 fw-semibold" style="font-size: 13px;">
                Tìm kiếm phòng trọ
            </a>
        </div>
    </div>

    {{-- BỘ LỌC TABS DẠNG SEGMENTED --}}
    <div class="segmented-filter-wrapper mb-4">
        <div class="segmented-filter-bar" role="tablist">
            <a href="{{ route('tenant.reports.index') }}" class="segmented-tab {{ $status === '' ? 'active' : '' }}">
                <span class="tab-label">Tất cả</span>
                <span class="tab-count">{{ $counts['all'] }}</span>
            </a>
            <a href="{{ route('tenant.reports.index', ['status' => 'pending']) }}" class="segmented-tab {{ $status === 'pending' ? 'active' : '' }}">
                <span class="tab-label">Chờ xử lý</span>
                <span class="tab-count">{{ $counts['pending'] }}</span>
            </a>
            <a href="{{ route('tenant.reports.index', ['status' => 'investigating']) }}" class="segmented-tab {{ $status === 'investigating' ? 'active' : '' }}">
                <span class="tab-label">Đang xác minh</span>
                <span class="tab-count">{{ $counts['investigating'] }}</span>
            </a>
            <a href="{{ route('tenant.reports.index', ['status' => 'resolved']) }}" class="segmented-tab {{ $status === 'resolved' ? 'active' : '' }}">
                <span class="tab-label">Đã giải quyết</span>
                <span class="tab-count">{{ $counts['resolved'] }}</span>
            </a>
            <a href="{{ route('tenant.reports.index', ['status' => 'dismissed']) }}" class="segmented-tab {{ $status === 'dismissed' ? 'active' : '' }}">
                <span class="tab-label">Bác bỏ</span>
                <span class="tab-count">{{ $counts['dismissed'] }}</span>
            </a>
        </div>
    </div>

    {{-- DANH SÁCH BÁO CÁO --}}
    @if($reports->isEmpty())
        <div class="text-center py-5 bg-white rounded-4 border p-4 my-3">
            <h5 class="fw-bold text-dark">Bạn chưa gửi báo cáo vi phạm nào</h5>
            <p class="text-muted small mx-auto" style="max-width: 460px;">
                Khi bạn bắt gặp các tin đăng có dấu hiệu sai sự thật, lừa cọc hoặc giá ảo trên hệ thống, hãy bấm "Báo cáo tin đăng" để giúp cộng đồng Trọ Ơi luôn an toàn, minh bạch.
            </p>
            <a href="{{ route('rooms.index') }}" class="btn btn-success rounded-pill px-4 py-2 mt-2">
                Khám phá danh sách phòng trọ
            </a>
        </div>
    @else
        <div class="d-flex flex-column gap-3 mb-4">
            @foreach($reports as $report)
                @php
                    $post = $report->rentalPost;
                    $owner = $post?->room?->property?->owner;
                    $statusBadgeClass = match($report->status) {
                        'pending' => 'bg-warning text-dark',
                        'investigating' => 'bg-info text-dark',
                        'resolved' => 'bg-success text-white',
                        'dismissed' => 'bg-secondary text-white',
                        default => 'bg-light text-dark',
                    };
                @endphp
                <div class="report-card">
                    <div class="report-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="fw-bold font-monospace text-primary">#{{ $report->report_code }}</span>
                            <span class="text-muted" style="font-size: 12px;">• Gửi lúc {{ $report->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>
                            <span class="badge {{ $statusBadgeClass }} px-2 py-1" style="font-size: 11.5px; border-radius: 6px;">
                                {{ $report->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="p-3 p-md-4">
                        <div class="row g-3">
                            {{-- CỘT TRÁI: THÔNG TIN TIN ĐĂNG BỊ TỐ CÁO --}}
                            <div class="col-md-7 border-end-md">
                                <h6 class="fw-bold text-dark mb-2" style="font-size: 14.5px;">
                                    @if($post)
                                        <a href="{{ route('rooms.show', $post->room_id) }}" class="text-decoration-none text-dark hover-primary" target="_blank">
                                            {{ $post->title }}
                                        </a>
                                    @else
                                        <span class="text-danger">Tin đăng này đã bị gỡ khỏi hệ thống</span>
                                    @endif
                                </h6>

                                @if($post)
                                    <div class="text-muted small mb-2">
                                        Phòng: <strong>{{ $post->room->name ?? '—' }}</strong> · {{ $post->room->property->name ?? '' }} · Chủ trọ: {{ $owner->name ?? 'Chưa rõ' }}
                                    </div>
                                @endif

                                <div class="mb-2">
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1 small" style="font-size: 11px;">
                                        Lý do: {{ $report->reason_label }}
                                    </span>
                                </div>

                                <div class="p-2 bg-light rounded small border text-secondary mb-2" style="line-height: 1.5;">
                                    <strong class="text-dark d-block mb-1">Mô tả vi phạm bạn đã cung cấp:</strong>
                                    {{ $report->description }}
                                </div>

                                @if($report->evidence_image)
                                    <div class="mt-2 d-flex align-items-center gap-2">
                                        <span class="small text-muted">Bằng chứng:</span>
                                        <a href="{{ asset('storage/' . $report->evidence_image) }}" target="_blank" title="Bấm xem ảnh kích thước gốc">
                                            <img src="{{ asset('storage/' . $report->evidence_image) }}" alt="Bằng chứng đính kèm" class="report-thumb">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- CỘT PHẢI: KẾT QUẢ XỬ LÝ TỪ BAN QUẢN TRỊ --}}
                            <div class="col-md-5">
                                <h6 class="fw-bold text-secondary mb-2" style="font-size: 13px; text-transform: uppercase;">
                                    Phản hồi từ Ban Quản Trị
                                </h6>

                                @if($report->status === 'pending')
                                    <div class="alert alert-warning py-3 px-3 mb-0 small rounded-3 border-warning">
                                        <div class="fw-bold mb-1">Đang chờ tiếp nhận</div>
                                        <div class="text-muted" style="line-height: 1.4;">
                                            Báo cáo của bạn đã được tiếp nhận. Đội ngũ kiểm duyệt Trọ Ơi sẽ sớm đối soát nội dung và ảnh bằng chứng.
                                        </div>
                                    </div>
                                @elseif($report->status === 'investigating')
                                    <div class="alert alert-info py-3 px-3 mb-0 small rounded-3 border-info">
                                        <div class="fw-bold mb-1">Đang xác minh thông tin</div>
                                        <div class="text-muted" style="line-height: 1.4;">
                                            Ban quản trị đang tiến hành liên hệ với chủ trọ và xác minh các bằng chứng liên quan.
                                        </div>
                                    </div>
                                @elseif($report->status === 'resolved')
                                    <div class="alert alert-success py-3 px-3 mb-0 small rounded-3 border-success">
                                        <div class="fw-bold text-success mb-1">Đã giải quyết vi phạm</div>
                                        <div class="mb-1">
                                            <strong>Biện pháp chế tài:</strong>
                                            <span class="badge bg-success-subtle text-success border border-success-subtle">
                                                {{ $report->action_taken_label }}
                                            </span>
                                        </div>
                                        @if($report->admin_note)
                                            <div class="mt-2 p-2 bg-white rounded border border-success-subtle">
                                                <strong>Lời nhắn BQT:</strong> {{ $report->admin_note }}
                                            </div>
                                        @endif
                                        @if($report->resolved_at)
                                            <div class="text-muted mt-2" style="font-size: 11px;">
                                                Thời gian xử lý: {{ $report->resolved_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                @elseif($report->status === 'dismissed')
                                    <div class="alert alert-secondary py-3 px-3 mb-0 small rounded-3 border-secondary">
                                        <div class="fw-bold mb-1">Báo cáo đã được bác bỏ</div>
                                        <div class="text-muted mb-1" style="line-height: 1.4;">
                                            Sau khi kiểm tra, tin đăng chưa phát hiện dấu hiệu vi phạm hoặc chủ nhà đã cung cấp giải trình hợp lệ.
                                        </div>
                                        @if($report->admin_note)
                                            <div class="mt-2 p-2 bg-white rounded border">
                                                <strong>Lời nhắn BQT:</strong> {{ $report->admin_note }}
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- PHÂN TRANG --}}
        @if ($reports->hasPages())
            <div class="mt-3 d-flex justify-content-center">
                {{ $reports->links() }}
            </div>
        @endif
    @endif

</div>
@endsection
