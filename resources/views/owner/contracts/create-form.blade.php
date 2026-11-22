@extends('Layout.landlord')

@section('title', 'Quản lý hợp đồng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/contracts/create_form.css') }}">
    <style>
        .tenant-mode-card {
            border: 2px solid var(--border, #e2e8f0);
            background: #ffffff;
            border-radius: 12px;
            padding: 14px 18px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .tenant-mode-card:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        .tenant-mode-card.active {
            border-color: #0e5b42 !important;
            background: #f0fdf4 !important;
            box-shadow: 0 0 0 1px #0e5b42;
        }
        .tenant-mode-card .form-check-input {
            width: 1.15rem;
            height: 1.15rem;
            margin-top: 2px;
            cursor: pointer;
        }
        .tenant-mode-card .mode-title {
            font-weight: 700;
            font-size: 14px;
            color: #0c2340;
            margin-bottom: 2px;
        }
        .tenant-mode-card .mode-desc {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
        }
    </style>
@endpush

@section('content')

<main class="page-wrap">
    <a href="{{ route('owner.contracts.index') }}" class="back-link">← Quay lại hợp đồng</a>

    <div class="page-header mt-2">
        <div>
            <h1 class="page-title">Tạo hợp đồng mới</h1>
            <div class="page-desc">Chọn phòng trống và người thuê để lập hợp đồng.</div>
        </div>
    </div>

    @if($rooms->isEmpty())
        <div class="alert alert-warning">Chưa có phòng trống thuộc khu trọ của bạn. Hãy <a href="{{ route('owner.rooms.create') }}">tạo phòng</a> trước.</div>
    @else
        @if($requests->isNotEmpty())
        <div class="panel">
            <h2 class="panel-title">Yêu cầu thuê mới gửi tới ({{ $requests->count() }})</h2>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Người muốn thuê</th>
                            <th>Phòng</th>
                            <th>Ngày hẹn</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $req)
                        <tr>
                            <td>
                                <strong>{{ $req->tenant_name }}</strong><br>
                                <small class="text-muted">{{ $req->tenant_phone }}</small>
                                @if($req->message)<br><small class="text-muted">{{ \Illuminate\Support\Str::limit($req->message, 60) }}</small>@endif
                            </td>
                            <td>
                                {{ $req->room_name }}<br>
                                <small class="text-muted">{{ $req->property_name }} · {{ number_format((float)$req->room_price) }} đ</small>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($req->appointment_date)->format('d/m/Y') }}</td>
                            <td>{{ $req->status === 'pending' ? 'Chờ duyệt' : 'Đã xác nhận' }}</td>
                            <td><a href="{{ route('owner.contracts.create', ['tenant_id' => $req->tenant_id, 'room_id' => $req->room_id]) }}" class="btn btn-sm btn-outline-success">Dùng để tạo HĐ</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <div class="panel">
            <h2 class="panel-title">Thông tin hợp đồng</h2>
            <form method="POST" action="{{ route('owner.contracts.store') }}" id="contractForm">
                @csrf
                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">Phòng trống <span class="text-danger">*</span></label>
                        <select name="room_id" id="roomSelect" class="form-select" required>
                            <option value="">Chọn phòng trống</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->price }}" data-deposit="{{ $room->deposit }}" @selected((string) old('room_id', request('room_id')) === (string) $room->id)>{{ $room->name }} — {{ $room->property->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div id="roomPriceBox" class="alert alert-info mb-0 d-none"></div>
                    </div>

                    {{-- Nút chuyển đổi 2 chế độ Người thuê --}}
                    @php
                        $defaultMode = old('tenant_mode', request('tenant_id') ? 'existing' : ($tenants->isEmpty() ? 'walkin' : 'existing'));
                    @endphp
                    <div class="col-12">
                        <label class="form-label d-block mb-2">Hình thức tiếp nhận người thuê <span class="text-danger">*</span></label>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="tenant-mode-card w-100 h-100 {{ $defaultMode === 'existing' ? 'active' : '' }}" id="modeCardExisting">
                                    <input type="radio" name="tenant_mode" value="existing" id="modeExisting" class="form-check-input" @checked($defaultMode === 'existing')>
                                    <div>
                                        <div class="mode-title">Chế độ 1: Khách đã đặt lịch hẹn xem phòng</div>
                                        <div class="mode-desc">Chỉ chọn khách thuê đã gửi lịch hẹn xem phòng với các khu trọ của bạn</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="tenant-mode-card w-100 h-100 {{ $defaultMode === 'walkin' ? 'active' : '' }}" id="modeCardWalkin">
                                    <input type="radio" name="tenant_mode" value="walkin" id="modeWalkin" class="form-check-input" @checked($defaultMode === 'walkin')>
                                    <div>
                                        <div class="mode-title">Chế độ 2: Khách vãng lai đến thuê trực tiếp</div>
                                        <div class="mode-desc">Nhập thông tin người thuê mới chưa có tài khoản hoặc chưa từng đặt lịch hẹn</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Chế độ 1: Khách đã đặt lịch hẹn --}}
                    <div class="col-12 {{ $defaultMode === 'existing' ? '' : 'd-none' }}" id="existingTenantSection">
                        <label class="form-label d-flex align-items-center justify-content-between mb-2">
                            <span>Người thuê đã đặt lịch hẹn <span class="text-danger">*</span></span>
                            <span class="text-muted small fw-normal" id="tenantCountBadge"></span>
                        </label>

                        {{-- Khung thông báo/thông tin người thuê theo phòng đã chọn --}}
                        <div id="tenantAppointmentStatusBox"></div>

                        {{-- Dropdown người thuê (sẽ hiện khi có >= 2 người đặt hẹn) --}}
                        <div id="tenantSelectWrapper" class="d-none mt-2">
                            <select name="tenant_id" id="tenantSelect" class="form-select">
                                <option value="">-- Chọn khách thuê đã đặt lịch hẹn xem phòng --</option>
                            </select>
                            <div class="form-text small">Phòng này có nhiều người cùng đặt lịch hẹn. Vui lòng chọn người bạn đồng ý ký hợp đồng.</div>
                        </div>
                    </div>

                    {{-- Chế độ 2: Khách vãng lai --}}
                    <div class="col-12 {{ $defaultMode === 'walkin' ? '' : 'd-none' }}" id="walkinTenantSection">
                        <div class="border rounded-3 p-3 bg-light">
                            <div class="mb-3">
                                <span class="fw-bold text-dark" style="font-size: 14.5px;">Thông tin khách thuê trực tiếp</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên khách thuê <span class="text-danger">*</span></label>
                                    <input type="text" name="walkin_name" id="walkinName" class="form-control" placeholder="Ví dụ: Đỗ Hoàng Yến" value="{{ old('walkin_name') }}" minlength="2" maxlength="100">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="tel" name="walkin_phone" id="walkinPhone" class="form-control" placeholder="Ví dụ: 0912345678" value="{{ old('walkin_phone') }}" maxlength="10" pattern="0[35789][0-9]{8}">
                                    <div class="form-text small">Gồm 10 số, bắt đầu bằng 03, 05, 07, 08 hoặc 09.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email nhận hợp đồng & tài khoản <span class="text-danger">*</span></label>
                                    <input type="email" name="walkin_email" id="walkinEmail" class="form-control" placeholder="Ví dụ: yen.do@gmail.com" value="{{ old('walkin_email') }}" maxlength="150">
                                    <div class="form-text small">Email của khách thuê để nhận thông báo hợp đồng.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số CCCD / CMND</label>
                                    <input type="text" name="walkin_identity_card" id="walkinIdCard" class="form-control" placeholder="Ví dụ: 012345678901" value="{{ old('walkin_identity_card') }}" maxlength="12" pattern="[0-9]{9}|[0-9]{12}">
                                    <div class="form-text small">Gồm 9 hoặc 12 số định danh để lưu vào hồ sơ hợp đồng.</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" id="startDateInput" value="{{ old('start_date') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" id="endDateInput" value="{{ old('end_date') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tiền thuê (đ/tháng) <span class="text-danger">*</span></label>
                        <input type="number" id="rentInput" name="rent" min="0" step="1" value="{{ old('rent') !== null ? (int) old('rent') : '' }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tiền cọc (đ) <span class="text-danger">*</span></label>
                        <input type="number" id="depositInput" name="deposit" min="0" step="1" value="{{ old('deposit') !== null ? (int) old('deposit') : '' }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chu kỳ thanh toán</label>
                        <select name="payment_cycle" class="form-select">
                            <option value="monthly" selected>Hàng tháng</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Trạng thái hợp đồng</label>
                        <select name="status" class="form-select">
                            <option value="active">Kích hoạt ngay</option>
                            <option value="draft">Lưu nháp</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Dịch vụ áp dụng cho hợp đồng</label>
                        <div class="form-text mb-2">Chỉ hiển thị dịch vụ đang hoạt động của bạn. Điện, nước sẽ được tính theo chỉ số hàng tháng.</div>
                        @forelse($services as $service)
                            @php
                                $unitLower = mb_strtolower(trim($service->unit));
                                $nameLower = mb_strtolower(trim($service->name));
                                // Chỉ dịch vụ gửi xe / phương tiện mới cần tăng số lượng chiếc
                                $needsQuantity = in_array($unitLower, ['chiếc', 'chiec', 'xe', 'phương tiện']) 
                                    || str_contains($nameLower, 'xe') 
                                    || str_contains($nameLower, 'gửi xe') 
                                    || str_contains($nameLower, 'đỗ xe');
                            @endphp
                            <div class="border rounded-3 p-3 mb-2 bg-light">
                                <div class="row align-items-center g-2">
                                    <div class="{{ $needsQuantity ? 'col-md-7' : 'col-12' }}">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="service_ids[]" value="{{ $service->id }}" id="service-{{ $service->id }}" @checked(in_array((string) $service->id, array_map('strval', old('service_ids', [])), true))>
                                            <label class="form-check-label fw-semibold" for="service-{{ $service->id }}">
                                                {{ $service->name }} <span class="text-muted fw-normal">— {{ number_format((float) $service->price) }} đ/{{ $service->unit }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    @if($needsQuantity)
                                        <div class="col-md-5">
                                            <div class="input-group input-group-sm">
                                                <span class="input-group-text">Số lượng</span>
                                                <input type="number" name="service_quantities[{{ $service->id }}]" min="1" step="1" class="form-control text-center" value="{{ max(1, (int) old('service_quantities.' . $service->id, 1)) }}" aria-label="Số lượng {{ $service->name }}">
                                                <span class="input-group-text">{{ $service->unit }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <input type="hidden" name="service_quantities[{{ $service->id }}]" value="1">
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="alert alert-light border mb-0">Bạn chưa có dịch vụ đang hoạt động. Hãy thêm dịch vụ trong mục Quản lý dịch vụ nếu cần.</div>
                        @endforelse
                    </div>
                    <div class="col-12">
                        <label class="form-label">Điều khoản hợp đồng</label>
                        <textarea name="terms" class="form-control" rows="4" placeholder="Nhập các điều khoản bổ sung nếu có...">{{ old('terms') }}</textarea>
                    </div>
                    <div class="col-12 d-flex gap-2">
                        <a href="{{ route('owner.contracts.index') }}" class="btn-outline-brand">Hủy</a>
                        <button type="submit" class="btn-brand">Tạo hợp đồng</button>
                    </div>
                </div>
            </form>
        </div>

        <script>
        (function () {
            var roomAppointments = @json($appointmentsByRoom ?? []);
            var preselectedTenantId = "{{ old('tenant_id', request('tenant_id', '')) }}";

            // Giá phòng tự động điền khi chọn phòng
            var roomSelect = document.getElementById('roomSelect');
            var rentInput = document.getElementById('rentInput');
            var depositInput = document.getElementById('depositInput');
            var priceBox = document.getElementById('roomPriceBox');

            var tenantSelect = document.getElementById('tenantSelect');
            var tenantSelectWrapper = document.getElementById('tenantSelectWrapper');
            var statusBox = document.getElementById('tenantAppointmentStatusBox');
            var tenantCountBadge = document.getElementById('tenantCountBadge');

            function fmt(n) { return Number(n).toLocaleString('vi-VN'); }

            function escapeHtml(str) {
                if (!str) return '';
                return String(str).replace(/[&<>"']/g, function (m) {
                    return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[m];
                });
            }

            function updatePrice() {
                if (!roomSelect) return;
                var opt = roomSelect.options[roomSelect.selectedIndex];
                if (!opt || !opt.value) { priceBox.classList.add('d-none'); return; }
                var price = Math.round(parseFloat(opt.getAttribute('data-price') || 0));
                var deposit = Math.round(parseFloat(opt.getAttribute('data-deposit') || 0));
                if (!rentInput.value) rentInput.value = price;
                if (!depositInput.value) depositInput.value = deposit;
                priceBox.textContent = 'Giá phòng: ' + fmt(price) + ' đ/tháng · Cọc gợi ý: ' + fmt(deposit) + ' đ';
                priceBox.classList.remove('d-none');
            }

            // Tự động tải & cập nhật danh sách người thuê theo phòng đã chọn
            function updateTenantList() {
                if (!roomSelect || !statusBox || !tenantSelect) return;
                var roomId = String(roomSelect.value || '');
                var appts = (roomId && roomAppointments[roomId]) ? roomAppointments[roomId] : [];

                statusBox.innerHTML = '';
                tenantSelect.innerHTML = '';
                if (tenantCountBadge) tenantCountBadge.textContent = '';

                if (!roomId) {
                    statusBox.innerHTML = '<div class="alert alert-light border py-2 px-3 small mb-0 text-muted">Vui lòng chọn phòng trống ở trên để hệ thống tự động tải người thuê đã đặt lịch hẹn.</div>';
                    if (tenantSelectWrapper) tenantSelectWrapper.classList.add('d-none');
                    tenantSelect.required = false;
                    return;
                }

                if (appts.length === 0) {
                    // 0 người đặt hẹn cho phòng này
                    if (tenantCountBadge) tenantCountBadge.innerHTML = '<span class="badge bg-secondary-subtle text-secondary">0 người đặt hẹn</span>';
                    statusBox.innerHTML = '<div class="alert alert-warning py-2 px-3 small mb-0" style="border-radius: 10px;">Chưa có khách nào đặt lịch hẹn xem phòng này. Nếu khách đến thuê trực tiếp, vui lòng chuyển sang <b>Chế độ 2: Khách vãng lai đến thuê trực tiếp</b> bên trên.</div>';
                    if (tenantSelectWrapper) tenantSelectWrapper.classList.add('d-none');
                    tenantSelect.required = false;
                } else if (appts.length === 1) {
                    // Đúng 1 người đặt hẹn -> TỰ ĐỘNG CHỌN VÀ HIỂN THỊ THÔNG TIN LUÔN!
                    var t = appts[0];
                    if (tenantCountBadge) tenantCountBadge.innerHTML = '<span class="badge bg-success-subtle text-success">1 người đặt hẹn</span>';

                    var opt = document.createElement('option');
                    opt.value = t.tenant_id;
                    opt.textContent = t.tenant_name + ' — SĐT: ' + (t.tenant_phone || '—');
                    opt.selected = true;
                    tenantSelect.appendChild(opt);
                    tenantSelect.value = t.tenant_id;
                    tenantSelect.required = true;

                    if (tenantSelectWrapper) tenantSelectWrapper.classList.add('d-none');
                    statusBox.innerHTML = 
                        '<div class="alert alert-success d-flex align-items-center justify-content-between py-2 px-3 mb-0" style="border-radius: 10px;">' +
                            '<div>' +
                                '<div class="small text-success fw-bold mb-1">Khách đã đặt lịch hẹn cho phòng này:</div>' +
                                '<div class="fw-bold fs-6 text-dark">' + escapeHtml(t.tenant_name) + (t.tenant_phone ? ' — SĐT: ' + escapeHtml(t.tenant_phone) : '') + '</div>' +
                                (t.appointment_date ? '<div class="text-muted small mt-1">Ngày hẹn xem phòng: ' + escapeHtml(t.appointment_date) + '</div>' : '') +
                            '</div>' +
                            '<span class="badge bg-success px-2 py-1" style="font-size: 11px;">Đã tự động chọn</span>' +
                        '</div>';
                } else {
                    // 2 người đặt hẹn trở lên -> HIỆN DROPDOWN ĐỂ CHỦ TRỌ LỰA CHỌN
                    if (tenantCountBadge) tenantCountBadge.innerHTML = '<span class="badge bg-primary-subtle text-primary">' + appts.length + ' người đặt hẹn</span>';
                    statusBox.innerHTML = 
                        '<div class="alert alert-info py-2 px-3 small mb-2" style="border-radius: 10px;">' +
                            'Phòng này có <strong>' + appts.length + ' người</strong> đã đặt lịch hẹn xem phòng. Vui lòng chọn người thuê bạn đồng ý ký hợp đồng bên dưới:' +
                        '</div>';

                    var defaultOpt = document.createElement('option');
                    defaultOpt.value = '';
                    defaultOpt.textContent = '-- Chọn 1 trong số ' + appts.length + ' người đã đặt lịch hẹn --';
                    tenantSelect.appendChild(defaultOpt);

                    var matched = false;
                    appts.forEach(function (t) {
                        var opt = document.createElement('option');
                        opt.value = t.tenant_id;
                        opt.textContent = t.tenant_name + (t.tenant_phone ? ' — SĐT: ' + t.tenant_phone : '') + (t.appointment_date ? ' (Ngày hẹn: ' + t.appointment_date + ')' : '');
                        if (preselectedTenantId && String(preselectedTenantId) === String(t.tenant_id)) {
                            opt.selected = true;
                            matched = true;
                        }
                        tenantSelect.appendChild(opt);
                    });

                    if (tenantSelectWrapper) tenantSelectWrapper.classList.remove('d-none');
                    tenantSelect.required = true;
                }
            }

            if (roomSelect) {
                roomSelect.addEventListener('change', function () {
                    updatePrice();
                    updateTenantList();
                });
                updatePrice();
                updateTenantList();
            }

            // Tự động tính Ngày kết thúc là 1 năm sau Ngày bắt đầu
            var startDateInput = document.getElementById('startDateInput');
            var endDateInput = document.getElementById('endDateInput');
            if (startDateInput && endDateInput) {
                startDateInput.addEventListener('change', function () {
                    if (!this.value) return;
                    endDateInput.min = this.value;
                    var parts = this.value.split('-');
                    if (parts.length === 3) {
                        var y = parseInt(parts[0], 10) + 1;
                        var m = parts[1];
                        var d = parts[2];
                        if (m === '02' && d === '29') {
                            d = '28';
                        }
                        endDateInput.value = y + '-' + m + '-' + d;
                    }
                });
            }

            // Chuyển đổi 2 chế độ Người thuê
            var modeExisting = document.getElementById('modeExisting');
            var modeWalkin = document.getElementById('modeWalkin');
            var modeCardExisting = document.getElementById('modeCardExisting');
            var modeCardWalkin = document.getElementById('modeCardWalkin');
            var existingSec = document.getElementById('existingTenantSection');
            var walkinSec = document.getElementById('walkinTenantSection');

            var walkinName = document.getElementById('walkinName');
            var walkinPhone = document.getElementById('walkinPhone');
            var walkinEmail = document.getElementById('walkinEmail');
            var walkinIdCard = document.getElementById('walkinIdCard');

            function applyMode() {
                var isWalkin = modeWalkin && modeWalkin.checked;
                if (isWalkin) {
                    if (existingSec) existingSec.classList.add('d-none');
                    if (walkinSec) walkinSec.classList.remove('d-none');
                    if (modeCardWalkin) modeCardWalkin.classList.add('active');
                    if (modeCardExisting) modeCardExisting.classList.remove('active');

                    if (tenantSelect) { tenantSelect.required = false; tenantSelect.disabled = true; }
                    if (walkinName) { walkinName.required = true; walkinName.disabled = false; }
                    if (walkinPhone) { walkinPhone.required = true; walkinPhone.disabled = false; }
                    if (walkinEmail) { walkinEmail.required = true; walkinEmail.disabled = false; }
                    if (walkinIdCard) { walkinIdCard.disabled = false; }
                } else {
                    if (existingSec) existingSec.classList.remove('d-none');
                    if (walkinSec) walkinSec.classList.add('d-none');
                    if (modeCardExisting) modeCardExisting.classList.add('active');
                    if (modeCardWalkin) modeCardWalkin.classList.remove('active');

                    if (tenantSelect) { tenantSelect.disabled = false; }
                    updateTenantList();
                    if (walkinName) { walkinName.required = false; walkinName.disabled = true; }
                    if (walkinPhone) { walkinPhone.required = false; walkinPhone.disabled = true; }
                    if (walkinEmail) { walkinEmail.required = false; walkinEmail.disabled = true; }
                    if (walkinIdCard) { walkinIdCard.disabled = true; }
                }
            }

            if (modeExisting) modeExisting.addEventListener('change', applyMode);
            if (modeWalkin) modeWalkin.addEventListener('change', applyMode);
            applyMode();

            // Ràng buộc kiểm tra dữ liệu trước khi gửi form
            var contractForm = document.getElementById('contractForm');
            if (contractForm) {
                contractForm.addEventListener('submit', function (e) {
                    if (!roomSelect || !roomSelect.value) {
                        alert('Vui lòng chọn phòng trống cần lập hợp đồng.');
                        if (roomSelect) roomSelect.focus();
                        e.preventDefault();
                        return false;
                    }

                    var isWalkin = modeWalkin && modeWalkin.checked;
                    if (isWalkin) {
                        var nameVal = walkinName ? walkinName.value.trim() : '';
                        if (!nameVal || nameVal.length < 2) {
                            alert('Vui lòng nhập họ và tên khách thuê (ít nhất 2 ký tự).');
                            if (walkinName) walkinName.focus();
                            e.preventDefault();
                            return false;
                        }

                        var phoneVal = walkinPhone ? walkinPhone.value.trim() : '';
                        var phoneRegex = /^0[35789]\d{8}$/;
                        if (!phoneRegex.test(phoneVal)) {
                            alert('Số điện thoại khách thuê không hợp lệ. Vui lòng nhập đúng 10 số (bắt đầu bằng 03, 05, 07, 08 hoặc 09).');
                            if (walkinPhone) walkinPhone.focus();
                            e.preventDefault();
                            return false;
                        }

                        var emailVal = walkinEmail ? walkinEmail.value.trim() : '';
                        var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailRegex.test(emailVal)) {
                            alert('Vui lòng nhập địa chỉ email hợp lệ của khách thuê.');
                            if (walkinEmail) walkinEmail.focus();
                            e.preventDefault();
                            return false;
                        }

                        var idCardVal = walkinIdCard ? walkinIdCard.value.trim() : '';
                        if (idCardVal && !/^\d{9}$|^\d{12}$/.test(idCardVal)) {
                            alert('Số CCCD/CMND không hợp lệ (phải gồm đúng 9 hoặc 12 số).');
                            if (walkinIdCard) walkinIdCard.focus();
                            e.preventDefault();
                            return false;
                        }
                    } else {
                        if (!tenantSelect || !tenantSelect.value) {
                            alert('Vui lòng chọn khách thuê đã đặt lịch hẹn xem phòng trong danh sách.');
                            if (tenantSelect) tenantSelect.focus();
                            e.preventDefault();
                            return false;
                        }
                    }

                    if (startDateInput && endDateInput && startDateInput.value && endDateInput.value) {
                        if (endDateInput.value <= startDateInput.value) {
                            alert('Ngày kết thúc hợp đồng phải sau ngày bắt đầu.');
                            endDateInput.focus();
                            e.preventDefault();
                            return false;
                        }
                    }

                    var rentVal = rentInput ? parseFloat(rentInput.value) : -1;
                    if (isNaN(rentVal) || rentVal < 0) {
                        alert('Tiền thuê phòng phải lớn hơn hoặc bằng 0 đ.');
                        if (rentInput) rentInput.focus();
                        e.preventDefault();
                        return false;
                    }

                    var depositVal = depositInput ? parseFloat(depositInput.value) : -1;
                    if (isNaN(depositVal) || depositVal < 0) {
                        alert('Tiền cọc phòng phải lớn hơn hoặc bằng 0 đ.');
                        if (depositInput) depositInput.focus();
                        e.preventDefault();
                        return false;
                    }
                });
            }
        })();
        </script>
    @endif
</main>

@endsection
