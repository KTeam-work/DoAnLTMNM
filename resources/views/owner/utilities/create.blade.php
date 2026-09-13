<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm phòng mới - Trọ Ơi</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font: Be Vietnam Pro -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --green: #20584f;
            --green-dark: #17463e;
            --green-soft: #eaf3ef;
            --cream: #f8f5eb;
            --yellow: #f5c84b;
            --yellow-light: #fff7d7;
            --text: #213430;
            --muted: #78837e;
            --border: #e6dcc2;
            --white: #ffffff;
            --red: #c65b4a;
            --red-soft: #fbe9e6;
            --blue: #356d9e;
            --blue-soft: #e8f2ff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: var(--cream);
            color: var(--text);
        }

        /* =========================================
           NAVBAR (Ngang)
        ========================================= */
        .app-navbar {
            background: var(--green);
            min-height: 74px;
            box-shadow: 0 3px 14px rgba(20,55,47,.12);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 0;
        }
        .app-navbar .container-fluid {
            max-width: 1360px;
            padding: 0 28px;
        }
        .logo {
            color: #fff;
            text-decoration: none;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -1.2px;
            white-space: nowrap;
        }
        .logo span { color: var(--yellow); }

        .app-nav-link {
            color: rgba(255,255,255,.82) !important;
            font-size: 13.5px;
            font-weight: 600;
            padding: 9px 13px !important;
            border-radius: 10px;
            transition: .2s;
            white-space: nowrap;
            text-decoration: none;
        }
        .app-nav-link:hover, .app-nav-link.active {
            color: var(--green) !important;
            background: #fff;
        }

        /* Right-hand user zone */
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .notif-btn {
            position: relative;
            width: 40px;
            height: 40px;
            border-radius: 11px;
            border: 1px solid rgba(255,255,255,.18);
            background: rgba(255,255,255,.08);
            color: #fff;
            font-size: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notif-dot {
            position: absolute;
            top: 7px;
            right: 7px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--yellow);
            border: 2px solid var(--green);
        }
        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 6px 12px 6px 6px;
            border-radius: 12px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.16);
            color: #fff;
            text-decoration: none;
            margin-left: 6px;
        }
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: var(--yellow);
            color: var(--green-dark);
            font-weight: 800;
            font-size: 13px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
        }
        .user-meta { line-height: 1.2; }
        .user-name { font-size: 12.5px; font-weight: 700; color: #fff; }
        .user-role { font-size: 10px; color: rgba(255,255,255,.65); }
        .caret { font-size: 9px; color: rgba(255,255,255,.6); margin-left: 2px; }

        @media(max-width:991px){
            .app-nav-link { margin: 2px 0; }
            .navbar-toggler { filter: invert(1); border: none; }
        }

        /* =========================================
           PAGE HEADER & BUTTONS
        ========================================= */
        .page-wrap { max-width: 1360px; margin: 0 auto; padding: 30px 28px 70px; }
        
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 26px;
        }
        .page-title {
            color: var(--green-dark);
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -.6px;
            margin: 0;
        }
        .page-desc { color: var(--muted); font-size: 13px; margin-top: 6px; }
        .page-desc span { color: var(--text); font-weight: 600; }
        
        .btn-brand {
            border: 0;
            background: var(--yellow);
            color: var(--green-dark);
            font-weight: 800;
            font-size: 13px;
            border-radius: 11px;
            padding: 11px 18px;
            white-space: nowrap;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-brand:hover { background: #ffd968; color: var(--green-dark); }
        
        .btn-outline-brand {
            border: 1px solid var(--border);
            background: #fff;
            color: var(--green);
            font-weight: 700;
            font-size: 13px;
            border-radius: 11px;
            padding: 10px 16px;
            transition: 0.2s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-outline-brand:hover { background: var(--green-soft); color: var(--green); }

        /* =========================
           FORM LAYOUT & CARDS
        ========================= */
        .form-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 22px;
            align-items: start;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 22px;
            box-shadow: 0 4px 12px rgba(20,55,47,.03);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15.5px;
            font-weight: 800;
            color: var(--green-dark);
            margin-bottom: 18px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--border);
        }

        .row-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .row-3 {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            gap: 15px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 12.5px;
            color: var(--muted);
            font-weight: 700;
            margin-bottom: 7px;
        }

        .form-group label span {
            color: var(--red);
        }

        .custom-input {
            width: 100%;
            height: 44px;
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 0 14px;
            outline: none;
            background: var(--white);
            color: var(--text);
            font-family: inherit;
            font-size: 13.5px;
            font-weight: 600;
            transition: .2s;
        }

        textarea.custom-input {
            height: 95px;
            padding: 12px 14px;
            resize: vertical;
        }

        .custom-input:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px var(--green-soft);
        }

        /* =========================
           AMENITIES SELECTION
        ========================= */
        .amenities-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 9px;
            background: var(--white);
            border: 1px solid var(--border);
            padding: 10px 14px;
            border-radius: 11px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            color: var(--text);
            transition: .2s;
        }

        .amenity-item:hover {
            border-color: var(--green);
            background: var(--green-soft);
            color: var(--green-dark);
        }

        .amenity-item input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: var(--green);
            cursor: pointer;
        }

        /* =========================
           IMAGE UPLOAD
        ========================= */
        .upload-dropzone {
            border: 2px dashed var(--border);
            border-radius: 14px;
            padding: 26px 16px;
            text-align: center;
            background: var(--white);
            cursor: pointer;
            transition: .2s;
        }

        .upload-dropzone:hover {
            border-color: var(--green);
            background: var(--green-soft);
        }

        .upload-icon {
            font-size: 32px;
            margin-bottom: 8px;
            color: var(--green);
        }

        .upload-text {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.5;
            font-weight: 500;
        }

        .upload-text strong {
            color: var(--green-dark);
            font-weight: 700;
        }

        .preview-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 14px;
        }

        .preview-item {
            height: 70px;
            border-radius: 9px;
            background: var(--green-soft);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: var(--green);
            overflow: hidden;
            position: relative;
        }

        /* RESPONSIVE */
        @media (max-width: 1000px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .row-3 {
                grid-template-columns: 1fr;
            }
            .amenities-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR NGANG — OWNER -->
<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid">
        <a class="logo" href="#">Trọ <span>Ơi</span><small>CHỦ TRỌ</small></a>

        <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#mainMenu">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainMenu">
            <ul class="navbar-nav mx-auto align-items-lg-center">
                <li class="nav-item"><a class="app-nav-link" href="#">Tổng quan</a></li>
                <li class="nav-item"><a class="app-nav-link active" href="#">Nhà &amp; Phòng</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Tin đăng</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Người thuê</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Dịch vụ</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Điện nước</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Hóa đơn</a></li>
                <li class="nav-item"><a class="app-nav-link" href="#">Giao dịch</a></li>
            </ul>

            <div class="navbar-actions mt-3 mt-lg-0">
                <button class="notif-btn">🔔<span class="notif-dot"></span></button>
                <a href="#" class="user-chip">
                    <span class="user-avatar">MT</span>
                    <span class="user-meta d-none d-md-block">
                        <span class="user-name d-block">Minh Tuấn</span>
                        <span class="user-role">Chủ trọ</span>
                    </span>
                    <span class="caret d-none d-md-block">▾</span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- MAIN CONTENT -->
<div class="page-wrap">

    <!-- HEADER -->
    <div class="page-header">
        <div>
            <h2 class="page-title">Thêm phòng trọ mới</h2>
            <div class="page-desc">Chủ nhà <span> / Quản lý phòng / Thêm mới</span></div>
        </div>

        <div class="header-actions">
            <a href="#" class="btn-outline-brand" onclick="history.back()">
                ← Quay lại
            </a>
            <button type="submit" form="addRoomForm" class="btn-brand">
                💾 Lưu phòng trọ
            </button>
        </div>
    </div>

    <!-- FORM START -->
    <form id="addRoomForm">
        <div class="form-grid">

            <!-- CỘT TRÁI: THÔNG TIN CĂN PHÒNG & TIỆN ÍCH -->
            <div class="col-left">

                <!-- THÔNG TIN CƠ BẢN -->
                <div class="card">
                    <div class="card-header">
                        <span>📌</span> Thông tin chi tiết phòng
                    </div>

                    <div class="form-group">
                        <label>Thuộc tòa nhà / Dãy trọ <span>*</span></label>
                        <select class="custom-input" required>
                            <option value="">-- Chọn bất động sản quản lý --</option>
                            <option value="1">Nhà trọ Nguyễn Văn Cừ (Quận 5)</option>
                            <option value="2">Nhà trọ Quận 7</option>
                            <option value="3">Nhà trọ Bình Thạnh</option>
                        </select>
                    </div>

                    <div class="row-3">
                        <div class="form-group">
                            <label>Tên / Số phòng <span>*</span></label>
                            <input type="text" class="custom-input" placeholder="Vd: Phòng 201" required>
                        </div>
                        <div class="form-group">
                            <label>Tầng lầu</label>
                            <input type="number" class="custom-input" value="1" min="1">
                        </div>
                        <div class="form-group">
                            <label>Loại phòng</label>
                            <select class="custom-input">
                                <option>Tiêu chuẩn</option>
                                <option>Có gác lửng</option>
                                <option>Studio khép kín</option>
                                <option>Duplex</option>
                            </select>
                        </div>
                    </div>

                    <div class="row-2">
                        <div class="form-group">
                            <label>Diện tích (m²) <span>*</span></label>
                            <input type="number" class="custom-input" step="0.1" placeholder="Vd: 25" required>
                        </div>
                        <div class="form-group">
                            <label>Sức chứa tối đa (người)</label>
                            <input type="number" class="custom-input" value="2" min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mô tả bổ sung</label>
                        <textarea class="custom-input" placeholder="Cửa sổ thoáng mát, giờ giấc tự do, có chỗ nấu ăn..."></textarea>
                    </div>
                </div>

                <!-- TIỆN ÍCH CĂN PHÒNG -->
                <div class="card">
                    <div class="card-header">
                        <span>✨</span> Tiện ích phòng trọ
                    </div>

                    <div class="amenities-container">
                        <label class="amenity-item">
                            <input type="checkbox"> ❄️ Máy lạnh
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 🧊 Tủ lạnh
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 🚿 Bình nước nóng
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 📶 Wifi riêng
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 🛏️ Giường nệm
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 🚪 Tủ quần áo
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 🧺 Máy giặt
                        </label>
                        <label class="amenity-item">
                            <input type="checkbox"> 🌿 Ban công riêng
                        </label>
                    </div>
                </div>

            </div>

            <!-- CỘT PHẢI: GIÁ & HÌNH ẢNH -->
            <div class="col-right">

                <!-- THIẾT LẬP GIÁ -->
                <div class="card">
                    <div class="card-header">
                        <span>💰</span> Chi phí & Trạng thái
                    </div>

                    <div class="form-group">
                        <label>Giá thuê tháng (VNĐ) <span>*</span></label>
                        <input type="number" class="custom-input" placeholder="Vd: 3500000" step="10000" required>
                    </div>

                    <div class="form-group">
                        <label>Tiền đặt cọc (VNĐ)</label>
                        <input type="number" class="custom-input" placeholder="Vd: 3500000" step="10000">
                    </div>

                    <div class="form-group">
                        <label>Trạng thái ban đầu</label>
                        <select class="custom-input">
                            <option value="available">🟢 Phòng trống (Sẵn sàng)</option>
                            <option value="maintenance">🟡 Đang sửa chữa / Bảo trì</option>
                            <option value="rented">🔴 Đã có người thuê</option>
                        </select>
                    </div>
                </div>

                <!-- HÌNH ẢNH PHÒNG -->
                <div class="card">
                    <div class="card-header">
                        <span>📸</span> Hình ảnh phòng
                    </div>

                    <div class="upload-dropzone" onclick="document.getElementById('fileInput').click()">
                        <div class="upload-icon">📷</div>
                        <div class="upload-text">
                            <strong>Nhấp tải ảnh</strong> hoặc kéo thả vào đây<br>
                            Hỗ trợ PNG, JPG, JPEG
                        </div>
                        <input type="file" id="fileInput" multiple accept="image/*" style="display: none;">
                    </div>

                    <div class="preview-list">
                        <div class="preview-item">Ảnh 1</div>
                        <div class="preview-item">Ảnh 2</div>
                        <div class="preview-item">Ảnh 3</div>
                    </div>
                </div>

            </div>

        </div>
    </form>
    <!-- FORM END -->

</div>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>