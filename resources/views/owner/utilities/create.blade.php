@extends('Layout.landlord')

@section('title', 'Quản lý chỉ số điện nước | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/utilities/create.css') }}">
    <style>
        .source-hint {
            font-size: 11.5px;
            color: #20584f;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .calc-feedback {
            font-size: 12px;
            font-weight: 700;
            margin-top: 5px;
            min-height: 18px;
        }
        .ai-banner-badge {
            background: #ede7f6;
            color: #5e35b1;
            font-weight: 700;
            font-size: 11px;
            padding: 3px 8px;
            border-radius: 6px;
            display: inline-block;
        }
    </style>
@endpush

@section('content')

@php
    $eOldVal = old('electricity_old', $reading?->electricity_old ?? ($initialPrevious['electricity_old'] ?? 0));
    $wOldVal = old('water_old', $reading?->water_old ?? ($initialPrevious['water_old'] ?? 0));
    $ePriceVal = old('electricity_price', $reading?->electricity_price ?? ($initialPrevious['electricity_price'] ?? 3500));
    $wPriceVal = old('water_price', $reading?->water_price ?? ($initialPrevious['water_price'] ?? 20000));
    $sourceVal = $reading ? 'Bản ghi hiện tại' : ($initialPrevious['source'] ?? 'Tự động từ hệ thống');
    $alreadyExists = ! $reading && ($initialPrevious['already_exists'] ?? false);
    $existingId = $initialPrevious['existing_id'] ?? null;
    $existingMonth = $initialPrevious['existing_month'] ?? null;
@endphp

