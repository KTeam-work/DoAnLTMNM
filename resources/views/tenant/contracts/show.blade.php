<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Chi tiết hợp đồng | Trọ Ơi</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- CSS chung -->
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        * {
            box-sizing: border-box;
        }

        /* Đã xóa màu nền và font chữ fix cứng ở đây để đồng bộ với styles.css chung */

        .container-page {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px 50px;
        }

        /* TOP */
        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .back-link {
            color: #555;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link:hover {
            color: #dc3545;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
        }

        .btn-custom {
            padding: 8px 14px;
            border-radius: 7px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .btn-pdf {
            border: 1px solid #dc3545;
            color: #dc3545;
        }

        .btn-pdf:hover {
            background: #dc3545;
            color: white;
        }

        .btn-liquidation {
            border: 1px solid #dc3545;
            color: #dc3545;
        }

        .btn-liquidation:hover {
            background: #dc3545;
            color: white;
        }

        /* HEADER */
        .page-header {
            background: white;
            border-radius: 10px;
            padding: 22px 25px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }

        .page-title {
            margin: 0 0 7px;
            font-size: 25px;
            font-weight: 700;
        }

        .text-brand {
            color: #dc3545;
        }

        .page-desc {
            color: #6c757d;
            font-size: 14px;
        }

        .badge-status {
            display: inline-block;
            padding: 7px 13px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }

        .resolved {
            background: #dff6e7;
            color: #198754;
        }

        /* PANEL */
        .panel {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 10px;
            overflow: hidden;
        }

        .panel-head {
            padding: 16px 20px;
            border-bottom: 1px solid #eee;
        }

        .panel-title {
            margin: 0;
            font-size: 17px;
            font-weight: 700;
        }

        /* INFO */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
            padding: 22px;
        }

        .cell-sub {
            color: #8a8f98;
            font-size: 13px;
            margin-bottom: 7px;
        }

        .info-value {
            font-size: 15px;
            font-weight: 600;
        }

        .price {
            color: #dc3545;
            font-size: 16px;
        }

        /* TABLE */
        .table-wrapper {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #fafafa;
            color: #6c757d;
            font-size: 13px;
            font-weight: 600;
            padding: 14px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .data-table td {
            padding: 16px 20px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .text-end {
            text-align: right !important;
        }

        /* RIGHT COLUMN */
        .owner-box,
        .member-box {
            padding: 20px;
        }

        .person {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 18px;
        }

        .avatar-sm {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #ffe5e8;
            color: #dc3545;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
        }

        .person-name {
            font-weight: 700;
            font-size: 15px;
            margin-bottom: 4px;
        }

        .call-button {
            display: block;
            width: 100%;
            text-align: center;
            text-decoration: none;
            border: 1px solid #dc3545;
            color: #dc3545;
            border-radius: 7px;
            padding: 9px;
            font-size: 14px;
            transition: 0.2s;
        }

        .call-button:hover {
            background: #dc3545;
            color: white;
        }

        .member-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .top-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .action-buttons {
                width: 100%;
            }

            .btn-custom {
                flex: 1;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start !important;
                gap: 15px;
            }

            .info-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 576px) {
            .container-page {
                padding: 20px 12px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }

            .page-title {
                font-size: 21px;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<div class="container-page">

    <!-- BREADCRUMB / ACTION -->
    <div class="top-bar">
        <a href="{{ route('tenant.contracts.index') }}" class="back-link">
            ← Quay lại danh sách hợp đồng
        </a>

        <div class="action-buttons">
            <button class="btn-custom btn-pdf">
                📥 Tải file PDF
            </button>
            <button class="btn-custom btn-liquidation">
                ⚠️ Yêu cầu thanh lý
            </button>
        </div>
    </div>


    <!-- PAGE HEADER -->
    <div class="page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="page-title">
                Chi tiết hợp đồng
                <span class="text-brand">#HD-2026-01</span>
            </h2>
            <div class="page-desc">
                Thông tin chi tiết về phòng thuê, chi phí cố định và các dịch vụ đi kèm.
            </div>
        </div>

        <span class="badge-status resolved">
            Đang hiệu lực
        </span>
    </div>


    <!-- MAIN CONTENT -->
    <div class="row g-3">

        <!-- LEFT -->
        <div class="col-lg-8">

            <!-- THÔNG TIN PHÒNG -->
            <div class="panel mb-3">
                <div class="panel-head">
                    <h3 class="panel-title">
                        Thông tin phòng & Giá thuê
                    </h3>
                </div>

                <div class="info-grid">
                    <div>
                        <div class="cell-sub">Phòng thuê</div>
                        <div class="info-value">
                            Phòng trọ máy lạnh Q.7
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Tiền thuê hàng tháng</div>
                        <div class="info-value price">
                            2.800.000 đ/tháng
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Tiền cọc</div>
                        <div class="info-value">
                            2.800.000 đ
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Ngày bắt đầu</div>
                        <div class="info-value">
                            01/02/2026
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Ngày kết thúc</div>
                        <div class="info-value">
                            01/02/2027
                        </div>
                    </div>

                    <div>
                        <div class="cell-sub">Kỳ thanh toán</div>
                        <div class="info-value">
                            Hàng tháng (Ngày 05)
                        </div>
                    </div>
                </div>
            </div>

            <!-- DỊCH VỤ -->
            <div class="panel">
                <div class="panel-head">
                    <h3 class="panel-title">
                        Bảng giá Dịch vụ & Điện nước
                    </h3>
                </div>

                <div class="table-wrapper">
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
                                <td class="text-end">
                                    <strong>3.500 đ / kWh</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>💧 Nước sinh hoạt</td>
                                <td>Theo số người ở</td>
                                <td class="text-end">
                                    <strong>100.000 đ / người</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>🌐 Internet / Wifi</td>
                                <td>Cố định theo phòng</td>
                                <td class="text-end">
                                    <strong>50.000 đ / phòng</strong>
                                </td>
                            </tr>
                            <tr>
                                <td>🧹 Vệ sinh & Rác</td>
                                <td>Theo số người ở</td>
                                <td class="text-end">
                                    <strong>30.000 đ / người</strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-4">

            <!-- CHỦ TRỌ -->
            <div class="panel mb-3">
                <div class="panel-head">
                    <h3 class="panel-title">
                        Thông tin Chủ trọ
                    </h3>
                </div>

                <div class="owner-box">
                    <div class="person">
                        <div class="avatar-sm">
                            MT
                        </div>
                        <div>
                            <div class="person-name">
                                Minh Tuấn
                            </div>
                            <div class="cell-sub">
                                SĐT: 0901 234 567
                            </div>
                        </div>
                    </div>

                    <a href="tel:0901234567" class="call-button">
                        📞 Gọi cho chủ trọ
                    </a>
                </div>
            </div>

            <!-- THÀNH VIÊN -->
            <div class="panel">
                <div class="panel-head">
                    <div class="member-header">
                        <h3 class="panel-title">
                            Thành viên phòng
                        </h3>
                        <span class="cell-sub mb-0">
                            1/2 Người
                        </span>
                    </div>
                </div>

                <div class="member-box">
                    <div class="person mb-0">
                        <div class="avatar-sm">
                            TH
                        </div>
                        <div>
                            <div class="person-name">
                                Nguyễn Tấn Hiệu
                            </div>
                            <div class="cell-sub">
                                Người đại diện hợp đồng
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

</body>
</html>