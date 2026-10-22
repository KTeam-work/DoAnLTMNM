
@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/contracts/show.css') }}">
@endpush

@section('content')



<div class="container-page">

    <!-- BREADCRUMB / ACTION -->
    <div class="top-bar">
        <a href="{{ route('tenant.contracts.index') }}" class="back-link">
            ← Quay lại danh sách hợp đồng
        </a>

        <div class="action-buttons">
            <a href="{{ route('tenant.maintenance.create', ['contract_id' => $contract->id]) }}" class="btn-custom text-decoration-none" style="border: 1px solid #16834b; color: #16834b;">
                ＋ Tạo yêu cầu mới
            </a>
            <button class="btn-custom btn-pdf">
                📥 Tải file PDF
            </button>
            <button class="btn-custom btn-liquidation">
                ⚠️ Yêu cầu thanh lý
            </button>
        </div>
    </div>


    <!-- PAGE HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">
                Chi tiết hợp đồng
                <span class="text-brand">#{{ $contract->contract_code }}</span>
            </h2>
            <div class="page-desc">
                Thông tin chi tiết về phòng thuê, chi phí cố định và các dịch vụ đi kèm.
            </div>
        </div>

        <span class="badge-status resolved">
            {{ ['draft' => 'Nháp', 'active' => 'Đang hiệu lực', 'expired' => 'Hết hạn', 'terminated' => 'Đã thanh lý'][$contract->status] ?? $contract->status }}
        </span>
    </div>


    <!-- MAIN CONTENT -->
    <div class="row g-3">

        <!-- LEFT -->
        <div class="col-lg-8">

            <!-- THÔNG TIN PHÒNG -->
            <div class="panel mb-3">
                <div class="panel-head">
                    <h3 class="panel-title">
                        Thông tin phòng & Giá thuê
                    </h3>
                </div>

                <div class="info-grid">
                    <div>
                        <div class="cell-sub">Phòng thuê</div>
                        <div class="info-value">
                            {{ $contract->room->name ?? '—' }}
                        </div>
                        <div class="cell-sub mt-1">{{ $contract->room->property->name ?? '' }}</div>
                    </div>

                    <div>
                        <div class="cell-sub">Tiền thuê hàng tháng</div>
                        <div class="info-value price">
                            {{ number_format((float)$contract->rent) }} đ/tháng
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Tiền cọc</div>
                        <div class="info-value">
                            {{ number_format((float)$contract->deposit) }} đ
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Ngày bắt đầu</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Ngày kết thúc</div>
                        <div class="info-value">
                            {{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Kỳ thanh toán</div>
                        <div class="info-value">
                            {{ $contract->payment_cycle === 'quarterly' ? 'Hàng quý' : 'Hàng tháng' }}
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Chốt điện ban đầu</div>
                        <div class="info-value">{{ number_format((float)$contract->initial_electricity_reading) }}</div>
                    </div>

                    <div>
                        <div class="cell-sub">Chốt nước ban đầu</div>
                        <div class="info-value">{{ number_format((float)$contract->initial_water_reading) }}</div>
                    </div>

                    @if($contract->termination)
                    <div>
                        <div class="cell-sub">Quyết toán cọc</div>
                        <div class="info-value">Hoàn {{ number_format((float)$contract->termination->refund_amount) }} đ</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- DỊCH VỤ -->
            <div class="panel">
                <div class="panel-head">
                    <h3 class="panel-title">
                        Bảng giá Dịch vụ & Điện nước
                    </h3>
                </div>

                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Loại dịch vụ</th>
                                <th>Hình thức tính</th>
                                <th class="text-end">Đơn giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>⚡ Điện sinh hoạt</td>
                                <td>Theo chỉ số đồng hồ</td>
                                <td class="text-end">
                                    <strong>3.500 đ / kWh</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>💧 Nước sinh hoạt</td>
                                <td>Theo số người ở</td>
                                <td class="text-end">
                                    <strong>100.000 đ / người</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>🌐 Internet / Wifi</td>
                                <td>Cố định theo phòng</td>
                                <td class="text-end">
                                    <strong>50.000 đ / phòng</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>🧹 Vệ sinh & Rác</td>
                                <td>Theo số người ở</td>
                                <td class="text-end">
                                    <strong>30.000 đ / người</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">

            <!-- CHỦ TRỌ -->
            <div class="panel mb-3">
                <div class="panel-head">
                    <h3 class="panel-title">
                        Thông tin Chủ trọ
                    </h3>
                </div>

                <div class="owner-box">
                    <div class="person">
                        <div class="avatar-sm">
                            {{ strtoupper(substr($contract->owner->name ?? '?', 0, 2)) }}
                        </div>
                        <div>
                            <div class="person-name">
                                {{ $contract->owner->name ?? '—' }}
                            </div>
                            <div class="cell-sub">
                                SĐT: {{ $contract->owner->phone ?? '—' }}
                            </div>
                        </div>
                    </div>

                    <a href="tel:{{ $contract->owner->phone ?? '' }}" class="call-button">
                        📞 Gọi cho chủ trọ
                    </a>
                </div>
            </div>

            <!-- THÀNH VIÊN -->
            <div class="panel">
                <div class="panel-head">
                    <div class="member-header">
                        <h3 class="panel-title">
                            Thành viên phòng
                        </h3>
                        <span class="cell-sub mb-0">
                            {{ 1 + $contract->members->count() }} người
                        </span>
                    </div>
                </div>

                <div class="member-box">
                    <div class="person mb-3">
                        <div class="avatar-sm">
                            {{ strtoupper(substr($contract->tenant->name ?? '?', 0, 2)) }}
                        </div>
                        <div>
                            <div class="person-name">
                                {{ $contract->tenant->name ?? '—' }}
                            </div>
                            <div class="cell-sub">
                                Người đại diện hợp đồng
                            </div>
                        </div>
                    </div>
                    @forelse($contract->members as $member)
                    <div class="person {{ $loop->last ? 'mb-0' : 'mb-3' }}">
                        <div class="avatar-sm">
                            {{ strtoupper(substr($member->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="person-name">
                                {{ $member->name }}
                            </div>
                            <div class="cell-sub">
                                {{ $member->relationship ?? 'Người ở cùng' }}{{ $member->phone ? ' · ' . $member->phone : '' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="cell-sub mb-0">Chưa có người ở cùng.</div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>


@endsection