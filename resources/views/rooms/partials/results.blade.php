
<div class="row g-4">
    @forelse ($rooms as $room)
        @php
            $favoriteId = $favoriteIdsByRoom[$room->id] ?? null;
            $isFavorite = $favoriteId !== null;
        @endphp

        <div class="col-md-6 col-xl-4">
            <article class="room-card">
                <form
                    action="{{ $isFavorite
                        ? route('favorites.destroy', $favoriteId)
                        : route('favorites.store') }}"
                    method="POST"
                    class="favorite-form"
                    data-store-url="{{ route('favorites.store') }}"
                    data-destroy-url="{{ route('favorites.destroy', ['id' => '__FAVORITE_ID__']) }}"
                    data-room-id="{{ $room->id }}"
                >
                    @csrf

                    @if ($isFavorite)
                        @method('DELETE')
                    @else
                        <input type="hidden" name="room_id"
                               value="{{ $room->id }}">
                    @endif

                    <button
                        type="submit"
                        class="fav-btn {{ $isFavorite ? 'is-favorite' : '' }}"
                        title="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm yêu thích' }}"
                        aria-label="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm yêu thích' }}"
                    >
                        {{ $isFavorite ? '♥' : '♡' }}
                    </button>
                </form>

                @php
                    $cardImg = null;
                    $firstPost = $room->rentalPosts->first();
                    if ($firstPost && $firstPost->relationLoaded('images') && $firstPost->images->isNotEmpty()) {
                        $pPath = $firstPost->images->first()->image_path;
                        $cardImg = \Illuminate\Support\Str::startsWith($pPath, ['http://', 'https://', '//'])
                            ? $pPath
                            : asset('storage/' . ltrim($pPath, '/'));
                    }
                    if (!$cardImg) {
                        $rImages = is_array($room->images) ? $room->images : (json_decode($room->images ?? '[]', true) ?: []);
                        if (!empty($rImages[0])) {
                            $cardImg = \Illuminate\Support\Str::startsWith($rImages[0], ['http://', 'https://', '//'])
                                ? $rImages[0]
                                : asset('storage/' . ltrim($rImages[0], '/'));
                        }
                    }
                    if (!$cardImg && $room->property && $room->property->relationLoaded('images') && $room->property->images->isNotEmpty()) {
                        $propPath = $room->property->images->first()->image_path;
                        $cardImg = \Illuminate\Support\Str::startsWith($propPath, ['http://', 'https://', '//'])
                            ? $propPath
                            : asset('storage/' . ltrim($propPath, '/'));
                    }
                    if (!$cardImg) {
                        $cardImg = 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';
                    }
                @endphp

                <img
                    class="room-img"
                    src="{{ $cardImg }}"
                    alt="{{ $room->name ?? 'Phòng trọ' }}"
                    loading="lazy"
                    onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80';"
                >

                <div class="room-body">
                    <div class="room-badges">
                        <span class="badge-room">
                            Mã phòng: {{ $room->room_code ?? $room->id }}
                        </span>
                    </div>

                    <h3 class="room-title">
                        {{ $room->name ?? 'Phòng trọ' }}
                    </h3>

                    <div class="room-address">
                        {{ $room->property?->address ?? 'Chưa cập nhật địa chỉ' }}
                        @if ($room->property?->ward)
                            , {{ $room->property->ward }}
                        @endif
                        @if ($room->property?->district)
                            , {{ $room->property->district }}
                        @endif
                    </div>

                    <div class="room-stats">
                        <span>
                            📐 {{ number_format((float) $room->area, 0, ',', '.') }} m²
                        </span>

                        @forelse ($room->amenities->take(2) as $amenity)
                            <span>{{ $amenity->name }}</span>
                        @empty
                            <span>Chưa cập nhật tiện ích</span>
                        @endforelse
                    </div>

                    <div class="room-bottom">
                        <div class="room-price">
                            {{ number_format((float) $room->price, 0, ',', '.') }}
                            đ <small>/ tháng</small>
                        </div>

                        <a href="{{ route('rooms.show', $room->id) }}"
                           class="detail-btn">
                            Xem phòng →
                        </a>
                    </div>
                    <form action="{{ route('rooms.compare.store', $room) }}" method="POST" data-no-ajax="true" class="compare-add-form">@csrf<button type="submit" class="compare-add-btn">{{ in_array($room->id, session('compare_rooms', [])) ? 'Đã thêm vào so sánh' : '+ Thêm vào so sánh' }}</button></form>
                </div>
            </article>
        </div>
    @empty
        <div class="col-12">
            <div class="room-empty-state">
                <div class="room-empty-icon">⌕</div>
                <h3>Chưa tìm thấy phòng phù hợp</h3>
                <p>Em thử thay đổi khu vực, giá tiền hoặc tiện ích nhé.</p>
                <button type="button" class="btn-brand"
                        id="reset-empty-room-filters">
                    Xóa bộ lọc
                </button>
            </div>
        </div>
    @endforelse
</div>

@if ($rooms->hasPages())
    <div class="d-flex justify-content-center mt-4 room-pagination">
        {{ $rooms->links() }}
    </div>
@endif
