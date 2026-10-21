@extends('Layout.landlord')

@section('title', 'Quản lý tin đăng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rental_protal/show.css') }}">
@endpush

@section('content')



<div class="page">
    <a href="{{ route('owner.rental-posts.index') }}" class="text-decoration-none">← Quay lại danh sách</a>

    <div class="panel mt-3">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
            <div>
                <div class="label">CHI TIẾT TIN ĐĂNG #{{ $post->id }}</div>
                <h1>{{ $post->title }}</h1>
            </div>
            <a href="{{ route('owner.rental-posts.edit', $post) }}" class="btn btn-main">Chỉnh sửa</a>
        </div>

        @php
            $labels = [
                'draft' => 'Bản nháp',
                'pending' => 'Chờ duyệt',
                'published' => 'Đang hiển thị',
                'rejected' => 'Bị từ chối',
                'hidden' => 'Đã ẩn',
            ];
        @endphp

        <div class="label">Trạng thái</div>
        <div class="value">{{ $labels[$post->status] ?? $post->status }}</div>

        <div class="label">Phòng trọ</div>
        <div class="value">
            {{ $post->room?->room_number ?? $post->room?->name ?? ('Phòng #' . ($post->room?->id ?? '—')) }}
        </div>

        <div class="label">Nhà trọ</div>
        <div class="value">{{ $post->room?->property?->name ?? 'Chưa có tên nhà trọ' }}</div>

        <div class="label">Giá thuê</div>
        <div class="value">
            @if ($post->room?->price !== null)
                {{ number_format((float) $post->room->price, 0, ',', '.') }}đ/tháng
            @else
                Chưa cập nhật giá thuê
            @endif
        </div>

        <div class="label">Diện tích</div>
        <div class="value">
            {{ $post->room?->area !== null ? $post->room->area . ' m²' : 'Chưa cập nhật' }}
        </div>

        <div class="label">Mô tả</div>
        <div class="value description">{{ $post->description }}</div>

        <div class="label">Ngày hết hạn</div>
        <div class="value">
            {{ $post->expired_at?->format('d/m/Y') ?? 'Không đặt ngày hết hạn' }}
        </div>

        <div class="label">Ngày đăng</div>
        <div class="value">
            {{ $post->published_at?->format('d/m/Y H:i') ?? 'Chưa xuất bản' }}
        </div>

        @if ($post->status === 'rejected' && $post->rejection_reason)
            <div class="alert alert-danger">
                <strong>Lý do từ chối:</strong> {{ $post->rejection_reason }}
            </div>
        @endif

        <a href="{{ route('owner.rental-posts.index') }}" class="btn btn-light">Quay lại</a>
    </div>
</div>

@endsection