
@extends('Layout.landlord')

@section('title', 'Hồ sơ người thuê | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/show_tenant.css') }}">
@endpush

@section('content')

@php
    /*
    |--------------------------------------------------------------------------
    | Khởi tạo dữ liệu an toàn
    |--------------------------------------------------------------------------
    */

    $contract = $contract ?? null;

    $room = $contract ? $contract->room : null;
    $property = $room ? $room->property : null;

    $members = $contract && $contract->members
        ? $contract->members
        : collect();

    $fullName = $tenant->name ?? 'Chưa cập nhật';

    $nameParts = array_values(
        array_filter(explode(' ', trim($fullName)))
    );

    $initials = '';

    foreach (array_slice($nameParts, 0, 2) as $word) {
        $initials .= mb_substr($word, 0, 1);
    }

    $initials = $initials ?: 'NT';

    /*
    |--------------------------------------------------------------------------
    | Thông tin phòng và hợp đồng
    |--------------------------------------------------------------------------
    */

    $roomName = $room
        ? ($room->name ?? $room->room_code ?? 'Chưa cập nhật')
        : 'Chưa thuê phòng';

    $contractCode = $contract
        ? ($contract->contract_code ?? $contract->code ?? $contract->id)
        : null;

    $startDate = $contract->start_date ?? null;
    $endDate = $contract->end_date ?? null;

    $rentPrice = $contract
        ? ($contract->rent
            ?? $contract->rent_price
            ?? $contract->monthly_rent
            ?? $contract->rental_price
            ?? null)
        : null;

    $deposit = $contract
        ? ($contract->deposit
            ?? $contract->deposit_amount
            ?? null)
        : null;

    /*
    |--------------------------------------------------------------------------
    | Hàm định dạng ngày
    |--------------------------------------------------------------------------
    */

    $formatDate = function ($date) {
        if (empty($date)) {
            return 'Chưa cập nhật';
        }

        try {
            return \Illuminate\Support\Carbon::parse($date)->format('d/m/Y');
        } catch (\Throwable $e) {
            return 'Chưa cập nhật';
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Trạng thái hợp đồng
    |--------------------------------------------------------------------------
    */

    $contractStatus = $contract->status ?? null;

    $isActive = $contract && in_array(
        $contractStatus,
        ['active', 'approved', 'signed'],
        true
    );

    /*
    |--------------------------------------------------------------------------
    | Tính thời hạn hợp đồng
    |--------------------------------------------------------------------------
    */

    $duration = null;

    if ($startDate && $endDate) {
        try {
            $start = \Illuminate\Support\Carbon::parse($startDate);
            $end = \Illuminate\Support\Carbon::parse($endDate);

            $duration = (int) $start->diffInMonths($end);
        } catch (\Throwable $e) {
            $duration = null;
        }
    }
@endphp

<div class="page-wrap">

    {{-- QUAY LẠI --}}
    <a href="{{ url('/owner/tenants') }}" class="back-link">
        <i class="bi bi-arrow-left" aria-hidden="true"></i> Danh sách người thuê
    </a>

    @if(session('success'))
        <div class="alert alert-success" role="status">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    {{-- HỒ SƠ NGƯỜI THUÊ --}}
    <div class="profile-card">
        <div class="profile-header">

            <div class="profile-left">

                <div class="avatar-large">
                    {{ $initials }}
                </div>

                <div>
                    <div class="profile-eyebrow">HỒ SƠ NGƯỜI THUÊ</div>
                    <div class="profile-name">
                        {{ $fullName }}
                    </div>

                    <div class="profile-subtitle">
                        {{ $contract ? 'Người đứng tên hợp đồng' : 'Chưa có hợp đồng' }}
                        · {{ $roomName }}
                    </div>

                    <div class="profile-badges">

                        <span class="{{ $isActive ? 'badge-soft-success' : 'badge-soft-neutral' }}">
                            ● {{ $isActive ? 'Đang ở' : ($contract ? 'Chưa xác nhận' : 'Chưa có hợp đồng') }}
                        </span>

                        <span class="badge-soft-primary">
                            Người thuê chính
                        </span>

                    </div>
                </div>

            </div>

           <a href="{{ route('owner.tenants.edit', ['id' => $tenant->id]) }}" class="btn-edit">
                    <i class="bi bi-pencil-square" aria-hidden="true"></i> Chỉnh sửa hồ sơ
                </a>

        </div>
    </div>

    {{-- NỘI DUNG --}}
    <div class="content-grid">

        {{-- CỘT TRÁI --}}
        <div>

            {{-- THÔNG TIN CÁ NHÂN --}}
            <div class="panel-modern">

                <div class="panel-title">
                    <span class="section-icon"><i class="bi bi-person" aria-hidden="true"></i></span><span>Thông tin cá nhân<small>Thông tin liên hệ và giấy tờ</small></span>
                </div>

                <div class="info-row">
                    <div class="info-label">Họ và tên</div>
                    <div class="info-value">
                        {{ $fullName }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Giới tính</div>
                    <div class="info-value">
                        @php
                            $genderLabels = ['female' => 'Nữ', 'male' => 'Nam', 'other' => 'Khác'];
                        @endphp
                        {{ $genderLabels[$tenant->gender ?? ''] ?? ($tenant->gender ?: 'Chưa cập nhật') }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Ngày sinh</div>
                    <div class="info-value">
                        {{ $formatDate($tenant->date_of_birth ?? $tenant->birth_date ?? null) }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Số CCCD</div>
                    <div class="info-value">
                        {{ $tenant->identity_card
                            ?? $tenant->identity_number
                            ?? $tenant->cccd
                            ?? $tenant->citizen_id
                            ?? 'Chưa cập nhật' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Số điện thoại</div>
                    <div class="info-value">
                        {{ $tenant->phone ?? 'Chưa cập nhật' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Email</div>
                    <div class="info-value">
                        {{ $tenant->email ?? 'Chưa cập nhật' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Địa chỉ thường trú</div>
                    <div class="info-value">
                        {{ $tenant->address
                            ?? $tenant->permanent_address
                            ?? 'Chưa cập nhật' }}
                    </div>
                </div>

            </div>

            {{-- HỢP ĐỒNG --}}
            <div class="panel-modern">

                <div class="panel-title">
                    <span class="section-icon"><i class="bi bi-file-earmark-text" aria-hidden="true"></i></span><span>Hợp đồng thuê hiện tại<small>Chi tiết thỏa thuận thuê phòng</small></span>
                </div>

                @if($contract)

                    <div class="contract-box">

                        <div class="contract-code">
                            Hợp đồng #{{ $contractCode }}
                        </div>

                        <div class="contract-item">
                            <span class="contract-label">Phòng</span>
                            <span class="contract-value">
                                {{ $roomName }}
                            </span>
                        </div>

                        <div class="contract-item">
                            <span class="contract-label">Ngày bắt đầu</span>
                            <span class="contract-value">
                                {{ $formatDate($startDate) }}
                            </span>
                        </div>

                        <div class="contract-item">
                            <span class="contract-label">Ngày kết thúc</span>
                            <span class="contract-value">
                                {{ $formatDate($endDate) }}
                            </span>
                        </div>

                        <div class="contract-item">
                            <span class="contract-label">Tiền thuê</span>
                            <span class="contract-value">
                                @if($rentPrice !== null)
                                    {{ number_format((float) $rentPrice, 0, ',', '.') }} đ / tháng
                                @else
                                    Chưa cập nhật
                                @endif
                            </span>
                        </div>

                        <div class="contract-item">
                            <span class="contract-label">Tiền cọc</span>
                            <span class="contract-value">
                                @if($deposit !== null)
                                    {{ number_format((float) $deposit, 0, ',', '.') }} đ
                                @else
                                    Chưa cập nhật
                                @endif
                            </span>
                        </div>

                    </div>

                    <div class="mt-3">
                        <a href="{{ url('/owner/contracts/' . $contract->id) }}"
                           class="btn btn-action">
                            Xem chi tiết hợp đồng →
                        </a>
                    </div>

                @else

                    <div class="empty-state">
                        Người thuê này chưa có hợp đồng phù hợp.
                    </div>

                @endif

            </div>

        </div>

        {{-- CỘT PHẢI --}}
        <div>

            {{-- THÔNG TIN LƯU TRÚ --}}
            <div class="panel-modern">

                <div class="panel-title">
                    <span class="section-icon"><i class="bi bi-house-door" aria-hidden="true"></i></span><span>Thông tin lưu trú<small>Phòng và thời gian thuê</small></span>
                </div>

                <div class="info-row">
                    <div class="info-label">Phòng đang thuê</div>
                    <div class="info-value">
                        {{ $roomName }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Nhà trọ</div>
                    <div class="info-value">
                        {{ $property->name ?? 'Chưa cập nhật' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Ngày vào ở</div>
                    <div class="info-value">
                        {{ $formatDate($startDate) }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Thời hạn</div>
                    <div class="info-value">
                        {{ $duration !== null ? $duration . ' tháng' : 'Chưa cập nhật' }}
                    </div>
                </div>

                <div class="info-row">
                    <div class="info-label">Trạng thái</div>
                    <div class="info-value">

                        <span class="{{ $isActive ? 'badge-soft-success' : 'badge-soft-neutral' }}">
                            {{ $isActive
                                ? 'Đang ở'
                                : ($contract ? 'Chưa xác nhận' : 'Chưa thuê phòng') }}
                        </span>

                    </div>
                </div>

            </div>

            {{-- NGƯỜI Ở CÙNG --}}
            <div class="panel-modern">

                <div class="member-heading">

                    <div class="panel-title">
                        <span class="section-icon"><i class="bi bi-people" aria-hidden="true"></i></span><span>Người ở cùng<small>Thành viên trong hợp đồng</small></span>
                    </div>

                    <span class="text-muted small">
                        {{ $members->count() }} người
                    </span>

                </div>

                @forelse($members as $member)

                    @php
                        $memberName = $member->name ?? 'Chưa cập nhật';

                        $memberParts = array_values(
                            array_filter(explode(' ', trim($memberName)))
                        );

                        $memberInitials = '';

                        foreach (array_slice($memberParts, 0, 2) as $word) {
                            $memberInitials .= mb_substr($word, 0, 1);
                        }

                        $memberInitials = $memberInitials ?: 'NT';
                    @endphp

                    <div class="member-item">

                        <div class="member-left">

                            <div class="member-avatar">
                                {{ $memberInitials }}
                            </div>

                            <div>
                                <div class="member-name">
                                    {{ $memberName }}
                                </div>

                                <div class="member-role">
                                    {{ $member->phone ?? 'Chưa có số điện thoại' }}
                                </div>
                            </div>

                        </div>

                        <span class="member-role">
                            Người ở cùng
                        </span>

                    </div>

                @empty

                    <div class="empty-state">
                        Chưa có người ở cùng trong hợp đồng này.
                    </div>

                @endforelse

                @if($contract)

                    <button type="button" class="btn btn-action w-100 mt-3" data-bs-toggle="modal" data-bs-target="#addMemberModal">
                        + Thêm người ở cùng
                    </button>

                @endif

            </div>

            {{-- GHI CHÚ --}}
            <div class="panel-modern">

                <div class="panel-title">
                    <span class="section-icon"><i class="bi bi-journal-text" aria-hidden="true"></i></span><span>Ghi chú<small>Thông tin quản lý</small></span>
                </div>

                <div class="note-box">

                    @if($contract)

                        Người thuê chính đứng tên hợp đồng
                        #{{ $contractCode }}. Các thông tin người ở cùng
                        được quản lý theo hợp đồng thuê.

                    @else

                        Người thuê chưa có hợp đồng gắn với chủ trọ hiện tại.

                    @endif

                </div>

            </div>

        </div>

    </div>

</div>

@if($contract)
<div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 18px; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.12);">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="addMemberModalLabel" style="color: #0c2340;">+ Thêm người ở cùng phòng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('owner.contracts.members', $contract) }}">
                @csrf
                <input type="hidden" name="action" value="add">
                <div class="modal-body pt-3">
                    <p class="text-muted small mb-3">Người ở cùng sẽ được thêm vào hợp đồng <strong>#{{ $contractCode }}</strong> (Phòng: <strong>{{ $roomName }}</strong>).</p>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Họ và tên <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Ví dụ: Nguyễn Văn A" required maxlength="100">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Số CCCD / CMND <span class="text-danger">*</span></label>
                        <input type="text" name="identity_card" class="form-control" placeholder="Nhập số CCCD/CMND" required maxlength="20">
                    </div>
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Số điện thoại</label>
                            <input type="text" name="phone" class="form-control" placeholder="0901234567" maxlength="20">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-dark">Quan hệ</label>
                            <input type="text" name="relationship" class="form-control" placeholder="Bạn, Em..." maxlength="50">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success fw-bold rounded-pill px-4" style="background: #059669; border: none;">Lưu người ở cùng</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection
