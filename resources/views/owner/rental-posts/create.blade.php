
@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/edit.css') }}">
@endpush

@section('content')




<div class="page">
    <a href="{{ route('owner.rental-posts.index') }}" class="text-decoration-none">← Quay lại danh sách</a>

    <div class="panel mt-3">
        <h1>Tạo tin đăng mới</h1>
        <p class="text-secondary">Chọn phòng và nhập thông tin cho thuê.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

      <form
            action="{{ route('owner.rental-posts.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="mb-3">
                <label class="form-label">Phòng trọ</label>
                <select name="room_id" class="form-select" required>
                    <option value="">-- Chọn phòng --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" @selected(old('room_id') == $room->id)>
                            {{ $room->room_number ?? $room->name ?? ('Phòng #' . $room->id) }}
                            — {{ $room->property->name ?? 'Nhà trọ' }}
                            — {{ number_format((float) $room->price, 0, ',', '.') }}đ/tháng
                        </option>
                    @endforeach
                </select>
                @if ($rooms->isEmpty())
                    <small class="text-danger">Em chưa có phòng trọ nào để tạo tin đăng. Hãy tạo nhà và phòng trước.</small>
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Tiêu đề tin đăng</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title') }}" maxlength="200"
                       placeholder="Ví dụ: Phòng trọ đầy đủ tiện nghi gần trường" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="6"
                          placeholder="Mô tả vị trí, tiện nghi và điều kiện thuê..."
                          required>{{ old('description') }}</textarea>
            </div>
    
      
{{-- CHỌN ẢNH PHÒNG --}}
<div class="mb-4">
    <label for="images" class="form-label fw-semibold">
        Hình ảnh phòng trọ
    </label>

    <input
        type="file"
        name="images[]"
        id="images"
        class="form-control"
        accept="image/jpeg,image/png,image/webp"
        multiple
        required
    >

    <small class="text-secondary d-block mt-2">
        Chọn từ 1 đến 10 ảnh, tối đa 5 MB mỗi ảnh.
    </small>

    <div id="imagePreview" class="image-preview-grid mt-3"></div>
</div>



            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select" required>
                    <option value="draft" @selected(old('status', 'draft') === 'draft')>Bản nháp</option>
                    <option value="pending" @selected(old('status') === 'pending')>Gửi duyệt</option>
                    <option value="hidden" @selected(old('status') === 'hidden')>Ẩn tin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Ngày hết hạn (không bắt buộc)</label>
                <input type="date" name="expired_at" class="form-control"
                       min="{{ now()->addDay()->toDateString() }}"
                       value="{{ old('expired_at') }}">
            </div>

            <button type="submit" class="btn btn-main"
                    @disabled($rooms->isEmpty())>Lưu tin đăng</button>
            <a href="{{ route('owner.rental-posts.index') }}" class="btn btn-light ms-2">Hủy</a>
        </form>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/tv3/rental_protal/create.js') }}"></script>
@endpush

@endsection