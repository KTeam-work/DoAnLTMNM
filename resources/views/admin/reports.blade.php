
@extends('Layout.admin')

@section('title', 'Quản lý báo cáo vi phạm | Trọ Ơi Admin')

@push('styles')

    <link rel="stylesheet" href="{{ asset('css/tv1/reports.css') }}?v=1">
@endpush

@section('content')
<div class="report-page">

    {{-- HERO --}}
    <section class="report-hero">
        <div class="report-hero-content">
            <div class="report-eyebrow">
                <span class="report-eyebrow-dot"></span>
                ADMIN CONTROL CENTER
            </div>

            <h1>Kiểm soát báo cáo</h1>

            <p>
                Theo dõi và xử lý các báo cáo vi phạm trên hệ thống Trọ Ơi.
                Góp phần xây dựng cộng đồng thuê trọ minh bạch, an toàn và đáng tin cậy.
            </p>

            <div class="report-hero-actions">
                <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}"
                   class="report-btn-primary">
                    <i class="bi bi-flag-fill"></i>
                    Báo cáo cần xử lý
                </a>

                <a href="{{ route('admin.rental-posts') }}"
                   class="report-btn-outline">
                    <i class="bi bi-house-door"></i>
                    Quản lý bài đăng
                </a>
            </div>
        </div>

        <div class="report-hero-art">
            <div class="report-art-circle report-art-circle-one"></div>
            <div class="report-art-circle report-art-circle-two"></div>

            <div class="report-shield">
                <i class="bi bi-shield-check"></i>
            </div>

            <div class="report-art-tag report-art-tag-top">
                <i class="bi bi-check-circle-fill"></i>
                Community Safety
            </div>

            <div class="report-art-tag report-art-tag-bottom">
                <i class="bi bi-clipboard-data-fill"></i>
                Report Center
            </div>
        </div>
    </section>

    {{-- PAGE HEADING --}}
    <div class="report-heading">
        <div>
            <div class="report-breadcrumb">
                <span>Quản trị</span>
                <i class="bi bi-chevron-right"></i>
                <span>Báo cáo vi phạm</span>
            </div>

            <h2>Danh sách báo cáo</h2>
            <p>Tra cứu thông tin và thực hiện xử lý các báo cáo từ người dùng.</p>
        </div>

        <div class="report-heading-status">
            <span class="report-status-dot"></span>
            Trung tâm kiểm duyệt
        </div>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="report-alert report-alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button type="button" class="report-alert-close"
                    onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="report-alert report-alert-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button type="button" class="report-alert-close"
                    onclick="this.parentElement.remove()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    @endif

    {{-- FILTER --}}
    <section class="report-panel">
        <div class="report-panel-heading">
            <div class="report-panel-icon">
                <i class="bi bi-funnel-fill"></i>
            </div>

            <div>
                <h3>Bộ lọc báo cáo</h3>
                <p>Tìm kiếm và lọc báo cáo theo nhu cầu</p>
            </div>
        </div>

        <form action="{{ route('admin.reports.index') }}"
              method="GET"
              class="report-filters">

            <div class="report-filter-item report-filter-search">
                <label for="reportSearch">Tìm kiếm</label>
                <div class="report-input-wrap">
                    <i class="bi bi-search"></i>
                    <input
                        type="text"
                        name="q"
                        id="reportSearch"
                        value="{{ $query ?? '' }}"
                        placeholder="Mã báo cáo, người báo cáo, bài đăng..."
                    >
                </div>
            </div>

            <div class="report-filter-item">
                <label for="reportStatus">Trạng thái</label>
                <select name="status" id="reportStatus">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ ($status ?? '') === 'pending' ? 'selected' : '' }}>
                        Chờ xử lý
                    </option>
                    <option value="investigating" {{ ($status ?? '') === 'investigating' ? 'selected' : '' }}>
                        Đang xác minh
                    </option>
                    <option value="resolved" {{ ($status ?? '') === 'resolved' ? 'selected' : '' }}>
                        Đã xử lý
                    </option>
                    <option value="dismissed" {{ ($status ?? '') === 'dismissed' ? 'selected' : '' }}>
                        Đã từ chối
                    </option>
                </select>
            </div>

            <div class="report-filter-item">
                <label for="reportReason">Lý do báo cáo</label>
                <select name="reason" id="reportReason">
                    <option value="">Tất cả lý do</option>
                    <option value="fake_info" {{ ($reason ?? '') === 'fake_info' ? 'selected' : '' }}>
                        Thông tin giả mạo
                    </option>
                    <option value="price_mismatch" {{ ($reason ?? '') === 'price_mismatch' ? 'selected' : '' }}>
                        Sai lệch giá
                    </option>
                    <option value="fraud_scam" {{ ($reason ?? '') === 'fraud_scam' ? 'selected' : '' }}>
                        Lừa đảo
                    </option>
                    <option value="contact_failed" {{ ($reason ?? '') === 'contact_failed' ? 'selected' : '' }}>
                        Không liên hệ được
                    </option>
                    <option value="inappropriate" {{ ($reason ?? '') === 'inappropriate' ? 'selected' : '' }}>
                        Nội dung không phù hợp
                    </option>
                    <option value="other" {{ ($reason ?? '') === 'other' ? 'selected' : '' }}>
                        Lý do khác
                    </option>
                </select>
            </div>

            <div class="report-filter-actions">
                <button type="submit" class="report-filter-submit">
                    <i class="bi bi-search"></i>
                    Lọc dữ liệu
                </button>

                <a href="{{ route('admin.reports.index') }}"
                   class="report-filter-reset"
                   title="Xóa bộ lọc">
                    <i class="bi bi-arrow-counterclockwise"></i>
                </a>
            </div>
        </form>
    </section>

    {{-- REPORT TABLE --}}
    <section class="report-panel report-list-panel">
        <div class="report-panel-heading report-list-heading">
            <div class="report-panel-icon">
                <i class="bi bi-clipboard2-data-fill"></i>
            </div>

            <div>
                <h3>Danh sách báo cáo vi phạm</h3>
                <p>Thông tin báo cáo được gửi từ người dùng hệ thống</p>
            </div>

            <div class="report-list-count">
                <i class="bi bi-file-earmark-text"></i>
                {{ $reports->total() }} báo cáo
            </div>
        </div>

        <div class="report-table-wrap">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Mã báo cáo</th>
                        <th>Bài đăng bị báo cáo</th>
                        <th>Chủ trọ</th>
                        <th>Người báo cáo</th>
                        <th>Nội dung</th>
                        <th>Minh chứng</th>
                        <th>Trạng thái</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($reports as $report)
                        @php
                            $post = $report->rentalPost;
                            $owner = $post?->room?->property?->owner;

                            $badgeClass = match($report->status) {
                                'pending' => 'badge-pending',
                                'investigating' => 'badge-investigating',
                                'resolved' => 'badge-resolved',
                                'dismissed' => 'badge-dismissed',
                                default => 'badge-default',
                            };
                        @endphp

                        <tr>
                            <td>
                                <div class="report-code">
                                    #{{ $report->id }}
                                </div>
                                <div class="report-date">
                                    {{ $report->created_at?->format('d/m/Y H:i') ?? '—' }}
                                </div>
                            </td>

                            <td>
                                @if($post)
                                    <div class="report-main-info">
                                        <span class="report-info-title">
                                            {{ $post->title ?? 'Bài đăng #' . $post->id }}
                                        </span>

                                        @if($post->room_id)
                                            <a href="{{ route('rooms.show', $post->room_id) }}"
                                               target="_blank"
                                               class="report-sub-link">
                                                Xem bài đăng
                                                <i class="bi bi-box-arrow-up-right"></i>
                                            </a>
                                        @endif
                                    </div>
                                @else
                                    <span class="report-muted">Bài đăng không còn tồn tại</span>
                                @endif
                            </td>

                            <td>
                                <div class="report-main-info">
                                    <span class="report-info-title">
                                        {{ $owner?->name ?? '—' }}
                                    </span>
                                    <span class="report-sub-info">
                                        <i class="bi bi-telephone"></i>
                                        {{ $owner?->phone ?? 'Chưa có SĐT' }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="report-main-info">
                                    <span class="report-info-title">
                                        {{ $report->reporter?->name ?? '—' }}
                                    </span>
                                    <span class="report-sub-info">
                                        <i class="bi bi-envelope"></i>
                                        {{ $report->reporter?->email ?? '—' }}
                                    </span>
                                    <span class="report-sub-info">
                                        <i class="bi bi-telephone"></i>
                                        {{ $report->reporter?->phone ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="report-reason">
                                    {{ $report->reason_label ?? 'Khác' }}
                                </div>

                                <div class="report-description"
                                     title="{{ $report->description }}">
                                    {{ $report->description ?: 'Không có mô tả' }}
                                </div>
                            </td>

                            <td>
                                @if($report->evidence_image)
                                    <a href="{{ asset('storage/' . $report->evidence_image) }}"
                                       target="_blank"
                                       rel="noopener noreferrer"
                                       class="report-evidence"
                                       title="Xem ảnh minh chứng">
                                        <img
                                            src="{{ asset('storage/' . $report->evidence_image) }}"
                                            alt="Ảnh minh chứng"
                                        >
                                        <span><i class="bi bi-eye"></i></span>
                                    </a>
                                @else
                                    <span class="report-no-evidence">
                                        <i class="bi bi-image"></i>
                                        Không có
                                    </span>
                                @endif
                            </td>

                            <td>
                                <span class="report-badge {{ $badgeClass }}">
                                    <span class="report-badge-dot"></span>
                                    {{ $report->status_label ?? 'Không xác định' }}
                                </span>

                                @if($report->handler)
                                    <div class="report-handler">
                                        Xử lý: {{ $report->handler->name }}
                                    </div>
                                @endif
                            </td>

                            <td class="text-center">
                                <button type="button"
                                        class="report-action-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalReport{{ $report->id }}">
                                    <i class="bi bi-pencil-square"></i>
                                    Xử lý
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="report-empty">
                                    <div class="report-empty-icon">
                                        <i class="bi bi-inbox"></i>
                                    </div>
                                    <h4>Chưa có báo cáo nào</h4>
                                    <p>Không tìm thấy báo cáo phù hợp với điều kiện lọc.</p>
                                    <a href="{{ route('admin.reports.index') }}">
                                        Xem tất cả báo cáo
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="report-pagination">
                {{ $reports->withQueryString()->links() }}
            </div>
        @endif
    </section>

    {{-- REPORT MODALS: đặt ngoài TABLE để HTML hợp lệ --}}
    @foreach($reports as $report)
        @php
            $post = $report->rentalPost;
            $owner = $post?->room?->property?->owner;
        @endphp

        <div class="modal fade report-modal"
             id="modalReport{{ $report->id }}"
             tabindex="-1"
             aria-labelledby="modalReportLabel{{ $report->id }}"
             aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <div>
                            <div class="report-modal-eyebrow">REPORT MANAGEMENT</div>
                            <h5 class="modal-title"
                                id="modalReportLabel{{ $report->id }}">
                                Xử lý báo cáo #{{ $report->id }}
                            </h5>
                        </div>

                        <button type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"></button>
                    </div>

                    <form action="{{ route('admin.reports.update', $report->id) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="modal-body">

                            <div class="report-modal-summary">
                                <div class="report-modal-summary-icon">
                                    <i class="bi bi-flag-fill"></i>
                                </div>
                                <div>
                                    <strong>{{ $report->reason_label ?? 'Báo cáo vi phạm' }}</strong>
                                    <span>
                                        Gửi lúc
                                        {{ $report->created_at?->format('H:i d/m/Y') ?? '—' }}
                                    </span>
                                </div>
                            </div>

                            <div class="report-modal-section">
                                <h6>Thông tin báo cáo</h6>

                                <div class="report-modal-grid">
                                    <div class="report-modal-field">
                                        <span>Người báo cáo</span>
                                        <strong>{{ $report->reporter?->name ?? '—' }}</strong>
                                    </div>

                                    <div class="report-modal-field">
                                        <span>Email</span>
                                        <strong>{{ $report->reporter?->email ?? '—' }}</strong>
                                    </div>

                                    <div class="report-modal-field">
                                        <span>Chủ trọ</span>
                                        <strong>{{ $owner?->name ?? '—' }}</strong>
                                    </div>

                                    <div class="report-modal-field">
                                        <span>Số điện thoại chủ trọ</span>
                                        <strong>{{ $owner?->phone ?? '—' }}</strong>
                                    </div>

                                    <div class="report-modal-field report-modal-field-full">
                                        <span>Bài đăng bị báo cáo</span>
                                        <strong>{{ $post?->title ?? 'Bài đăng không còn tồn tại' }}</strong>
                                    </div>

                                    <div class="report-modal-field report-modal-field-full">
                                        <span>Mô tả chi tiết</span>
                                        <p>{{ $report->description ?: 'Người dùng không cung cấp mô tả.' }}</p>
                                    </div>

                                    @if($report->evidence_image)
                                        <div class="report-modal-field report-modal-field-full">
                                            <span>Ảnh minh chứng</span>
                                            <a href="{{ asset('storage/' . $report->evidence_image) }}"
                                               target="_blank"
                                               rel="noopener noreferrer"
                                               class="report-modal-image-link">
                                                <img
                                                    src="{{ asset('storage/' . $report->evidence_image) }}"
                                                    alt="Ảnh minh chứng"
                                                >
                                                <span>
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                    Mở ảnh kích thước đầy đủ
                                                </span>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="report-modal-section report-modal-update">
                                <h6>Cập nhật xử lý</h6>

                                <div class="report-modal-form-grid">
                                    <div class="report-modal-field">
                                        <label for="status{{ $report->id }}">
                                            Trạng thái báo cáo
                                        </label>

                                        <select name="status"
                                                id="status{{ $report->id }}"
                                                class="form-select"
                                                required>
                                            <option value="pending"
                                                {{ $report->status === 'pending' ? 'selected' : '' }}>
                                                Chờ xử lý
                                            </option>

                                            <option value="investigating"
                                                {{ $report->status === 'investigating' ? 'selected' : '' }}>
                                                Đang xác minh
                                            </option>

                                            <option value="resolved"
                                                {{ $report->status === 'resolved' ? 'selected' : '' }}>
                                                Đã xử lý
                                            </option>

                                            <option value="dismissed"
                                                {{ $report->status === 'dismissed' ? 'selected' : '' }}>
                                                Đã từ chối
                                            </option>
                                        </select>
                                    </div>

                                    <div class="report-modal-field">
                                        <label for="action{{ $report->id }}">
                                            Hành động xử lý
                                        </label>

                                        <select name="action_taken"
                                                id="action{{ $report->id }}"
                                                class="form-select">
                                            <option value="none"
                                                {{ $report->action_taken === 'none' ? 'selected' : '' }}>
                                                Không thực hiện hành động
                                            </option>

                                            <option value="post_hidden"
                                                {{ $report->action_taken === 'post_hidden' ? 'selected' : '' }}>
                                                Ẩn bài đăng
                                            </option>

                                            <option value="owner_warned"
                                                {{ $report->action_taken === 'owner_warned' ? 'selected' : '' }}>
                                                Cảnh cáo chủ trọ
                                            </option>

                                            <option value="owner_banned"
                                                {{ $report->action_taken === 'owner_banned' ? 'selected' : '' }}>
                                                Khóa tài khoản chủ trọ
                                            </option>
                                        </select>
                                    </div>

                                    <div class="report-modal-field report-modal-field-full">
                                        <label for="adminNote{{ $report->id }}">
                                            Ghi chú của quản trị viên
                                        </label>

                                        <textarea
                                            name="admin_note"
                                            id="adminNote{{ $report->id }}"
                                            class="form-control"
                                            rows="4"
                                            placeholder="Nhập nội dung xử lý hoặc ghi chú..."
                                        >{{ old('admin_note', $report->admin_note) }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button type="button"
                                    class="report-modal-cancel"
                                    data-bs-dismiss="modal">
                                Hủy
                            </button>

                            <button type="submit" class="report-modal-save">
                                <i class="bi bi-check2-circle"></i>
                                Lưu kết quả xử lý
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endforeach

</div>
@endsection
=======
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

{{-- CARDS THỐNG KÊ & BỘ LỌC --}}
<div class="row g-3 mb-4">
    {{-- TẤT CẢ --}}
    <div class="col-6 col-lg">
        <a href="{{ route('admin.reports.index') }}"
           class="stat-card text-decoration-none {{ $status === '' ? 'border-primary shadow-sm' : '' }}"
           style="{{ $status === '' ? 'border-width: 2px; background: #f8fafc;' : '' }}">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Tất cả</span>
                <span class="badge-status new">Hệ thống</span>
            </div>
            <div class="stat-value text-dark">{{ number_format($counts['all']) }}</div>
            <div class="stat-label">Tổng báo cáo nhận được</div>
        </a>
    </div>

    {{-- CHỜ XỬ LÝ --}}
    <div class="col-6 col-lg">
        <a href="{{ route('admin.reports.index', ['status' => 'pending']) }}"
           class="stat-card text-decoration-none {{ $status === 'pending' ? 'border-warning shadow-sm' : '' }}"
           style="{{ $status === 'pending' ? 'border-width: 2px; background: #fffdf5;' : '' }}">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Chờ xử lý</span>
                <span class="{{ $counts['pending'] > 0 ? 'badge-status pending' : 'badge-status hidden' }}">
                    {{ $counts['pending'] > 0 ? 'Cần duyệt' : 'Đã duyệt' }}
                </span>
            </div>
            <div class="stat-value {{ $counts['pending'] > 0 ? 'text-warning' : 'text-dark' }}">{{ number_format($counts['pending']) }}</div>
            <div class="stat-label">Báo cáo mới chờ xử lý</div>
        </a>
    </div>

    {{-- ĐANG XÁC MINH --}}
    <div class="col-6 col-lg">
        <a href="{{ route('admin.reports.index', ['status' => 'investigating']) }}"
           class="stat-card text-decoration-none {{ $status === 'investigating' ? 'border-info shadow-sm' : '' }}"
           style="{{ $status === 'investigating' ? 'border-width: 2px; background: #f0f9ff;' : '' }}">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Xác minh</span>
                <span class="badge-status vacant">Đang kiểm tra</span>
            </div>
            <div class="stat-value text-dark">{{ number_format($counts['investigating']) }}</div>
            <div class="stat-label">Đang điều tra vi phạm</div>
        </a>
    </div>

    {{-- ĐÃ XỬ LÝ --}}
    <div class="col-6 col-lg">
        <a href="{{ route('admin.reports.index', ['status' => 'resolved']) }}"
           class="stat-card text-decoration-none {{ $status === 'resolved' ? 'border-success shadow-sm' : '' }}"
           style="{{ $status === 'resolved' ? 'border-width: 2px; background: #f8fafc;' : '' }}">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Đã xử lý</span>
                <span class="badge-status approved">Có vi phạm</span>
            </div>
            <div class="stat-value text-dark">{{ number_format($counts['resolved']) }}</div>
            <div class="stat-label">Đã chế tài & cảnh cáo</div>
        </a>
    </div>

    {{-- ĐÃ BÁC BỎ --}}
    <div class="col-6 col-lg">
        <a href="{{ route('admin.reports.index', ['status' => 'dismissed']) }}"
           class="stat-card text-decoration-none {{ $status === 'dismissed' ? 'border-secondary shadow-sm' : '' }}"
           style="{{ $status === 'dismissed' ? 'border-width: 2px; background: #f8fafc;' : '' }}">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Bác bỏ</span>
                <span class="badge-status hidden">Sai sự thật</span>
            </div>
            <div class="stat-value text-dark">{{ number_format($counts['dismissed']) }}</div>
            <div class="stat-label">Báo cáo không chính xác</div>
        </a>
    </div>
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
                                    {{ $report->action_taken_label }}
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
                                Xem &amp; Xử lý
                            </button>
                        </td>
                    </tr>

                    {{-- MODAL XỬ LÝ CHO TỪNG BÁO CÁO --}}
                    <div class="modal fade" id="modalReport{{ $report->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-dialog-centered">
                            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
                                <div class="modal-header bg-light" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
                                    <h5 class="modal-title fw-bold fs-6">
                                        Xử lý báo cáo vi phạm #{{ $report->report_code }}
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
                                                            Xem trang tin đăng thực tế
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
                                        <button type="submit" class="btn btn-sm btn-primary px-3">Lưu kết quả xử lý</button>
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


