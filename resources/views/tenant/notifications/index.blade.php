<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Thông báo của bạn</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .skeleton-mode .hide-on-skeleton { opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box { background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; color: transparent !important; border-color: transparent !important; pointer-events: none; }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .animate-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
  .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }
  
  /* Notification Panel */
  .notify-panel { background: #fff; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; overflow: hidden; }
  .notify-header { padding: 20px 32px; border-bottom: 1px solid #f0f0f0; display: flex; justify-content: space-between; align-items: center; background: #fff; }
  
  /* Filter Tabs */
  .filter-tabs { display: flex; gap: 12px; margin-bottom: 20px; overflow-x: auto; padding-bottom: 4px; }
  .filter-tab { padding: 8px 16px; border-radius: 99px; font-size: 13px; font-weight: 700; color: var(--muted); background: #fff; border: 1px solid #e0e0e0; text-decoration: none; transition: 0.2s; white-space: nowrap; }
  .filter-tab:hover, .filter-tab.active { background: var(--green-dark); color: #fff; border-color: var(--green-dark); }

  /* Notify Item */
  .notify-item { padding: 24px 32px; border-bottom: 1px solid #f9f9f9; display: flex; gap: 20px; transition: 0.2s; text-decoration: none; color: inherit; align-items: flex-start; }
  .notify-item:last-child { border-bottom: none; }
  .notify-item:hover { background: #fafafa; }
  
  /* Unread State */
  .notify-item.unread { background: #f4faf8; position: relative; }
  .notify-item.unread::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: var(--green); }
  .notify-dot { width: 10px; height: 10px; background: var(--green); border-radius: 50%; display: inline-block; flex-shrink: 0; margin-top: 6px; }

  /* Icons matched to DB 'type' */
  .notify-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
  .icon-invoice { background: #fff7d7; color: #9b6a00; }      /* type: invoice */
  .icon-maintenance { background: #eaf2fc; color: #0d6efd; }  /* type: maintenance */
  .icon-contract { background: #eaf3ef; color: var(--green-dark); } /* type: contract */
  .icon-appointment { background: #fdf0ed; color: var(--red); } /* type: viewing_appointment */

  .notify-time { font-size: 12px; color: #aaa; margin-top: 8px; font-weight: 600; }
  .mark-read-btn { font-size: 13px; font-weight: 700; color: var(--green); cursor: pointer; text-decoration: none; transition: 0.2s; }
  .mark-read-btn:hover { color: var(--green-dark); text-decoration: underline; }
</style>
</head>
<body class="skeleton-mode">

<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ route('tenant.home') }}">Trọ <span>Ơi</span></a>
    <div class="collapse navbar-collapse" id="mainMenu">
      <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.home') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#rooms">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
      </ul>
      
      <!-- Cái chuông thông báo -->
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

<div class="page-wrap" style="padding-top: 40px; max-width: 850px; margin: 0 auto;">
  
  <div class="mb-4 animate-up delay-1">
    <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Thông báo của bạn</span></h2>
    <div class="text-muted mt-2 skeleton-box"><span class="hide-on-skeleton">Cập nhật nhanh các thông tin quan trọng từ Ban quản lý và Chủ nhà.</span></div>
  </div>

  <!-- Thông báo thành công sẽ hiện ở đây khi búng về -->
  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3 animate-up delay-1" role="alert" style="background-color: #d1e7dd; color: #0f5132; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
      <strong>✓ Thành công!</strong> {{ session('success') }}
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="filter-tabs animate-up delay-2 skeleton-box rounded-pill border-0 p-0 mb-4">
    <a href="#" class="filter-tab active hide-on-skeleton">Tất cả</a>
    <a href="#" class="filter-tab hide-on-skeleton">Chưa đọc (2)</a>
    <a href="#" class="filter-tab hide-on-skeleton">💰 Hóa đơn</a>
    <a href="#" class="filter-tab hide-on-skeleton">🔧 Sửa chữa</a>
  </div>

  <div class="notify-panel animate-up delay-3 skeleton-box">
    <div class="hide-on-skeleton">
      
      <div class="notify-header">
        <h5 class="fw-bold mb-0 text-dark" style="font-size: 16px;">Mới nhất</h5>
        
        <!-- Đã sửa thành Form POST trỏ đúng về Route mark_read -->
        <form action="{{ route('tenant.notifications.mark_read') }}" method="POST" class="m-0">
          @csrf
          <button type="submit" class="mark-read-btn" style="background: transparent; border: none; padding: 0;">
            ✓ Đánh dấu tất cả đã đọc
          </button>
        </form>
      </div>

      <!-- Item 1: Invoice (Unread) -->
      <a href="{{ route('tenant.invoices.show', 1) }}" class="notify-item unread">
        <div class="notify-icon icon-invoice">💰</div>
        <div class="flex-grow-1">
          <div class="fw-bold text-dark" style="font-size: 15px;">Hóa đơn tháng 09/2026 đã được phát hành</div>
          <div class="text-muted mt-1" style="font-size: 14px; line-height: 1.5;">Hóa đơn tiền phòng và dịch vụ tháng 9 của Phòng 12A là <strong>3.450.000đ</strong>. Hạn thanh toán: 10/09/2026.</div>
          <div class="notify-time">10 phút trước</div>
        </div>
        <div class="notify-dot"></div>
      </a>

      <!-- Item 2: Maintenance (Unread) -->
      <a href="{{ route('tenant.maintenance.show', 1) }}" class="notify-item unread">
        <div class="notify-icon icon-maintenance">🔧</div>
        <div class="flex-grow-1">
          <div class="fw-bold text-dark" style="font-size: 15px;">Cập nhật tiến độ sửa chữa</div>
          <div class="text-muted mt-1" style="font-size: 14px; line-height: 1.5;">BQL đã phản hồi ticket #TCK-0985 (Máy lạnh kêu to): "Thợ sẽ qua kiểm tra vào 17h chiều nay nhé".</div>
          <div class="notify-time">2 giờ trước</div>
        </div>
        <div class="notify-dot"></div>
      </a>

      <div class="notify-header mt-2" style="background: #fafafa; border-top: 1px solid #f0f0f0;">
        <h5 class="fw-bold mb-0 text-muted" style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px;">Trước đó</h5>
      </div>

      <!-- Item 3: Contract (Read) -->
      <a href="#" class="notify-item">
        <div class="notify-icon icon-contract">📜</div>
        <div class="flex-grow-1">
          <div class="fw-bold text-dark" style="font-size: 15px;">Hợp đồng thuê phòng sắp hết hạn</div>
          <div class="text-muted mt-1" style="font-size: 14px; line-height: 1.5;">Hợp đồng #HD-092026 của bạn sẽ hết hạn sau 30 ngày nữa. Vui lòng liên hệ BQL nếu bạn muốn gia hạn.</div>
          <div class="notify-time">01/09/2026</div>
        </div>
      </a>

      <!-- Item 4: Viewing Appointment (Read) -->
      <a href="#" class="notify-item">
        <div class="notify-icon icon-appointment">📅</div>
        <div class="flex-grow-1">
          <div class="fw-bold text-dark" style="font-size: 15px;">Lịch xem phòng đã được xác nhận</div>
          <div class="text-muted mt-1" style="font-size: 14px; line-height: 1.5;">Chủ nhà đã xác nhận lịch đến xem Phòng 12A - Tòa nhà Hoa Mai vào lúc 09:00 ngày 28/08/2026.</div>
          <div class="notify-time">26/08/2026</div>
        </div>
      </a>

    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => { document.body.classList.remove('skeleton-mode'); }, 600); 
  });
</script>
</body>
</html>