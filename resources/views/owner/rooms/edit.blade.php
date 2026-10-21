
@extends('Layout.landlord')

@section('title', 'Chi tiết phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/edit.css') }}">
@endpush

@section('content')



<main class="page-wrap">
    <div class="page-header">
        <div>
            <h1 class="page-title">Chỉnh sửa phòng</h1>
            <div class="page-desc">
                Cập nhật thông tin và trạng thái phòng đang quản lý.
            </div>
        </div>

        <a class="btn-secondary-custom" href="{{ route('owner.rooms.index') }}">
            ← Quay lại danh sách
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <div class="fw-bold mb-2">Vui lòng kiểm tra lại thông tin:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="edit-layout">
        {{-- FORM --}}
        <section class="panel">
            <div class="panel-heading">
                <div class="heading-icon">🏠</div>
                <div>
                    <h2 class="panel-title">Thông tin phòng</h2>
                    <div class="panel-subtitle">
                        Mã bản ghi: #{{ $room->id }}
                    </div>
                </div>
            </div>

            <form
                id="editRoomForm"
                method="POST"
                action="{{ route('owner.rooms.update', $room) }}"
                novalidate>
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="property_id" class="form-label">
                        Nhà trọ <span class="required">*</span>
                    </label>
                    <select
                        id="property_id"
                        name="property_id"
                        class="form-select @error('property_id') is-invalid @enderror"
                        required>
                        <option value="">Chọn nhà trọ</option>
                        @foreach ($properties as $property)
                            <option
                                value="{{ $property->id }}"
                                @selected((string) old('property_id', $room->property_id) === (string) $property->id)>
                                {{ $property->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('property_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-7">
                        <label for="name" class="form-label">
                            Tên phòng <span class="required">*</span>
                        </label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            maxlength="100"
                            class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $room->name) }}"
                            placeholder="Ví dụ: Phòng 101"
                            required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-5">
                        <label for="room_code" class="form-label">Mã phòng</label>
                        <input
                            id="room_code"
                            name="room_code"
                            type="text"
                            maxlength="20"
                            class="form-control @error('room_code') is-invalid @enderror"
                            value="{{ old('room_code', $room->room_code) }}"
                            placeholder="Ví dụ: P101">
                        @error('room_code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="area" class="form-label">
                            Diện tích (m²) <span class="required">*</span>
                        </label>
                        <input
                            id="area"
                            name="area"
                            type="number"
                            min="0.01"
                            step="0.01"
                            class="form-control @error('area') is-invalid @enderror"
                            value="{{ old('area', $room->area) }}"
                            required>
                        @error('area')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="max_people" class="form-label">
                            Sức chứa (người) <span class="required">*</span>
                        </label>
                        <input
                            id="max_people"
                            name="max_people"
                            type="number"
                            min="1"
                            step="1"
                            class="form-control @error('max_people') is-invalid @enderror"
                            value="{{ old('max_people', $room->max_people) }}"
                            required>
                        @error('max_people')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label">
                            Giá thuê (VNĐ/tháng) <span class="required">*</span>
                        </label>
                        <input
                            id="price"
                            name="price"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control @error('price') is-invalid @enderror"
                            value="{{ old('price', (int) $room->price) }}"
                            required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="pricePreview"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="deposit" class="form-label">
                            Tiền đặt cọc (VNĐ) <span class="required">*</span>
                        </label>
                        <input
                            id="deposit"
                            name="deposit"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control @error('deposit') is-invalid @enderror"
                            value="{{ old('deposit', (int) $room->deposit) }}"
                            required>
                        @error('deposit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text" id="depositPreview"></div>
                    </div>

                    <div class="col-md-6">
                        <label for="floor" class="form-label">Tầng</label>
                        <input
                            id="floor"
                            name="floor"
                            type="number"
                            min="0"
                            step="1"
                            class="form-control @error('floor') is-invalid @enderror"
                            value="{{ old('floor', $room->floor) }}"
                            placeholder="Để trống nếu không áp dụng">
                        @error('floor')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="status" class="form-label">
                            Trạng thái <span class="required">*</span>
                        </label>
                        <select
                            id="status"
                            name="status"
                            class="form-select @error('status') is-invalid @enderror"
                            required>
                            @foreach ([
                                'available' => 'Còn trống',
                                'pending' => 'Đang chờ',
                                'rented' => 'Đang thuê',
                                'maintenance' => 'Bảo trì',
                                'hidden' => 'Đang ẩn',
                            ] as $value => $label)
                                <option
                                    value="{{ $value }}"
                                    @selected(old('status', $room->status) === $value)>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="statusNote" class="status-note" hidden></div>
                    </div>

                    <div class="col-12">
                        <label for="description" class="form-label">Mô tả phòng</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control @error('description') is-invalid @enderror"
                            rows="4"
                            placeholder="Mô tả nội thất, tiện nghi và lưu ý về phòng...">{{ old('description', $room->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-footer">
                    <a href="{{ route('owner.rooms.index') }}" class="btn-secondary-custom">
                        Hủy
                    </a>
                    <button type="submit" id="saveButton" class="btn-brand">
                        <span>✓</span> Lưu thay đổi
                    </button>
                </div>
            </form>
        </section>

        {{-- CỘT THÔNG TIN BÊN PHẢI --}}
        <aside class="side-column">
            <section class="side-card">
                <h3 class="side-title">Xem trước thông tin</h3>

                <div class="room-preview">
                    <div class="preview-label">Tên phòng</div>
                    <div class="preview-name" id="previewName">
                        {{ old('name', $room->name) }}
                    </div>

                    <div class="preview-price" id="previewPrice">
                        {{ number_format((float) old('price', $room->price), 0, ',', '.') }}đ
                    </div>

                    <div class="preview-meta">
                        Diện tích: <strong id="previewArea">{{ old('area', $room->area) }}</strong> m²
                    </div>
                    <div class="preview-meta">
                        Sức chứa: <strong id="previewPeople">{{ old('max_people', $room->max_people) }}</strong> người
                    </div>
                    <div class="preview-meta">
                        Trạng thái: <strong id="previewStatus"></strong>
                    </div>
                </div>
            </section>

            <section class="side-card">
                <h3 class="side-title">Lưu ý khi chỉnh sửa</h3>

                <div class="info-list">
                    <div class="info-item">
                        <span class="info-icon">🔐</span>
                        <span>Chỉ chủ trọ sở hữu phòng mới được phép cập nhật thông tin.</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">💰</span>
                        <span>Kiểm tra giá thuê và tiền đặt cọc trước khi lưu.</span>
                    </div>
                    <div class="info-item">
                        <span class="info-icon">📋</span>
                        <span>Thông tin chỉ được lưu khi em bấm nút Lưu thay đổi.</span>
                    </div>
                </div>
            </section>
        </aside>
    </div>
</main>

@push('scripts')
    <script src="{{ asset('js/tv3/rooms/edit.js') }}"></script>
@endpush


@endsection