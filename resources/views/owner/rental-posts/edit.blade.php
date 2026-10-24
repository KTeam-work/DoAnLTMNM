@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/edit.css') }}">
@endpush

@section('content')




<div class="page">
    <a href="{{ route('owner.rental-posts.index') }}" class="text-decoration-none">← Quay lại danh sách</a>

    <div class="panel mt-3">
        <h1>Chỉnh sửa tin đăng</h1>
        <p class="text-secondary">Cập nhật nội dung tin cho thuê.</p>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if ($post->status === 'published')
            <div class="alert alert-info">
                Nếu thay đổi tiêu đề, mô tả hoặc phòng, tin đăng sẽ chuyển sang trạng thái chờ duyệt lại.
            </div>
        @endif

       <form
                action="{{ route('owner.rental-posts.update', $post) }}"
                method="POST"
                enctype="multipart/form-data"
            >
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Phòng trọ</label>
                <select name="room_id" class="form-select" required>
                    <option value="">-- Chọn phòng --</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}"
                            @selected(old('room_id', $post->room_id) == $room->id)>
                            {{ $room->room_number ?? $room->name ?? ('Phòng #' . $room->id) }}
                            — {{ $room->property->name ?? 'Nhà trọ' }}
                            — {{ number_format((float) $room->price, 0, ',', '.') }}đ/tháng
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Tiêu đề tin đăng</label>
                <input type="text" name="title" class="form-control"
                       value="{{ old('title', $post->title) }}" maxlength="200" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="6" required>{{ old('description', $post->description) }}</textarea>
            </div>

            
{{-- ẢNH HIỆN TẠI --}}
<div class="mb-4">
    <label class="form-label fw-semibold">
        Ảnh hiện tại
    </label>

    <div class="image-preview-grid">
        @forelse ($post->images as $image)
            <div class="existing-image">
                <img
                    src="{{ asset('storage/' . $image->image_path) }}"
                    alt="Ảnh phòng trọ"
                    class="rental-preview-image"
                >

                @if ($image->is_thumbnail)
                    <span class="thumbnail-label">Ảnh đại diện</span>
                @endif

                <label class="remove-image">
                    <input
                        type="checkbox"
                        name="remove_images[]"
                        value="{{ $image->id }}"
                    >
                    Xóa ảnh này
                </label>
            </div>
        @empty
            <p class="text-secondary">
                Tin đăng chưa có ảnh.
            </p>
        @endforelse
    </div>
</div>

        {{-- THÊM ẢNH MỚI --}}
        <div class="mb-4">
            <label for="images" class="form-label fw-semibold">
                Thêm ảnh mới
            </label>

            <input
                type="file"
                name="images[]"
                id="images"
                class="form-control"
                accept="image/jpeg,image/png,image/webp"
                multiple
            >

            <small class="text-secondary d-block mt-2">
                Có thể chọn thêm tối đa 10 ảnh mới.
                Để trống nếu không muốn thêm ảnh.
            </small>

            <div id="imagePreview" class="image-preview-grid mt-3"></div>
        </div>

       


            <div class="mb-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select" required>
                    <option value="draft" @selected(old('status', $post->status) === 'draft')>Bản nháp</option>
                    <option value="pending" @selected(old('status', $post->status) === 'pending')>Chờ duyệt</option>
                    <option value="hidden" @selected(old('status', $post->status) === 'hidden')>Ẩn tin</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="form-label">Ngày hết hạn</label>
                <input type="date" name="expired_at" class="form-control"
                       min="{{ now()->addDay()->toDateString() }}"
                       value="{{ old('expired_at', $post->expired_at?->format('Y-m-d')) }}">
            </div>

            <button type="submit" class="btn btn-main">Lưu thay đổi</button>
            <a href="{{ route('owner.rental-posts.index') }}" class="btn btn-light ms-2">Hủy</a>
        </form>
    </div>
</div>


@push('scripts')
    <script src="{{ asset('js/tv3/rental_protal/edit.js') }}"></script>
@endpush


@endsection