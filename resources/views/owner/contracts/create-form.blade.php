
@extends('Layout.landlord')

@section('title', 'Quản lý họp đồng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/contracts/create_form.css') }}">
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
    @elseif($tenants->isEmpty())
        <div class="alert alert-warning">Chưa có tài khoản người thuê đang hoạt động.</div>
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
            <form method="POST" action="{{ route('owner.contracts.store') }}">
                @csrf
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                        </ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Phòng trống</label>
                        <select name="room_id" id="roomSelect" class="form-select" required>
                            <option value="">Chọn phòng</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->price }}" data-deposit="{{ $room->deposit }}" @selected((string) old('room_id', request('room_id')) === (string) $room->id)>{{ $room->name }} — {{ $room->property->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Người thuê</label>
                        <select name="tenant_id" class="form-select" required>
                            <option value="">Chọn người thuê</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" @selected((string) old('tenant_id', request('tenant_id')) === (string) $tenant->id)>{{ $tenant->name }}{{ $tenant->phone ? ' — '.$tenant->phone : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <div id="roomPriceBox" class="alert alert-info mb-0 d-none"></div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày bắt đầu</label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Ngày kết thúc</label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tiền thuê</label>
                        <input type="number" id="rentInput" name="rent" min="0" step="0.01" value="{{ old('rent') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tiền cọc</label>
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
                        <label class="form-label">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="active">Kích hoạt ngay</option>
                            <option value="draft">Lưu nháp</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Điều khoản</label>
                        <textarea name="terms" class="form-control" rows="4">{{ old('terms') }}</textarea>
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
            roomSelect.addEventListener('change', updatePrice);
            updatePrice();
        })();
        </script>
    @endif
</main>

@endsection