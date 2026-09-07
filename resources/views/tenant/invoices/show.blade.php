<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Trọ Ơi | Hóa đơn #INV-092026</title>

<!-- Import Bootstrap & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/styles.css') }}">

<style>
  /* --- 1. SKELETON LOADING --- */
  .skeleton-mode .hide-on-skeleton { opacity: 0; visibility: hidden; }
  .skeleton-mode .skeleton-box { background: linear-gradient(90deg, #e9ecef 25%, #f8f9fa 50%, #e9ecef 75%); background-size: 200% 100%; animation: shimmer 1.5s infinite; border-radius: 8px; color: transparent !important; border-color: transparent !important; pointer-events: none; }
  .skeleton-mode .skeleton-box * { visibility: hidden; }
  @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
  
  /* --- 2. ANIMATION XUẤT HIỆN --- */
  @keyframes fadeInUp { from { opacity: 0; transform: translateY(25px); } to { opacity: 1; transform: translateY(0); } }
  .animate-up { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
  .delay-1 { animation-delay: 0.1s; } .delay-2 { animation-delay: 0.2s; } .delay-3 { animation-delay: 0.3s; }

  /* --- 3. CON DẤU TRẠNG THÁI (WATERMARK STAMP) --- */
  @keyframes stampDrop {
    0% { transform: scale(3) rotate(-15deg); opacity: 0; }
    50% { transform: scale(0.9) rotate(-15deg); opacity: 0.8; }
    100% { transform: scale(1) rotate(-15deg); opacity: 1; }
  }
  .invoice-stamp {
    position: absolute; top: 60px; right: 50px; font-size: 32px; font-weight: 900; text-transform: uppercase;
    border: 5px solid; border-radius: 12px; padding: 10px 24px; letter-spacing: 2px;
    opacity: 0; pointer-events: none; z-index: 10;
  }
  body:not(.skeleton-mode) .stamp-pending {
    color: rgba(198, 91, 74, 0.15); border-color: rgba(198, 91, 74, 0.15);
    animation: stampDrop 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) 0.6s forwards;
  }

  /* --- 4. GIAO DIỆN TỜ HÓA ĐƠN LỚN --- */
  .invoice-paper {
    background: #fff url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PHBhdGggZD0iTTAgMGgyMHYyMEgwem0xMCAxMGgxMHYxMEgxMHoiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMCIvPjxwYXRoIGQ9Ik0wIDEwaDIwTTAgMjBoMjBNMTAgMHYyME0yMCAwdjIwIiBzdHJva2U9IiNmM2YzZjMiIHN0cm9rZS13aWR0aD0iMSIvPjwvc3ZnPg==');
    border-radius: 16px; padding: 48px; box-shadow: 0 15px 40px rgba(0,0,0,0.04);
    border: 1px solid #eaeaea; position: relative; overflow: hidden;
  }
  .invoice-paper::before {
    content: ''; position: absolute; top: 0; left: 0; right: 0; height: 8px;
    background: linear-gradient(90deg, var(--green) 0%, #1e4a43 50%, var(--yellow) 100%);
  }
  
  /* Bảng Bóc Tách Chi Phí */
  .invoice-table th { font-size: 11px; text-transform: uppercase; color: var(--muted); border-bottom: 2px solid #e9ecef; padding: 12px 0; letter-spacing: 0.5px; }
  .invoice-table td { padding: 18px 0; border-bottom: 1px dashed #e9ecef; font-size: 14px; color: var(--text); vertical-align: middle; transition: 0.2s; }
  .invoice-table tbody tr:hover td { background: rgba(234, 243, 239, 0.3); transform: scale(1.002); }
  .invoice-table tr:last-child td { border-bottom: none; }
  
  .total-box { background: var(--green-soft); border-radius: 12px; padding: 24px; margin-top: 10px; border: 1px solid rgba(32, 88, 79, 0.1); }

  /* --- 5. BẢNG SIDEBAR THANH TOÁN (STICKY) --- */
  .payment-sidebar {
    background: #fff; border-radius: 16px; padding: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); border: 1px solid #eaeaea;
    position: sticky; top: 90px;
  }
  
  /* Hiệu ứng quét QR (Scanner Line) */
  .qr-scanner { position: relative; display: inline-block; overflow: hidden; border-radius: 12px; padding: 10px; background: #fff; border: 1px solid #eee; }
  .qr-scanner img { width: 100%; max-width: 160px; mix-blend-mode: multiply; }
  .qr-scanner::after {
    content: ''; position: absolute; top: 0; left: 0; width: 100%; height: 3px;
    background: rgba(32, 88, 79, 0.6); box-shadow: 0 0 15px rgba(32, 88, 79, 0.8);
    animation: scan 2.5s infinite linear; opacity: 0.8;
  }
  @keyframes scan { 0% { top: -10%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 110%; opacity: 0; } }

  /* --- 6. NÚT BẤM & TOAST --- */
  .btn-brand, .btn-outline-brand { transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1); border-radius: 10px; font-weight: 700; }
  .btn-brand:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(245,200,75,0.3); }
  .btn-brand:active, .btn-outline-brand:active { transform: scale(0.95); }
  .toast-container-custom { position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 9999; }
  .toast-custom { background: #213430; color: #fff; padding: 14px 28px; border-radius: 99px; font-size: 13px; font-weight: 600; box-shadow: 0 15px 30px rgba(0,0,0,0.2); display: flex; align-items: center; gap: 8px; opacity: 0; transform: translateY(20px); transition: all 0.3s ease; }
  .toast-custom.show { opacity: 1; transform: translateY(0); }

  /* --- 7. TỐI ƯU HÓA KHI IN ẨN (Ctrl + P) --- */
  @media print {
    body { background: #fff; }
    .app-navbar, .payment-sidebar, .breadcrumb-custom, .print-hide, .invoice-stamp { display: none !important; }
    .invoice-paper { box-shadow: none; border: none; padding: 0; background: none; }
    .invoice-paper::before { display: none; }
    .col-lg-8 { width: 100%; max-width: 100%; flex: 0 0 100%; }
    .page-wrap { padding-top: 0 !important; }
  }
</style>
</head>

<body class="skeleton-mode">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg app-navbar">
  <div class="container-fluid">
    <a class="logo" href="{{ url('/') }}">Trọ <span>Ơi</span></a>
    <div class="collapse navbar-collapse" id="mainMenu">
     <ul class="navbar-nav mx-auto align-items-lg-center">
        <li class="nav-item"><a class="app-nav-link" href="{{ url('/tenant') }}">Trang chủ</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#rooms">Tìm phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Yêu thích</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Lịch xem phòng</a></li>
        <li class="nav-item"><a class="app-nav-link" href="#">Hợp đồng</a></li>
        <li class="nav-item"><a class="app-nav-link active" href="{{ url('/tenant/invoices') }}">Hóa đơn</a></li>
      </ul>
      <div class="navbar-actions">
        <a href="#" class="user-chip skeleton-box"><span class="user-avatar hide-on-skeleton">TH</span><span class="user-meta hide-on-skeleton"><span class="user-name d-block">Thanh Huyền</span></span></a>
      </div>
    </div>
  </div>
</nav>

<!-- MAIN CONTENT -->
<div class="page-wrap" id="invoice-detail" style="padding-top: 20px;">
  
  <!-- Breadcrumb -->
  <div class="breadcrumb-custom mb-3 animate-up delay-1 skeleton-box rounded" style="color: var(--muted); font-size: 13px; font-weight: 500;">
    <span class="hide-on-skeleton">
      <a href="{{ route('tenant.home') }}" class="text-decoration-none text-muted">Trang chủ</a> <span class="mx-2 text-light-gray">/</span>
      <a href="{{ url('/tenant/invoices/index') }}" class="text-decoration-none text-muted">Hóa đơn</a> <span class="mx-2 text-light-gray">/</span>
      <span class="fw-bold" style="color: var(--green-dark);">Chi tiết #INV-092026</span>
    </span>
  </div>

  <div class="row g-4">
    <!-- CỘT TRÁI: TỜ HÓA ĐƠN DOANH NGHIỆP -->
    <div class="col-lg-8 animate-up delay-2">
      <div class="invoice-paper skeleton-box">
        <div class="hide-on-skeleton">
          
          <!-- Watermark Đóng Dấu -->
          <div class="invoice-stamp stamp-pending">Chưa Thanh Toán</div>

          <!-- Header Hóa Đơn -->
          <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-5">
            <div>
              <h2 class="fw-bold mb-1" style="color: var(--green-dark); letter-spacing: -1px; text-transform: uppercase;">Hóa Đơn Thuê Phòng</h2>
              <div class="text-muted" style="font-size: 14px;">Mã số: <strong class="text-dark">#INV-092026</strong></div>
              <div class="text-muted" style="font-size: 14px;">Ngày lập: 01/09/2026</div>
            </div>
            <div class="text-end">
              <div style="font-size: 26px; font-weight: 800; letter-spacing: -1px; color: var(--green-dark);">Trọ <span style="color: var(--yellow);">Ơi</span></div>
              <div class="text-muted" style="font-size: 12px; font-weight: 500;">Nền tảng quản lý lưu trú</div>
            </div>
          </div>

          <!-- Thông tin 2 bên (Bill From / Bill To) -->
          <div class="row mb-5" style="background: rgba(248, 249, 250, 0.5); padding: 20px; border-radius: 12px;">
            <div class="col-sm-6 mb-3 mb-sm-0 border-end">
              <div style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 700; margin-bottom: 8px;">Đơn vị phát hành</div>
              <div class="fw-bold text-dark" style="font-size: 16px;">Tòa nhà Trọ Ơi Q.7</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Chủ cơ sở: Nguyễn Văn Tuấn</div>
              <div class="text-muted" style="font-size: 13px;">MST: 0312345678</div>
              <div class="text-muted" style="font-size: 13px;">SĐT: 0901 234 567</div>
            </div>
            <div class="col-sm-6 ps-sm-4">
              <div style="font-size: 11px; text-transform: uppercase; color: var(--muted); font-weight: 700; margin-bottom: 8px;">Khách hàng (Người thuê)</div>
              <div class="fw-bold text-dark" style="font-size: 16px;">Thanh Huyền</div>
              <div class="text-muted mt-1" style="font-size: 13px;">Phòng thuê: <strong class="text-dark">Phòng 12A</strong></div>
              <div class="text-muted" style="font-size: 13px;">Hợp đồng: HD-12A-2026</div>
              <div class="text-muted" style="font-size: 13px;">SĐT: 0987 654 321</div>
            </div>
          </div>

          <!-- Bảng Chi Phí -->
          <div class="table-responsive mb-4">
            <table class="w-100 invoice-table">
              <thead>
                <tr>
                  <th style="width: 5%;">#</th>
                  <th style="width: 45%;">Hạng mục tính phí</th>
                  <th class="text-center" style="width: 15%;">Số lượng</th>
                  <th class="text-end" style="width: 15%;">Đơn giá</th>
                  <th class="text-end" style="width: 20%;">Thành tiền</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-muted">1</td>
                  <td><div class="fw-bold">Tiền thuê phòng tháng 09</div><div class="text-muted mt-1" style="font-size: 12px;">Kỳ: 01/09/2026 - 30/09/2026</div></td>
                  <td class="text-center">1 Tháng</td>
                  <td class="text-end text-muted">2.800.000</td>
                  <td class="text-end fw-bold">2.800.000 đ</td>
                </tr>
                <tr>
                  <td class="text-muted">2</td>
                  <td><div class="fw-bold">Tiền điện tiêu thụ</div><div class="text-muted mt-1" style="font-size: 12px;">CS Đầu: 1200 | CS Cuối: 1250</div></td>
                  <td class="text-center">50 kWh</td>
                  <td class="text-end text-muted">3.500</td>
                  <td class="text-end fw-bold">175.000 đ</td>
                </tr>
                <tr>
                  <td class="text-muted">3</td>
                  <td><div class="fw-bold">Tiền nước sinh hoạt</div><div class="text-muted mt-1" style="font-size: 12px;">Theo đồng hồ phòng</div></td>
                  <td class="text-center">2 Khối</td>
                  <td class="text-end text-muted">20.000</td>
                  <td class="text-end fw-bold">40.000 đ</td>
                </tr>
                <tr>
                  <td class="text-muted">4</td>
                  <td><div class="fw-bold">Dịch vụ chung</div><div class="text-muted mt-1" style="font-size: 12px;">Rác sinh hoạt, Internet tốc độ cao</div></td>
                  <td class="text-center">1 Gói</td>
                  <td class="text-end text-muted">150.000</td>
                  <td class="text-end fw-bold">150.000 đ</td>
                </tr>
                <tr>
                  <td class="text-muted">5</td>
                  <td><div class="fw-bold">Phí quản lý vận hành</div><div class="text-muted mt-1" style="font-size: 12px;">Vệ sinh hành lang, thang máy</div></td>
                  <td class="text-center">1 Gói</td>
                  <td class="text-end text-muted">285.000</td>
                  <td class="text-end fw-bold">285.000 đ</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Khối Tổng Tiền -->
          <div class="total-box d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
              <div style="font-size: 13px; color: var(--green-dark); font-weight: 700; text-transform: uppercase;">Tổng số tiền cần thanh toán</div>
              <div class="text-muted mt-1" style="font-size: 12px;">(Giá trị hóa đơn đã bao gồm VAT & Phí DV)</div>
            </div>
            <div class="text-end">
              <div style="font-size: 32px; font-weight: 800; color: var(--green-dark); letter-spacing: -1px;">3.450.000 đ</div>
            </div>
          </div>

          <!-- Điều khoản / Ghi chú -->
          <div class="mt-4 p-3 print-hide" style="background: rgba(245, 200, 75, 0.1); border-radius: 8px; border-left: 3px solid var(--yellow);">
            <strong style="font-size: 13px; color: #9b6a00;">Lưu ý từ ban quản lý:</strong>
            <p class="mb-0 mt-1" style="font-size: 13px; color: #7a5400;">Quý khách vui lòng hoàn tất thanh toán trước ngày <strong class="text-danger">05/09/2026</strong>. Nếu quá hạn 05 ngày, hệ thống điện nước có thể tự động tạm ngưng cung cấp.</p>
          </div>

        </div>
      </div>
    </div>

    <!-- CỘT PHẢI: BẢNG ĐIỀU KHIỂN THANH TOÁN -->
    <div class="col-lg-4 animate-up delay-3">
      <div class="payment-sidebar skeleton-box">
        <div class="hide-on-skeleton">
          
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0" style="color: var(--text);">Trạng thái</h5>
            <span class="badge-status pending" style="font-size: 12px; padding: 6px 12px; letter-spacing: 0.5px;">CHỜ THANH TOÁN</span>
          </div>

          <div class="mb-4 bg-light p-3 rounded-3 border">
            <div class="text-muted mb-1" style="font-size: 11px; text-transform: uppercase; font-weight: 700;">Hạn chót thanh toán</div>
            <div class="fw-bold text-danger d-flex align-items-center gap-2" style="font-size: 18px;">
              05/09/2026
              <span class="badge bg-danger bg-opacity-10 text-danger" style="font-size: 10px;">Còn 3 ngày</span>
            </div>
          </div>

          <!-- Box Mã QR với Scanner Effect -->
          <div class="text-center mb-4">
            <div class="qr-scanner shadow-sm mb-3">
              <img src="https://upload.wikimedia.org/wikipedia/commons/d/d0/QR_code_for_mobile_English_Wikipedia.svg" alt="Mã QR Vietcombank">
            </div>
            
            <div class="fw-bold mb-1" style="color: var(--green-dark); font-size: 15px;">Vietcombank (VCB)</div>
            <div class="text-muted mb-3" style="font-size: 13px;">Chủ TK: NGUYEN VAN TUAN<br><span style="font-size: 18px; font-weight: 800; color: var(--text); display: block; margin-top: 5px; letter-spacing: 1px;">0123456789</span></div>
            
            <button onclick="showCopyToast()" class="btn btn-light w-100 shadow-sm border bg-white fw-bold text-muted py-2 d-flex justify-content-center align-items-center gap-2" style="border-radius: 10px; font-size: 13px;">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/><path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/></svg> 
              Sao chép số tài khoản
            </button>
          </div>

          <hr style="border-top: 1px dashed #ddd; margin: 20px 0;">

          <!-- Các nút hành động -->
          <div class="d-flex flex-column gap-2 print-hide">
            <button class="btn btn-brand py-3 w-100" style="font-size: 14px;">✅ Tôi đã chuyển khoản</button>
            
            <div class="row g-2 mt-1">
              <div class="col-6">
                <!-- Nút gọi cửa sổ In mặc định của trình duyệt -->
                <button onclick="window.print()" class="btn btn-outline-brand w-100 py-2 d-flex justify-content-center align-items-center gap-2" style="font-size: 13px; background: #f8f9fa; border-color: #ddd; color: var(--text);">
                  🖨️ In Hóa Đơn
                </button>
              </div>
              <div class="col-6">
                <button class="btn btn-outline-brand w-100 py-2 d-flex justify-content-center align-items-center gap-2" style="font-size: 13px; background: #f8f9fa; border-color: #ddd; color: var(--text);">
                  📥 Lưu PDF
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- TOAST THÔNG BÁO COPY -->
<div class="toast-container-custom">
  <div id="copyToast" class="toast-custom">
    <span style="color: var(--yellow); font-size: 16px;">✔</span> Đã sao chép nội dung chuyển khoản!
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // 1. Tắt Skeleton Loading sau 0.8s (Mô phỏng load dữ liệu)
  document.addEventListener("DOMContentLoaded", function() {
    setTimeout(() => {
      document.body.classList.remove('skeleton-mode');
    }, 800); 
  });

  // 2. Chức năng hiển thị Toast khi bấm Copy
  function showCopyToast() {
    const toast = document.getElementById('copyToast');
    toast.classList.add('show');
    
    // Tự động tắt sau 3 giây
    setTimeout(() => { 
      toast.classList.remove('show'); 
    }, 3000);
  }
</script>
</body>
</html>