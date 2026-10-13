
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết phòng | Trọ Ơi</title>

    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        :root {
            --primary: #005D4B;
            --primary-light: #E6F0EB;
            --background: #F5F7F5;
            --text: #1F2937;
            --muted: #6B7280;
            --border: #E5E7EB;
            --yellow: #FFB800;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Be Vietnam Pro', sans-serif;
            background: var(--background);
            color: var(--text);
        }

        .navbar {
            height: 72px;
            background: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 6%;
            border-bottom: 1px solid var(--border);
        }

        .brand {
            color: var(--primary);
            font-size: 25px;
            font-weight: 800;
            text-decoration: none;
        }

        .brand span {
            color: var(--yellow);
        }

        .nav-label {
            color: var(--muted);
            font-size: 14px;
        }

        .container {
            max-width: 1150px;
            margin: 32px auto;
            padding: 0 22px 45px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 22px;
        }

        .breadcrumb a {
            color: var(--primary);
            text-decoration: none;
        }

        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .page-header p {
            color: var(--muted);
            font-size: 14px;
        }

        .actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 11px 17px;
            border-radius: 9px;
            text-decoration: none;
            font-family: inherit;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
        }

        .btn-back {
            color: var(--text);
            background: white;
            border: 1px solid var(--border);
        }

        .btn-edit {
            color: white;
            background: var(--primary);
        }

        .btn:hover {
            opacity: .88;
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            margin-bottom: 22px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .025);
        }

        .card-header {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .icon-box {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--primary-light);
            color: var(--primary);
            display: grid;
            place-items: center;
            font-size: 17px;
        }

        .card-header h2 {
            font-size: 16px;
        }

        .card-header p {
            color: var(--muted);
            font-size: 12px;
            margin-top: 4px;
        }

        .card-body {
            padding: 24px;
        }

        .room-summary {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
        }

        .room-code {
            display: inline-block;
            background: var(--primary-light);
            color: var(--primary);
            border-radius: 6px;
            padding: 6px 10px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .room-name {
            font-size: 23px;
            margin-bottom: 10px;
        }

        .room-address {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.8;
        }

        .status {
            display: inline-block;
            padding: 8px 13px;
            border-radius: 20px;
            background: #E6F4EA;
            color: #187443;
            font-size: 12px;
            font-weight: 700;
        }

        .status.unavailable {
            background: #FEECEC;
            color: #B42318;
        }

        .status.other {
            background: #FFF4D6;
            color: #946200;
        }

        .price-panel {
            margin-top: 24px;
            padding: 20px;
            background: #F8FAF8;
            border: 1px solid #E8EEE9;
            border-radius: 12px;
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .price-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 9px;
        }

        .price-value {
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
            overflow-wrap: anywhere;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 24px 35px;
        }

        .detail-item {
            min-width: 0;
        }

        .detail-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 8px;
        }

        .detail-value {
            font-size: 14px;
            font-weight: 600;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .description {
            font-size: 14px;
            line-height: 1.9;
            color: #4B5563;
            white-space: pre-line;
            overflow-wrap: anywhere;
        }

        .alert {
            padding: 13px 16px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .alert-success {
            background: #E6F4EA;
            color: #187443;
        }

        .alert-error {
            background: #FEECEC;
            color: #B42318;
        }

        @media (max-width: 650px) {
            .navbar {
                padding: 0 20px;
            }

            .nav-label {
                display: none;
            }

            .container {
                margin-top: 22px;
                padding: 0 14px 30px;
            }

            .page-header h1 {
                font-size: 22px;
            }

            .card-header,
            .card-body {
                padding: 18px;
            }

            .price-panel {
                grid-template-columns: 1fr;
                gap: 18px;
            }

            .detail-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .room-name {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="{{ route('owner.rooms.index') }}" class="brand">
            TRỌ <span>ƠI</span>
        </a>
        <div class="nav-label">
            <i class="fa-solid fa-house"></i>
            &nbsp; Quản lý phòng trọ
        </div>
    </nav>

    <main class="container">
        <div class="breadcrumb">
            <a href="{{ route('owner.rooms.index') }}">Quản lý phòng</a>
            &nbsp; / &nbsp; Chi tiết phòng
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                {{ session('error') }}
            </div>
        @endif

        <div class="page-header">
            <div>
                <h1>Chi tiết phòng trọ</h1>
                <p>Xem thông tin và tình trạng phòng đang quản lý.</p>
            </div>

            <div class="actions">
                <a href="{{ route('owner.rooms.index') }}" class="btn btn-back">
                    <i class="fa-solid fa-arrow-left"></i>
                    Quay lại
                </a>

                <a href="{{ route('owner.rooms.edit', $room) }}" class="btn btn-edit">
                    <i class="fa-solid fa-pen-to-square"></i>
                    Sửa phòng
                </a>
            </div>
        </div>

        {{-- THÔNG TIN TỔNG QUAN --}}
        <section class="card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div>
                    <h2>Tổng quan phòng</h2>
                    <p>Thông tin nhận diện và giá phòng</p>
                </div>
            </div>

            <div class="card-body">
                <div class="room-summary">
                    <div>
                        <span class="room-code">
                            Mã phòng: {{ $room->room_code ?? $room->id }}
                        </span>

                        <h2 class="room-name">
                            {{ $room->name ?? 'Phòng ' . $room->id }}
                        </h2>

                        <div class="room-address">
                            <i class="fa-solid fa-building"></i>
                            {{ $room->property?->name ?? 'Chưa cập nhật nhà trọ' }}
                        </div>
                    </div>

                    @php
                        $roomStatus = strtolower((string) ($room->status ?? ''));
                    @endphp

                    @if (in_array($roomStatus, ['available', 'vacant', 'trống']))
                        <span class="status">Đang trống</span>
                    @elseif (in_array($roomStatus, ['occupied', 'rented', 'đã thuê']))
                        <span class="status unavailable">Đã cho thuê</span>
                    @else
                        <span class="status other">
                            {{ $room->status ?? 'Chưa cập nhật trạng thái' }}
                        </span>
                    @endif
                </div>

                <div class="price-panel">
                    <div>
                        <div class="price-label">Giá thuê mỗi tháng</div>
                        <div class="price-value">
                            @if (is_numeric($room->price ?? null))
                                {{ number_format((float) $room->price, 0, ',', '.') }} đ
                            @else
                                Chưa cập nhật
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="price-label">Tiền đặt cọc</div>
                        <div class="price-value">
                            @if (is_numeric($room->deposit ?? null))
                                {{ number_format((float) $room->deposit, 0, ',', '.') }} đ
                            @else
                                Chưa cập nhật
                            @endif
                        </div>
                    </div>

                    <div>
                        <div class="price-label">Diện tích</div>
                        <div class="price-value">
                            {{ $room->area !== null ? $room->area . ' m²' : 'Chưa cập nhật' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- THÔNG TIN CHI TIẾT --}}
        <section class="card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <h2>Thông tin chi tiết</h2>
                    <p>Các thuộc tính của phòng trọ</p>
                </div>
            </div>

            <div class="card-body">
                <div class="detail-grid">
                    <div class="detail-item">
                        <div class="detail-label">Mã phòng</div>
                        <div class="detail-value">
                            {{ $room->room_code ?? $room->id }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tên phòng</div>
                        <div class="detail-value">
                            {{ $room->name ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Nhà trọ / Bất động sản</div>
                        <div class="detail-value">
                            {{ $room->property?->name ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Diện tích</div>
                        <div class="detail-value">
                            {{ $room->area !== null ? $room->area . ' m²' : 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Số người tối đa</div>
                        <div class="detail-value">
                            {{ $room->max_people ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Tầng</div>
                        <div class="detail-value">
                            {{ $room->floor ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Ngày tạo</div>
                        <div class="detail-value">
                            {{ $room->created_at?->format('d/m/Y H:i') ?? 'Chưa cập nhật' }}
                        </div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-label">Cập nhật lần cuối</div>
                        <div class="detail-value">
                            {{ $room->updated_at?->format('d/m/Y H:i') ?? 'Chưa cập nhật' }}
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- MÔ TẢ PHÒNG --}}
        <section class="card">
            <div class="card-header">
                <div class="icon-box">
                    <i class="fa-solid fa-align-left"></i>
                </div>
                <div>
                    <h2>Mô tả phòng</h2>
                    <p>Thông tin giới thiệu về phòng trọ</p>
                </div>
            </div>

            <div class="card-body">
                <div class="description">{{ $room->description ?: 'Chưa có mô tả cho phòng này.' }}</div>
            </div>
        </section>

        <div class="actions">
            <a href="{{ route('owner.rooms.index') }}" class="btn btn-back">
                <i class="fa-solid fa-arrow-left"></i>
                Quay lại danh sách
            </a>

            <a href="{{ route('owner.rooms.edit', $room) }}" class="btn btn-edit">
                <i class="fa-solid fa-pen-to-square"></i>
                Chỉnh sửa thông tin
            </a>
        </div>
    </main>
</body>
</html>
