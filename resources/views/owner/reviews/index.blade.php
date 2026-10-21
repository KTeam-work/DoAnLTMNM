@extends('Layout.landlord')

@section('title', 'Tạo Phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv4/review/index.css') }}">
@endpush

@section('content')


<div class="page-wrap mt-4 mb-5">
  <div class="page-header d-flex justify-content-between align-items-center mb-4">
    <div>
      <h2 class="page-title mb-1">Quản lý Đánh giá</h2>
      <div class="page-desc">Theo dõi phản hồi và chất lượng dịch vụ từ người thuê.</div>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
  @endif

  <!-- TỔNG QUAN — bấm vào một mức sao để lọc nhanh -->
  <div class="overview-panel">
    <div class="overview-score">
      <div class="num">{{ $stats['average'] }}<small>/ 5</small></div>
      <div class="stars">{{ str_repeat('★', (int) round($stats['average'])) }}{{ str_repeat('☆', 5 - (int) round($stats['average'])) }}</div>
      <div class="total">{{ $stats['total'] }} lượt đánh giá</div>
    </div>
    <div class="overview-divider"></div>
    <div class="rating-bars">
      @foreach([5, 4, 3, 2, 1] as $star)
        @php $pct = $stats['total'] > 0 ? round($stats['distribution'][$star] / $stats['total'] * 100) : 0; @endphp
        <a href="{{ route('owner.reviews.index', ['rating' => $star]) }}" class="rating-bar-row {{ $star <= 2 ? 'attn' : '' }} {{ (string) ($filters['rating'] ?? '') === (string) $star ? 'is-active' : '' }}" style="text-decoration: none;">
          <span class="label">{{ $star }} sao</span>
          <span class="rating-bar-track"><span class="rating-bar-fill" "></span></span>
          <span class="count">{{ $stats['distribution'][$star] }}</span>
        </a>
      @endforeach
    </div>
  </div>

  <!-- BỘ LỌC -->
  <form method="GET" action="{{ route('owner.reviews.index') }}" class="filter-row d-flex flex-wrap justify-content-between align-items-center gap-3">
    <div class="d-flex align-items-center gap-2 flex-wrap">
      <span class="fw-bold" style="font-size:.95rem;">Danh sách phản hồi</span>
      <span class="filter-count">— hiển thị <strong>{{ $reviews->count() }}</strong>/{{ $reviews->total() }}</span>
    </div>
    <div class="d-flex gap-2 flex-wrap">
      <select name="room_id" class="form-select form-select-sm shadow-none" style="width: 170px;" onchange="this.form.submit()">
        <option value="">Tất cả phòng</option>
        @foreach($rooms as $room)
          <option value="{{ $room->id }}" @selected((string) ($filters['room_id'] ?? '') === (string) $room->id)>{{ $room->name }}</option>
        @endforeach
      </select>
      <select name="rating" class="form-select form-select-sm shadow-none" style="width: 150px;" onchange="this.form.submit()">
        <option value="">Tất cả số sao</option>
        @foreach([5, 4, 3, 2, 1] as $star)
          <option value="{{ $star }}" @selected((string) ($filters['rating'] ?? '') === (string) $star)>{{ $star }} sao</option>
        @endforeach
      </select>
      <select name="status" class="form-select form-select-sm shadow-none" style="width: 150px;" onchange="this.form.submit()">
        <option value="">Mọi trạng thái</option>
        <option value="visible" @selected(($filters['status'] ?? '') === 'visible')>Đang hiển thị</option>
        <option value="hidden" @selected(($filters['status'] ?? '') === 'hidden')>Đang ẩn</option>
      </select>
      @if(!empty($filters['room_id']) || !empty($filters['rating']) || !empty($filters['status']))
        <a href="{{ route('owner.reviews.index') }}" class="btn btn-sm btn-light border">Xóa lọc</a>
      @endif
    </div>
  </form>

  <!-- DANH SÁCH PHẢN HỒI -->
  <div class="review-feed">

    @forelse($reviews as $review)
    <div class="review-row {{ $review->rating <= 2 ? 'attn' : '' }} {{ $review->status === 'hidden' ? 'hidden-row' : '' }}">
      <div class="review-who">
        <div class="review-avatar">{{ strtoupper(substr($review->tenant->name ?? '?', 0, 2)) }}</div>
        <div>
          <div class="review-name">{{ $review->tenant->name ?? '' }}</div>
          <div class="review-date">{{ $review->created_at->format('d/m/Y') }}</div>
          <div class="review-room">{{ $review->room->name ?? '' }}</div>
        </div>
      </div>
      <div class="review-body">
        <div class="stars-line">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
        <div class="review-quote {{ $review->status === 'hidden' ? 'muted-quote' : '' }}">
          <span class="quote-text">{{ $review->comment ?: '(Không có nhận xét)' }}</span>
        </div>
      </div>
      <div class="review-side">
        @if($review->status === 'visible')
          <span class="status-pill showing">Đang hiển thị</span>
        @else
          <span class="status-pill hidden-pill">Đã ẩn</span>
        @endif
        <div class="row-actions">
          <form action="{{ route('owner.reviews.visibility', $review->id) }}" method="POST" class="m-0">
            @csrf
            @method('PUT')
            <button type="submit" class="btn-row-action">{{ $review->status === 'visible' ? 'Ẩn' : 'Hiển thị lại' }}</button>
          </form>
        </div>
      </div>
    </div>
    @empty
    <div class="text-center text-muted py-5">Chưa có đánh giá nào.</div>
    @endforelse

  </div>

  <div class="review-pagination">
    <div class="small">Tổng {{ $reviews->total() }} đánh giá</div>
    <div>{{ $reviews->links() }}</div>
  </div>
</div>

@endsection