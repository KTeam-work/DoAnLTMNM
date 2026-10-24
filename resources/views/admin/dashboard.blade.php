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
<div class="row g-3 mb-3">

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon blue">👥</div>
                <span class="stat-trend">Người dùng</span>
            </div>
            <div class="stat-value">{{ \App\Models\User::count() }}</div>
            <div class="stat-label">Tổng người dùng</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon">🏠</div>
                <span class="stat-trend">Tin đăng</span>
            </div>
            <div class="stat-value">{{ \App\Models\RentalPost::count() }}</div>
            <div class="stat-label">Tổng tin đăng</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon green">📋</div>
                <span class="stat-trend down">Cần xử lý</span>
            </div>
            <div class="stat-value">
                {{ \App\Models\RentalPost::where('status', 'pending')->count() }}
            </div>
            <div class="stat-label">Tin chờ duyệt</div>
        </div>
    </div>

    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-top">
                <div class="stat-icon red">🔒</div>
                <span class="stat-trend down">Tài khoản</span>
            </div>
            <div class="stat-value">
                {{ \App\Models\User::where('status', 'inactive')->count() }}
            </div>
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

@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/dashboard.js') }}"></script>
@endpush