<?php

$dir = 'C:\\PHP 2\\DoAnLTMNM\\public\\images\\diagrams';

// -----------------------------------------------------------------------------
// SƠ ĐỒ 1: TỔNG QUAN VÒNG ĐỜI HỆ THỐNG TRỌ ƠI (END-TO-END WORKFLOW)
// -----------------------------------------------------------------------------
$svg1 = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 680" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0b132b"/>
      <stop offset="100%" stop-color="#1c2541"/>
    </linearGradient>
    <linearGradient id="cardGrad1" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#1e293b"/>
      <stop offset="100%" stop-color="#0f172a"/>
    </linearGradient>
    <filter id="glow1" x="-10%" y="-10%" width="120%" height="120%">
      <feDropShadow dx="0" dy="8" stdDeviation="6" flood-color="#000" flood-opacity="0.35"/>
    </filter>
    <marker id="arrow1" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
      <path d="M 0 0 L 8 4 L 0 8 Z" fill="#38bdf8"/>
    </marker>
    <marker id="arrowGreen" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
      <path d="M 0 0 L 8 4 L 0 8 Z" fill="#34d399"/>
    </marker>
  </defs>

  <!-- Background -->
  <rect width="1200" height="680" rx="16" fill="url(#bgGrad1)"/>

  <!-- Title Badge -->
  <rect x="330" y="25" width="540" height="48" rx="24" fill="#0284c7" opacity="0.2"/>
  <rect x="330" y="25" width="540" height="48" rx="24" fill="none" stroke="#38bdf8" stroke-width="1.5"/>
  <text x="600" y="55" fill="#f8fafc" font-size="20" font-weight="bold" font-family="'Segoe UI', Roboto, sans-serif" text-anchor="middle">
    SƠ ĐỒ TỔNG QUAN VÒNG ĐỜI QUẢN LÝ THUÊ TRỌ - TRỌ ƠI
  </text>

  <!-- Subtitle -->
  <text x="600" y="95" fill="#94a3b8" font-size="13" font-family="'Segoe UI', Roboto, sans-serif" text-anchor="middle">
    Quy trình số hóa khép kín 100% từ Tìm kiếm phòng đến Thanh lý hợp đồng & Đánh giá minh bạch
  </text>

  <!-- Giai đoạn 1: Khám phá & Đặt lịch -->
  <g transform="translate(60, 130)">
    <!-- Phase Card -->
    <rect width="320" height="480" rx="16" fill="url(#cardGrad1)" stroke="#3b82f6" stroke-width="1.5" filter="url(#glow1)"/>
    <rect x="0" y="0" width="320" height="44" rx="16" fill="#1e3a8a"/>
    <text x="160" y="28" fill="#60a5fa" font-size="15" font-weight="bold" font-family="'Segoe UI', sans-serif" text-anchor="middle">
      GIAI ĐOẠN 1: KHÁM PHÁ & ĐẶT LỊCH
    </text>

    <!-- Step 1.1 -->
    <rect x="20" y="65" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="97" r="14" fill="#3b82f6"/>
    <text x="45" y="102" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">1</text>
    <text x="70" y="90" fill="#f8fafc" font-size="13" font-weight="bold">Tìm kiếm & Bản đồ số</text>
    <text x="70" y="110" fill="#94a3b8" font-size="11">Lọc giá, vị trí, tiện ích, xem ảnh 360</text>

    <!-- Connector down -->
    <line x1="160" y1="130" x2="160" y2="155" stroke="#38bdf8" stroke-width="2" marker-end="url(#arrow1)"/>

    <!-- Step 1.2 -->
    <rect x="20" y="160" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="192" r="14" fill="#3b82f6"/>
    <text x="45" y="197" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">2</text>
    <text x="70" y="185" fill="#f8fafc" font-size="13" font-weight="bold">Chatbot AI Tư vấn 24/7</text>
    <text x="70" y="205" fill="#94a3b8" font-size="11">Gemini AI giải đáp nội quy, đề xuất phòng</text>

    <!-- Connector down -->
    <line x1="160" y1="225" x2="160" y2="250" stroke="#38bdf8" stroke-width="2" marker-end="url(#arrow1)"/>

    <!-- Step 1.3 -->
    <rect x="20" y="255" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="287" r="14" fill="#3b82f6"/>
    <text x="45" y="292" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">3</text>
    <text x="70" y="280" fill="#f8fafc" font-size="13" font-weight="bold">Đặt lịch xem phòng</text>
    <text x="70" y="300" fill="#94a3b8" font-size="11">Khách chọn ngày giờ trực tiếp trên web</text>

    <!-- Connector down -->
    <line x1="160" y1="320" x2="160" y2="345" stroke="#38bdf8" stroke-width="2" marker-end="url(#arrow1)"/>

    <!-- Step 1.4 -->
    <rect x="20" y="350" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="382" r="14" fill="#10b981"/>
    <text x="45" y="387" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">✓</text>
    <text x="70" y="375" fill="#f8fafc" font-size="13" font-weight="bold">Chủ trọ duyệt lịch hẹn</text>
    <text x="70" y="395" fill="#94a3b8" font-size="11">Bắn thông báo chuông & SMS/Email</text>
  </g>

  <!-- Big Arrow: Phase 1 -> Phase 2 -->
  <line x1="390" y1="370" x2="430" y2="370" stroke="#38bdf8" stroke-width="3" marker-end="url(#arrow1)"/>

  <!-- Giai đoạn 2: Hợp đồng & Cư trú -->
  <g transform="translate(440, 130)">
    <!-- Phase Card -->
    <rect width="320" height="480" rx="16" fill="url(#cardGrad1)" stroke="#8b5cf6" stroke-width="1.5" filter="url(#glow1)"/>
    <rect x="0" y="0" width="320" height="44" rx="16" fill="#4c1d95"/>
    <text x="160" y="28" fill="#c084fc" font-size="15" font-weight="bold" font-family="'Segoe UI', sans-serif" text-anchor="middle">
      GIAI ĐOẠN 2: HỢP ĐỒNG & CƯ TRÚ
    </text>

    <!-- Step 2.1 -->
    <rect x="20" y="65" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="97" r="14" fill="#8b5cf6"/>
    <text x="45" y="102" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">4</text>
    <text x="70" y="90" fill="#f8fafc" font-size="13" font-weight="bold">Ký Hợp đồng điện tử</text>
    <text x="70" y="110" fill="#94a3b8" font-size="11">Quy định cọc, giá, chuyển phòng sang 'rented'</text>

    <!-- Connector down -->
    <line x1="160" y1="130" x2="160" y2="155" stroke="#c084fc" stroke-width="2" marker-end="url(#arrow1)"/>

    <!-- Step 2.2 -->
    <rect x="20" y="160" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="192" r="14" fill="#8b5cf6"/>
    <text x="45" y="197" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">5</text>
    <text x="70" y="185" fill="#f8fafc" font-size="13" font-weight="bold">Khai báo thành viên ở cùng</text>
    <text x="70" y="205" fill="#94a3b8" font-size="11">Quản lý cư dân, tạm trú tạm vắng</text>

    <!-- Connector down -->
    <line x1="160" y1="225" x2="160" y2="250" stroke="#c084fc" stroke-width="2" marker-end="url(#arrow1)"/>

    <!-- Step 2.3 -->
    <rect x="20" y="255" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="287" r="14" fill="#ec4899"/>
    <text x="45" y="292" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">6</text>
    <text x="70" y="280" fill="#f8fafc" font-size="13" font-weight="bold">Ghi điện nước AI OCR</text>
    <text x="70" y="300" fill="#94a3b8" font-size="11">Chụp ảnh đồng hồ -> Gemini tự đọc số</text>

    <!-- Connector down -->
    <line x1="160" y1="320" x2="160" y2="345" stroke="#c084fc" stroke-width="2" marker-end="url(#arrow1)"/>

    <!-- Step 2.4 -->
    <rect x="20" y="350" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="382" r="14" fill="#f59e0b"/>
    <text x="45" y="387" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">7</text>
    <text x="70" y="375" fill="#f8fafc" font-size="13" font-weight="bold">Báo hỏng sửa chữa</text>
    <text x="70" y="395" fill="#94a3b8" font-size="11">Khách gửi ảnh sự cố -> Chủ trọ tiếp nhận</text>
  </g>

  <!-- Big Arrow: Phase 2 -> Phase 3 -->
  <line x1="770" y1="370" x2="810" y2="370" stroke="#c084fc" stroke-width="3" marker-end="url(#arrow1)"/>

  <!-- Giai đoạn 3: Thanh toán & Đánh giá -->
  <g transform="translate(820, 130)">
    <!-- Phase Card -->
    <rect width="320" height="480" rx="16" fill="url(#cardGrad1)" stroke="#10b981" stroke-width="1.5" filter="url(#glow1)"/>
    <rect x="0" y="0" width="320" height="44" rx="16" fill="#064e3b"/>
    <text x="160" y="28" fill="#34d399" font-size="15" font-weight="bold" font-family="'Segoe UI', sans-serif" text-anchor="middle">
      GIAI ĐOẠN 3: THANH TOÁN & ĐÁNH GIÁ
    </text>

    <!-- Step 3.1 -->
    <rect x="20" y="65" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="97" r="14" fill="#10b981"/>
    <text x="45" y="102" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">8</text>
    <text x="70" y="90" fill="#f8fafc" font-size="13" font-weight="bold">Xuất Hóa đơn định kỳ</text>
    <text x="70" y="110" fill="#94a3b8" font-size="11">Phòng + Điện nước + Rác/Wifi/Xe</text>

    <!-- Connector down -->
    <line x1="160" y1="130" x2="160" y2="155" stroke="#34d399" stroke-width="2" marker-end="url(#arrowGreen)"/>

    <!-- Step 3.2 -->
    <rect x="20" y="160" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="192" r="14" fill="#059669"/>
    <text x="45" y="197" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">9</text>
    <text x="70" y="185" fill="#f8fafc" font-size="13" font-weight="bold">SePay Quét QR gạch nợ</text>
    <text x="70" y="205" fill="#94a3b8" font-size="11">Khách quét VietQR -> Gạch nợ sau 3 giây</text>

    <!-- Connector down -->
    <line x1="160" y1="225" x2="160" y2="250" stroke="#34d399" stroke-width="2" marker-end="url(#arrowGreen)"/>

    <!-- Step 3.3 -->
    <rect x="20" y="255" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="287" r="14" fill="#10b981"/>
    <text x="45" y="292" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">10</text>
    <text x="70" y="280" fill="#f8fafc" font-size="13" font-weight="bold">Thanh lý Hợp đồng</text>
    <text x="70" y="300" fill="#94a3b8" font-size="11">Tất toán cọc, phòng đổi về 'available'</text>

    <!-- Connector down -->
    <line x1="160" y1="320" x2="160" y2="345" stroke="#34d399" stroke-width="2" marker-end="url(#arrowGreen)"/>

    <!-- Step 3.4 -->
    <rect x="20" y="350" width="280" height="65" rx="10" fill="#0f172a" stroke="#334155"/>
    <circle cx="45" cy="382" r="14" fill="#eab308"/>
    <text x="45" y="387" fill="#fff" font-size="12" font-weight="bold" text-anchor="middle">★</text>
    <text x="70" y="375" fill="#f8fafc" font-size="13" font-weight="bold">Đánh giá phòng công khai</text>
    <text x="70" y="395" fill="#94a3b8" font-size="11">Huy hiệu 'Đã từng thuê' cho khách sau xem</text>
  </g>

  <!-- Bottom Legend -->
  <rect x="60" y="630" width="1080" height="32" rx="8" fill="#1e293b"/>
  <text x="600" y="651" fill="#cbd5e1" font-size="12" font-family="'Segoe UI', sans-serif" text-anchor="middle">
    💡 Hệ sinh thái Trọ Ơi: Tích hợp AI Gemini • Cổng thanh toán VietQR SePay • MySQL 3NF • Laravel 12 MVC • Thông báo thời gian thực
  </text>
