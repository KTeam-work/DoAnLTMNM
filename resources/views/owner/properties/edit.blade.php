@extends('Layout.landlord')

@section('title', 'Sửa nhà | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/properties/edit.css') }}">
@endpush

@section('content')


    <div class="owner-page">

        <nav class="navbar navbar-expand-lg app-navbar">
            <div class="container-fluid">
                <a class="logo" href="{{ route('landlord.dashboard') }}">
                    Trọ <span>Ơi</span>
                </a>

                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('owner.properties.index') }}" class="btn-back">
                        ← Danh sách nhà
                    </a>
                </div>
            </div>
        </nav>

        <main class="edit-container">

            <div class="page-heading">
                <div class="eyebrow">Owner workspace</div>
                <h1>Sửa thông tin nhà trọ</h1>
                <p>Cập nhật thông tin cho nhà trọ: {{ $property->name }}</p>
            </div>

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

                    <form action="{{ route('owner.properties.update', $property->id) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

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

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">
                                    Vĩ độ
                                </label>

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
                                <label for="longitude" class="form-label">
                                    Kinh độ
                                </label>

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

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Mô tả
                            </label>

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
