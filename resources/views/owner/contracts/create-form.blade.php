<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tạo hợp đồng | Trọ Ơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <style>
        .page-wrap { max-width: 960px; margin: 0 auto; padding: 30px 28px 70px; }
        .page-header { display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 16px; margin-bottom: 26px; }
        .page-title { color: var(--green-dark); font-size: 26px; font-weight: 800; letter-spacing: -.6px; margin: 0; }
        .page-desc { color: var(--muted); font-size: 13px; margin-top: 6px; }
        .btn-brand { border: 0; background: var(--yellow); color: var(--green-dark); font-weight: 800; font-size: 13px; border-radius: 11px; padding: 11px 18px; white-space: nowrap; }
        .btn-brand:hover { background: #ffd968; color: var(--green-dark); }
        .btn-outline-brand { border: 1px solid var(--border); background: #fff; color: var(--green); font-weight: 700; font-size: 13px; border-radius: 11px; padding: 10px 16px; text-decoration: none; }
        .btn-outline-brand:hover { background: var(--green-soft); color: var(--green); }
        .panel { background: #fff; border: 1px solid var(--border); border-radius: 18px; padding: 24px; margin-bottom: 22px; box-shadow: 0 4px 12px rgba(0,0,0,.02); }
        .panel-title { font-size: 16px; font-weight: 800; color: var(--green-dark); margin: 0 0 16px; }
        .form-label { font-size: 12px; font-weight: 700; color: var(--text); }
        .form-control, .form-select { border-radius: 11px; border-color: var(--border); font-size: 13.5px; padding: 10px 14px; }
        .form-control:focus, .form-select:focus { border-color: var(--green); box-shadow: 0 0 0 3px var(--green-soft); }
        .back-link { color: var(--muted); text-decoration: none; font-size: 13px; font-weight: 600; }
        .back-link:hover { color: var(--green); }
        .data-table { width: 100%; border-collapse: collapse; font-size: 12.5px; }
        .data-table thead th { text-align: left; color: var(--muted); font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .4px; padding: 0 12px 12px; border-bottom: 1px solid var(--border); white-space: nowrap; }
        .data-table tbody td { padding: 14px 12px; border-bottom: 1px solid #f0eadc; vertical-align: middle; }
        .data-table tbody tr:last-child td { border-bottom: 0; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>
    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item">
          <a class="app-nav-link" href="{{ route('landlord.home') }}">Tổng quan</a>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop1" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Nhà &amp; Phòng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop1">
            <li>
              <a class="dropdown-item" href="{{ route('owner.properties.index') }}">🏠 Quản lý nhà</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.rooms.index') }}">🚪 Quản lý phòng</a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Khách &amp; Hợp đồng
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop2">
            <li>
              <a class="dropdown-item" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a>
            </li>
            <li>
              <a class="dropdown-item active" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop3" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Tài chính
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop3">
            <li>
              <a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.utilities.index') }}">⚡ Điện nước</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.payments.index') }}">💰 Giao dịch</a>
            </li>
          </ul>
        </li>
        <li class="nav-item dropdown">
          <a class="app-nav-link dropdown-toggle" href="#" id="navbarDrop4" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Vận hành
          </a>
          <ul class="dropdown-menu border-0 shadow-sm" aria-labelledby="navbarDrop4">
            <li>
              <a class="dropdown-item" href="{{ route('owner.rental-posts.index') }}">📢 Tin đăng</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.appointments.index') }}">📅 Lịch xem</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ url('/owner/maintenance') }}">🛠️ Sửa chữa</a>
            </li>
            <li>
              <a class="dropdown-item" href="{{ route('owner.reviews.index') }}">⭐ Đánh giá</a>
            </li>
          </ul>
        </li>
      </ul>
      <div class="navbar-actions ms-lg-3">
        <button class="notif-btn" type="button" aria-label="Thông báo">
          🔔<span class="notif-dot"></span>
        </button>
        <a href="#" class="user-chip">
          <div class="user-avatar">A</div>
          <div class="user-meta">
            <div class="user-name">Chủ trọ</div>
            <div class="user-role">Owner</div>
          </div>
          <span class="caret">▼</span>
        </a>
      </div>
    </div>
  </div>
</nav>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