</svg>
SVG;

file_put_contents("$dir/luong_1_tong_quan_vong_doi.svg", $svg1);


// -----------------------------------------------------------------------------
// SƠ ĐỒ 2: LUỒNG THANH TOÁN TỰ ĐỘNG SEPAY VIETQR WEBHOOK
// -----------------------------------------------------------------------------
$svg2 = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1100 620" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad2" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f172a"/>
      <stop offset="100%" stop-color="#1e1b4b"/>
    </linearGradient>
    <filter id="shadow2" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="6" stdDeviation="5" flood-color="#000" flood-opacity="0.4"/>
    </filter>
    <marker id="arrBlue" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
      <path d="M 0 0 L 8 4 L 0 8 Z" fill="#38bdf8"/>
    </marker>
    <marker id="arrGreen" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
      <path d="M 0 0 L 8 4 L 0 8 Z" fill="#34d399"/>
    </marker>
  </defs>

  <!-- BG -->
  <rect width="1100" height="620" rx="16" fill="url(#bgGrad2)"/>

  <!-- Title Badge -->
  <rect x="250" y="25" width="600" height="48" rx="24" fill="#047857" opacity="0.25"/>
  <rect x="250" y="25" width="600" height="48" rx="24" fill="none" stroke="#10b981" stroke-width="1.5"/>
  <text x="550" y="55" fill="#f8fafc" font-size="20" font-weight="bold" font-family="'Segoe UI', Roboto, sans-serif" text-anchor="middle">
    LUỒNG THANH TOÁN TỰ ĐỘNG SEPAY (VIETQR WEBHOOK 3 GIÂY)
  </text>
  <text x="550" y="92" fill="#94a3b8" font-size="13" font-family="'Segoe UI', sans-serif" text-anchor="middle">
    Khách quét mã VietQR động -> Ngân hàng báo biến động số dư -> Webhook tự động gạch nợ tức thì
  </text>

  <!-- Step 1: Lập hóa đơn -->
  <g transform="translate(60, 130)" filter="url(#shadow2)">
    <rect width="210" height="200" rx="14" fill="#1e293b" stroke="#3b82f6" stroke-width="1.5"/>
    <circle cx="105" cy="40" r="22" fill="#2563eb"/>
    <text x="105" y="47" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">1</text>
    <text x="105" y="85" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Xuất Hóa Đơn</text>
    <text x="105" y="112" fill="#94a3b8" font-size="11" text-anchor="middle">Hệ thống tính tổng tiền</text>
    <text x="105" y="130" fill="#94a3b8" font-size="11" text-anchor="middle">Phòng + Điện + Nước</text>
    <rect x="25" y="148" width="160" height="32" rx="6" fill="#0f172a"/>
    <text x="105" y="169" fill="#38bdf8" font-size="11" font-weight="bold" text-anchor="middle">Mã: TROOI10293</text>
  </g>

  <!-- Arrow 1 -> 2 -->
  <line x1="280" y1="230" x2="315" y2="230" stroke="#38bdf8" stroke-width="3" marker-end="url(#arrBlue)"/>

  <!-- Step 2: Sinh QR VietQR -->
  <g transform="translate(325, 130)" filter="url(#shadow2)">
    <rect width="210" height="200" rx="14" fill="#1e293b" stroke="#8b5cf6" stroke-width="1.5"/>
    <circle cx="105" cy="40" r="22" fill="#7c3aed"/>
    <text x="105" y="47" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">2</text>
    <text x="105" y="85" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Sinh VietQR Động</text>
    <text x="105" y="112" fill="#94a3b8" font-size="11" text-anchor="middle">Mã QR chứa sẵn STK,</text>
    <text x="105" y="130" fill="#94a3b8" font-size="11" text-anchor="middle">Số tiền & Nội dung CK</text>
    <rect x="25" y="148" width="160" height="32" rx="6" fill="#0f172a"/>
    <text x="105" y="169" fill="#c084fc" font-size="11" font-weight="bold" text-anchor="middle">Chuẩn NAPAS 247</text>
  </g>

  <!-- Arrow 2 -> 3 -->
  <line x1="545" y1="230" x2="580" y2="230" stroke="#c084fc" stroke-width="3" marker-end="url(#arrBlue)"/>

  <!-- Step 3: Quét mã Ngân hàng -->
  <g transform="translate(590, 130)" filter="url(#shadow2)">
    <rect width="210" height="200" rx="14" fill="#1e293b" stroke="#f59e0b" stroke-width="1.5"/>
    <circle cx="105" cy="40" r="22" fill="#d97706"/>
    <text x="105" y="47" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">3</text>
    <text x="105" y="85" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Khách Quét Mã QR</text>
    <text x="105" y="112" fill="#94a3b8" font-size="11" text-anchor="middle">App VCB, MB, Momo...</text>
    <text x="105" y="130" fill="#94a3b8" font-size="11" text-anchor="middle">Tự điền 100% dữ liệu</text>
    <rect x="25" y="148" width="160" height="32" rx="6" fill="#0f172a"/>
    <text x="105" y="169" fill="#fbbf24" font-size="11" font-weight="bold" text-anchor="middle">Khách bấm Xác nhận</text>
  </g>

  <!-- Arrow 3 -> 4 -->
  <line x1="810" y1="230" x2="845" y2="230" stroke="#f59e0b" stroke-width="3" marker-end="url(#arrBlue)"/>

  <!-- Step 4: SePay Bắt Biến động -->
  <g transform="translate(855, 130)" filter="url(#shadow2)">
    <rect width="190" height="200" rx="14" fill="#1e293b" stroke="#10b981" stroke-width="1.5"/>
    <circle cx="95" cy="40" r="22" fill="#059669"/>
    <text x="95" y="47" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">4</text>
    <text x="95" y="85" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">SePay Webhook</text>
    <text x="95" y="112" fill="#94a3b8" font-size="11" text-anchor="middle">Ngân hàng báo tiền vào</text>
    <text x="95" y="130" fill="#94a3b8" font-size="11" text-anchor="middle">SePay gửi POST Request</text>
    <rect x="20" y="148" width="150" height="32" rx="6" fill="#0f172a"/>
    <text x="95" y="169" fill="#34d399" font-size="11" font-weight="bold" text-anchor="middle">/api/sepay/webhook</text>
  </g>

  <!-- Big Curve Arrow Down: Step 4 -> Step 5 Backend -->
  <path d="M 950 340 L 950 400 L 700 400" fill="none" stroke="#10b981" stroke-width="3" marker-end="url(#arrGreen)"/>

  <!-- Step 5: Backend Trọ Ơi Xử Lý & Gạch Nợ -->
  <g transform="translate(100, 390)" filter="url(#shadow2)">
    <rect width="570" height="180" rx="14" fill="#064e3b" stroke="#10b981" stroke-width="2"/>
    <circle cx="50" cy="50" r="22" fill="#10b981"/>
    <text x="50" y="56" fill="#fff" font-size="18" font-weight="bold" text-anchor="middle">5</text>
    <text x="90" y="45" fill="#f8fafc" font-size="16" font-weight="bold">MÁY CHỦ TRỌ ƠI: ĐỐI SOÁT & GẠCH NỢ TỰ ĐỘNG</text>
    <text x="90" y="68" fill="#a7f3d0" font-size="12">Thời gian xử lý: ~2 đến 3 giây sau khi khách chuyển tiền</text>

    <!-- Sub-boxes -->
    <rect x="30" y="90" width="160" height="65" rx="8" fill="#022c22" stroke="#059669"/>
    <text x="110" y="115" fill="#34d399" font-size="12" font-weight="bold" text-anchor="middle">Xác thực Token</text>
    <text x="110" y="135" fill="#cbd5e1" font-size="10" text-anchor="middle">Bảo mật API Secret</text>

    <rect x="205" y="90" width="160" height="65" rx="8" fill="#022c22" stroke="#059669"/>
    <text x="285" y="115" fill="#34d399" font-size="12" font-weight="bold" text-anchor="middle">Chống Lặp Idempotent</text>
    <text x="285" y="135" fill="#cbd5e1" font-size="10" text-anchor="middle">Chặn trùng Transaction ID</text>

    <rect x="380" y="90" width="160" height="65" rx="8" fill="#022c22" stroke="#059669"/>
    <text x="460" y="115" fill="#34d399" font-size="12" font-weight="bold" text-anchor="middle">Đổi Trạng Thái 'PAID'</text>
    <text x="460" y="135" fill="#cbd5e1" font-size="10" text-anchor="middle">Bắn chuông thông báo 2 bên</text>
  </g>

  <!-- Result Banner Right -->
  <g transform="translate(710, 420)" filter="url(#shadow2)">
    <rect width="335" height="120" rx="12" fill="#1e293b" stroke="#38bdf8" stroke-width="1.5"/>
    <text x="167" y="38" fill="#38bdf8" font-size="14" font-weight="bold" text-anchor="middle">⚡ HIỆU QUẢ VƯỢT TRỘI</text>
    <text x="30" y="68" fill="#f8fafc" font-size="12">• Chủ trọ không cần canh mở app ngân hàng</text>
    <text x="30" y="90" fill="#f8fafc" font-size="12">• Khách thuê nhận biên nhận đã đóng tiền ngay</text>
    <text x="30" y="112" fill="#f8fafc" font-size="12">• Triệt tiêu 100% gian lận & sai sót đối soát</text>
  </g>
</svg>
SVG;

file_put_contents("$dir/luong_2_thanh_toan_sepay.svg", $svg2);


// -----------------------------------------------------------------------------
// SƠ ĐỒ 3: LUỒNG GHI ĐIỆN NƯỚC BẰNG AI OCR (GEMINI VISION)
// -----------------------------------------------------------------------------
$svg3 = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1100 580" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad3" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f172a"/>
      <stop offset="100%" stop-color="#1e293b"/>
    </linearGradient>
    <filter id="shadow3" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="6" stdDeviation="5" flood-color="#000" flood-opacity="0.35"/>
    </filter>
    <marker id="arrPurple" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
      <path d="M 0 0 L 8 4 L 0 8 Z" fill="#a855f7"/>
    </marker>
  </defs>

  <rect width="1100" height="580" rx="16" fill="url(#bgGrad3)"/>

  <!-- Title Badge -->
  <rect x="250" y="25" width="600" height="48" rx="24" fill="#9333ea" opacity="0.2"/>
  <rect x="250" y="25" width="600" height="48" rx="24" fill="none" stroke="#a855f7" stroke-width="1.5"/>
  <text x="550" y="55" fill="#f8fafc" font-size="20" font-weight="bold" font-family="'Segoe UI', Roboto, sans-serif" text-anchor="middle">
    QUY TRÌNH GHI SỐ ĐIỆN - NƯỚC THÔNG MINH BẰNG AI OCR
  </text>
  <text x="550" y="92" fill="#94a3b8" font-size="13" font-family="'Segoe UI', sans-serif" text-anchor="middle">
    Chụp ảnh công tơ -> Google Gemini Vision bóc tách số -> Tự động tính sản lượng & thành tiền
  </text>

  <!-- Step 1 -->
  <g transform="translate(60, 130)" filter="url(#shadow3)">
    <rect width="210" height="260" rx="14" fill="#0f172a" stroke="#3b82f6" stroke-width="1.5"/>
    <circle cx="105" cy="45" r="22" fill="#2563eb"/>
    <text x="105" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">1</text>
    <text x="105" y="95" fill="#f8fafc" font-size="15" font-weight="bold" text-anchor="middle">Chụp Ảnh Công Tơ</text>
    <text x="105" y="125" fill="#94a3b8" font-size="12" text-anchor="middle">Chủ trọ dùng điện thoại</text>
    <text x="105" y="145" fill="#94a3b8" font-size="12" text-anchor="middle">chụp đồng hồ điện/nước</text>
    <rect x="30" y="180" width="150" height="50" rx="8" fill="#1e293b"/>
    <text x="105" y="210" fill="#38bdf8" font-size="12" font-weight="bold" text-anchor="middle">📷 Upload ảnh chụp</text>
  </g>

  <!-- Arrow 1 -> 2 -->
  <line x1="280" y1="260" x2="315" y2="260" stroke="#a855f7" stroke-width="3" marker-end="url(#arrPurple)"/>

  <!-- Step 2 -->
  <g transform="translate(325, 130)" filter="url(#shadow3)">
    <rect width="210" height="260" rx="14" fill="#0f172a" stroke="#a855f7" stroke-width="1.5"/>
    <circle cx="105" cy="45" r="22" fill="#9333ea"/>
    <text x="105" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">2</text>
    <text x="105" y="95" fill="#f8fafc" font-size="15" font-weight="bold" text-anchor="middle">Gemini Vision OCR</text>
    <text x="105" y="125" fill="#94a3b8" font-size="12" text-anchor="middle">Hệ thống gửi Base64</text>
    <text x="105" y="145" fill="#94a3b8" font-size="12" text-anchor="middle">sang Gemini 1.5 Flash</text>
    <rect x="30" y="180" width="150" height="50" rx="8" fill="#1e293b"/>
    <text x="105" y="210" fill="#c084fc" font-size="12" font-weight="bold" text-anchor="middle">🤖 Nhận diện số</text>
  </g>

  <!-- Arrow 2 -> 3 -->
  <line x1="545" y1="260" x2="580" y2="260" stroke="#a855f7" stroke-width="3" marker-end="url(#arrPurple)"/>

  <!-- Step 3 -->
  <g transform="translate(590, 130)" filter="url(#shadow3)">
    <rect width="210" height="260" rx="14" fill="#0f172a" stroke="#f59e0b" stroke-width="1.5"/>
    <circle cx="105" cy="45" r="22" fill="#d97706"/>
    <text x="105" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">3</text>
    <text x="105" y="95" fill="#f8fafc" font-size="15" font-weight="bold" text-anchor="middle">Human In The Loop</text>
    <text x="105" y="125" fill="#94a3b8" font-size="12" text-anchor="middle">Tự điền chỉ số vào ô.</text>
    <text x="105" y="145" fill="#94a3b8" font-size="12" text-anchor="middle">Chủ trọ rà soát & điều</text>
    <text x="105" y="165" fill="#94a3b8" font-size="12" text-anchor="middle">chỉnh tay nếu muốn</text>
    <rect x="30" y="185" width="150" height="45" rx="8" fill="#1e293b"/>
    <text x="105" y="212" fill="#fbbf24" font-size="12" font-weight="bold" text-anchor="middle">🔍 Rà soát chính xác</text>
  </g>

  <!-- Arrow 3 -> 4 -->
  <line x1="810" y1="260" x2="845" y2="260" stroke="#a855f7" stroke-width="3" marker-end="url(#arrPurple)"/>

  <!-- Step 4 -->
  <g transform="translate(855, 130)" filter="url(#shadow3)">
    <rect width="190" height="260" rx="14" fill="#0f172a" stroke="#10b981" stroke-width="1.5"/>
    <circle cx="95" cy="45" r="22" fill="#059669"/>
    <text x="95" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">4</text>
    <text x="95" y="95" fill="#f8fafc" font-size="15" font-weight="bold" text-anchor="middle">Tự Động Tính Tiền</text>
    <text x="95" y="125" fill="#94a3b8" font-size="12" text-anchor="middle">Sản lượng = Mới - Cũ</text>
    <text x="95" y="145" fill="#94a3b8" font-size="12" text-anchor="middle">Thành tiền = SL x Đơn giá</text>
    <rect x="20" y="180" width="150" height="50" rx="8" fill="#1e293b"/>
    <text x="95" y="210" fill="#34d399" font-size="12" font-weight="bold" text-anchor="middle">💰 Đưa vào hóa đơn</text>
  </g>

  <!-- Bottom Metric Box -->
  <g transform="translate(60, 430)" filter="url(#shadow3)">
    <rect width="985" height="110" rx="12" fill="#1e293b" stroke="#334155"/>
    <text x="492" y="35" fill="#38bdf8" font-size="14" font-weight="bold" text-anchor="middle">SO SÁNH HIỆU SUẤT GHI ĐIỆN NƯỚC</text>
    <text x="60" y="70" fill="#f87171" font-size="13">❌ Cách truyền thống: 2 - 3 phút/phòng ghi sổ tay, dễ nhìn nhầm số 3 và 8, tốn 2 giờ cho dãy 30 phòng.</text>
    <text x="60" y="95" fill="#34d399" font-size="13">✓ Hệ thống Trọ Ơi: Chỉ mất 2 giây/phòng, AI đọc số chuẩn xác, giảm 95% thời gian chốt điện nước cuối tháng.</text>
  </g>
</svg>
SVG;

file_put_contents("$dir/luong_3_ghi_dien_nuoc_ai_ocr.svg", $svg3);


// -----------------------------------------------------------------------------
// SƠ ĐỒ 4: LUỒNG HỢP ĐỒNG & ĐÁNH GIÁ MINH BẠCH
// -----------------------------------------------------------------------------
$svg4 = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1100 580" width="100%" height="100%">
  <defs>
    <linearGradient id="bgGrad4" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" stop-color="#0f172a"/>
      <stop offset="100%" stop-color="#134e4a"/>
    </linearGradient>
    <filter id="shadow4" x="-5%" y="-5%" width="110%" height="110%">
      <feDropShadow dx="0" dy="6" stdDeviation="5" flood-color="#000" flood-opacity="0.35"/>
    </filter>
    <marker id="arrTeal" markerWidth="8" markerHeight="8" refX="6" refY="4" orient="auto">
      <path d="M 0 0 L 8 4 L 0 8 Z" fill="#2dd4bf"/>
    </marker>
  </defs>

  <rect width="1100" height="580" rx="16" fill="url(#bgGrad4)"/>

  <!-- Title Badge -->
  <rect x="250" y="25" width="600" height="48" rx="24" fill="#0f766e" opacity="0.3"/>
  <rect x="250" y="25" width="600" height="48" rx="24" fill="none" stroke="#2dd4bf" stroke-width="1.5"/>
  <text x="550" y="55" fill="#f8fafc" font-size="20" font-weight="bold" font-family="'Segoe UI', Roboto, sans-serif" text-anchor="middle">
    LUỒNG HỢP ĐỒNG & ĐÁNH GIÁ MINH BẠCH CHỐNG SEEDING
  </text>
  <text x="550" y="92" fill="#94a3b8" font-size="13" font-family="'Segoe UI', sans-serif" text-anchor="middle">
    Chỉ khách thuê ĐÃ CÓ HỢP ĐỒNG mới được đánh giá -> Hiển thị công khai cho người thuê mới
  </text>

  <!-- Step 1 -->
  <g transform="translate(60, 130)" filter="url(#shadow4)">
    <rect width="210" height="240" rx="14" fill="#0f172a" stroke="#0ea5e9" stroke-width="1.5"/>
    <circle cx="105" cy="45" r="22" fill="#0284c7"/>
    <text x="105" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">1</text>
    <text x="105" y="95" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Ký Hợp Đồng</text>
    <text x="105" y="125" fill="#94a3b8" font-size="11" text-anchor="middle">Thiết lập thời hạn,</text>
    <text x="105" y="145" fill="#94a3b8" font-size="11" text-anchor="middle">tiền cọc, giá thuê & DV</text>
    <rect x="25" y="170" width="160" height="40" rx="8" fill="#1e293b"/>
    <text x="105" y="195" fill="#38bdf8" font-size="11" font-weight="bold" text-anchor="middle">Phòng sang 'rented'</text>
  </g>

  <!-- Arrow 1 -> 2 -->
  <line x1="280" y1="250" x2="315" y2="250" stroke="#2dd4bf" stroke-width="3" marker-end="url(#arrTeal)"/>

  <!-- Step 2 -->
  <g transform="translate(325, 130)" filter="url(#shadow4)">
    <rect width="210" height="240" rx="14" fill="#0f172a" stroke="#14b8a6" stroke-width="1.5"/>
    <circle cx="105" cy="45" r="22" fill="#0d9488"/>
    <text x="105" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">2</text>
    <text x="105" y="95" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Thanh Lý Hợp Đồng</text>
    <text x="105" y="125" fill="#94a3b8" font-size="11" text-anchor="middle">Khách hết hạn hoặc trả</text>
    <text x="105" y="145" fill="#94a3b8" font-size="11" text-anchor="middle">phòng -> Tất toán cọc</text>
    <rect x="25" y="170" width="160" height="40" rx="8" fill="#1e293b"/>
    <text x="105" y="195" fill="#2dd4bf" font-size="11" font-weight="bold" text-anchor="middle">Phòng về 'available'</text>
  </g>

  <!-- Arrow 2 -> 3 -->
  <line x1="545" y1="250" x2="580" y2="250" stroke="#2dd4bf" stroke-width="3" marker-end="url(#arrTeal)"/>

  <!-- Step 3 -->
  <g transform="translate(590, 130)" filter="url(#shadow4)">
    <rect width="210" height="240" rx="14" fill="#0f172a" stroke="#eab308" stroke-width="1.5"/>
    <circle cx="105" cy="45" r="22" fill="#ca8a04"/>
    <text x="105" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">3</text>
    <text x="105" y="95" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Mời Đánh Giá</text>
    <text x="105" y="125" fill="#94a3b8" font-size="11" text-anchor="middle">Hệ thống bắn Notification</text>
    <text x="105" y="145" fill="#94a3b8" font-size="11" text-anchor="middle">mời khách gửi nhận xét</text>
    <rect x="25" y="170" width="160" height="40" rx="8" fill="#1e293b"/>
    <text x="105" y="195" fill="#fde047" font-size="11" font-weight="bold" text-anchor="middle">Chấm điểm 1 - 5 sao</text>
  </g>

  <!-- Arrow 3 -> 4 -->
  <line x1="810" y1="250" x2="845" y2="250" stroke="#2dd4bf" stroke-width="3" marker-end="url(#arrTeal)"/>

  <!-- Step 4 -->
  <g transform="translate(855, 130)" filter="url(#shadow4)">
    <rect width="190" height="240" rx="14" fill="#0f172a" stroke="#10b981" stroke-width="1.5"/>
    <circle cx="95" cy="45" r="22" fill="#059669"/>
    <text x="95" y="52" fill="#fff" font-size="16" font-weight="bold" text-anchor="middle">4</text>
    <text x="95" y="95" fill="#f8fafc" font-size="14" font-weight="bold" text-anchor="middle">Hiển Thị Công Khai</text>
    <text x="95" y="125" fill="#94a3b8" font-size="11" text-anchor="middle">Hiển thị tại trang /rooms/{id}</text>
    <text x="95" y="145" fill="#94a3b8" font-size="11" text-anchor="middle">cho khách thuê khác xem</text>
    <rect x="20" y="170" width="150" height="40" rx="8" fill="#1e293b"/>
    <text x="95" y="195" fill="#34d399" font-size="10" font-weight="bold" text-anchor="middle">✓ Đã từng thuê phòng</text>
  </g>

  <!-- Bottom Box -->
  <g transform="translate(60, 410)" filter="url(#shadow4)">
    <rect width="985" height="130" rx="12" fill="#1e293b" stroke="#0d9488"/>
    <text x="492" y="35" fill="#2dd4bf" font-size="15" font-weight="bold" text-anchor="middle">CƠ CHẾ BẢO VỆ TÍNH MINH BẠCH & CHỐNG SEEDING ĐÁNH GIÁ ẢO</text>
    <text x="40" y="68" fill="#f8fafc" font-size="12">• ReviewController kiểm tra quan hệ `contracts` trong CSDL: Khách vãng lai KHÔNG THỂ gửi đánh giá.</text>
    <text x="40" y="92" fill="#f8fafc" font-size="12">• Khi xem trang chi tiết phòng, người thuê mới thấy rõ thống kê: Điểm trung bình (★), phân bổ số sao và chi tiết nhận xét.</text>
    <text x="40" y="116" fill="#f8fafc" font-size="12">• Tạo niềm tin tuyệt đối cho người thuê mới và thúc đẩy chủ trọ nâng cao chất lượng dịch vụ phòng ở.</text>
  </g>
