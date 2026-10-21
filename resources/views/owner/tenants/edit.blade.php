
@extends('Layout.landlord')

@section('title', 'Quản thêm người ở cùng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/edit_tenants.css') }}">
@endpush

@section('content')




<div class="page-wrap">


    <!-- QUAY LẠI -->

    <a
        href="{{ url('/owner/tenants/1') }}"
        class="back-link"
    >
        ← Quay lại hồ sơ người thuê
    </a>


    <h1 class="page-title">
        Chỉnh sửa hồ sơ người thuê
    </h1>

    <div class="page-desc">
        Cập nhật thông tin cá nhân của người thuê.
    </div>



    <!-- =================================================
         FORM
    ================================================= -->

    <div class="form-card">


        <!-- THÔNG BÁO -->

        <div class="info-box">

            💡 Các thông tin bên dưới là thông tin hồ sơ
            của <strong>người thuê chính</strong>.
            Thay đổi thông tin tại đây sẽ cập nhật hồ sơ
            người thuê.

        </div>



        <form
            action="#"
            method="POST"
        >

            @csrf

            @method('PUT')



            <!-- =========================================
                 THÔNG TIN CÁ NHÂN
            ========================================== -->

            <div class="section-title">
                Thông tin cá nhân
            </div>

            <div class="section-desc">
                Thông tin cơ bản của người thuê.
            </div>


            <div class="row g-4">


                <!-- HỌ TÊN -->

                <div class="col-md-6">

                    <label class="form-label">

                        Họ và tên

                        <span class="required">
                            *
                        </span>

                    </label>


                    <input
                        type="text"
                        name="name"
                        class="form-control"
                        value="Nguyễn Thanh Huyền"
                        placeholder="Nhập họ và tên"
                        required
                    >

                </div>



                <!-- GIỚI TÍNH -->

                <div class="col-md-6">

                    <label class="form-label">
                        Giới tính
                    </label>


                    <select
                        name="gender"
                        class="form-select"
                    >

                        <option value="">
                            -- Chọn giới tính --
                        </option>

                        <option value="female" selected>
                            Nữ
                        </option>

                        <option value="male">
                            Nam
                        </option>

                        <option value="other">
                            Khác
                        </option>

                    </select>

                </div>



                <!-- NGÀY SINH -->

                <div class="col-md-6">

                    <label class="form-label">
                        Ngày sinh
                    </label>


                    <input
                        type="date"
                        name="date_of_birth"
                        class="form-control"
                        value="1998-06-15"
                    >

                </div>



                <!-- CCCD -->

                <div class="col-md-6">

                    <label class="form-label">
                        Số CCCD
                    </label>


                    <input
                        type="text"
                        name="identity_card"
                        class="form-control"
                        value="079198001234"
                        placeholder="Nhập số CCCD"
                    >

                </div>

            </div>



            <hr class="section-divider">



            <!-- =========================================
                 LIÊN HỆ
            ========================================== -->

            <div class="section-title">
                Thông tin liên hệ
            </div>

            <div class="section-desc">
                Thông tin dùng để liên lạc với người thuê.
            </div>


            <div class="row g-4">


                <!-- SĐT -->

                <div class="col-md-6">

                    <label class="form-label">
                        Số điện thoại
                    </label>


                    <input
                        type="tel"
                        name="phone"
                        class="form-control"
                        value="0901234567"
                        placeholder="Nhập số điện thoại"
                    >

                </div>



                <!-- EMAIL -->

                <div class="col-md-6">

                    <label class="form-label">
                        Email
                    </label>


                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="huyen.nt@email.com"
                        placeholder="Nhập email"
                    >

                </div>



                <!-- ĐỊA CHỈ -->

                <div class="col-12">

                    <label class="form-label">
                        Địa chỉ thường trú
                    </label>


                    <input
                        type="text"
                        name="address"
                        class="form-control"
                        value="TP. Hồ Chí Minh"
                        placeholder="Nhập địa chỉ thường trú"
                    >

                </div>

            </div>



            <hr class="section-divider">



            <!-- =========================================
                 THÔNG TIN TÀI KHOẢN
            ========================================== -->

            <div class="section-title">
                Thông tin tài khoản
            </div>

            <div class="section-desc">
                Thông tin tài khoản đăng nhập của người thuê.
            </div>


            <div class="row g-4">


                <!-- EMAIL ĐĂNG NHẬP -->

                <div class="col-md-6">

                    <label class="form-label">
                        Email đăng nhập
                    </label>


                    <input
                        type="email"
                        class="form-control"
                        value="huyen.nt@email.com"
                        disabled
                    >


                    <div class="input-note">
                        Email đăng nhập được quản lý riêng.
                    </div>

                </div>



                <!-- VAI TRÒ -->

                <div class="col-md-6">

                    <label class="form-label">
                        Vai trò
                    </label>


                    <input
                        type="text"
                        class="form-control"
                        value="Người thuê"
                        disabled
                    >


                    <div class="input-note">
                        Vai trò tài khoản không thể thay đổi tại đây.
                    </div>

                </div>

            </div>



            <!-- =========================================
                 BUTTON
            ========================================== -->

            <hr class="section-divider">


            <div class="form-actions">

                <a
                    href="{{ url('/owner/tenants/1') }}"
                    class="btn-cancel"
                >
                    Hủy
                </a>


                <button
                    type="submit"
                    class="btn-save"
                >
                    ✓ Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

</div>




@endsection
 

