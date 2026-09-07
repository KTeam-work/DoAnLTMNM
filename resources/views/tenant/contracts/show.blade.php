@extends('layout.tenant')

@section('content')
<!-- BREADCRUMB / ACTION TOP -->
<div class="d-flex justify-content-between align-items-center mb-3">
  <a href="{{ url('/tenant/contracts') }}" class="panel-link text-decoration-none">
    ← Quay lại danh sách hợp đồng
  </a>
  <div class="d-flex gap-2">
    <button class="btn-outline-brand btn-sm">📥 Tải file PDF</button>
    <button class="btn btn-sm btn-outline-danger">⚠️ Yêu cầu thanh lý</button>
  </div>
</div>

<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h2 class="page-title">Chi tiết hợp đồng <span class="text-brand">#HD-2026-01</span></h2>
    <div class="page-desc">Thông tin chi tiết về phòng thuê, chi phí cố định và các dịch vụ đi kèm.</div>
  </div>
  <span class="badge-status resolved">Đang hiệu lực</span>
</div>

<div class="row g-3">
  <!-- CỘT TRÁI: THÔNG TIN PHÒNG & DỊCH VỤ -->
  <div class="col-lg-8">
    <div class="panel mb-3">
      <div class="panel-head">
        <h3 class="panel-title">Thông tin phòng & Giá thuê</h3>
      </div>
      <div class="row g-3 p-3">
        <div class="col-6 col-md-4">
          <div class="cell-sub">Phòng thuê</div>
          <div class="fw-bold text-dark fs-6">Phòng trọ máy lạnh Q.7</div>
        </div>
        <div class="col-6 col-md-4">
          <div class="cell-sub">Tiền thuê hàng tháng</div>
          <div class="fw-bold text-danger fs-6">2.800.000 đ/tháng</div>
        </div>
        <div class="col-6 col-md-4">
          <div class="cell-sub">Tiền cọc</div>
          <div class="fw-bold text-dark fs-6">2.800.000 đ</div>
        </div>
        <div class="col-6 col-md-4">
          <div class="cell-sub">Ngày bắt đầu</div>
          <div class="fw-bold text-dark">01/02/2026</div>
        </div>
        <div class="col-6 col-md-4">
          <div class="cell-sub">Ngày kết thúc</div>
          <div class="fw-bold text-dark">01/02/2027</div>
        </div>
        <div class="col-6 col-md-4">
          <div class="cell-sub">Kỳ thanh toán</div>
          <div class="fw-bold text-dark">Hàng tháng (Ngày 05)</div>
        </div>
      </div>
    </div>

    <div class="panel">
      <div class="panel-head">
        <h3 class="panel-title">Bảng giá Dịch vụ & Điện nước</h3>
      </div>
      <table class="data-table">
        <thead>
          <tr>
            <th>Loại dịch vụ</th>
            <th>Hình thức tính</th>
            <th class="text-end">Đơn giá</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>⚡ Điện sinh hoạt</td>
            <td>Theo chỉ số đồng hồ</td>
            <td class="text-end"><strong>3.500 đ / kWh</strong></td>
          </tr>
          <tr>
            <td>💧 Nước sinh hoạt</td>
            <td>Theo số người ở</td>
            <td class="text-end"><strong>100.000 đ / người</strong></td>
          </tr>
          <tr>
            <td>🌐 Internet / Wifi</td>
            <td>Cố định theo phòng</td>
            <td class="text-end"><strong>50.000 đ / phòng</strong></td>
          </tr>
          <tr>
            <td>🧹 Vệ sinh & Rác</td>
            <td>Theo số người ở</td>
            <td class="text-end"><strong>30.000 đ / người</strong></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <!-- CỘT PHẢI: CHỦ TRỌ & THÀNH VIÊN -->
  <div class="col-lg-4">
    <div class="panel mb-3">
      <div class="panel-head">
        <h3 class="panel-title">Thông tin Chủ trọ</h3>
      </div>
      <div class="p-3">
        <div class="d-flex align-items-center gap-3 mb-3">
          <div class="avatar-sm">MT</div>
          <div>
            <div class="fw-bold text-dark">Minh Tuấn</div>
            <div class="cell-sub">SĐT: 0901 234 567</div>
          </div>
        </div>
        <a href="tel:0901234567" class="btn-outline-brand btn-sm w-100 text-center d-block text-decoration-none">
          📞 Gọi cho chủ trọ
        </a>
      </div>
    </div>

    <div class="panel">
      <div class="panel-head d-flex justify-content-between align-items-center">
        <h3 class="panel-title">Thành viên phòng</h3>
        <span class="cell-sub">1/2 Người</span>
      </div>
      <div class="p-3">
        <div class="d-flex align-items-center gap-3">
          <div class="avatar-sm">TH</div>
          <div>
            <div class="fw-bold text-dark">Nguyễn Tấn Hiệu</div>
            <div class="cell-sub">Người đại diện hợp đồng</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection