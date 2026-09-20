<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Quản lý Hóa đơn</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
  :root{
    --green:#20584f;
    --green-dark:#17463e;
    --green-soft:#eaf3ef;
    --cream:#f8f5eb;
    --yellow:#f5c84b;
    --yellow-light:#fff7d7;
    --text:#213430;
    --muted:#78837e;
    --border:#e6dcc2;
    --white:#ffffff;
    --red:#c65b4a;
    --red-soft:#fbe9e6;
    --blue:#356d9e;
    --blue-soft:#e8f2ff;
  }
  body{ background:var(--cream); font-family:"Be Vietnam Pro",sans-serif; color:var(--text); }

  /* --- Navbar xanh như styles.css --- */
  .app-navbar{
    background:var(--green);
    min-height:74px;
    box-shadow:0 3px 14px rgba(20,55,47,.12);
    position:sticky;
    top:0;
    z-index:1000;
  }
  .app-navbar .container-fluid{
    max-width:1360px;
    padding:0 28px;
  }
  .logo{
    color:#fff;
    text-decoration:none;
    font-size:26px;
    font-weight:800;
    letter-spacing:-1.2px;
    white-space:nowrap;
  }
  .logo span{color:var(--yellow)}
  .logo small{
    display:block;
    font-size:10px;
    font-weight:700;
    letter-spacing:1.5px;
    color:rgba(255,255,255,.6);
    margin-top:-3px;
  }
  .app-nav-link{
    color:rgba(255,255,255,.82)!important;
    font-size:13.5px;
    font-weight:600;
    padding:9px 13px!important;
    border-radius:10px;
    transition:.2s;
    white-space:nowrap;
    text-decoration:none;
  }
  .app-nav-link:hover,.app-nav-link.active{
    color:var(--green)!important;
    background:#fff;
  }
  .navbar-actions{
    display:flex;
    align-items:center;
    gap:6px;
  }
  .notif-btn{
    position:relative;
    width:40px;
    height:40px;
    border-radius:11px;
    border:1px solid rgba(255,255,255,.18);
    background:rgba(255,255,255,.08);
    color:#fff;
    font-size:16px;
    display:flex;
    align-items:center;
    justify-content:center;
    text-decoration:none;
  }
  .notif-dot{
    position:absolute;
    top:7px;
    right:7px;
    width:8px;
    height:8px;
    border-radius:50%;
    background:var(--yellow);
    border:2px solid var(--green);
  }
  .user-chip{
    display:flex;
    align-items:center;
    gap:9px;
    padding:6px 12px 6px 6px;
    border-radius:12px;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.16);
    color:#fff;
    text-decoration:none;
    margin-left:6px;
  }
  .user-avatar{
    width:32px;
    height:32px;
    border-radius:9px;
    background:var(--yellow);
    color:var(--green-dark);
    font-weight:800;
    font-size:13px;
    display:flex;
    align-items:center;
    justify-content:center;
    flex:0 0 auto;
  }
  .user-meta{line-height:1.2}
  .user-name{font-size:12.5px;font-weight:700;color:#fff}
  .user-role{font-size:10px;color:rgba(255,255,255,.65)}
  .caret{font-size:9px;color:rgba(255,255,255,.6);margin-left:2px}

  @media(max-width:991px){
    .app-nav-link{margin:2px 0}
  }

  /* --- Page --- */
  .page-wrap{max-width:1280px;margin:0 auto;padding:1.5rem 1.25rem 3rem;}
  .page-title{font-weight:800;font-size:1.55rem;margin:0;color:var(--green-dark);}
  .page-desc{color:var(--muted);font-size:.92rem;}
  .btn-brand{background:var(--green);color:#fff;border:none;font-weight:700;font-size:.88rem;padding:.62rem 1.1rem;border-radius:10px;transition:background .15s ease;}
  .btn-brand:hover{background:var(--green-dark);color:#fff;}
  .btn-outline-brand{background:#fff;color:var(--green);border:1.5px solid var(--green);font-weight:700;font-size:.85rem;padding:.55rem .9rem;border-radius:10px;}
  .btn-outline-brand:hover{background:var(--green-soft);color:var(--green);}

  /* --- Stat cards --- */
  .stat-card{background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.1rem 1.2rem;height:100%;}
  .stat-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:.7rem;}
  .stat-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:1.05rem;background:var(--blue-soft);color:var(--blue);}
  .stat-icon.green{background:var(--green-soft);color:var(--green);}
  .stat-icon.red{background:var(--red-soft);color:var(--red);}
  .stat-value{font-size:1.55rem;font-weight:800;line-height:1.1;}
  .stat-label{color:var(--muted);font-size:.83rem;margin-top:.15rem;}
  .progress-thin{height:6px;border-radius:99px;background-color:var(--green-soft);margin-top:12px;}
  .progress-thin .progress-bar{background-color:var(--green);border-radius:99px;}

  /* --- Panel --- */
  .panel{background:#fff;border:1px solid var(--border);border-radius:14px;padding:1.2rem 1.25rem;}
  .panel-head{display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;padding-bottom:1rem;border-bottom:1px solid var(--border);}

  /* --- Badges --- */
  .badge-status{display:inline-block;padding:.3rem .65rem;border-radius:99px;font-size:.74rem;font-weight:700;}
  .badge-status.pending{background:var(--yellow-light);color:#9b6a00;}
  .badge-status.paid{background:var(--green-soft);color:var(--green);}
  .badge-status.overdue{background:var(--red-soft);color:var(--red);}

  /* --- Table --- */
  .data-table{width:100%;border-collapse:collapse;}
  .data-table thead th{font-size:.72rem;letter-spacing:.02em;color:var(--muted);font-weight:700;padding:.7rem .6rem;border-bottom:1px solid var(--border);white-space:nowrap;}
  .data-table tbody td{padding:.85rem .6rem;border-bottom:1px solid var(--border);vertical-align:middle;font-size:.88rem;}
  .data-table tbody tr:last-child td{border-bottom:none;}
  .data-table tbody tr:hover{background-color:#f8faf9;}
  .cell-title{font-weight:700;}
  .cell-sub{color:var(--muted);font-size:.78rem;margin-top:1px;}
</style>
</head>
<body>

<!-- NAVBAR ĐÃ ĐỒNG BỘ -->
<!-- NAVBAR ĐÃ SỬA LỖI -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('landlord.home') }}">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler shadow-none border-0" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu" aria-controls="mainMenu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        
        <!-- 1. TỔNG QUAN -->
        <li class="nav-item">
          <a class="app-nav-link " href="{{ route('landlord.home') }}">Tổng quan</a>
        </li>

        <!-- 2. QUẢN LÝ TÀI SẢN -->
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

        <!-- 3. KHÁCH THUÊ -->
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

        <!-- 4. TÀI CHÍNH -->
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
              <a class="dropdown-item" href="{{ route( 'owner.payments.index') }}">💰 Giao dịch</a>
            </li>
          </ul>
        </li>

        <!-- 5. VẬN HÀNH & TƯƠNG TÁC -->
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

<!-- CONTENT -->
<div class="page-wrap">
  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-end flex-wrap gap-3 mb-4">
    <div>
      <h2 class="page-title">Quản lý Hóa đơn</h2>
      <div class="page-desc">Kỳ thanh toán hiện tại: <strong>Tháng {{ \Carbon\Carbon::parse($currentMonth)->format('m/Y') }}</strong></div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn-brand" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
        <i class="bi bi-magic me-1"></i> Tạo hóa đơn tháng
      </button>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon"><i class="bi bi-receipt"></i></div>
          <span class="text-muted small fw-bold">Dự kiến {{ \Carbon\Carbon::parse($currentMonth)->format('m/Y') }}</span>
        </div>
        <div class="stat-value">{{ number_format((float) $stats['expected']) }} ₫</div>
        <div class="stat-label">Tổng doanh thu chờ thu</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
          <span class="fw-bold text-success">Đã thu</span>
        </div>
        <div class="stat-value" style="color: var(--green);">{{ number_format((float) $stats['collected']) }} ₫</div>
        <div class="stat-label">Đã thu trong kỳ</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card" style="border-color: var(--red-soft);">
        <div class="stat-top">
          <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <span class="text-danger fw-bold small">{{ $stats['outstanding_count'] }} hóa đơn cần xử lý</span>
        </div>
        <div class="stat-value" style="color: var(--red);">{{ number_format((float) $stats['outstanding']) }} ₫</div>
        <div class="stat-label">Còn nợ &amp; Quá hạn</div>
      </div>
    </div>
  </div>

  <!-- BẢNG HÓA ĐƠN -->
  <div class="panel">
    <div class="panel-head">
      <h3 class="fw-bold fs-5 mb-0">Danh sách Hóa đơn</h3>
      <form method="GET" action="{{ route('owner.invoices.index') }}" class="d-flex flex-wrap gap-2">
        <input type="month" name="month" class="form-control form-control-sm w-auto shadow-none" value="{{ $filters['month'] ?? '' }}" onchange="this.form.submit()">
        <select name="status" class="form-select form-select-sm w-auto shadow-none" onchange="this.form.submit()">
          <option value="">Tất cả trạng thái</option>
          @foreach(['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'] as $value => $label)
            <option value="{{ $value }}" @selected(($filters['status'] ?? '') === $value)>{{ $label }}</option>
          @endforeach
        </select>
        @if(!empty($filters['month']) || !empty($filters['status']))
          <a href="{{ route('owner.invoices.index') }}" class="btn btn-sm btn-light border">Xóa lọc</a>
        @endif
      </form>
    </div>

    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>MÃ HĐ / NGÀY LẬP</th>
            <th>PHÒNG &amp; KHÁCH</th>
            <th>TỔNG TIỀN</th>
            <th>TRẠNG THÁI</th>
            <th class="text-end">THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          @php
            $statusLabels = ['unpaid' => 'Chưa thanh toán', 'pending' => 'Chờ xác nhận', 'paid' => 'Đã thanh toán', 'overdue' => 'Quá hạn', 'cancelled' => 'Đã hủy'];
          @endphp
          @forelse($invoices as $invoice)
            @php
              $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
              $displayStatus = $isOverdue ? 'overdue' : $invoice->status;
            @endphp
          <tr>
            <td>
              <div class="cell-title" style="color: var(--green);">#{{ $invoice->invoice_code }}</div>
              <div class="cell-sub">Hạn: {{ $invoice->due_date->format('d/m/Y') }}</div>
            </td>
            <td>
              <div class="cell-title">{{ $invoice->contract->room->name ?? '' }}</div>
              <div class="cell-sub">{{ $invoice->contract->tenant->name ?? '' }}</div>
            </td>
            <td><strong>{{ number_format((float) $invoice->total) }} ₫</strong></td>
            <td><span class="badge-status {{ $displayStatus }}">{{ $statusLabels[$displayStatus] ?? $displayStatus }}</span></td>
            <td class="text-end">
              <a href="{{ route('owner.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i> Xem</a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-5">Chưa có hóa đơn nào.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-3">
      <span class="text-muted small">Tổng {{ $invoices->total() }} hóa đơn</span>
      <div>{{ $invoices->links() }}</div>
    </div>
  </div>
</div>

<!-- MODAL: TẠO HÓA ĐƠN THÁNG -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <form method="POST" action="{{ route('owner.invoices.store') }}" class="modal-content">
      @csrf
      <div class="modal-header bg-light">
        <div>
          <h5 class="modal-title fw-bold" style="color: var(--green);"><i class="bi bi-magic me-2"></i>Tạo hóa đơn tháng</h5>
          <div class="text-muted" style="font-size: 13px;">Tự động tính tiền phòng, điện nước và dịch vụ cho các hợp đồng hiệu lực.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label small fw-bold">Kỳ thanh toán</label>
          <input type="month" name="month" class="form-control" value="{{ $currentMonth }}" required>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-bold">Giảm giá chung (đ, tùy chọn)</label>
          <input type="number" name="discount" class="form-control" min="0" step="0.01" value="0">
        </div>
        <div class="alert alert-info mb-0" style="font-size: 13px;">Chọn kỳ đã có chỉ số điện nước (VD: 2026-10). Phòng thiếu chỉ số của kỳ sẽ bị bỏ qua và liệt kê sau khi tạo. Không tạo trùng kỳ/hợp đồng.</div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="submit" class="btn btn-brand fw-bold px-4"><i class="bi bi-send-check me-1"></i> Phát hành Hóa đơn</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>