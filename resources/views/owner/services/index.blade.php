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
            <a href="{{ route('owner.services.create') }}" class="btn-brand">
                <span>＋</span> Thêm dịch vụ
            </a>
        </div>

        {{-- PROPERTY SELECT --}}
        <div class="property-box">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <div class="property-label">Nhà trọ đang xem</div>
                    <div class="property-name">Nhà trọ Q.7</div>
                    <div class="property-address">123 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh</div>
                </div>
                <div class="col-md-5">
                    <select class="property-select">
                        <option>Nhà trọ Q.7</option>
                        <option>Chung cư mini Thủ Đức</option>
                        <option>Nhà trọ Bình Thạnh</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- STATS --}}
        @php
            $totalServices = $services->count();
            $activeServices = $services->where('status', 'active')->count();
            $inactiveServices = $services->where('status', 'inactive')->count();
        @endphp
        <div class="row g-3">
            <div class="col-lg-4 col-md-6">
                <div class="service-stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="service-stat-icon">☷</div>
                        <div>
                            <div class="service-stat-title">Tổng dịch vụ</div>
                            <div class="service-stat-number">{{ $totalServices }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="service-stat-icon">✓</div>
                        <div>
                            <div class="service-stat-title">Đang hoạt động</div>
                            <div class="service-stat-number">{{ $activeServices }}</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-stat-card">
                    <div class="d-flex align-items-center gap-3">
                        <div class="service-stat-icon" style="background:var(--red-soft); color:var(--red);">◷</div>
                        <div>
                            <div class="service-stat-title">Tạm ngưng</div>
                            <div class="service-stat-number">{{ $inactiveServices }}</div>
                        </div>
                    </div>
                </div>
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
                        <tr>
                            <td><span class="service-id">#{{ $service->id }}</span></td>
                            <td>
                                <div class="service-wrapper">
                                    <div class="service-icon">✨</div>
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
                                    <a href="{{ route('owner.services.edit', ['id' => $service->id]) }}" class="btn-action btn-edit">✎ Sửa</a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Chưa có dịch vụ nào. Hãy thêm dịch vụ mới.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>

@endsection