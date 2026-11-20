
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
