@extends('Layout.landlord')

@section('title', 'Tổng quan hoạt động | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/dashboard.css') }}">
@endpush

@section('content')

<div class="owner-page">
    <div class="owner-main">

        {{-- TIÊU ĐỀ --}}
        <div class="page-heading">
            <div>
                <div class="eyebrow">Owner workspace</div>
                <h1>Tổng quan hoạt động</h1>
                <p>Xin chào <strong>{{ auth()->user()->name ?? 'Chủ trọ' }}</strong>, đây là tình hình cho thuê và kinh doanh của bạn.</p>
            </div>

            <a class="btn-owner" href="{{ route('owner.rooms.index') }}">
                ＋ Quản lý phòng
            </a>
        </div>

        {{-- THỐNG KÊ --}}
        <div class="stats-grid">
            {{-- Tổng số phòng --}}
            <div class="stat-card">
                <div class="stat-top">
                    <span>Tổng số phòng</span>
                    <div class="stat-icon">🏠</div>
                </div>
                <strong>{{ $totalRooms }}</strong>
                <span class="stat-sub">Tất cả phòng đang quản lý</span>
            </div>

            {{-- Phòng đang thuê --}}
            <div class="stat-card">
                <div class="stat-top">
                    <span>Phòng đang thuê</span>
                    <div class="stat-icon green">👥</div>
                </div>
                <strong>{{ $rentedRooms }}</strong>
                <span class="stat-sub">Phòng có khách đang ở</span>
            </div>

            {{-- Phòng còn trống --}}
            <div class="stat-card">
                <div class="stat-top">
                    <span>Phòng còn trống</span>
                    <div class="stat-icon blue">🔑</div>
                </div>
                <strong>{{ $availableRooms }}</strong>
                <span class="stat-sub">Phòng sẵn sàng đón khách</span>
            </div>

            {{-- Doanh thu tháng này --}}
            <div class="stat-card">
                <div class="stat-top">
                    <span>Doanh thu tháng này</span>
                    <div class="stat-icon gold">💰</div>
                </div>
                <strong>{{ number_format((float) $monthlyRevenue, 0, ',', '.') }}đ</strong>
                <span class="stat-sub">Tiền phòng đã thu trong tháng</span>
            </div>
        </div>

        {{-- NỘI DUNG CHI TIẾT --}}
        <div class="row g-4">
            {{-- DANH SÁCH PHÒNG --}}
            <div class="col-lg-7">
                <section class="content-card">
                    <div class="card-header-owner">
                        <div>
                            <h2>Danh sách phòng</h2>
                            <p>Trạng thái các phòng cho thuê gần đây</p>
                        </div>
                        <a href="{{ route('owner.rooms.index') }}" class="btn-light-owner">
                            Quản lý tất cả →
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Phòng</th>
                                    <th>Người thuê</th>
                                    <th>Giá thuê</th>
                                    <th>Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($rooms as $room)
                                    @php
                                        $activeContract = $room->contracts->first();
                                        $tenantName = $activeContract?->tenant?->name;
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="cell-title">
                                                {{ $room->name }}
                                                @if ($room->property)
                                                    <span style="color: #8a9691; font-weight: normal;">· {{ $room->property->name }}</span>
                                                @endif
                                            </div>
                                            <div class="cell-sub">
                                                {{ $room->property?->district ?? 'Chưa cập nhật quận/huyện' }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($tenantName)
                                                <div class="tenant-cell">
                                                    <div class="avatar-sm">
                                                        {{ strtoupper(mb_substr($tenantName, 0, 1)) }}
                                                    </div>
                                                    <span>{{ $tenantName }}</span>
                                                </div>
                                            @else
                                                <span style="color: #a0aba5;">—</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="price-text">{{ number_format((float) $room->price, 0, ',', '.') }}đ</span>
                                        </td>
                                        <td>
                                            @switch($room->status)
                                                @case('rented')
                                                    <span class="badge-status occupied">Đang thuê</span>
                                                    @break
                                                @case('available')
                                                    <span class="badge-status vacant">Còn trống</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge-status pending">Đang chờ</span>
                                                    @break
                                                @case('maintenance')
                                                    <span class="badge-status maintenance">Bảo trì</span>
                                                    @break
                                                @case('hidden')
                                                    <span class="badge-status">Đang ẩn</span>
                                                    @break
                                                @default
                                                    <span class="badge-status">{{ $room->status }}</span>
                                            @endswitch
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">🚪</div>
                                                <div class="cell-title">Chưa có phòng nào</div>
                                                <div class="cell-sub">Hãy thêm phòng để dữ liệu hiển thị tại đây.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>

            {{-- LỊCH XEM PHÒNG GẦN NHẤT --}}
            <div class="col-lg-5">
                <section class="content-card">
                    <div class="card-header-owner">
                        <div>
                            <h2>Lịch xem phòng</h2>
                            <p>Lịch hẹn khách đăng ký mới nhất</p>
                        </div>
                        <a href="{{ route('owner.appointments.index') }}" class="btn-light-owner">
                            Xem tất cả →
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Khách xem</th>
                                    <th>Phòng</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($appointments as $appointment)
                                    @php
                                        $tName = $appointment->tenant?->name ?? 'Khách xem';
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="tenant-cell">
                                                <div class="avatar-sm">
                                                    {{ strtoupper(mb_substr($tName, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="cell-title" style="font-size: 13px;">{{ $tName }}</div>
                                                    <div class="cell-sub">
                                                        @switch($appointment->status)
                                                            @case('pending')
                                                                <span style="color: #b7791f; font-weight: 600;">Chờ xác nhận</span>
                                                                @break
                                                            @case('confirmed')
                                                                <span style="color: #1b8a5a; font-weight: 600;">Đã xác nhận</span>
                                                                @break
                                                            @case('rescheduled')
                                                                <span style="color: #2b6cb0; font-weight: 600;">Đổi lịch</span>
                                                                @break
                                                            @case('rejected')
                                                                <span style="color: #c53030; font-weight: 600;">Từ chối</span>
                                                                @break
                                                            @case('cancelled')
                                                                <span style="color: #718096; font-weight: 600;">Đã hủy</span>
                                                                @break
                                                            @case('completed')
                                                                <span style="color: #4a5568; font-weight: 600;">Hoàn thành</span>
                                                                @break
                                                            @default
                                                                <span>{{ $appointment->status }}</span>
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="cell-title" style="font-size: 13px;">
                                                {{ $appointment->room?->name ?? 'Phòng không tồn tại' }}
                                            </div>
                                            @if ($appointment->room?->property)
                                                <div class="cell-sub">
                                                    {{ $appointment->room->property->name }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="appointment-date-badge">
                                                {{ $appointment->appointment_date ? $appointment->appointment_date->format('d/m/Y') : 'Chưa có ngày' }}
                                            </div>
                                            <div class="appointment-time-badge">
                                                {{ substr((string) $appointment->appointment_time, 0, 5) }}
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <div class="empty-state-icon">📅</div>
                                                <div class="cell-title">Chưa có lịch xem phòng</div>
                                                <div class="cell-sub">Lịch hẹn mới sẽ hiển thị tại đây.</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>

    </div>
</div>

@endsection
