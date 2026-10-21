
@extends('Layout.landlord')

@section('title', 'Quản thêm người ở cùng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/show_tenant.css') }}">
@endpush

@section('content')



<div class="page-wrap">


    <!-- QUAY LẠI -->

    <a
        href="{{ url('/owner/tenants') }}"
        class="back-link"
    >
        ← Quay lại danh sách người thuê
    </a>



    <!-- =================================================
         PROFILE HEADER
    ================================================= -->

    <div class="profile-card">

        <div class="profile-header">

            <div class="profile-left">

                <div class="avatar-large">
                    TH
                </div>


                <div>

                    <div class="profile-name">
                        Nguyễn Thanh Huyền
                    </div>

                    <div class="profile-subtitle">
                        Người đứng tên hợp đồng · Phòng 12A
                    </div>

                    <span class="badge-soft-success">
                        ● Đang ở
                    </span>

                    <span class="badge-soft-primary ms-2">
                        Người thuê chính
                    </span>

                </div>

            </div>


            <a
                href="{{ route('owner.tenants.edit') }}"
                class="btn-edit"
            >
                ✎ Chỉnh sửa hồ sơ
            </a>

        </div>

    </div>



    <!-- =================================================
         CONTENT
    ================================================= -->

    <div class="content-grid">


        <!-- =================================================
             LEFT
        ================================================= -->

        <div>


            <!-- THÔNG TIN CÁ NHÂN -->

            <div class="panel-modern">

                <div class="panel-title">
                    Thông tin cá nhân
                </div>


                <div class="info-row">

                    <div class="info-label">
                        Họ và tên
                    </div>

                    <div class="info-value">
                        Nguyễn Thanh Huyền
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Giới tính
                    </div>

                    <div class="info-value">
                        Nữ
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Ngày sinh
                    </div>

                    <div class="info-value">
                        15/06/1998
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Số CCCD
                    </div>

                    <div class="info-value">
                        07919800****1234
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Số điện thoại
                    </div>

                    <div class="info-value">
                        0901 234 567
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Email
                    </div>

                    <div class="info-value">
                        huyen.nt@email.com
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Địa chỉ thường trú
                    </div>

                    <div class="info-value">
                        TP. Hồ Chí Minh
                    </div>

                </div>

            </div>



            <!-- HỢP ĐỒNG -->

            <div class="panel-modern">

                <div class="panel-title">
                    Hợp đồng thuê hiện tại
                </div>


                <div class="contract-box">

                    <div class="contract-code">
                        Hợp đồng #HD-2026-01
                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Phòng
                        </span>

                        <span class="contract-value">
                            Phòng 12A
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Ngày bắt đầu
                        </span>

                        <span class="contract-value">
                            01/02/2026
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Ngày kết thúc
                        </span>

                        <span class="contract-value">
                            01/02/2027
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Tiền thuê
                        </span>

                        <span class="contract-value">
                            2.800.000 đ / tháng
                        </span>

                    </div>


                    <div class="contract-item">

                        <span class="contract-label">
                            Tiền cọc
                        </span>

                        <span class="contract-value">
                            2.800.000 đ
                        </span>

                    </div>

                </div>


                <div class="mt-3">

                    <a
                        href="{{ url('/owner/contracts/1') }}"
                        class="btn btn-action"
                    >
                        Xem chi tiết hợp đồng →
                    </a>

                </div>

            </div>

        </div>



        <!-- =================================================
             RIGHT
        ================================================= -->

        <div>


            <!-- THÔNG TIN LƯU TRÚ -->

            <div class="panel-modern">

                <div class="panel-title">
                    Thông tin lưu trú
                </div>


                <div class="info-row">

                    <div class="info-label">
                        Phòng đang thuê
                    </div>

                    <div class="info-value">
                        12A
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Ngày vào ở
                    </div>

                    <div class="info-value">
                        01/02/2026
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Thời hạn
                    </div>

                    <div class="info-value">
                        12 tháng
                    </div>

                </div>


                <div class="info-row">

                    <div class="info-label">
                        Trạng thái
                    </div>

                    <div class="info-value">

                        <span class="badge-soft-success">
                            Đang ở
                        </span>

                    </div>

                </div>

            </div>



            <!-- NGƯỜI Ở CÙNG -->

            <div class="panel-modern">

                <div class="d-flex justify-content-between align-items-center mb-3">

                    <div class="panel-title mb-0">
                        Người ở cùng
                    </div>

                    <span class="text-muted small">
                        1 người
                    </span>

                </div>


                <div class="member-item">

                    <div class="member-left">

                        <div class="member-avatar">
                            TB
                        </div>

                        <div>

                            <div class="member-name">
                                Trần Minh Bình
                            </div>

                            <div class="member-role">
                                Bạn · 0912 345 678
                            </div>

                        </div>

                    </div>


                    <a
                        href="#"
                        class="btn btn-sm btn-action"
                    >
                        Hồ sơ
                    </a>

                </div>


                <a
                    href="{{ url('/owner/contracts/1/members/create') }}"
                    class="btn btn-action w-100 mt-3"
                >
                    + Thêm người ở cùng
                </a>

            </div>



            <!-- GHI CHÚ -->

            <div class="panel-modern">

                <div class="panel-title">
                    Ghi chú
                </div>

                <div class="note-box">

                    Người thuê chính đang đứng tên hợp đồng
                    #HD-2026-01. Các thông tin người ở cùng
                    được quản lý theo hợp đồng thuê.

                </div>

            </div>

        </div>

    </div>

</div>



@endsection