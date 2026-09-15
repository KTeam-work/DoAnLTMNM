<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Quản lý Đánh giá | Trọ Ơi Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">
<style>
  /* ============================================================
     CSS riêng cho trang Quản lý Đánh giá.
     .app-navbar / .logo / .app-nav-link / .user-chip ... dùng chung
     từ styles.css của hệ thống, không định nghĩa lại ở đây.
     ============================================================ */

  :root{
    --review-cream:   #FAF7EF;
    --review-forest:  #1E4B36;
    --review-forest-d:#153627;
    --review-sage:    #E7EEE3;
    --review-gold:    #B8871E;
    --review-gold-soft:#FBF1DA;
    --review-clay:    #A85A34;
    --review-clay-soft:#F5E7DD;
    --review-ink:     #1E2621;
    --review-ink-soft:#66756C;
    --review-border:  #E1E6DC;
  }

  body{ background: var(--review-cream); font-family:'Be Vietnam Pro', sans-serif; color: var(--review-ink); }

  .page-wrap{ max-width: 1180px; margin: 0 auto; padding: 0 1.25rem; }

  .page-header{ align-items: flex-end; }
  .page-title{ font-weight: 800; letter-spacing: -0.01em; color: var(--review-ink) !important; }
  .page-desc{ color: var(--review-ink-soft) !important; }

  a, button, input, select, textarea{ outline-color: var(--review-forest); }
  :focus-visible{ outline: 2px solid var(--review-forest); outline-offset: 2px; }

  /* ---------- Tổng quan ---------- */
  .overview-panel{
    background: var(--review-forest);
    border-radius: 18px;
    padding: 2rem 2.25rem;
    color: #fff;
    display: grid;
    grid-template-columns: 220px 1px 1fr;
    gap: 2.25rem;
    align-items: center;
  }
  .overview-score{ text-align: left; }
  .overview-score .num{ font-size: 3.4rem; font-weight: 800; line-height: 1; letter-spacing: -0.02em; }
  .overview-score .num small{ font-size: 1.1rem; font-weight: 500; opacity: .65; margin-left: 4px; }
  .overview-score .stars{ color: var(--review-gold); font-size: 1rem; letter-spacing: 3px; margin: .6rem 0 .35rem; }
  .overview-score .total{ font-size: .875rem; color: rgba(255,255,255,.7); }
  .overview-divider{ background: rgba(255,255,255,.16); align-self: stretch; }

  .rating-bars{ display: flex; flex-direction: column; gap: .55rem; }
  .rating-bar-row{
    display: grid; grid-template-columns: 44px 1fr 40px; align-items: center; gap: .75rem;
    font-size: .85rem; background: none; border: none; padding: 0; width: 100%; cursor: pointer;
    color: inherit; font-family: inherit; border-radius: 6px; transition: background .12s ease;
  }
  .rating-bar-row:hover, .rating-bar-row.is-active{ background: rgba(255,255,255,.08); }
  .rating-bar-row .label{ color: rgba(255,255,255,.75); text-align: right; }
  .rating-bar-track{ height: 7px; background: rgba(255,255,255,.14); border-radius: 20px; overflow: hidden; }
  .rating-bar-fill{ height: 100%; border-radius: 20px; background: var(--review-gold); }
  .rating-bar-row .count{ color: rgba(255,255,255,.75); text-align: left; }
  .rating-bar-row.attn .rating-bar-fill{ background: var(--review-clay); }

  /* ---------- Thanh lọc ---------- */
  .filter-row{ background: #fff; border: 1px solid var(--review-border); border-radius: 12px; padding: .85rem 1.1rem; margin-top: 1.5rem; }
  .filter-row select, .filter-row input{ font-size: .9rem; }
  .filter-row .form-select, .filter-row .form-control{ border-color: var(--review-border); }
  .filter-row .form-select:focus, .filter-row .form-control:focus{ border-color: var(--review-forest); box-shadow: 0 0 0 3px rgba(30,75,54,.1); }
  .filter-count{ font-size: .82rem; color: var(--review-ink-soft); }
  .filter-count strong{ color: var(--review-ink); }
  .active-filter-chip{
    display: inline-flex; align-items: center; gap: 6px; background: var(--review-sage); color: var(--review-forest);
    font-size: .78rem; font-weight: 600; padding: 3px 10px 3px 12px; border-radius: 20px; border: none;
  }
  .active-filter-chip button{ background: none; border: none; color: var(--review-forest); font-size: .9rem; line-height: 1; padding: 0; }

  /* ---------- Danh sách phản hồi ---------- */
  .review-feed{ display: flex; flex-direction: column; gap: .75rem; margin-top: 1rem; }

  .review-row{
    background: #fff; border: 1px solid var(--review-border); border-left: 4px solid var(--review-forest);
    border-radius: 10px; padding: 1.15rem 1.35rem; display: grid;
    grid-template-columns: 200px 1fr 150px; gap: 1.5rem; align-items: start;
    transition: opacity .2s ease, border-color .2s ease;
  }
  .review-row.attn{ border-left-color: var(--review-clay); }
  .review-row.hidden-row{ border-left-color: var(--review-border); background: #FBFBF9; }
  .review-row.is-filtered-out{ display: none; }

  .review-who{ display: flex; gap: .7rem; align-items: flex-start; }
  .review-avatar{
    width: 38px; height: 38px; border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-weight: 700; font-size: .8rem; color: #fff; background: var(--review-forest); flex-shrink: 0;
  }
  .review-row.attn .review-avatar{ background: var(--review-clay); }
  .review-row.hidden-row .review-avatar{ background: var(--review-ink-soft); }
  .review-name{ font-weight: 700; font-size: .92rem; color: var(--review-ink); }
  .review-date{ font-size: .78rem; color: var(--review-ink-soft); }
  .review-room{ font-size: .8rem; color: var(--review-ink-soft); margin-top: 2px; }

  .review-body .stars-line{ color: var(--review-gold); letter-spacing: 3px; font-size: .85rem; margin-bottom: .4rem; }
  .review-row.hidden-row .stars-line{ color: #C9CFC5; }
  .review-quote{ font-size: .92rem; line-height: 1.55; color: var(--review-ink); border-left: 2px solid var(--review-sage); padding-left: .75rem; }
  .review-quote.muted-quote{ color: var(--review-ink-soft); font-style: italic; border-left-color: var(--review-border); }
  .review-quote .quote-text.is-clamped{
    display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;
  }
  .expand-toggle{ background: none; border: none; padding: 0; margin-top: .3rem; font-size: .8rem; font-weight: 600; color: var(--review-forest); }
  .expand-toggle:hover{ text-decoration: underline; }

  /* Phản hồi của chủ trọ */
  .owner-reply{ margin-top: .8rem; margin-left: .75rem; padding: .7rem .9rem; background: var(--review-sage); border-radius: 8px; display: flex; gap: .6rem; }
  .owner-reply .review-avatar{ width: 28px; height: 28px; font-size: .68rem; }
  .owner-reply-body{ font-size: .85rem; }
  .owner-reply-label{ font-weight: 700; color: var(--review-forest); font-size: .8rem; }
  .owner-reply-text{ color: var(--review-ink); margin-top: 2px; }

  .reply-form{ margin-top: .75rem; margin-left: .75rem; display: none; }
  .reply-form.is-open{ display: block; }
  .reply-form textarea{ width: 100%; border: 1px solid var(--review-border); border-radius: 8px; padding: .55rem .7rem; font-size: .85rem; font-family: inherit; resize: vertical; min-height: 64px; }
  .reply-form textarea:focus{ border-color: var(--review-forest); outline: none; box-shadow: 0 0 0 3px rgba(30,75,54,.1); }
  .reply-form-actions{ display: flex; gap: .5rem; margin-top: .5rem; }

  .review-side{ text-align: right; display: flex; flex-direction: column; align-items: flex-end; gap: .55rem; }
  .status-pill{ font-size: .78rem; font-weight: 600; padding: 4px 10px; border-radius: 20px; }
  .status-pill.showing{ background: var(--review-sage); color: var(--review-forest); }
  .status-pill.attn-pill{ background: var(--review-clay-soft); color: var(--review-clay); }
  .status-pill.hidden-pill{ background: #EEF0EB; color: var(--review-ink-soft); }

  .row-actions{ display: flex; flex-direction: column; gap: .4rem; align-items: flex-end; }
  .btn-row-action{ border: 1px solid var(--review-border); background: #fff; color: var(--review-ink-soft); font-size: .82rem; font-weight: 600; padding: 5px 13px; border-radius: 7px; transition: all .15s ease; white-space: nowrap; }
  .btn-row-action:hover{ background: var(--review-forest); border-color: var(--review-forest); color: #fff; }
  .btn-row-action.primary-action{ background: var(--review-forest); border-color: var(--review-forest); color: #fff; }
  .btn-row-action.primary-action:hover{ background: var(--review-forest-d); }

  /* ---------- Trạng thái rỗng ---------- */
  .empty-state{ display: none; text-align: center; padding: 3rem 1rem; color: var(--review-ink-soft); }
  .empty-state.is-visible{ display: block; }
  .empty-state .empty-icon{ font-size: 1.8rem; margin-bottom: .5rem; }
  .empty-state .empty-title{ font-weight: 700; color: var(--review-ink); font-size: .95rem; margin-bottom: .25rem; }
  .empty-state button{ margin-top: .75rem; }

  /* ---------- Phân trang ---------- */
  .review-pagination{ display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem; padding-top: 1.1rem; border-top: 1px solid var(--review-border); }
  .review-pagination .small{ color: var(--review-ink-soft); }
  .pagination .page-link{ border-color: var(--review-border); color: var(--review-ink-soft); }
  .pagination .page-item.active .page-link{ background: var(--review-forest); border-color: var(--review-forest); }

  /* ---------- Toast ---------- */
  .toast-stack{ position: fixed; bottom: 1.25rem; right: 1.25rem; z-index: 1080; display: flex; flex-direction: column; gap: .5rem; }
  .review-toast{
    background: var(--review-ink); color: #fff; padding: .65rem 1rem; border-radius: 8px; font-size: .85rem;
    display: flex; align-items: center; gap: .9rem; box-shadow: 0 8px 20px rgba(0,0,0,.18);
    opacity: 0; transform: translateY(8px); transition: opacity .18s ease, transform .18s ease;
  }
  .review-toast.is-visible{ opacity: 1; transform: translateY(0); }
  .review-toast button{ background: none; border: none; color: var(--review-gold); font-weight: 700; font-size: .85rem; padding: 0; white-space: nowrap; }

  @media (max-width: 900px){
    .overview-panel{ grid-template-columns: 1fr; }
    .overview-divider{ display: none; }
    .review-row{ grid-template-columns: 1fr; }
    .review-side{ align-items: flex-start; flex-direction: row; justify-content: space-between; width: 100%; }
    .row-actions{ align-items: flex-start; }
  }
</style>
</head>
<body>

<!-- NAVBAR NGANG ĐỒNG BỘ (dùng chung toàn hệ thống, không chỉnh sửa) -->
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
          <a class="app-nav-link" href="{{ url('/landlord') }}">Tổng quan</a>
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
              <a class="dropdown-item active" href="{{ route('owner.reviews.index') }}">⭐ Đánh giá</a>
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
<div class="page-wrap mt-4 mb-5">
  <div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="page-title mb-1">Quản lý Đánh giá</h2>
      <div class="page-desc">Theo dõi phản hồi và chất lượng dịch vụ từ người thuê.</div>
    </div>
  </div>

  <!-- TỔNG QUAN — bấm vào một mức sao để lọc nhanh -->
  <div class="overview-panel">
    <div class="overview-score">
      <div class="num">4.8<small>/ 5</small></div>
      <div class="stars">★ ★ ★ ★ ★</div>
      <div class="total">128 lượt đánh giá · 4 cần chú ý</div>
    </div>
    <div class="overview-divider"></div>
    <div class="rating-bars" id="ratingBars">
      <button type="button" class="rating-bar-row" data-star="5">
        <span class="label">5 sao</span>
        <span class="rating-bar-track"><span class="rating-bar-fill" style="width: 82%"></span></span>
        <span class="count">105</span>
      </button>
      <button type="button" class="rating-bar-row" data-star="4">
        <span class="label">4 sao</span>
        <span class="rating-bar-track"><span class="rating-bar-fill" style="width: 12%"></span></span>
        <span class="count">15</span>
      </button>
      <button type="button" class="rating-bar-row attn" data-star="3">
        <span class="label">3 sao</span>
        <span class="rating-bar-track"><span class="rating-bar-fill" style="width: 3%"></span></span>
        <span class="count">4</span>
      </button>
      <button type="button" class="rating-bar-row attn" data-star="2">
        <span class="label">2 sao</span>
        <span class="rating-bar-track"><span class="rating-bar-fill" style="width: 1%"></span></span>
        <span class="count">2</span>
      </button>
      <button type="button" class="rating-bar-row attn" data-star="1">
        <span class="label">1 sao</span>
        <span class="rating-bar-track"><span class="rating-bar-fill" style="width: 1%"></span></span>
        <span class="count">2</span>
      </button>
    </div>
  </div>

  <!-- BỘ LỌC + SẮP XẾP -->
  <div class="filter-row d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="fw-bold" style="font-size:.95rem;">Danh sách phản hồi</span>
      <span class="filter-count" id="filterCount">— hiển thị <strong>3</strong>/3</span>
      <span id="activeFilterChip"></span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <select class="form-select form-select-sm shadow-none" id="starFilter" style="width: 150px;" aria-label="Lọc theo số sao">
        <option value="">Tất cả số sao</option>
        <option value="5">5 sao</option>
        <option value="4">4 sao</option>
        <option value="3">3 sao</option>
        <option value="2">2 sao</option>
        <option value="1">1 sao</option>
      </select>
      <select class="form-select form-select-sm shadow-none" id="sortOrder" style="width: 165px;" aria-label="Sắp xếp">
        <option value="newest">Mới nhất</option>
        <option value="high">Sao cao đến thấp</option>
        <option value="low">Sao thấp đến cao</option>
      </select>
      <div class="input-group input-group-sm" style="width: 230px;">
        <span class="input-group-text bg-white border-end-0 text-muted">🔎</span>
        <input type="text" class="form-control border-start-0 shadow-none" id="searchInput" placeholder="Tìm theo tên phòng, người thuê...">
      </div>
    </div>
  </div>

  <!-- DANH SÁCH PHẢN HỒI -->
  <div class="review-feed" id="reviewFeed">

    <div class="review-row" data-rating="5" data-date="2026-09-12" data-name="Thanh Huyền" data-room="Phòng 12A Nhà trọ Q.7">
      <div class="review-who">
        <div class="review-avatar">TH</div>
        <div>
          <div class="review-name">Thanh Huyền</div>
          <div class="review-date">12/09/2026</div>
          <div class="review-room">Phòng 12A · Nhà trọ Q.7</div>
        </div>
      </div>
      <div class="review-body">
        <div class="stars-line">★ ★ ★ ★ ★</div>
        <div class="review-quote">
          <span class="quote-text">Phòng ốc rất sạch sẽ và thoáng mát. Chủ nhà thân thiện, hỗ trợ sửa vòi nước bị rỉ rất nhanh chóng. Mình rất hài lòng khi ở đây.</span>
        </div>
        <div class="reply-form" data-role="reply-form">
          <textarea placeholder="Viết phản hồi tới Thanh Huyền..."></textarea>
          <div class="reply-form-actions">
            <button type="button" class="btn-row-action primary-action" data-action="submit-reply">Gửi phản hồi</button>
            <button type="button" class="btn-row-action" data-action="cancel-reply">Hủy</button>
          </div>
        </div>
      </div>
      <div class="review-side">
        <span class="status-pill showing" data-role="status-pill">Đang hiển thị</span>
        <div class="row-actions">
          <button type="button" class="btn-row-action" data-action="toggle-reply">Trả lời</button>
          <button type="button" class="btn-row-action" data-action="toggle-visibility">Ẩn</button>
        </div>
      </div>
    </div>

    <div class="review-row attn" data-rating="3" data-date="2026-09-05" data-name="Hoàng Minh" data-room="Phòng 105 Chung cư mini Thủ Đức">
      <div class="review-who">
        <div class="review-avatar">HM</div>
        <div>
          <div class="review-name">Hoàng Minh</div>
          <div class="review-date">05/09/2026</div>
          <div class="review-room">Phòng 105 · Chung cư mini Thủ Đức</div>
        </div>
      </div>
      <div class="review-body">
        <div class="stars-line">★ ★ ★</div>
        <div class="review-quote">
          <span class="quote-text">Khu vực để xe hơi chật vào buổi tối, mạng wifi thỉnh thoảng hay bị rớt. Ngoài ra phòng khá rộng, ánh sáng tự nhiên tốt, giá thuê hợp lý so với khu vực. Phòng thì ổn nhưng cần cải thiện wifi và chỗ để xe ạ.</span>
        </div>
        <div class="reply-form" data-role="reply-form">
          <textarea placeholder="Viết phản hồi tới Hoàng Minh..."></textarea>
          <div class="reply-form-actions">
            <button type="button" class="btn-row-action primary-action" data-action="submit-reply">Gửi phản hồi</button>
            <button type="button" class="btn-row-action" data-action="cancel-reply">Hủy</button>
          </div>
        </div>
      </div>
      <div class="review-side">
        <span class="status-pill attn-pill" data-role="status-pill">Cần chú ý</span>
        <div class="row-actions">
          <button type="button" class="btn-row-action" data-action="toggle-reply">Trả lời</button>
          <button type="button" class="btn-row-action" data-action="toggle-visibility">Ẩn</button>
        </div>
      </div>
    </div>

    <div class="review-row hidden-row" data-rating="1" data-date="2026-08-28" data-name="Quốc Anh" data-room="Phòng 202 Nhà trọ Bình Thạnh" data-hidden="true">
      <div class="review-who">
        <div class="review-avatar">QA</div>
        <div>
          <div class="review-name">Quốc Anh</div>
          <div class="review-date">28/08/2026</div>
          <div class="review-room">Phòng 202 · Nhà trọ Bình Thạnh</div>
        </div>
      </div>
      <div class="review-body">
        <div class="stars-line">★</div>
        <div class="review-quote muted-quote">
          <span class="quote-text">(Đánh giá vi phạm chuẩn mực cộng đồng hoặc chứa từ ngữ không phù hợp)</span>
        </div>
      </div>
      <div class="review-side">
        <span class="status-pill hidden-pill" data-role="status-pill">Đã ẩn</span>
        <div class="row-actions">
          <button type="button" class="btn-row-action" data-action="toggle-visibility" data-hidden-label="Hiển thị lại" data-shown-label="Ẩn">Hiển thị lại</button>
        </div>
      </div>
    </div>

  </div>

  <!-- TRẠNG THÁI RỖNG -->
  <div class="empty-state" id="emptyState">
    <div class="empty-icon">🔎</div>
    <div class="empty-title">Không tìm thấy đánh giá phù hợp</div>
    <div>Thử đổi từ khóa tìm kiếm hoặc bỏ bớt bộ lọc số sao.</div>
    <button type="button" class="btn-row-action" id="clearFiltersBtn">Xóa bộ lọc</button>
  </div>

  <div class="review-pagination">
    <div class="small">Hiển thị 1 đến 3 của 128 đánh giá</div>
    <ul class="pagination pagination-sm mb-0">
      <li class="page-item disabled"><a class="page-link" href="#">Trước</a></li>
      <li class="page-item active"><a class="page-link" href="#">1</a></li>
      <li class="page-item"><a class="page-link" href="#">2</a></li>
      <li class="page-item"><a class="page-link" href="#">3</a></li>
      <li class="page-item"><a class="page-link" href="#">Sau</a></li>
    </ul>
  </div>
</div>

<div class="toast-stack" id="toastStack" aria-live="polite"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</script>
</body>
</html>