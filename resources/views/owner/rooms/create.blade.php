
@extends('Layout.landlord')

@section('title', 'Tạo Phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/created.css') }}">
@endpush

@section('content')



  <main class="page-wrap">
    <a href="{{ route('owner.rooms.index') }}" class="back-link">← Quay lại quản lý phòng</a>
    <h1 class="page-title my-3">Tạo phòng mới</h1>
    @if ($properties->isEmpty())
    <div class="alert alert-warning">Bạn chưa có khu trọ nào. Hãy tạo khu trọ trước khi thêm phòng.</div>
    @else
    <div class="panel">
      <form method="POST" action="{{ route('owner.rooms.store') }}">
        @csrf
        @if ($errors->any())<div class="alert alert-danger">
          <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>@endif
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Khu trọ</label><select name="property_id" class="form-select" required>
              <option value="">Chọn khu trọ</option>@foreach ($properties as $property)<option value="{{ $property->id }}" @selected(old('property_id')==$property->id)>{{ $property->name }}</option>@endforeach
            </select></div>
          <div class="col-md-6"><label class="form-label">Tên phòng</label><input name="name" value="{{ old('name') }}" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">Mã phòng</label><input name="room_code" value="{{ old('room_code') }}" class="form-control"></div>
          <div class="col-md-6"><label class="form-label">Trạng thái</label><select name="status" class="form-select">
              <option value="available">Còn trống</option>
              <option value="maintenance">Bảo trì</option>
              <option value="hidden">Ẩn</option>
            </select></div>
          <div class="col-md-4"><label class="form-label">Diện tích (m²)</label><input name="area" type="number" min="0.01" step="0.01" value="{{ old('area') }}" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label">Giá thuê</label><input name="price" type="number" min="0" step="0.01" value="{{ old('price') }}" class="form-control" required></div>
          <div class="col-md-4"><label class="form-label">Tiền cọc</label><input name="deposit" type="number" min="0" step="0.01" value="{{ old('deposit') }}" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">Sức chứa</label><input name="max_people" type="number" min="1" value="{{ old('max_people', 1) }}" class="form-control" required></div>
          <div class="col-md-6"><label class="form-label">Tầng</label><input name="floor" type="number" min="0" value="{{ old('floor') }}" class="form-control"></div>
          <div class="col-12"><label class="form-label">Mô tả</label><textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea></div>
          <div class="col-12"><button class="btn-brand">Lưu phòng</button></div>
        </div>
      </form>
    </div>
    @endif
  </main>

@endsection