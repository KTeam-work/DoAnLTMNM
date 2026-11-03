@extends('layout.tenant')

@section('title', 'Hợp đồng của tôi | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenant/contracts/index.css') }}">
@endpush

@section('content')

<div class="page-wrap" id="contracts-page" style="padding-top: 30px;">
    
    <!-- HEADER -->
    <div class="page-header d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4 animate-up delay-1">
        <div>
            <h2 class="page-title">Hợp đồng của tôi</h2>
            <div class="page-desc mt-1">Quản lý và theo dõi danh sách hợp đồng thuê phòng của bạn.</div>
        </div>
        <div>
            <a href="{{ route('tenant.maintenance.create') }}" class="btn btn-outline-brand d-inline-flex align-items-center gap-2">
                ＋ Tạo yêu cầu sửa chữa
            </a>
        </div>
    </div>

    <!-- =================================================
         STAT CARDS
    ================================================= -->
    @php
        $totalContracts = $contracts->count();
        $activeContracts = $contracts->where('status', 'active')->count();
        $expiringContracts = $contracts->filter(function($c) {
            return $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60 && $c->status === 'active';
        })->count();
    @endphp

    <div class="row g-3 mb-4 animate-up delay-2">
        <!-- CARD 1: Tổng hợp đồng -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-top mb-3">
                    <div class="stat-icon blue">📋</div>
                    <span class="stat-trend text-primary">Tất cả</span>
                </div>
                <div class="stat-label">Tổng hợp đồng</div>
                <div class="stat-value">{{ str_pad($totalContracts, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- CARD 2: Đang hiệu lực -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-top mb-3">
                    <div class="stat-icon green">✅</div>
                    <span class="stat-trend text-success">Đang thuê</span>
                </div>
                <div class="stat-label">Đang hiệu lực</div>
                <div class="stat-value" style="color: #1b8a5a;">{{ str_pad($activeContracts, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        <!-- CARD 3: Sắp hết hạn -->
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-top mb-3">
                    <div class="stat-icon yellow">⏳</div>
                    <span class="stat-trend text-warning" style="color: #b7791f !important;">Cần lưu ý</span>
                </div>
                <div class="stat-label">Sắp hết hạn</div>
                <div class="stat-value" style="color: #b7791f;">{{ str_pad($expiringContracts, 2, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>
    </div>

    <!-- =================================================
         DANH SÁCH HỢP ĐỒNG
    ================================================= -->
    <div class="content-panel animate-up delay-3">
        <!-- PANEL HEADER -->
        <div class="content-panel-head">
            <div>
                <h3 class="content-panel-title">Danh sách hợp đồng thuê</h3>
                <small class="text-muted">Chi tiết các hợp đồng đã ký kết với chủ nhà</small>
            </div>
            
            <!-- SEARCH INPUT -->
            <div class="search-box-custom">
                <span>🔎</span>
                <input type="text" id="contractSearchInput" placeholder="Tìm mã HĐ, tên phòng, địa chỉ...">
            </div>
        </div>

        <!-- TABLE -->
        <div class="table-responsive">
            <table class="data-table-contracts align-middle" id="contractsTable">
                <thead>
                    <tr>
                        <th style="padding-left: 20px;">MÃ HỢP ĐỒNG</th>
                        <th>PHÒNG TRỌ &amp; KHU VỰC</th>
                        <th>THỜI HẠN THUÊ</th>
                        <th>GIÁ THUÊ</th>
                        <th>TRẠNG THÁI</th>
                        <th class="text-end" style="padding-right: 20px;">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $statusLabels = [
                            'active' => 'Đang hiệu lực',
                            'expired' => 'Hết hạn',
                            'terminated' => 'Đã thanh lý',
                            'draft' => 'Nháp'
                        ];
                    @endphp

                    @forelse($contracts as $contract)
                    <tr class="contract-row">
                        <!-- MÃ HỢP ĐỒNG -->
                        <td style="padding-left: 20px;">
                            <div class="fw-bold" style="color: #20584f; font-size: 14px;">#{{ $contract->contract_code }}</div>
                            <small class="text-muted">Kỳ: {{ $contract->payment_cycle === 'quarterly' ? 'Hàng quý' : 'Hàng tháng' }}</small>
                        </td>

                        <!-- PHÒNG -->
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $contract->room->name ?? '—' }}</div>
                            <div class="text-muted" style="font-size: 12px;">
                                📍 {{ $contract->room->property->name ?? '' }} · {{ $contract->room->property->district ?? '' }}
                            </div>
                        </td>

                        <!-- THỜI HẠN -->
                        <td>
                            <div class="fw-semibold text-dark" style="font-size: 13px;">
                                {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}
                            </div>
                            <div class="text-muted" style="font-size: 12px;">
                                Đến {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                            </div>
                        </td>

                        <!-- GIÁ -->
                        <td>
                            <div class="fw-bold" style="color: #20584f; font-size: 15px;">
                                {{ number_format((float)$contract->rent) }} đ
                            </div>
                            <div class="text-muted" style="font-size: 12px;">
                                Cọc: {{ number_format((float)$contract->deposit) }} đ
                            </div>
                        </td>

                        <!-- TRẠNG THÁI -->
                        <td>
                            <span class="badge-status-contract {{ $contract->status }}">
                                {{ $statusLabels[$contract->status] ?? $contract->status }}
                            </span>
                        </td>

                        <!-- THAO TÁC -->
                        <td class="text-end" style="padding-right: 20px;">
                            <a href="{{ route('tenant.contracts.show', $contract->id) }}" class="btn-view-contract">
                                Xem chi tiết →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">
                            <div style="font-size: 32px; margin-bottom: 8px;">📄</div>
                            <div class="fw-bold text-dark">Bạn chưa có hợp đồng thuê nào.</div>
                            <div class="text-muted small mt-1">Khi bạn ký hợp đồng với chủ nhà, thông tin sẽ xuất hiện tại đây.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Live filter search cho bảng hợp đồng
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('contractSearchInput');
        if (!searchInput) return;

        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            const rows = document.querySelectorAll('#contractsTable tbody tr.contract-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endpush

@endsection
