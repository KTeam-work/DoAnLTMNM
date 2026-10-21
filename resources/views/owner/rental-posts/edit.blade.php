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

        <form action="{{ route('owner.rental-posts.update', $post) }}" method="POST">
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


@endsection