
@extends('Layout.landlord')

@section('title', 'Quản lý hợp đồng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/contracts/create.css') }}">
@endpush

@section('content')



<div class="page-wrap mt-4 mb-5">
    <div class="mb-3">
        <a href="{{ url('/owner/contracts') }}" class="text-decoration-none text-muted">← Quay lại danh sách</a>
    </div>
    
    <h2 class="fw-bold text-dark mb-4">Tạo hợp đồng mới</h2>

    <form action="#" method="POST">
        <div class="row">
            <div class="col-lg-8">
                <!-- Thông tin phòng -->
                <div class="panel-modern">
                    <h5 class="fw-bold mb-3">1. Thông tin thuê phòng</h5>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Chọn nhà / khu trọ</label>
                            <select class="form-select shadow-none">
                                <option>Nhà trọ Q.7</option>
                                <option>Chung cư mini Thủ Đức</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Chọn phòng trống</label>
                            <select class="form-select shadow-none">
                                <option>Phòng 102</option>
                                <option>Phòng 205</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Giá thuê (VNĐ/tháng)</label>
                            <input type="number" class="form-control shadow-none" value="3000000">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tiền cọc (VNĐ)</label>
                            <input type="number" class="form-control shadow-none" value="3000000">
                        </div>
                    </div>
                </div>

                <!-- Thông tin thời gian -->
                <div class="panel-modern">
                    <h5 class="fw-bold mb-3">2. Thời hạn hợp đồng</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Ngày bắt đầu</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày kết thúc</label>
                            <input type="date" class="form-control shadow-none">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ngày lập hóa đơn</label>
                            <select class="form-select shadow-none">
                                <option>Mùng 1 hàng tháng</option>
                                <option>Mùng 5 hàng tháng</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Đại diện thuê -->
                <div class="panel-modern">
                    <h5 class="fw-bold mb-3">3. Người đại diện thuê</h5>
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control shadow-none" placeholder="Nhập tên khách...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control shadow-none" placeholder="0901...">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Số CMND / CCCD</label>
                        <input type="text" class="form-control shadow-none" placeholder="079...">
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end gap-2 mt-2">
            <a href="{{ url('/owner/contracts') }}" class="btn btn-light px-4 py-2 border shadow-none">Hủy bỏ</a>
            <button type="submit" class="btn btn-success px-4 py-2 shadow-none">Tạo hợp đồng</button>
        </div>
    </form>
</div>




@endsection