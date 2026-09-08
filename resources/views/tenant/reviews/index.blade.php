<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Đánh giá & Nhận xét</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  .skeleton-mode .hide-on-skeleton { opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box { background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; color: transparent !important; border-color: transparent !important; pointer-events: none; }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
  .animate-up { animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
  .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; }
  
  /* Panel & Form */
  .review-panel { background: #fff; border-radius: 20px; padding: 32px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; height: 100%; }
  .form-control { border-radius: 12px; padding: 14px 18px; border: 1px solid #e0e0e0; font-size: 14px; background: #fafafa; transition: 0.2s; }
  .form-control:focus { background: #fff; border-color: var(--yellow); box-shadow: 0 0 0 4px rgba(245, 200, 75, 0.15); }
  
  /* CSS-only Star Rating */
  .star-rating { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 8px; }
  .star-rating input { display: none; }
  .star-rating label { font-size: 36px; color: #e0e0e0; cursor: pointer; transition: 0.2s; line-height: 1; margin: 0; }
  .star-rating input:checked ~ label, 
  .star-rating label:hover, 
  .star-rating label:hover ~ label { color: var(--yellow); }
  
  /* Review Cards */
  .review-card { padding: 20px; border-radius: 16px; border: 1px solid #f0f0f0; background: #fafafa; margin-bottom: 16px; transition: 0.3s; }
  .review-card:hover { border-color: #e6dcc2; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
  .static-stars { color: var(--yellow); font-size: 16px; letter-spacing: 2px; }
  
  /* Status Badges */
  .badge-visible { background: rgba(32, 88, 79, 0.1); color: var(--green-dark); padding: 6px 12px; border-radius: 99px; font-size: 11px; font-weight: 800; }
  .badge-hidden { background: var(--red-soft); color: var(--red); padding: 6px 12px; border-radius: 99px; font-size: 11px; font-weight: 800; }
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
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a></li>
        <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
      </ul>
      <div class="navbar-actions">
        <a href="#" class="user-chip skeleton-box"><span class="user-avatar hide-on-skeleton">TH</span><span class="user-name hide-on-skeleton">Thanh Huyền</span></a>
      </div>
    </div>
  </div>
</nav>

<div class="page-wrap" style="padding-top: 40px; max-width: 1100px;">
  
  <div class="mb-4 animate-up delay-1">
    <h2 class="page-title skeleton-box"><span class="hide-on-skeleton">Đánh giá & Nhận xét</span></h2>
    <div class="text-muted mt-2 skeleton-box"><span class="hide-on-skeleton">Chia sẻ trải nghiệm của bạn về phòng thuê để giúp cộng đồng có cái nhìn khách quan.</span></div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-4 border-0 rounded-3" role="alert" style="background-color: #d1e7dd; color: #0f5132;">
      <strong>✓ Thành công!</strong> {{ session('success') }}
      <button type="button" class="btn-close shadow-none" data-bs-dismiss="alert"></button>
    </div>
  @endif

  <div class="row g-4">
    <!-- Cột Trái: Viết đánh giá mới (Tạo Record vào DB) -->
    <div class="col-lg-5 animate-up delay-2">
      <div class="review-panel skeleton-box">
        <form action="{{ route('tenant.reviews.store') }}" method="POST" class="hide-on-skeleton">
          @csrf
          <h4 class="fw-bold mb-4" style="color: var(--green-dark); font-size: 18px;">✍️ Viết đánh giá mới</h4>
          
          <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Phòng đang thuê</label>
            <div class="form-control fw-bold text-dark" style="background: #eaf3ef; border-color: var(--green-soft);">
              🏠 Phòng 12A - Khu trọ Hoa Mai
            </div>
            <!-- Input ẩn chứa room_id để submit -->
            <input type="hidden" name="room_id" value="1"> 
          </div>

          <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Chất lượng phòng (1-5 Sao) <span class="text-danger">*</span></label>
            <!-- Giao diện chọn sao (Radio Inputs) -->
            <div class="star-rating">
              <input type="radio" id="star5" name="rating" value="5">
              <label for="star5" title="5 sao">★</label>
              
              <input type="radio" id="star4" name="rating" value="4">
              <label for="star4" title="4 sao">★</label>
              
              <input type="radio" id="star3" name="rating" value="3">
              <label for="star3" title="3 sao">★</label>
              
              <input type="radio" id="star2" name="rating" value="2">
              <label for="star2" title="2 sao">★</label>
              
              <input type="radio" id="star1" name="rating" value="1" required>
              <label for="star1" title="1 sao">★</label>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Nhận xét chi tiết <span class="text-danger">*</span></label>
            <textarea name="comment" class="form-control" rows="4" placeholder="Phòng có ồn không? Ban quản lý có nhiệt tình không?..." required style="resize: none;"></textarea>
          </div>

          <button type="submit" class="btn btn-brand w-100 py-3 mt-2" style="font-size: 14px; box-shadow: 0 4px 15px rgba(245,200,75,0.4);">
            🚀 Gửi Đánh Giá
          </button>
        </form>
      </div>
    </div>

    <!-- Cột Phải: Lịch sử đã đánh giá -->
    <div class="col-lg-7 animate-up delay-2">
      <div class="review-panel skeleton-box">
        <div class="hide-on-skeleton">
          
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="fw-bold mb-0" style="color: var(--green-dark); font-size: 18px;">🕒 Lịch sử đánh giá của bạn</h4>
            <span class="text-muted" style="font-size: 13px; font-weight: 600;">Tổng: 2 lượt</span>
          </div>

          <!-- Thanh Tìm kiếm & Lọc -->
          <div class="d-flex gap-2 mb-4 border-bottom pb-4">
            <div class="position-relative flex-grow-1">
              <span class="position-absolute" style="top: 12px; left: 14px; font-size: 14px; color: #aaa;">🔍</span>
              <input type="text" class="form-control" placeholder="Tìm theo tên phòng, khu trọ..." style="padding-left: 38px; border-radius: 10px;">
            </div>
            <select class="form-select w-auto fw-bold text-dark" style="border-radius: 10px; background-color: #fafafa;">
              <option value="newest">Lọc: Mới nhất</option>
              <option value="oldest">Lọc: Cũ nhất</option>
              <option value="5star">⭐ Đánh giá 5 Sao</option>
              <option value="hidden">🚫 Bị ẩn</option>
            </select>
          </div>

          <!-- Lịch sử 1 (Visible) -->
          <div class="review-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <div class="static-stars">★★★★★</div>
                <div class="fw-bold mt-1 text-dark" style="font-size: 15px;">Phòng 12A - Khu trọ Hoa Mai</div>
              </div>
              <div class="text-end">
                <span class="badge-visible mb-1 d-inline-block">✓ Đang hiển thị</span>
                <div class="text-muted" style="font-size: 11px;">05/09/2026</div>
              </div>
            </div>
            <p class="text-muted mb-0 mt-2" style="font-size: 13.5px; line-height: 1.6;">
              "Phòng rất mới, sạch sẽ, BQL hỗ trợ nhiệt tình mỗi khi hỏng hóc đồ đạc. Tuy nhiên bãi để xe hơi chật vào buổi tối."
            </p>
          </div>

          <!-- Lịch sử 2 (Hidden by Admin) -->
          <div class="review-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <div class="static-stars" style="color: #ccc;">★★☆☆☆</div>
                <div class="fw-bold mt-1 text-dark" style="font-size: 15px;">Phòng 3B - Chung cư mini Trần Khát Chân</div>
              </div>
              <div class="text-end">
                <span class="badge-hidden mb-1 d-inline-block">✖ Bị ẩn do vi phạm</span>
                <div class="text-muted" style="font-size: 11px;">12/03/2025</div>
              </div>
            </div>
            <p class="text-muted mb-0 mt-2" style="font-size: 13.5px; line-height: 1.6;">
              "Chủ nhà quá khắt khe giờ giấc, tiền điện tính sai lệch tháng vừa rồi..."
            </p>
          </div>

        </div>
      </div>
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