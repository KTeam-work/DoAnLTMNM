
@extends('Layout.landlord')

@section('title', 'Tạo Nhà | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/properties/index.css') }}">
@endpush

@section('content')


    <div class="owner-page">

 

        <main class="owner-main">

            <!-- TIÊU ĐỀ -->
            <div class="page-heading">
                <div>
                    <div class="eyebrow">Owner workspace</div>
                    <h1>Quản lý nhà trọ</h1>
                    <p>Theo dõi và quản lý toàn bộ bất động sản của bạn.</p>
                </div>

                <button
                    class="btn-owner"
                    type="button"
                    data-bs-toggle="modal"
                    data-bs-target="#propertyModal">
                    ＋ Thêm nhà trọ
                </button>
            </div>

            <!-- THÔNG BÁO -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Dữ liệu chưa hợp lệ:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- THỐNG KÊ -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Nhà trọ</span>
                        <div class="stat-icon">🏠</div>
                    </div>
                    <strong>{{ $properties->count() }}</strong>
                    <span>Nhà trọ đang quản lý</span>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Tổng phòng</span>
                        <div class="stat-icon">🚪</div>
                    </div>
                    <strong>{{ $properties->sum('rooms_count') }}</strong>
                    <span>Tổng số phòng thuộc các nhà</span>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Phòng trống</span>
                        <div class="stat-icon">🔑</div>
                    </div>
                    <strong>—</strong>
                    <span>Cần đối chiếu trạng thái bảng rooms</span>
                </div>

                <div class="stat-card">
                    <div class="stat-top">
                        <span>Đang thuê</span>
                        <div class="stat-icon">👥</div>
                    </div>
                    <strong>—</strong>
                    <span>Cần đối chiếu trạng thái bảng rooms</span>
                </div>

            </div>

            <!-- DANH SÁCH NHÀ TRỌ -->
            <section class="content-card">

                <div class="card-header-owner">
                    <div>
                        <h2>Danh sách nhà trọ</h2>
                        <p>Quản lý các bất động sản thuộc tài khoản của bạn.</p>
                    </div>

                    <span class="badge rounded-pill text-bg-light">
                        {{ $properties->count() }} bất động sản
                    </span>
                </div>

                <div class="property-grid">

                    @forelse ($properties as $property)
                    <article class="property-card">

                        <div class="property-image">
                            <img
                                src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80"
                                alt="{{ $property->name }}">

                            <span class="property-status {{ $property->status === 'inactive' ? 'inactive' : '' }}">
                                {{ $property->status === 'active' ? 'Đang hoạt động' : 'Tạm ngưng' }}
                            </span>
                        </div>

                        <div class="property-body">

                            <h3>{{ $property->name }}</h3>

                            <div class="property-address">
                                📍 {{ $property->address }},
                                {{ $property->ward }},
                                {{ $property->district }}
                            </div>

                            <div class="property-meta">
                                <div>
                                    <strong>{{ $property->rooms_count }}</strong>
                                    <span>Số phòng</span>
                                </div>

                                <div>
                                    <strong>
                                        @if ($property->type === 'rooming_house')
                                        Nhà trọ
                                        @elseif ($property->type === 'apartment')
                                        Căn hộ
                                        @else
                                        Nhà
                                        @endif
                                    </strong>
                                    <span>Loại hình</span>
                                </div>

                                <div>
                                    <strong>{{ $property->status === 'active' ? 'Có' : 'Không' }}</strong>
                                    <span>Hoạt động</span>
                                </div>
                            </div>

                            @if ($property->description)
                            <div class="property-description">
                                {{ $property->description }}
                            </div>
                            @endif

                            <div class="property-actions mt-3">

                                <a class="btn-light-owner"
                                    href="{{ route('owner.properties.show', $property->id) }}">
                                    Chi tiết
                                </a>

                                <a
                                    class="btn-light-owner"
                                    href="{{ route('owner.properties.edit', $property) }}">
                                    Sửa
                                </a>

                                <form
                                    action="{{ route('owner.properties.destroy', $property) }}"
                                    method="POST"
                                    onsubmit="return confirm('Em có chắc muốn xóa nhà trọ này không?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-delete-owner">
                                        Xóa
                                    </button>
                                </form>

                            </div>
                        </div>
                    </article>

                    @empty
                    <div class="empty-property">
                        <div class="empty-property-icon">🏠</div>
                        <strong>Chưa có nhà trọ nào</strong>
                        <div class="mt-2">
                            Bấm “Thêm nhà trọ” để bắt đầu quản lý bất động sản của bạn.
                        </div>
                    </div>
                    @endforelse

                </div>
            </section>

        </main>
    </div>

    <!-- MODAL THÊM NHÀ TRỌ -->
    <div class="modal fade" id="propertyModal" tabindex="-1" aria-labelledby="propertyModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="propertyModalTitle">
                        Thêm nhà trọ
                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Đóng">
                    </button>
                </div>

                <form
                    id="propertyForm"
                    action="{{ route('owner.properties.store') }}"
                    method="POST">
                    @csrf

                    <div class="modal-body p-4">

                        <div class="mb-3">
                            <label for="propertyName" class="form-label fw-semibold">
                                Tên nhà trọ <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="propertyName"
                                name="name"
                                value="{{ old('name') }}"
                                maxlength="150"
                                placeholder="VD: Nhà trọ Nguyễn Thị Thập"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="propertyType" class="form-label fw-semibold">
                                Loại hình <span class="required">*</span>
                            </label>

                            <select class="form-select" id="propertyType" name="type" required>
                                <option value="rooming_house" {{ old('type', 'rooming_house') === 'rooming_house' ? 'selected' : '' }}>
                                    Nhà trọ
                                </option>
                                <option value="apartment" {{ old('type') === 'apartment' ? 'selected' : '' }}>
                                    Căn hộ
                                </option>
                                <option value="house" {{ old('type') === 'house' ? 'selected' : '' }}>
                                    Nhà nguyên căn
                                </option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="propertyAddress" class="form-label fw-semibold">
                                Địa chỉ chi tiết <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                class="form-control"
                                id="propertyAddress"
                                name="address"
                                value="{{ old('address') }}"
                                placeholder="Số nhà, tên đường"
                                required>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="propertyDistrict" class="form-label fw-semibold">
                                    Quận/huyện <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="propertyDistrict"
                                    name="district"
                                    value="{{ old('district') }}"
                                    maxlength="100"
                                    placeholder="VD: Quận 7"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label for="propertyWard" class="form-label fw-semibold">
                                    Phường/xã <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    class="form-control"
                                    id="propertyWard"
                                    name="ward"
                                    value="{{ old('ward') }}"
                                    maxlength="100"
                                    placeholder="VD: Tân Phong"
                                    required>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label for="propertyLatitude" class="form-label fw-semibold">
                                    Vĩ độ (không bắt buộc)
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    class="form-control"
                                    id="propertyLatitude"
                                    name="latitude"
                                    value="{{ old('latitude') }}"
                                    min="-90"
                                    max="90"
                                    placeholder="VD: 10.729">
                            </div>

                            <div class="col-md-6">
                                <label for="propertyLongitude" class="form-label fw-semibold">
                                    Kinh độ (không bắt buộc)
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    class="form-control"
                                    id="propertyLongitude"
                                    name="longitude"
                                    value="{{ old('longitude') }}"
                                    min="-180"
                                    max="180"
                                    placeholder="VD: 106.721">
                            </div>
                        </div>

                        <div class="mb-3 mt-3">
                            <label for="propertyStatus" class="form-label fw-semibold">
                                Trạng thái <span class="required">*</span>
                            </label>

                            <select class="form-select" id="propertyStatus" name="status" required>
                                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                    Đang hoạt động
                                </option>
                                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                    Tạm ngưng
                                </option>
                            </select>
                        </div>

                        <div class="mb-2">
                            <label for="propertyDescription" class="form-label fw-semibold">
                                Mô tả
                            </label>

                            <textarea
                                class="form-control"
                                id="propertyDescription"
                                name="description"
                                rows="3"
                                placeholder="Mô tả về nhà trọ...">{{ old('description') }}</textarea>
                        </div>

                        <div class="form-text">
                            Số lượng phòng sẽ được quản lý riêng trong mục “Quản lý phòng”.
                        </div>

                    </div>

                    <div class="modal-footer border-0 px-4 pb-4">
                        <button
                            type="button"
                            class="btn btn-light rounded-3"
                            data-bs-dismiss="modal">
                            Hủy
                        </button>

                        <button type="submit" class="btn-owner">
                            Lưu nhà trọ
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

 


@push('scripts')
    <script src="{{ asset('js/tv3/properties/index.js') }}"></script>
@endpush


@endsection
