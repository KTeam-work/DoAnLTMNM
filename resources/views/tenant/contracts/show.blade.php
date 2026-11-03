@extends('layout.tenant')

@section('title', 'Chi tiết hợp đồng #' . $contract->contract_code . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenant/contracts/show.css') }}">
@endpush

@section('content')

<div class="page-wrap" id="contract-detail-page" style="padding-top: 25px;">

    <!-- BREADCRUMB -->
    <div class="breadcrumb-custom mb-3 animate-up delay-1">
        <a href="{{ route('tenant.home') }}" class="text-decoration-none text-muted">Trang chủ</a>
        <span class="mx-2 text-light-gray">/</span>
        <a href="{{ route('tenant.contracts.index') }}" class="text-decoration-none text-muted">Hợp đồng</a>
        <span class="mx-2 text-light-gray">/</span>
        <span class="fw-bold" style="color: #20584f;">Chi tiết #{{ $contract->contract_code }}</span>
    </div>

    @php
        $statusLabels = [
            'active' => 'Đang hiệu lực',
            'expired' => 'Hết hạn',
            'terminated' => 'Đã thanh lý',
            'draft' => 'Nháp'
        ];
    @endphp

    <!-- PAGE HEADER CARD -->
    <div class="contract-header-card animate-up delay-1">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="font-size: 20px;">📄</span>
                <h2 class="contract-header-title">
                    Hợp đồng <span style="color: #20584f;">#{{ $contract->contract_code }}</span>
                </h2>
                <span class="badge-status-contract {{ $contract->status }} ms-2">
                    {{ $statusLabels[$contract->status] ?? $contract->status }}
                </span>
            </div>
            <div class="contract-header-desc">
                Phòng: <strong>{{ $contract->room->name ?? '—' }}</strong> · Khu trọ: <strong>{{ $contract->room->property->name ?? '' }}</strong>
            </div>
        </div>

        <div class="action-buttons-group">
            <a href="{{ route('tenant.maintenance.create', ['contract_id' => $contract->id]) }}" class="btn btn-outline-brand d-inline-flex align-items-center gap-1">
                ＋ Yêu cầu sửa chữa
            </a>
            <button type="button" class="btn btn-outline-brand d-inline-flex align-items-center gap-1" onclick="window.print()">
                🖨️ In hợp đồng
            </button>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="row g-4 animate-up delay-2">

        <!-- CỘT TRÁI (8 CỘT) -->
        <div class="col-lg-8">

            <!-- THÔNG TIN PHÒNG & GIÁ THUÊ -->
            <div class="panel-card">
                <div class="panel-card-head">
                    <h3 class="panel-card-title">🏠 Thông tin phòng &amp; Chi phí</h3>
                </div>

                <div class="info-grid-custom">
                    <div>
                        <div class="cell-sub">Phòng thuê</div>
                        <div class="info-value">{{ $contract->room->name ?? '—' }}</div>
                        <small class="text-muted">{{ $contract->room->property->name ?? '' }}</small>
                    </div>

                    <div>
                        <div class="cell-sub">Tiền thuê hàng tháng</div>
                        <div class="info-value price-highlight">
                            {{ number_format((float)$contract->rent) }} đ/tháng
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Tiền cọc phòng</div>
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
                        <div class="info-value">
                            {{ number_format((float)$contract->initial_electricity_reading) }} kWh
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Chốt nước ban đầu</div>
                        <div class="info-value">
                            {{ number_format((float)$contract->initial_water_reading) }} m³
                        </div>
                    </div>

                    @if($contract->termination)
                    <div>
                        <div class="cell-sub">Quyết toán cọc</div>
                        <div class="info-value text-success">
                            Hoàn {{ number_format((float)$contract->termination->refund_amount) }} đ
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- BẢNG GIÁ DỊCH VỤ & ĐIỆN NƯỚC -->
            <div class="panel-card">
                <div class="panel-card-head">
                    <h3 class="panel-card-title">💡 Bảng giá dịch vụ &amp; Tiện ích</h3>
                </div>

                <div class="service-table-wrap">
                    <table class="service-table">
                        <thead>
                            <tr>
                                <th>Loại dịch vụ</th>
                                <th>Hình thức tính</th>
                                <th class="text-end">Đơn giá</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="font-size: 18px;">⚡</span>
                                        <strong>Điện sinh hoạt</strong>
                                    </div>
                                </td>
                                <td>Theo chỉ số đồng hồ thực tế</td>
                                <td class="text-end fw-bold" style="color: #20584f;">3.500 đ / kWh</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="font-size: 18px;">💧</span>
                                        <strong>Nước sinh hoạt</strong>
                                    </div>
                                </td>
                                <td>Theo số người ở / tháng</td>
                                <td class="text-end fw-bold" style="color: #20584f;">100.000 đ / người</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="font-size: 18px;">🌐</span>
                                        <strong>Internet / Wi-Fi</strong>
                                    </div>
                                </td>
                                <td>Trọn gói theo phòng</td>
                                <td class="text-end fw-bold" style="color: #20584f;">50.000 đ / phòng</td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <span style="font-size: 18px;">🧹</span>
                                        <strong>Vệ sinh &amp; Rác</strong>
                                    </div>
                                </td>
                                <td>Theo số người ở / tháng</td>
                                <td class="text-end fw-bold" style="color: #20584f;">30.000 đ / người</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- CỘT PHẢI (4 CỘT) -->
        <div class="col-lg-4">

            <!-- THÔNG TIN CHỦ TRỌ -->
            <div class="panel-card">
                <div class="panel-card-head">
                    <h3 class="panel-card-title">👤 Thông tin Chủ trọ</h3>
                </div>

                <div class="owner-box-content">
                    <div class="person-item">
                        <div class="person-avatar">
                            {{ strtoupper(mb_substr($contract->owner->name ?? 'CT', 0, 2)) }}
                        </div>
                        <div>
                            <div class="person-name">{{ $contract->owner->name ?? 'Chủ nhà' }}</div>
                            <div class="text-muted" style="font-size: 12px;">SĐT: {{ $contract->owner->phone ?? '—' }}</div>
                            <div class="text-muted" style="font-size: 12px;">Email: {{ $contract->owner->email ?? '—' }}</div>
                        </div>
                    </div>

                    @if(!empty($contract->owner->phone))
                    <a href="tel:{{ $contract->owner->phone }}" class="btn-call-owner">
                        📞 Gọi cho chủ trọ
                    </a>
                    @endif
                </div>
            </div>

            <!-- THÀNH VIÊN PHÒNG -->
            <div class="panel-card">
                <div class="panel-card-head">
                    <h3 class="panel-card-title">👥 Thành viên phòng</h3>
                    <span class="badge bg-light text-dark border fw-bold px-2 py-1" style="border-radius: 8px; font-size: 11px;">
                        {{ 1 + $contract->members->count() }} người
                    </span>
                </div>

                <div class="member-box-content">
                    <!-- Người đại diện ký hợp đồng -->
                    <div class="person-item">
                        <div class="person-avatar">
                            {{ strtoupper(mb_substr($contract->tenant->name ?? 'NG', 0, 2)) }}
                        </div>
                        <div>
                            <div class="person-name">{{ $contract->tenant->name ?? '—' }}</div>
                            <span class="badge bg-success bg-opacity-10 text-success" style="font-size: 11px;">Người đứng tên HĐ</span>
                        </div>
                    </div>

                    <!-- Danh sách người ở cùng -->
                    @forelse($contract->members as $member)
                    <div class="person-item {{ $loop->last ? 'mb-0' : '' }}">
                        <div class="person-avatar" style="background: #f4f6f8; color: #536561;">
                            {{ strtoupper(mb_substr($member->name, 0, 2)) }}
                        </div>
                        <div>
                            <div class="person-name">{{ $member->name }}</div>
                            <div class="text-muted" style="font-size: 12px;">
                                {{ $member->relationship ?? 'Người ở cùng' }}{{ $member->phone ? ' · ' . $member->phone : '' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-muted text-center py-2" style="font-size: 13px;">
                        Chưa có người ở cùng đăng ký.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
