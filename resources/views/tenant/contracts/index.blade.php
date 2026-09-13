<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Hợp đồng của tôi | Trọ Ơi</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- CSS chung -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        /* =====================================================
           STAT CARDS
        ===================================================== */

        .stat-card-modern {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            padding: 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        }

        .stat-card-modern:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px -3px rgba(0, 0, 0, 0.08);
        }

        /* =====================================================
           ICON BOX
        ===================================================== */

        .icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
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

        /* =====================================================
           PANEL
        ===================================================== */

        .panel-modern {
            background: #fff;
            border-radius: 16px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
            padding: 1.5rem;
        }

        /* =====================================================
           TABLE
        ===================================================== */

        .table-modern {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .table-modern thead th {
            color: #64748b;
            font-weight: 600;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border: none;
            padding: 0 1rem 0.5rem 1rem;
        }

        .table-modern tbody tr {
            transition: all 0.2s ease;
            background: #fff;
        }

        .table-modern tbody tr:hover {
            background: #fff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transform: scale(1.002);
        }

        .table-modern td {
            vertical-align: middle;
            padding: 1rem;
            border: none;
            border-top: 1px solid transparent;
            border-bottom: 1px solid transparent;
        }

        .table-modern td:first-child {
            border-top-left-radius: 12px;
            border-bottom-left-radius: 12px;
            border-left: 1px solid transparent;
        }

        .table-modern td:last-child {
            border-top-right-radius: 12px;
            border-bottom-right-radius: 12px;
            border-right: 1px solid transparent;
        }

        /* =====================================================
           STATUS
        ===================================================== */

        .badge-soft-success {
            background: #dcfce7;
            color: #16a34a;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 8px;
            display: inline-block;
        }

        /* =====================================================
           BUTTON
        ===================================================== */

        .btn-action {
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            color: #475569;
            background: #fff;
        }

        .btn-action:hover {
            background: #0f172a;
            color: #fff;
            border-color: #0f172a;
        }

        /* =====================================================
           RESPONSIVE
        ===================================================== */

        @media (max-width: 768px) {
            .page-header {
                align-items: flex-start !important;
            }
            .table-modern {
                min-width: 900px;
            }
        }
    </style>
</head>

<body>

    <!-- =====================================================
         NAVBAR
    ===================================================== -->
    <nav class="navbar navbar-expand-lg app-navbar px-4">
        <div class="container-fluid">
            <!-- LOGO -->
            <a class="logo" href="{{ url('/') }}">
                Trọ <span>Ơi</span>
            </a>

            <!-- MOBILE BUTTON -->
            <button
                class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu"
            >
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="mainMenu">
                <ul class="navbar-nav mx-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="app-nav-link" href="{{ route('tenant.home') }}">Trang chủ</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link" href="{{ url('/#rooms') }}">Tìm phòng</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link" href="#">Yêu thích</a>
                    </li>
                    <li class="nav-item">
                        <a class="app-nav-link" href="#">Lịch xem phòng</a>
                    </li>
                    <!-- HỢP ĐỒNG -->
                    <li class="nav-item">
                        <a class="app-nav-link active" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a>
                    </li>
                    <!-- HÓA ĐƠN -->
                    <li class="nav-item">
                        <a class="app-nav-link" href="{{ route('tenant.invoices.index') }}">Hóa đơn</a>
                    </li>
                    <!-- SỬA CHỮA -->
                    <li class="nav-item">
                        <a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a>
                    </li>
                    <!-- ĐÁNH GIÁ -->
                    <li class="nav-item">
                        <a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a>
                    </li>
                </ul>

                <!-- USER -->
                <div class="navbar-actions">
                    <button class="notif-btn">
                        🔔<span class="notif-dot"></span>
                    </button>
                    <a href="#" class="user-chip text-decoration-none">
                        <span class="user-avatar">TH</span>
                        <span class="user-meta">
                            <span class="user-name d-block">Thanh Huyền</span>
                            <span class="user-role">Người thuê</span>
                        </span>
                        <span class="caret">▾</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- =====================================================
         MAIN CONTENT
    ===================================================== -->
    <main class="container-fluid mt-4 mb-5">
        <!-- HEADER -->
        <div class="page-header d-flex justify-content-between align-items-center mb-5 mt-2">
            <div>
                <h2 class="page-title fw-bold text-dark mb-1">
                    Hợp đồng của tôi
                </h2>
                <div class="page-desc text-muted">
                    Quản lý và theo dõi danh sách hợp đồng thuê phòng của bạn.
                </div>
            </div>
            <button class="btn btn-primary rounded-pill px-4 py-2 fw-medium shadow-sm">
                + Tạo yêu cầu mới
            </button>
        </div>

        <!-- =================================================
             STAT CARDS
        ================================================= -->
        <div class="row g-4 mb-5">
            <!-- CARD 1 -->
            <div class="col-6 col-lg-4">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="icon-box bg-blue-soft">📋</div>
                    <div>
                        <div class="text-muted small fw-medium mb-1">
                            Tổng hợp đồng <span class="badge bg-primary ms-1">+1 mới</span>
                        </div>
                        <div class="fs-3 fw-bold text-dark">01</div>
                    </div>
                </div>
            </div>

            <!-- CARD 2 -->
            <div class="col-6 col-lg-4">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="icon-box bg-green-soft">✅</div>
                    <div>
                        <div class="text-muted small fw-medium mb-1">
                            Đang hiệu lực
                        </div>
                        <div class="fs-3 fw-bold text-dark">01</div>
                    </div>
                </div>
            </div>

            <!-- CARD 3 -->
            <div class="col-6 col-lg-4">
                <div class="stat-card-modern d-flex align-items-center gap-3">
                    <div class="icon-box bg-orange-soft">⏳</div>
                    <div>
                        <div class="text-muted small fw-medium mb-1">
                            Sắp hết hạn <span class="text-danger small fw-bold ms-1">Chú ý</span>
                        </div>
                        <div class="fs-3 fw-bold text-dark">00</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- =================================================
             DATA TABLE
        ================================================= -->
        <div class="panel-modern">
            <!-- PANEL HEADER -->
            <div class="panel-head d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">Danh sách hợp đồng thuê</h4>
                <!-- SEARCH -->
                <div class="input-group" style="max-width: 280px;">
                    <span class="input-group-text bg-white border-end-0 text-muted border-light-subtle rounded-start-3">
                        🔎
                    </span>
                    <input type="text" class="form-control border-start-0 border-light-subtle rounded-end-3 shadow-none" placeholder="Tìm mã HĐ, tên phòng...">
                </div>
            </div>

            <!-- TABLE -->
            <div class="table-responsive">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Mã HĐ</th>
                            <th>Phòng trọ</th>
                            <th>Thời hạn</th>
                            <th>Giá thuê</th>
                            <th>Trạng thái</th>
                            <th class="text-end">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- MÃ HỢP ĐỒNG -->
                            <td>
                                <div class="fw-bold text-primary">#HD-2026-01</div>
                            </td>

                            <!-- PHÒNG -->
                            <td>
                                <div class="fw-semibold text-dark mb-1">Phòng trọ máy lạnh Q.7</div>
                                <div class="small text-muted">
                                    📐 22 m² · 📍 Nguyễn Hữu Thọ, Q.7
                                </div>
                            </td>

                            <!-- THỜI HẠN -->
                            <td>
                                <div class="text-dark fw-medium">01/02/2026</div>
                                <div class="small text-muted">Đến 01/02/2027</div>
                            </td>

                            <!-- GIÁ -->
                            <td>
                                <div class="fw-bold text-dark fs-6">2.800.000 đ</div>
                                <div class="small text-muted">/ tháng</div>
                            </td>

                            <!-- TRẠNG THÁI -->
                            <td>
                                <span class="badge-soft-success">● Đang hiệu lực</span>
                            </td>

                            <!-- THAO TÁC -->
                            <td class="text-end">
                                <a href="{{ route('tenant.contracts.show', 1) }}" class="btn btn-action btn-sm px-3 py-2">
                                    Xem chi tiết →
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>