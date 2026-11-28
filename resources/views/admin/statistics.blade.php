@extends('Layout.admin')

@section('title', 'Thống kê giám sát hệ thống | Trọ Ơi Admin')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv1/statistics.css') }}?v={{ time() }}">
@endpush

@section('content')
@php
    // 1. Dữ liệu Người dùng (Admin quản lý)
    $totalUsers = \App\Models\User::count();
    $ownerCount = \App\Models\User::where('role', 'owner')->count();
    $tenantCount = \App\Models\User::where('role', 'tenant')->count();
    $adminCount = \App\Models\User::where('role', 'admin')->count();
    $inactiveUsers = \App\Models\User::where('status', 'inactive')->count();
    $newUsersThisMonth = \App\Models\User::whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

    // 2. Dữ liệu Tin đăng & Kiểm duyệt (Admin quản lý)
    $totalPosts = \App\Models\RentalPost::count();
    $publishedPosts = \App\Models\RentalPost::where('status', 'published')->count();
    $pendingPosts = \App\Models\RentalPost::where('status', 'pending')->count();
    $hiddenPosts = \App\Models\RentalPost::where('status', 'hidden')->count();

    // 3. Dữ liệu Quy mô Phòng trọ & Hợp đồng toàn hệ thống
    $totalRooms = \App\Models\Room::count();
    $rentedRooms = \App\Models\Room::where('status', 'rented')->count();
    $availableRooms = \App\Models\Room::where('status', 'available')->count();
    $totalContracts = \App\Models\Contract::count();

    // 4. Dữ liệu An toàn & Báo cáo vi phạm (Admin xử lý)
    $reportsCount = \App\Models\PostReport::count();
    $pendingReportsCount = \App\Models\PostReport::where('status', 'pending')->count();
    $resolvedReportsCount = \App\Models\PostReport::where('status', 'resolved')->count();

    // Tỷ lệ phần trăm vận hành
    $approvalRate = $totalPosts > 0 ? round(($publishedPosts / $totalPosts) * 100) : 100;
    $rentedRate = $totalRooms > 0 ? round(($rentedRooms / $totalRooms) * 100) : 0;
    $resolvedRate = $reportsCount > 0 ? round(($resolvedReportsCount / $reportsCount) * 100) : 100;

    // Tin đăng mới nhất cần chú ý
    $samplePost = \App\Models\RentalPost::with(['room.property.owner'])->latest()->first();
    // Báo cáo vi phạm mới nhất
    $sampleReport = \App\Models\PostReport::with(['rentalPost', 'reporter'])->latest()->first();
    // Người dùng mới nhất
    $recentUsers = \App\Models\User::latest()->take(3)->get();

    // 5. Thống kê tăng trưởng 12 tháng theo năm từ database (Tính toán động 100%)
    $selectedYear = (int) request('year', now()->year);
    $availableYears = range(now()->year, max(2023, now()->year - 4));

    $yearTotalPosts = \App\Models\RentalPost::whereYear('created_at', $selectedYear)->count();
    $yearTotalUsers = \App\Models\User::whereYear('created_at', $selectedYear)->count();

    $months = collect();
    for ($m = 1; $m <= 12; $m++) {
        $pCount = \App\Models\RentalPost::whereYear('created_at', $selectedYear)->whereMonth('created_at', $m)->count();
        $uCount = \App\Models\User::whereYear('created_at', $selectedYear)->whereMonth('created_at', $m)->count();
        $months->push([
            'num' => $m,
            'label' => 'T' . $m,
            'full_label' => 'Tháng ' . $m . '/' . $selectedYear,
            'year' => $selectedYear,
            'posts' => $pCount,
            'users' => $uCount,
            'post_pct' => $yearTotalPosts > 0 ? round(($pCount / $yearTotalPosts) * 100, 1) : 0,
            'user_pct' => $yearTotalUsers > 0 ? round(($uCount / $yearTotalUsers) * 100, 1) : 0,
            'is_current' => ($selectedYear == now()->year && $m == now()->month),
        ]);
    }
    $maxMonthVal = max(1, $months->max(fn($m) => max($m['posts'], $m['users'])));

    // 6. Nhật ký sự kiện hoạt động thực tế từ database
    $events = collect();
    foreach (\App\Models\RentalPost::with('room.property.owner')->latest()->take(2)->get() as $p) {
        $events->push([
            'color' => '#2563eb',
            'time' => $p->created_at?->format('H:i') ?? '09:00',
            'title' => 'Đăng tin cho thuê mới',
            'desc' => '"' . \Illuminate\Support\Str::limit($p->title, 32) . '" (' . ($p->room?->property?->owner?->name ?? 'Chủ trọ') . ')',
            'created_at' => $p->created_at,
        ]);
    }
    foreach (\App\Models\PostReport::with('reporter')->latest()->take(2)->get() as $r) {
        $events->push([
            'color' => '#dc2626',
            'time' => $r->created_at?->format('H:i') ?? '10:30',
            'title' => 'Báo cáo vi phạm #' . $r->report_code,
            'desc' => $r->status === 'resolved' ? 'Đã được BQT giải quyết' : 'Đang chờ Admin xử lý',
            'created_at' => $r->created_at,
        ]);
    }
    foreach (\App\Models\User::latest()->take(2)->get() as $u) {
        $roleName = $u->role === 'owner' ? 'Chủ trọ' : ($u->role === 'tenant' ? 'Khách thuê' : 'Quản trị');
        $events->push([
            'color' => '#0c2340',
            'time' => $u->created_at?->format('H:i') ?? '14:00',
            'title' => 'Thành viên mới gia nhập',
            'desc' => $u->name . ' (' . $roleName . ')',
            'created_at' => $u->created_at,
        ]);
    }
    foreach (\App\Models\ViewingAppointment::with('tenant', 'room')->latest()->take(2)->get() as $a) {
        $events->push([
            'color' => '#0f766e',
            'time' => $a->created_at?->format('H:i') ?? '16:00',
            'title' => 'Lịch hẹn xem phòng',
            'desc' => ($a->tenant?->name ?? 'Khách') . ' hẹn xem ' . ($a->room?->name ?? 'phòng'),
            'created_at' => $a->created_at,
        ]);
    }
    $sortedEvents = $events->sortByDesc('created_at')->values()->take(4);
    $splitPayments = \App\Models\Payment::where('status', 'success')->whereNotNull('platform_fee');
    $totalPlatformFees = (clone $splitPayments)->where('fee_payment_status', 'paid')->sum('platform_fee');
    $pendingPlatformFees = (clone $splitPayments)->where('fee_payment_status', 'pending')->sum('platform_fee');
    $recentSplitPayments = (clone $splitPayments)->whereNotNull('fee_payment_status')->with('invoice.contract.owner')->latest('paid_at')->limit(10)->get();
