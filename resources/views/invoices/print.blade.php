<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $invoice->invoice_code }} - {{ $invoice->contract->room->name ?? 'Phòng' }} | Trọ Ơi</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,600&display=swap" rel="stylesheet">

    <style>
        :root {
            --brand-green: #20584f;
            --brand-green-dark: #123731;
            --brand-yellow: #f5c84c;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --border-color: #cbd5e1;
            --bg-light: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Be Vietnam Pro', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            color: var(--text-dark);
            background-color: #e2e8f0;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* ===== TOOLBAR ĐIỀU KHIỂN TRÊN MÀN HÌNH ===== */
        .print-toolbar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: #1e293b;
            color: #fff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .toolbar-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 600;
        }

        .toolbar-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-tb {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            border: none;
        }

        .btn-tb-back {
            background: #334155;
            color: #f1f5f9;
        }
        .btn-tb-back:hover {
            background: #475569;
            color: #fff;
        }

        .btn-tb-print {
            background: #198754;
            color: #fff;
        }
        .btn-tb-print:hover {
            background: #157347;
        }

        .btn-tb-pdf {
            background: #0284c7;
            color: #fff;
        }
        .btn-tb-pdf:hover {
            background: #0369a1;
        }

        /* ===== TRANG GIẤY A4 ===== */
        .page-container {
            padding: 24px 0 60px 0;
            display: flex;
            justify-content: center;
        }

        .a4-paper {
            width: 210mm;
            min-height: 297mm;
            padding: 18mm 20mm;
            background: #ffffff;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border-radius: 4px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* ===== NỘI DUNG HÓA ĐƠN ===== */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid var(--brand-green);
            padding-bottom: 14px;
            margin-bottom: 16px;
        }

        .property-info h2 {
            font-size: 19px;
            font-weight: 800;
            color: var(--brand-green);
            margin-bottom: 4px;
            text-transform: uppercase;
            letter-spacing: -0.3px;
        }

        .property-info p {
            font-size: 12.5px;
            color: #475569;
            margin-bottom: 2px;
        }

        .brand-logo {
            text-align: right;
        }

        .logo-text {
            font-size: 24px;
            font-weight: 900;
            color: var(--brand-green);
            letter-spacing: -1px;
            line-height: 1;
        }

        .logo-text span {
            color: #d97706;
        }

        .logo-sub {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 600;
            margin-top: 4px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* TIÊU ĐỀ PHIẾU */
        .invoice-title-block {
            text-align: center;
            margin: 16px 0 20px 0;
        }

        .invoice-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--brand-green-dark);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .invoice-subtitle {
            font-size: 13px;
            color: #475569;
            font-weight: 600;
        }

        /* THÔNG TIN CHUNG (3 Ô) */
        .meta-strip {
            display: flex;
            background: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            margin-bottom: 18px;
            overflow: hidden;
        }

        .meta-item {
            flex: 1;
            padding: 8px 14px;
            border-right: 1px solid var(--border-color);
            font-size: 12px;
        }
        .meta-item:last-child {
            border-right: none;
        }
        .meta-label {
            color: var(--text-muted);
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }
        .meta-value {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        /* KHỐI 2 BÊN (CHỦ NHÀ & KHÁCH THUÊ) */
        .parties-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        .party-box {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-left: 3px solid var(--brand-green);
            border-radius: 6px;
            padding: 10px 14px;
            font-size: 12.5px;
        }

        .party-title {
            font-size: 11px;
            font-weight: 800;
            color: var(--brand-green);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .party-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 3px;
        }

        .party-detail {
            color: #475569;
            font-size: 12px;
            margin-bottom: 2px;
        }

        /* BẢNG CHI PHÍ */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 12.5px;
        }

        .items-table th {
            background: #f1f5f9;
            color: #1e293b;
            font-weight: 700;
            text-align: left;
            padding: 9px 10px;
            border: 1px solid #cbd5e1;
            font-size: 11.5px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .items-table td {
            padding: 8px 10px;
            border: 1px solid #cbd5e1;
            color: #334155;
            vertical-align: middle;
        }

        .items-table tr:nth-child(even) td {
            background-color: #fafbfc;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .fw-bold { font-weight: 700; }

        /* TỔNG TIỀN */
        .summary-wrapper {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 16px;
        }

        .summary-table {
            width: 320px;
            border-collapse: collapse;
            font-size: 13px;
        }

        .summary-table td {
            padding: 5px 8px;
        }

        .grand-total-row {
            border-top: 2px solid var(--brand-green);
            border-bottom: 2px solid var(--brand-green);
            background: #f0fdf4;
        }

        .grand-total-row td {
            padding: 8px 8px;
            font-size: 15px;
            font-weight: 800;
            color: var(--brand-green);
        }

        .words-box {
            background: #f8fafc;
            border: 1px dashed var(--border-color);
            border-radius: 6px;
            padding: 8px 14px;
            font-size: 12.5px;
            color: #334155;
            margin-bottom: 18px;
        }

        /* KHỐI THANH TOÁN & QR */
        .payment-status-block {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 16px;
            margin-bottom: 24px;
        }

        .payment-badge {
            display: inline-block;
            font-weight: 800;
            font-size: 12px;
            padding: 4px 10px;
            border-radius: 4px;
            text-transform: uppercase;
        }
        .badge-paid {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .badge-unpaid {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fca5a5;
        }
        .badge-pending {
            background: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }

        /* CON DẤU ĐÃ THANH TOÁN */
        .paid-stamp {
            border: 3px double #dc2626;
            color: #dc2626;
            font-size: 15px;
            font-weight: 900;
            padding: 6px 14px;
            border-radius: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transform: rotate(-4deg);
            display: inline-block;
        }

        .qr-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .qr-section img {
            width: 76px;
            height: 76px;
            border-radius: 6px;
            border: 1px solid #cbd5e1;
        }
        .qr-desc {
            font-size: 11.5px;
            color: #64748b;
            line-height: 1.4;
        }

        /* CHỮ KÝ 2 BÊN */
        .signatures-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            text-align: center;
            margin-top: 10px;
            margin-bottom: 20px;
            page-break-inside: avoid;
        }

        .sig-title {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .sig-sub {
            font-size: 11px;
            color: var(--text-muted);
            font-style: italic;
            margin-bottom: 55px;
        }

        .sig-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
        }

        /* CHÂN TRANG */
        .invoice-footer {
            border-top: 1px dashed var(--border-color);
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            line-height: 1.5;
        }

        /* ===== IN ẤN MEDIA PRINT ===== */
        @media print {
            body {
                background: #ffffff !important;
                color: #000000 !important;
                padding: 0 !important;
            }

            .print-toolbar {
                display: none !important;
            }

            .page-container {
                padding: 0 !important;
                margin: 0 !important;
            }

            .a4-paper {
                box-shadow: none !important;
                border-radius: 0 !important;
                width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            @page {
                size: A4 portrait;
                margin: 14mm 15mm 14mm 15mm;
            }

            .items-table th {
                background: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .grand-total-row {
                background: #f0fdf4 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

    <!-- THANH CÔNG CỤ (ẨN KHI IN HOẶC XUẤT PDF) -->
    <div class="print-toolbar">
        <div class="toolbar-title">
            <span>🧾</span>
            <span>Xem trước Hóa đơn in ấn &amp; Xuất PDF (#{{ $invoice->invoice_code }})</span>
        </div>

        <div class="toolbar-actions">
            <a href="{{ $backUrl ?? url()->previous() }}" class="btn-tb btn-tb-back">
                ← Quay lại
            </a>
            <button type="button" class="btn-tb btn-tb-print" onclick="window.print()">
                🖨️ In hóa đơn
            </button>
            <button type="button" class="btn-tb btn-tb-pdf" onclick="window.print()" title="Chọn 'Lưu dưới dạng PDF' trong hộp thoại in">
                📥 Lưu file PDF
            </button>
        </div>
    </div>

    <!-- KHUNG TRANG IN A4 -->
    <div class="page-container">
        <div class="a4-paper">

            <div>
                <!-- HEADER: THÔNG TIN CƠ SỞ & THƯƠNG HIỆU -->
                <div class="invoice-header">
                    <div class="property-info">
                        <h2>{{ $invoice->contract->room->property->name ?? 'Khu trọ sinh viên' }}</h2>
                        <p>📍 Địa chỉ: {{ $invoice->contract->room->property->address ?? 'Hồ Chí Minh' }}</p>
                        <p>📞 Điện thoại: {{ $invoice->contract->owner->phone ?? '---' }} · Email: {{ $invoice->contract->owner->email ?? '---' }}</p>
                    </div>

                    <div class="brand-logo">
                        <div class="logo-text">Trọ <span>Ơi</span></div>
                        <div class="logo-sub">Hệ thống quản lý lưu trú</div>
                    </div>
                </div>

                <!-- TIÊU ĐỀ HÓA ĐƠN -->
                <div class="invoice-title-block">
                    <h1 class="invoice-title">HÓA ĐƠN TIỀN TRỌ &amp; DỊCH VỤ</h1>
                    <div class="invoice-subtitle">Kỳ thu tiền: Tháng {{ $invoice->billing_month->format('m/Y') }}</div>
                </div>

                <!-- THÔNG TIN CHUNG (MÃ SỐ, NGÀY LẬP, HẠN NỘP) -->
                <div class="meta-strip">
                    <div class="meta-item">
                        <div class="meta-label">Mã hóa đơn</div>
                        <div class="meta-value">#{{ $invoice->invoice_code }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Ngày lập hóa đơn</div>
                        <div class="meta-value">{{ $invoice->issue_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Hạn chót thanh toán</div>
                        <div class="meta-value" style="color: #dc2626;">{{ $invoice->due_date->format('d/m/Y') }}</div>
                    </div>
                    <div class="meta-item">
                        <div class="meta-label">Trạng thái</div>
                        <div class="meta-value">
                            @php
                                $statusTexts = [
                                    'paid' => 'Đã thanh toán',
                                    'unpaid' => 'Chưa thanh toán',
                                    'pending' => 'Chờ xác nhận',
                                    'overdue' => 'Quá hạn',
                                    'cancelled' => 'Đã hủy',
                                ];
                                $isOverdue = in_array($invoice->status, ['unpaid', 'pending']) && $invoice->due_date < now()->toDateString();
                                $finalStatus = $isOverdue ? 'overdue' : $invoice->status;
                            @endphp
                            {{ $statusTexts[$finalStatus] ?? $finalStatus }}
                        </div>
                    </div>
                </div>

                <!-- THÔNG TIN BÊN BÁN & BÊN THUÊ -->
                <div class="parties-grid">
                    <!-- BÊN CHO THUÊ -->
                    <div class="party-box">
                        <div class="party-title">Đơn vị thu tiền (Chủ cơ sở)</div>
                        <div class="party-name">{{ $invoice->contract->owner->name ?? 'Chủ nhà trọ' }}</div>
                        <div class="party-detail">Số điện thoại: <strong>{{ $invoice->contract->owner->phone ?? '---' }}</strong></div>
                        <div class="party-detail">Tài khoản: <strong>{{ config('services.sepay.bank_name', 'Ngân hàng') }} - {{ config('services.sepay.bank_account', '---') }}</strong></div>
                        <div class="party-detail">Chủ TK: <strong>{{ config('services.sepay.account_name', 'TRO OI') }}</strong></div>
                    </div>

                    <!-- BÊN THUÊ PHÒNG -->
                    <div class="party-box">
                        <div class="party-title">Bên thanh toán (Khách thuê)</div>
                        <div class="party-name">{{ $invoice->contract->tenant->name ?? 'Người thuê' }}</div>
                        <div class="party-detail">Phòng thuê: <strong>{{ $invoice->contract->room->name ?? '' }}</strong> (Mã phòng: {{ $invoice->contract->room->room_code ?? '---' }})</div>
                        <div class="party-detail">Số điện thoại: <strong>{{ $invoice->contract->tenant->phone ?? '---' }}</strong></div>
                        <div class="party-detail">Mã hợp đồng: <strong>{{ $invoice->contract->contract_code ?? '---' }}</strong></div>
                    </div>
                </div>

                <!-- BẢNG CHI TIẾT CÁC HẠNG MỤC -->
                <table class="items-table">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 38px;">STT</th>
                            <th>Khoản mục chi phí</th>
                            <th class="text-center" style="width: 100px;">Chỉ số / Diễn giải</th>
                            <th class="text-center" style="width: 75px;">Số lượng</th>
                            <th class="text-right" style="width: 95px;">Đơn giá</th>
                            <th class="text-right" style="width: 115px;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoice->items as $index => $item)
                            @php
                                $detail = '---';
                                if ($item->type === 'electricity' && $invoice->utilityReading) {
                                    $detail = $invoice->utilityReading->electricity_old . ' → ' . $invoice->utilityReading->electricity_new;
                                } elseif ($item->type === 'water' && $invoice->utilityReading) {
                                    $detail = $invoice->utilityReading->water_old . ' → ' . $invoice->utilityReading->water_new;
                                } elseif ($item->type === 'rent') {
                                    $detail = 'Tháng ' . $invoice->billing_month->format('m/Y');
                                }
                            @endphp
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>
                                    <span class="fw-bold">{{ $item->description }}</span>
                                </td>
                                <td class="text-center" style="color: #64748b; font-size: 11.5px;">{{ $detail }}</td>
                                <td class="text-center">{{ rtrim(rtrim(number_format((float) $item->quantity, 2), '0'), '.') }}</td>
                                <td class="text-right">{{ number_format((float) $item->unit_price) }} đ</td>
                                <td class="text-right fw-bold" style="color: #0f172a;">{{ number_format((float) $item->amount) }} đ</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">Chưa có hạng mục tính phí nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- BẢNG TỔNG KẾT TIỀN -->
                <div class="summary-wrapper">
                    <table class="summary-table">
                        <tr>
                            <td class="text-left text-muted">Cộng tiền hàng (Tạm tính):</td>
                            <td class="text-right fw-bold">{{ number_format((float) $invoice->subtotal) }} đ</td>
                        </tr>
                        @if((float) $invoice->discount > 0)
                            <tr>
                                <td class="text-left text-muted">Giảm giá / Ưu đãi:</td>
                                <td class="text-right fw-bold text-success">- {{ number_format((float) $invoice->discount) }} đ</td>
                            </tr>
                        @endif
                        <tr class="grand-total-row">
                            <td class="text-left">TỔNG CỘNG THANH TOÁN:</td>
                            <td class="text-right">{{ number_format((float) $invoice->total) }} đ</td>
                        </tr>
                    </table>
                </div>

                <!-- SỐ TIỀN BẰNG CHỮ -->
                <div class="words-box">
                    <strong>Số tiền viết bằng chữ:</strong>
                    <em>{{ $invoice->totalInWords() }}.</em>
                </div>

                <!-- TÌNH TRẠNG THANH TOÁN VÀ MÃ QR -->
                <div class="payment-status-block">
                    @if($invoice->status === 'paid')
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="payment-badge badge-paid">✓ ĐÃ HOÀN TẤT THANH TOÁN</span>
                            </div>
                            <div style="font-size: 12px; color: #475569;">
                                Hóa đơn này đã được đối soát và ghi nhận thanh toán đầy đủ vào hệ thống.
                                @if($invoice->payments->where('status', 'success')->isNotEmpty())
                                    <br>Mã GD: <strong>{{ $invoice->payments->where('status', 'success')->first()->payment_code ?? '---' }}</strong>
                                    · Lúc: {{ $invoice->payments->where('status', 'success')->first()->paid_at?->format('H:i d/m/Y') ?? '---' }}
                                @endif
                            </div>
                        </div>

                        <div class="paid-stamp">
                            ★ ĐÃ THANH TOÁN ★
                        </div>
                    @else
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="payment-badge {{ $isOverdue ? 'badge-unpaid' : 'badge-pending' }}">
                                    {{ $isOverdue ? '⚠️ QUÁ HẠN THANH TOÁN' : '⏳ CHƯA THANH TOÁN' }}
                                </span>
                            </div>
                            <div style="font-size: 12px; color: #64748b;">
                                Quý khách vui lòng quét mã VietQR bên cạnh để thanh toán tự động,<br>hoặc nộp tiền trực tiếp cho chủ nhà trước ngày {{ $invoice->due_date->format('d/m/Y') }}.
                            </div>
                        </div>

                        <!-- MÃ QR THANH TOÁN TỰ ĐỘNG -->
                        <div class="qr-section">
                            <img src="https://qr.sepay.vn/img?acc={{ config('services.sepay.bank_account') }}&bank={{ config('services.sepay.bank_name') }}&amount={{ round((float)$invoice->total) }}&des={{ $invoice->invoice_code }}" alt="Mã QR thanh toán">
                            <div class="qr-desc">
                                <strong style="color: #0f172a;">Quét QR nộp tiền</strong><br>
                                Ngân hàng: {{ config('services.sepay.bank_name') }}<br>
                                Cú pháp: <strong>{{ $invoice->invoice_code }}</strong>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- CHỮ KÝ XÁC NHẬN 2 BÊN -->
                <div class="signatures-grid">
                    <div>
                        <div class="sig-title">Người nộp tiền</div>
                        <div class="sig-sub">(Ký và ghi rõ họ tên)</div>
                        <div class="sig-name">{{ $invoice->contract->tenant->name ?? 'Người thuê' }}</div>
                    </div>

                    <div>
                        <div class="sig-title">Người lập phiếu / Chủ nhà</div>
                        <div class="sig-sub">(Ký, đóng dấu nếu có)</div>
                        <div class="sig-name">{{ $invoice->contract->owner->name ?? 'Chủ nhà trọ' }}</div>
                    </div>
                </div>
            </div>

            <!-- CHÂN TRANG HÓA ĐƠN -->
            <div class="invoice-footer">
                <div>Phiếu thu được khởi tạo và lưu trữ trên Hệ thống Quản lý Nhà trọ Trọ Ơi (trooi.vn).</div>
                <div>Mọi thắc mắc về hóa đơn, quý khách vui lòng liên hệ Ban quản lý trong vòng 03 ngày kể từ ngày phát hành phiếu.</div>
                <div style="font-size: 10px; margin-top: 2px;">Thời gian in: {{ now()->format('d/m/Y H:i:s') }}</div>
            </div>

        </div>
    </div>

</body>
</html>

