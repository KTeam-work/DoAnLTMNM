<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Chi tiết sự cố</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .detail-panel { background: #fff; border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; height: 100%; }
  
  /* Timeline Styles */
  .timeline { position: relative; padding-left: 30px; list-style: none; margin: 0; }
  .timeline::before { content: ''; position: absolute; left: 7px; top: 0; bottom: 0; width: 2px; background: #eef0ef; }
  .timeline-item { position: relative; margin-bottom: 24px; }
  .timeline-item:last-child { margin-bottom: 0; }
  .timeline-dot { position: absolute; left: -30px; top: 4px; width: 16px; height: 16px; border-radius: 50%; background: #fff; border: 3px solid #eef0ef; z-index: 2; transition: 0.3s; }
  
  /* Timeline Statuses */
  .timeline-item.done .timeline-dot { border-color: var(--green); background: var(--green); }
  .timeline-item.active .timeline-dot { border-color: var(--blue); background: var(--blue); box-shadow: 0 0 0 4px var(--blue-soft); }
  
  .timeline-content { background: #fafafa; border-radius: 12px; padding: 16px; border: 1px solid #eef0ef; margin-left: 10px; }
  .timeline-date { font-size: 11.5px; font-weight: 700; color: var(--muted); margin-bottom: 4px; }
  
  /* Info Grid */
  .info-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 24px; }
  .info-item { background: #fdfdfd; padding: 12px 16px; border-radius: 12px; border: 1px solid #f0f0f0; }
  .info-label { font-size: 11px; font-weight: 700; color: var(--muted); text-transform: uppercase; margin-bottom: 4px; }
  .info-value { font-size: 14px; font-weight: 700; color: var(--text); }
  
  .img-gallery img { width: 100px; height: 100px; object-fit: cover; border-radius: 12px; cursor: pointer; border: 1px solid #eef0ef; transition: 0.2s; }
  .img-gallery img:hover { border-color: var(--green); transform: scale(1.05); }
  
  /* Custom Badge */
  .badge-status.processing { background: rgba(13, 110, 253, 0.1); color: #0d6efd; padding: 8px 16px; border-radius: 99px; font-weight: 800; font-size: 12px; }
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

<div class="page-wrap" style="padding-top: 40px; max-width: 1000px;">
  
  <!-- Breadcrumb & Header -->
  <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
    <div>
      <a href="{{ route('tenant.maintenance.index') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 13px;">← QUAY LẠI DANH SÁCH</a>
      <div class="d-flex align-items-center gap-3 mt-2">
        <h2 class="page-title mb-0" style="font-size: 28px;">Chi tiết Ticket #TCK-0985</h2>
        <span class="badge-status processing">🔧 Đang xử lý</span>
      </div>
    </div>
    <!-- Nút Hủy cho phép người dùng rút lại yêu cầu nếu chưa có thợ tới -->
    <button class="btn btn-light border text-danger fw-bold px-4 py-2" style="border-radius: 10px;">✖ Hủy yêu cầu</button>
  </div>

  <div class="row g-4">
    <!-- Cột Trái: Thông tin chi tiết sự cố -->
    <div class="col-lg-7">
      <div class="detail-panel">
        <h4 class="fw-bold mb-4" style="color: var(--green-dark); font-size: 20px;">Máy lạnh kêu to và không mát</h4>
        
        <div class="info-grid">
          <div class="info-item">
            <div class="info-label">Khu vực / Danh mục</div>
            <div class="info-value">❄️ Thiết bị điện lạnh</div>
          </div>
          <div class="info-item">
            <div class="info-label">Mức độ ưu tiên</div>
            <div class="info-value" style="color: var(--green);">🔧 Bình thường (Medium)</div>
          </div>
          <div class="info-item">
            <div class="info-label">Phòng báo cáo</div>
            <div class="info-value">Phòng 12A</div>
          </div>
          <div class="info-item">
            <div class="info-label">Người báo cáo</div>
            <div class="info-value">Thanh Huyền</div>
          </div>
        </div>

        <div class="mb-4">
          <div class="info-label">Mô tả hiện trạng</div>
          <div class="p-3 mt-2" style="background: #fafafa; border-radius: 12px; font-size: 14px; line-height: 1.6; border: 1px solid #eef0ef;">
            Điều hòa bật vẫn lên nguồn, có gió thổi ra nhưng hoàn toàn không phả ra hơi lạnh dù đã chỉnh 16 độ. Cục nóng bên ngoài ban công kêu rất to và rung lắc mạnh. Mong BQL cho thợ qua kiểm tra sớm vì trời đang rất nóng ạ.
          </div>
        </div>

        <div>
          <div class="info-label mb-2">Ảnh đính kèm (2)</div>
          <div class="img-gallery d-flex gap-3 flex-wrap">
            <!-- Ảnh giả lập -->
            <img src="https://via.placeholder.com/150/eaf3ef/20584f?text=Anh+1" alt="Lỗi máy lạnh 1">
            <img src="https://via.placeholder.com/150/eaf3ef/20584f?text=Anh+2" alt="Lỗi cục nóng">
          </div>
        </div>
      </div>
    </div>

    <!-- Cột Phải: Tiến độ xử lý (Timeline) -->
    <div class="col-lg-5">
      <div class="detail-panel">
        <h4 class="fw-bold mb-4" style="color: var(--green-dark); font-size: 18px;">📍 Tiến độ xử lý</h4>
        
        <ul class="timeline">
          <!-- Step 1: Hoàn thành -->
          <li class="timeline-item done">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="timeline-date">14:15 - 01/09/2026</div>
              <div class="fw-bold text-dark" style="font-size: 14px;">Gửi yêu cầu thành công</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Hệ thống đã ghi nhận sự cố của bạn.</div>
            </div>
          </li>

          <!-- Step 2: Hoàn thành -->
          <li class="timeline-item done">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <div class="timeline-date">15:30 - 01/09/2026</div>
              <div class="fw-bold text-dark" style="font-size: 14px;">BQL đã tiếp nhận</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Quản lý tòa nhà đã đọc và đánh giá mức độ sự cố.</div>
            </div>
          </li>

          <!-- Step 3: Đang thực hiện (Active) -->
          <li class="timeline-item active">
            <div class="timeline-dot"></div>
            <div class="timeline-content border-primary bg-white" style="box-shadow: 0 4px 15px rgba(13, 110, 253, 0.08);">
              <div class="timeline-date" style="color: var(--blue);">16:00 - 01/09/2026</div>
              <div class="fw-bold text-dark" style="font-size: 14px;">Đang phân công thợ (Processing)</div>
              <div class="mt-2 p-2 rounded" style="background: var(--blue-soft); border-left: 3px solid var(--blue); font-size: 13px;">
                <strong>Ghi chú từ BQL:</strong> Thợ điện lạnh sẽ qua kiểm tra phòng bạn vào lúc 17h00 chiều nay nhé. Bạn chú ý điện thoại.
              </div>
            </div>
          </li>

          <!-- Step 4: Chưa tới (Pending) -->
          <li class="timeline-item">
            <div class="timeline-dot"></div>
            <div class="timeline-content" style="background: transparent; border-color: transparent;">
              <div class="fw-bold" style="font-size: 14px; color: #ccc;">Nghiệm thu & Hoàn thành</div>
            </div>
          </li>
        </ul>

      </div>
    </div>
  </div>

</div>
</body>
</html>