@extends('Layout.landlord')

@section('title', 'Quản lý chỉ số điện nước | Trọ Ơi')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/utilities/create.css') }}">
@endpush

@section('content')

<div class="page-wrap">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h2 class="page-title">{{ $reading ? 'Cập nhật chỉ số điện nước' : 'Ghi chỉ số điện nước' }}</h2>
            <div class="page-desc">Chủ nhà <span> / Điện nước / {{ $reading ? 'Cập nhật' : 'Ghi mới' }}</span></div>
        </div>

        <div class="header-actions d-flex gap-2">
            <a href="{{ route('owner.utilities.index') }}" class="btn-outline-brand">
                ← Quay lại
            </a>
            <button type="submit" form="readingForm" class="btn-brand">
                💾 {{ $reading ? 'Cập nhật' : 'Lưu chỉ số' }}
            </button>
        </div>
    </div>

    <!-- FORM START -->
    @if($errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if($rooms->isEmpty())
        <div class="alert alert-warning">Chưa có phòng nào thuộc khu trọ của bạn. Hãy tạo phòng trước.</div>
    @endif
    <form id="readingForm" method="POST" action="{{ $reading ? route('owner.utilities.update', $reading) : route('owner.utilities.store') }}">
        @csrf
        @if($reading)
            @method('PUT')
        @endif
        <div class="form-grid">

            <!-- COT TRAI -->
            <div class="col-left">

                <!-- PHONG + THANG -->
                <div class="card">
                    <div class="card-header">
                        <span>🏠</span> Phòng &amp; kỳ ghi
                    </div>

                    <div class="row-2">
                        <div class="form-group">
                            <label>Phòng <span>*</span></label>
                            @if($reading)
                                <input type="text" class="custom-input" value="{{ $reading->room->name ?? '' }} ({{ $reading->room->room_code ?? '' }})" disabled>
                            @else
                                <select name="room_id" class="custom-input" required>
                                    <option value="">-- Chọn phòng --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ (string) old('room_id', $prefillRoomId) === (string) $room->id ? 'selected' : '' }}>{{ $room->name }} · {{ $room->property->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Kỳ ghi chỉ số <span>*</span></label>
                            <input type="month" name="month" class="custom-input" value="{{ old('month', $prefillMonth) }}" required>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-muted" style="font-weight:500">Chỉ số cũ sẽ tự lấy từ tháng trước (hoặc chốt đầu hợp đồng) nếu bạn bỏ trống.</label>
                    </div>
                </div>

                <!-- DIEN -->
                <div class="card">
                    <div class="card-header">
                        <span>⚡</span> Chỉ số điện
                    </div>

                    <div class="row-3">
                        <div class="form-group">
                            <label>Chỉ số cũ</label>
                            <input type="number" step="0.01" min="0" name="electricity_old" class="custom-input" value="{{ old('electricity_old', $reading?->electricity_old) }}" placeholder="Tự động">
                        </div>
                        <div class="form-group">
                            <label>Chỉ số mới <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="electricity_new" class="custom-input" value="{{ old('electricity_new', $reading?->electricity_new) }}" placeholder="Nhập số mới..." required>
                        </div>
                        <div class="form-group">
                            <label>Đơn giá (đ/kWh) <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="electricity_price" class="custom-input" value="{{ old('electricity_price', $reading?->electricity_price ?? 3500) }}" required>
                        </div>
                    </div>
                </div>

                <!-- NUOC -->
                <div class="card">
                    <div class="card-header">
                        <span>💧</span> Chỉ số nước
                    </div>

                    <div class="row-3">
                        <div class="form-group">
                            <label>Chỉ số cũ</label>
                            <input type="number" step="0.01" min="0" name="water_old" class="custom-input" value="{{ old('water_old', $reading?->water_old) }}" placeholder="Tự động">
                        </div>
                        <div class="form-group">
                            <label>Chỉ số mới <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="water_new" class="custom-input" value="{{ old('water_new', $reading?->water_new) }}" placeholder="Nhập số mới..." required>
                        </div>
                        <div class="form-group">
                            <label>Đơn giá (đ/m³) <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="water_price" class="custom-input" value="{{ old('water_price', $reading?->water_price ?? 20000) }}" required>
                        </div>
                    </div>
                </div>

            </div>

            <!-- COT PHAI: HUONG DAN -->
            <div class="col-right">
                <div class="card">
                    <div class="card-header">
                        <span>📌</span> Lưu ý
                    </div>
                    <div class="form-group">
                        <label class="text-muted" style="font-weight:500; line-height:1.7">
                            • Mỗi phòng mỗi tháng chỉ ghi 1 lần (hệ thống chặn trùng).<br>
                            • Chỉ số mới không được thấp hơn chỉ số cũ.<br>
                            • Muốn sửa bản ghi cũ, mở trang này với nút Sửa ở danh sách.
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <!-- FORM END -->

</div>
@endsection