
@extends('Layout.landlord')

@section('title', 'Quản lý người thuê | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenants.css') }}?v={{ file_exists(public_path('css/tv3/tenants.css')) ? filemtime(public_path('css/tv3/tenants.css')) : time() }}">
@endpush

@section('content')



    <div class="page-wrap mt-4 mb-5">


        <!-- PAGE HEADER -->

        <div
            class="page-header d-flex justify-content-between align-items-center mb-4"
        >

            <div>

                <h2 class="page-title">
                    Quản lý người thuê
                </h2>

                <div class="page-desc">

                    Theo dõi thông tin khách đang thuê và
                    lịch sử lưu trú.

                </div>

            </div>


            <!--
                Thêm người ở cùng qua Popup Modal
            -->

            <button
                type="button"
                class="btn-add-member"
                data-bs-toggle="modal"
                data-bs-target="#addMemberModal"
            >
                + Thêm người ở cùng
            </button>

        </div>

        {{-- THÔNG BÁO FLASH --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif



        <!-- =====================================================
             STAT CARDS
        ===================================================== -->

        @php
            $totalTenants = $tenants->count() + $tenants->reduce(function ($carry, $t) {
                return $carry + optional($t->rentedContracts)->flatMap->members->count();
            }, 0);
            $staying = $tenants->count();
        @endphp
        <!-- =====================================================
             SEGMENTED FILTER TABS (NO ICONS)
        ===================================================== -->
        @php
            $expiringCount = $tenants->filter(fn($t) => ($c = $t->rentedContracts->first()) && $c->status === 'active' && $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60)->count();
            $activeContractsCount = $tenants->flatMap->rentedContracts->where('status', 'active')->count();
        @endphp

        <div class="segmented-filter-wrapper mb-4">
            <div class="segmented-filter-bar" role="tablist">
                <button type="button" class="segmented-tab active" data-filter="all">
                    <span class="tab-label">Tất cả</span>
                    <span class="tab-count">{{ $totalTenants }}</span>
                </button>
                <button type="button" class="segmented-tab" data-filter="staying">
                    <span class="tab-label">Đang lưu trú</span>
                    <span class="tab-count">{{ $staying }}</span>
                </button>
                <button type="button" class="segmented-tab" data-filter="expiring">
                    <span class="tab-label">Sắp hết hạn HĐ</span>
                    <span class="tab-count">{{ $expiringCount }}</span>
                </button>
                <button type="button" class="segmented-tab" data-filter="active_contract">
                    <span class="tab-label">HĐ hiệu lực</span>
                    <span class="tab-count">{{ $activeContractsCount }}</span>
                </button>
            </div>
        </div>



        <!-- =====================================================
             TABLE PANEL
        ===================================================== -->

        <div class="panel-modern">


            <!-- TABLE HEADER -->

            <div
                class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3"
            >

                <h4 class="fw-bold text-dark m-0 fs-5">
                    Danh sách người đang lưu trú
                </h4>


                <div class="d-flex gap-2">


                    <!-- FILTER -->

                    <select
                        class="form-select form-select-sm border-light-subtle shadow-none"
                        style="width: 150px;"
                    >

                        <option value="">
                            Tất cả trạng thái
                        </option>

                        <option value="active">
                            Đang ở
                        </option>

                        <option value="leaving">
                            Sắp chuyển đi
                        </option>

                    </select>


                    <!-- SEARCH -->

                    <div
                        class="input-group input-group-sm"
                        style="width: 250px;"
                    >

                        <input
                            type="text"
                            id="tenantSearchInput"
                            class="form-control border-light-subtle shadow-none"
                            placeholder="Tìm tên, SĐT, CCCD, phòng..."
                        >

                    </div>

                </div>

            </div>



            <!-- =================================================
                 TABLE
            ================================================= -->

            <div class="table-responsive">

                <table class="table-modern">

                    <thead>

                        <tr>

                            <th>
                                Người ở
                            </th>

                            <th>
                                Phòng
                            </th>

                            <th>
                                Vai trò
                            </th>

                            <th>
                                Thông tin liên hệ
                            </th>

                            <th>
                                Ngày vào ở
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th class="text-end">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>
                        @forelse($tenants as $tenant)
                            @php
                                $contract = $tenant->rentedContracts->first();
                                $isExp = $contract && $contract->status === 'active' && $contract->end_date && \Carbon\Carbon::parse($contract->end_date)->diffInDays(now(), false) > -60;
                                $hasActive = $contract && $contract->status === 'active';
                            @endphp
                            <tr class="tenant-row" data-expiring="{{ $isExp ? '1' : '0' }}" data-active-contract="{{ $hasActive ? '1' : '0' }}">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-blue-soft">
                                            {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $tenant->name }}</div>
                                            <div class="small text-muted">{{ $tenant->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-1">{{ $contract?->room?->name ?? '—' }}</div>
                                    <div class="small text-muted">{{ $contract?->contract_code ?? '' }}</div>
                                </td>
                                <td><span class="badge-soft-primary">Người đứng tên HĐ</span></td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $tenant->phone ?? '—' }}</div>
                                    <div class="small text-muted">{{ $tenant->email }}</div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $contract ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '—' }}</div>
                                    <div class="small text-muted">HĐ: {{ $contract?->contract_code ?? '—' }}</div>
                                </td>
                                <td><span class="badge-soft-success">● Đang ở</span></td>
                                <td class="text-end">
                                    <a href="{{ route('owner.tenants.show', $tenant->id) }}" class="btn btn-action text-decoration-none">Hồ sơ</a>
                                </td>
                            </tr>
                            @foreach($contract?->members ?? [] as $member)
                            <tr class="tenant-row" data-expiring="{{ $isExp ? '1' : '0' }}" data-active-contract="{{ $hasActive ? '1' : '0' }}">
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-purple-soft">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $member->name }}</div>
                                            <div class="small text-muted">{{ $member->relationship ?? 'Người ở cùng' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-1">{{ $contract?->room?->name ?? '—' }}</div>
                                    <div class="small text-muted">{{ $contract?->contract_code ?? '' }}</div>
                                </td>
                                <td><span class="badge-soft-secondary">Người ở cùng</span></td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $member->phone ?? '—' }}</div>
                                    <div class="small text-muted">CCCD: {{ $member->identity_card }}</div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $member->created_at?->format('d/m/Y') ?? '—' }}</div>
                                    <div class="small text-muted">HĐ: {{ $contract?->contract_code ?? '—' }}</div>
                                </td>
                                <td><span class="badge-soft-success">● Đang ở</span></td>
                                <td class="text-end"><span class="text-muted small">—</span></td>
                            </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-5">Chưa có người thuê nào.</td></tr>
                        @endforelse
                        <tr id="noTenantFilterResult" style="display: none;">
                            <td colspan="7" class="text-center text-muted py-4">Không tìm thấy người thuê nào phù hợp.</td>
                        </tr>
                    </tbody>

                </table>

            </div>

        </div>

    </div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('tenantSearchInput');
        const tabBtns = document.querySelectorAll('.segmented-tab');
        const rows = document.querySelectorAll('.table-modern tbody tr.tenant-row');
        const noResults = document.getElementById('noTenantFilterResult');

        let currentFilter = 'all';
        let currentQuery = '';

        function applyFilter() {
            let visibleCount = 0;

            rows.forEach(row => {
                const isExpiring = row.getAttribute('data-expiring') === '1';
                const hasActive = row.getAttribute('data-active-contract') === '1';
                const text = row.textContent.toLowerCase();

                let matchesFilter = false;
                if (currentFilter === 'all' || currentFilter === 'staying') {
                    matchesFilter = true;
                } else if (currentFilter === 'expiring') {
                    matchesFilter = isExpiring;
                } else if (currentFilter === 'active_contract') {
                    matchesFilter = hasActive;
                }

                const matchesSearch = !currentQuery || text.includes(currentQuery);

                if (matchesFilter && matchesSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (noResults) {
                noResults.style.display = (rows.length > 0 && visibleCount === 0) ? '' : 'none';
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

        // Đồng bộ dữ liệu tóm tắt hợp đồng trong Modal
        function syncModalContract() {
            var select = document.getElementById('modalContractSelect');
            var form = document.getElementById('modalMemberForm');
            if (!select || !form) return;
            var opt = select.options[select.selectedIndex];
            if (!opt) return;
            form.action = "{{ url('/owner/contracts') }}/" + opt.value + "/members";
            var hidden = document.getElementById('modalContractIdHidden');
            if (hidden) hidden.value = opt.value;
            var infoCode = document.getElementById('modalInfoCode');
            if (infoCode) infoCode.textContent = '#' + (opt.getAttribute('data-code') || '');
            var infoTenant = document.getElementById('modalInfoTenant');
            if (infoTenant) infoTenant.textContent = opt.getAttribute('data-tenant') || '—';
            var infoRoom = document.getElementById('modalInfoRoom');
            if (infoRoom) infoRoom.textContent = opt.getAttribute('data-room') || '—';
            var infoCount = document.getElementById('modalInfoCount');
            if (infoCount) {
                infoCount.textContent = (1 + parseInt(opt.getAttribute('data-count') || '0', 10)) + ' / ' + (opt.getAttribute('data-max') || '?') + ' người';
            }
        }

        var modalContractSelect = document.getElementById('modalContractSelect');
        if (modalContractSelect) {
            modalContractSelect.addEventListener('change', syncModalContract);
            syncModalContract();
        }
    });
</script>
@endpush

    {{-- MODAL THÊM NGƯỜI Ở CÙNG (MODERN DARK NAVY HEADER) --}}
    <div
        class="modal fade"
        id="addMemberModal"
        tabindex="-1"
        aria-labelledby="addMemberModalLabel"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="addMemberModalLabel">
                        Thêm người ở cùng
                    </h5>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Đóng"
                    ></button>
                </div>

                @if(!isset($contracts) || $contracts->isEmpty())
                    <div class="modal-body p-4">
                        <div class="alert alert-warning mb-0">
                            Chưa có hợp đồng nào đang hiệu lực để thêm người ở cùng.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">Đóng</button>
                    </div>
                @else
                    <form id="modalMemberForm" action="#" method="POST">
                        @csrf
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="contract_id" id="modalContractIdHidden" value="">

                        <div class="modal-body p-4">
                            {{-- CHỌN HỢP ĐỒNG --}}
                            <div class="mb-3">
                                <label for="modalContractSelect" class="form-label fw-semibold" style="font-size: 13.5px; color: #1e293b;">
                                    Chọn hợp đồng <span class="text-danger">*</span>
                                </label>
                                <select id="modalContractSelect" class="form-select form-select-lg fw-bold" required>
                                    @foreach($contracts as $contract)
                                        <option value="{{ $contract->id }}"
                                            data-code="{{ $contract->contract_code }}"
                                            data-tenant="{{ $contract->tenant->name ?? '' }}"
                                            data-room="{{ $contract->room->name ?? '' }} · {{ $contract->room->property->name ?? '' }}"
                                            data-count="{{ $contract->members->count() }}"
                                            data-max="{{ $contract->room->max_people ?? '' }}"
                                            @selected((string) old('contract_id') === (string) $contract->id)>
                                            {{ $contract->contract_code }} — {{ $contract->room->name ?? '' }} ({{ $contract->tenant->name ?? '' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- THÔNG TIN TÓM TẮT HỢP ĐỒNG --}}
                            <div class="contract-quick-info mb-4" id="modalContractInfoBox">
                                <div class="row g-3">
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Mã hợp đồng</div>
                                        <div class="info-col-val text-primary" id="modalInfoCode">—</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Người thuê chính</div>
                                        <div class="info-col-val" id="modalInfoTenant">—</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Phòng</div>
                                        <div class="info-col-val" id="modalInfoRoom">—</div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="info-col-label">Đã ở / Tối đa</div>
                                        <div class="info-col-val" id="modalInfoCount">—</div>
                                    </div>
                                </div>
                            </div>

                            {{-- THÔNG TIN NGƯỜI Ở CÙNG --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Họ và tên <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="name"
                                        value="{{ old('name') }}"
                                        class="form-control"
                                        placeholder="VD: Nguyễn Văn A"
                                        required
                                        maxlength="100"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Số CCCD <span class="text-danger">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        name="identity_card"
                                        value="{{ old('identity_card') }}"
                                        class="form-control"
                                        placeholder="VD: 079..."
                                        required
                                        maxlength="20"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Số điện thoại
                                    </label>
                                    <input
                                        type="tel"
                                        name="phone"
                                        value="{{ old('phone') }}"
                                        class="form-control"
                                        placeholder="VD: 090..."
                                        maxlength="20"
                                    >
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label fw-semibold" style="font-size: 13px; color: #1e293b;">
                                        Quan hệ với người thuê chính
                                    </label>
                                    <select name="relationship" class="form-select">
                                        <option value="">-- Chọn quan hệ --</option>
                                        @foreach(['Bạn', 'Vợ/Chồng', 'Anh/Chị/Em', 'Người thân', 'Khác'] as $rel)
                                            <option value="{{ $rel }}" @selected(old('relationship') === $rel)>
                                                {{ $rel }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="mt-3 p-2 bg-light rounded text-muted" style="font-size: 12.5px;">
                                Người ở cùng chỉ được ghi nhận trong hợp đồng và không cần tạo tài khoản Trọ Ơi.
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-light rounded-3 px-4 py-2 fw-semibold"
                                data-bs-dismiss="modal"
                            >
                                Hủy
                            </button>
                            <button
                                type="submit"
                                class="btn text-white rounded-3 px-4 py-2 fw-semibold"
                                style="background: #0c2340; border-color: #0c2340;"
                            >
                                Thêm người ở cùng
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>

@endsection
 

