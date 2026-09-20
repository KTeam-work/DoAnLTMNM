<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Sửa dịch vụ</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- CSS dùng chung với trang Landlord --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        * { box-sizing: border-box; }
        body { margin: 0; font-family: 'Be Vietnam Pro', sans-serif; }

        /* PAGE */
        .service-edit-page { min-height: calc(100vh - 68px); padding: 32px 25px 60px; }
        .service-edit-container { width: 100%; max-width: 1050px; margin: 0 auto; }

        /* BACK */
        .service-back { display: inline-flex; align-items: center; gap: 7px; margin-bottom: 20px; color: #7a8580; text-decoration: none; font-size: 12px; font-weight: 500; transition: 0.2s ease; }
        .service-back:hover { color: #16834b; }

        /* HEADER */
        .service-header { margin-bottom: 23px; }
        .service-title { margin: 0; color: #26332d; font-size: 25px; font-weight: 800; }
        .service-description { margin-top: 6px; color: #7b8781; font-size: 13px; }

        /* PROPERTY */
        .service-property { display: flex; align-items: center; gap: 14px; padding: 16px 18px; margin-bottom: 18px; background: #ffffff; border: 1px solid #e5ebe7; border-radius: 13px; }
        .service-property-icon { width: 44px; height: 44px; flex-shrink: 0; display: flex; align-items: center; justify-content: center; background: #e7f5ec; border-radius: 11px; font-size: 20px; }
        .service-property-label { margin-bottom: 3px; color: #89948e; font-size: 10px; font-weight: 500; text-transform: uppercase; }
        .service-property-name { color: #26332d; font-size: 14px; font-weight: 700; }
        .service-property-address { margin-top: 3px; color: #8a958f; font-size: 11px; }

        /* EDIT CARD */
        .service-card { background: #ffffff; border: 1px solid #e5ebe7; border-radius: 14px; overflow: hidden; }
        .service-card-header { padding: 20px 23px; background: #ffffff; border-bottom: 1px solid #edf1ef; }
        .service-card-title { margin: 0; color: #26332d; font-size: 16px; font-weight: 700; }
        .service-card-description { margin-top: 4px; color: #8a958f; font-size: 11px; }
        .service-card-body { padding: 24px; background: #ffffff; }

        /* FORM */
        .service-form-group { margin-bottom: 20px; }
        .service-label { display: block; margin-bottom: 7px; color: #37443d; font-size: 12px; font-weight: 700; }
        .service-required { color: #dc3545; }
        .service-input, .service-select { width: 100%; height: 44px; padding: 0 12px; background: #ffffff; border: 1px solid #dce5e0; border-radius: 9px; color: #26332d; font-family: inherit; font-size: 12px; outline: none; transition: all 0.2s ease; }
        .service-input:focus, .service-select:focus { border-color: #16834b; box-shadow: 0 0 0 3px rgba(22, 131, 75, 0.08); }
        .service-input::placeholder { color: #a0aaa5; }
        textarea.service-input { height: 105px; padding-top: 11px; padding-bottom: 11px; resize: vertical; }
        .service-help { margin-top: 5px; color: #929c97; font-size: 10px; }

        /* PRICE */
        .service-price-wrapper { position: relative; }
        .service-price-wrapper .service-input { padding-right: 40px; }
        .service-price-unit { position: absolute; right: 13px; top: 50%; transform: translateY(-50%); color: #7f8a85; font-size: 11px; font-weight: 600; pointer-events: none; }

        /* STATUS */
        .service-status { display: flex; align-items: center; justify-content: space-between; padding: 14px 15px; background: #ffffff; border: 1px solid #e2e9e5; border-radius: 10px; }
        .service-status-content { display: flex; align-items: center; gap: 10px; }
        .service-status-dot { width: 9px; height: 9px; flex-shrink: 0; background: #16834b; border-radius: 50%; }
        .service-status-title { color: #37443d; font-size: 12px; font-weight: 700; }
        .service-status-description { margin-top: 2px; color: #8a958f; font-size: 10px; }

        /* SWITCH */
        .service-switch { position: relative; display: inline-block; width: 42px; height: 23px; flex-shrink: 0; }
        .service-switch input { opacity: 0; width: 0; height: 0; }
        .service-slider { position: absolute; inset: 0; cursor: pointer; background: #d8e0dc; border-radius: 20px; transition: 0.2s; }
        .service-slider::before { content: ""; position: absolute; width: 17px; height: 17px; left: 3px; top: 3px; background: #ffffff; border-radius: 50%; transition: 0.2s; }
        .service-switch input:checked + .service-slider { background: #16834b; }
        .service-switch input:checked + .service-slider::before { transform: translateX(19px); }

        /* NOTE */
        .service-note { margin-top: 22px; padding: 13px 15px; background: #f8fbf9; border: 1px solid #e5eee9; border-radius: 10px; color: #7e8984; font-size: 10px; line-height: 1.7; }
        .service-note strong { color: #56635c; }

        /* FOOTER */
        .service-card-footer { display: flex; align-items: center; justify-content: space-between; padding: 17px 23px; background: #f9fbfa; border-top: 1px solid #edf1ef; }

        /* BUTTONS */
        .service-btn-cancel { display: inline-flex; align-items: center; justify-content: center; min-width: 90px; height: 40px; padding: 0 16px; background: #ffffff; border: 1px solid #dce5e0; border-radius: 8px; color: #69756f; text-decoration: none; font-size: 12px; font-weight: 600; transition: 0.2s ease; }
        .service-btn-cancel:hover { background: #f4f7f5; color: #4f5b55; }
        .service-btn-save { min-width: 130px; height: 40px; padding: 0 18px; background: #16834b; border: none; border-radius: 8px; color: #ffffff; font-size: 12px; font-weight: 700; cursor: pointer; transition: 0.2s ease; }
        .service-btn-save:hover { background: #126b3e; }

        /* RESPONSIVE */
        @media (max-width: 1100px) { .service-edit-page { padding-left: 20px; padding-right: 20px; } }
        @media (max-width: 992px) { .service-edit-page { padding-top: 27px; } }
        @media (max-width: 768px) {
            .service-title { font-size: 22px; }
            .service-card-body { padding: 20px; }
            .service-card-footer { padding: 15px 20px; }
            .service-property { align-items: flex-start; }
            .service-btn-cancel, .service-btn-save { min-width: 110px; }
        }
        @media (max-width: 576px) {
            .service-edit-page { padding-left: 15px; padding-right: 15px; }
            .service-property-address { line-height: 1.5; }
            .service-card-footer { gap: 10px; }
            .service-btn-cancel, .service-btn-save { flex: 1; }
        }
    </style>
</head>

<body>

    {{-- =========================================================
         NAVBAR OWNER ĐÃ ĐỒNG BỘ
    ========================================================== --}}
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
              <a class="dropdown-item active" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a>
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

    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}
    <main class="service-edit-page">
        <div class="service-edit-container">

            {{-- BACK --}}
            <a href="{{ route('owner.services.index') }}" class="service-back">
                ← Quay lại danh sách dịch vụ
            </a>

            {{-- HEADER --}}
            <div class="service-header">
                <h1 class="service-title">Sửa dịch vụ</h1>
                <div class="service-description">Cập nhật thông tin dịch vụ đang áp dụng cho nhà trọ.</div>
            </div>

            {{-- PROPERTY --}}
            <div class="service-property">
                <div class="service-property-icon">🏠</div>
                <div>
                    <div class="service-property-label">Dịch vụ thuộc nhà trọ</div>
                    <div class="service-property-name">Nhà trọ Q.7</div>
                    <div class="service-property-address">123 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh</div>
                </div>
            </div>

            {{-- EDIT CARD --}}
            <div class="service-card">
                
                {{-- CARD HEADER --}}
                <div class="service-card-header">
                    <h2 class="service-card-title">Thông tin dịch vụ</h2>
                    <div class="service-card-description">Chỉnh sửa thông tin và mức giá của dịch vụ.</div>
                </div>

                {{-- FORM --}}
                <form action="#" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- CARD BODY --}}
                    <div class="service-card-body">

                        {{-- TÊN DỊCH VỤ --}}
                        <div class="service-form-group">
                            <label class="service-label">Tên dịch vụ <span class="service-required">*</span></label>
                            <input type="text" name="name" class="service-input" value="Internet" placeholder="Nhập tên dịch vụ">
                            <div class="service-help">Ví dụ: Internet, Giữ xe, Vệ sinh, Rác...</div>
                        </div>

                        {{-- MÔ TẢ --}}
                        <div class="service-form-group">
                            <label class="service-label">Mô tả</label>
                            <textarea name="description" class="service-input" placeholder="Nhập mô tả dịch vụ">Internet và Wifi dùng chung cho cư dân trong nhà trọ</textarea>
                            <div class="service-help">Mô tả ngắn gọn về dịch vụ.</div>
                        </div>

                        {{-- UNIT + PRICE --}}
                        <div class="row g-3">
                            {{-- ĐƠN VỊ --}}
                            <div class="col-md-6">
                                <div class="service-form-group">
                                    <label class="service-label">Đơn vị tính <span class="service-required">*</span></label>
                                    <select name="unit" class="service-select">
                                        <option value="tháng" selected>tháng</option>
                                        <option value="người">người</option>
                                        <option value="chiếc">chiếc</option>
                                        <option value="kWh">kWh</option>
                                        <option value="m³">m³</option>
                                    </select>
                                    <div class="service-help">Đơn vị dùng để tính phí dịch vụ.</div>
                                </div>
                            </div>

                            {{-- ĐƠN GIÁ --}}
                            <div class="col-md-6">
                                <div class="service-form-group">
                                    <label class="service-label">Đơn giá áp dụng <span class="service-required">*</span></label>
                                    <div class="service-price-wrapper">
                                        <input type="number" name="price" class="service-input" value="100000" min="0" placeholder="Nhập đơn giá">
                                        <span class="service-price-unit">₫</span>
                                    </div>
                                    <div class="service-help">Đơn giá hiện đang áp dụng cho nhà trọ này.</div>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="service-form-group">
                            <label class="service-label">Trạng thái</label>
                            <div class="service-status">
                                <div class="service-status-content">
                                    <div class="service-status-dot"></div>
                                    <div>
                                        <div class="service-status-title">Đang hoạt động</div>
                                        <div class="service-status-description">Dịch vụ hiện đang được áp dụng cho nhà trọ.</div>
                                    </div>
                                </div>

                               
                                <label class="service-switch">
                                    <input type="checkbox" name="status" value="active" checked>
                                    <span class="service-slider"></span>
                                </label>
                            </div>
                        </div>

                       
                        <div class="service-note">
                            <strong>Lưu ý:</strong>
                            Khi thay đổi đơn giá, mức giá mới sẽ được áp dụng cho các khoản phí phát sinh sau thời điểm cập nhật. Các khoản phí hoặc hóa đơn đã ghi nhận trước đó không nên tự động thay đổi.
                        </div>
                    </div>

                  
                    <div class="service-card-footer">
                        <a href="{{ route('owner.services.index') }}" class="service-btn-cancel">Hủy</a>
                        <button type="submit" class="service-btn-save">✓ &nbsp; Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </main>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>