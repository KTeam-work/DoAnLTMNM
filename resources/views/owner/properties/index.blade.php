
@extends('Layout.landlord')

@section('title', 'Quản lý nhà trọ | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/properties/index.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
@endpush

@section('content')

<div class="owner-page">
    <main class="owner-main">

        {{-- TIÊU ĐỀ --}}
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

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close"
                    data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger" role="alert">
                <strong>Dữ liệu chưa hợp lệ:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- BỘ LỌC TRẠNG THÁI NHÀ TRỌ (NO ICONS) --}}
        @php
            $totalProperties = $properties->count();
            $activeProperties = $properties->where('status', 'active')->count();
            $inactiveProperties = $properties->where('status', '!=', 'active')->count();
            $hasAvailableRooms = $properties->filter(fn($p) => $p->rooms->where('status', 'available')->count() > 0)->count();
        @endphp

        <div class="segmented-filter-wrapper mb-4">
            <div class="segmented-filter-bar" role="tablist">
                <button type="button" class="segmented-tab active" data-filter="all">
                    <span class="tab-label">Tất cả</span>
                    <span class="tab-count">{{ $totalProperties }}</span>
                </button>
                <button type="button" class="segmented-tab" data-filter="active">
                    <span class="tab-label">Đang hoạt động</span>
                    <span class="tab-count">{{ $activeProperties }}</span>
                </button>
                <button type="button" class="segmented-tab" data-filter="inactive">
                    <span class="tab-label">Tạm ngưng</span>
                    <span class="tab-count">{{ $inactiveProperties }}</span>
                </button>
                <button type="button" class="segmented-tab" data-filter="has_available">
                    <span class="tab-label">Có phòng trống</span>
                    <span class="tab-count">{{ $hasAvailableRooms }}</span>
                </button>
            </div>
        </div>

        {{-- DANH SÁCH NHÀ TRỌ --}}
        <section class="content-card">
            <div class="card-header-owner">
                <div>
                    <h2>Danh sách nhà trọ</h2>
                    <p>Quản lý các bất động sản thuộc tài khoản của bạn.</p>
                </div>
                <div class="d-flex flex-wrap align-items-center gap-2">
                    <input type="search" id="propertySearch" class="form-control"
                        placeholder="Tìm tên nhà, địa chỉ, phường, quận..." aria-label="Tìm nhà trọ"
                        style="width: min(340px, 100%);">
                    <span class="badge rounded-pill text-bg-light" id="propertyResultCount">
                        {{ $properties->count() }} bất động sản
                    </span>
                </div>
            </div>

            <div class="property-grid">
                @forelse ($properties as $property)
                    @php
                        $hasEmptyRooms = $property->rooms->where('status', 'available')->count() > 0;
                    @endphp
                    <article class="property-card"
                        data-status="{{ $property->status }}"
                        data-has-available="{{ $hasEmptyRooms ? '1' : '0' }}"
                        data-search="{{ $property->name }} {{ $property->address }} {{ $property->ward }} {{ $property->district }} {{ $property->type }}">

                        <div class="property-image">
                            @php
                                $thumbPath = $property->thumbnail?->image_path ?? $property->images?->first()?->image_path;
                            @endphp
                            @if ($thumbPath)
                                <img
                                    src="{{ asset('storage/' . ltrim($thumbPath, '/')) }}"
                                    alt="{{ $property->name }}"
                                    loading="lazy">
                            @else
                                <img
                                    src="https://images.unsplash.com/photo-1564013799919-ab600027ffc6?auto=format&fit=crop&w=900&q=80"
                                    alt="{{ $property->name }}"
                                    loading="lazy">
                            @endif

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
                                            Nhà nguyên căn
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
                                <a
                                    class="btn-light-owner"
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
                <div id="noPropertyFilter" class="empty-property" style="display: none; grid-column: 1 / -1;">
                    <div class="fw-bold">Không tìm thấy nhà trọ phù hợp với bộ lọc.</div>
                </div>
            </div>
        </section>
    </main>
