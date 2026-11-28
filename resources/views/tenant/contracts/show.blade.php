@extends('layout.tenant')

@section('title', 'Chi tiết hợp đồng #' . $contract->contract_code . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenant/contracts/show.css') }}">
@endpush

@section('content')

<div class="contract-page-wrap" id="contract-detail-page">

    <!-- BREADCRUMB -->
    <nav class="breadcrumb-modern" aria-label="breadcrumb">
        <a href="{{ route('tenant.home') }}">Trang chủ</a>
        <span>/</span>
        <a href="{{ route('tenant.contracts.index') }}">Hợp đồng</a>
        <span>/</span>
        <span class="active">#{{ $contract->contract_code }}</span>
    </nav>

    @php
        $statusLabels = [
            'active' => 'Đang hiệu lực',
            'expired' => 'Hết hạn',
            'terminated' => 'Đã thanh lý',
            'draft' => 'Nháp'
        ];
        
        $startDate = \Carbon\Carbon::parse($contract->start_date);
        $endDate = \Carbon\Carbon::parse($contract->end_date);
        $totalDays = max(1, $startDate->diffInDays($endDate));
        $elapsedDays = max(0, $startDate->diffInDays(now(), false));
        $remainingDays = max(0, (int) ceil(now()->diffInDays($endDate, false)));
        $progressPct = min(100, max(0, round(($elapsedDays / $totalDays) * 100)));
    @endphp

    <!-- HERO HEADER CARD -->
    <div class="contract-hero-card">
        <div>
            <h1 class="contract-hero-title">
                Hợp đồng <span class="contract-code-badge">#{{ $contract->contract_code }}</span>
                <span class="status-pill {{ $contract->status }}">
                    {{ $statusLabels[$contract->status] ?? $contract->status }}
                </span>
            </h1>
            <div class="contract-hero-meta">
                <span>Phòng: <strong class="text-dark">{{ $contract->room->name ?? '—' }}</strong></span>
                <span>Khu trọ: <strong class="text-dark">{{ $contract->room->property->name ?? 'Nhà trọ' }}</strong></span>
                @if($contract->room->property->address)
                <span class="d-none d-md-inline text-truncate" style="max-width: 320px;">
                    {{ $contract->room->property->address }}
                </span>
                @endif
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 flex-wrap">
            <a href="{{ route('tenant.maintenance.create', ['contract_id' => $contract->id]) }}" class="btn-hero-action btn-hero-primary">
                Yêu cầu sửa chữa
            </a>
            <button type="button" class="btn-hero-action btn-hero-light" onclick="window.print()">
                In hợp đồng
            </button>
        </div>
    </div>

    <!-- CONTRACT DURATION & TIMELINE BAR -->
    <div class="contract-timeline-card">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 text-muted" style="font-size: 13px;">
            <div>
                Bắt đầu: <strong class="text-dark">{{ $startDate->format('d/m/Y') }}</strong>
            </div>
            <div>
                @if($contract->status === 'active')
                    <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="font-size: 12px; border-radius: 6px;">
                        Còn {{ $remainingDays }} ngày ({{ 100 - $progressPct }}% thời hạn)
                    </span>
                @elseif($contract->status === 'expired')
                    <span class="badge bg-danger-subtle text-danger fw-bold px-2 py-1" style="font-size: 12px; border-radius: 6px;">
                        Hợp đồng đã kết thúc
                    </span>
                @else
                    <span class="badge bg-light text-secondary border fw-bold px-2 py-1" style="font-size: 12px; border-radius: 6px;">
                        {{ $statusLabels[$contract->status] ?? $contract->status }}
                    </span>
                @endif
            </div>
            <div>
                Kết thúc: <strong class="text-dark">{{ $endDate->format('d/m/Y') }}</strong>
            </div>
        </div>

        <div class="timeline-progress-wrap">
            <div class="timeline-progress-bar" style="width: {{ $progressPct }}%;"></div>
        </div>
    </div>

    <!-- MAIN CONTENT GRID -->
    <div class="row g-4">

        <!-- CỘT TRÁI (8 CỘT) -->
        <div class="col-lg-8">

            <!-- THÔNG TIN PHÒNG & CHI PHÍ -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern">Thông tin phòng &amp; Chi phí thuê</h2>
                    <span class="text-muted small fw-semibold">Chi tiết điều khoản ký kết</span>
                </div>

                <div class="info-tiles-grid">
                    <!-- Tile 1: Phòng thuê -->
                    <div class="info-tile">
                        <span class="tile-label">Phòng thuê</span>
                        <div class="tile-value">{{ $contract->room->name ?? '—' }}</div>
                        <div class="tile-sub text-truncate">{{ $contract->room->property->name ?? '' }}</div>
                    </div>

                    <!-- Tile 2: Tiền thuê hàng tháng -->
                    <div class="info-tile" style="border-left: 3px solid #059669;">
                        <span class="tile-label">Tiền thuê hàng tháng</span>
                        <div class="tile-value highlight-rent">
                            {{ number_format((float)$contract->rent) }} đ
                        </div>
                        <div class="tile-sub">Thu định kỳ theo chu kỳ</div>
                    </div>

                    <!-- Tile 3: Tiền cọc phòng -->
                    <div class="info-tile">
                        <span class="tile-label">Tiền cọc phòng</span>
                        <div class="tile-value">
                            {{ number_format((float)$contract->deposit) }} đ
                        </div>
                        <div class="tile-sub">Bảo lưu đến hết hợp đồng</div>
                    </div>

                    <!-- Tile 4: Ngày bắt đầu -->
                    <div class="info-tile">
                        <span class="tile-label">Ngày bắt đầu</span>
                        <div class="tile-value">{{ $startDate->format('d/m/Y') }}</div>
                        <div class="tile-sub">Bàn giao &amp; nhận phòng</div>
                    </div>

                    <!-- Tile 5: Ngày kết thúc -->
                    <div class="info-tile">
                        <span class="tile-label">Ngày kết thúc</span>
                        <div class="tile-value">{{ $endDate->format('d/m/Y') }}</div>
                        <div class="tile-sub">Hạn hiệu lực hợp đồng</div>
                    </div>

                    <!-- Tile 6: Kỳ thanh toán -->
                    <div class="info-tile">
                        <span class="tile-label">Kỳ thanh toán</span>
                        <div class="tile-value">
                            {{ $contract->payment_cycle === 'quarterly' ? 'Hàng quý (3 tháng)' : 'Hàng tháng' }}
                        </div>
                        <div class="tile-sub">Đóng tiền đúng hạn định kỳ</div>
                    </div>

                    <!-- Tile 7: Chốt điện ban đầu -->
                    <div class="info-tile">
                        <span class="tile-label">Chốt điện ban đầu</span>
                        <div class="tile-value">
                            {{ number_format((float)$contract->initial_electricity_reading) }} kWh
                        </div>
                        <div class="tile-sub">Chỉ số công tơ lúc nhận phòng</div>
                    </div>

                    <!-- Tile 8: Chốt nước ban đầu -->
                    <div class="info-tile">
                        <span class="tile-label">Chốt nước ban đầu</span>
                        <div class="tile-value">
                            {{ number_format((float)$contract->initial_water_reading) }} m³
                        </div>
                        <div class="tile-sub">Chỉ số đồng hồ lúc nhận phòng</div>
                    </div>

                    @if($contract->termination)
                    <!-- Tile 9: Quyết toán cọc (nếu đã thanh lý) -->
                    <div class="info-tile" style="border-left: 3px solid #10b981;">
                        <span class="tile-label">Quyết toán cọc</span>
                        <div class="tile-value text-success">
                            Hoàn {{ number_format((float)$contract->termination->refund_amount) }} đ
                        </div>
                        <div class="tile-sub">Đã quyết toán khi trả phòng</div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- BẢNG GIÁ DỊCH VỤ & TIỆN ÍCH -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern">Bảng giá dịch vụ &amp; Tiện ích</h2>
                    <span class="text-muted small fw-semibold">Biểu phí áp dụng hàng tháng</span>
                </div>

                <div>
                    <!-- Điện sinh hoạt -->
                    <div class="service-item-row">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 14px;">Điện sinh hoạt</div>
                            <div class="text-muted small">Tính theo chỉ số đồng hồ thực tế tiêu thụ trong tháng</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-6 text-dark">{{ number_format((float)($electricPrice ?? 3500)) }} đ <span class="text-muted fw-normal small">/ kWh</span></div>
                            <span class="badge bg-light text-secondary border" style="font-size: 11px;">Theo đồng hồ</span>
                        </div>
                    </div>

                    <!-- Nước sinh hoạt -->
                    <div class="service-item-row">
                        <div>
                            <div class="fw-bold text-dark" style="font-size: 14px;">Nước sinh hoạt</div>
                            <div class="text-muted small">Tính theo chỉ số đồng hồ nước thực tế sử dụng</div>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold fs-6 text-dark">{{ number_format((float)($waterPrice ?? 20000)) }} đ <span class="text-muted fw-normal small">/ m³</span></div>
                            <span class="badge bg-light text-secondary border" style="font-size: 11px;">Theo đồng hồ</span>
                        </div>
                    </div>

                    <!-- Các dịch vụ bổ sung nếu có -->
                    @if($contract->services && $contract->services->isNotEmpty())
                        @foreach($contract->services as $svc)
                            @php
                                $qty = (float)($svc->pivot->quantity ?? 1);
                                $unitPrice = (float)($svc->pivot->unit_price ?? $svc->price);
                                $lineTotal = $qty * $unitPrice;
                            @endphp
                            <div class="service-item-row">
                                <div>
                                    <div class="fw-bold text-dark" style="font-size: 14px;">{{ $svc->name }}</div>
                                    <div class="text-muted small">Cố định hàng tháng (Số lượng: {{ $qty }} {{ $svc->unit }})</div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-6 text-dark">{{ number_format($lineTotal) }} đ <span class="text-muted fw-normal small">/ tháng</span></div>
                                    @if($qty > 1)
                                        <div class="small text-muted">({{ number_format($unitPrice) }} đ/{{ $svc->unit }})</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

        </div>

        <!-- CỘT PHẢI (4 CỘT) -->
        <div class="col-lg-4">

            <!-- THÔNG TIN CHỦ TRỌ -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern">Thông tin Chủ trọ</h2>
                    <span class="badge bg-success-subtle text-success fw-bold px-2 py-1" style="border-radius: 8px; font-size: 11px;">
                        Đã xác thực
                    </span>
                </div>

                <div class="owner-card-profile">
                    <div class="owner-avatar-lg position-relative">
                        {{ strtoupper(mb_substr($contract->owner->name ?? 'CT', 0, 2)) }}
                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 14px; height: 14px;"></span>
                    </div>
                    <div class="owner-name-title">{{ $contract->owner->name ?? 'Chủ nhà' }}</div>
                    <div class="text-muted small mb-3">Đại diện cho thuê bất động sản</div>

                    <div class="owner-meta-row text-start">
                        <span class="text-muted">Số điện thoại:</span>
                        <span class="fw-bold text-dark">{{ $contract->owner->phone ?? 'Chưa cập nhật' }}</span>
                    </div>

                    <div class="owner-meta-row text-start">
                        <span class="text-muted">Email:</span>
                        <span class="fw-bold text-dark text-truncate" style="max-width: 170px;">
                            {{ $contract->owner->email ?? '—' }}
                        </span>
                    </div>
                    @if(auth()->check() && auth()->user()->role === 'tenant' && auth()->id() === $contract->tenant_id)
                        <form action="{{ route('chat.start-contract', $contract) }}" method="POST" class="mt-3" data-no-ajax="true">
                            @csrf
                            <button type="submit" class="btn btn-outline-primary w-100 fw-bold py-2">Nhắn tin cho chủ trọ</button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- THÀNH VIÊN PHÒNG -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern">Thành viên phòng</h2>
                    <span class="badge bg-light text-dark border fw-bold px-2 py-1" style="border-radius: 8px; font-size: 11px;">
                        {{ 1 + $contract->members->count() }} người
                    </span>
                </div>

                <div>
                    <!-- Người đại diện ký hợp đồng -->
                    <div class="roommate-row mb-2" style="background: #eff6ff; border-color: #dbeafe;">
                        <div class="roommate-avatar" style="background: #2563eb; color: #ffffff;">
                            {{ strtoupper(mb_substr($contract->tenant->name ?? 'NG', 0, 2)) }}
                        </div>
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 14px;">{{ $contract->tenant->name ?? '—' }}</div>
                            <span class="badge bg-primary text-white" style="font-size: 10.5px; font-weight: 600;">Người đứng tên HĐ</span>
                        </div>
                    </div>

                    <!-- Danh sách người ở cùng -->
                    @forelse($contract->members as $member)
                    <div class="roommate-row">
                        <div class="roommate-avatar">
                            {{ strtoupper(mb_substr($member->name, 0, 2)) }}
                        </div>
                        <div class="flex-grow-1 text-truncate">
                            <div class="fw-bold text-dark text-truncate" style="font-size: 13.5px;">{{ $member->name }}</div>
                            <div class="text-muted" style="font-size: 12px;">
                                {{ $member->relationship ?? 'Người ở cùng' }}{{ $member->phone ? ' · ' . $member->phone : '' }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="p-3 text-center text-muted rounded-3" style="background: #f8fafc; font-size: 13px;">
                        Chưa có người ở cùng đăng ký.
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- LỐI TẮT TIỆN ÍCH -->
            <div class="modern-panel" style="background: #f8fafc; border-style: dashed;">
                <div class="fw-bold text-dark mb-2" style="font-size: 14px;">
                    Lối tắt nhanh
                </div>
                <div class="d-flex flex-column gap-2" style="font-size: 13px;">
                    <a href="{{ route('tenant.invoices.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-white border text-decoration-none text-dark hover-shadow">
                        <span>Xem hóa đơn tiền phòng</span>
                        <span class="text-muted small">&rarr;</span>
                    </a>
                    <a href="{{ route('tenant.maintenance.create', ['contract_id' => $contract->id]) }}" class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-white border text-decoration-none text-dark hover-shadow">
                        <span>Báo sự cố thiết bị</span>
                        <span class="text-muted small">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
