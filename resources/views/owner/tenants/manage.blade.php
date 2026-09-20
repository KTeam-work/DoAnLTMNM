<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Quản lý người thuê | Trọ Ơi Chủ Trọ</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font -->

    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- CSS chung -->

    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}"
    >


    <style>

        /* =========================================
           NỀN TRANG
        ========================================= */

        html,
        body {
            background-color: #f7f9f8 !important;
        }

        body {
            min-height: 100vh;
        }

        .page-wrap {
            background-color: #f7f9f8 !important;
        }


        /* =========================================
           STAT CARD
        ========================================= */

        .stat-card-modern {
            background: #fff;

            border-radius: 12px;

            border: 1px solid #e2e8f0;

            padding: 1.5rem;

            transition: all 0.3s ease;

            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.02);

            height: 100%;
        }

        .stat-card-modern:hover {

            transform: translateY(-4px);

            box-shadow:
                0 10px 15px -3px rgba(0, 0, 0, 0.08);
        }


        /* =========================================
           ICON
        ========================================= */

        .icon-box {

            width: 48px;

            height: 48px;

            border-radius: 12px;

            display: flex;

            align-items: center;

            justify-content: center;

            font-size: 24px;
        }

        .bg-blue-soft {

            background: #e0f2fe;

            color: #0284c7;
        }

        .bg-green-soft {

            background: #dcfce7;

            color: #16a34a;
        }

        .bg-orange-soft {

            background: #ffedd5;

            color: #ea580c;
        }

        .bg-purple-soft {

            background: #f3e8ff;

            color: #9333ea;
        }


        /* =========================================
           PANEL
        ========================================= */

        .panel-modern {

            background: #fff;

            border-radius: 12px;

            border: 1px solid #e2e8f0;

            box-shadow:
                0 1px 3px rgba(0, 0, 0, 0.02);

            padding: 1.5rem;
        }


        /* =========================================
           TABLE
        ========================================= */

        .table-modern {

            width: 100%;

            border-collapse: separate;

            border-spacing: 0 8px;
        }

        .table-modern thead th {

            color: #64748b;

            font-weight: 600;

            font-size: 0.85rem;

            text-transform: uppercase;

            letter-spacing: 0.5px;

            border: none;

            padding:
                0 1rem
                0.5rem 1rem;
        }

        .table-modern tbody tr {

            transition: all 0.2s ease;

            background: #f8fafc;
        }

        .table-modern tbody tr:hover {

            background: #fff;

            box-shadow:
                0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table-modern td {

            vertical-align: middle;

            padding: 1rem;

            border: none;
        }

        .table-modern td:first-child {

            border-top-left-radius: 8px;

            border-bottom-left-radius: 8px;
        }

        .table-modern td:last-child {

            border-top-right-radius: 8px;

            border-bottom-right-radius: 8px;
        }


        /* =========================================
           AVATAR
        ========================================= */

        .avatar-circle {

            width: 40px;

            height: 40px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            font-weight: 600;

            font-size: 14px;

            flex-shrink: 0;
        }


        /* =========================================
           STATUS
        ========================================= */

        .badge-soft-success {

            background: #dcfce7;

            color: #16a34a;

            font-weight: 600;

            padding: 6px 12px;

            border-radius: 6px;

            display: inline-block;

            font-size: 0.85rem;
        }

        .badge-soft-warning {

            background: #ffedd5;

            color: #ea580c;

            font-weight: 600;

            padding: 6px 12px;

            border-radius: 6px;

            display: inline-block;

            font-size: 0.85rem;
        }

        .badge-soft-primary {

            background: #e0f2fe;

            color: #0284c7;

            font-weight: 600;

            padding: 5px 10px;

            border-radius: 6px;

            display: inline-block;

            font-size: 0.8rem;
        }

        .badge-soft-secondary {

            background: #f1f5f9;

            color: #64748b;

            font-weight: 600;

            padding: 5px 10px;

            border-radius: 6px;

            display: inline-block;

            font-size: 0.8rem;
        }


        /* =========================================
           BUTTON
        ========================================= */

        .btn-action {

            border-radius: 6px;

            font-weight: 500;

            transition: all 0.2s;

            border:
                1px solid #e2e8f0;

            color: #475569;

            background: #fff;

            padding:
                6px 12px;

            font-size: 0.875rem;

            text-decoration: none;
        }

        .btn-action:hover {

            background: #0f172a;

            color: #fff;

            border-color: #0f172a;
        }


        /* =========================================
           BUTTON THÊM NGƯỜI Ở CÙNG
        ========================================= */

        .btn-add-member {

            border: none;

            background: #0f172a;

            color: #fff;

            border-radius: 8px;

            padding:
                9px 16px;

            font-weight: 600;

            transition: all 0.2s;
        }

        .btn-add-member:hover {

            background: #1e293b;

            color: #fff;

            transform: translateY(-1px);
        }


        /* =========================================
           RESPONSIVE
        ========================================= */

        @media (max-width: 768px) {

            .table-modern {
                min-width: 1000px;
            }

        }

    </style>

</head>


<body>


    <!-- =====================================================
         NAVBAR
    ===================================================== -->
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
          <a class="app-nav-link" href="{{ route('landlord.home') }}">Tổng quan</a>
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
            </li>v
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


    <!-- =====================================================
         PAGE
    ===================================================== -->

    <div class="page-wrap mt-4 mb-5">


        <!-- PAGE HEADER -->

        <div
            class="page-header d-flex justify-content-between align-items-center mb-4"
        >

            <div>

                <h2 class="page-title">
                    Quản lý người thuê
                </h2>

                <div class="page-desc">

                    Theo dõi thông tin khách đang thuê và
                    lịch sử lưu trú.

                </div>

            </div>


            <!--
                Thêm người ở cùng tại trang riêng:
                chọn hợp đồng rồi nhập thông tin.
            -->

            <a
                href="{{ route('owner.tenants.create') }}"
                class="btn-add-member text-decoration-none"
            >
                + Thêm người ở cùng
            </a>

        </div>



        <!-- =====================================================
             STAT CARDS
        ===================================================== -->

        @php
            $totalTenants = $tenants->count() + $tenants->sum(fn($t) => $t->tenantContracts->flatMap->members->count());
            $staying = $tenants->count();
        @endphp
        <div class="row g-3 mb-4">


            <!-- TỔNG NGƯỜI THUÊ -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-blue-soft">
                        👥
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Tổng người thuê
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $totalTenants }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- ĐANG LƯU TRÚ -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-green-soft">
                        🏠
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Đang lưu trú
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $staying }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- SẮP HẾT HẠN -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-orange-soft">
                        ⚠️
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Sắp hết hạn HĐ
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $tenants->filter(fn($t) => ($c = $t->tenantContracts->first()) && $c->status === 'active' && $c->end_date && \Carbon\Carbon::parse($c->end_date)->diffInDays(now(), false) > -60)->count() }}
                        </div>

                    </div>

                </div>

            </div>


            <!-- MỚI CHUYỂN ĐẾN -->

            <div class="col-6 col-lg-3">

                <div
                    class="stat-card-modern d-flex align-items-center gap-3"
                >

                    <div class="icon-box bg-purple-soft">
                        👋
                    </div>


                    <div>

                        <div class="text-muted small fw-medium mb-1">
                            Hợp đồng hiệu lực
                        </div>

                        <div class="fs-4 fw-bold text-dark">
                            {{ $tenants->flatMap->tenantContracts->where('status', 'active')->count() }}
                        </div>

                    </div>

                </div>

            </div>

        </div>



        <!-- =====================================================
             TABLE PANEL
        ===================================================== -->

        <div class="panel-modern">


            <!-- TABLE HEADER -->

            <div
                class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3"
            >

                <h4 class="fw-bold text-dark m-0 fs-5">
                    Danh sách người đang lưu trú
                </h4>


                <div class="d-flex gap-2">


                    <!-- FILTER -->

                    <select
                        class="form-select form-select-sm border-light-subtle shadow-none"
                        style="width: 150px;"
                    >

                        <option value="">
                            Tất cả trạng thái
                        </option>

                        <option value="active">
                            Đang ở
                        </option>

                        <option value="leaving">
                            Sắp chuyển đi
                        </option>

                    </select>


                    <!-- SEARCH -->

                    <div
                        class="input-group input-group-sm"
                        style="width: 250px;"
                    >

                        <span
                            class="input-group-text bg-white border-end-0 text-muted border-light-subtle"
                        >
                            🔎
                        </span>


                        <input
                            type="text"
                            class="form-control border-start-0 border-light-subtle shadow-none"
                            placeholder="Tìm tên, SĐT, CCCD..."
                        >

                    </div>

                </div>

            </div>



            <!-- =================================================
                 TABLE
            ================================================= -->

            <div class="table-responsive">

                <table class="table-modern">

                    <thead>

                        <tr>

                            <th>
                                Người ở
                            </th>

                            <th>
                                Phòng
                            </th>

                            <th>
                                Vai trò
                            </th>

                            <th>
                                Thông tin liên hệ
                            </th>

                            <th>
                                Ngày vào ở
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th class="text-end">
                                Thao tác
                            </th>

                        </tr>

                    </thead>


                    <tbody>
                        @forelse($tenants as $tenant)
                            @php($contract = $tenant->tenantContracts->first())
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-blue-soft">
                                            {{ strtoupper(substr($tenant->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $tenant->name }}</div>
                                            <div class="small text-muted">{{ $tenant->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-1">{{ $contract?->room?->name ?? '—' }}</div>
                                    <div class="small text-muted">{{ $contract?->contract_code ?? '' }}</div>
                                </td>
                                <td><span class="badge-soft-primary">Người đứng tên HĐ</span></td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $tenant->phone ?? '—' }}</div>
                                    <div class="small text-muted">{{ $tenant->email }}</div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $contract ? \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') : '—' }}</div>
                                    <div class="small text-muted">HĐ: {{ $contract?->contract_code ?? '—' }}</div>
                                </td>
                                <td><span class="badge-soft-success">● Đang ở</span></td>
                                <td class="text-end">
                                    <a href="{{ route('owner.tenants.show', $tenant->id) }}" class="btn btn-action text-decoration-none">Hồ sơ</a>
                                </td>
                            </tr>
                            @foreach($contract?->members ?? [] as $member)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-circle bg-purple-soft">
                                            {{ strtoupper(substr($member->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold text-dark">{{ $member->name }}</div>
                                            <div class="small text-muted">{{ $member->relationship ?? 'Người ở cùng' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark mb-1">{{ $contract?->room?->name ?? '—' }}</div>
                                    <div class="small text-muted">{{ $contract?->contract_code ?? '' }}</div>
                                </td>
                                <td><span class="badge-soft-secondary">Người ở cùng</span></td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $member->phone ?? '—' }}</div>
                                    <div class="small text-muted">CCCD: {{ $member->identity_card }}</div>
                                </td>
                                <td>
                                    <div class="text-dark fw-medium">{{ $member->created_at?->format('d/m/Y') ?? '—' }}</div>
                                    <div class="small text-muted">HĐ: {{ $contract?->contract_code ?? '—' }}</div>
                                </td>
                                <td><span class="badge-soft-success">● Đang ở</span></td>
                                <td class="text-end"><span class="text-muted small">—</span></td>
                            </tr>
                            @endforeach
                        @empty
                            <tr><td colspan="7" class="text-center text-muted py-5">Chưa có người thuê nào.</td></tr>
                        @endforelse
                    </tbody>

                </table>

            </div>

        </div>

    </div>



 

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    ></script>

</body>

</html>