</div>

{{-- MODAL THÊM NHÀ TRỌ --}}
<div
    class="modal fade"
    id="propertyModal"
    tabindex="-1"
    aria-labelledby="propertyModalTitle"
    aria-hidden="true">

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
                method="POST"
                enctype="multipart/form-data">

                @csrf

                <div class="modal-body p-4">

                    {{-- TÊN NHÀ TRỌ --}}
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

                    {{-- LOẠI HÌNH --}}
                    <div class="mb-3">
                        <label for="propertyType" class="form-label fw-semibold">
                            Loại hình <span class="required">*</span>
                        </label>

                        <select class="form-select" id="propertyType" name="type" required>
                            <option value="rooming_house"
                                {{ old('type', 'rooming_house') === 'rooming_house' ? 'selected' : '' }}>
                                Nhà trọ
                            </option>
                            <option value="apartment"
                                {{ old('type') === 'apartment' ? 'selected' : '' }}>
                                Căn hộ
                            </option>
                            <option value="house"
                                {{ old('type') === 'house' ? 'selected' : '' }}>
                                Nhà nguyên căn
                            </option>
                        </select>
                    </div>

                    {{-- ĐỊA CHỈ --}}
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

                    {{-- QUẬN/HUYỆN VÀ PHƯỜNG/XÃ --}}
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

                    {{-- TỌA ĐỘ --}}
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
                    
                    {{-- BẢN ĐỒ CHỌN VỊ TRÍ --}}
                    <div class="mt-4">
                        <label class="form-label fw-semibold">
                            📍 Chọn vị trí nhà trọ trên bản đồ
                        </label>

                        <p class="text-muted small">
                            Bấm vào vị trí trên bản đồ để tự động lấy tọa độ.
                        </p>

                        <div
                            id="propertyMap"
                            style="width: 100%; height: 350px; border-radius: 12px;
                                overflow: hidden; background: #f1f5f9;">
                        </div>

                        <div id="mapStatus" class="form-text mt-2">
                            Hãy chọn vị trí nhà trọ trên bản đồ.
                        </div>
                    </div>


                    {{-- TRẠNG THÁI --}}
                    <div class="mb-3 mt-3">
                        <label for="propertyStatus" class="form-label fw-semibold">
                            Trạng thái <span class="required">*</span>
                        </label>

                        <select class="form-select" id="propertyStatus" name="status" required>
                            <option value="active"
                                {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                                Đang hoạt động
                            </option>
                            <option value="inactive"
                                {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                Tạm ngưng
                            </option>
                        </select>
                    </div>

                    {{-- MÔ TẢ --}}
                    <div class="mb-3">
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

                    {{-- HÌNH ẢNH NHÀ TRỌ (CHỌN NHIỀU ẢNH) --}}
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="form-label fw-semibold mb-0">
                                Hình ảnh nhà trọ (Tối đa 10 ảnh)
                            </label>
                            <span class="badge bg-light text-dark border" id="imageCountBadge">0/10 ảnh</span>
                        </div>

                        <!-- Vùng chọn ảnh kéo thả hoặc click -->
                        <div class="p-3 border border-2 border-dashed rounded-3 text-center bg-light" id="dropzoneBox" style="cursor: pointer; border-color: #cbd5e1 !important; transition: all 0.2s;">
                            <div style="font-size: 28px;">📸</div>
                            <div class="fw-bold text-dark mt-1" style="font-size: 13.5px;">Bấm vào đây để chọn ảnh hoặc kéo thả ảnh vào</div>
                            <div class="small text-muted mt-1">Hỗ trợ JPG, PNG, WEBP (tối đa 5MB/ảnh). Bạn có thể chọn nhiều lần để gom nhiều ảnh!</div>
                        </div>

                        <input
                            type="file"
                            name="images[]"
                            id="property_images"
                            class="d-none"
                            accept="image/jpeg,image/png,image/webp"
                            multiple>

                        <div
                            id="imagePreview"
                            class="d-flex flex-wrap gap-2 mt-3">
                        </div>
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

{{-- SCRIPT BẢN ĐỒ OPENSTREETMAP (LEAFLET) VÀ QUẢN LÝ ẢNH --}}
@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="{{ asset('js/tv3/properties/index.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.segmented-tab');
            const cards = document.querySelectorAll('.property-card');
            const noResults = document.getElementById('noPropertyFilter');
            const searchInput = document.getElementById('propertySearch');
            const resultCount = document.getElementById('propertyResultCount');
            let activeFilter = 'all';

            function normalizeText(value) {
                return String(value || '').toLocaleLowerCase('vi')
                    .normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').trim();
            }

            function filterProperties(filter) {
                activeFilter = filter;
                const keyword = normalizeText(searchInput?.value);
                let visibleCount = 0;
                cards.forEach(card => {
                    const status = card.getAttribute('data-status') || '';
                    const hasAvail = card.getAttribute('data-has-available') === '1';
                    const matchesKeyword = normalizeText(card.dataset.search).includes(keyword);

                    let matchesFilter = false;
                    if (filter === 'all') {
                        matchesFilter = true;
                    } else if (filter === 'active') {
                        matchesFilter = (status === 'active');
                    } else if (filter === 'inactive') {
                        matchesFilter = (status !== 'active');
                    } else if (filter === 'has_available') {
                        matchesFilter = hasAvail;
                    }

                    const match = matchesFilter && matchesKeyword;
                    card.style.display = match ? '' : 'none';
                    if (match) visibleCount++;
                });

                if (noResults) {
                    noResults.style.display = (cards.length > 0 && visibleCount === 0) ? '' : 'none';
                }
                if (resultCount) {
                    resultCount.textContent = `Đang hiển thị ${visibleCount}/${cards.length} bất động sản`;
                }
            }

            searchInput?.addEventListener('input', () => filterProperties(activeFilter));

            tabBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    tabBtns.forEach(b => b.classList.remove('active'));
                    this.classList.add('active');
                    const filter = this.getAttribute('data-filter') || 'all';
                    filterProperties(filter);
                });
            });
        });
    </script>
