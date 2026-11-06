@extends('Layout.admin')

@section('title', 'Quản lý báo cáo vi phạm | Trọ Ơi Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv1/rental-posts.css') }}">
    <style>
        .badge-report {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 700;
        }
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-investigating { background: #cff4fc; color: #055160; }
        .badge-resolved { background: #d1e7dd; color: #0f5132; }
        .badge-dismissed { background: #e2e3e5; color: #41464b; }
        .evidence-thumb {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #ddd;
            cursor: pointer;
            transition: transform 0.2s;
        }
        .evidence-thumb:hover { transform: scale(1.1); }
    </style>
@endpush

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý báo cáo vi phạm</h2>
        <div class="page-desc">Tiếp nhận khiếu nại, tố cáo tin đăng từ người tìm trọ và xử lý vi phạm.</div>
    </div>
</div>

{{-- CARDS THỐNG KÊ --}}
<div class="rp-summary" style="grid-template-columns: repeat(5, minmax(0, 1fr));">
    <a class="rp-summary-card {{ $status === '' ? 'selected' : '' }}" href="{{ route('admin.reports.index') }}">
        <span>Tất cả báo cáo</span>
        <strong>{{ $counts['all'] }}</strong>
    </a>
    <a class="rp-summary-card {{ $status === 'pending' ? 'selected' : '' }}" href="{{ route('admin.reports.index', ['status' => 'pending']) }}" style="border-left: 4px solid #ffc107;">
        <span>Chờ xử lý</span>
        <strong style="color: #d39e00;">{{ $counts['pending'] }}</strong>
    </a>
    <a class="rp-summary-card {{ $status === 'investigating' ? 'selected' : '' }}" href="{{ route('admin.reports.index', ['status' => 'investigating']) }}" style="border-left: 4px solid #0dcaf0;">
        <span>Đang xác minh</span>
        <strong style="color: #0aa2c0;">{{ $counts['investigating'] }}</strong>
    </a>
    <a class="rp-summary-card {{ $status === 'resolved' ? 'selected' : '' }}" href="{{ route('admin.reports.index', ['status' => 'resolved']) }}" style="border-left: 4px solid #198754;">
        <span>Đã xử lý</span>
        <strong style="color: #198754;">{{ $counts['resolved'] }}</strong>
    </a>
    <a class="rp-summary-card {{ $status === 'dismissed' ? 'selected' : '' }}" href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}" style="border-left: 4px solid #6c757d;">
        <span>Đã bác bỏ</span>
        <strong style="color: #6c757d;">{{ $counts['dismissed'] }}</strong>
    </a>
</div>

<div class="rp-panel">
    {{-- FORM LỌC & TÌM KIẾM --}}
    <form method="GET" action="{{ route('admin.reports.index') }}" class="rp-filters">
        <input
            type="search"
            name="q"
            value="{{ $query }}"
            placeholder="Tìm mã báo cáo, tên người gửi, tin đăng..."
            aria-label="Tìm báo cáo"
        >

        <select name="status" aria-label="Lọc trạng thái">
            <option value="">Tất cả trạng thái</option>
            <option value="pending" @selected($status === 'pending')>Chờ xử lý</option>
            <option value="investigating" @selected($status === 'investigating')>Đang xác minh</option>
            <option value="resolved" @selected($status === 'resolved')>Đã xử lý</option>
            <option value="dismissed" @selected($status === 'dismissed')>Đã bác bỏ</option>
        </select>

        <select name="reason" aria-label="Lọc lý do">
            <option value="">Tất cả lý do vi phạm</option>
            <option value="fake_info" @selected($reason === 'fake_info')>Tin giả / ảnh sai thực tế</option>
            <option value="price_mismatch" @selected($reason === 'price_mismatch')>Giá sai thực tế</option>
            <option value="fraud_scam" @selected($reason === 'fraud_scam')>Lừa đảo tiền cọc</option>
            <option value="contact_failed" @selected($reason === 'contact_failed')>Không liên hệ được</option>
            <option value="inappropriate" @selected($reason === 'inappropriate')>Nội dung phản cảm</option>
            <option value="other" @selected($reason === 'other')>Lý do khác</option>
        </select>

        <button type="submit" class="rp-btn rp-btn-primary">Lọc dữ liệu</button>
        <a href="{{ route('admin.reports.index') }}" class="rp-btn rp-btn-light">Đặt lại</a>
    </form>

    @if (session('success'))
        <div class="rp-alert success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="rp-alert error">{{ session('error') }}</div>
    @endif

    {{-- BẢNG DỮ LIỆU --}}
    <div class="table-responsive">
        <table class="rp-table">
            <thead>
                <tr>
                    <th>Mã BC</th>
                    <th>Tin đăng bị tố cáo</th>
                    <th>Người tố cáo</th>
                    <th>Lý do vi phạm</th>
                    <th>Bằng chứng</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reports as $report)
                    @php
                        $post = $report->rentalPost;
                        $owner = $post?->room?->property?->owner;
                        $badgeClass = match($report->status) {
                            'pending' => 'badge-pending',
                            'investigating' => 'badge-investigating',
                            'resolved' => 'badge-resolved',
                            'dismissed' => 'badge-dismissed',
                            default => '',
                        };
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $report->report_code }}</strong><br>
                            <small class="text-muted">{{ $report->created_at->format('d/m/Y H:i') }}</small>
                        </td>

                        <td style="max-width: 250px;">
                            @if($post)
                                <div class="fw-bold text-dark">{{ $post->title }}</div>
                                <small class="text-muted">Phòng: {{ $post->room->name ?? '—' }} ({{ $post->room->property->name ?? '' }})</small><br>
                                <small class="text-muted">Chủ trọ: <strong>{{ $owner->name ?? 'Chưa rõ' }}</strong> ({{ $owner->phone ?? '—' }})</small><br>
                                <span class="badge bg-light text-dark border mt-1" style="font-size: 10px;">Trạng thái tin: {{ $post->status }}</span>
                            @else
                                <span class="text-danger small">Tin đăng đã bị xóa</span>
                            @endif
                        </td>

                        <td>
                            <strong>{{ $report->reporter->name ?? 'Khách' }}</strong><br>
                            <small class="text-muted">{{ $report->reporter->email ?? '—' }}</small><br>
                            <small class="text-muted">{{ $report->reporter->phone ?? '' }}</small>
                        </td>

                        <td style="max-width: 220px;">
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-1" style="font-size: 11px;">
                                {{ $report->reason_label }}
                            </span>
                            <div class="small text-secondary" style="line-height: 1.4;">
                                {{ \Illuminate\Support\Str::limit($report->description, 80) }}
                            </div>
                        </td>

                        <td class="text-center">
                            @if($report->evidence_image)
                                <a href="{{ asset('storage/' . $report->evidence_image) }}" target="_blank" title="Bấm xem ảnh gốc">
                                    <img src="{{ asset('storage/' . $report->evidence_image) }}" alt="Bằng chứng" class="evidence-thumb">
                                </a>
                            @else
                                <span class="text-muted small">Không có</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge-report {{ $badgeClass }}">
                                {{ $report->status_label }}
                            </span>
                            @if($report->action_taken !== 'none')
                                <div class="small text-success mt-1 fw-bold" style="font-size: 11px;">
                                    ✓ {{ $report->action_taken_label }}
                                </div>
                            @endif
                            @if($report->handler)
                                <div class="small text-muted" style="font-size: 10px;">Duyệt bởi: {{ $report->handler->name }}</div>
                            @endif
                        </td>

                        <td class="text-end">
                            <button
                                type="button"
                                class="rp-btn rp-btn-primary rp-btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modalReport{{ $report->id }}"
                            >
                                🔍 Xem &amp; Xử lý
                            </button>
                        </td>
                    </tr>

                    {{-- MODAL XỬ LÝ CHO TỪNG BÁO CÁO --}}
                    <div class="modal fade" id="modalReport{{ $report->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                                <div class="modal-header bg-light" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                                    <h5 class="modal-title fw-bold fs-6">
                                        ⚖️ Xử lý báo cáo vi phạm #{{ $report->report_code }}
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                </div>
                                <form action="{{ route('admin.reports.update', $report->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body p-4 text-start">
                                        <div class="row g-3 mb-4">
                                            <div class="col-md-6 border-end">
                                                <h6 class="fw-bold text-danger mb-2">Thông tin khiếu nại</h6>
                                                <p class="mb-1 small"><strong>Lý do:</strong> {{ $report->reason_label }}</p>
                                                <p class="mb-1 small"><strong>Người gửi:</strong> {{ $report->reporter->name ?? '—' }} ({{ $report->reporter->email ?? '' }} / {{ $report->reporter->phone ?? '' }})</p>
                                                <p class="mb-2 small"><strong>Thời gian:</strong> {{ $report->created_at->format('d/m/Y H:i:s') }}</p>
                                                <div class="p-2 bg-light rounded small border">
                                                    <strong>Mô tả chi tiết từ khách:</strong><br>
                                                    {{ $report->description }}
                                                </div>
                                                @if($report->evidence_image)
                                                    <div class="mt-3">
                                                        <strong class="small d-block mb-1">Ảnh bằng chứng đính kèm:</strong>
                                                        <a href="{{ asset('storage/' . $report->evidence_image) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $report->evidence_image) }}" alt="Ảnh bằng chứng" class="img-fluid rounded border" style="max-height: 180px;">
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6">
                                                <h6 class="fw-bold text-dark mb-2">Tin đăng bị khiếu nại</h6>
                                                @if($post)
                                                    <p class="mb-1 small"><strong>Tiêu đề tin:</strong> {{ $post->title }}</p>
                                                    <p class="mb-1 small"><strong>Phòng:</strong> {{ $post->room->name ?? '' }} · {{ $post->room->property->name ?? '' }}</p>
                                                    <p class="mb-1 small"><strong>Chủ trọ:</strong> {{ $owner->name ?? '' }} ({{ $owner->email ?? '' }} - {{ $owner->phone ?? '' }})</p>
                                                    <p class="mb-2 small"><strong>Trạng thái tin hiện tại:</strong> <span class="badge bg-secondary">{{ $post->status }}</span></p>
                                                    <div class="mt-2">
                                                        <a href="{{ route('rooms.show', $post->room_id) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                                            🔗 Xem trang tin đăng thực tế
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="text-danger small">Tin đăng này đã bị xóa khỏi hệ thống.</p>
                                                @endif
                                            </div>
                                        </div>

                                        <hr>

                                        {{-- KHUNG HÀNH ĐỘNG CỦA ADMIN --}}
                                        <h6 class="fw-bold text-primary mb-3">Quyết định xử lý của Admin</h6>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Trạng thái báo cáo <span class="text-danger">*</span></label>
                                                <select name="status" class="form-select form-select-sm" required>
                                                    <option value="pending" @selected($report->status === 'pending')>Chờ xử lý</option>
                                                    <option value="investigating" @selected($report->status === 'investigating')>Đang xác minh thông tin</option>
                                                    <option value="resolved" @selected($report->status === 'resolved')>Đã xử lý (Xác nhận có vi phạm)</option>
                                                    <option value="dismissed" @selected($report->status === 'dismissed')>Bác bỏ (Tố cáo sai sự thật)</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold small">Biện pháp chế tài áp dụng</label>
                                                <select name="action_taken" class="form-select form-select-sm" required>
                                                    <option value="none" @selected($report->action_taken === 'none')>Không áp dụng chế tài</option>
                                                    <option value="post_hidden" @selected($report->action_taken === 'post_hidden')>Ẩn tin đăng vi phạm ngay</option>
                                                    <option value="owner_warned" @selected($report->action_taken === 'owner_warned')>Ghi nhận cảnh cáo chủ trọ</option>
                                                    <option value="owner_banned" @selected($report->action_taken === 'owner_banned')>Khóa tài khoản chủ trọ & Ẩn bài</option>
                                                </select>
                                            </div>

                                            <div class="col-12">
                                                <label class="form-label fw-semibold small">Ghi chú xử lý của Quản trị viên</label>
                                                <textarea name="admin_note" class="form-control form-control-sm" rows="3" placeholder="Nhập lý do xử lý hoặc căn cứ xác minh...">{{ old('admin_note', $report->admin_note) }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer bg-light p-3" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Đóng</button>
                                        <button type="submit" class="btn btn-sm btn-primary px-3">💾 Lưu kết quả xử lý</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">
                            Không tìm thấy báo cáo vi phạm nào phù hợp với bộ lọc.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PHÂN TRANG --}}
    @if ($reports->hasPages())
        <div class="mt-3">
            {{ $reports->links() }}
        </div>
    @endif
</div>

@endsection

