


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/reviews/index.css') }}">
@endpush

@section('content')


<div class="page-wrap" style="padding-top: 40px; max-width: 1100px;">
  
  <div class="mb-4">
    <h2 class="page-title">Đánh giá & Nhận xét</h2>
    <div class="text-muted mt-2">Chia sẻ trải nghiệm của bạn về phòng thuê để giúp cộng đồng có cái nhìn khách quan.</div>
  </div>

  <div class="row g-4">
    <!-- Cột Trái: Viết đánh giá mới -->
    <div class="col-lg-5">
      <div class="review-panel">
        <!-- 1. THÊM id="review-form" -->
        <form id="review-form" action="{{ route('tenant.reviews.store') }}" method="POST">
          @csrf
          <h4 class="fw-bold mb-4" style="color: var(--green-dark); font-size: 18px;">✍️ Viết đánh giá mới</h4>
          
          <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Phòng đang thuê</label>
            @if($rooms->isEmpty())
              <div class="alert alert-warning mb-0">Bạn chưa có phòng nào để đánh giá.</div>
            @else
              <select name="room_id" class="form-select fw-bold text-dark" required>
                @foreach($rooms as $room)
                  <option value="{{ $room->id }}">🏠 {{ $room->name }} — {{ $room->property->name ?? '' }}</option>
                @endforeach
              </select>
            @endif
          </div>

          <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Chất lượng phòng (1-5 Sao) <span class="text-danger">*</span></label>
            <div class="star-rating">
              <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 sao">★</label>
              <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 sao">★</label>
              <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 sao">★</label>
              <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 sao">★</label>
              <input type="radio" id="star1" name="rating" value="1" required><label for="star1" title="1 sao">★</label>
            </div>
          </div>

          <div class="mb-4">
            <label class="form-label" style="font-size: 12px; font-weight: 800; color: var(--muted); text-transform: uppercase;">Nhận xét chi tiết</label>
            <textarea name="comment" class="form-control" rows="4" placeholder="Phòng có ồn không?... (tối đa 1000 ký tự)" maxlength="1000" style="resize: none;"></textarea>
          </div>

          <button type="submit" class="btn btn-brand w-100 py-3 mt-2" style="font-size: 14px; box-shadow: 0 4px 15px rgba(245,200,75,0.4);">
            🚀 Gửi Đánh Giá
          </button>
        </form>
      </div>
    </div>

    <!-- Cột Phải: Lịch sử đã đánh giá -->
    <div class="col-lg-7">
      <div class="review-panel">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <h4 class="fw-bold mb-0" style="color: var(--green-dark); font-size: 18px;">🕒 Lịch sử đánh giá của bạn</h4>
          <!-- 2. THÊM id="review-count" -->
          <span id="review-count" class="text-muted" style="font-size: 13px; font-weight: 600;">Tổng: {{ $reviews->count() }} lượt</span>
        </div>

        <!-- 3. THÊM id="review-list" bọc danh sách review -->
        <div id="review-list">
          @forelse($reviews as $review)
          <div class="review-card">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <div class="static-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                <div class="fw-bold mt-1 text-dark" style="font-size: 15px;">{{ $review->room->name ?? '' }}</div>
              </div>
              <div class="text-end">
                <span class="badge-visible mb-1 d-inline-block">✓ Đang hiển thị</span>
                <div class="text-muted" style="font-size: 11px;">{{ $review->created_at->format('d/m/Y') }}</div>
              </div>
            </div>
            @if($review->comment)
            <p class="text-muted mb-0 mt-2" style="font-size: 13.5px; line-height: 1.6;">
              "{{ $review->comment }}"
            </p>
            @endif
          </div>
          @empty
          <div id="no-review-text" class="text-center text-muted py-4">Bạn chưa có đánh giá nào. Hãy chia sẻ trải nghiệm ở form bên cạnh.</div>
          @endforelse
        </div>

      </div>
    </div>
  </div>
</div>

@push('scripts')
    <script src="{{ asset('js/tv3/tenant/reivews/index.js') }}"></script>
@endpush


@endsection