


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/reviews/index.css') }}?v={{ filemtime(public_path('css/tv3/tenant/reviews/index.css')) }}">
@endpush

@section('content')


<div class="page-wrap tenant-review-page">
  
  <div class="review-page-header">
    <span class="review-breadcrumb">TÀI KHOẢN / ĐÁNH GIÁ</span>
    <h1 class="page-title">Đánh giá &amp; nhận xét</h1>
    <p>Chia sẻ trải nghiệm thực tế để giúp người khác tìm được nơi ở phù hợp.</p>
  </div>

  <div class="row g-4">
    <!-- Cột Trái: Viết đánh giá mới -->
    <div class="col-lg-5">
      <div class="review-panel">
        <!-- 1. THÊM id="review-form" -->
        <form id="review-form" action="{{ route('tenant.reviews.store') }}" method="POST">
          @csrf
          <div class="review-section-heading"><h2>Viết đánh giá</h2><p>Trải nghiệm của bạn có thể giúp ích cho người thuê sau.</p></div>
          
          <div class="mb-4">
            <label class="form-label" for="review-room">Phòng đã thuê</label>
            @if($rooms->isEmpty())
              <div class="alert alert-warning mb-0" style="font-size: 13px; line-height: 1.5; border-radius: 10px;">
                <strong>Bạn chưa có phòng nào để đánh giá.</strong><br>
                Hệ thống chỉ cho phép gửi đánh giá cho những phòng mà bạn đã từng ký hợp đồng thuê hoặc hợp đồng đã thanh lý.
              </div>
            @else
              <select id="review-room" name="room_id" class="form-select" required>
                @foreach($rooms as $room)
                  @php
                    $contract = $room->related_contract ?? null;
                    $statusLabel = $contract ? ($contract->status === 'terminated' ? 'HĐ đã thanh lý' : ($contract->status === 'active' ? 'HĐ đang hiệu lực' : 'HĐ ' . $contract->status)) : 'Hợp đồng thuê';
                  @endphp
                  <option value="{{ $room->id }}">
                    {{ $room->name }} — {{ $room->property->name ?? '' }} ({{ $statusLabel }})
                  </option>
                @endforeach
              </select>
            @endif
          </div>

          <div class="mb-4">
            <div class="form-label" id="rating-label">Chất lượng phòng <span class="text-danger">*</span></div>
            <div class="star-rating" role="radiogroup" aria-labelledby="rating-label">
              <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 sao">★</label>
              <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 sao">★</label>
              <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 sao">★</label>
              <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 sao">★</label>
              <input type="radio" id="star1" name="rating" value="1" required><label for="star1" title="1 sao">★</label>
            </div>
            <div class="rating-hint">Chọn từ 1 đến 5 sao</div>
          </div>

          <div class="mb-4">
            <label class="form-label" for="review-comment">Nhận xét chi tiết <span class="review-optional">(không bắt buộc)</span></label>
            <textarea id="review-comment" name="comment" class="form-control" rows="4" placeholder="Chia sẻ điều bạn thích hoặc điều cần cải thiện..." maxlength="1000"></textarea>
            <div class="review-input-hint">Tối đa 1.000 ký tự</div>
          </div>

          <button type="submit" class="btn btn-brand review-submit w-100" @disabled($rooms->isEmpty())>
            Gửi đánh giá
          </button>
          <div id="review-feedback" class="small mt-2" role="status" aria-live="polite"></div>
        </form>
      </div>
    </div>

    <!-- Cột Phải: Lịch sử đã đánh giá -->
    <div class="col-lg-7">
      <div class="review-panel">
        <div class="review-history-heading">
          <div class="review-section-heading"><h2>Đánh giá của bạn</h2><p>Những trải nghiệm bạn đã chia sẻ.</p></div>
          <!-- 2. THÊM id="review-count" -->
          <span id="review-count" class="review-count">Tổng: {{ $reviews->count() }} lượt</span>
        </div>

        <!-- 3. THÊM id="review-list" bọc danh sách review -->
        <div id="review-list">
          @forelse($reviews as $review)
          <div class="review-card" data-review-id="{{ $review->id }}">
            <div class="d-flex justify-content-between align-items-start mb-2">
              <div>
                <div class="static-stars">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                <div class="fw-bold mt-1 text-dark" style="font-size: 15px;">{{ $review->room->name ?? '' }}</div>
              </div>
              <div class="text-end">
                <span class="{{ $review->status === 'visible' ? 'badge-visible' : 'badge-hidden' }} mb-1 d-inline-block">{{ $review->status === 'visible' ? 'Đang hiển thị' : 'Đang ẩn' }}</span>
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
    <script src="{{ asset('js/tv3/tenant/reivews/index.js') }}?v={{ filemtime(public_path('js/tv3/tenant/reivews/index.js')) }}"></script>
@endpush


@endsection
