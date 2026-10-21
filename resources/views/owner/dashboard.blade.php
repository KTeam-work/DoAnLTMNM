@extends('Layout.landlord')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')


@section('content')


  
      
        <section class="page-header">
            <div>
                <h2 class="page-title">Tổng quan hoạt động</h2>

                <div class="page-desc">
                    Xin chào {{ auth()->user()->name ?? 'Chủ trọ' }},
                    đây là tình hình cho thuê của bạn.
                </div>
            </div>

            <a class="btn-brand"
               href="{{ route('owner.rooms.index') }}"
               style="text-decoration: none;">
                + Quản lý phòng
            </a>
        </section>


        {{-- =========================
             THỐNG KÊ
        ========================== --}}
        <section class="row g-3 mb-3">

            {{-- Tổng số phòng --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon">🏠</div>
                    </div>

                    <div class="stat-value">
                        {{ $totalRooms }}
                    </div>

                    <div class="stat-label">Tổng số phòng</div>
                </div>
            </div>

            {{-- Phòng đang thuê --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon green">✅</div>
                    </div>

                    <div class="stat-value">
                        {{ $rentedRooms }}
                    </div>

                    <div class="stat-label">Phòng đang thuê</div>
                </div>
            </div>

            {{-- Phòng còn trống --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon blue">🔑</div>
                    </div>

                    <div class="stat-value">
                        {{ $availableRooms }}
                    </div>

                    <div class="stat-label">Phòng còn trống</div>
                </div>
            </div>

            {{-- Doanh thu --}}
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-top">
                        <div class="stat-icon red">💰</div>
                    </div>

                    <div class="stat-value">
                        {{ number_format((float) $monthlyRevenue, 0, ',', '.') }}đ
                    </div>

                    <div class="stat-label">Doanh thu tháng này</div>
                </div>
            </div>

        </section>


        {{-- =========================
             BẢNG DỮ LIỆU
        ========================== --}}
        <section class="row g-3">

            {{-- DANH SÁCH PHÒNG --}}
            <div class="col-lg-7">
                <div class="panel">

                    <div class="panel-head">
                        <h3 class="panel-title">Danh sách phòng</h3>

                        <a class="panel-link"
                           href="{{ route('owner.rooms.index') }}">
                            Quản lý tất cả
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
                                                    · {{ $room->property->name }}
                                                @endif
                                            </div>

                                            <div class="cell-sub">
                                                {{ $room->property?->district
                                                    ?? 'Chưa cập nhật quận/huyện' }}
                                            </div>
                                        </td>

                                        <td>
                                            {{ $tenantName ?? '—' }}
                                        </td>

                                        <td>
                                            {{ number_format((float) $room->price, 0, ',', '.') }}đ
                                        </td>

                                        <td>
                                            @switch($room->status)
                                                @case('rented')
                                                    <span class="badge-status occupied">
                                                        Đang thuê
                                                    </span>
                                                    @break

                                                @case('available')
                                                    <span class="badge-status vacant">
                                                        Còn trống
                                                    </span>
                                                    @break

                                                @case('pending')
                                                    <span class="badge-status">
                                                        Đang chờ
                                                    </span>
                                                    @break

                                                @case('maintenance')
                                                    <span class="badge-status overdue">
                                                        Đang bảo trì
                                                    </span>
                                                    @break

                                                @case('hidden')
                                                    <span class="badge-status">
                                                        Đang ẩn
                                                    </span>
                                                    @break

                                                @default
                                                    <span class="badge-status">
                                                        {{ $room->status }}
                                                    </span>
                                            @endswitch
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="cell-title">
                                                Chưa có phòng nào
                                            </div>

                                            <div class="cell-sub">
                                                Hãy thêm phòng để dữ liệu hiển thị tại đây.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>


            {{-- LỊCH XEM PHÒNG --}}
            <div class="col-lg-5">
                <div class="panel">

                    <div class="panel-head">
                        <h3 class="panel-title">Lịch xem phòng gần nhất</h3>

                        <a class="panel-link"
                           href="{{ route('owner.appointments.index') }}">
                            Xem tất cả
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
                                    <tr>
                                        <td>
                                            <div class="avatar-sm mb-1">
                                                {{ strtoupper(substr(
                                                    $appointment->tenant?->name ?? 'KH',
                                                    0,
                                                    2
                                                )) }}
                                            </div>

                                            {{ $appointment->tenant?->name ?? 'Khách thuê' }}

                                            <div class="cell-sub">
                                                @switch($appointment->status)
                                                    @case('pending')
                                                        Chờ xác nhận
                                                        @break

                                                    @case('confirmed')
                                                        Đã xác nhận
                                                        @break

                                                    @case('rescheduled')
                                                        Đề xuất đổi lịch
                                                        @break

                                                    @case('rejected')
                                                        Đã từ chối
                                                        @break

                                                    @case('cancelled')
                                                        Đã hủy
                                                        @break

                                                    @case('completed')
                                                        Đã hoàn thành
                                                        @break

                                                    @default
                                                        {{ $appointment->status }}
                                                @endswitch
                                            </div>
                                        </td>

                                        <td>
                                            {{ $appointment->room?->name ?? 'Phòng không tồn tại' }}
                                        </td>

                                        <td>
                                            @if ($appointment->appointment_date)
                                                {{ $appointment->appointment_date->format('d/m/Y') }}
                                            @else
                                                Chưa có ngày
                                            @endif

                                            <br>

                                            {{ substr(
                                                (string) $appointment->appointment_time,
                                                0,
                                                5
                                            ) }}
                                        </td>
                                    </tr>

                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4">
                                            <div class="cell-title">
                                                Chưa có lịch xem phòng
                                            </div>

                                            <div class="cell-sub">
                                                Lịch hẹn mới sẽ xuất hiện tại đây.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </section>

    


@endsection