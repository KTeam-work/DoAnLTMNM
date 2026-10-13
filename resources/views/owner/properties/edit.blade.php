
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trọ Ơi | Sửa nhà trọ</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        body {
            background: #f8f5eb;
            color: #213430;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .edit-container {
            max-width: 1000px;
            margin: auto;
            padding: 35px 20px 50px;
        }

        .page-heading {
            margin-bottom: 25px;
        }

        .eyebrow {
            color: #20584f;
            font-size: 12px;
            font-weight: 800;
            letter-spacing: 1.4px;
            text-transform: uppercase;
            margin-bottom: 7px;
        }

        .page-heading h1 {
            font-size: 30px;
            font-weight: 800;
        }

        .page-heading p {
            color: #78837e;
        }

        .edit-card {
            background: white;
            border: 1px solid #e6dcc2;
            border-radius: 22px;
            overflow: hidden;
            box-shadow: 0 10px 32px rgba(32, 88, 79, .05);
        }

        .edit-card-header {
            background: #20584f;
            color: white;
            padding: 22px 26px;
        }

        .edit-card-header h2 {
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .edit-card-header p {
            font-size: 13px;
            margin: 0;
            color: #e0eee8;
        }

        .edit-card-body {
            padding: 28px;
        }

        .form-label {
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .required {
            color: #c65b4a;
        }

        .form-control,
        .form-select {
            border-radius: 12px;
            padding: 11px 13px;
            border-color: #e6dcc2;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #20584f;
            box-shadow: 0 0 0 .2rem rgba(32, 88, 79, .10);
        }

        .btn-owner {
            background: #20584f;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }

        .btn-owner:hover {
            background: #17463e;
            color: white;
        }

        .btn-back {
            background: #eaf3ef;
            color: #20584f;
            border-radius: 12px;
            padding: 12px 20px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back:hover {
            background: #dcebe4;
            color: #17463e;
        }

        .form-text {
            color: #78837e;
        }

        @media (max-width: 576px) {
            .edit-container {
                padding: 25px 14px 35px;
            }

            .edit-card-body {
                padding: 20px 16px;
            }

            .page-heading h1 {
                font-size: 25px;
            }
        }
    </style>
</head>

<body>
    <div class="owner-page">

        <nav class="navbar navbar-expand-lg app-navbar">
            <div class="container-fluid">
                <a class="logo" href="{{ route('landlord.dashboard') }}">
                    Trọ <span>Ơi</span>
                </a>

                <div class="ms-auto d-flex gap-2">
                    <a href="{{ route('owner.properties.index') }}" class="btn-back">
                        ← Danh sách nhà
                    </a>
                </div>
            </div>
        </nav>

        <main class="edit-container">

            <div class="page-heading">
                <div class="eyebrow">Owner workspace</div>
                <h1>Sửa thông tin nhà trọ</h1>
                <p>Cập nhật thông tin cho nhà trọ: {{ $property->name }}</p>
            </div>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Vui lòng kiểm tra lại thông tin:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <section class="edit-card">

                <div class="edit-card-header">
                    <h2>Thông tin nhà trọ</h2>
                    <p>Các trường có dấu * là bắt buộc.</p>
                </div>

                <div class="edit-card-body">

                    <form action="{{ route('owner.properties.update', $property->id) }}"
                          method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="form-label">
                                Tên nhà trọ <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="name"
                                name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $property->name) }}"
                                maxlength="150"
                                required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="type" class="form-label">
                                Loại hình <span class="required">*</span>
                            </label>

                            <select
                                id="type"
                                name="type"
                                class="form-select @error('type') is-invalid @enderror"
                                required>
                                <option value="rooming_house"
                                    {{ old('type', $property->type) === 'rooming_house' ? 'selected' : '' }}>
                                    Nhà trọ
                                </option>

                                <option value="apartment"
                                    {{ old('type', $property->type) === 'apartment' ? 'selected' : '' }}>
                                    Căn hộ
                                </option>

                                <option value="house"
                                    {{ old('type', $property->type) === 'house' ? 'selected' : '' }}>
                                    Nhà nguyên căn
                                </option>
                            </select>

                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="address" class="form-label">
                                Địa chỉ chi tiết <span class="required">*</span>
                            </label>

                            <input
                                type="text"
                                id="address"
                                name="address"
                                class="form-control @error('address') is-invalid @enderror"
                                value="{{ old('address', $property->address) }}"
                                maxlength="255"
                                required>

                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="district" class="form-label">
                                    Quận/huyện <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="district"
                                    name="district"
                                    class="form-control @error('district') is-invalid @enderror"
                                    value="{{ old('district', $property->district) }}"
                                    maxlength="100"
                                    required>

                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ward" class="form-label">
                                    Phường/xã <span class="required">*</span>
                                </label>

                                <input
                                    type="text"
                                    id="ward"
                                    name="ward"
                                    class="form-control @error('ward') is-invalid @enderror"
                                    value="{{ old('ward', $property->ward) }}"
                                    maxlength="100"
                                    required>

                                @error('ward')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label for="latitude" class="form-label">
                                    Vĩ độ
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    min="-90"
                                    max="90"
                                    id="latitude"
                                    name="latitude"
                                    class="form-control @error('latitude') is-invalid @enderror"
                                    value="{{ old('latitude', $property->latitude) }}"
                                    placeholder="Không bắt buộc">

                                @error('latitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="longitude" class="form-label">
                                    Kinh độ
                                </label>

                                <input
                                    type="number"
                                    step="any"
                                    min="-180"
                                    max="180"
                                    id="longitude"
                                    name="longitude"
                                    class="form-control @error('longitude') is-invalid @enderror"
                                    value="{{ old('longitude', $property->longitude) }}"
                                    placeholder="Không bắt buộc">

                                @error('longitude')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">
                                Trạng thái <span class="required">*</span>
                            </label>

                            <select
                                id="status"
                                name="status"
                                class="form-select @error('status') is-invalid @enderror"
                                required>
                                <option value="active"
                                    {{ old('status', $property->status) === 'active' ? 'selected' : '' }}>
                                    Đang hoạt động
                                </option>

                                <option value="inactive"
                                    {{ old('status', $property->status) === 'inactive' ? 'selected' : '' }}>
                                    Tạm ngưng
                                </option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="description" class="form-label">
                                Mô tả
                            </label>

                            <textarea
                                id="description"
                                name="description"
                                rows="4"
                                class="form-control @error('description') is-invalid @enderror"
                                placeholder="Mô tả về nhà trọ...">{{ old('description', $property->description) }}</textarea>

                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-text mb-4">
                            Thông tin phòng được quản lý riêng trong mục Quản lý phòng.
                        </div>

                        <div class="d-flex flex-wrap justify-content-end gap-2">
                            <a href="{{ route('owner.properties.index') }}" class="btn-back">
                                Hủy
                            </a>

                            <button type="submit" class="btn-owner">
                                Lưu thay đổi
                            </button>
                        </div>

                    </form>
                </div>
            </section>
        </main>
    </div>
</body>
</html>