@endphp

<div class="admin-stats-wrapper">
    <section style="background:#fff;border:1px solid #dce6ed;border-radius:16px;padding:24px;margin-bottom:20px;">
        <h2 style="font-size:20px;font-weight:700;margin:0 0 8px;">Phí nền tảng từ thanh toán</h2>
        <p style="color:#5b6b7a;margin-bottom:14px;">Phí đã nhận: <strong>{{ number_format((float) $totalPlatformFees) }} đ</strong> · Phí tiền mặt chủ trọ cần nộp: <strong>{{ number_format((float) $pendingPlatformFees) }} đ</strong></p>
        <p style="color:#5b6b7a;font-size:13px;">Tổng phí đã ghi nhận có thể bao gồm giao dịch mô phỏng. Phí tiền mặt chỉ được cộng sau khi nộp phí thành công; dữ liệu cũ chưa có trạng thái phí không được tính.</p>
        <div style="overflow-x:auto;">
            <table class="table table-sm align-middle">
                <thead><tr><th>Giao dịch</th><th>Chủ trọ</th><th>Hóa đơn</th><th>Phí nền tảng</th><th>Trạng thái phí</th></tr></thead>
                <tbody>
                @forelse($recentSplitPayments as $payment)
                    <tr><td>{{ $payment->payment_code }}</td><td>{{ $payment->invoice?->contract?->owner?->name ?? '—' }}</td><td>{{ number_format((float) $payment->amount) }} đ</td><td>{{ number_format((float) $payment->platform_fee) }} đ</td><td>{{ $payment->fee_payment_status !== 'paid' ? 'Chờ chủ trọ nộp' : ($payment->fee_payment_source === 'demo' ? 'Đã nộp (mô phỏng)' : ($payment->fee_payment_source === 'sepay' ? 'Đã nộp qua SePay' : 'Đã ghi nhận')) }}</td></tr>
                @empty
                    <tr><td colspan="5">Chưa có giao dịch được tính phí.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <div class="stats-master-card">

        {{-- ========================================================
             1. KHỐI TRÊN: NAVY HERO (VIRTUAL CONSOLE THEME)
        ======================================================== --}}
        <section class="stat-top-navy">
            
            {{-- Tiêu đề khu vực thẻ --}}
            <div class="stat-section-subtitle">
                Hạng mục trọng tâm cần giám sát
            </div>

            {{-- 3 Thẻ sự kiện nổi bật (Upcoming events style) --}}
            <div class="stat-event-cards">

                {{-- Thẻ 1: Kiểm duyệt tin đăng --}}
                <a href="{{ route('admin.rental-posts') }}" class="stat-event-card">
                    <div>
                        <div class="stat-card-meta">
                            <span>Hôm nay</span>
                            <span class="stat-meta-tag {{ $pendingPosts > 0 ? 'tag-warning' : 'tag-success' }}">
                                {{ $pendingPosts > 0 ? ($pendingPosts . ' tin chờ duyệt') : 'Đã duyệt sạch' }}
                            </span>
                        </div>
                        <h4 class="stat-card-h4">Kiểm duyệt bài đăng</h4>
                        <p class="stat-card-p">
                            @if($samplePost)
                                Bài đăng: "{{ $samplePost->title }}" từ {{ $samplePost->room?->property?->owner?->name ?? 'Chủ trọ' }}.
                            @else
                                Chưa có bài đăng nào cần kiểm duyệt thêm.
                            @endif
                        </p>
                    </div>

                    <div class="stat-card-foot">
                        <div class="stat-avatars-group">
                            <span class="stat-avatar-circle">CT</span>
                            <span class="stat-avatar-circle" style="background: #2563eb;">TK</span>
                            <span class="stat-avatar-circle" style="background: #0f766e;">+{{ $totalPosts }}</span>
                        </div>
                        <span class="stat-action-pill">
                            Kiểm duyệt ngay &rarr;
                        </span>
                    </div>
                </a>

                {{-- Thẻ 2: Báo cáo vi phạm --}}
                <a href="{{ route('admin.reports.index') }}" class="stat-event-card">
                    <div>
                        <div class="stat-card-meta">
                            <span>Khẩn cấp</span>
                            <span class="stat-meta-tag {{ $pendingReportsCount > 0 ? 'tag-danger' : 'tag-success' }}">
                                {{ $pendingReportsCount > 0 ? ($pendingReportsCount . ' khiếu nại mới') : 'An toàn' }}
                            </span>
                        </div>
                        <h4 class="stat-card-h4">Báo cáo & Khiếu nại</h4>
                        <p class="stat-card-p">
                            @if($sampleReport)
                                Báo cáo #{{ $sampleReport->report_code }}: "{{ \Illuminate\Support\Str::limit($sampleReport->reason, 45) }}"
                            @else
                                Không có khiếu nại tồn đọng từ người tìm phòng.
                            @endif
                        </p>
                    </div>

                    <div class="stat-card-foot">
                        <div class="stat-avatars-group">
                            <span class="stat-avatar-circle" style="background: #dc2626;">BC</span>
                            <span class="stat-avatar-circle" style="background: #ea580c;">VP</span>
                        </div>
                        <span class="stat-action-pill" style="color: #dc2626;">
                            Xử lý báo cáo &rarr;
                        </span>
                    </div>
                </a>

                {{-- Thẻ 3: Người dùng & An toàn tài khoản --}}
                <a href="{{ route('admin.users') }}" class="stat-event-card">
                    <div>
                        <div class="stat-card-meta">
                            <span>Toàn hệ thống</span>
                            <span class="stat-meta-tag tag-success">
                                {{ $totalUsers }} tài khoản
                            </span>
                        </div>
                        <h4 class="stat-card-h4">Người dùng & Tài khoản</h4>
                        <p class="stat-card-p">
                            Quản lý {{ $ownerCount }} chủ trọ và {{ $tenantCount }} người thuê. Giám sát an toàn thông tin & mở khóa tài khoản.
                        </p>
                    </div>

                    <div class="stat-card-foot">
                        <div class="stat-avatars-group">
                            @foreach($recentUsers as $ru)
                                <span class="stat-avatar-circle" style="background: #1e3a8a;">
                                    {{ strtoupper(mb_substr($ru->name, 0, 1)) }}
                                </span>
                            @endforeach
                        </div>
                        <span class="stat-action-pill">
                            Quản lý người dùng &rarr;
                        </span>
                    </div>
                </a>

            </div>
        </section>


        {{-- ========================================================
             2. KHỐI DƯỚI: LIGHT CONSOLE (WHITE BG, 3 COLUMNS)
        ======================================================== --}}
        <section class="stat-bottom-white">
            <div class="stat-main-grid">

                {{-- CỘT 1: MINI COUNTER & CIRCULAR PROGRESS --}}
                <div class="stat-col-left">

                    {{-- Thẻ số đếm lớn (như thẻ 68 Days to holiday) --}}
                    <div class="stat-holiday-box">
                        <div class="stat-giant-num">{{ $publishedPosts }}</div>
                        <div class="stat-giant-label">
                            Tin đăng đã được duyệt &amp; đang hiển thị công khai trên nền tảng
                        </div>
                    </div>

                    {{-- Thẻ Exams / Tiến độ & tỷ lệ kiểm duyệt --}}
                    <div class="stat-progress-box">
                        <div class="stat-box-header">
                            <div>
                                <h3 class="stat-box-title">Tiêu chí vận hành</h3>
                                <div style="font-size: 11px; color: var(--adm-muted); margin-top: 2px;">Tỷ lệ chuẩn hóa sàn</div>
                            </div>
                        </div>

                        {{-- Mục 1: Duyệt tin --}}
                        <div class="stat-ring-row">
                            <div class="stat-ring-info">
                                <span class="stat-ring-title">Duyệt tin đăng</span>
                                <span class="stat-ring-sub">{{ $publishedPosts }}/{{ $totalPosts }} bài</span>
                            </div>
                            <div class="stat-mini-circle">
                                <svg class="stat-circle-svg" width="38" height="38">
                                    <circle cx="19" cy="19" r="14" stroke="#e2e8f0" stroke-width="3" fill="none" />
                                    <circle cx="19" cy="19" r="14" stroke="#2563eb" stroke-width="3" fill="none"
                                            stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $approvalRate / 100) }}" stroke-linecap="round" />
                                </svg>
                                <span>{{ $approvalRate }}%</span>
                            </div>
                        </div>

                        {{-- Mục 2: Lấp đầy phòng --}}
                        <div class="stat-ring-row">
                            <div class="stat-ring-info">
                                <span class="stat-ring-title">Phòng có khách</span>
                                <span class="stat-ring-sub">{{ $rentedRooms }}/{{ $totalRooms }} phòng</span>
                            </div>
                            <div class="stat-mini-circle">
                                <svg class="stat-circle-svg" width="38" height="38">
                                    <circle cx="19" cy="19" r="14" stroke="#e2e8f0" stroke-width="3" fill="none" />
                                    <circle cx="19" cy="19" r="14" stroke="#0f766e" stroke-width="3" fill="none"
                                            stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $rentedRate / 100) }}" stroke-linecap="round" />
                                </svg>
                                <span>{{ $rentedRate }}%</span>
                            </div>
                        </div>

                        {{-- Mục 3: Xử lý vi phạm --}}
                        <div class="stat-ring-row">
                            <div class="stat-ring-info">
                                <span class="stat-ring-title">Xử lý vi phạm</span>
                                <span class="stat-ring-sub">{{ $resolvedReportsCount }}/{{ max(1, $reportsCount) }} vụ</span>
                            </div>
                            <div class="stat-mini-circle">
                                <svg class="stat-circle-svg" width="38" height="38">
                                    <circle cx="19" cy="19" r="14" stroke="#e2e8f0" stroke-width="3" fill="none" />
                                    <circle cx="19" cy="19" r="14" stroke="#0c2340" stroke-width="3" fill="none"
                                            stroke-dasharray="88" stroke-dashoffset="{{ 88 - (88 * $resolvedRate / 100) }}" stroke-linecap="round" />
                                </svg>
                                <span>{{ $resolvedRate }}%</span>
                            </div>
                        </div>

                        <a href="{{ route('admin.dashboard') }}" class="stat-btn-seeall">
                            Xem tổng thể
                        </a>
                    </div>

                </div>


                {{-- CỘT 2: TIMELINE HOẠT ĐỘNG SÀN (CALENDAR / TIMELINE STYLE) --}}
                <div class="stat-timeline-box">
                    <div class="stat-timeline-header">
                        <h3 class="stat-timeline-title">Nhật ký vận hành sàn</h3>
                        <select class="stat-select-sm">
                            <option>Hôm nay</option>
                            <option>Tuần này</option>
                        </select>
                    </div>

                    <div class="stat-timeline-list">
                        @forelse($sortedEvents as $event)
                            <div class="stat-timeline-item">
                                <span class="stat-time-slot">{{ $event['time'] }}</span>
                                <div class="stat-event-badge">
                                    <span class="stat-badge-dot" style="background: {{ $event['color'] }};"></span>
                                    <div class="stat-event-content">
                                        <span class="stat-event-name">{{ $event['title'] }}</span>
                                        <span class="stat-event-time">{{ $event['desc'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3" style="font-size: 12px;">Chưa có hoạt động mới ghi nhận.</div>
                        @endforelse
                    </div>
                </div>


                {{-- CỘT 3: TÀI NGUYÊN HỆ THỐNG & BIỂU ĐỒ THỐNG KÊ (RIGHT COL) --}}
                <div class="stat-col-right">

                    {{-- 4 Thẻ Phân loại tài nguyên (Your Classes style) --}}
                    <div class="stat-classes-box">
                        <div class="d-flex align-items-center justify-content-between">
                            <h3 class="stat-box-title">Quy mô tài nguyên sàn</h3>
                            <span class="badge bg-secondary-subtle text-secondary px-2 py-1" style="font-size: 11px;">Hệ thống</span>
                        </div>

                        <div class="stat-classes-grid">
                            {{-- Thẻ 1: Chủ trọ --}}
                            <a href="{{ route('admin.users', ['role' => 'owner']) }}" class="stat-class-card card-navy">
                                <span class="stat-class-title">Chủ trọ</span>
                                <span class="stat-class-count">{{ $ownerCount }} tài khoản</span>
                            </a>

                            {{-- Thẻ 2: Khách thuê --}}
                            <a href="{{ route('admin.users', ['role' => 'tenant']) }}" class="stat-class-card card-blue">
                                <span class="stat-class-title">Khách thuê</span>
                                <span class="stat-class-count">{{ $tenantCount }} tài khoản</span>
                            </a>

                            {{-- Thẻ 3: Tổng phòng trọ --}}
                            <div class="stat-class-card card-teal">
                                <span class="stat-class-title">Phòng trọ</span>
                                <span class="stat-class-count">{{ $totalRooms }} phòng</span>
                            </div>

                            {{-- Thẻ 4: Hợp đồng --}}
                            <div class="stat-class-card card-sky">
                                <span class="stat-class-title">Hợp đồng</span>
                                <span class="stat-class-count">{{ $totalContracts }} hiệu lực</span>
                            </div>
                        </div>
                    </div>

                    {{-- Biểu đồ Thống kê cột viên nang (Statistics style - Đủ 12 tháng & Năm) --}}
                    <div class="stat-barchart-box">
                        <div class="stat-chart-header">
                            <div>
                                <h3 class="stat-box-title">Tăng trưởng bài đăng &amp; Thành viên</h3>
                                <div style="font-size: 11px; color: var(--adm-muted); margin-top: 2px;">12 tháng trong năm {{ $selectedYear }}</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <form method="GET" action="{{ route('admin.statistics') }}" class="d-inline-flex align-items-center m-0" data-ajax-filter>
                                    <select name="year" class="form-select form-select-sm py-0 ps-2 pe-3" style="font-size: 11px; height: 26px; border-radius: 99px; font-weight: 700; color: var(--adm-navy);" onchange="this.form.requestSubmit()">
                                        @foreach($availableYears as $y)
                                            <option value="{{ $y }}" {{ $y == $selectedYear ? 'selected' : '' }}>Năm {{ $y }}</option>
                                        @endforeach
                                    </select>
                                </form>
                                <div class="stat-chart-legend">
                                    <div class="stat-legend-item">
                                        <span class="stat-leg-dot dot-navy"></span>
                                        <span>Tin đăng</span>
                                    </div>
                                    <div class="stat-legend-item">
                                        <span class="stat-leg-dot dot-blue"></span>
                                        <span>Người dùng</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Các cột viên nang kép qua 12 tháng (Rờ chuột hiện tooltip tỷ lệ ngay tại cột) --}}
                        <div class="stat-pill-bars-wrap">
                            @foreach($months as $m)
                                @php
                                    $pHeight = $m['posts'] > 0 ? max(10, round(($m['posts'] / $maxMonthVal) * 50)) : 6;
                                    $uHeight = $m['users'] > 0 ? max(8, round(($m['users'] / $maxMonthVal) * 36)) : 4;
                                @endphp
                                <div class="stat-bar-group clickable-bar {{ $m['is_current'] ? 'active' : '' }}"
                                     data-month="{{ $m['full_label'] }}"
                                     data-posts="{{ $m['posts'] }}"
                                     data-users="{{ $m['users'] }}"
                                     data-post-pct="{{ $m['post_pct'] }}"
                                     data-user-pct="{{ $m['user_pct'] }}">
                                    
                                    {{-- Tooltip nổi hiện tỷ lệ ngay khi rờ chuột vào cột --}}
                                    <div class="stat-bar-tooltip">
                                        <div class="tooltip-header">{{ $m['full_label'] }}</div>
                                        <div class="tooltip-line">
                                            <span class="tooltip-bullet dot-navy"></span>
                                            <span>Tin đăng: <strong>{{ $m['posts'] }}</strong> ({{ $m['post_pct'] }}%)</span>
                                        </div>
                                        <div class="tooltip-line">
                                            <span class="tooltip-bullet dot-blue"></span>
                                            <span>Người dùng: <strong>{{ $m['users'] }}</strong> ({{ $m['user_pct'] }}%)</span>
                                        </div>
                                    </div>

                                    <div class="stat-pill-stack">
                                        <div class="stat-pill-part part-navy" style="height: {{ $pHeight }}px;"></div>
                                        <div class="stat-pill-part part-blue" style="height: {{ $uHeight }}px; margin-bottom: 2px;"></div>
                                    </div>
                                    <span class="stat-bar-month">{{ $m['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Bảng chi tiết tỷ lệ khi rờ chuột vào cột --}}
                        @php
                            $currentM = $months->firstWhere('is_current', true) ?: $months->last();
                        @endphp
                        <div class="stat-bar-detail" id="barDetailPanel">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <span class="fw-bold text-dark" id="detailMonthTitle" style="font-size: 12px;">Chi tiết {{ $currentM['full_label'] }}</span>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-0" id="detailRatioBadge" style="font-size: 10px;">Rê chuột vào cột để xem</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between gap-3">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 11px;">
                                        <span style="color: var(--adm-navy); font-weight: 700;">Tin đăng</span>
                                        <span id="detailPostCount" class="fw-bold">{{ $currentM['posts'] }} tin ({{ $currentM['post_pct'] }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 5px; background: #e2e8f0; border-radius: 99px;">
                                        <div id="detailPostBar" class="progress-bar" style="background: var(--adm-navy); width: {{ $currentM['post_pct'] }}%;"></div>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between mb-1" style="font-size: 11px;">
                                        <span style="color: #0284c7; font-weight: 700;">Người dùng</span>
                                        <span id="detailUserCount" class="fw-bold">{{ $currentM['users'] }} người ({{ $currentM['user_pct'] }}%)</span>
                                    </div>
                                    <div class="progress" style="height: 5px; background: #e2e8f0; border-radius: 99px;">
                                        <div id="detailUserBar" class="progress-bar" style="background: #38bdf8; width: {{ $currentM['user_pct'] }}%;"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </section>

    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/statistics.js') }}?v={{ time() }}"></script>
@endpush

