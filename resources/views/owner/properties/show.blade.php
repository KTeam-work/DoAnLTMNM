
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết nhà trọ | Trọ Ơi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

    <style>
        body {
            background: #F9F9F6;
            color: #1F2937;
        }

        .detail-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
            margin-bottom: 24px;
        }

        .page-title {
            color: #005D4B;
            font-weight: 700;
        }

        .info-label {
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .btn-owner {
            background: #005D4B;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 18px;
            text-decoration: none;
            display: inline-block;
        }

        .btn-owner:hover {
            background: #004638;
            color: white;
        }

        .btn-back {
            background: #E6F0EB;
            color: #005D4B;
            border-radius: 8px;
            padding: 10px 18px;
            text-decoration: none;
            display: inline-block;
        }

        .status-active {
            color: #087443;
            background: #E6F5EC;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
        }

        .status-inactive {
            color: #92400E;
            background: #FEF3C7;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container py-4 py-md-5">

        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="page-title mb-1">Chi tiết nhà trọ</h2>
                <p class="text-secondary mb-0">
                    Xem thông tin và danh sách phòng thuộc nhà trọ.
                </p>
            </div>

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ route('owner.properties.index') }}" class="btn-back">
                    Quay lại
                </a>

                <a href="{{ route('owner.properties.edit', $property->id) }}"
                   class="btn-owner">
                    Sửa thông tin
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="detail-card">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h4 class="fw-bold mb-2">{{ $property->name }}</h4>

                    <p class="text-secondary mb-0">
                        {{ $property->address }},
                        {{ $property->ward }},
                        {{ $property->district }}
                    </p>
                </div>

                <span class="{{ $property->status === 'active' ? 'status-active' : 'status-inactive' }}">
                    {{ $property->status === 'active' ? 'Đang hoạt động' : 'Tạm ngưng' }}
                </span>
            </div>

            <hr>

            <div class="row g-4">
                <div class="col-md-6">
                    <div class="info-label">Tên nhà trọ</div>
                    <div class="info-value">{{ $property->name }}</div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Loại hình</div>
                    <div class="info-value">
                        @switch($property->type)
                            @case('rooming_house')
                                Nhà trọ
                                @break
                            @case('apartment')
                                Căn hộ
                                @break
                            @case('house')
                                Nhà nguyên căn
                                @break
                            @default
                                {{ $property->type }}
                        @endswitch
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Địa chỉ</div>
                    <div class="info-value">{{ $property->address }}</div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Phường / xã</div>
                    <div class="info-value">{{ $property->ward }}</div>
                </div>

                <div class="col-md-3">
                    <div class="info-label">Quận / huyện</div>
                    <div class="info-value">{{ $property->district }}</div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Vĩ độ</div>
                    <div class="info-value">{{ $property->latitude ?? 'Chưa cập nhật' }}</div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Kinh độ</div>
                    <div class="info-value">{{ $property->longitude ?? 'Chưa cập nhật' }}</div>
                </div>

                <div class="col-12">
                    <div class="info-label">Mô tả</div>
                    <div class="info-value" style="font-weight: 400; white-space: pre-line;">
                        {{ $property->description ?: 'Chưa có mô tả cho nhà trọ này.' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Ngày tạo</div>
                    <div class="info-value">
                        {{ $property->created_at?->format('d/m/Y H:i') ?? '—' }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="info-label">Cập nhật lần cuối</div>
                    <div class="info-value">
                        {{ $property->updated_at?->format('d/m/Y H:i') ?? '—' }}
                    </div>
                </div>
            </div>
        </div>

        <div class="detail-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Danh sách phòng</h5>
                    <p class="text-secondary mb-0">
                        Tổng số: {{ $property->rooms->count() }} phòng
                    </p>
                </div>

                <a href="{{ route('owner.rooms.index', ['property_id' => $property->id]) }}"
                   class="btn-owner">
                    Quản lý phòng
                </a>
            </div>

            @if ($property->rooms->isEmpty())
                <div class="text-center py-4 text-secondary">
                    Nhà trọ này chưa có phòng nào.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Mã phòng</th>
                                <th>Tên phòng</th>
                                <th>Diện tích</th>
                                <th>Giá thuê</th>
                                <th>Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($property->rooms as $room)
                                <tr>
                                    <td>{{ $room->room_code ?? '—' }}</td>
                                    <td>{{ $room->name }}</td>
                                    <td>{{ number_format($room->area, 2, ',', '.') }} m²</td>
                                    <td>{{ number_format($room->price, 0, ',', '.') }} đ/tháng</td>
                                    <td>
                                        @switch($room->status)
                                            @case('available')
                                                Đang trống
                                                @break
                                            @case('pending')
                                                Chờ xác nhận
                                                @break
                                            @case('rented')
                                                Đã cho thuê
                                                @break
                                            @case('maintenance')
                                                Đang bảo trì
                                                @break
                                            @case('hidden')
                                                Đang ẩn
                                                @break
                                            @default
                                                {{ $room->status }}
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

    </div>
</body>
</html>