<div class="page-wrap">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h2 class="page-title">{{ $reading ? 'Cập nhật chỉ số điện nước' : 'Ghi chỉ số điện nước' }}</h2>
            <div class="page-desc">Chủ nhà <span> / Điện nước / {{ $reading ? 'Cập nhật' : 'Ghi mới' }}</span></div>
        </div>

        <div class="header-actions d-flex gap-2">
            <a href="{{ route('owner.utilities.index', array_filter(['month' => $prefillMonth])) }}" class="btn-outline-brand">
                ← Quay lại
            </a>
            <button type="submit" form="readingForm" class="btn-brand">
                💾 {{ $reading ? 'Cập nhật' : 'Lưu chỉ số' }}
            </button>
        </div>
    </div>

    <!-- FORM START -->
    @if(isset($errors) && $errors->any())
        <div class="alert alert-danger mb-3">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if($rooms->isEmpty())
        <div class="alert alert-warning">Chưa có phòng nào thuộc khu trọ của bạn. Hãy tạo phòng trước.</div>
    @endif
    <form id="readingForm" method="POST" action="{{ $reading ? route('owner.utilities.update', $reading) : route('owner.utilities.store') }}">
        @csrf
        @if($reading)
            @method('PUT')
        @endif
        <div class="form-grid">

            <!-- COT TRAI -->
            <div class="col-left">

                <!-- PHONG + THANG -->
                <div class="card">
                    <div class="card-header">
                        <span>🏠</span> Phòng &amp; kỳ ghi
                    </div>

                    <div class="row-2">
                        <div class="form-group">
                            <label>Phòng <span>*</span></label>
                            @if($reading)
                                <input type="text" class="custom-input" value="{{ $reading->room->name ?? '' }} ({{ $reading->room->room_code ?? '' }})" disabled>
                            @else
                                <select name="room_id" id="select-room" class="custom-input" required>
                                    <option value="">-- Chọn phòng --</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ (string) old('room_id', $prefillRoomId) === (string) $room->id ? 'selected' : '' }}>{{ $room->name }} · {{ $room->property->name ?? '' }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                        <div class="form-group">
                            <label>Tháng ghi chỉ số <span>*</span></label>
                            <input type="month" name="month" id="input-month" class="custom-input" value="{{ old('month', $prefillMonth) }}" lang="vi" title="Chọn tháng và năm ghi chỉ số" required>
                        </div>
                    </div>

                    <!-- CANH BAO TRUNG KY -->
                    <div id="duplicate-warning" class="alert alert-warning align-items-center justify-content-between mt-2 mb-0" style="display: {{ $alreadyExists ? 'flex' : 'none' }}; background: #fff8e6; border: 1px solid #ffd591; color: #873800; border-radius: 12px; padding: 12px 16px;">
                        <div>⚠️ Phòng này đã có chỉ số tháng <strong id="duplicate-month">{{ $existingMonth ?? '' }}</strong> rồi!</div>
                        <a id="duplicate-edit-link" href="{{ $existingId ? route('owner.utilities.create', ['id' => $existingId]) : '#' }}" class="btn-brand" style="padding: 6px 14px; font-size: 12px; text-decoration: none;">✏️ Sửa bản ghi đó</a>
                    </div>
                </div>

                <!-- DIEN -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><span>⚡</span> Chỉ số điện</div>
                        <span class="source-hint" id="e-source-text">🗄️ Nguồn: {{ $sourceVal }}</span>
                    </div>

                    <div class="row-3">
                        <div class="form-group">
                            <label>Chỉ số cũ</label>
                            <input type="number" step="0.01" min="0" name="electricity_old" id="electricity_old" class="custom-input" value="{{ $eOldVal }}" placeholder="Tự động từ CSDL">
                        </div>
                        <div class="form-group">
                            <label>Chỉ số mới <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="electricity_new" id="electricity_new" class="custom-input" value="{{ old('electricity_new', $reading?->electricity_new) }}" placeholder="Nhập số mới..." required autofocus>
                            <div id="e-calc-feedback" class="calc-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label>Đơn giá (đ/kWh) <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="electricity_price" id="electricity_price" class="custom-input" value="{{ $ePriceVal }}" required>
                        </div>
                    </div>
                </div>

                <!-- NUOC -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><span>💧</span> Chỉ số nước</div>
                        <span class="source-hint" id="w-source-text">🗄️ Nguồn: {{ $sourceVal }}</span>
                    </div>

                    <div class="row-3">
                        <div class="form-group">
                            <label>Chỉ số cũ</label>
                            <input type="number" step="0.01" min="0" name="water_old" id="water_old" class="custom-input" value="{{ $wOldVal }}" placeholder="Tự động từ CSDL">
                        </div>
                        <div class="form-group">
                            <label>Chỉ số mới <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="water_new" id="water_new" class="custom-input" value="{{ old('water_new', $reading?->water_new) }}" placeholder="Nhập số mới..." required>
                            <div id="w-calc-feedback" class="calc-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label>Đơn giá (đ/m³) <span>*</span></label>
                            <input type="number" step="0.01" min="0" name="water_price" id="water_price" class="custom-input" value="{{ $wPriceVal }}" required>
                        </div>
                    </div>
                </div>

            </div>

            <!-- COT PHAI: TAM TINH & AI SUGGESTION -->
            <div class="col-right">

                <!-- BANG TAM TINH CHI PHI -->
                <div class="card" style="border-top: 4px solid var(--green);">
                    <div class="card-header">
                        <span>🧾</span> Tạm tính hóa đơn
                    </div>
                    <div style="font-size: 13.5px;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>⚡ Điện tiêu thụ:</span>
                            <span id="summary-e-diff" style="font-weight: 700;">0 kWh</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-muted small mb-2 ps-3">
                            <span>Đơn giá:</span>
                            <span id="summary-e-rate">0đ / kWh</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Tiền điện:</span>
                            <strong id="summary-e-money" style="color: var(--green-dark);">0đ</strong>
                        </div>

                        <hr style="border: 0; border-top: 1px dashed var(--border); margin: 12px 0;">

                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>💧 Nước tiêu thụ:</span>
                            <span id="summary-w-diff" style="font-weight: 700;">0 m³</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-muted small mb-2 ps-3">
                            <span>Đơn giá:</span>
                            <span id="summary-w-rate">0đ / m³</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted">Tiền nước:</span>
                            <strong id="summary-w-money" style="color: var(--green-dark);">0đ</strong>
                        </div>

                        <div class="p-3 mt-3" style="background: var(--green-soft); border-radius: 12px; border: 1px solid rgba(32,88,79,0.15);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span style="font-weight: 800; color: var(--green-dark); font-size: 13px;">TỔNG TẠM TÍNH:</span>
                                <strong id="summary-grand-total" style="font-size: 20px; color: var(--green-dark); font-weight: 900;">0đ</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GOI Y AI GEMINI SCANNER -->
                <div class="card" style="background: linear-gradient(135deg, #f8f9ff 0%, #fff7f2 100%); border: 1px dashed #d1c4e9;">
                    <div class="card-header" style="color: #512da8; border-bottom-color: rgba(81,45,168,0.15);">
                        <span>📸 🤖</span> AI đọc công tơ
                        <span class="ai-banner-badge ms-auto">Đang phát triển</span>
                    </div>
                    <div style="font-size: 12.5px; color: #555; line-height: 1.6;">
                        <p class="mb-2">Thay vì nhập tay, bạn có thể tải ảnh chụp đồng hồ điện/nước. Gemini Vision AI sẽ quét và điền số tự động!</p>
                        <div class="p-2 text-center" style="background: rgba(255,255,255,0.85); border: 1px dashed #b39ddb; border-radius: 10px; cursor: pointer;" onclick="alert('Tính năng chụp ảnh công tơ AI Gemini đang sẵn sàng được tích hợp!');">
                            <span style="font-size: 20px;">📷</span><br>
                            <span style="font-weight: 600; color: #5e35b1;">Tải ảnh chụp công tơ lên</span>
                        </div>
                    </div>
                </div>

                <!-- LUU Y -->
                <div class="card">
                    <div class="card-header">
                        <span>📌</span> Lưu ý
                    </div>
                    <div class="form-group mb-0">
                        <label class="text-muted" style="font-weight:500; line-height:1.7; font-size: 12px;">
                            • Chỉ số cũ được lấy tự động từ kỳ trước trong CSDL.<br>
                            • Chỉ số mới không được thấp hơn chỉ số cũ.<br>
                            • Mỗi phòng mỗi tháng chỉ ghi 1 lần.
                        </label>
                    </div>
                </div>
            </div>

        </div>
    </form>
    <!-- FORM END -->

</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isEditing = {{ $reading ? 'true' : 'false' }};
    const selectRoom = document.getElementById('select-room');
    const inputMonth = document.getElementById('input-month');
    const inputEOld = document.getElementById('electricity_old');
    const inputENew = document.getElementById('electricity_new');
    const inputEPrice = document.getElementById('electricity_price');
    const inputWOld = document.getElementById('water_old');
    const inputWNew = document.getElementById('water_new');
    const inputWPrice = document.getElementById('water_price');

    const duplicateWarning = document.getElementById('duplicate-warning');
    const duplicateMonthSpan = document.getElementById('duplicate-month');
    const duplicateEditLink = document.getElementById('duplicate-edit-link');

    const eSourceText = document.getElementById('e-source-text');
    const wSourceText = document.getElementById('w-source-text');
    const eCalcFeedback = document.getElementById('e-calc-feedback');
    const wCalcFeedback = document.getElementById('w-calc-feedback');

    const summaryEDiff = document.getElementById('summary-e-diff');
    const summaryERate = document.getElementById('summary-e-rate');
    const summaryEMoney = document.getElementById('summary-e-money');
    const summaryWDiff = document.getElementById('summary-w-diff');
    const summaryWRate = document.getElementById('summary-w-rate');
    const summaryWMoney = document.getElementById('summary-w-money');
    const summaryGrandTotal = document.getElementById('summary-grand-total');

    function formatNumber(num) {
        return new Intl.NumberFormat('vi-VN').format(Math.round(num));
    }

    function calculate() {
        const eOld = parseFloat(inputEOld?.value) || 0;
        const eNewVal = inputENew?.value;
        const eNew = (eNewVal !== '' && eNewVal !== null && eNewVal !== undefined) ? parseFloat(eNewVal) : null;
        const ePrice = parseFloat(inputEPrice?.value) || 0;

        const wOld = parseFloat(inputWOld?.value) || 0;
        const wNewVal = inputWNew?.value;
        const wNew = (wNewVal !== '' && wNewVal !== null && wNewVal !== undefined) ? parseFloat(wNewVal) : null;
        const wPrice = parseFloat(inputWPrice?.value) || 0;

        let eDiff = 0;
        let eMoney = 0;
        if (eNew !== null && !isNaN(eNew)) {
            eDiff = eNew - eOld;
            if (eDiff < 0) {
                if (eCalcFeedback) eCalcFeedback.innerHTML = `<span style="color: #dc3545;">⚠️ Chỉ số mới (${eNew}) nhỏ hơn chỉ số cũ (${eOld})!</span>`;
            } else {
                eMoney = eDiff * ePrice;
                if (eCalcFeedback) eCalcFeedback.innerHTML = `<span style="color: var(--green);">✓ Tiêu thụ: ${formatNumber(eDiff)} kWh (${formatNumber(eMoney)}đ)</span>`;
            }
        } else {
            if (eCalcFeedback) eCalcFeedback.innerHTML = '';
        }

        let wDiff = 0;
        let wMoney = 0;
        if (wNew !== null && !isNaN(wNew)) {
            wDiff = wNew - wOld;
            if (wDiff < 0) {
                if (wCalcFeedback) wCalcFeedback.innerHTML = `<span style="color: #dc3545;">⚠️ Chỉ số mới (${wNew}) nhỏ hơn chỉ số cũ (${wOld})!</span>`;
            } else {
                wMoney = wDiff * wPrice;
                if (wCalcFeedback) wCalcFeedback.innerHTML = `<span style="color: var(--green);">✓ Tiêu thụ: ${formatNumber(wDiff)} m³ (${formatNumber(wMoney)}đ)</span>`;
            }
        } else {
            if (wCalcFeedback) wCalcFeedback.innerHTML = '';
        }

        if (summaryEDiff) summaryEDiff.textContent = `${formatNumber(Math.max(0, eDiff))} kWh`;
        if (summaryERate) summaryERate.textContent = `${formatNumber(ePrice)}đ / kWh`;
        if (summaryEMoney) summaryEMoney.textContent = `${formatNumber(eMoney)}đ`;

        if (summaryWDiff) summaryWDiff.textContent = `${formatNumber(Math.max(0, wDiff))} m³`;
        if (summaryWRate) summaryWRate.textContent = `${formatNumber(wPrice)}đ / m³`;
        if (summaryWMoney) summaryWMoney.textContent = `${formatNumber(wMoney)}đ`;

        if (summaryGrandTotal) summaryGrandTotal.textContent = `${formatNumber(eMoney + wMoney)}đ`;
    }

    function fetchPrevious() {
        if (isEditing || !selectRoom || !inputMonth) return;
        const roomId = selectRoom.value;
        const month = inputMonth.value;
        if (!roomId || !month) return;

        fetch(`{{ route('owner.utilities.previous-reading') }}?room_id=${roomId}&month=${month}`)
            .then(res => res.json())
            .then(data => {
                if (data.electricity_old !== undefined) {
                    if (inputEOld) inputEOld.value = data.electricity_old;
                    if (inputWOld) inputWOld.value = data.water_old;
                    if (eSourceText) eSourceText.textContent = `🗄️ Nguồn: ${data.source}`;
                    if (wSourceText) wSourceText.textContent = `🗄️ Nguồn: ${data.source}`;

                    if (data.electricity_price && inputEPrice && (!inputEPrice.value || inputEPrice.value == 3500)) {
                        inputEPrice.value = data.electricity_price;
                    }
                    if (data.water_price && inputWPrice && (!inputWPrice.value || inputWPrice.value == 20000)) {
                        inputWPrice.value = data.water_price;
                    }
                }

                if (duplicateWarning) {
                    if (data.already_exists) {
                        duplicateWarning.style.display = 'flex';
                        if (duplicateMonthSpan) duplicateMonthSpan.textContent = data.existing_month || month;
                        if (duplicateEditLink) duplicateEditLink.href = `{{ route('owner.utilities.create') }}?id=${data.existing_id}`;
                    } else {
                        duplicateWarning.style.display = 'none';
                    }
                }

                calculate();
            })
            .catch(err => console.error('Error fetching previous readings:', err));
    }

    if (selectRoom) selectRoom.addEventListener('change', fetchPrevious);
    if (inputMonth) inputMonth.addEventListener('change', fetchPrevious);

    [inputEOld, inputENew, inputEPrice, inputWOld, inputWNew, inputWPrice].forEach(input => {
        if (input) {
            input.addEventListener('input', calculate);
        }
    });

    calculate();
});
</script>
@endsection
