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
                <h1 class="page-title">Quản lý dịch vụ</h1>
                <div class="page-desc">Quản lý các dịch vụ và đơn giá áp dụng cho nhà trọ</div>
            </div>
            <a href="{{ route('owner.services.create', ['property_id' => $selectedProperty?->id]) }}" class="btn-brand">
                <span>＋</span> Thêm dịch vụ
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
                                    <a href="{{ route('owner.services.edit', ['id' => $service->id]) }}" class="btn-action btn-edit">Sửa</a>
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
    });
</script>
@endpush

@endsection