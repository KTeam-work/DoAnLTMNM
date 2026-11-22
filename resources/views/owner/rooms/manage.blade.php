
@extends('Layout.landlord')

@section('title', 'Tạo Phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/manage.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/modal_premium.css') }}">
@endpush

@section('content')





    {{-- NỘI DUNG --}}
    <main class="page-wrap">

        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý phòng</h1>
                <div class="page-desc">
                    Quản lý thông tin và trạng thái các phòng thuộc nhà trọ của bạn.
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn-brand btn-brand-light"
                    href="{{ route('owner.rental-posts.index') }}">
                    Quản lý tin đăng
                </a>

                <a class="btn-brand"
                    href="{{ route('owner.rental-posts.create') }}">
                    + Tạo tin đăng
                </a>

                <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#createRoomModal">
                    + Thêm phòng
                </button>
            </div>
        </div>

        {{-- THÔNG BÁO --}}
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

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Dữ liệu chưa hợp lệ!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- BỘ LỌC TRẠNG THÁI (NO ICONS) --}}
        <div class="segmented-filter-wrapper mb-4">
            <div class="segmented-filter-bar" role="tablist">
                <button type="button" class="segmented-tab active" data-status="">
                    <span class="tab-label">Tất cả</span>
                    <span class="tab-count">{{ $rooms->count() }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="available">
                    <span class="tab-label">Còn trống</span>
                    <span class="tab-count">{{ $rooms->where('status', 'available')->count() }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="rented">
                    <span class="tab-label">Đang cho thuê</span>
                    <span class="tab-count">{{ $rooms->where('status', 'rented')->count() }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="maintenance">
                    <span class="tab-label">Bảo trì</span>
                    <span class="tab-count">{{ $rooms->where('status', 'maintenance')->count() }}</span>
                </button>
            </div>
        </div>

        {{-- DANH SÁCH PHÒNG --}}
        <section class="panel">
            <div class="panel-heading">Danh sách phòng</div>
            <div class="panel-subheading">
                Theo dõi giá thuê, sức chứa và trạng thái từng phòng.
            </div>

            <div class="toolbar">
                <input
                    type="search"
                    id="roomSearch"
                    class="search-input"
                    placeholder="Tìm theo tên phòng, mã phòng hoặc nhà trọ..."
                    aria-label="Tìm phòng">

                <select id="propertyFilter" class="filter-select" aria-label="Lọc theo nhà trọ">
                    <option value="">Tất cả nhà trọ</option>
                    @foreach ($properties as $property)
                        <option value="{{ $property->id }}">{{ $property->name }}</option>
                    @endforeach
                </select>

                <select id="statusFilter" class="filter-select" aria-label="Lọc trạng thái">
                    <option value="">Tất cả trạng thái</option>
                    <option value="available">Còn trống</option>
                    <option value="rented">Đang thuê</option>
                    <option value="pending">Đang chờ</option>
                    <option value="maintenance">Đang bảo trì</option>
                    <option value="hidden">Đang ẩn</option>
                </select>
            </div>

            <div class="result-count" id="resultCount">
                Tổng cộng {{ $rooms->count() }} phòng
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Thông tin phòng</th>
                            <th>Nhà trọ</th>
                            <th>Diện tích</th>
                            <th>Giá thuê</th>
                            <th>Sức chứa</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="roomTableBody">
                        @forelse ($rooms as $room)
                            <tr
                                class="room-row"
                                data-search="{{ $room->name }} {{ $room->room_code ?? '' }} {{ $room->property?->name ?? '' }}"
                                data-property="{{ $room->property_id }}"
                                data-status="{{ $room->status }}">

                                <td>
                                    <div class="room-name">{{ $room->name }}</div>
                                    <div class="room-code">
                                        Mã phòng: {{ $room->room_code ?: 'Chưa có mã' }}
                                    </div>
                                </td>

                                <td>{{ $room->property?->name ?? 'Chưa có nhà trọ' }}</td>

                                <td>
                                    {{ $room->area !== null ? number_format((float) $room->area, 2, ',', '.') : 'Chưa cập nhật' }}
                                    @if ($room->area !== null)
                                        m²
                                    @endif
                                </td>

                                <td>
                                    <span class="room-price">
                                        {{ number_format((float) ($room->price ?? 0), 0, ',', '.') }}đ
                                    </span>
                                    <div class="room-code">/ tháng</div>
                                </td>

                                <td>{{ $room->max_people ?? '—' }} người</td>

                                <td>
                                    @switch($room->status)
                                        @case('available')
                                            <span class="badge-status available">Còn trống</span>
                                            @break

                                        @case('rented')
                                            <span class="badge-status rented">Đang thuê</span>
                                            @break

                                        @case('pending')
                                            <span class="badge-status pending">Đang chờ</span>
                                            @break

                                        @case('maintenance')
                                            <span class="badge-status maintenance">Bảo trì</span>
                                            @break

                                        @case('hidden')
                                            <span class="badge-status hidden">Đang ẩn</span>
                                            @break

                                        @default
                                            <span class="badge-status other">
                                                {{ $room->status ?? 'Chưa xác định' }}
                                            </span>
                                    @endswitch
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            class="btn-action btn-detail"
                                            href="{{ route('owner.rooms.show', $room) }}">
                                            Chi tiết
                                        </a>

                                        <a
                                            class="btn-action btn-edit-room"
                                            href="{{ route('owner.rooms.edit', $room) }}">
                                            Sửa
                                        </a>

                                        <form
                                            action="{{ route('owner.rooms.destroy', $room) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng {{ $room->name }} không?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-action btn-delete-room">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRoomsRow">
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">🏠</div>
                                    <div class="fw-bold mb-2">Chưa có phòng nào</div>
                                    <div class="mb-3">
                                        Hãy thêm phòng đầu tiên để bắt đầu quản lý.
                                    </div>
                                    <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#createRoomModal">
                                        + Thêm phòng
                                    </button>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noSearchResults" style="display: none;">
                            <td colspan="7" class="empty-state">
                                Không tìm thấy phòng phù hợp với bộ lọc.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <script>
            if (typeof window.initRoomManage === 'function') {
                window.initRoomManage();
            }
        </script>
    </main>

    <!-- MODAL TẠO PHÒNG MỚI (PREMIUM DARK HEADER NHƯ HÌNH 3) -->
    <div class="modal fade modal-premium" id="createRoomModal" tabindex="-1" aria-labelledby="createRoomModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createRoomModalLabel">Tạo phòng mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                @if ($properties->isEmpty())
                    <div class="modal-body p-4">
                        <div class="alert alert-warning mb-0">Bạn chưa có khu trọ nào. Hãy tạo khu trọ trước khi thêm phòng.</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary px-4 rounded-3" data-bs-dismiss="modal">Đóng</button>
                    </div>
                @else
                    <form method="POST" action="{{ route('owner.rooms.store') }}" id="modalCreateRoomForm">
                        @csrf
                        <div class="modal-body p-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" for="modal_property_id">Khu trọ <span class="text-danger">*</span></label>
                                    <select id="modal_property_id" name="property_id" class="form-select" required>
                                        <option value="">Chọn khu trọ</option>
                                        @foreach ($properties as $property)
                                            <option value="{{ $property->id }}" data-room-code="{{ $property->next_room_code }}" @selected(old('property_id') == $property->id)>
                                                {{ $property->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tên phòng <span class="text-danger">*</span></label>
                                    <input name="name" value="{{ old('name') }}" class="form-control" placeholder="Ví dụ: Phòng 101" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="modal_room_code_preview" class="form-label fw-semibold">Mã phòng</label>
                                    <input id="modal_room_code_preview" class="form-control bg-light" value="" placeholder="Chọn khu trọ để xem mã" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Trạng thái ban đầu</label>
                                    <select name="status" class="form-select">
                                        <option value="available">Còn trống</option>
                                        <option value="maintenance">Đang bảo trì</option>
                                        <option value="hidden">Đang ẩn</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Diện tích (m²) <span class="text-danger">*</span></label>
                                    <input name="area" type="number" min="0.01" step="0.01" value="{{ old('area') }}" class="form-control" placeholder="Ví dụ: 25" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Giá thuê (đ/tháng) <span class="text-danger">*</span></label>
                                    <input id="modal_price" name="price" type="text" class="form-control" placeholder="Ví dụ: 3.000.000" required autocomplete="off">
                                    <div class="form-text text-primary fw-medium" id="modalPricePreview" style="font-size: 11.5px; min-height: 18px;"></div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Tiền đặt cọc (đ) <span class="text-danger">*</span></label>
                                    <input id="modal_deposit" name="deposit" type="text" class="form-control" placeholder="Ví dụ: 3.000.000" required autocomplete="off">
                                    <div class="form-text text-primary fw-medium" id="modalDepositPreview" style="font-size: 11.5px; min-height: 18px;"></div>
                                    <div id="modalDepositError" class="text-danger small mt-1" style="display: none;">Tiền cọc không được lớn hơn tiền thuê.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Sức chứa tối đa (người) <span class="text-danger">*</span></label>
                                    <input name="max_people" type="number" min="1" value="{{ old('max_people', 1) }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Tầng</label>
                                    <input name="floor" type="number" min="0" value="{{ old('floor') }}" class="form-control" placeholder="Để trống nếu không áp dụng">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Mô tả phòng</label>
                                    <textarea name="description" class="form-control" rows="2" placeholder="Nhập thêm thông tin mô tả chi tiết phòng nếu có...">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <button type="button" class="btn btn-light px-4 rounded-3 border fw-semibold" data-bs-dismiss="modal">Hủy</button>
                            <button type="submit" class="btn btn-primary px-4 rounded-3 fw-bold" style="background: #0c2340; border-color: #0c2340;">
                                Thêm phòng mới
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

  

 
@push('scripts')
    <script src="{{ asset('js/tv3/rooms/manage.js') }}"></script>
    <script>
        if (typeof window.initRoomManage === 'function') {
            window.initRoomManage();
        }

        document.addEventListener('DOMContentLoaded', function () {
            var propSel = document.getElementById('modal_property_id');
            var codeInp = document.getElementById('modal_room_code_preview');
            if (propSel && codeInp) {
                function updateCode() {
                    var opt = propSel.options[propSel.selectedIndex];
                    codeInp.value = opt ? (opt.getAttribute('data-room-code') || '') : '';
                }
                propSel.addEventListener('change', updateCode);
                updateCode();
            }

            function setupCurFmt(inpId, prevId) {
                var inp = document.getElementById(inpId);
                var prev = document.getElementById(prevId);
                if (!inp || !prev) return;
                inp.addEventListener('input', function () {
                    var raw = inp.value.replace(/\D/g, '');
                    if (!raw) { prev.textContent = ''; return; }
                    var num = Number(raw);
                    inp.value = num.toLocaleString('vi-VN');
                    if (num >= 1e6) {
                        prev.textContent = '≈ ' + (num / 1e6).toLocaleString('vi-VN', {maximumFractionDigits: 2}) + ' triệu đồng';
                    } else if (num >= 1e3) {
                        prev.textContent = '≈ ' + (num / 1e3).toLocaleString('vi-VN', {maximumFractionDigits: 2}) + ' nghìn đồng';
                    } else {
                        prev.textContent = '≈ ' + num.toLocaleString('vi-VN') + ' đồng';
                    }
                });
            }
            setupCurFmt('modal_price', 'modalPricePreview');
            setupCurFmt('modal_deposit', 'modalDepositPreview');
        });
    </script>
@endpush


@endsection
