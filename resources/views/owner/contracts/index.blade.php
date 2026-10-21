

@extends('Layout.landlord')

@section('title', 'Quản lý hợp đồng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/contracts/index.css') }}">
@endpush

@section('content')


<!-- MAIN CONTENT -->
<div class="page-wrap mt-4 mb-5">
    
    <div class="page-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold text-dark mb-1">Quản lý Hợp đồng</h2>
            <div class="page-desc text-muted">Quản lý danh sách hợp đồng thuê phòng của khách.</div>
        </div>
        <a href="{{ route('owner.contracts.create') }}" class="btn btn-success rounded-pill px-4 py-2 fw-medium shadow-sm text-decoration-none">+ Tạo hợp đồng mới</a>
    </div>

    <!-- THỐNG KÊ -->
    @php
        $total = $contracts->count();
        $active = $contracts->where('status', 'active')->count();
        $expiring = $contracts->filter(fn($c) => $c->status === 'active' && $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60)->count();
        $terminated = $contracts->where('status', 'terminated')->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-blue-soft">📋</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Tổng hợp đồng</div>
                    <div class="fs-4 fw-bold text-dark">{{ $total }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-green-soft">✅</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Đang hiệu lực</div>
                    <div class="fs-4 fw-bold text-dark">{{ $active }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-orange-soft">⏳</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Sắp hết hạn</div>
                    <div class="fs-4 fw-bold text-dark">{{ $expiring }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="stat-card-modern d-flex align-items-center gap-3">
                <div class="icon-box bg-red-soft">🔒</div>
                <div>
                    <div class="text-muted small fw-medium mb-1">Đã thanh lý</div>
                    <div class="fs-4 fw-bold text-dark">{{ $terminated }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- BẢNG DANH SÁCH HỢP ĐỒNG -->
    <div class="panel-modern">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold text-dark m-0 fs-5">Danh sách hợp đồng ({{ $total }})</h4>
        </div>
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="table-responsive">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Mã HĐ</th>
                        <th>Phòng / Nhà</th>
                        <th>Khách đại diện</th>
                        <th>Thời hạn</th>
                        <th>Trạng thái</th>
                        <th class="text-end">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($contracts as $contract)
                    <tr>
                        <td><strong class="text-primary">#{{ $contract->contract_code }}</strong></td>
                        <td>
                            <div class="fw-bold text-dark">{{ $contract->room->name ?? '—' }}</div>
                            <div class="small text-muted">{{ $contract->room->property->name ?? '' }}</div>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $contract->tenant->name ?? '—' }}</div>
                            <div class="small text-muted">{{ $contract->tenant->phone ?? '' }}</div>
                        </td>
                        <td>
                            <div class="text-dark">{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}</div>
                            <div class="small text-muted">Đến {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</div>
                        </td>
                        <td>
                            @if($contract->status === 'active')
                            <span class="badge-soft-success">Đang hiệu lực</span>
                            @elseif($contract->status === 'terminated')
                            <span class="badge-soft-warning">Đã thanh lý</span>
                            @else
                            <span class="badge-soft-warning">{{ $contract->status }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('owner.contracts.show', $contract) }}" class="btn-action">Xem</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Chưa có hợp đồng nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>




@endsection