</svg>
SVG;

file_put_contents("$dir/luong_4_hop_dong_va_danh_gia.svg", $svg4);


// -----------------------------------------------------------------------------
// TẠO FILE HTML GALLERY ĐỂ XEM VÀ COPY ẢNH DỄ DÀNG
// -----------------------------------------------------------------------------
$html = <<<HTML
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bộ Sơ Đồ Luồng Hoạt Động - Hệ Thống Trọ Ơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #0f172a; color: #f8fafc; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding-bottom: 60px; }
        .hero { background: linear-gradient(135deg, #1e1b4b 0%, #0f172a 100%); border-bottom: 1px solid #334155; padding: 40px 0; margin-bottom: 40px; }
        .diagram-card { background: #1e293b; border: 1px solid #334155; border-radius: 16px; overflow: hidden; margin-bottom: 40px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.5); }
        .diagram-header { padding: 18px 24px; background: #0f172a; border-bottom: 1px solid #334155; display: flex; justify-content: space-between; align-items: center; }
        .diagram-body { padding: 20px; background: #0b1120; text-align: center; }
        .diagram-body img { max-width: 100%; height: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.4); }
        .badge-step { background: #0284c7; font-size: 13px; font-weight: 600; padding: 6px 12px; border-radius: 20px; }
        .btn-download { background: #0284c7; color: white; border: none; font-size: 13px; font-weight: 600; border-radius: 8px; padding: 6px 16px; }
        .btn-download:hover { background: #0369a1; color: white; }
    </style>
</head>
<body>
    <div class="hero text-center">
        <div class="container">
            <span class="badge bg-primary px-3 py-2 mb-2 text-uppercase fw-bold">Tài nguyên Đồ án Trọ Ơi</span>
            <h1 class="fw-bold text-white mb-3">BỘ HÌNH ẢNH SƠ ĐỒ CÁC LUỒNG HOẠT ĐỘNG CHÍNH</h1>
            <p class="text-secondary mb-0 max-w-2xl mx-auto">
                Hình ảnh sơ đồ vector SVG độ phân giải cao cực nét, sẵn sàng kéo thả trực tiếp vào Microsoft PowerPoint (PPTX) hoặc tài liệu báo cáo đồ án.
            </p>
        </div>
    </div>

    <div class="container">
        <!-- Diagram 1 -->
        <div class="diagram-card">
            <div class="diagram-header">
                <div>
                    <span class="badge-step me-2">Sơ đồ 1</span>
                    <strong class="text-white fs-5">Luồng Tổng Quan Vòng Đời Quản Lý Nhà Trọ (End-to-End)</strong>
                </div>
                <a href="luong_1_tong_quan_vong_doi.svg" download class="btn btn-download">Tải file SVG</a>
            </div>
            <div class="diagram-body">
                <img src="luong_1_tong_quan_vong_doi.svg" alt="Sơ đồ tổng quan">
            </div>
        </div>

        <!-- Diagram 2 -->
        <div class="diagram-card">
            <div class="diagram-header">
                <div>
                    <span class="badge-step me-2" style="background: #059669;">Sơ đồ 2</span>
                    <strong class="text-white fs-5">Luồng Thanh Toán Tự Động SePay (VietQR Webhook 3 Giây)</strong>
                </div>
                <a href="luong_2_thanh_toan_sepay.svg" download class="btn btn-download" style="background: #059669;">Tải file SVG</a>
            </div>
            <div class="diagram-body">
                <img src="luong_2_thanh_toan_sepay.svg" alt="Sơ đồ thanh toán SePay">
            </div>
        </div>

        <!-- Diagram 3 -->
        <div class="diagram-card">
            <div class="diagram-header">
                <div>
                    <span class="badge-step me-2" style="background: #7c3aed;">Sơ đồ 3</span>
                    <strong class="text-white fs-5">Luồng Ghi Số Điện - Nước Thông Minh Bằng AI OCR (Gemini Vision)</strong>
                </div>
                <a href="luong_3_ghi_dien_nuoc_ai_ocr.svg" download class="btn btn-download" style="background: #7c3aed;">Tải file SVG</a>
            </div>
            <div class="diagram-body">
                <img src="luong_3_ghi_dien_nuoc_ai_ocr.svg" alt="Sơ đồ AI OCR điện nước">
            </div>
        </div>

        <!-- Diagram 4 -->
        <div class="diagram-card">
            <div class="diagram-header">
                <div>
                    <span class="badge-step me-2" style="background: #0d9488;">Sơ đồ 4</span>
                    <strong class="text-white fs-5">Luồng Hợp Đồng & Đánh Giá Minh Bạch Sau Thanh Lý</strong>
                </div>
                <a href="luong_4_hop_dong_va_danh_gia.svg" download class="btn btn-download" style="background: #0d9488;">Tải file SVG</a>
            </div>
            <div class="diagram-body">
                <img src="luong_4_hop_dong_va_danh_gia.svg" alt="Sơ đồ hợp đồng và đánh giá">
            </div>
        </div>
    </div>
</body>
</html>
HTML;

file_put_contents("$dir/index.html", $html);

echo "ĐÃ TẠO XONG 4 SƠ ĐỒ VÀ TRANG XEM TỔNG HỢP TẠI:\n$dir\n";
