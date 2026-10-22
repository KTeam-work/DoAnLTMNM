@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/contracts/index.css') }}">
@endpush

@section('content')



    <main class="container-fluid mt-4 mb-5">
        <!-- HEADER -->
        <div class="page-header d-flex justify-content-between align-items-center mb-5 mt-2">
            <div>
                <h2 class="page-title fw-bold text-dark mb-1">
                    Hợp đồng của tôi
                </h2>
                <div class="page-desc text-muted">
                    Quản lý và theo dõi danh sách hợp đồng thuê phòng của bạn.
                </div>
            </div>
            <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm text-decoration-none">
                + Tạo yêu cầu mới
            </a>
        </div>

        <!-- =================================================
             STAT CARDS
        ================================================= -->
        @php
            $totalContracts = $contracts->count();
            $activeContracts = $contracts->where('status', 'active')->count();
            $expiringContracts = $contracts->filter(fn($c) => $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60 && $c->status === 'active')->count();
        @endphp
        <div class="row g-4 mb-5">
            <!-- CARD 1 -->
            <div class="col-6 col-lg-4">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="icon-box bg-blue-soft">📋</div>
                    <div>
                        <div class="text-muted small fw-medium mb-1">
                            Tổng hợp đồng
                        </div>
                        <div class="fs-3 fw-bold text-dark">{{ str_pad($totalContracts, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="col-6 col-lg-4">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="icon-box bg-green-soft">✅</div>
                    <div>
                        <div class="text-muted small fw-medium mb-1">
                            Đang hiệu lực
                        </div>
                        <div class="fs-3 fw-bold text-dark">{{ str_pad($activeContracts, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="col-6 col-lg-4">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="icon-box bg-orange-soft">⏳</div>
                    <div>
                        <div class="text-muted small fw-medium mb-1">
                            Sắp hết hạn <span class="text-danger small fw-bold ms-1">Chú ý</span>
                        </div>
                        <div class="fs-3 fw-bold text-dark">{{ str_pad($expiringContracts, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================================================
             DATA TABLE
        ================================================= -->
        <div class="panel-modern">
            <!-- PANEL HEADER -->
            <div class="panel-head d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Danh sách hợp đồng thuê</h4>
                <!-- SEARCH -->
                <div class="input-group" style="max-width: 280px;">
                    <span class="input-group-text bg-white border-end-0 text-muted border-light-subtle rounded-start-3">
                        🔎
                    </span>
                    <input type="text" class="form-control border-start-0 border-light-subtle rounded-end-3 shadow-none" placeholder="Tìm mã HĐ, tên phòng...">
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Phòng trọ</th>
                            <th>Thời hạn</th>
                            <th>Giá thuê</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                        <tr>
                            <!-- MÃ HỢP ĐỒNG -->
                            <td>
                                <div class="fw-bold text-primary">#{{ $contract->contract_code }}</div>
                            </td>

                            <!-- PHÒNG -->
                            <td>
                                <div class="fw-semibold text-dark mb-1">{{ $contract->room->name ?? '—' }}</div>
                                <div class="small text-muted">
                                    📍 {{ $contract->room->property->address ?? '' }}
                                </div>
                            </td>

                            <!-- THỜI HẠN -->
                            <td>
                                <div class="text-dark fw-medium">{{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}</div>
                                <div class="small text-muted">Đến {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</div>
                            </td>

                            <!-- GIÁ -->
                            <td>
                                <div class="fw-bold text-dark fs-6">{{ number_format((float)$contract->rent) }} đ</div>
                                <div class="small text-muted">/ {{ $contract->payment_cycle === 'quarterly' ? 'quý' : 'tháng' }}</div>
                            </td>

                            <!-- TRẠNG THÁI -->
                            <td>
                                <span class="badge-soft-success">● {{ ['draft' => 'Nháp', 'active' => 'Đang hiệu lực', 'expired' => 'Hết hạn', 'terminated' => 'Đã thanh lý'][$contract->status] ?? $contract->status }}</span>
                            </td>

                            <!-- THAO TÁC -->
                            <td class="text-end">
                                <a href="{{ route('tenant.contracts.show', $contract->id) }}" class="btn btn-action btn-sm px-3 py-2">
                                    Xem chi tiết →
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Bạn chưa có hợp đồng nào.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>


@endsection