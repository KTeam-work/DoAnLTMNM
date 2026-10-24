@extends('Layout.admin')

@section('title', 'Thống kê | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv1/statistics.css') }}?v=4">
@endpush

@section('content')
@php
    $totalUsers = \App\Models\User::count();
    $totalPosts = \App\Models\RentalPost::count();
    $totalRooms = \App\Models\Room::count();

    $inactiveUsers = \App\Models\User::where('status', 'inactive')->count();

    $roleStats = [
        [
            'label' => 'Quản trị viên',
            'count' => \App\Models\User::where('role', 'admin')->count(),
            'color' => 'purple',
        ],
        [
            'label' => 'Chủ trọ',
            'count' => \App\Models\User::where('role', 'owner')->count(),
            'color' => 'green',
        ],
        [
            'label' => 'Người thuê',
            'count' => \App\Models\User::where('role', 'tenant')->count(),
            'color' => 'blue',
        ],
    ];

    $postStats = [
        [
            'label' => 'Bản nháp',
            'count' => \App\Models\RentalPost::where('status', 'draft')->count(),
            'color' => 'gray',
        ],
        [
            'label' => 'Chờ duyệt',
            'count' => \App\Models\RentalPost::where('status', 'pending')->count(),
            'color' => 'orange',
        ],
        [
            'label' => 'Đã đăng',
            'count' => \App\Models\RentalPost::where('status', 'published')->count(),
            'color' => 'green',
        ],
        [
            'label' => 'Bị từ chối',
            'count' => \App\Models\RentalPost::where('status', 'rejected')->count(),
            'color' => 'red',
        ],
        [
            'label' => 'Đang ẩn',
            'count' => \App\Models\RentalPost::where('status', 'hidden')->count(),
            'color' => 'blue',
        ],
    ];

    $maxRole = max(1, collect($roleStats)->max('count'));
    $maxPost = max(1, collect($postStats)->max('count'));

    $roleTotal = collect($roleStats)->sum('count');
    $postTotal = collect($postStats)->sum('count');
@endphp

