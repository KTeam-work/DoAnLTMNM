
@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/edit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/modal_premium.css') }}">
@endpush

@section('content')

<div class="page" style="max-width: 920px; margin: 30px auto; padding: 0 16px;">
    <div class="mb-3">
        <a href="{{ route('owner.rental-posts.index') }}" class="text-decoration-none fw-semibold text-muted">← Quay lại danh sách tin đăng</a>
    </div>

    <div class="panel-premium-card">
        <div class="panel-premium-header">
            <div>
                <h2 class="panel-premium-title">Tạo tin đăng mới</h2>
                <div class="panel-premium-subtitle">Chọn phòng trống và soạn nội dung giới thiệu tin đăng cho thuê</div>
            </div>
            <span class="badge bg-light text-dark px-3 py-2 rounded-3 fw-bold" style="font-size: 11.5px;">Đăng tin mới</span>
        </div>

        <div class="panel-premium-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form
                action="{{ route('owner.rental-posts.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
            @csrf

            {{-- BỘ LỌC VÀ CHỌN PHÒNG CÒN TRỐNG --}}
            <div class="mb-4 p-3 bg-light border rounded-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label fw-bold mb-0">
                        Chọn phòng trọ cần đăng tin <span class="text-danger">*</span>
                    </label>
                    @if ($rooms->isNotEmpty())
                        <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                            {{ $rooms->count() }} phòng còn trống sẵn sàng đăng tin
                        </span>
                    @endif
                </div>

                {{-- Bộ lọc theo Nhà trọ (nếu có từ 2 nhà trọ trở lên) --}}
                @if (isset($properties) && $properties->count() > 1)
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Lọc theo nhà trọ:</label>
                        <select id="propertyFilter" class="form-select form-select-sm">
                            <option value="">-- Tất cả nhà trọ ({{ $rooms->count() }} phòng trống) --</option>
                            @foreach ($properties as $property)
                                @php
                                    $propRoomCount = $rooms->where('property_id', $property->id)->count();
                                @endphp
                                <option value="{{ $property->id }}">
                                    {{ $property->name }} ({{ $propRoomCount }} phòng trống)
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                {{-- Dropdown chọn phòng chỉ hiển thị các phòng còn trống --}}
                <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                    <option value="">-- Chọn phòng trọ còn trống --</option>
                    @foreach ($rooms->groupBy(fn($r) => $r->property->name ?? 'Nhà trọ') as $propertyName => $propertyRooms)
                        <optgroup label="🏠 Khu trọ: {{ $propertyName }}">
                            @foreach ($propertyRooms as $room)
                                <option value="{{ $room->id }}"
                                    data-property-id="{{ $room->property_id }}"
                                    data-name="{{ $room->name }}"
                                    data-code="{{ $room->room_code ?? '' }}"
                                    data-property="{{ $room->property->name ?? 'Nhà trọ' }}"
                                    data-price="{{ number_format((float) $room->price, 0, ',', '.') }}đ/tháng"
                                    data-deposit="{{ number_format((float) $room->deposit, 0, ',', '.') }}đ"
                                    data-area="{{ $room->area }}"
                                    data-max-people="{{ $room->max_people }}"
                                    @selected(old('room_id') == $room->id)>
                                    {{ $room->name ?? ('Phòng #' . $room->id) }}
                                    — Giá: {{ number_format((float) $room->price, 0, ',', '.') }}đ/tháng
                                    — Diện tích: {{ $room->area }}m²
                                    (Còn trống)
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                @error('room_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                @if ($rooms->isEmpty())
                    <div class="alert alert-warning mt-3 mb-0 py-2 px-3 small">
                        <strong>Thông báo:</strong> Hiện tại bạn không có phòng nào ở trạng thái <strong>Còn trống</strong> (hoặc tất cả các phòng trống đều đã có tin đăng). Hệ thống chỉ cho phép tạo tin đăng cho phòng còn trống để tránh trùng lặp.
                        <div class="mt-1">
                            <a href="{{ route('owner.rooms.index') }}" class="fw-bold text-decoration-none">
                                → Vào Quản lý phòng để kiểm tra hoặc cập nhật trạng thái phòng
                            </a>
                        </div>
                    </div>
                @else
                    <small class="text-secondary d-block mt-2">
                        ✓ Danh sách chỉ hiển thị các phòng <strong>Còn trống</strong> và chưa có tin đăng đang hoạt động.
                    </small>
                @endif

                {{-- Hộp thông tin chi tiết phòng khi chọn --}}
                <div id="roomDetailCard" class="mt-3 p-3 bg-white border rounded" style="display: none;">
                    <div class="fw-bold mb-2 text-dark" id="detailRoomTitle">Thông tin phòng</div>
                    <div class="row g-2 small text-secondary">
                        <div class="col-sm-6">
                            <span>Nhà trọ:</span> <strong class="text-dark" id="detailProperty">-</strong>
                        </div>
                        <div class="col-sm-6">
                            <span>Trạng thái:</span> <span class="badge bg-success">Còn trống</span>
                        </div>
                        <div class="col-sm-6">
                            <span>Giá thuê:</span> <strong class="text-primary" id="detailPrice">-</strong>
                        </div>
                        <div class="col-sm-6">
                            <span>Tiền cọc:</span> <strong class="text-dark" id="detailDeposit">-</strong>
                        </div>
                        <div class="col-sm-6">
                            <span>Diện tích:</span> <strong class="text-dark" id="detailArea">-</strong> m²
                        </div>
                        <div class="col-sm-6">
                            <span>Số người tối đa:</span> <strong class="text-dark" id="detailMaxPeople">-</strong> người
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Tiêu đề tin đăng</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}" maxlength="200"
                       placeholder="Ví dụ: Phòng trọ đầy đủ tiện nghi gần trường" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="6"
                          placeholder="Mô tả vị trí, tiện nghi và điều kiện thuê..."
                          required>{{ old('description') }}</textarea>
            </div>
    
      
{{-- CHỌN ẢNH PHÒNG --}}
<div class="mb-4">
    <label for="images" class="form-label fw-semibold">
        Hình ảnh phòng trọ
    </label>

    <input
        type="file"
        name="images[]"
        id="images"
        class="form-control"
        accept="image/jpeg,image/png,image/webp"
        multiple
        required
    >

    <small class="text-secondary d-block mt-2">
        Chọn từ 1 đến 10 ảnh, tối đa 5 MB mỗi ảnh.
    </small>

    <div id="imagePreview" class="image-preview-grid mt-3"></div>
</div>



            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select" required>
                    <option value="draft" @selected(old('status', 'draft') === 'draft')>Bản nháp</option>
                    <option value="pending" @selected(old('status') === 'pending')>Gửi duyệt</option>
                    <option value="hidden" @selected(old('status') === 'hidden')>Ẩn tin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Ngày hết hạn (không bắt buộc)</label>
                <input type="date" name="expired_at" class="form-control"
                       min="{{ now()->addDay()->toDateString() }}"
                       value="{{ old('expired_at') }}">
            </div>

            <div class="d-flex justify-content-end gap-2 pt-3 border-top mt-4">
                <a href="{{ route('owner.rental-posts.index') }}" class="btn btn-light px-4 py-2 rounded-3 border fw-semibold">Hủy</a>
                <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold" style="background: #0c2340; border-color: #0c2340;"
                        @disabled($rooms->isEmpty())>
                    Lưu tin đăng
                </button>
            </div>
        </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/tv3/rental_protal/create.js') }}"></script>
@endpush

@endsection