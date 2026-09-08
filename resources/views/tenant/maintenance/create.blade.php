<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Báo cáo sự cố mới</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .form-panel { background: #fff; border-radius: 20px; padding: 40px; box-shadow: 0 10px 40px rgba(0,0,0,0.04); border: 1px solid #f0f0f0; }
  .form-control, .form-select { border-radius: 12px; padding: 14px 18px; border: 1px solid #e0e0e0; font-size: 14px; background: #fafafa; transition: all 0.2s ease; }
  .form-control:focus, .form-select:focus { background: #fff; border-color: var(--green); box-shadow: 0 0 0 4px rgba(32, 88, 79, 0.1); }
  .form-label { font-size: 12px; font-weight: 800; color: var(--muted); margin-bottom: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
  
  /* Advanced Radio Cards for Priority */
  .radio-card-input { display: none; }
  .radio-card { border: 2px solid #eef0ef; border-radius: 14px; padding: 20px; cursor: pointer; transition: 0.2s; background: #fff; height: 100%; }
  .radio-card:hover { border-color: #d1d5d3; }
  .radio-card-input:checked + .radio-card.urgent { border-color: var(--red); background: var(--red-soft); box-shadow: 0 4px 15px rgba(198, 91, 74, 0.15); }
  .radio-card-input:checked + .radio-card.normal { border-color: var(--green); background: var(--green-soft); box-shadow: 0 4px 15px rgba(32, 88, 79, 0.15); }
  
  /* Drag & Drop File Zone */
  .upload-zone { border: 2px dashed #d1d5d3; border-radius: 16px; padding: 40px 20px; text-align: center; background: #fafafa; cursor: pointer; transition: 0.3s; display: flex; flex-direction: column; align-items: center; gap: 12px; }
  .upload-zone:hover { border-color: var(--green); background: rgba(32, 88, 79, 0.03); }
  .upload-icon { width: 56px; height: 56px; background: #fff; border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.05); display: flex; align-items: center; justify-content: center; font-size: 24px; color: var(--green); }
  
  .btn-brand { border-radius: 12px; font-weight: 800; transition: 0.2s; letter-spacing: 0.3px; }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('tenant.home') }}">Trọ <span>Ơi</span></a>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.home') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#rooms">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Yêu thích</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
      </ul>
       <div class="navbar-actions skeleton-box">
        <div class="hide-on-skeleton d-flex align-items-center gap-3">
          <!-- Thêm text-decoration-none vào class -->
          <a href="{{ route('tenant.notifications.index') }}" class="notif-btn text-decoration-none">
            🔔<span class="notif-dot"></span>
          </a>
          
          <!-- Thêm text-decoration-none vào class -->
          <a href="#" class="user-chip text-decoration-none">
            <span class="user-avatar">TH</span>
            <span class="user-meta">
              <span class="user-name d-block" style="text-decoration: none;">Thanh Huyền</span>
              <span class="user-role">Người thuê</span>
            </span>
            <span class="caret">▾</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</nav>

<div class="page-wrap" style="padding-top: 40px; max-width: 900px;">
  
  <div class="mb-4">
    <a href="{{ route('tenant.maintenance.index') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 13px;">← QUAY LẠI TRUNG TÂM HỖ TRỢ</a>
    <h2 class="page-title mt-2" style="font-size: 32px;">Tạo Ticket Sự Cố Mới</h2>
    <p class="text-muted mt-2" style="font-size: 14px;">Mô tả chi tiết tình trạng để Ban quản lý có thể chuẩn bị vật tư và khắc phục nhanh nhất.</p>
  </div>

  <div class="form-panel">
    <form action="{{ route('tenant.maintenance.store') }}" method="POST">
      @csrf
      
      <div class="row g-4">
        <!-- Section 1: Thông tin cơ bản -->
        <div class="col-md-6">
          <label class="form-label">Phòng tiếp nhận sự cố</label>
          <div class="form-control fw-bold text-dark d-flex align-items-center" style="background: #f0f0f0; opacity: 0.8;">
            <span style="margin-right: 10px;">🏠</span> Phòng 12A - Tòa A
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Phân loại sự cố <span class="text-danger">*</span></label>
          <select class="form-select fw-bold text-dark" required>
            <option value="" selected disabled>-- Vui lòng chọn hệ thống --</option>
            <option value="dien_nuoc">💧 Hệ thống Điện / Nước</option>
            <option value="dien_lanh">❄️ Thiết bị Điện lạnh (Điều hòa, Tủ lạnh)</option>
            <option value="noi_that">🚪 Nội thất (Giường, Tủ, Cửa, Khóa)</option>
            <option value="khac">🔧 Vấn đề khác</option>
          </select>
        </div>

        <!-- Section 2: Enterprise Priority Cards -->
        <div class="col-12 mt-5">
          <label class="form-label">Đánh giá mức độ nghiêm trọng</label>
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label class="w-100 h-100 m-0">
                <input type="radio" name="priority" value="normal" class="radio-card-input" checked>
                <div class="radio-card normal">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size: 20px;">🛡️</span>
                    <h6 class="fw-bold mb-0 text-dark">Bình thường</h6>
                  </div>
                  <p class="text-muted mb-0" style="font-size: 13px;">Sự cố nhỏ, không ảnh hưởng trực tiếp đến sinh hoạt (Thay bóng đèn, sửa bản lề...). Xử lý trong 24-48h.</p>
                </div>
              </label>
            </div>
            <div class="col-md-6">
              <label class="w-100 h-100 m-0">
                <input type="radio" name="priority" value="urgent" class="radio-card-input">
                <div class="radio-card urgent">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size: 20px;">🚨</span>
                    <h6 class="fw-bold mb-0 text-danger">Khẩn cấp</h6>
                  </div>
                  <p class="text-muted mb-0" style="font-size: 13px;">Sự cố nghiêm trọng, không thể sinh hoạt (Mất điện toàn phòng, vỡ ống nước chính...). Xử lý ngay lập tức.</p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Section 3: Chi tiết & Đính kèm -->
        <div class="col-12 mt-5">
          <label class="form-label">Mô tả hiện trạng <span class="text-danger">*</span></label>
          <textarea class="form-control" rows="5" placeholder="Ví dụ: Điều hòa bật vẫn lên nguồn nhưng không phả ra hơi lạnh, cục nóng bên ngoài kêu rất to..." required style="resize: none;"></textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Tài liệu đính kèm / Hình ảnh hiện trường (Tùy chọn)</label>
          <div class="upload-zone">
            <div class="upload-icon">📁</div>
            <div>
              <div class="fw-bold text-dark" style="font-size: 15px;">Kéo thả file hoặc nhấn để tải lên</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Hỗ trợ định dạng: JPG, PNG, MP4 (Tối đa 3 file, 10MB/file)</div>
            </div>
          </div>
        </div>
      </div>

      <hr style="border-top: 1px solid #e0e0e0; margin: 40px 0 30px;">

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="policy" required style="cursor: pointer;">
          <label class="form-check-label text-muted" for="policy" style="font-size: 13px; cursor: pointer;">
            Tôi xác nhận các thông tin báo cáo trên là hoàn toàn chính xác.
          </label>
        </div>
        <div>
          <a href="{{ route('tenant.maintenance.index') }}" class="btn btn-light fw-bold px-4 py-3 me-2" style="border-radius: 12px; font-size: 14px;">Hủy bỏ</a>
          <button type="submit" class="btn btn-brand px-5 py-3" style="font-size: 14px; box-shadow: 0 4px 15px rgba(245,200,75,0.4);">🚀 Gửi Ticket</button>
        </div>
      </div>
    </form>
  </div>

</div>
</body>
</html>