@endpush

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const root = document.querySelector('.owner-page');
        if (!root || root.dataset.propertyFiltersReady) return;
        root.dataset.propertyFiltersReady = '1';

        const tabs = root.querySelectorAll('.segmented-filter-bar .segmented-tab');
        const cards = root.querySelectorAll('.property-card');
        const search = root.querySelector('#propertySearch');
        const empty = root.querySelector('#noPropertyFilter');
        const count = root.querySelector('#propertyResultCount');
        let filter = 'all';

        const normalize = value => String(value || '').toLocaleLowerCase('vi')
            .normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/\u0111/g, 'd').trim();

        function applyFilter() {
            const keyword = normalize(search?.value);
            let visible = 0;
            cards.forEach(card => {
                const status = card.dataset.status || '';
                const matchesTab = filter === 'all'
                    || (filter === 'active' && status === 'active')
                    || (filter === 'inactive' && status !== 'active')
                    || (filter === 'has_available' && card.dataset.hasAvailable === '1');
                const show = matchesTab && normalize(card.dataset.search).includes(keyword);
                card.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (empty) empty.style.display = cards.length && !visible ? '' : 'none';
            if (count) count.textContent = `Đang hiển thị ${visible}/${cards.length} bất động sản`;
        }

        search?.addEventListener('input', applyFilter);
        tabs.forEach(tab => tab.addEventListener('click', function() {
            tabs.forEach(item => item.classList.remove('active'));
            this.classList.add('active');
            filter = this.dataset.filter || 'all';
            applyFilter();
        }));
    });
</script>

@endsection
