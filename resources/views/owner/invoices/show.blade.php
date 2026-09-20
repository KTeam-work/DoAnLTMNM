<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết hóa đơn #{{ $invoice->invoice_code }} | Trọ Ơi</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .page-wrap{max-width:960px;margin:0 auto;padding:1.5rem 1.25rem 3rem;}
  .back-link{color:var(--muted);text-decoration:none;font-size:13px;font-weight:600;}
  .back-link:hover{color:var(--green);}
  .panel{background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.4rem;margin-bottom:1.2rem;}
  .panel h5{color:var(--green-dark);font-weight:800;font-size:1rem;margin-bottom:1rem;}
  .badge-status{display:inline-block;padding:.3rem .65rem;border-radius:99px;font-size:.74rem;font-weight:700;}
  .badge-status.unpaid{background:var(--yellow-light);color:#9b6a00;}
  .badge-status.pending{background:var(--yellow-light);color:#9b6a00;}
  .badge-status.paid{background:var(--green-soft);color:var(--green);}
  .badge-status.overdue{background:var(--red-soft);color:var(--red);}
  .badge-status.cancelled{background:#f0eadc;color:var(--muted);}
  .data-table{width:100%;border-collapse:collapse;font-size:.88rem;}
  .data-table thead th{font-size:.72rem;color:var(--muted);font-weight:700;padding:.6rem;border-bottom:1px solid var(--border);}
  .data-table tbody td{padding:.7rem .6rem;border-bottom:1px dashed var(--border);}
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
              <a class="dropdown-item" href="{{ route('owner.contracts.index') }}">📝 Hợp đồng</a>
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
              <a class="dropdown-item active" href="{{ route('owner.invoices.index') }}">🧾 Hóa đơn</a>
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
              <a class="dropdown-item" href="{{ route('owner.maintenance.index') }}">🛠️ Sửa chữa</a>
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
  <a href="{{ route('owner.invoices.index') }}" class="back-link">← Quay lại danh sách hóa đơn</a>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 my-3">
    <h1 class="h4 fw-bold mb-0" style="color: var(--green-dark);">#{{ $invoice->invoice_code }}</h1>
    @php
      $statusLabels = ['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'];
      $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
      $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
    @endphp
    <span class="badge-status {{ $displayStatus }}">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</span>
  </div>

  <div class="panel">
    <h5>Thông tin chung</h5>
    <div class="row g-3" style="font-size: .9rem;">
      <div class="col-md-6">Phòng: <strong>{{ $invoice->contract->room->name ?? '' }}</strong> ({{ $invoice->contract->room->property->name ?? '' }})</div>
      <div class="col-md-6">Người thuê: <strong>{{ $invoice->contract->tenant->name ?? '' }}</strong> ({{ $invoice->contract->tenant->phone ?? '' }})</div>
      <div class="col-md-4">Kỳ: <strong>{{ $invoice->billing_month->format('m/Y') }}</strong></div>
      <div class="col-md-4">Ngày lập: {{ $invoice->issue_date->format('d/m/Y') }}</div>
      <div class="col-md-4">Hạn thanh toán: <strong class="{{ $isOverdue ? 'text-danger' : '' }}">{{ $invoice->due_date->format('d/m/Y') }}</strong></div>
    </div>
  </div>

  <div class="panel">
    <h5>Chi tiết các khoản</h5>
    <div class="table-responsive">
      <table class="data-table">
        <thead><tr><th>#</th><th>Hạng mục</th><th class="text-end">Số lượng</th><th class="text-end">Đơn giá</th><th class="text-end">Thành tiền</th></tr></thead>
        <tbody>
          @foreach($invoice->items as $index => $item)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->description }}</td>
            <td class="text-end">{{ rtrim(rtrim(number_format((float) $item->quantity, 2), '0'), '.') }}</td>
            <td class="text-end">{{ number_format((float) $item->unit_price) }}</td>
            <td class="text-end fw-bold">{{ number_format((float) $item->amount) }} đ</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="text-end mt-3" style="font-size: .9rem;">
      <div>Tạm tính: {{ number_format((float) $invoice->subtotal) }} đ</div>
      @if((float) $invoice->discount > 0)<div>Giảm giá: {{ number_format((float) $invoice->discount) }} đ</div>@endif
      <div class="fw-bold fs-5" style="color: var(--green-dark);">Tổng: {{ number_format((float) $invoice->total) }} đ</div>
    </div>
  </div>

  <div class="panel">
    <h5>Lịch sử thanh toán</h5>
    @forelse($invoice->payments as $payment)
      <div class="d-flex justify-content-between align-items-center border-bottom py-2 flex-wrap gap-2" style="font-size: .9rem;">
        <div><strong>{{ $payment->payment_code }}</strong> · {{ number_format((float) $payment->amount) }} đ · {{ ['cash' => 'Tiền mặt', 'bank_transfer' => 'Chuyển khoản', 'qr' => 'QR', 'online' => 'Online'][$payment->method] ?? $payment->method }}{{ $payment->transaction_code ? ' · ' . $payment->transaction_code : '' }}</div>
        <div class="d-flex align-items-center gap-2">
          <span class="badge-status {{ $payment->status === 'success' ? 'paid' : ($payment->status === 'pending' ? 'pending' : 'cancelled') }}">{{ ['pending' => 'Chờ xác nhận', 'success' => 'Thành công', 'failed' => 'Thất bại', 'cancelled' => 'Đã hủy'][$payment->status] ?? $payment->status }}</span>
          @if($payment->status === 'pending')
            <form action="{{ route('owner.payments.confirm', $payment->id) }}" method="POST" class="d-inline m-0">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Xác nhận đã nhận {{ number_format((float) $payment->amount) }} đ?')">✓ Xác nhận</button>
            </form>
            <form action="{{ route('owner.payments.reject', $payment->id) }}" method="POST" class="d-inline m-0">
              @csrf
              @method('PUT')
              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Từ chối giao dịch này?')">Từ chối</button>
            </form>
          @endif
        </div>
      </div>
    @empty
      <div class="text-muted">Chưa có giao dịch nào.</div>
    @endforelse
  </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
