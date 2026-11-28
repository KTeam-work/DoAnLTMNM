@extends('Layout.tenant')
@section('title', 'So sánh phòng | Trọ Ơi')
@push('styles')<link rel="stylesheet" href="{{ asset('css/tv2/rooms/compare.css') }}">@endpush
@section('content')
<div class="compare-page">
  <a class="compare-back" href="{{ route('rooms.index') }}" data-no-ajax="true">Trở lại tìm phòng</a>
  <div class="compare-heading"><div><span class="compare-eyebrow">TÌM PHÒNG PHÙ HỢP</span><h1>So sánh phòng trọ</h1><p>Đặt các phòng cạnh nhau để chọn nơi ở phù hợp với bạn.</p></div><span class="compare-count">{{ $rooms->count() }} / 3 phòng</span></div>
  @if(session('compare_message'))<div class="alert alert-success" role="status">{{ session('compare_message') }}</div>@endif
  @if(session('compare_error'))<div class="alert alert-warning" role="alert">{{ session('compare_error') }}</div>@endif
  @if($rooms->isEmpty())
    <div class="compare-empty"><h2>Chưa có phòng để so sánh</h2><p>Chọn tối đa 3 phòng từ trang tìm phòng, rồi quay lại đây.</p><a href="{{ route('rooms.index') }}" class="compare-primary" data-no-ajax="true">Tìm phòng trọ</a></div>
  @else
    <p class="compare-hint">{{ $rooms->count() < 2 ? 'Thêm ít nhất một phòng nữa để so sánh.' : 'Cuộn ngang để xem tất cả phòng trên màn hình nhỏ.' }}</p>
    <div class="compare-table-wrap"><table class="compare-table"><thead><tr><th scope="col">Thông tin</th>@foreach($rooms as $room)
      @php
        $path = $room->rentalPosts->first()?->images->first()?->image_path;
        $image = null;
        if ($path) {
            $image = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//'])
                ? $path
                : (\Illuminate\Support\Str::startsWith($path, ['storage/', '/storage/'])
                    ? asset(ltrim($path, '/'))
                    : asset('storage/'.ltrim($path, '/')));
        }
      @endphp
      <th scope="col"><div class="compare-room-card{{ $image ? ' has-image' : '' }}">@if($image)<img src="{{ $image }}" alt="Ảnh {{ $room->name }}" loading="lazy">@endif<div class="compare-room-title">{{ $room->name }}</div><div class="compare-room-sub">{{ $room->property?->name }}</div><form action="{{ route('rooms.compare.destroy', $room) }}" method="POST" data-no-ajax="true">@csrf @method('DELETE')<button type="submit" class="compare-remove" aria-label="Bỏ {{ $room->name }} khỏi so sánh">Bỏ phòng</button></form></div></th>
    @endforeach</tr></thead><tbody>
      <tr><th scope="row">Giá thuê</th>@foreach($rooms as $room)<td><strong class="compare-price">{{ number_format((float) $room->price, 0, ',', '.') }} đ</strong><small>/ tháng</small></td>@endforeach</tr>
      <tr><th scope="row">Tiền cọc</th>@foreach($rooms as $room)<td>{{ number_format((float) $room->deposit, 0, ',', '.') }} đ</td>@endforeach</tr>
      <tr><th scope="row">Diện tích</th>@foreach($rooms as $room)<td>{{ number_format((float) $room->area, 1, ',', '.') }} m²</td>@endforeach</tr>
      <tr><th scope="row">Sức chứa</th>@foreach($rooms as $room)<td>{{ $room->max_people }} người</td>@endforeach</tr>
      <tr><th scope="row">Địa chỉ</th>@foreach($rooms as $room)<td>{{ implode(', ', array_filter([$room->property?->address, $room->property?->ward, $room->property?->district])) ?: 'Chưa cập nhật' }}</td>@endforeach</tr>
      <tr><th scope="row">Tiện ích</th>@foreach($rooms as $room)<td>@php
        $amenities = $room->amenities->whereIn('id', $room->property->amenities()->wherePivot('status', 'active')->pluck('amenities.id'))->pluck('name');
      @endphp{{ $amenities->isNotEmpty() ? $amenities->join(', ') : 'Chưa cập nhật' }}</td>@endforeach</tr>
      <tr><th scope="row">Xem phòng</th>@foreach($rooms as $room)<td><a href="{{ route('rooms.show', $room) }}" class="compare-primary" data-no-ajax="true">Xem chi tiết</a></td>@endforeach</tr>
    </tbody></table></div>
    @if($rooms->count() < 3)<a class="compare-more" href="{{ route('rooms.index') }}" data-no-ajax="true">+ Thêm phòng khác</a>@endif
  @endif
</div>
@endsection
