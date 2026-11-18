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
                                        <div class="mode-title">Chế độ 1: Khách có sẵn / Đã đặt lịch hẹn</div>
                                        <div class="mode-desc">Chọn người thuê đã có tài khoản trên hệ thống hoặc đã gửi lịch hẹn xem phòng</div>
                                    </div>
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label class="tenant-mode-card w-100 h-100 {{ $defaultMode === 'walkin' ? 'active' : '' }}" id="modeCardWalkin">
                                    <input type="radio" name="tenant_mode" value="walkin" id="modeWalkin" class="form-check-input" @checked($defaultMode === 'walkin')>
                                    <div>
                                        <div class="mode-title">Chế độ 2: Khách vãng lai đến thuê trực tiếp</div>
                                        <div class="mode-desc">Tự động tạo tài khoản Người thuê và gửi Gmail thông báo kèm mật khẩu mặc định qua Resend API</div>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Chế độ 1: Khách có sẵn --}}
                    <div class="col-12 {{ $defaultMode === 'existing' ? '' : 'd-none' }}" id="existingTenantSection">
                        <label class="form-label">Chọn người thuê <span class="text-danger">*</span></label>
                        @if($tenants->isEmpty())
                            <div class="alert alert-warning py-2 small mb-0">
                                Hệ thống chưa có tài khoản người thuê nào. Vui lòng chuyển sang <b>Chế độ 2: Khách vãng lai</b> bên trên để nhập thông tin và tự động tạo tài khoản.
                            </div>
                        @else
                            <select name="tenant_id" id="tenantSelect" class="form-select">
                                <option value="">-- Chọn người thuê từ hệ thống --</option>
                                @foreach($tenants as $tenant)
                                    <option value="{{ $tenant->id }}" @selected((string) old('tenant_id', request('tenant_id')) === (string) $tenant->id)>
                                        {{ $tenant->name }}{{ $tenant->phone ? ' — SĐT: '.$tenant->phone : '' }}{{ $tenant->email ? ' ('.$tenant->email.')' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>

                    {{-- Chế độ 2: Khách vãng lai --}}
                    <div class="col-12 {{ $defaultMode === 'walkin' ? '' : 'd-none' }}" id="walkinTenantSection">
                        <div class="border rounded-3 p-3 bg-light">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <span class="fw-bold" style="color: var(--green-dark); font-size: 14px;">Thông tin khách thuê trực tiếp</span>
                                <span class="badge bg-success text-white py-1 px-2" style="font-size: 11px;">Tự động gửi thông tin qua Resend API</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Họ và tên khách thuê <span class="text-danger">*</span></label>
                                    <input type="text" name="walkin_name" id="walkinName" class="form-control" placeholder="Ví dụ: Đỗ Hoàng Yến" value="{{ old('walkin_name') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" name="walkin_phone" id="walkinPhone" class="form-control" placeholder="Ví dụ: 0912345678" value="{{ old('walkin_phone') }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Email nhận hợp đồng & tài khoản <span class="text-danger">*</span></label>
                                    <input type="email" name="walkin_email" id="walkinEmail" class="form-control" placeholder="Ví dụ: yen.do@gmail.com" value="{{ old('walkin_email') }}">
                                    <div class="form-text small">Gmail của khách để nhận thông báo hợp đồng và mật khẩu đăng nhập.</div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Số CCCD / CMND</label>
                                    <input type="text" name="walkin_identity_card" id="walkinIdCard" class="form-control" placeholder="Ví dụ: 012345678901" value="{{ old('walkin_identity_card') }}">
                                    <div class="form-text small">Thông tin định danh để lưu vào hồ sơ hợp đồng.</div>
                                </div>
                            </div>
                            <div class="alert alert-info py-2 px-3 small mt-3 mb-0">
                                💡 <b>Cơ chế thông minh:</b> Nếu Email này đã có tài khoản trên hệ thống, hợp đồng sẽ được gán vào tài khoản đó. Nếu chưa, hệ thống sẽ tự động khởi tạo tài khoản Người thuê mới theo đúng Email bạn nhập với mật khẩu mặc định <code>default_password_123</code> và gửi thư về Gmail khách.
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Ngày bắt đầu <span class="text-danger">*</span></label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày kết thúc <span class="text-danger">*</span></label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tiền thuê (đ/tháng) <span class="text-danger">*</span></label>
                        <input type="number" id="rentInput" name="rent" min="0" step="0.01" value="{{ old('rent') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tiền cọc (đ) <span class="text-danger">*</span></label>
                        <input type="number" id="depositInput" name="deposit" min="0" step="0.01" value="{{ old('deposit') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Chu kỳ thanh toán</label>
                        <select name="payment_cycle" class="form-select">
                            <option value="monthly">Hàng tháng</option>
                            <option value="quarterly">Hàng quý</option>
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
                            <div class="border rounded-3 p-3 mb-2 bg-light">
                                <div class="row align-items-center g-2">
                                    <div class="col-md-7">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="service_ids[]" value="{{ $service->id }}" id="service-{{ $service->id }}" @checked(in_array((string) $service->id, array_map('strval', old('service_ids', [])), true))>
                                            <label class="form-check-label fw-semibold" for="service-{{ $service->id }}">
                                                {{ $service->name }} <span class="text-muted fw-normal">— {{ number_format((float) $service->price) }} đ/{{ $service->unit }}</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text">Số lượng</span>
                                            <input type="number" name="service_quantities[{{ $service->id }}]" min="0.01" step="0.01" class="form-control" value="{{ old('service_quantities.' . $service->id, 1) }}" aria-label="Số lượng {{ $service->name }}">
                                            <span class="input-group-text">{{ $service->unit }}</span>
                                        </div>
                                    </div>
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
            // Giá phòng tự động điền khi chọn phòng
            var roomSelect = document.getElementById('roomSelect');
            var rentInput = document.getElementById('rentInput');
            var depositInput = document.getElementById('depositInput');
            var priceBox = document.getElementById('roomPriceBox');
            function fmt(n) { return Number(n).toLocaleString('vi-VN'); }
            function updatePrice() {
                var opt = roomSelect.options[roomSelect.selectedIndex];
                if (!opt || !opt.value) { priceBox.classList.add('d-none'); return; }
                var price = opt.getAttribute('data-price') || 0;
                var deposit = opt.getAttribute('data-deposit') || 0;
                if (!rentInput.value) rentInput.value = price;
                if (!depositInput.value) depositInput.value = deposit;
                priceBox.textContent = 'Giá phòng: ' + fmt(price) + ' đ/tháng · Cọc gợi ý: ' + fmt(deposit) + ' đ';
                priceBox.classList.remove('d-none');
            }
            if (roomSelect) {
                roomSelect.addEventListener('change', updatePrice);
                updatePrice();
            }

            // Chuyển đổi 2 chế độ Người thuê
            var modeExisting = document.getElementById('modeExisting');
            var modeWalkin = document.getElementById('modeWalkin');
            var modeCardExisting = document.getElementById('modeCardExisting');
            var modeCardWalkin = document.getElementById('modeCardWalkin');
            var existingSec = document.getElementById('existingTenantSection');
            var walkinSec = document.getElementById('walkinTenantSection');

            var tenantSelect = document.getElementById('tenantSelect');
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

                    if (tenantSelect) { tenantSelect.required = true; tenantSelect.disabled = false; }
                    if (walkinName) { walkinName.required = false; walkinName.disabled = true; }
                    if (walkinPhone) { walkinPhone.required = false; walkinPhone.disabled = true; }
                    if (walkinEmail) { walkinEmail.required = false; walkinEmail.disabled = true; }
                    if (walkinIdCard) { walkinIdCard.disabled = true; }
                }
            }

            if (modeExisting) modeExisting.addEventListener('change', applyMode);
            if (modeWalkin) modeWalkin.addEventListener('change', applyMode);
            applyMode();
        })();
        </script>
    @endif
</main>

@endsection
