
@extends('Layout.landlord')

@section('title', 'Chi tiết phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/show.css') }}">
@endpush

@section('content')




    <main class="container">
        <div class="breadcrumb">
            <a href="{{ route('owner.rooms.index') }}">Quản lý phòng</a>
            &nbsp; / &nbsp; Chi tiết phòng
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="page-header">
            <div>
                <h1>Chi tiết phòng trọ</h1>
                <p>Xem thông tin và tình trạng phòng đang quản lý.</p>
            </div>

            <div class="actions">
                <a href="{{ route('owner.rooms.index') }}" class="btn btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>

                <a href="{{ route('owner.rooms.edit', $room) }}" class="btn btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Sửa phòng
                </a>
            </div>
        </div>

        {{-- THÔNG TIN TỔNG QUAN --}}
        <section class="card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div>
                    <h2>Tổng quan phòng</h2>
                    <p>Thông tin nhận diện và giá phòng</p>
                </div>
            </div>

            <div class="card-body">
                <div class="room-summary">
                    <div>
                        <span class="room-code">
                            Mã phòng: {{ $room->room_code ?? $room->id }}
                        </span>

                        <h2 class="room-name">
                            {{ $room->name ?? 'Phòng ' . $room->id }}
                        </h2>

                        <div class="room-address">
                            <i class="fa-solid fa-building"></i>
                            {{ $room->property?->name ?? 'Chưa cập nhật nhà trọ' }}
                        </div>
                    </div>

                    @php
                        $roomStatus = strtolower((string) ($room->status ?? ''));
                    @endphp

                    @if (in_array($roomStatus, ['available', 'vacant', 'trống']))
                        <span class="status">Đang trống</span>
                    @elseif (in_array($roomStatus, ['occupied', 'rented', 'đã thuê']))
                        <span class="status unavailable">Đã cho thuê</span>
                    @else
                        <span class="status other">
                            {{ $room->status ?? 'Chưa cập nhật trạng thái' }}
                        </span>
                    @endif
                </div>

                <div class="price-panel">
                    <div>
                        <div class="price-label">Giá thuê mỗi tháng</div>
                        <div class="price-value">
                            @if (is_numeric($room->price ?? null))
                                {{ number_format((float) $room->price, 0, ',', '.') }} đ
                            @else
                                Chưa cập nhật
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="price-label">Tiền đặt cọc</div>
                        <div class="price-value">
                            @if (is_numeric($room->deposit ?? null))
                                {{ number_format((float) $room->deposit, 0, ',', '.') }} đ
                            @else
                                Chưa cập nhật
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="price-label">Diện tích</div>
                        <div class="price-value">
                            {{ $room->area !== null ? $room->area . ' m²' : 'Chưa cập nhật' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- THÔNG TIN CHI TIẾT --}}
        <section class="card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h2>Thông tin chi tiết</h2>
                    <p>Các thuộc tính của phòng trọ</p>
                </div>
            </div>

            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Mã phòng</div>
                        <div class="detail-value">
                            {{ $room->room_code ?? $room->id }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tên phòng</div>
                        <div class="detail-value">
                            {{ $room->name ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Nhà trọ / Bất động sản</div>
                        <div class="detail-value">
                            {{ $room->property?->name ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Diện tích</div>
                        <div class="detail-value">
                            {{ $room->area !== null ? $room->area . ' m²' : 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Số người tối đa</div>
                        <div class="detail-value">
                            {{ $room->max_people ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tầng</div>
                        <div class="detail-value">
                            {{ $room->floor ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Ngày tạo</div>
                        <div class="detail-value">
                            {{ $room->created_at?->format('d/m/Y H:i') ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Cập nhật lần cuối</div>
                        <div class="detail-value">
                            {{ $room->updated_at?->format('d/m/Y H:i') ?? 'Chưa cập nhật' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MÔ TẢ PHÒNG --}}
        <section class="card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-align-left"></i>
                </div>
                <div>
                    <h2>Mô tả phòng</h2>
                    <p>Thông tin giới thiệu về phòng trọ</p>
                </div>
            </div>

            <div class="card-body">
                <div class="description">{{ $room->description ?: 'Chưa có mô tả cho phòng này.' }}</div>
            </div>
        </section>

        <div class="actions">
            <a href="{{ route('owner.rooms.index') }}" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại danh sách
            </a>

            <a href="{{ route('owner.rooms.edit', $room) }}" class="btn btn-edit">
                <i class="fa-solid fa-pen-to-square"></i>
                Chỉnh sửa thông tin
            </a>
        </div>
    </main>

@endsection