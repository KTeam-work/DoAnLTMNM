
@extends('Layout.landlord')

@section('title', 'Chỉnh sửa hồ sơ người thuê | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/edit_tenants.css') }}">
@endpush

@section('content')

<div class="page-wrap">

    {{-- QUAY LẠI --}}
    <a href="{{ route('owner.tenants.show', $tenant->id) }}" class="back-link">
        ← Quay lại hồ sơ người thuê
    </a>

    <h1 class="page-title">Chỉnh sửa hồ sơ người thuê</h1>

    <div class="page-desc">
        Cập nhật thông tin cá nhân của người thuê.
    </div>

    <div class="form-card">

        <div class="info-box">
            💡 Các thông tin bên dưới là thông tin hồ sơ của
            <strong>người thuê chính</strong>.
            Thay đổi thông tin tại đây sẽ cập nhật hồ sơ người thuê.
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form
            action="{{ route('owner.tenants.update', ['id' => $tenant->id]) }}"
            method="POST"
        >
            @csrf
            @method('PUT')

            {{-- THÔNG TIN CÁ NHÂN --}}
            <div class="section-title">Thông tin cá nhân</div>

            <div class="section-desc">
                Thông tin cơ bản của người thuê.
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label for="name" class="form-label">
                        Họ và tên <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="form-control"
                        value="{{ old('name', $tenant->name) }}"
                        placeholder="Nhập họ và tên"
                        required
                    >
                </div>

                <div class="col-md-6">
                    <label for="gender" class="form-label">Giới tính</label>

                    <select id="gender" name="gender" class="form-select">
                        <option value="">-- Chọn giới tính --</option>

                        <option value="female"
                            {{ old('gender', $tenant->gender ?? '') === 'female' ? 'selected' : '' }}>
                            Nữ
                        </option>

                        <option value="male"
                            {{ old('gender', $tenant->gender ?? '') === 'male' ? 'selected' : '' }}>
                            Nam
                        </option>

                        <option value="other"
                            {{ old('gender', $tenant->gender ?? '') === 'other' ? 'selected' : '' }}>
                            Khác
                        </option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="date_of_birth" class="form-label">Ngày sinh</label>

                    <input
                        type="date"
                        id="date_of_birth"
                        name="date_of_birth"
                        class="form-control"
                        value="{{ old('date_of_birth', $tenant->date_of_birth ? \Illuminate\Support\Carbon::parse($tenant->date_of_birth)->format('Y-m-d') : '') }}"
                    >
                </div>

                <div class="col-md-6">
                    <label for="identity_card" class="form-label">Số CCCD</label>

                    <input
                        type="text"
                        id="identity_card"
                        name="identity_card"
                        class="form-control"
                        value="{{ old('identity_card', $tenant->identity_card ?? '') }}"
                        placeholder="Nhập số CCCD"
                    >
                </div>

            </div>

            <hr class="section-divider">

            {{-- THÔNG TIN LIÊN HỆ --}}
            <div class="section-title">Thông tin liên hệ</div>

            <div class="section-desc">
                Thông tin dùng để liên lạc với người thuê.
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label for="phone" class="form-label">Số điện thoại</label>

                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        class="form-control"
                        value="{{ old('phone', $tenant->phone ?? '') }}"
                        placeholder="Nhập số điện thoại"
                    >
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>

                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="form-control"
                        value="{{ old('email', $tenant->email ?? '') }}"
                        placeholder="Nhập email"
                    >
                </div>

                <div class="col-12">
                    <label for="address" class="form-label">
                        Địa chỉ thường trú
                    </label>

                    <input
                        type="text"
                        id="address"
                        name="address"
                        class="form-control"
                        value="{{ old('address', $tenant->address ?? '') }}"
                        placeholder="Nhập địa chỉ thường trú"
                    >
                </div>

            </div>

            <hr class="section-divider">

            {{-- THÔNG TIN TÀI KHOẢN --}}
            <div class="section-title">Thông tin tài khoản</div>

            <div class="section-desc">
                Thông tin tài khoản đăng nhập của người thuê.
            </div>

            <div class="row g-4">

                <div class="col-md-6">
                    <label class="form-label">Email đăng nhập</label>

                    <input
                        type="email"
                        class="form-control"
                        value="{{ $tenant->email ?? '' }}"
                        disabled
                    >

                    <div class="input-note">
                        Email đăng nhập được quản lý riêng.
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Vai trò</label>

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

            <hr class="section-divider">

            {{-- NÚT THAO TÁC --}}
            <div class="form-actions">

                <a
                    href="{{ route('owner.tenants.show', $tenant->id) }}"
                    class="btn-cancel"
                >
                    Hủy
                </a>

                <button type="submit" class="btn-save">
                    ✓ Lưu thay đổi
                </button>

            </div>

        </form>

    </div>

</div>

@endsection
