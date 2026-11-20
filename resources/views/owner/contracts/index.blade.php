

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

    <!-- BỘ LỌC TRẠNG THÁI (NO ICONS) -->
    @php
        $total = $contracts->count();
        $active = $contracts->where('status', 'active')->count();
        $draft = $contracts->where('status', 'draft')->count();
        $expiring = $contracts->filter(fn($c) => $c->status === 'active' && $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60)->count();
        $terminated = $contracts->where('status', 'terminated')->count();
    @endphp

    <div class="segmented-filter-wrapper mb-4">
        <div class="segmented-filter-bar" role="tablist">
            <button type="button" class="segmented-tab active" data-filter="all">
                <span class="tab-label">Tất cả</span>
                <span class="tab-count">{{ $total }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="active">
                <span class="tab-label">Đang hiệu lực</span>
                <span class="tab-count">{{ $active }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="draft">
                <span class="tab-label">Bản nháp</span>
                <span class="tab-count">{{ $draft }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="expiring">
                <span class="tab-label">Sắp hết hạn</span>
                <span class="tab-count">{{ $expiring }}</span>
            </button>
            <button type="button" class="segmented-tab" data-filter="terminated">
                <span class="tab-label">Đã thanh lý</span>
                <span class="tab-count">{{ $terminated }}</span>
            </button>
        </div>
    </div>

    <!-- BẢNG DANH SÁCH HỢP ĐỒNG -->
    <div class="panel-modern">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-3">
            <h4 class="fw-bold text-dark m-0 fs-5">Danh sách hợp đồng ({{ $total }})</h4>
            <div class="search-input-custom">
                <input type="text" id="ownerContractSearch" placeholder="Tìm kiếm theo mã HĐ, tên phòng, khách thuê...">
            </div>
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
                    @php
                        $isExpiring = $contract->status === 'active' && $contract->end_date && \Carbon\Carbon::parse($contract->end_date)->diffInDays(now(), false) > -60;
                    @endphp
                    <tr class="owner-contract-row"
                        data-status="{{ $contract->status }}"
                        data-expiring="{{ $isExpiring ? '1' : '0' }}">
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
                            @elseif($contract->status === 'draft')
                            <span class="badge-soft-secondary">Bản nháp</span>
                            @elseif($contract->status === 'terminated')
                            <span class="badge-soft-warning">Đã thanh lý</span>
                            @else
                            <span class="badge-soft-warning">{{ $contract->status }}</span>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('owner.contracts.show', $contract) }}" class="btn-action">Xem</a>
                            @if($contract->status === 'draft')
                                <form method="POST" action="{{ route('owner.contracts.activate', $contract) }}" class="d-inline" onsubmit="return confirm('Xác nhận kích hoạt hợp đồng #{{ $contract->contract_code }}? Phòng sẽ chuyển sang Đang thuê.')">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success ms-1 py-1 px-2" style="font-size: 13px;">Kích hoạt</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-5">Chưa có hợp đồng nào.</td>
                    </tr>
                    @endforelse
                    <tr id="ownerNoFilterResultRow" style="display: none;">
                        <td colspan="6" class="text-center text-muted py-4">Không tìm thấy hợp đồng nào phù hợp bộ lọc.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('ownerContractSearch');
        const tabBtns = document.querySelectorAll('.segmented-tab');
        const rows = document.querySelectorAll('.table-modern tbody tr.owner-contract-row');
        const noFilterResultRow = document.getElementById('ownerNoFilterResultRow');

        let currentFilter = 'all';
        let currentQuery = '';

        function applyFilter() {
            let visibleCount = 0;

            rows.forEach(row => {
                const status = row.getAttribute('data-status') || '';
                const isExpiring = row.getAttribute('data-expiring') === '1';
                const text = row.textContent.toLowerCase();

                let matchesStatus = false;
                if (currentFilter === 'all') {
                    matchesStatus = true;
                } else if (currentFilter === 'active') {
                    matchesStatus = (status === 'active');
                } else if (currentFilter === 'draft') {
                    matchesStatus = (status === 'draft');
                } else if (currentFilter === 'expiring') {
                    matchesStatus = isExpiring;
                } else if (currentFilter === 'terminated') {
                    matchesStatus = (status === 'terminated');
                }

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
</div>




@endsection