<div class="stats-page">
    <div class="stats-header">
        <div>
            <h1 class="stats-title">Thống kê hệ thống</h1>
            <p class="stats-subtitle">
                Tổng quan người dùng, phòng trọ và tình trạng tin đăng trên Trọ Ơi.
            </p>
        </div>

        <button type="button" class="stats-export-btn" id="exportStatistics">
            <span aria-hidden="true">↓</span>
            Xuất báo cáo CSV
        </button>
    </div>

    <div class="stats-summary">
        <article class="stats-card stats-card-users">
            <div class="stats-card-icon" aria-hidden="true">👥</div>
            <div class="stats-card-info">
                <p>Tổng người dùng</p>
                <h2>{{ number_format($totalUsers) }}</h2>
                <span>Toàn bộ tài khoản</span>
            </div>
        </article>

        <article class="stats-card stats-card-posts">
            <div class="stats-card-icon" aria-hidden="true">📋</div>
            <div class="stats-card-info">
                <p>Tổng tin đăng</p>
                <h2>{{ number_format($totalPosts) }}</h2>
                <span>Tất cả trạng thái</span>
            </div>
        </article>

        <article class="stats-card stats-card-rooms">
            <div class="stats-card-icon" aria-hidden="true">🏠</div>
            <div class="stats-card-info">
                <p>Tổng phòng</p>
                <h2>{{ number_format($totalRooms) }}</h2>
                <span>Dữ liệu phòng trọ</span>
            </div>
        </article>

        <article class="stats-card stats-card-inactive">
            <div class="stats-card-icon" aria-hidden="true">⏸</div>
            <div class="stats-card-info">
                <p>Tài khoản không hoạt động</p>
                <h2>{{ number_format($inactiveUsers) }}</h2>
                <span>Trạng thái inactive</span>
            </div>
        </article>
    </div>

    <div class="stats-panels">
        <section class="stats-panel">
            <div class="stats-panel-header">
                <div>
                    <h2>Người dùng theo vai trò</h2>
                    <p>Phân bố tài khoản trong hệ thống</p>
                </div>
                <span class="stats-total-badge">
                    {{ number_format($roleTotal) }} tài khoản
                </span>
            </div>

            <div class="stats-chart">
                @foreach ($roleStats as $item)
                    @php
                        $barWidth = $item['count'] > 0
                            ? max(3, ($item['count'] / $maxRole) * 100)
                            : 0;
                    @endphp

                    <div class="stats-bar-row">
                        <div class="stats-bar-label">
                            <span>{{ $item['label'] }}</span>
                            <strong>{{ number_format($item['count']) }}</strong>
                        </div>

                        <div class="stats-bar-track">
                            <div
                                class="stats-bar stats-bar-{{ $item['color'] }}"
                                data-width="{{ $barWidth }}"
                                role="progressbar"
                                aria-label="{{ $item['label'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                aria-valuenow="{{ round($barWidth, 1) }}"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="stats-panel">
            <div class="stats-panel-header">
                <div>
                    <h2>Tình trạng tin đăng</h2>
                    <p>Số lượng tin theo từng trạng thái</p>
                </div>
                <span class="stats-total-badge">
                    {{ number_format($postTotal) }} tin
                </span>
            </div>

            <div class="stats-chart">
                @foreach ($postStats as $item)
                    @php
                        $barWidth = $item['count'] > 0
                            ? max(3, ($item['count'] / $maxPost) * 100)
                            : 0;
                    @endphp

                    <div class="stats-bar-row">
                        <div class="stats-bar-label">
                            <span>{{ $item['label'] }}</span>
                            <strong>{{ number_format($item['count']) }}</strong>
                        </div>

                        <div class="stats-bar-track">
                            <div
                                class="stats-bar stats-bar-{{ $item['color'] }}"
                                data-width="{{ $barWidth }}"
                                role="progressbar"
                                aria-label="{{ $item['label'] }}"
                                aria-valuemin="0"
                                aria-valuemax="100"
                                aria-valuenow="{{ round($barWidth, 1) }}"
                            ></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    </div>

    <section class="stats-panel stats-detail-panel">
        <div class="stats-panel-header">
            <div>
                <h2>Bảng tổng hợp thống kê</h2>
                <p>Dữ liệu hiện tại được lấy từ cơ sở dữ liệu.</p>
            </div>
        </div>

        <div class="stats-table-wrapper">
            <table class="stats-table" id="statisticsTable">
                <thead>
                    <tr>
                        <th>STT</th>
                        <th>Nhóm thống kê</th>
                        <th>Loại</th>
                        <th>Số lượng</th>
                    </tr>
                </thead>

                <tbody>
                    @php $index = 1; @endphp

                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>Người dùng</td>
                        <td>Tổng tài khoản</td>
                        <td>{{ $totalUsers }}</td>
                    </tr>

                    @foreach ($roleStats as $item)
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td>Người dùng</td>
                            <td>{{ $item['label'] }}</td>
                            <td>{{ $item['count'] }}</td>
                        </tr>
                    @endforeach

                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>Người dùng</td>
                        <td>Tài khoản không hoạt động</td>
                        <td>{{ $inactiveUsers }}</td>
                    </tr>

                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>Phòng trọ</td>
                        <td>Tổng phòng</td>
                        <td>{{ $totalRooms }}</td>
                    </tr>

                    <tr>
                        <td>{{ $index++ }}</td>
                        <td>Tin đăng</td>
                        <td>Tổng tin đăng</td>
                        <td>{{ $totalPosts }}</td>
                    </tr>

                    @foreach ($postStats as $item)
                        <tr>
                            <td>{{ $index++ }}</td>
                            <td>Tin đăng</td>
                            <td>{{ $item['label'] }}</td>
                            <td>{{ $item['count'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <p class="stats-note">
            Lưu ý: Báo cáo này thống kê số lượng dữ liệu hiện có, chưa tính doanh thu.
        </p>
    </section>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/statistics.js') }}?v=4"></script>
@endpush