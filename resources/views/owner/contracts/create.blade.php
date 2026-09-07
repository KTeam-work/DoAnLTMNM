<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tạo Hợp Đồng | Trọ Ơi Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  body { background-color: #f8fafc; font-family: 'Be Vietnam Pro', sans-serif; }
  .panel-modern { background: #fff; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 1px 3px rgba(0,0,0,0.02); padding: 1.5rem; margin-bottom: 1.5rem; }
  .form-label { font-weight: 500; color: #475569; font-size: 0.9rem; }
  .form-control, .form-select { border-radius: 8px; border-color: #cbd5e1; padding: 0.6rem 1rem; }
  .form-control:focus, .form-select:focus { box-shadow: none; border-color: #10b981; }
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
  <div class="mb-3"><a href="{{ url('/owner/contracts') }}" class="text-decoration-none text-muted">← Quay lại danh sách</a></div>
  <h2 class="fw-bold text-dark mb-4">Tạo hợp đồng mới</h2>

  <form action="#" method="POST">
    <div class="row">
      <div class="col-lg-8">
        <!-- Thông tin phòng -->
        <div class="panel-modern">
          <h5 class="fw-bold mb-3">1. Thông tin thuê phòng</h5>
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label">Chọn nhà / khu trọ</label>
              <select class="form-select"><option>Nhà trọ Q.7</option><option>Chung cư mini Thủ Đức</option></select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Chọn phòng trống</label>
              <select class="form-select"><option>Phòng 102</option><option>Phòng 205</option></select>
            </div>
            <div class="col-md-6">
              <label class="form-label">Giá thuê (VNĐ/tháng)</label>
              <input type="number" class="form-control" value="3000000">
            </div>
            <div class="col-md-6">
              <label class="form-label">Tiền cọc (VNĐ)</label>
              <input type="number" class="form-control" value="3000000">
            </div>
          </div>
        </div>

        <!-- Thông tin thời gian -->
        <div class="panel-modern">
          <h5 class="fw-bold mb-3">2. Thời hạn hợp đồng</h5>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Ngày bắt đầu</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Ngày kết thúc</label>
              <input type="date" class="form-control">
            </div>
            <div class="col-md-4">
              <label class="form-label">Ngày lập hóa đơn</label>
              <select class="form-select"><option>Mùng 1 hàng tháng</option><option>Mùng 5 hàng tháng</option></select>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-4">
        <!-- Đại diện thuê -->
        <div class="panel-modern">
          <h5 class="fw-bold mb-3">3. Người đại diện thuê</h5>
          <div class="mb-3">
            <label class="form-label">Họ và tên</label>
            <input type="text" class="form-control" placeholder="Nhập tên khách...">
          </div>
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="text" class="form-control" placeholder="0901...">
          </div>
          <div class="mb-3">
            <label class="form-label">Số CMND / CCCD</label>
            <input type="text" class="form-control" placeholder="079...">
          </div>
        </div>
      </div>
    </div>
    
    <div class="d-flex justify-content-end gap-2 mt-2">
      <a href="{{ url('/owner/contracts') }}" class="btn btn-light px-4 py-2 border">Hủy bỏ</a>
      <button type="submit" class="btn btn-success px-4 py-2">Tạo hợp đồng</button>
    </div>
  </form>
</div>
</body>
</html>