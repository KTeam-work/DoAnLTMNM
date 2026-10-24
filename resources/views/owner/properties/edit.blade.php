
@extends('Layout.landlord')

@section('title', 'Sửa nhà | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/properties/edit.css') }}">

    <style>
        .property-images-section {
            margin-bottom: 28px;
        }

        .property-images-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 16px;
            margin-top: 16px;
        }

        .property-image-item {
            position: relative;
            min-width: 0;
            padding: 10px;
            border: 1px solid #dce5df;
            border-radius: 12px;
            background: #fff;
        }

        .property-image-item img {
            display: block;
            width: 100%;
            height: 130px;
            object-fit: cover;
            border-radius: 8px;
        }

        .property-image-item.is-deleted {
            opacity: .45;
            border-color: #dc3545;
        }

        .property-image-item.is-deleted img {
            filter: grayscale(1);
        }

        .image-thumbnail-label {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            margin-top: 12px;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .image-delete-label {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            margin-top: 10px;
            color: #dc3545;
            font-size: 13px;
            overflow-wrap: anywhere;
        }

        .image-thumbnail-badge {
            display: inline-block;
            margin-top: 8px;
            padding: 4px 8px;
            border-radius: 20px;
            background: #e6f3eb;
            color: #20584f;
            font-size: 11px;
            font-weight: 600;
        }

        .new-image-preview {
            display: none;
        }

        .new-image-preview img {
            margin-bottom: 8px;
        }

        .image-help {
            color: #6b7280;
            font-size: 13px;
            margin-top: 8px;
        }

        .image-empty {
            padding: 20px;
            border: 1px dashed #cbd5ce;
            border-radius: 10px;
            color: #6b7280;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="owner-page">

     

        <main class="edit-container">
                    
                <div class="d-flex justify-content-end mb-3">
                    <a href="{{ route('owner.properties.index') }}" class="btn-back">
                        ← Danh sách nhà
                    </a>
              </div>
            <div class="page-heading">
                <div class="eyebrow">Owner workspace</div>
                <h1>Sửa thông tin nhà trọ</h1>
                <p>Cập nhật thông tin cho nhà trọ: {{ $property->name }}</p>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại thông tin:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="edit-card">

                <div class="edit-card-header">
                    <h2>Thông tin nhà trọ</h2>
                    <p>Các trường có dấu * là bắt buộc.</p>
                </div>

                <div class="edit-card-body">

                    <form
                        action="{{ route('owner.properties.update', $property->id) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        id="editPropertyForm">

                        @csrf
                        @method('PUT')

                        {{-- QUẢN LÝ ẢNH HIỆN TẠI --}}
                        <div class="property-images-section">
                            <label class="form-label">
                                Ảnh nhà trọ hiện tại
                            </label>

                            @if ($property->images->isNotEmpty())
                                <div class="property-images-grid">
                                    @foreach ($property->images as $image)
                                        <div
                                            class="property-image-item"
                                            data-image-item="{{ $image->id }}">

                                            <img
                                                src="{{ asset('storage/' . ltrim($image->image_path, '/')) }}"
                                                alt="Ảnh nhà trọ"
                                                loading="lazy">

                                            @if ($image->is_thumbnail)
                                                <span class="image-thumbnail-badge">
                                                    Ảnh đại diện hiện tại
                                                </span>
                                            @endif

                                            <label class="image-thumbnail-label">
                                                <input
                                                    type="radio"
                                                    name="thumbnail_image"
                                                    value="{{ $image->id }}"
                                                    class="thumbnail-radio"
                                                    {{ old('thumbnail_image', $property->images->firstWhere('is_thumbnail', true)?->id) == $image->id ? 'checked' : '' }}>

                                                <span>Chọn làm ảnh đại diện</span>
                                            </label>

                                            <label class="image-delete-label">
                                                <input
                                                    type="checkbox"
                                                    name="delete_images[]"
                                                    value="{{ $image->id }}"
                                                    class="delete-image-checkbox"
                                                    {{ in_array((string) $image->id, old('delete_images', [])) ? 'checked' : '' }}>

                                                <span>Đánh dấu xóa ảnh này</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="image-empty">
                                    Nhà trọ chưa có ảnh. Mày có thể thêm ảnh mới bên dưới.
                                </div>
                            @endif

                            <p class="image-help">
                                Chọn ảnh đại diện bằng nút tròn. Đánh dấu xóa ảnh nếu không muốn giữ ảnh đó.
                                Ảnh chỉ bị xóa sau khi mày bấm Lưu thay đổi.
                            </p>
                        </div>

                        <hr>

                        {{-- THÊM ẢNH MỚI --}}
                        <div class="mb-4">
                            <label for="images" class="form-label">
                                Thêm ảnh nhà trọ
                            </label>

                            <input
                                type="file"
                                id="images"
                                name="images[]"
                                class="form-control @error('images') is-invalid @enderror @error('images.*') is-invalid @enderror"
                                accept="image/jpeg,image/png,image/webp"
                                multiple>

                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            @error('images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <p class="image-help">
                                Có thể chọn nhiều ảnh JPG, PNG hoặc WEBP. Mỗi ảnh tối đa 5 MB.
                                Bỏ trống nếu không muốn thêm ảnh.
                            </p>

                            <div id="newImagePreview" class="property-images-grid new-image-preview"></div>
                        </div>

                        <hr>

                        {{-- TÊN NHÀ --}}
                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Tên nhà trọ <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $property->name) }}"
                                maxlength="150"
                                required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- LOẠI HÌNH --}}
                        <div class="mb-4">
                            <label for="type" class="form-label">
                                Loại hình <span class="required">*</span>
                            </label>

                            <select
                                id="type"
                                name="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required>

                                <option value="rooming_house"
                                    {{ old('type', $property->type) === 'rooming_house' ? 'selected' : '' }}>
                                    Nhà trọ
                                </option>

                                <option value="apartment"
                                    {{ old('type', $property->type) === 'apartment' ? 'selected' : '' }}>
                                    Căn hộ
                                </option>

                                <option value="house"
                                    {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>
                                    Nhà nguyên căn
                                </option>
                            </select>

                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- ĐỊA CHỈ --}}
                        <div class="mb-4">
                            <label for="address" class="form-label">
                                Địa chỉ chi tiết <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="address"
                                name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address', $property->address) }}"
                                maxlength="255"
                                required>

                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- QUẬN VÀ PHƯỜNG --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="district" class="form-label">
                                    Quận/huyện <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="district"
                                    name="district"
                                    class="form-control @error('district') is-invalid @enderror"
                                    value="{{ old('district', $property->district) }}"
                                    maxlength="100"
                                    required>

                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ward" class="form-label">
                                    Phường/xã <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="ward"
                                    name="ward"
                                    class="form-control @error('ward') is-invalid @enderror"
                                    value="{{ old('ward', $property->ward) }}"
                                    maxlength="100"
                                    required>

                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TỌA ĐỘ --}}
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">Vĩ độ</label>

                                <input
                                    type="number"
                                    step="any"
                                    min="-90"
                                    max="90"
                                    id="latitude"
                                    name="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $property->latitude) }}"
                                    placeholder="Không bắt buộc">

                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="longitude" class="form-label">Kinh độ</label>

                                <input
                                    type="number"
                                    step="any"
                                    min="-180"
                                    max="180"
                                    id="longitude"
                                    name="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $property->longitude) }}"
                                    placeholder="Không bắt buộc">

                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        {{-- TRẠNG THÁI --}}
                        <div class="mb-4">
                            <label for="status" class="form-label">
                                Trạng thái <span class="required">*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>

                                <option value="active"
                                    {{ old('status', $property->status) === 'active' ? 'selected' : '' }}>
                                    Đang hoạt động
                                </option>

                                <option value="inactive"
                                    {{ old('status', $property->status) === 'inactive' ? 'selected' : '' }}>
                                    Tạm ngưng
                                </option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- MÔ TẢ --}}
                        <div class="mb-4">
                            <label for="description" class="form-label">Mô tả</label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Mô tả về nhà trọ...">{{ old('description', $property->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-text mb-4">
                            Thông tin phòng được quản lý riêng trong mục Quản lý phòng.
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <a href="{{ route('owner.properties.index') }}" class="btn-back">
                                Hủy
                            </a>

                            <button type="submit" class="btn-owner">
                                Lưu thay đổi
                            </button>
                        </div>

                    </form>
                </div>
            </section>
        </main>
    </div>
@endsection

@push('scripts')
       <script src="{{ asset('js/tv3/properties/edit.js') }}"></script>
@endpush
