<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quản lý hợp đồng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Quản lý hợp đồng</h1>
                <p class="text-muted mb-0">Chỉ hiển thị hợp đồng của khu trọ bạn quản lý.</p>
            </div>
            <a class="btn btn-success" href="{{ route('owner.contracts.create') }}">+ Tạo hợp đồng</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow-sm">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Mã hợp đồng</th>
                            <th>Phòng</th>
                            <th>Người thuê</th>
                            <th>Thời hạn</th>
                            <th>Trạng thái</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($contracts as $contract)
                            <tr>
                                <td>{{ $contract->contract_code }}</td>
                                <td>
                                    <strong>{{ $contract->room->name }}</strong><br>
                                    <small class="text-muted">{{ $contract->room->property->name }}</small>
                                </td>
                                <td>
                                    {{ $contract->tenant->name }}<br>
                                    <small class="text-muted">{{ $contract->tenant->phone }}</small>
                                </td>
                                <td>
                                    {{ $contract->start_date->format('d/m/Y') }} – {{ $contract->end_date->format('d/m/Y') }}
                                </td>
                                <td>
                                    {{ ['draft' => 'Nháp', 'active' => 'Đang hiệu lực', 'expired' => 'Hết hạn', 'terminated' => 'Đã thanh lý'][$contract->status] ?? $contract->status }}
                                </td>
                                <td>
                                    <a href="{{ route('owner.contracts.show', $contract) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">Chưa có hợp đồng nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>