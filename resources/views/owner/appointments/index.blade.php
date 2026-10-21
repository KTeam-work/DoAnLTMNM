

@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/appointment/index.css') }}">
@endpush

@section('content')


<main class="owner-main">
    <div class="heading">
        <div>
            <div class="eyebrow">Viewing appointments</div>
            <h1>Lịch xem phòng</h1>
            <p>Xem lịch hẹn theo tuần và xử lý yêu cầu của khách thuê.</p>
        </div>
        <div class="text-muted small">Bấm vào một lịch hẹn để xem chi tiết.</div>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="filter-links">
        <a href="{{ request()->fullUrlWithQuery(['status' => 'all']) }}"
           class="filter-link {{ $statusFilter === 'all' ? 'active' : '' }}">
            Tất cả ({{ $counts['total'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
           class="filter-link {{ $statusFilter === 'pending' ? 'active' : '' }}">
            Chờ xác nhận ({{ $counts['pending'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'confirmed']) }}"
           class="filter-link {{ $statusFilter === 'confirmed' ? 'active' : '' }}">
            Đã xác nhận ({{ $counts['confirmed'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'completed']) }}"
           class="filter-link {{ $statusFilter === 'completed' ? 'active' : '' }}">
            Hoàn thành ({{ $counts['completed'] }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['status' => 'cancelled']) }}"
           class="filter-link {{ $statusFilter === 'cancelled' ? 'active' : '' }}">
            Đã hủy ({{ $counts['cancelled'] }})
        </a>
    </div>

    <div class="summary-grid">
        <div class="summary-card">
            <div class="summary-card-top">
                <span>Tổng lịch</span>
                <div class="summary-icon">📅</div>
            </div>
            <strong>{{ $counts['total'] }}</strong>
            <small>Tất cả lịch xem phòng</small>
        </div>

        <div class="summary-card">
            <div class="summary-card-top">
                <span>Chờ xác nhận</span>
                <div class="summary-icon">⏳</div>
            </div>
            <strong>{{ $counts['pending'] }}</strong>
            <small>Cần xử lý</small>
        </div>

        <div class="summary-card">
            <div class="summary-card-top">
                <span>Đã xác nhận</span>
                <div class="summary-icon">✓</div>
            </div>
            <strong>{{ $counts['confirmed'] }}</strong>
            <small>Lịch đã được chấp nhận</small>
        </div>

        <div class="summary-card">
            <div class="summary-card-top">
                <span>Hoàn thành</span>
                <div class="summary-icon">★</div>
            </div>
            <strong>{{ $counts['completed'] }}</strong>
            <small>Đã xem phòng</small>
        </div>
    </div>

    <section class="panel">
        <div class="panel-head">
            <h3 class="panel-title">Lịch xem phòng theo tuần</h3>
            <span class="text-muted small">
                Tổng {{ $appointments->count() }} lịch đang hiển thị
            </span>
        </div>

        @if ($appointments->isEmpty())
            <div class="empty-state">
                <span class="emoji">📅</span>
                <p class="mb-0">Chưa có lịch hẹn ở trạng thái này.</p>
            </div>
        @endif

        <div class="calendar-content">
            <!-- Dữ liệu Blade được truyền qua data-* thay vì nhúng trong JavaScript -->
            <div
                id="appointmentCalendar"
                data-events="{{ json_encode($calendarEvents) }}"
                data-confirm-url="{{ route('owner.appointments.confirm', ['appointment' => '__ID__']) }}"
                data-reject-url="{{ route('owner.appointments.reject', ['appointment' => '__ID__']) }}">
            </div>
        </div>

        <div class="calendar-legend">
            <div class="legend-item">
                <span class="legend-dot dot-pending"></span> Chờ xác nhận
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-confirmed"></span> Đã xác nhận
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-completed"></span> Hoàn thành
            </div>
            <div class="legend-item">
                <span class="legend-dot dot-cancelled"></span> Đã hủy
            </div>
        </div>
    </section>
</main>

<!-- Modal chi tiết lịch hẹn -->
<div class="modal fade"
     id="appointmentDetailModal"
     tabindex="-1"
     aria-labelledby="appointmentDetailTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title" id="appointmentDetailTitle">
                        Chi tiết lịch xem phòng
                    </h5>
                    <small class="text-white-50">Thông tin lịch hẹn của khách thuê</small>
                </div>
                <button type="button" class="btn-close"
                        data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>

            <div class="detail-body">
                <div class="detail-row">
                    <div class="detail-label">Khách thuê</div>
                    <div class="detail-value" id="detailTenant">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Phòng</div>
                    <div class="detail-value" id="detailRoom">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Địa chỉ</div>
                    <div class="detail-value" id="detailAddress">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Số điện thoại</div>
                    <div class="detail-value" id="detailPhone">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ngày xem phòng</div>
                    <div class="detail-value" id="detailDate">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Giờ xem phòng</div>
                    <div class="detail-value" id="detailTime">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Lời nhắn</div>
                    <div class="detail-value" id="detailMessage">—</div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Trạng thái</div>
                    <span id="detailStatus" class="status-badge">—</span>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-4" id="appointmentActions">
                    <form id="confirmForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-owner">✓ Xác nhận lịch</button>
                    </form>

                    <form id="rejectForm" method="POST" action="">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn-reject">✕ Từ chối lịch</button>
                    </form>
                </div>

                <div id="processedNotice" class="alert alert-light mt-3 mb-0 d-none">
                    Lịch hẹn này đã được xử lý, không thể xác nhận hoặc từ chối lại.
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script src="{{ asset('js/tv3/appointment/index.js') }}"></script>
@endpush


@endsection