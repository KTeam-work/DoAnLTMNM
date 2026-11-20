@extends('Layout.admin')

@section('title', 'Dashboard | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv1/dashboard.css') }}">
@endpush

@section('content')

<div class="page-header">
    <div>
        <h2 class="page-title">Tổng quan hệ thống</h2>
        <div class="page-desc">
            Theo dõi hoạt động toàn nền tảng Trọ Ơi.
        </div>
    </div>

    <button type="button" class="btn-outline-brand">
        Xuất báo cáo
    </button>
</div>

{{-- THỐNG KÊ --}}
@php
    $totalUsers = \App\Models\User::count();
    $totalPosts = \App\Models\RentalPost::count();
    $pendingPosts = \App\Models\RentalPost::where('status', 'pending')->count();
    $pendingReports = \App\Models\PostReport::where('status', 'pending')->count();
    $inactiveUsers = \App\Models\User::where('status', 'inactive')->count();
@endphp

<div class="row g-3 mb-4">

    {{-- TỔNG NGƯỜI DÙNG --}}
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Người dùng</span>
                <span class="badge-status new">Hệ thống</span>
            </div>
            <div class="stat-value">{{ number_format($totalUsers) }}</div>
            <div class="stat-label">Tổng người dùng</div>
        </div>
    </div>

    {{-- TỔNG TIN ĐĂNG --}}
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Tin đăng</span>
                <span class="badge-status new">Toàn sàn</span>
            </div>
            <div class="stat-value">{{ number_format($totalPosts) }}</div>
            <div class="stat-label">Tổng tin đăng</div>
        </div>
    </div>

    {{-- TIN CHỜ DUYỆT --}}
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Chờ duyệt</span>
                <span class="badge-status new">Kiểm duyệt</span>
            </div>
            <div class="stat-value">{{ number_format($pendingPosts) }}</div>
            <div class="stat-label">Tin chờ duyệt</div>
        </div>
    </div>

    {{-- BÁO CÁO VI PHẠM --}}
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Vi phạm</span>
                <span class="badge-status new">Báo cáo</span>
            </div>
            <div class="stat-value">{{ number_format($pendingReports) }}</div>
            <div class="stat-label">Báo cáo chờ xử lý</div>
        </div>
    </div>

    {{-- TÀI KHOẢN KHÓA --}}
    <div class="col-6 col-lg">
        <div class="stat-card">
            <div class="stat-top">
                <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Tài khoản</span>
                <span class="badge-status new">Bị khóa</span>
            </div>
            <div class="stat-value">{{ number_format($inactiveUsers) }}</div>
            <div class="stat-label">Tài khoản bị khóa</div>
        </div>
    </div>

</div>

{{-- TIN ĐĂNG CHỜ DUYỆT --}}
<div class="panel">
    <div class="panel-head">
        <h3 class="panel-title">Tin đăng chờ duyệt</h3>

        <a class="panel-link"
           href="{{ route('admin.rental-posts') }}">
            Xem tất cả
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Tin đăng</th>
                    <th>Phòng</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>

            <tbody>
                @forelse (
                    \App\Models\RentalPost::with('room')
                        ->where('status', 'pending')
                        ->latest()
                        ->take(5)
                        ->get() as $post
                )
                    <tr>
                        <td>
                            <div class="cell-title">{{ $post->title }}</div>
                            <div class="cell-sub">
                                {{ \Illuminate\Support\Str::limit($post->description, 70) }}
                            </div>
                        </td>

                        <td>
                            {{ $post->room?->name ?? ('Phòng #' . $post->room_id) }}
                        </td>

                        <td>
                            {{ $post->created_at?->format('d/m/Y') ?? '—' }}
                        </td>

                        <td>
                            <span class="badge-status pending">
                                Chờ duyệt
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4">
                            Chưa có tin đăng nào chờ duyệt.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- BÁO CÁO VI PHẠM MỚI NHẤT --}}
@php
    $recentReports = \App\Models\PostReport::with(['rentalPost', 'reporter'])
        ->where('status', 'pending')
        ->latest()
        ->take(5)
        ->get();
@endphp
<div class="panel mt-4">
    <div class="panel-head">
        <h3 class="panel-title">Báo cáo vi phạm cần xử lý</h3>

        <a class="panel-link" href="{{ route('admin.reports.index') }}">
            Xem tất cả ({{ \App\Models\PostReport::where('status', 'pending')->count() }})
        </a>
    </div>

    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Mã BC</th>
                    <th>Tin bị tố cáo</th>
                    <th>Người gửi</th>
                    <th>Lý do vi phạm</th>
                    <th>Thời gian</th>
                    <th class="text-end">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($recentReports as $report)
                    <tr>
                        <td><strong>{{ $report->report_code }}</strong></td>
                        <td>
                            <div class="cell-title">{{ $report->rentalPost?->title ?? 'Tin đã xóa' }}</div>
                            <div class="cell-sub">{{ \Illuminate\Support\Str::limit($report->description, 60) }}</div>
                        </td>
                        <td>{{ $report->reporter?->name ?? 'Khách' }}</td>
                        <td>
                            <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1" style="font-size: 11px;">
                                {{ $report->reason_label }}
                            </span>
                        </td>
                        <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.reports.index', ['status' => 'pending', 'q' => $report->report_code]) }}" class="btn btn-sm btn-outline-danger" style="font-size: 12px; padding: 4px 10px;">
                                Xử lý
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-muted">
                            Hiện không có báo cáo vi phạm nào đang chờ xử lý.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/dashboard.js') }}"></script>
@endpush