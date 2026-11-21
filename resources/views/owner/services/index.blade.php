@extends('Layout.landlord')

@section('title', 'Dịch vụ | Trọ Ơi')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/services/index.css') }}">
@endpush

@section('content')

    <main class="page-wrap">

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý dịch vụ & tiện ích</h1>
                <div class="page-desc">Quản lý các dịch vụ tính phí và tiện ích phòng áp dụng cho nhà trọ</div>
            </div>
            <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                <span>＋</span> Thêm dịch vụ
            </button>
        </div>

        {{-- NAVIGATION TABS GIỮA DỊCH VỤ VÀ TIỆN ÍCH --}}
        <div style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 2px;">
            <a href="{{ route('owner.services.index') }}" style="padding: 10px 20px; font-weight: 700; font-size: 15px; color: #0c2340; border-bottom: 3px solid #0c2340; text-decoration: none; margin-bottom: -4px;">
                Dịch vụ tính phí
            </a>
            <a href="{{ route('owner.amenities.index') }}" style="padding: 10px 20px; font-weight: 600; font-size: 15px; color: #64748b; text-decoration: none; margin-bottom: -4px; transition: color 0.2s;">
                Tiện ích phòng
            </a>
        </div>

        {{-- PROPERTY SELECT --}}
        <div class="property-box">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="property-label">Nhà trọ đang xem</div>
                    @if($selectedProperty)
                        <div class="property-name">{{ $selectedProperty->name }}</div>
                        <div class="property-address">{{ $selectedProperty->address }}</div>
                    @else
                        <div class="property-name">Chưa có nhà trọ nào</div>
                        <div class="property-address">Vui lòng thêm bất động sản / nhà trọ trước khi cấu hình dịch vụ</div>
                    @endif
                </div>
                <div class="col-md-5 text-md-end">
                    @if($properties->isNotEmpty())
                        <select class="property-select" onchange="if(this.value) window.location.href='{{ route('owner.services.index') }}?property_id=' + this.value">
                            @foreach($properties as $prop)
                                <option value="{{ $prop->id }}" @selected($selectedProperty && $selectedProperty->id === $prop->id)>
                                    {{ $prop->name }}
                                </option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>

        {{-- BỘ LỌC TRẠNG THÁI (NO ICONS) --}}
        @php
            $totalServices = $services->count();
            $activeServices = $services->where('status', 'active')->count();
            $inactiveServices = $services->where('status', 'inactive')->count();
        @endphp
        <div class="segmented-filter-wrapper mb-4">
            <div class="segmented-filter-bar" role="tablist">
                <button type="button" class="segmented-tab active" data-status="">
                    <span class="tab-label">Tất cả</span>
                    <span class="tab-count">{{ $totalServices }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="active">
                    <span class="tab-label">Đang hoạt động</span>
                    <span class="tab-count">{{ $activeServices }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="inactive">
                    <span class="tab-label">Tạm ngưng</span>
                    <span class="tab-count">{{ $inactiveServices }}</span>
                </button>
            </div>
        </div>

        {{-- SERVICE TABLE --}}
        <div class="panel service-panel">
            <div class="service-panel-header">
                <div>
                    <h2 class="service-panel-title">Dịch vụ đang áp dụng</h2>
                    <div class="service-panel-description">Các dịch vụ được áp dụng cho nhà trọ này (dữ liệu thật từ DB)</div>
                </div>
                <span class="service-count">{{ $totalServices }} dịch vụ</span>
            </div>

            @if(session('success'))
                <div class="alert alert-success m-3">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="service-table">
                    <thead>
                        <tr>
                            <th style="width:70px;">ID</th>
                            <th>Tên dịch vụ</th>
                            <th>Mô tả</th>
                            <th>Đơn vị</th>
                            <th>Đơn giá áp dụng</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr class="service-row" data-status="{{ $service->status }}">
                            <td><span class="service-id">#{{ $service->id }}</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-name">{{ $service->name }}</div>
                                </div>
                            </td>
                            <td><div class="service-description">{{ $service->description ?? '—' }}</div></td>
                            <td><span class="unit">{{ $service->unit }}</span></td>
                            <td><span class="price">{{ number_format((float)$service->price) }} ₫</span></td>
                            <td>
                                @if($service->status === 'active')
                                <span class="status-badge status-active">● Đang hoạt động</span>
                                @else
                                <span class="status-badge status-inactive">● Tạm ngưng</span>
                                @endif
                            </td>
                            <td>
                                <div class="action-group">
                                    <a href="{{ route('owner.services.edit', ['id' => $service->id, 'property_id' => $selectedProperty?->id]) }}" class="btn-action btn-edit">Sửa</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Chưa có dịch vụ nào. Hãy thêm dịch vụ mới.</td>
                        </tr>
                        @endforelse
                        <tr id="noServicesFilter" style="display: none;">
                            <td colspan="7" class="text-center text-muted py-4">Không tìm thấy dịch vụ nào phù hợp trạng thái này.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    {{-- MODAL THÊM DỊCH VỤ (MODERN DARK NAVY HEADER) --}}
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Thêm dịch vụ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>

                @if(!isset($properties) || $properties->isEmpty())
                    <div class="modal-body p-4">
                        <div class="alert alert-warning mb-0">
                            Chưa có nhà trọ nào để cấu hình dịch vụ. Vui lòng tạo nhà trọ trước.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Đóng</button>
                    </div>
                @else
                    <form id="modalServiceForm" action="{{ route('owner.services.store') }}" method="POST">
                        @csrf
                        <div class="modal-body p-4">
                            {{-- CHỌN NHÀ TRỌ --}}
                            <div class="mb-3">
                                <label for="modalServicePropertySelect" class="form-label fw-semibold" style="font-size: 13.5px; color: #1e293b;">
                                    Chọn nhà trọ áp dụng <span class="text-danger">*</span>
                                </label>
                                <select id="modalServicePropertySelect" name="property_id" class="form-select" required>
                                    @foreach($properties as $prop)
                                        @php
                                            $serviceCount = \Illuminate\Support\Facades\DB::table('property_services')->where('property_id', $prop->id)->count();
                                        @endphp
                                        <option value="{{ $prop->id }}"
                                            data-name="{{ $prop->name }}"
                                            data-address="{{ $prop->address }}"
                                            data-type="{{ $prop->type ?? 'Nhà trọ' }}"
                                            data-count="{{ $serviceCount }}"
                                            @selected($selectedProperty && $selectedProperty->id === $prop->id)>
                                            {{ $prop->name }} — {{ $prop->address }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- THÔNG TIN TÓM TẮT NHÀ TRỌ --}}
                            <div class="property-summary-card mb-4" id="modalPropertySummary">
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Tên nhà trọ</div>
                                        <div class="info-col-val text-primary" id="modalPropName">{{ $selectedProperty?->name ?? '—' }}</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Loại hình</div>
                                        <div class="info-col-val" id="modalPropType">{{ $selectedProperty?->type ?? 'Nhà trọ' }}</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Dịch vụ hiện có</div>
                                        <div class="info-col-val" id="modalPropCount">{{ $services->count() }} dịch vụ</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Địa chỉ</div>
                                        <div class="info-col-val text-truncate" id="modalPropAddress" title="{{ $selectedProperty?->address ?? '' }}">{{ $selectedProperty?->address ?? '—' }}</div>
                                    </div>
                                </div>
                            </div>

                            {{-- THÔNG TIN DỊCH VỤ --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Tên dịch vụ <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" class="form-control" placeholder="VD: Internet tốc độ cao, Rác..." required maxlength="100">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Đơn vị tính <span class="text-danger">*</span>
                                    </label>
                                    <select name="unit" class="form-select" required>
                                        @foreach(['tháng', 'người', 'chiếc', 'kWh', 'm³'] as $unit)
                                            <option value="{{ $unit }}">{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Đơn giá áp dụng (VNĐ) <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="price" class="form-control" placeholder="VD: 50000" min="0" step="1000" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Trạng thái <span class="text-danger">*</span>
                                    </label>
                                    <select name="status" class="form-select">
                                        <option value="active" selected>Đang hoạt động</option>
                                        <option value="inactive">Tạm ngưng</option>
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Mô tả dịch vụ
                                    </label>
                                    <input type="text" name="description" class="form-control" placeholder="VD: Đã bao gồm phí bảo trì và wifi cáp quang...">
                                </div>
                            </div>

                            <div class="mt-3 p-2 bg-light rounded text-muted" style="font-size: 12.5px;">
                                Dịch vụ sau khi tạo sẽ được liên kết và tính toán riêng cho nhà trọ đã chọn.
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light rounded-3 px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                                Hủy
                            </button>
                            <button type="submit" class="btn text-white rounded-3 px-4 py-2 fw-semibold" style="background: #0c2340; border-color: #0c2340;">
                                Thêm dịch vụ
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.segmented-tab');
        const rows = document.querySelectorAll('.service-table tbody tr.service-row');
        const noResults = document.getElementById('noServicesFilter');

        function filterServices(targetStatus) {
            let visibleCount = 0;
            rows.forEach(row => {
                const status = row.getAttribute('data-status') || '';
                const match = !targetStatus || status === targetStatus;
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            if (noResults) {
                noResults.style.display = (rows.length > 0 && visibleCount === 0) ? '' : 'none';
            }
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const status = this.getAttribute('data-status') || '';
                filterServices(status);
            });
        });

        // Đồng bộ tóm tắt thông tin nhà trọ trong Modal Thêm dịch vụ
        const modalPropSelect = document.getElementById('modalServicePropertySelect');
        if (modalPropSelect) {
            function syncModalProp() {
                const opt = modalPropSelect.options[modalPropSelect.selectedIndex];
                if (!opt) return;
                const nameEl = document.getElementById('modalPropName');
                const typeEl = document.getElementById('modalPropType');
                const countEl = document.getElementById('modalPropCount');
                const addrEl = document.getElementById('modalPropAddress');

                if (nameEl) nameEl.textContent = opt.getAttribute('data-name') || '—';
                if (typeEl) typeEl.textContent = opt.getAttribute('data-type') || 'Nhà trọ';
                if (countEl) countEl.textContent = (opt.getAttribute('data-count') || '0') + ' dịch vụ';
                if (addrEl) {
                    addrEl.textContent = opt.getAttribute('data-address') || '—';
                    addrEl.setAttribute('title', opt.getAttribute('data-address') || '');
                }
            }
            modalPropSelect.addEventListener('change', syncModalProp);
            syncModalProp();
        }
    });
</script>
@endpush

@endsection