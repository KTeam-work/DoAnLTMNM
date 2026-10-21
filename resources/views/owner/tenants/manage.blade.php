
@extends('Layout.landlord')

@section('title', 'Quản lý người thuê | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenants.css') }}">
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
                Thêm người ở cùng tại trang riêng:
                chọn hợp đồng rồi nhập thông tin.
            -->

            <a
                href="{{ route('owner.tenants.create') }}"
                class="btn-add-member text-decoration-none"
            >
                + Thêm người ở cùng
            </a>

        </div>



        <!-- =====================================================
             STAT CARDS
        ===================================================== -->

        @php
            $totalTenants = $tenants->count() + $tenants->reduce(function ($carry, $t) {
                return $carry + optional($t->rentedContracts)->flatMap->members->count();
            }, 0);
            $staying = $tenants->count();
        @endphp
        <div class="row g-3 mb-4">


            <!-- TỔNG NGƯỜI THUÊ -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-blue-soft">
                        👥
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Tổng người thuê
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $totalTenants }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- ĐANG LƯU TRÚ -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-green-soft">
                        🏠
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Đang lưu trú
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $staying }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- SẮP HẾT HẠN -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-orange-soft">
                        ⚠️
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Sắp hết hạn HĐ
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $tenants->filter(fn($t) => ($c = $t->rentedContracts->first()) && $c->status === 'active' && $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60)->count() }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- MỚI CHUYỂN ĐẾN -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-purple-soft">
                        👋
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Hợp đồng hiệu lực
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $tenants->flatMap->rentedContracts->where('status', 'active')->count() }}
                        </div>

                    </div>

                </div>

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

                        <span
                            class="input-group-text bg-white border-end-0 text-muted border-light-subtle"
                        >
                            🔎
                        </span>


                        <input
                            type="text"
                            class="form-control border-start-0 border-light-subtle shadow-none"
                            placeholder="Tìm tên, SĐT, CCCD..."
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
                            @php($contract = $tenant->rentedContracts->first())
                            <tr>
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
                            <tr>
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
                    </tbody>

                </table>

            </div>

        </div>

    </div>


@endsection
 

