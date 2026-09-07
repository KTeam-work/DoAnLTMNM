<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Chi tiết Hợp đồng | Trọ Ơi Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  body { background-color: #f8fafc; font-family: 'Be Vietnam Pro', sans-serif; }
  .panel-modern { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 1.5rem; margin-bottom: 1.5rem; }
  .badge-soft-success { background: #dcfce7; color: #16a34a; font-weight: 600; padding: 6px 12px; border-radius: 6px; }
  .detail-label { font-size: 0.85rem; color: #64748b; margin-bottom: 0.25rem; }
  .detail-value { font-size: 1rem; font-weight: 600; color: #0f172a; }
  .table-minimal th { font-weight: 500; color: #64748b; font-size: 0.85rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem; }
  .table-minimal td { padding: 0.75rem 0; border-bottom: 1px dashed #e2e8f0; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ url('/') }}">Trọ <span>Ơi</span></a>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="{{ url('/') }}">Tổng quan</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Nhà &amp; Phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ url('/owner/tenants') }}">Người thuê</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ url('/owner/contracts') }}">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hóa đơn</a></li>
      </ul>
      <div class="navbar-actions">
        <a href="#" class="user-chip"><span class="user-avatar">MT</span><span class="user-meta"><span class="user-name d-block">Minh Tuấn</span><span class="user-role">Chủ trọ</span></span></a>
      </div>
    </div>
  </div>
</nav>

<div class="page-wrap mt-4 mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <a href="{{ url('/owner/contracts') }}" class="text-decoration-none text-muted">← Quay lại danh sách</a>
    <div class="d-flex gap-2">
      <button class="btn btn-outline-secondary btn-sm px-3">Tải PDF</button>
      <button class="btn btn-outline-danger btn-sm px-3">Thanh lý HĐ</button>
    </div>
  </div>

  <div class="d-flex align-items-center gap-3 mb-4">
    <h2 class="fw-bold text-dark m-0">Hợp đồng #HD-2026-01</h2>
    <span class="badge-soft-success">Đang hiệu lực</span>
  </div>

  <div class="row">
    <!-- Cột trái: Thông tin phòng & Dịch vụ -->
    <div class="col-lg-8">
      <div class="panel-modern">
        <h5 class="fw-bold mb-4">Thông tin thuê</h5>
        <div class="row g-4">
          <div class="col-md-4"><div class="detail-label">Phòng thuê</div><div class="detail-value text-primary">Phòng 12A - Nhà Q.7</div></div>
          <div class="col-md-4"><div class="detail-label">Giá thuê</div><div class="detail-value text-danger">2.800.000 đ/tháng</div></div>
          <div class="col-md-4"><div class="detail-label">Tiền cọc</div><div class="detail-value">2.800.000 đ</div></div>
          <div class="col-md-4"><div class="detail-label">Ngày bắt đầu</div><div class="detail-value">01/02/2026</div></div>
          <div class="col-md-4"><div class="detail-label">Ngày kết thúc</div><div class="detail-value">01/02/2027</div></div>
          <div class="col-md-4"><div class="detail-label">Kỳ thanh toán</div><div class="detail-value">Mùng 5 hàng tháng</div></div>
        </div>
      </div>

      <div class="panel-modern">
        <h5 class="fw-bold mb-3">Dịch vụ & Tiêu thụ</h5>
        <table class="table w-100 table-minimal">
          <thead><tr><th>Tên dịch vụ</th><th>Đơn giá</th><th>Cách tính</th></tr></thead>
          <tbody>
            <tr><td>⚡ Điện</td><td>3.500 đ</td><td>Theo đồng hồ (kWh)</td></tr>
            <tr><td>💧 Nước</td><td>100.000 đ</td><td>Theo người</td></tr>
            <tr><td>🌐 Rác & Wifi</td><td>80.000 đ</td><td>Cố định theo phòng</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Cột phải: Thông tin khách thuê -->
    <div class="col-lg-4">
      <div class="panel-modern">
        <h5 class="fw-bold mb-3">Đại diện thuê phòng</h5>
        <div class="text-center mb-3">
          <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center text-primary fw-bold mb-2" style="width:60px; height:60px; font-size: 24px;">TH</div>
          <div class="fw-bold fs-5 text-dark">Thanh Huyền</div>
        </div>
        <hr class="border-light-subtle">
        <div class="mb-2"><div class="detail-label">Số điện thoại</div><div class="detail-value">0901 234 567</div></div>
        <div class="mb-2"><div class="detail-label">Số CMND / CCCD</div><div class="detail-value">079199012345</div></div>
        <div class="mb-2"><div class="detail-label">Ngày sinh</div><div class="detail-value">14/05/1998</div></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>