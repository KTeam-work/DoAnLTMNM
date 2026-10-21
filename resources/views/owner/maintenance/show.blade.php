

@extends('Layout.landlord')

@section('title', 'Quản lý sửa chữa | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/maintenance/show.css') }}">
@endpush

@section('content')


<main class="page-wrap">
  <a href="{{ route('owner.maintenance.index') }}" class="back-link">← Quay lại danh sách sửa chữa</a>
  <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 my-3">
    <h1 class="h4 fw-bold mb-0" style="color: var(--green-dark);">#TCK-{{ $maintenance->id }} — {{ $maintenance->title }}</h1>
    @php $statusLabels = ['pending' => 'Chờ xử lý', 'processing' => 'Đang sửa', 'completed' => 'Hoàn thành', 'rejected' => 'Đã từ chối']; @endphp
    <span class="badge-status {{ $maintenance->status }}">{{ $statusLabels[$maintenance->status] ?? $maintenance->status }}</span>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <div class="panel">
    <h5>Thông tin yêu cầu</h5>
    <div class="row g-3" style="font-size: .9rem;">
      <div class="col-md-6">Phòng: <strong>{{ $maintenance->room->name ?? '' }}</strong></div>
      <div class="col-md-6">Người thuê: <strong>{{ $maintenance->tenant->name ?? '' }}</strong> ({{ $maintenance->tenant->phone ?? '' }})</div>
      <div class="col-md-6">Mức độ: <strong>{{ ['low' => 'Thấp', 'medium' => 'Trung bình', 'high' => 'Cao', 'urgent' => 'Khẩn cấp'][$maintenance->priority] ?? $maintenance->priority }}</strong></div>
      <div class="col-md-6">Gửi lúc: {{ $maintenance->created_at->format('H:i d/m/Y') }}</div>
      @if($maintenance->contract)
      <div class="col-md-6">Hợp đồng: <strong>{{ $maintenance->contract->contract_code }}</strong></div>
      @endif
      <div class="col-12">Mô tả: {{ $maintenance->description }}</div>
      @if($maintenance->image)
      <div class="col-12">Ảnh đính kèm:<br><img src="{{ asset('storage/' . $maintenance->image) }}" alt="Ảnh hiện trường" style="max-width: 320px; border-radius: 12px; border: 1px solid var(--border);"></div>
      @endif
      @if($maintenance->owner_note)
      <div class="col-12">Ghi chú của bạn: {{ $maintenance->owner_note }}</div>
      @endif
    </div>
  </div>

  @if(in_array($maintenance->status, ['pending', 'processing']))
  <div class="panel">
    <h5>Cập nhật xử lý</h5>
    <form action="{{ route('owner.maintenance.status', $maintenance->id) }}" method="POST" class="row g-3">
      @csrf
      @method('PUT')
      <div class="col-md-5">
        <label class="form-label small fw-bold">Trạng thái mới</label>
        <select name="status" class="form-select" required>
          @if($maintenance->status === 'pending')
            <option value="processing">Đang sửa</option>
          @endif
          @if($maintenance->status === 'processing')
            <option value="completed">Hoàn thành</option>
          @endif
          <option value="rejected">Từ chối</option>
        </select>
      </div>
      <div class="col-md-7">
        <label class="form-label small fw-bold">Ghi chú cho người thuê</label>
        <input type="text" name="owner_note" value="{{ old('owner_note', $maintenance->owner_note) }}" class="form-control" placeholder="VD: Thợ sẽ qua lúc 17h hôm nay">
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-success">Lưu cập nhật</button>
      </div>
    </form>
  </div>
  @endif
</main>


@endsection