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

        @if ($post->status === 'rejected' && $post->rejection_reason)
            <div class="alert alert-danger">
                <strong>⚠️ Tin đăng đã bị Admin từ chối phê duyệt!</strong>
                <div class="mt-1"><strong>Lý do từ chối:</strong> {{ $post->rejection_reason }}</div>
                <div class="mt-1 small">Vui lòng kiểm tra và chỉnh sửa lại thông tin, sau đó chọn trạng thái <strong>"Chờ duyệt"</strong> rồi lưu lại để gửi Admin kiểm duyệt.</div>
            </div>
        @endif

        @if ($post->status === 'published')
            <div class="alert alert-info">
                Nếu thay đổi tiêu đề hoặc mô tả, tin đăng sẽ chuyển sang trạng thái chờ duyệt lại.
            </div>
        @endif

       <form
                action="{{ route('owner.rental-posts.update', $post) }}"
                method="POST"
                enctype="multipart/form-data"
            >
            @csrf
            @method('PUT')

            {{-- CỐ ĐỊNH PHÒNG TRỌ (Không cho đổi sang phòng khác khi sửa tin) --}}
            <input type="hidden" name="room_id" value="{{ $post->room_id }}">

            <div class="mb-3">
                <label class="form-label fw-semibold">Phòng trọ</label>
                <div class="p-3 bg-light border rounded-3 text-secondary small">
                    <span class="fw-bold text-dark fs-6 d-block mb-1">
                        {{ $post->room?->name ?? ('Phòng #' . $post->room_id) }}
                        — {{ $post->room?->property?->name ?? 'Nhà trọ' }}
                    </span>
                    <span>Giá thuê: <strong class="text-primary">{{ number_format((float) ($post->room?->price ?? 0), 0, ',', '.') }}đ/tháng</strong></span>
                    <span class="ms-3">Diện tích: <strong>{{ $post->room?->area ?? '-' }} m²</strong></span>
                    <small class="text-muted d-block mt-1">🔒 Phòng trọ được gắn cố định với tin đăng này (không thể đổi sang phòng khác).</small>
                </div>
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
                <label class="form-label fw-semibold">Trạng thái sau khi sửa</label>
                <select name="status" class="form-select" required>
                    <option value="pending" @selected(old('status', $post->status) === 'pending' || $post->status === 'rejected')>Gửi duyệt (chờ Admin kiểm duyệt)</option>
                    <option value="draft" @selected(old('status', $post->status) === 'draft')>Lưu bản nháp (chưa gửi duyệt)</option>
                </select>
                <small class="text-secondary d-block mt-1">
                    Nếu bạn đã chỉnh sửa xong theo yêu cầu của Admin, hãy chọn <strong>"Gửi duyệt"</strong> để Admin kiểm tra lại.
                </small>
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