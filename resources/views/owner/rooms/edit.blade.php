
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
                    <label for="property_id" class="form-label d-flex align-items-center justify-content-between">
                        <span>Nhà trọ <span class="required">*</span></span>
                        <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Cố định</span>
                    </label>
                    <select
                        id="property_id_select"
                        class="form-select bg-light text-muted"
                        disabled>
                        @foreach ($properties as $property)
                            <option
                                value="{{ $property->id }}"
                                @selected((string) old('property_id', $room->property_id) === (string) $property->id)>
                                {{ $property->name }}
                            </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="property_id" value="{{ $room->property_id }}">
                    <div class="form-text text-muted">Khu trọ gắn liền với vị trí phòng và không thể thay đổi sau khi tạo.</div>
                    @error('property_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
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
                        <label for="room_code" class="form-label d-flex align-items-center justify-content-between">
                            <span>Mã phòng</span>
                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1" style="font-size: 11px;">Cố định</span>
                        </label>
                        <input
                            id="room_code"
                            type="text"
                            class="form-control bg-light text-muted fw-bold"
                            value="{{ $room->room_code }}"
                            readonly
                            disabled>
                        <div class="form-text text-muted">Mã phòng được tạo tự động và cố định, không thể chỉnh sửa.</div>
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
                        <label for="price" class="form-label d-flex align-items-center justify-content-between">
                            <span>Giá thuê (VNĐ/tháng) <span class="required">*</span></span>
                            @if(!empty($hasActiveContract))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 11px;">Khóa theo HĐ</span>
                            @endif
                        </label>
                        <div class="input-group">
                            <input
                                id="price"
                                name="price"
                                type="text"
                                class="form-control @error('price') is-invalid @enderror @if(!empty($hasActiveContract)) bg-light @endif"
                                value="{{ old('price') ? number_format((float) preg_replace('/[^\d]/', '', old('price')), 0, ',', '.') : number_format((float) $room->price, 0, ',', '.') }}"
                                placeholder="Ví dụ: 3.000.000"
                                required
                                @if(!empty($hasActiveContract)) readonly @endif
                                autocomplete="off">
                            <span class="input-group-text">đ/tháng</span>
                        </div>
                        @if(!empty($hasActiveContract))
                            <div class="form-text text-warning-emphasis fw-medium mt-1" style="font-size: 12px;">
                                Giá thuê được cố định theo hợp đồng thuê đang có hiệu lực.
                            </div>
                        @else
                            <div class="form-text text-primary fw-medium" id="pricePreview" style="font-size: 12px; min-height: 18px;"></div>
                        @endif
                        @error('price')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="deposit" class="form-label d-flex align-items-center justify-content-between">
                            <span>Tiền đặt cọc (VNĐ) <span class="required">*</span></span>
                            @if(!empty($hasActiveContract))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 11px;">Khóa theo HĐ</span>
                            @endif
                        </label>
                        <div class="input-group">
                            <input
                                id="deposit"
                                name="deposit"
                                type="text"
                                class="form-control @error('deposit') is-invalid @enderror @if(!empty($hasActiveContract)) bg-light @endif"
                                value="{{ old('deposit') ? number_format((float) preg_replace('/[^\d]/', '', old('deposit')), 0, ',', '.') : number_format((float) $room->deposit, 0, ',', '.') }}"
                                placeholder="Ví dụ: 3.000.000"
                                required
                                @if(!empty($hasActiveContract)) readonly @endif
                                autocomplete="off">
                            <span class="input-group-text">đ</span>
                        </div>
                        @if(!empty($hasActiveContract))
                            <div class="form-text text-warning-emphasis fw-medium mt-1" style="font-size: 12px;">
                                Tiền đặt cọc đang được bảo lưu theo hợp đồng.
                            </div>
                        @else
                            <div class="form-text text-primary fw-medium" id="depositPreview" style="font-size: 12px; min-height: 18px;"></div>
                        @endif
                        @error('deposit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
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
                        <label for="status" class="form-label d-flex align-items-center justify-content-between">
                            <span>Trạng thái <span class="required">*</span></span>
                            @if(!empty($hasActiveContract))
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle" style="font-size: 11px;">Khóa theo HĐ</span>
                            @endif
                        </label>
                        @if(!empty($hasActiveContract))
                            <select id="status_disabled" class="form-select bg-light text-muted" disabled>
                                <option value="rented" selected>Đang thuê (Tự động theo hợp đồng có hiệu lực)</option>
                            </select>
                            <input type="hidden" name="status" value="rented">
                            <div class="form-text text-warning-emphasis fw-medium mt-1" style="font-size: 12px;">
                                Phòng đang có hợp đồng thuê có hiệu lực, trạng thái được khóa là "Đang thuê". Khi thanh lý hợp đồng, phòng sẽ tự động chuyển về "Còn trống".
                            </div>
                        @else
                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>
                                <option value="available" @selected(in_array(old('status', $room->status), ['available', 'rented', 'pending']))>Còn trống</option>
                                <option value="maintenance" @selected(old('status', $room->status) === 'maintenance')>Đang bảo trì</option>
                                <option value="hidden" @selected(old('status', $room->status) === 'hidden')>Đang ẩn</option>
                            </select>
                            <div class="form-text text-muted mt-1" style="font-size: 12px;">
                                Chỉ có 3 trạng thái: Còn trống, Đang bảo trì hoặc Đang ẩn.
                            </div>
                        @endif
                        @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <div id="statusNote" class="status-note" hidden></div>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Tiện ích phòng có sẵn</label>
                        @php
                            $selectedAmenityIds = old('amenities', $room->amenities->pluck('id')->toArray());
                        @endphp
                        @if(isset($amenities) && $amenities->isNotEmpty())
                            <div class="p-3 bg-light rounded border d-flex flex-wrap gap-3">
                                @foreach($amenities as $amenity)
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="edit_amenity_{{ $amenity->id }}" @checked(in_array($amenity->id, $selectedAmenityIds))>
                                        <label class="form-check-label" for="edit_amenity_{{ $amenity->id }}" style="cursor: pointer; font-size: 14px;">
                                            {{ $amenity->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-muted small">
                                Chưa có tiện ích nào trong danh mục. Bạn có thể thêm trong mục <a href="{{ route('owner.amenities.index') }}" target="_blank">Quản lý tiện ích</a>.
                            </div>
                        @endif
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
                        Lưu thay đổi
                    </button>
                </div>
            </form>
            <div class="border-top mt-4 pt-4">
                <h2 class="h5 fw-bold">Ảnh phòng 360°</h2>
                <p class="text-muted small">Tải ảnh toàn cảnh equirectangular tỷ lệ 2:1 (JPG, PNG hoặc WEBP, tối đa 15 MB). Ảnh thường không hiển thị đúng ở chế độ 360°.</p>
                @if($room->panorama_path)
                    <p class="text-success fw-semibold">Phòng đã có ảnh 360°.</p>
                    <form action="{{ route('owner.rooms.panorama.destroy', $room) }}" method="POST" class="mb-3" data-no-ajax="true" onsubmit="return confirm('Xóa ảnh 360° của phòng?')">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger">Xóa ảnh 360°</button></form>
                @endif
                <form action="{{ route('owner.rooms.panorama.store', $room) }}" method="POST" enctype="multipart/form-data" data-no-ajax="true">
                    @csrf <label for="room-panorama" class="form-label">{{ $room->panorama_path ? 'Thay ảnh 360°' : 'Chọn ảnh 360°' }}</label>
                    <input class="form-control mb-2" id="room-panorama" name="panorama" type="file" accept="image/jpeg,image/png,image/webp" required>
                    <button class="btn btn-outline-primary" type="submit">{{ $room->panorama_path ? 'Cập nhật ảnh 360°' : 'Tải ảnh 360°' }}</button>
                </form>
            </div>
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
                        <span>Chỉ chủ trọ sở hữu phòng mới được phép cập nhật thông tin.</span>
                    </div>
                    <div class="info-item">
                        <span>Kiểm tra giá thuê và tiền đặt cọc trước khi lưu.</span>
                    </div>
                    <div class="info-item">
                        <span>Thông tin chỉ được lưu khi bấm nút Lưu thay đổi.</span>
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
