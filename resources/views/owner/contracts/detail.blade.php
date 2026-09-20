<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chi tiết hợp đồng</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <main class="container py-5" style="max-width: 900px">
        <a href="{{ route('owner.contracts.index') }}" class="text-decoration-none">← Quay lại danh sách</a>
        
        <h1 class="h3 my-4">{{ $contract->contract_code }}</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <h2 class="h5">Phòng</h2>
                        <p class="mb-1"><strong>{{ $contract->room->name }}</strong></p>
                        <p class="text-muted">{{ $contract->room->property->name }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h2 class="h5">Người thuê</h2>
                        <p class="mb-1"><strong>{{ $contract->tenant->name }}</strong></p>
                        <p class="text-muted">{{ $contract->tenant->phone }} · {{ $contract->tenant->email }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h2 class="h5">Thời hạn</h2>
                        <p>{{ $contract->start_date->format('d/m/Y') }} – {{ $contract->end_date->format('d/m/Y') }}</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h2 class="h5">Thanh toán</h2>
                        <p class="mb-1">Thuê: {{ number_format((float)$contract->rent) }} đ</p>
                        <p>Cọc: {{ number_format((float)$contract->deposit) }} đ</p>
                    </div>
                    
                    <div class="col-md-6">
                        <h2 class="h5">Chốt số ban đầu</h2>
                        <p class="mb-1">Điện: {{ number_format((float)$contract->initial_electricity_reading) }}</p>
                        <p>Nước: {{ number_format((float)$contract->initial_water_reading) }}</p>
                    </div>

                    <div class="col-12">
                        <h2 class="h5">Người ở cùng ({{ $contract->members->count() }} + 1 đại diện / tối đa {{ $contract->room->max_people ?? '?' }})</h2>
                        <ul class="mb-3">
                            <li><strong>{{ $contract->tenant->name }}</strong> (đại diện hợp đồng)</li>
                            @forelse($contract->members as $member)
                                <li>
                                    {{ $member->name }}{{ $member->relationship ? ' (' . $member->relationship . ')' : '' }}{{ $member->phone ? ' · ' . $member->phone : '' }}
                                    <form method="POST" action="{{ route('owner.contracts.members', $contract) }}" class="d-inline ms-2">
                                        @csrf
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Xóa người ở cùng này?')">Xóa</button>
                                    </form>
                                </li>
                            @empty
                                <li>Chưa có người ở cùng.</li>
                            @endforelse
                        </ul>

                        @if($contract->status !== 'terminated')
                        <form method="POST" action="{{ route('owner.contracts.members', $contract) }}" class="row g-2 align-items-end">
                            @csrf
                            <input type="hidden" name="action" value="add">
                            <div class="col-md-3">
                                <label class="form-label">Họ tên *</label>
                                <input type="text" name="name" value="{{ old('name') }}" class="form-control" required maxlength="100">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">CCCD *</label>
                                <input type="text" name="identity_card" value="{{ old('identity_card') }}" class="form-control" required maxlength="20">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">SĐT</label>
                                <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" maxlength="20">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Quan hệ</label>
                                <input type="text" name="relationship" value="{{ old('relationship') }}" class="form-control" maxlength="50" placeholder="VD: bạn cùng phòng">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-success w-100">+ Thêm</button>
                            </div>
                        </form>
                        @else
                            <p class="text-muted mb-0">Hợp đồng đã thanh lý.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>