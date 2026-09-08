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

<!-- NAVBAR XANH -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="#">Trọ <span>Ơi</span></a>

    <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="#">Tổng quan</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Nhà &amp; Phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Tin đăng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Người thuê</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ route('owner.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('owner.payments.index') }}">Giao dịch</a></li>
      </ul>

      <div class="navbar-actions">
        <a href="#" class="notif-btn"><i class="bi bi-bell-fill"></i><span class="notif-dot"></span></a>
        <a href="#" class="user-chip">
          <span class="user-avatar">MT</span>
          <span class="user-meta">
            <span class="user-name d-block">Minh Tuấn</span>
            <span class="user-role">Chủ trọ</span>
          </span>
          <span class="caret">▾</span>
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
      <div class="page-desc">Kỳ thanh toán hiện tại: <strong>Tháng 09/2026</strong></div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn-outline-brand"><i class="bi bi-download me-1"></i>Xuất Excel</button>
      <button class="btn-brand" data-bs-toggle="modal" data-bs-target="#bulkCreateModal">
        <i class="bi bi-magic me-1"></i> Chốt điện nước &amp; Tạo HĐ
      </button>
    </div>
  </div>

  <!-- STAT CARDS -->
  <div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon"><i class="bi bi-receipt"></i></div>
          <span class="text-muted small fw-bold">Dự kiến T09</span>
        </div>
        <div class="stat-value">28,5tr ₫</div>
        <div class="stat-label">Tổng doanh thu chờ thu</div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green"><i class="bi bi-check-circle-fill"></i></div>
          <span class="fw-bold text-success">Đạt 68%</span>
        </div>
        <div class="stat-value" style="color: var(--green);">19,4tr ₫</div>
        <div class="stat-label">Đã thu</div>
        <div class="progress progress-thin"><div class="progress-bar" style="width: 68%;"></div></div>
      </div>
    </div>

    <div class="col-12 col-md-4">
      <div class="stat-card" style="border-color: var(--red-soft);">
        <div class="stat-top">
          <div class="stat-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
          <span class="text-danger fw-bold small">3 hóa đơn cần xử lý</span>
        </div>
        <div class="stat-value" style="color: var(--red);">9,1tr ₫</div>
        <div class="stat-label">Còn nợ &amp; Quá hạn</div>
      </div>
    </div>
  </div>

  <!-- BẢNG HÓA ĐƠN -->
  <div class="panel">
    <div class="panel-head">
      <h3 class="fw-bold fs-5 mb-0">Danh sách Hóa đơn</h3>
      <div class="d-flex flex-wrap gap-2">
        <div class="input-group input-group-sm" style="width: 220px;">
          <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
          <input type="text" class="form-control bg-light border-0 shadow-none ps-0" placeholder="Tìm mã phòng, khách...">
        </div>
        <select class="form-select form-select-sm w-auto shadow-none bg-light border-0">
          <option>Tất cả khu trọ</option>
          <option>Khu trọ Hoa Mai</option>
          <option>Chung cư mini Trần Khát Chân</option>
        </select>
        <select class="form-select form-select-sm w-auto shadow-none bg-light border-0">
          <option>Tất cả trạng thái</option>
          <option>Chưa thanh toán</option>
          <option>Đã thanh toán</option>
          <option>Quá hạn</option>
        </select>
      </div>
    </div>

    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th style="width: 40px;"><input class="form-check-input" type="checkbox"></th>
            <th>MÃ HĐ / NGÀY LẬP</th>
            <th>PHÒNG &amp; KHÁCH</th>
            <th>TỔNG TIỀN</th>
            <th>TRẠNG THÁI</th>
            <th class="text-end">THAO TÁC</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><input class="form-check-input" type="checkbox"></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-09-2026-12A</div>
              <div class="cell-sub">Hạn: 05/09/2026</div>
            </td>
            <td>
              <div class="cell-title">Phòng 12A</div>
              <div class="cell-sub">Thanh Huyền (0987xxx)</div>
            </td>
            <td><strong>3,7tr ₫</strong></td>
            <td><span class="badge-status pending">Chưa thanh toán</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger me-1"><i class="bi bi-bell-fill"></i></button>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i></button>
            </td>
          </tr>
          <tr>
            <td><input class="form-check-input" type="checkbox" disabled></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-09-2026-14B</div>
              <div class="cell-sub">Đã thu: 02/09/2026</div>
            </td>
            <td>
              <div class="cell-title">Phòng 14B</div>
              <div class="cell-sub">Hoàng Nam (0912xxx)</div>
            </td>
            <td><strong>4,2tr ₫</strong></td>
            <td><span class="badge-status paid"><i class="bi bi-check2-circle me-1"></i>Đã thanh toán</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer-fill"></i></button>
            </td>
          </tr>
          <tr>
            <td><input class="form-check-input" type="checkbox"></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-08-2026-03C</div>
              <div class="cell-sub"><span class="text-danger">Trễ 29 ngày</span></div>
            </td>
            <td>
              <div class="cell-title">Phòng 03C</div>
              <div class="cell-sub">Văn Hùng (0934xxx)</div>
            </td>
            <td><strong>2,9tr ₫</strong></td>
            <td><span class="badge-status overdue">Quá hạn (29 ngày)</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger me-1"><i class="bi bi-cone-striped"></i></button>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i></button>
            </td>
          </tr>
          <tr>
            <td><input class="form-check-input" type="checkbox"></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-09-2026-05A</div>
              <div class="cell-sub">Hạn: 10/09/2026</div>
            </td>
            <td>
              <div class="cell-title">Phòng 05A</div>
              <div class="cell-sub">Mai Lan (0977xxx)</div>
            </td>
            <td><strong>3,4tr ₫</strong></td>
            <td><span class="badge-status pending">Chưa thanh toán</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger me-1"><i class="bi bi-bell-fill"></i></button>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i></button>
            </td>
          </tr>
          <tr>
            <td><input class="form-check-input" type="checkbox" disabled></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-09-2026-08D</div>
              <div class="cell-sub">Đã thu: 03/09/2026</div>
            </td>
            <td>
              <div class="cell-title">Phòng 08D</div>
              <div class="cell-sub">Đức Anh (0966xxx)</div>
            </td>
            <td><strong>3,8tr ₫</strong></td>
            <td><span class="badge-status paid"><i class="bi bi-check2-circle me-1"></i>Đã thanh toán</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-printer-fill"></i></button>
            </td>
          </tr>
          <tr>
            <td><input class="form-check-input" type="checkbox"></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-09-2026-02B</div>
              <div class="cell-sub">Hạn: 10/09/2026</div>
            </td>
            <td>
              <div class="cell-title">Phòng 02B</div>
              <div class="cell-sub">Thu Trang (0988xxx)</div>
            </td>
            <td><strong>3,5tr ₫</strong></td>
            <td><span class="badge-status pending">Chưa thanh toán</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger me-1"><i class="bi bi-bell-fill"></i></button>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i></button>
            </td>
          </tr>
          <tr>
            <td><input class="form-check-input" type="checkbox"></td>
            <td>
              <div class="cell-title" style="color: var(--green);">#INV-08-2026-07C</div>
              <div class="cell-sub"><span class="text-danger">Trễ 19 ngày</span></div>
            </td>
            <td>
              <div class="cell-title">Phòng 07C</div>
              <div class="cell-sub">Quốc Bảo (0911xxx)</div>
            </td>
            <td><strong>3,6tr ₫</strong></td>
            <td><span class="badge-status overdue">Quá hạn (19 ngày)</span></td>
            <td class="text-end">
              <button class="btn btn-sm btn-outline-danger me-1"><i class="bi bi-cone-striped"></i></button>
              <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye-fill"></i></button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-between align-items-center pt-3">
      <span class="text-muted small">Hiển thị 1–7 trên 9 hóa đơn</span>
      <div class="d-flex gap-1">
        <button class="btn btn-sm btn-outline-secondary" disabled>‹</button>
        <button class="btn btn-sm btn-success">1</button>
        <button class="btn btn-sm btn-outline-secondary">2</button>
        <button class="btn btn-sm btn-outline-secondary">›</button>
      </div>
    </div>
  </div>
</div>

<!-- MODAL: CHỐT ĐIỆN NƯỚC -->
<div class="modal fade" id="bulkCreateModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-light">
        <div>
          <h5 class="modal-title fw-bold" style="color: var(--green);"><i class="bi bi-magic me-2"></i>Chốt điện/nước <span>tháng 10/2026</span></h5>
          <div class="text-muted" style="font-size: 13px;">Nhập số điện/nước mới — hệ thống tự tính tiền và tạo hóa đơn.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body p-0">
        <div class="d-flex flex-wrap gap-3 align-items-end px-4 pt-3 pb-2">
          <div>
            <label class="form-label small fw-bold mb-1">Khu trọ</label>
            <select class="form-select form-select-sm" style="width:220px;">
              <option>Khu trọ Hoa Mai</option>
              <option>Chung cư mini Trần Khát Chân</option>
            </select>
          </div>
          <div>
            <label class="form-label small fw-bold mb-1">Kỳ thanh toán</label>
            <input type="month" class="form-control form-control-sm" value="2026-10" style="width:150px;">
          </div>
          <div>
            <label class="form-label small fw-bold mb-1">Giá điện / kWh</label>
            <input type="number" class="form-control form-control-sm" value="3500" style="width:110px;">
          </div>
          <div>
            <label class="form-label small fw-bold mb-1">Giá nước / m³</label>
            <input type="number" class="form-control form-control-sm" value="20000" style="width:110px;">
          </div>
          <div>
            <label class="form-label small fw-bold mb-1">Phí dịch vụ</label>
            <input type="number" class="form-control form-control-sm" value="150000" style="width:120px;">
          </div>
        </div>
        <table class="table mb-0 align-middle">
          <thead class="table-light text-muted" style="font-size: 12px;">
            <tr>
              <th class="ps-4"><input class="form-check-input" type="checkbox" checked></th>
              <th>PHÒNG</th>
              <th class="text-end">SỐ ĐIỆN CŨ</th>
              <th class="text-end">SỐ ĐIỆN MỚI</th>
              <th class="text-end">SỐ NƯỚC CŨ</th>
              <th class="text-end pe-4">SỐ NƯỚC MỚI</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="ps-4"><input class="form-check-input" type="checkbox" checked></td>
              <td class="fw-bold">Phòng 12A</td>
              <td class="text-end text-muted">1450</td>
              <td class="text-end"><input type="number" class="form-control form-control-sm d-inline-block" style="width:100px;" value="1500"></td>
              <td class="text-end text-muted">120</td>
              <td class="text-end pe-4"><input type="number" class="form-control form-control-sm d-inline-block" style="width:100px;" value="124"></td>
            </tr>
            <tr>
              <td class="ps-4"><input class="form-check-input" type="checkbox" checked></td>
              <td class="fw-bold">Phòng 14B</td>
              <td class="text-end text-muted">2100</td>
              <td class="text-end"><input type="number" class="form-control form-control-sm d-inline-block" style="width:100px;" value="2185"></td>
              <td class="text-end text-muted">340</td>
              <td class="text-end pe-4"><input type="number" class="form-control form-control-sm d-inline-block" style="width:100px;" value="348"></td>
            </tr>
            <tr>
              <td class="ps-4"><input class="form-check-input" type="checkbox" checked></td>
              <td class="fw-bold">Phòng 05A</td>
              <td class="text-end text-muted">980</td>
              <td class="text-end"><input type="number" class="form-control form-control-sm d-inline-block" style="width:100px;" value="1015"></td>
              <td class="text-end text-muted">90</td>
              <td class="text-end pe-4"><input type="number" class="form-control form-control-sm d-inline-block" style="width:100px;" value="93"></td>
            </tr>
          </tbody>
        </table>
        <div class="d-flex justify-content-between align-items-center bg-light rounded-3 mx-4 mb-3 p-3">
          <span class="fw-bold">Tổng dự kiến (<span>3</span> phòng)</span>
          <span class="fw-bold fs-5" style="color: var(--green);">11,8tr ₫</span>
        </div>
      </div>
      <div class="modal-footer bg-light border-0">
        <button type="button" class="btn btn-light border fw-bold px-4" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-brand fw-bold px-4"><i class="bi bi-send-check me-1"></i> Phát hành Hóa đơn</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>