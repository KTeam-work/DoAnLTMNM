<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Thêm người ở cùng | Trọ Ơi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        html,
        body {
            background-color: #f7f9f8 !important;
        }

        body {
            min-height: 100vh;
            font-family: 'Be Vietnam Pro', sans-serif;
        }

        .page-wrap {
            max-width: 850px;
            margin: 35px auto 60px;
            padding: 0 20px;
        }

        .page-header {
            margin-bottom: 25px;
        }

        .back-link {
            color: #6b7280;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            display: inline-block;
            margin-bottom: 18px;
        }

        .back-link:hover {
            color: #198754;
        }

        .page-title {
            font-size: 27px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 7px;
        }

        .page-desc {
            color: #6b7280;
            font-size: 14px;
        }

        .info-card,
        .form-card {
            background: #ffffff;
            border-radius: 16px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.04);
        }

        .info-card {
            padding: 20px 22px;
            margin-bottom: 20px;
        }

        .info-title {
            font-size: 15px;
            font-weight: 700;
            color: #374151;
            margin-bottom: 15px;
        }

        .contract-info {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .info-item {
            background: #f8faf9;
            border-radius: 10px;
            padding: 12px 14px;
        }

        .info-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
        }

        .info-value {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
        }

        .form-card {
            padding: 28px;
        }

        .form-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .form-subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-label {
            font-size: 14px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            min-height: 46px;
            border-radius: 10px;
            border: 1px solid #dfe3e6;
            font-size: 14px;
            padding: 10px 13px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #198754;
            box-shadow: 0 0 0 3px rgba(25, 135, 84, 0.10);
        }

        .required {
            color: #dc3545;
        }

        .form-note {
            background: #f0fdf4;
            border: 1px solid #d1fae5;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            color: #166534;
            margin-top: 5px;
            margin-bottom: 25px;
        }

        .btn-cancel {
            min-height: 45px;
            padding: 10px 22px;
            border-radius: 10px;
            border: 1px solid #d1d5db;
            background: #ffffff;
            color: #4b5563;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
        }

        .btn-cancel:hover {
            background: #f9fafb;
            color: #374151;
        }

        .btn-save {
            min-height: 45px;
            padding: 10px 24px;
            border-radius: 10px;
            border: none;
            background: #198754;
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-save:hover {
            background: #157347;
            color: #ffffff;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding-top: 8px;
        }

        @media (max-width: 768px) {
            .contract-info {
                grid-template-columns: 1fr;
            }

            .form-card {
                padding: 20px;
            }

            .page-title {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>

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
          <a class="app-nav-link " href="{{ route('landlord.home') }}">Tổng quan</a>
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
              <a class="dropdown-item active" href="{{ route('owner.tenants.manage') }}">👤 Người thuê</a>
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
              <a class="dropdown-item" href="{{ route('owner.services.index') }}">✨ Dịch vụ</a>
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

<div class="page-wrap">

    <!-- Quay lại -->
    <div class="page-header">

        <a href="{{ route('owner.tenants.manage') }}" class="back-link">
            ← Quay lại quản lý người thuê
        </a>

        <h1 class="page-title">
            Thêm người ở cùng
        </h1>

        <div class="page-desc">
            Chọn hợp đồng rồi nhập thông tin người cùng sinh sống trong phòng.
        </div>

    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($contracts->isEmpty())
        <div class="alert alert-warning">Chưa có hợp đồng nào đang hiệu lực để thêm người ở cùng.</div>
    @else
    <!-- Chọn hợp đồng -->
    <div class="info-card">

        <div class="info-title">
            Chọn hợp đồng
        </div>

        <select id="contractSelect" class="form-select form-select-lg">
            @foreach($contracts as $contract)
                <option value="{{ $contract->id }}"
                    data-code="{{ $contract->contract_code }}"
                    data-tenant="{{ $contract->tenant->name ?? '' }}"
                    data-room="{{ $contract->room->name ?? '' }} · {{ $contract->room->property->name ?? '' }}"
                    data-count="{{ $contract->members->count() }}"
                    data-max="{{ $contract->room->max_people ?? '' }}"
                    @selected((string) old('contract_id', request('contract_id')) === (string) $contract->id)>
                    {{ $contract->contract_code }} — {{ $contract->room->name ?? '' }} ({{ $contract->tenant->name ?? '' }})
                </option>
            @endforeach
        </select>

        <div class="contract-info mt-3" id="contractInfoBox">

            <div class="info-item">
                <div class="info-label">
                    Mã hợp đồng
                </div>

                <div class="info-value" id="infoCode">
                    —
                </div>
            </div>


            <div class="info-item">
                <div class="info-label">
                    Người thuê chính
                </div>

                <div class="info-value" id="infoTenant">
                    —
                </div>
            </div>


            <div class="info-item">
                <div class="info-label">
                    Phòng
                </div>

                <div class="info-value" id="infoRoom">
                    —
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    Đã ở / Tối đa
                </div>

                <div class="info-value" id="infoCount">
                    —
                </div>
            </div>

        </div>

    </div>


    <!-- Form -->
    <div class="form-card">

        <div class="form-title">
            Thông tin người ở cùng
        </div>

        <div class="form-subtitle">
            Nhập thông tin của người sẽ cùng sinh sống trong phòng.
        </div>


        <form id="memberForm" action="#" method="POST">

            @csrf
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="contract_id" id="contractIdHidden" value="{{ old('contract_id', request('contract_id')) }}">

            <div class="row g-4">

                <!-- Họ tên -->
                <div class="col-md-6">

                    <label class="form-label">
                        Họ và tên <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="Nhập họ và tên"
                        required
                        maxlength="100"
                    >

                </div>


                <!-- CCCD -->
                <div class="col-md-6">

                    <label class="form-label">
                        Số CCCD <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="identity_card"
                        value="{{ old('identity_card') }}"
                        class="form-control"
                        placeholder="Nhập số CCCD"
                        required
                        maxlength="20"
                    >

                </div>


                <!-- Số điện thoại -->
                <div class="col-md-6">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control"
                        placeholder="Nhập số điện thoại"
                        maxlength="20"
                    >

                </div>


                <!-- Quan hệ -->
                <div class="col-md-6">

                    <label class="form-label">
                        Quan hệ với người thuê chính
                    </label>

                    <select
                        name="relationship"
                        class="form-select"
                    >

                        <option value="">
                            -- Chọn quan hệ --
                        </option>

                        @foreach(['Bạn', 'Vợ/Chồng', 'Anh/Chị/Em', 'Người thân', 'Khác'] as $rel)
                            <option value="{{ $rel }}" @selected(old('relationship') === $rel)>
                                {{ $rel }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>


            <!-- Ghi chú -->
            <div class="form-note mt-4">
                💡 Người ở cùng chỉ được ghi nhận trong hợp đồng và
                <strong>không cần tạo tài khoản Trọ Ơi</strong>.
            </div>


            <!-- Buttons -->
            <div class="form-actions">

                <a
                    href="{{ route('owner.tenants.manage') }}"
                    class="btn-cancel"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    ✓ Thêm người ở cùng
                </button>

            </div>

        </form>

        @endif

    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function () {
    var select = document.getElementById('contractSelect');
    var form = document.getElementById('memberForm');
    if (!select || !form) return;
    function sync() {
        var opt = select.options[select.selectedIndex];
        form.action = "{{ url('/owner/contracts') }}/" + opt.value + "/members";
        document.getElementById('contractIdHidden').value = opt.value;
        document.getElementById('infoCode').textContent = '#' + (opt.getAttribute('data-code') || '');
        document.getElementById('infoTenant').textContent = opt.getAttribute('data-tenant') || '—';
        document.getElementById('infoRoom').textContent = opt.getAttribute('data-room') || '—';
        document.getElementById('infoCount').textContent =
            (1 + parseInt(opt.getAttribute('data-count') || '0', 10)) + ' / ' + (opt.getAttribute('data-max') || '?') + ' người';
    }
    select.addEventListener('change', sync);
    sync();
})();
</script>

</body>
</html>