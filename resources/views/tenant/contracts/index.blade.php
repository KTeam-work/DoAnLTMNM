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
         SEGMENTED FILTER TABS (NO ICONS)
    ================================================= -->
    @php
        $totalContracts = $contracts->count();
        $activeContracts = $contracts->where('status', 'active')->count();
        $expiringContracts = $contracts->filter(function($c) {
            return $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60 && $c->status === 'active';
        })->count();
        $terminatedContracts = $contracts->whereIn('status', ['terminated', 'expired'])->count();
    @endphp

    <div class="segmented-filter-wrapper mb-4 animate-up delay-2">
        <div class="segmented-filter-bar" role="tablist">
            <button type="button" class="segmented-tab active" data-filter="all">
                <span class="tab-label">Tất cả</span>
                <span class="tab-count">{{ $totalContracts }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="active">
                <span class="tab-label">Đang hiệu lực</span>
                <span class="tab-count">{{ $activeContracts }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="expiring">
                <span class="tab-label">Sắp hết hạn</span>
                <span class="tab-count">{{ $expiringContracts }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="terminated">
                <span class="tab-label">Đã kết thúc</span>
                <span class="tab-count">{{ $terminatedContracts }}</span>
            </button>
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
                    @php
                        $isExpiring = $contract->end_date && \Carbon\Carbon::parse($contract->end_date)->diffInDays(now(), false) > -60 && $contract->status === 'active';
                    @endphp
                    <tr class="contract-row" 
                        data-status="{{ $contract->status }}"
                        data-expiring="{{ $isExpiring ? '1' : '0' }}">
                        <!-- MÃ HỢP ĐỒNG -->
                        <td style="padding-left: 20px;">
                            <div class="fw-bold" style="color: #0c2340; font-size: 14px;">#{{ $contract->contract_code }}</div>
                            <small class="text-muted">Kỳ: {{ $contract->payment_cycle === 'quarterly' ? 'Hàng quý' : 'Hàng tháng' }}</small>
                        </td>

                        <!-- PHÒNG -->
                        <td>
                            <div class="fw-bold text-dark" style="font-size: 14px;">{{ $contract->room->name ?? '—' }}</div>
                            <div class="text-muted" style="font-size: 12px;">
                                {{ $contract->room->property->name ?? '' }} · {{ $contract->room->property->district ?? '' }}
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
                            <div class="fw-bold" style="color: #0c2340; font-size: 15px;">
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
                            <div class="fw-bold text-dark">Bạn chưa có hợp đồng thuê nào.</div>
                            <div class="text-muted small mt-1">Khi bạn ký hợp đồng với chủ nhà, thông tin sẽ xuất hiện tại đây.</div>
                        </td>
                    </tr>
                    @endforelse
                    <tr id="noFilterResultRow" style="display: none;">
                        <td colspan="6" class="text-center text-muted py-4">
                            Không tìm thấy hợp đồng nào phù hợp bộ lọc.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('contractSearchInput');
        const tabBtns = document.querySelectorAll('.segmented-tab');
        const rows = document.querySelectorAll('#contractsTable tbody tr.contract-row');
        const noFilterResultRow = document.getElementById('noFilterResultRow');

        let currentFilter = 'all';
        let currentQuery = '';

        function applyFilter() {
            let visibleCount = 0;

            rows.forEach(row => {
                const status = row.getAttribute('data-status') || '';
                const isExpiring = row.getAttribute('data-expiring') === '1';
                const text = row.textContent.toLowerCase();

                // Status check
                let matchesStatus = false;
                if (currentFilter === 'all') {
                    matchesStatus = true;
                } else if (currentFilter === 'active') {
                    matchesStatus = (status === 'active');
                } else if (currentFilter === 'expiring') {
                    matchesStatus = isExpiring;
                } else if (currentFilter === 'terminated') {
                    matchesStatus = (status === 'terminated' || status === 'expired');
                }

                // Search check
                const matchesSearch = !currentQuery || text.includes(currentQuery);

                if (matchesStatus && matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noFilterResultRow) {
                noFilterResultRow.style.display = (rows.length > 0 && visibleCount === 0) ? '' : 'none';
            }
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.getAttribute('data-filter') || 'all';
                applyFilter();
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                currentQuery = this.value.toLowerCase().trim();
                applyFilter();
            });
        }
    });
</script>
@endpush

@endsection
