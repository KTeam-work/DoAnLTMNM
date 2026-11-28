@extends('Layout.landlord')

@section('title', 'Chi tiết hợp đồng #' . $contract->contract_code . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenant/contracts/show.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/owner_contract_detail.css') }}">
    <style>
        .owner-avatar-tenant {
            width: 68px;
            height: 68px;
            border-radius: 20px;
            background: #eff6ff;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            margin: 0 auto 12px;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.15);
        }
        .member-action-btn {
            background: transparent;
            border: 1px solid #fecaca;
            color: #dc2626;
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            transition: all 0.2s ease;
        }
        .member-action-btn:hover {
            background: #dc2626;
            color: #ffffff;
        }
        .btn-brand-save {
            background: #0c2340;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            border-radius: 10px;
            padding: 8px 16px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-brand-save:hover {
            background: #1e3a5f;
            color: #ffffff;
        }
        @media print {
            .app-navbar, .breadcrumb-modern, .action-buttons-group, .add-member-section, .member-action-btn, .shortcuts-panel {
                display: none !important;
            }
            .contract-page-wrap {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .modern-panel {
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
                break-inside: avoid;
            }
        }
    </style>
@endpush

@section('content')

@php
    $statusLabels = [
        'active' => 'Đang hiệu lực',
        'expired' => 'Đã hết hạn',
        'terminated' => 'Đã thanh lý',
        'draft' => 'Bản nháp'
    ];
    
    $startDate = \Carbon\Carbon::parse($contract->start_date);
    $endDate = \Carbon\Carbon::parse($contract->end_date);
    $totalDays = max(1, $startDate->diffInDays($endDate));
    $elapsedDays = max(0, $startDate->diffInDays(now(), false));
    $remainingDays = max(0, (int) ceil(now()->diffInDays($endDate, false)));
    $progressPct = min(100, max(0, round(($elapsedDays / $totalDays) * 100)));
    $isExpiredOrDue = $contract->status === 'expired' || now()->startOfDay()->gte($endDate->startOfDay());

    $electricPrice = $contract->room->property->electricity_price ?? 3500;
    $waterPrice = $contract->room->property->water_price ?? 20000;
@endphp

<div class="contract-page-wrap" id="contract-detail-page">

    <!-- BREADCRUMB -->
    <nav class="breadcrumb-modern" aria-label="breadcrumb">
        <a href="{{ route('landlord.dashboard') }}">Tổng quan</a>
        <span>/</span>
        <a href="{{ route('owner.contracts.index') }}">Hợp đồng</a>
        <span>/</span>
        <span class="active">#{{ $contract->contract_code }}</span>
    </nav>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            <div>{{ session('success') }}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (isset($errors) && $errors->any())
        <div class="alert alert-danger rounded-3 shadow-sm mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- HERO HEADER CARD -->
    <div class="contract-hero-card">
        <div>
            <div class="contract-eyebrow">HỒ SƠ HỢP ĐỒNG</div>
            <h1 class="contract-hero-title">
                Hợp đồng <span class="contract-code-badge">#{{ $contract->contract_code }}</span>
                <span class="status-pill {{ $contract->status }}">
                    {{ $statusLabels[$contract->status] ?? $contract->status }}
                </span>
            </h1>
            <div class="contract-hero-meta">
                <span><i class="bi bi-door-open" aria-hidden="true"></i> Phòng <strong class="text-dark">{{ $contract->room->name ?? '—' }}</strong></span>
                <span><i class="bi bi-buildings" aria-hidden="true"></i> <strong class="text-dark">{{ $contract->room->property->name ?? 'Nhà trọ' }}</strong></span>
                @if($contract->room->property->address)
                    <span class="d-none d-md-inline text-truncate" style="max-width: 320px;">
                        <i class="bi bi-geo-alt" aria-hidden="true"></i> {{ $contract->room->property->address }}
                    </span>
                @endif
            </div>
        </div>

        <div class="action-buttons-group d-flex align-items-center gap-2 flex-wrap">
            @if($contract->status === 'draft')
                <form method="POST" action="{{ route('owner.contracts.activate', $contract) }}" class="d-inline" onsubmit="return confirm('Xác nhận kích hoạt hợp đồng #{{ $contract->contract_code }}? Phòng sẽ được chuyển sang trạng thái đang thuê.')">
                    @csrf
                    <button type="submit" class="btn-hero-action btn-hero-primary" style="background: #059669;">
                        Kích hoạt hợp đồng
                    </button>
                </form>
            @endif
            @if($contract->status !== 'terminated')
                @if($isExpiredOrDue)
                    <button type="button" class="btn-hero-action" style="background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0;" data-bs-toggle="modal" data-bs-target="#extendContractModal">
                        Gia hạn hợp đồng
                    </button>
                @else
                    <button type="button" class="btn-hero-action" style="background: #f8fafc; color: #94a3b8; border: 1px solid #e2e8f0;" data-bs-toggle="modal" data-bs-target="#cannotExtendModal" title="Xem lý do hợp đồng chưa thể gia hạn">
                        Gia hạn hợp đồng
                    </button>
                @endif
                <button type="button" class="btn-hero-action" style="background: #fff1f2; color: #e11d48; border: 1px solid #fecdd3;" data-bs-toggle="modal" data-bs-target="#terminateContractModal">
                    Thanh lý hợp đồng
                </button>
            @endif
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
                        @if(now()->startOfDay()->lt($startDate->copy()->startOfDay()))
                            Bắt đầu sau {{ (int) now()->startOfDay()->diffInDays($startDate->copy()->startOfDay()) }} ngày
                        @else
                            Còn {{ $remainingDays }} ngày ({{ 100 - $progressPct }}% thời hạn)
                        @endif
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

            <!-- 1. THÔNG TIN PHÒNG & CHI PHÍ -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern"><span class="contract-section-icon"><i class="bi bi-house-door" aria-hidden="true"></i></span> Thông tin phòng &amp; chi phí thuê</h2>
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
                        <div class="tile-sub">Chỉ số công tơ lúc bàn giao</div>
                    </div>

                    <!-- Tile 8: Chốt nước ban đầu -->
                    <div class="info-tile">
                        <span class="tile-label">Chốt nước ban đầu</span>
                        <div class="tile-value">
                            {{ number_format((float)$contract->initial_water_reading) }} m³
                        </div>
                        <div class="tile-sub">Chỉ số đồng hồ lúc bàn giao</div>
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

            <!-- 2. BẢNG GIÁ DỊCH VỤ & TIỆN ÍCH -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern"><span class="contract-section-icon"><i class="bi bi-lightning-charge" aria-hidden="true"></i></span> Dịch vụ &amp; tiện ích</h2>
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
                            <div class="fw-bold fs-6 text-dark">{{ number_format((float)$electricPrice) }} đ <span class="text-muted fw-normal small">/ kWh</span></div>
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
                            <div class="fw-bold fs-6 text-dark">{{ number_format((float)$waterPrice) }} đ <span class="text-muted fw-normal small">/ m³</span></div>
                            <span class="badge bg-light text-secondary border" style="font-size: 11px;">Theo đồng hồ</span>
                        </div>
                    </div>

                    <!-- Các dịch vụ cố định theo hợp đồng nếu có -->
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

            <!-- 3. ĐIỀU KHOẢN HỢP ĐỒNG -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern"><span class="contract-section-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span> Điều khoản hợp đồng</h2>
                    <span class="text-muted small fw-semibold">Quy định và nghĩa vụ</span>
                </div>

                @if(!empty($contract->terms))
                    <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line; line-height: 1.6; font-size: 13.5px; border: 1px solid #eef2f6;">
                        {{ $contract->terms }}
                    </div>
                @else
                    <div class="p-3 bg-light rounded-3 text-muted text-center" style="font-size: 13.5px;">
                        Áp dụng theo quy chế thuê phòng và quy định nội bộ khu trọ tiêu chuẩn.
                    </div>
                @endif
            </div>

            <!-- 4. THÔNG TIN THANH LÝ (NẾU ĐÃ THANH LÝ) -->
            @if($contract->status === 'terminated' && $contract->termination)
                <div class="modern-panel" style="border: 1px solid #fecaca; background: #fff5f5;">
                    <div class="panel-header-modern" style="border-color: #fee2e2;">
                        <h2 class="panel-title-modern text-danger">Biên bản thanh lý hợp đồng</h2>
                        <span class="badge bg-danger text-white">Đã hoàn tất thanh lý</span>
                    </div>

                    <div class="info-tiles-grid">
                        <div class="info-tile bg-white">
                            <span class="tile-label">Ngày trả phòng</span>
                            <div class="tile-value">{{ \Carbon\Carbon::parse($contract->termination->return_date)->format('d/m/Y') }}</div>
                        </div>
                        <div class="info-tile bg-white">
                            <span class="tile-label">Tiền cọc ban đầu</span>
                            <div class="tile-value">{{ number_format((float)$contract->termination->deposit_amount) }} đ</div>
                        </div>
                        <div class="info-tile bg-white">
                            <span class="tile-label">Tiền khấu trừ</span>
                            <div class="tile-value text-danger">- {{ number_format((float)$contract->termination->deduction_amount) }} đ</div>
                            @if($contract->termination->deduction_reason)
                                <div class="tile-sub text-muted">{{ $contract->termination->deduction_reason }}</div>
                            @endif
                        </div>
                        <div class="info-tile bg-white" style="border-left: 3px solid #10b981;">
                            <span class="tile-label">Tiền hoàn lại</span>
                            <div class="tile-value text-success fw-bold">{{ number_format((float)$contract->termination->refund_amount) }} đ</div>
                        </div>
                    </div>
                    @if($contract->termination->utility_total !== null)
                        <div class="mt-3 p-3 rounded-3 bg-white">
                            <strong>Điện nước khi trả phòng: {{ number_format((float) $contract->termination->utility_total) }} đ</strong>
                            <div>Điện: {{ $contract->termination->electricity_old }} → {{ $contract->termination->electricity_new }} kWh × {{ number_format((float) $contract->termination->electricity_price) }} đ</div>
                            <div>Nước: {{ $contract->termination->water_old }} → {{ $contract->termination->water_new }} m³ × {{ number_format((float) $contract->termination->water_price) }} đ</div>
                            @if($contract->termination->utility_invoice_id)
                                <div><a href="{{ route('owner.invoices.show', $contract->termination->utility_invoice_id) }}">Xem hóa đơn điện nước cuối kỳ</a></div>
                            @else
                                <small class="text-muted">Không phát sinh hóa đơn điện nước.</small>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

        </div>

        <!-- CỘT PHẢI (4 CỘT) -->
        <div class="col-lg-4">

            <!-- THÔNG TIN NGƯỜI THUÊ (CHỦ HỢP ĐỒNG) -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern"><span class="contract-section-icon"><i class="bi bi-person" aria-hidden="true"></i></span> Người đại diện</h2>
                </div>

                <div class="owner-card-profile">
                    <div class="owner-avatar-tenant">
                        {{ strtoupper(mb_substr($contract->tenant->name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="owner-name-title">{{ $contract->tenant->name ?? 'Người thuê' }}</div>
                    <div class="text-muted small mb-3">Người đứng tên ký kết hợp đồng</div>

                    <div class="owner-meta-row text-start">
                        <span class="text-muted">Số điện thoại:</span>
                        <span class="fw-bold text-dark">{{ $contract->tenant->phone ?? 'Chưa cập nhật' }}</span>
                    </div>

                    <div class="owner-meta-row text-start">
                        <span class="text-muted">Email:</span>
                        <span class="fw-bold text-dark text-truncate" style="max-width: 170px;">
                            {{ $contract->tenant->email ?? '—' }}
                        </span>
                    </div>

                    @if(!empty($contract->tenant->identity_card))
                        <div class="owner-meta-row text-start">
                            <span class="text-muted">Số CCCD / CMND:</span>
                            <span class="fw-bold text-dark">{{ $contract->tenant->identity_card }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- THÀNH VIÊN PHÒNG / NGƯỜI Ở CÙNG -->
            <div class="modern-panel">
                <div class="panel-header-modern">
                    <h2 class="panel-title-modern"><span class="contract-section-icon"><i class="bi bi-people" aria-hidden="true"></i></span> Người ở cùng</h2>
                    <span class="badge bg-light text-dark border fw-bold px-2 py-1" style="border-radius: 8px; font-size: 11px;">
                        {{ 1 + $contract->members->count() }}/{{ $contract->room->max_people ?? '?' }} người
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
                            <span class="badge bg-primary text-white" style="font-size: 10.5px; font-weight: 600;">Đại diện HĐ</span>
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
                                    CCCD: {{ $member->identity_card }}
                                    @if($member->phone) · SĐT: {{ $member->phone }} @endif
                                    @if($member->relationship) ({{ $member->relationship }}) @endif
                                </div>
                            </div>
                            @if($contract->status !== 'terminated')
                                <form method="POST" action="{{ route('owner.contracts.members', $contract) }}" class="ms-2">
                                    @csrf
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                                    <button type="submit" class="member-action-btn" title="Xóa người ở cùng" onclick="return confirm('Xác nhận xóa người ở cùng này?')">
                                        Xóa
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="p-3 text-center text-muted rounded-3" style="background: #f8fafc; font-size: 13px;">
                            Chưa có người ở cùng đăng ký.
                        </div>
                    @endforelse

                    <!-- Form thêm người ở cùng -->
                    @if($contract->status !== 'terminated')
                        @php
                            $canAddMore = (1 + $contract->members->count()) < ($contract->room->max_people ?? 10);
                        @endphp

                        @if($canAddMore)
                            <hr class="my-3" style="border-color: #f1f5f9;">
                            <div class="add-member-section">
                                <div class="fw-bold mb-2 text-dark" style="font-size: 13px;">+ Thêm người ở cùng phòng</div>
                                <div class="text-muted small mb-2">CCCD/CMND và số điện thoại phải khác những người đã có trong hợp đồng.</div>
                                <form method="POST" action="{{ route('owner.contracts.members', $contract) }}" class="row g-2">
                                    @csrf
                                    <input type="hidden" name="action" value="add">
                                    <div class="col-12">
                                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Họ và tên *" value="{{ old('name') }}" required maxlength="100">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="identity_card" class="form-control form-control-sm" placeholder="Số CCCD / CMND *" value="{{ old('identity_card') }}" required maxlength="20">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Số điện thoại" value="{{ old('phone') }}" maxlength="20">
                                    </div>
                                    <div class="col-6">
                                        <input type="text" name="relationship" class="form-control form-control-sm" placeholder="Quan hệ (bạn, em...)" value="{{ old('relationship') }}" maxlength="50">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn-brand-save w-100">Lưu người ở cùng</button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-warning py-2 px-3 small mt-2 mb-0 text-center">
                                Phòng đã đạt tối đa {{ $contract->room->max_people }} người.
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- LỐI TẮT NHANH -->
            <div class="modern-panel shortcuts-panel" style="background: #f8fafc; border-style: dashed;">
                <div class="fw-bold text-dark mb-2" style="font-size: 14px;">
                    Lối tắt nhanh
                </div>
                <div class="d-flex flex-column gap-2" style="font-size: 13px;">
                    <a href="{{ route('owner.invoices.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-white border text-decoration-none text-dark hover-shadow">
                        <span>Quản lý hóa đơn tiền phòng</span>
                        <span class="text-muted small">&rarr;</span>
                    </a>
                    <a href="{{ route('owner.rooms.index') }}" class="d-flex align-items-center justify-content-between p-2 rounded-2 bg-white border text-decoration-none text-dark hover-shadow">
                        <span>Quản lý danh sách phòng</span>
                        <span class="text-muted small">&rarr;</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@if($contract->status !== 'terminated')
{{-- MODAL THÔNG BÁO CHƯA ĐƯỢC GIA HẠN HỢP ĐỒNG (THIẾT KẾ ĐẸP HIỆN ĐẠI THEO PHONG CÁCH TRỌ ƠI) --}}
<div class="modal fade" id="cannotExtendModal" tabindex="-1" aria-labelledby="cannotExtendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 460px;">
        <div class="modal-content" style="border-radius: 24px; border: none; box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15); overflow: hidden;">
            <div class="modal-body p-4 text-center">
                <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 68px; height: 68px; border-radius: 50%; background: #eff6ff; color: #2563eb; font-size: 28px; box-shadow: 0 4px 16px rgba(37, 99, 235, 0.15);">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                
                <h5 class="fw-bold text-dark mb-2" id="cannotExtendModalLabel" style="font-size: 18.5px;">
                    Chưa đến kỳ gia hạn hợp đồng
                </h5>

                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: #ecfdf5; border: 1px solid #a7f3d0;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #10b981;"></span>
                    <span class="fw-bold" style="font-size: 13px; color: #065f46;">
                        Hợp đồng đang còn {{ $remainingDays }} ngày hiệu lực
                    </span>
                </div>

                <div class="p-3 rounded-3 mb-3 text-start" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 13px; line-height: 1.6;">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Mã hợp đồng:</span>
                        <strong class="text-dark">#{{ $contract->contract_code }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Phòng thuê:</span>
                        <strong class="text-dark">{{ $contract->room->name ?? '—' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-muted">Hạn hiệu lực đến:</span>
                        <strong class="text-primary">{{ $endDate->format('d/m/Y') }}</strong>
                    </div>
                    <hr class="my-2" style="border-color: #e2e8f0;">
                    <div class="text-secondary small">
                        Hệ thống tự động khóa tính năng gia hạn nhằm bảo vệ điều khoản pháp lý của hợp đồng đang chạy. Bạn có thể gia hạn khi hợp đồng đã hết hiệu lực hoặc tới ngày kết thúc hợp đồng (<strong>{{ $endDate->format('d/m/Y') }}</strong>).
                    </div>
                </div>

                <button type="button" class="btn w-100 py-2 fw-bold text-white rounded-pill" data-bs-dismiss="modal" style="background: #0c2340; font-size: 14px;">
                    Đã hiểu
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL GIA HẠN HỢP ĐỒNG --}}
<div class="modal fade" id="extendContractModal" tabindex="-1" aria-labelledby="extendModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold" id="extendModalLabel" style="color: #059669;">Gia hạn hợp đồng thuê phòng</h5>
                    <div class="small text-muted">Hợp đồng #{{ $contract->contract_code }} · Phòng: {{ $contract->room->name ?? '—' }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('owner.contracts.extend', $contract) }}">
                @csrf
                @method('PUT')
                <div class="modal-body pt-3">
                    <div class="p-3 mb-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Thời hạn kết thúc hiện tại:</span>
                            <strong class="text-dark">{{ \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Giá thuê hiện tại:</span>
                            <strong class="text-dark">{{ number_format((float)$contract->rent) }} đ/tháng</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Chọn nhanh thời gian gia hạn thêm</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-success btn-sm flex-fill rounded-pill py-2 fw-semibold quick-extend-btn" data-months="3">
                                +3 tháng
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm flex-fill rounded-pill py-2 fw-semibold quick-extend-btn active bg-success text-white" data-months="6">
                                +6 tháng
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm flex-fill rounded-pill py-2 fw-semibold quick-extend-btn" data-months="12">
                                +12 tháng (1 năm)
                            </button>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Ngày kết thúc mới của hợp đồng <span class="text-danger">*</span></label>
                        <input type="date" id="new_end_date" name="new_end_date" class="form-control" style="background-color: #f8fafc; cursor: not-allowed;" min="{{ \Carbon\Carbon::parse($contract->end_date)->addDay()->format('Y-m-d') }}" value="{{ \Carbon\Carbon::parse($contract->end_date)->addMonths(6)->format('Y-m-d') }}" readonly required>
                        <div class="form-text text-muted" style="font-size: 11.5px;">Ngày kết thúc mới được cố định tự động khi bấm các nút chọn nhanh bên trên (+3, +6, +12 tháng).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Giá thuê mới (VNĐ/tháng)</label>
                        <div class="input-group">
                            <input type="text" name="new_rent" class="form-control" value="{{ number_format((float)$contract->rent, 0, ',', '.') }}" placeholder="Nhập giá thuê mới nếu có thay đổi">
                            <span class="input-group-text">đ/tháng</span>
                        </div>
                        <div class="form-text text-muted" style="font-size: 11.5px;">Để nguyên nếu giữ giá cũ theo hợp đồng hiện tại.</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Ghi chú thỏa thuận gia hạn</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Hai bên thống nhất gia hạn thêm 6 tháng, giữ nguyên mức giá và nội quy phòng..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4" style="background: #059669; border: none;">
                        Xác nhận gia hạn
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL THANH LÝ HỢP ĐỒNG (2 TRƯỜNG HỢP: ĐÚNG HẠN HOÀN CỌC & BỎ PHÒNG MẤT CỌC) --}}
<div class="modal fade" id="terminateContractModal" tabindex="-1" aria-labelledby="terminateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.15);">
            <div class="modal-header border-0 pb-0">
                <div>
                    <h5 class="modal-title fw-bold text-danger" id="terminateModalLabel">Thanh lý hợp đồng thuê phòng</h5>
                    <div class="small text-muted">Hợp đồng #{{ $contract->contract_code }} · Phòng: {{ $contract->room->name ?? '—' }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('owner.contracts.terminate', $contract) }}">
                @csrf
                @method('PUT')
                <div class="modal-body pt-3">
                    <div class="p-3 mb-3 rounded-3" style="background: #f8fafc; border: 1px solid #e2e8f0;">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Tiền cọc phòng ban đầu:</span>
                            <strong class="text-dark">{{ number_format((float)$contract->deposit) }} đ</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Người đứng tên hợp đồng:</span>
                            <strong class="text-dark">{{ $contract->tenant->name ?? 'Khách thuê' }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Hình thức thanh lý <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            @if(! $isExpiredOrDue)
                                <label class="p-2 border rounded-3 d-flex align-items-center gap-2" id="optNormalWrap" style="background: #f8fafc; border-color: #e2e8f0 !important; cursor: not-allowed; opacity: 0.6;" title="Chưa đến ngày hết hạn hợp đồng ({{ $endDate->format('d/m/Y') }})">
                                    <input type="radio" name="termination_type" id="termTypeNormal" value="normal" disabled>
                                    <div>
                                        <div class="fw-bold text-muted" style="font-size: 13.5px;">Hết hạn hợp đồng / Đúng hạn (Hoàn tiền cọc) <span class="badge bg-secondary ms-1">Chưa đến hạn</span></div>
                                        <div class="text-muted" style="font-size: 12px;">Chỉ áp dụng khi hợp đồng đến đúng ngày kết thúc ({{ $endDate->format('d/m/Y') }}).</div>
                                    </div>
                                </label>
                                <label class="p-2 border rounded-3 d-flex align-items-center gap-2 cursor-pointer" id="optEarlyWrap" style="background: #fef2f2; border-color: #fecaca; cursor: pointer;">
                                    <input type="radio" name="termination_type" id="termTypeEarly" value="early" checked>
                                    <div>
                                        <div class="fw-bold text-danger" style="font-size: 13.5px;">Bỏ phòng / Chấm dứt trước hạn (Không hoàn cọc)</div>
                                        <div class="text-muted" style="font-size: 12px;">Khách muốn chuyển đi trước ngày {{ $endDate->format('d/m/Y') }}. Tiền cọc bị khấu trừ toàn bộ theo điều khoản.</div>
                                    </div>
                                </label>
                            @else
                                <label class="p-2 border rounded-3 d-flex align-items-center gap-2 cursor-pointer" id="optNormalWrap" style="background: #f0fdf4; border-color: #bbf7d0 !important; cursor: pointer;">
                                    <input type="radio" name="termination_type" id="termTypeNormal" value="normal" checked>
                                    <div>
                                        <div class="fw-bold" style="font-size: 13.5px; color: #065f46;">Hết hạn hợp đồng / Đúng hạn (Hoàn tiền cọc)</div>
                                        <div class="text-muted" style="font-size: 12px;">Hợp đồng đã tới ngày kết thúc. Khách nhận lại tiền cọc sau khi trừ các chi phí nếu có.</div>
                                    </div>
                                </label>
                                <label class="p-2 border rounded-3 d-flex align-items-center gap-2 cursor-pointer" id="optEarlyWrap" style="background: #ffffff; border-color: #e2e8f0; cursor: pointer;">
                                    <input type="radio" name="termination_type" id="termTypeEarly" value="early">
                                    <div>
                                        <div class="fw-bold text-danger" style="font-size: 13.5px;">Bỏ phòng / Chấm dứt trước hạn (Không hoàn cọc)</div>
                                        <div class="text-muted" style="font-size: 12px;">Khách tự ý chuyển đi hoặc vi phạm bỏ phòng. Tiền cọc bị khấu trừ toàn bộ (0 đ hoàn lại).</div>
                                    </div>
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Ngày kết thúc / Trả phòng thực tế <span class="text-danger">*</span></label>
                        <input type="date" name="return_date" class="form-control" style="background-color: #f8fafc; cursor: not-allowed;" value="{{ now('Asia/Ho_Chi_Minh')->toDateString() }}" readonly required>
                        <div class="form-text text-muted" style="font-size: 11.5px;">Ngày trả phòng là ngày thanh lý. Có thể thanh lý từ ngày sau ngày tạo hợp đồng và không trước ngày bắt đầu thuê hoặc kỳ ghi điện nước gần nhất.</div>
                    </div>

                    <div class="border rounded-3 p-3 mb-3" style="background:#f8fafc">
                        <div class="fw-bold mb-1">Điện nước khi trả phòng</div>
                        <div class="small text-muted mb-2">Chỉ số cũ: {{ $finalReading['reading'] ? 'kỳ ghi gần nhất' : 'lúc bắt đầu hợp đồng' }}. Nhập số trên đồng hồ lúc trả phòng.</div>
                        @if($finalReading['uncertain'])
                            <div class="alert alert-warning">Có chỉ số kỳ cũ chưa xác nhận ngày hoặc chưa gắn hợp đồng. Hãy kiểm tra dữ liệu điện nước trước khi thanh lý.</div>
                        @endif
                        <div class="row g-2">
                            <div class="col-6"><label class="form-label small">Điện cũ (kWh)</label><input id="finalElectricOld" class="form-control" value="{{ $finalReading['electricity'] }}" readonly></div>
                            <div class="col-6"><label class="form-label small">Điện mới (kWh) *</label><input type="number" name="electricity_new" id="finalElectricNew" class="form-control" min="{{ $finalReading['electricity'] }}" step="0.01" required></div>
                            <div class="col-12"><label class="form-label small">Đơn giá điện (đ/kWh) *</label><input type="number" name="electricity_price" id="finalElectricPrice" class="form-control" min="0" step="0.01" value="{{ $finalReading['electricity_price'] }}" required></div>
                            <div class="col-6"><label class="form-label small">Nước cũ (m³)</label><input id="finalWaterOld" class="form-control" value="{{ $finalReading['water'] }}" readonly></div>
                            <div class="col-6"><label class="form-label small">Nước mới (m³) *</label><input type="number" name="water_new" id="finalWaterNew" class="form-control" min="{{ $finalReading['water'] }}" step="0.01" required></div>
                            <div class="col-12"><label class="form-label small">Đơn giá nước (đ/m³) *</label><input type="number" name="water_price" id="finalWaterPrice" class="form-control" min="0" step="0.01" value="{{ $finalReading['water_price'] }}" required></div>
                        </div>
                        <div class="fw-bold mt-3">Tiền điện nước: <span id="finalUtilityTotal">Nhập chỉ số mới để tính</span></div>
                        <div class="small text-muted">Nếu có phát sinh, hệ thống tạo hóa đơn riêng và thông báo cho người thuê thanh toán. Không tự trừ vào cọc.</div>
                    </div>

                    <div class="mb-3" id="deductionGroup">
                        <label class="form-label small fw-bold text-dark" id="deductionLabel">Số tiền khấu trừ cọc khác (vi phạm, hỏng đồ đạc)</label>
                        <div class="input-group">
                            <input type="number" id="terminateDeduction" name="deduction_amount" class="form-control" min="0" max="{{ (float)$contract->deposit }}" value="0" step="1000">
                            <span class="input-group-text">đ</span>
                        </div>
                        <div class="form-text text-muted" id="deductionHelp" style="font-size: 11.5px;">Tối đa không vượt quá số tiền cọc ({{ number_format((float)$contract->deposit) }} đ).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Lý do khấu trừ (nếu có)</label>
                        <input type="text" id="terminateReason" name="deduction_reason" class="form-control" placeholder="Ví dụ: Hỏng đồ đạc...">
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Ghi chú thanh lý & bàn giao</label>
                        <textarea name="note" class="form-control" rows="2" placeholder="Ví dụ: Đã nhận lại đủ chìa khóa, hiện trạng phòng sạch sẽ..."></textarea>
                    </div>

                    <div class="p-3 rounded-3 border d-flex justify-content-between align-items-center" id="refundBox" style="background: #ecfdf5; border-color: #a7f3d0 !important;">
                        <span class="fw-bold" id="refundBoxLabel" style="color: #065f46;">Số tiền cọc thực hoàn cho khách:</span>
                        <span id="refundPreview" class="fs-5 fw-bold text-success">{{ number_format((float)$contract->deposit) }} đ</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy bỏ</button>
                    <button type="submit" class="btn btn-danger fw-bold rounded-pill px-4">
                        Xác nhận thanh lý
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deposit = {{ (float)$contract->deposit }};
        const deductionInput = document.getElementById('terminateDeduction');
        const refundPreview = document.getElementById('refundPreview');
        const terminateReason = document.getElementById('terminateReason');
        const termTypeNormal = document.getElementById('termTypeNormal');
        const termTypeEarly = document.getElementById('termTypeEarly');
        const optNormalWrap = document.getElementById('optNormalWrap');
        const optEarlyWrap = document.getElementById('optEarlyWrap');
        const refundBox = document.getElementById('refundBox');
        const refundBoxLabel = document.getElementById('refundBoxLabel');

        const utilityFields = ['finalElectricOld', 'finalElectricNew', 'finalElectricPrice', 'finalWaterOld', 'finalWaterNew', 'finalWaterPrice'].map(id => document.getElementById(id));
        function updateFinalUtility() {
            const [eo, en, ep, wo, wn, wp] = utilityFields;
            const output = document.getElementById('finalUtilityTotal');
            if (!output || !en?.value || !wn?.value) { if (output) output.textContent = 'Nhập chỉ số mới để tính'; return; }
            const electric = Number(en.value) - Number(eo.value), water = Number(wn.value) - Number(wo.value);
            output.textContent = electric < 0 || water < 0 ? 'Chỉ số mới phải từ chỉ số cũ trở lên' : new Intl.NumberFormat('vi-VN').format(electric * Number(ep.value) + water * Number(wp.value)) + ' đ';
        }
        utilityFields.forEach(field => field?.addEventListener('input', updateFinalUtility));
        updateFinalUtility();

        function updateRefund() {
            if (termTypeEarly && termTypeEarly.checked) {
                if (deductionInput) {
                    deductionInput.value = deposit;
                    deductionInput.readOnly = true;
                }
                if (terminateReason && !terminateReason.value) {
                    terminateReason.value = 'Khách đơn phương chấm dứt hợp đồng trước hạn (không hoàn cọc)';
                }
                if (refundPreview) {
                    refundPreview.textContent = '0 đ';
                    refundPreview.className = 'fs-5 fw-bold text-danger';
                }
                if (refundBox) {
                    refundBox.style.background = '#fef2f2';
                    refundBox.style.borderColor = '#fecaca';
                }
                if (refundBoxLabel) {
                    refundBoxLabel.style.color = '#991b1b';
                }
                if (optEarlyWrap) {
                    optEarlyWrap.style.background = '#fef2f2';
                    optEarlyWrap.style.borderColor = '#fecaca';
                }
                if (optNormalWrap) {
                    optNormalWrap.style.background = '#ffffff';
                    optNormalWrap.style.borderColor = '#e2e8f0';
                }
            } else {
                if (deductionInput) {
                    deductionInput.readOnly = false;
                }
                let deduction = parseFloat(deductionInput ? deductionInput.value : 0) || 0;
                if (deduction > deposit) {
                    deduction = deposit;
                    if (deductionInput) deductionInput.value = deposit;
                }
                if (deduction < 0) {
                    deduction = 0;
                    if (deductionInput) deductionInput.value = 0;
                }
                const refund = Math.max(0, deposit - deduction);
                if (refundPreview) {
                    refundPreview.textContent = new Intl.NumberFormat('vi-VN').format(refund) + ' đ';
                    refundPreview.className = 'fs-5 fw-bold text-success';
                }
                if (refundBox) {
                    refundBox.style.background = '#ecfdf5';
                    refundBox.style.borderColor = '#a7f3d0';
                }
                if (refundBoxLabel) {
                    refundBoxLabel.style.color = '#065f46';
                }
                if (optNormalWrap) {
                    optNormalWrap.style.background = '#f0fdf4';
                    optNormalWrap.style.borderColor = '#bbf7d0';
                }
                if (optEarlyWrap) {
                    optEarlyWrap.style.background = '#ffffff';
                    optEarlyWrap.style.borderColor = '#e2e8f0';
                }
            }
        }

        if (termTypeNormal) termTypeNormal.addEventListener('change', updateRefund);
        if (termTypeEarly) termTypeEarly.addEventListener('change', updateRefund);
        if (deductionInput) deductionInput.addEventListener('input', updateRefund);
        updateRefund();

        // Gia hạn hợp đồng: Quick buttons
        const baseEndDate = new Date("{{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}T00:00:00");
        const newEndDateInput = document.getElementById('new_end_date');
        const quickExtendBtns = document.querySelectorAll('.quick-extend-btn');

        quickExtendBtns.forEach(btn => {
            btn.addEventListener('click', function () {
                quickExtendBtns.forEach(b => {
                    b.classList.remove('active', 'bg-success', 'text-white');
                });
                this.classList.add('active', 'bg-success', 'text-white');
                const months = parseInt(this.getAttribute('data-months')) || 6;
                const d = new Date(baseEndDate);
                d.setMonth(d.getMonth() + months);
                const yyyy = d.getFullYear();
                const mm = String(d.getMonth() + 1).padStart(2, '0');
                const dd = String(d.getDate()).padStart(2, '0');
                if (newEndDateInput) {
                    newEndDateInput.value = `${yyyy}-${mm}-${dd}`;
                }
            });
        });
    });
</script>
@endif

@endsection
