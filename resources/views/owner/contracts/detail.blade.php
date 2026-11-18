@extends('Layout.landlord')

@section('title', 'Chi tiết hợp đồng #' . $contract->contract_code . ' | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/tenant/contracts/show.css') }}">
    <style>
        .page-wrap {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px 20px 60px;
        }
        .back-link-custom {
            color: #64748b;
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
            transition: color 0.2s ease;
        }
        .back-link-custom:hover {
            color: #0e5b42;
        }
        .panel-card-head {
            background: #fafbfc;
            border-bottom: 1px solid #f1f4f3;
            padding: 16px 22px;
        }
        .meter-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .meter-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }
        .meter-icon.elec {
            background: #fef3c7;
            color: #d97706;
        }
        .meter-icon.water {
            background: #e0f2fe;
            color: #0284c7;
        }
        .meter-val {
            font-size: 18px;
            font-weight: 800;
            color: #0c2340;
        }
        .meter-lbl {
            font-size: 12px;
            color: #64748b;
            font-weight: 600;
            text-transform: uppercase;
        }
        .member-row-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 14px;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            margin-bottom: 10px;
            background: #ffffff;
            transition: all 0.2s ease;
        }
        .member-row-item:hover {
            border-color: #cbd5e1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .member-tag {
            font-size: 11px;
            padding: 2px 8px;
            border-radius: 6px;
            font-weight: 600;
        }
        .member-tag.leader {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .member-tag.guest {
            background: #f8fafc;
            color: #475569;
            border: 1px solid #e2e8f0;
        }
        .btn-outline-brand {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0c2340;
            font-weight: 700;
            font-size: 13px;
            border-radius: 10px;
            padding: 8px 16px;
            text-decoration: none;
            transition: all 0.2s ease;
        }
        .btn-outline-brand:hover {
            background: #f1f5f9;
            color: #0c2340;
        }
        .btn-brand-sm {
            background: #0e5b42;
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            border-radius: 10px;
            padding: 8px 16px;
            border: none;
            transition: all 0.2s ease;
        }
        .btn-brand-sm:hover {
            background: #093e2d;
            color: #fff;
        }
        @media print {
            .app-navbar, .back-link-custom, .action-buttons-group, .add-member-form, .btn-remove-member {
                display: none !important;
            }
            .page-wrap {
                max-width: 100% !important;
                padding: 0 !important;
            }
            .panel-card {
                border: 1px solid #000 !important;
                box-shadow: none !important;
                margin-bottom: 16px !important;
                page-break-inside: avoid;
            }
        }
    </style>
@endpush

@section('content')

@php
    $statusMap = [
        'active' => ['label' => 'Đang hiệu lực', 'class' => 'active'],
        'draft' => ['label' => 'Bản nháp', 'class' => 'draft'],
        'expired' => ['label' => 'Đã hết hạn', 'class' => 'expired'],
        'terminated' => ['label' => 'Đã thanh lý', 'class' => 'terminated'],
    ];
    $currStatus = $statusMap[$contract->status] ?? ['label' => $contract->status, 'class' => 'draft'];
@endphp

<main class="page-wrap">
    <a href="{{ route('owner.contracts.index') }}" class="back-link-custom">
        ← Quay lại danh sách hợp đồng
    </a>

    {{-- THÔNG BÁO --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-4" role="alert">
            {{ session('success') }}
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

    {{-- HEADER CARD --}}
    <div class="contract-header-card">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <span style="font-size: 24px;">📄</span>
                <h1 class="contract-header-title">
                    Hợp đồng <span style="color: #0e5b42;">#{{ $contract->contract_code }}</span>
                </h1>
                <span class="badge-status-contract {{ $currStatus['class'] }} ms-2">
                    {{ $currStatus['label'] }}
                </span>
            </div>
            <div class="contract-header-desc">
                Phòng: <strong>{{ $contract->room->name ?? '—' }}</strong> · Khu trọ: <strong>{{ $contract->room->property->name ?? '' }}</strong>
            </div>
        </div>

        <div class="action-buttons-group">
            <button type="button" class="btn-outline-brand d-inline-flex align-items-center gap-1" onclick="window.print()">
                🖨️ In hợp đồng
            </button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="row g-4">
        {{-- CỘT TRÁI (8 CỘT) --}}
        <div class="col-lg-8">
            {{-- 1. THÔNG TIN PHÒNG & GIÁ THUÊ --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <h2 class="panel-card-title">🏠 Thông tin phòng & Chi phí thuê</h2>
                </div>
                <div class="info-grid-custom">
                    <div>
                        <div class="cell-sub">Phòng thuê</div>
                        <div class="info-value">{{ $contract->room->name ?? '—' }}</div>
                        <small class="text-muted">{{ $contract->room->property->name ?? '' }}</small>
                    </div>
                    <div>
                        <div class="cell-sub">Tiền thuê hàng tháng</div>
                        <div class="info-value price-highlight text-success">
                            {{ number_format((float)$contract->rent) }} đ/tháng
                        </div>
                    </div>
                    <div>
                        <div class="cell-sub">Tiền đặt cọc</div>
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
                        <div class="cell-sub">Chu kỳ thanh toán</div>
                        <div class="info-value">
                            {{ $contract->payment_cycle === 'quarterly' ? 'Hàng quý (3 tháng)' : 'Hàng tháng' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. CHỐT SỐ ĐIỆN NƯỚC BAN ĐẦU --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <h2 class="panel-card-title">⚡ Chỉ số điện, nước lúc bàn giao</h2>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="meter-box">
                                <div class="meter-icon elec">⚡</div>
                                <div>
                                    <div class="meter-lbl">Số điện ban đầu</div>
                                    <div class="meter-val">{{ number_format((float)$contract->initial_electricity_reading) }} <span class="fs-6 fw-normal text-muted">kWh</span></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="meter-box">
                                <div class="meter-icon water">💧</div>
                                <div>
                                    <div class="meter-lbl">Số nước ban đầu</div>
                                    <div class="meter-val">{{ number_format((float)$contract->initial_water_reading) }} <span class="fs-6 fw-normal text-muted">m³</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. DỊCH VỤ CỐ ĐỊNH KÈM HỢP ĐỒNG --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <h2 class="panel-card-title">📦 Dịch vụ sử dụng cố định</h2>
                </div>
                <div class="service-table-wrap">
                    @if($contract->services && $contract->services->isNotEmpty())
                        <table class="service-table">
                            <thead>
                                <tr>
                                    <th>Dịch vụ</th>
                                    <th>Số lượng</th>
                                    <th>Đơn giá</th>
                                    <th class="text-end">Thành tiền / tháng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $totalService = 0; @endphp
                                @foreach($contract->services as $svc)
                                    @php
                                        $qty = (float)($svc->pivot->quantity ?? 1);
                                        $price = (float)($svc->pivot->unit_price ?? $svc->price);
                                        $lineTotal = $qty * $price;
                                        $totalService += $lineTotal;
                                    @endphp
                                    <tr>
                                        <td>
                                            <strong>{{ $svc->name }}</strong>
                                        </td>
                                        <td>{{ $qty }} {{ $svc->unit }}</td>
                                        <td>{{ number_format($price) }} đ/{{ $svc->unit }}</td>
                                        <td class="text-end fw-bold">{{ number_format($lineTotal) }} đ</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="fw-bold text-end pt-3">Tổng phí dịch vụ cố định:</td>
                                    <td class="text-end fw-bold text-success pt-3">{{ number_format($totalService) }} đ/tháng</td>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="p-4 text-center text-muted">
                            Hợp đồng này không đăng ký dịch vụ cố định (điện và nước sẽ tính theo chỉ số phát sinh hàng tháng).
                        </div>
                    @endif
                </div>
            </div>

            {{-- 4. ĐIỀU KHOẢN HỢP ĐỒNG --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <h2 class="panel-card-title">📜 Điều khoản hợp đồng</h2>
                </div>
                <div class="p-4">
                    @if(!empty($contract->terms))
                        <div class="p-3 bg-light rounded-3 text-secondary" style="white-space: pre-line; line-height: 1.6; font-size: 13.5px;">
                            {{ $contract->terms }}
                        </div>
                    @else
                        <p class="text-muted mb-0">Áp dụng theo quy chế thuê phòng và quy định nội bộ khu trọ tiêu chuẩn.</p>
                    @endif
                </div>
            </div>

            {{-- 5. THÔNG TIN THANH LÝ (NẾU ĐÃ THANH LÝ) --}}
            @if($contract->status === 'terminated' && $contract->termination)
                <div class="panel-card border-danger">
                    <div class="panel-card-head bg-danger-subtle">
                        <h2 class="panel-card-title text-danger">⚠️ Biên bản thanh lý hợp đồng</h2>
                    </div>
                    <div class="info-grid-custom">
                        <div>
                            <div class="cell-sub">Ngày trả phòng</div>
                            <div class="info-value">{{ \Carbon\Carbon::parse($contract->termination->return_date)->format('d/m/Y') }}</div>
                        </div>
                        <div>
                            <div class="cell-sub">Tiền cọc ban đầu</div>
                            <div class="info-value">{{ number_format((float)$contract->termination->deposit_amount) }} đ</div>
                        </div>
                        <div>
                            <div class="cell-sub">Tiền khấu trừ</div>
                            <div class="info-value text-danger">- {{ number_format((float)$contract->termination->deduction_amount) }} đ</div>
                            @if($contract->termination->deduction_reason)
                                <small class="text-muted">{{ $contract->termination->deduction_reason }}</small>
                            @endif
                        </div>
                        <div>
                            <div class="cell-sub">Tiền cọc hoàn trả</div>
                            <div class="info-value text-success fw-bold">{{ number_format((float)$contract->termination->refund_amount) }} đ</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- CỘT PHẢI (4 CỘT) --}}
        <div class="col-lg-4">
            {{-- THÔNG TIN NGƯỜI THUÊ (CHỦ HỢP ĐỒNG) --}}
            <div class="panel-card">
                <div class="panel-card-head">
                    <h2 class="panel-card-title">👤 Đại diện thuê (Chủ HĐ)</h2>
                </div>
                <div class="owner-box-content">
                    <div class="person-item mb-3">
                        <div class="person-avatar" style="background: #e0f2fe; color: #0284c7; font-size: 16px;">
                            {{ mb_strtoupper(mb_substr($contract->tenant->name ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="person-name">{{ $contract->tenant->name ?? 'Người thuê' }}</div>
                            <div class="small text-muted">{{ $contract->tenant->phone ?? 'Chưa có SĐT' }}</div>
                            <div class="small text-muted">{{ $contract->tenant->email ?? '' }}</div>
                        </div>
                    </div>

                    @if(!empty($contract->tenant->identity_card))
                        <div class="p-2 mb-3 bg-light rounded-3 text-center border">
                            <span class="small text-muted d-block">Số CCCD / CMND</span>
                            <span class="fw-bold" style="color: #0c2340; letter-spacing: 0.5px;">{{ $contract->tenant->identity_card }}</span>
                        </div>
                    @endif

                    @if(!empty($contract->tenant->phone))
                        <a href="tel:{{ $contract->tenant->phone }}" class="btn-call-owner">
                            📞 Gọi điện cho người thuê
                        </a>
                    @endif
                </div>
            </div>

            {{-- DANH SÁCH NGƯỜI Ở CÙNG --}}
            <div class="panel-card">
                <div class="panel-card-head d-flex justify-content-between align-items-center">
                    <h2 class="panel-card-title">👥 Người ở cùng</h2>
                    <span class="badge bg-light text-dark border">
                        {{ 1 + $contract->members->count() }}/{{ $contract->room->max_people ?? '?' }} người
                    </span>
                </div>
                <div class="p-3">
                    {{-- Đại diện hợp đồng --}}
                    <div class="member-row-item">
                        <div>
                            <div class="fw-bold" style="font-size: 13.5px; color: #0c2340;">{{ $contract->tenant->name }}</div>
                            <div class="small text-muted">{{ $contract->tenant->phone ?? '—' }}</div>
                        </div>
                        <span class="member-tag leader">Đại diện HĐ</span>
                    </div>

                    {{-- Danh sách thành viên khác --}}
                    @foreach($contract->members as $member)
                        <div class="member-row-item">
                            <div>
                                <div class="fw-bold" style="font-size: 13.5px; color: #0c2340;">{{ $member->name }}</div>
                                <div class="small text-muted">
                                    CCCD: {{ $member->identity_card }}
                                    @if($member->phone) · SĐT: {{ $member->phone }} @endif
                                    @if($member->relationship) ({{ $member->relationship }}) @endif
                                </div>
                            </div>
                            @if($contract->status !== 'terminated')
                                <form method="POST" action="{{ route('owner.contracts.members', $contract) }}" class="btn-remove-member">
                                    @csrf
                                    <input type="hidden" name="action" value="remove">
                                    <input type="hidden" name="member_id" value="{{ $member->id }}">
                                    <button type="submit" class="btn btn-sm btn-link text-danger p-0" title="Xóa người ở cùng" onclick="return confirm('Xác nhận xóa người ở cùng này?')">
                                        🗑️
                                    </button>
                                </form>
                            @endif
                        </div>
                    @endforeach

                    {{-- Form thêm người ở cùng --}}
                    @if($contract->status !== 'terminated')
                        @php
                            $canAddMore = (1 + $contract->members->count()) < ($contract->room->max_people ?? 10);
                        @endphp

                        @if($canAddMore)
                            <hr class="my-3 text-muted">
                            <div class="add-member-form">
                                <div class="fw-bold mb-2" style="font-size: 13px; color: #0c2340;">+ Thêm người ở cùng phòng</div>
                                <form method="POST" action="{{ route('owner.contracts.members', $contract) }}" class="row g-2">
                                    @csrf
                                    <input type="hidden" name="action" value="add">
                                    <div class="col-12">
                                        <input type="text" name="name" class="form-control form-control-sm" placeholder="Họ và tên *" value="{{ old('name') }}" required maxlength="100">
                                    </div>
                                    <div class="col-12">
                                        <input type="text" name="identity_card" class="form-control form-control-sm" placeholder="Số CCCD / CMND *" value="{{ old('identity_card') }}" required maxlength="20">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="phone" class="form-control form-control-sm" placeholder="Số điện thoại" value="{{ old('phone') }}" maxlength="20">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="relationship" class="form-control form-control-sm" placeholder="Quan hệ (bạn, em...)" value="{{ old('relationship') }}" maxlength="50">
                                    </div>
                                    <div class="col-12 mt-2">
                                        <button type="submit" class="btn-brand-sm w-100">Lưu người ở cùng</button>
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
        </div>
    </div>
</main>

@endsection
