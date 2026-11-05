@extends('Layout.landlord')

@section('title', 'Quản lý chỉ số điện nước | Trọ Ơi')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/utilities/index.css') }}">
@endpush

@section('content')

<div class="page-wrap">
  <!-- PAGE HEADER -->
  <div class="page-header">
    <div>
      <h2 class="page-title">Quản lý điện nước</h2>
      <div class="page-desc">Theo dõi mức tiêu thụ và chốt chỉ số cho các phòng trong tháng.</div>
    </div>
    <div class="d-flex gap-2">
      <button class="btn-outline-brand">📥 Xuất Excel</button>
      <a class="btn-brand" href="{{ route('owner.utilities.create', array_filter(['month' => $month, 'room_id' => $selectedRoomId])) }}" >+ Ghi chỉ số mới</a>
    </div>
  </div>

  <!-- STAT CARDS -->
  @php
      $totalRooms = $rooms->count();
      $currentMonth = $month ? \Carbon\Carbon::parse($month)->startOfMonth() : null;
      $displayedReadings = $currentMonth
          ? $readings->filter(fn($r) => \Carbon\Carbon::parse($r->month)->isSameMonth($currentMonth))
          : $readings;
      $doneCount = $displayedReadings->count();
      $totalKwh = $displayedReadings->sum(fn($r) => (float)$r->electricity_new - (float)$r->electricity_old);
      $totalM3 = $displayedReadings->sum(fn($r) => (float)$r->water_new - (float)$r->water_old);
      $totalMoney = $displayedReadings->sum(fn($r) => ((float)$r->electricity_new - (float)$r->electricity_old) * (float)$r->electricity_price + ((float)$r->water_new - (float)$r->water_old) * (float)$r->water_price);
  @endphp
  <div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon">🏠</div>
          <span class="stat-trend muted">{{ $currentMonth ? $currentMonth->format('m/Y') : 'Tất cả các kỳ' }}</span>
        </div>
        <div class="stat-value">{{ $totalRooms }}</div>
        <div class="stat-label">Tổng số phòng quản lý</div>
      </div>
    </div>
    
    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon green">⚡</div>
          <span class="stat-trend">{{ $currentMonth ? 'Còn ' . max(0, $totalRooms - $doneCount) . ' phòng' : 'Tổng lượt ghi' }}</span>
        </div>
        <div class="stat-value">{{ $doneCount }} @if($currentMonth)<small>/ {{ $totalRooms }}</small>@endif</div>
        <div class="stat-label">{{ $currentMonth ? 'Đã chốt chỉ số' : 'Bản ghi điện nước' }}</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon blue">💧</div>
          <span class="stat-trend">{{ $currentMonth ? 'Kỳ ' . $currentMonth->format('m/Y') : 'Toàn bộ kỳ' }}</span>
        </div>
        <div class="stat-value">{{ number_format($totalKwh + $totalM3) }}</div>
        <div class="stat-label">Tổng tiêu thụ (kWh + m³)</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card">
        <div class="stat-top">
          <div class="stat-icon red">💰</div>
          <span class="stat-trend">Tạm tính</span>
        </div>
        <div class="stat-value">{{ number_format($totalMoney) }}đ</div>
        <div class="stat-label">Tổng tiền dự kiến</div>
      </div>
    </div>
  </div>

  <!-- BẢNG DỮ LIỆU -->
  <div class="panel">

    @if(session('success'))
      <div class="alert alert-success mb-3">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger mb-3">{{ session('error') }}</div>
    @endif
    
    <!-- FILTERS -->
    <form method="GET" action="{{ route('owner.utilities.index') }}" class="row g-3 mb-3">
      <div class="col-md-4">
        <label class="filter-label">Phòng</label>
        <select name="room_id" class="custom-input">
          <option value="">Tất cả phòng</option>
          @foreach($rooms as $room)
            <option value="{{ $room->id }}" @selected((string) $selectedRoomId === (string) $room->id)>{{ $room->name }} · {{ $room->property->name ?? '' }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-4">
        <label class="filter-label">Tháng ghi chỉ số (để trống để xem tất cả)</label>
        <input type="month" name="month" class="custom-input" value="{{ $month }}" lang="vi" title="Chọn tháng và năm ghi chỉ số">
      </div>
      <div class="col-md-4 d-flex align-items-end gap-2">
        <button type="submit" class="btn-brand flex-grow-1" style="height: 44px; justify-content: center;">Lọc dữ liệu</button>
        <a href="{{ route('owner.utilities.index', ['all' => 1]) }}" class="btn-outline-brand text-decoration-none" style="height: 44px; display: inline-flex; align-items: center; white-space: nowrap;">Tất cả kỳ</a>
      </div>
    </form>

    <!-- STATUS BAR -->
    <div class="d-flex align-items-center justify-content-between mb-4 px-1 flex-wrap gap-2">
      <div class="text-muted small">
        @if($month)
          Đang xem kỳ: <strong class="text-dark">{{ \Carbon\Carbon::parse($month)->format('m/Y') }}</strong>
        @else
          Đang xem: <strong class="text-dark">Tất cả các kỳ</strong>
        @endif
        @if($selectedRoomId)
          &bull; Phòng: <strong class="text-dark">{{ $rooms->firstWhere('id', $selectedRoomId)?->name ?? $selectedRoomId }}</strong>
        @endif
        &bull; Hiển thị: <strong class="text-dark">{{ $readings->count() }} bản ghi</strong>
      </div>
      @if($month)
        <a href="{{ route('owner.utilities.index', ['all' => 1]) }}" class="small text-decoration-none" style="color: var(--brand-dark, #a85827); font-weight: 600;">Xem tất cả các tháng &rarr;</a>
      @endif
    </div>

    <!-- TABLE -->
    <div class="table-responsive">
      <table class="data-table">
        <thead>
          <tr>
            <th>Phòng</th>
            <th>Tháng</th>
            <th>Chỉ số Điện (Cũ - Mới)</th>
            <th>Chỉ số Nước (Cũ - Mới)</th>
            <th>Tiêu thụ</th>
            <th>Thành tiền</th>
            <th>Trạng thái</th>
            <th class="text-end">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($readings as $reading)
            @php
                $eUse = (float)$reading->electricity_new - (float)$reading->electricity_old;
                $wUse = (float)$reading->water_new - (float)$reading->water_old;
                $eMoney = $eUse * (float)$reading->electricity_price;
                $wMoney = $wUse * (float)$reading->water_price;
            @endphp
          <tr>
            <td>
              <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">{{ substr($reading->room->name ?? '?', -3) }}</div>
                <div>
                  <div class="cell-title">{{ $reading->room->name ?? '—' }} · {{ $reading->room->room_code ?? '' }}</div>
                  <div class="cell-sub">{{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }}</div>
                </div>
              </div>
            </td>
            <td><strong style="color: #4b3621;">{{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }}</strong></td>
            <td>
              <div>Cũ: <strong>{{ number_format((float)$reading->electricity_old) }}</strong></div>
              <div class="mt-1">Mới: <strong>{{ number_format((float)$reading->electricity_new) }}</strong></div>
            </td>
            <td>
              <div>Cũ: <strong>{{ number_format((float)$reading->water_old) }}</strong></div>
              <div class="mt-1">Mới: <strong>{{ number_format((float)$reading->water_new) }}</strong></div>
            </td>
            <td>
              <div style="font-weight: 700; color: var(--green-dark);">⚡ {{ number_format($eUse) }} kWh</div>
              <div class="mt-1" style="font-weight: 700; color: var(--green-dark);">💧 {{ number_format($wUse) }} m³</div>
            </td>
            <td>
              <div>⚡ {{ number_format($eMoney) }}đ</div>
              <div class="mt-1 border-bottom pb-1" style="border-color: #f0eadc!important;">💧 {{ number_format($wMoney) }}đ</div>
              <div class="text-highlight mt-1">Σ {{ number_format($eMoney + $wMoney) }}đ</div>
            </td>
            <td><span class="badge-status occupied">Đã ghi</span></td>
            <td class="text-end">
              <div class="d-flex justify-content-end gap-1">
                <a class="btn-brand" style="padding: 6px 12px;" href="{{ route('owner.utilities.create', ['id' => $reading->id]) }}">✏️ Sửa</a>
                <form method="POST" action="{{ route('owner.utilities.destroy', $reading) }}" onsubmit="return confirm('Bạn có chắc muốn xóa bản ghi chỉ số tháng {{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }} của phòng {{ $reading->room->name ?? '' }}?');" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-outline-brand" style="padding: 6px 10px; color: #dc3545; border-color: #f5c6cb;" title="Xóa bản ghi">🗑️</button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="text-center text-muted py-5">
              <div style="font-size: 1.1rem; font-weight: 500; margin-bottom: 8px;">
                Chưa có chỉ số điện nước nào{{ $month ? ' trong kỳ ' . \Carbon\Carbon::parse($month)->format('m/Y') : '' }}.
              </div>
              @if($month)
                <div class="mt-2">
                  <a href="{{ route('owner.utilities.index', ['all' => 1]) }}" class="btn-outline-brand text-decoration-none" style="display: inline-flex; padding: 6px 14px; font-size: 0.9rem;">Xem tất cả các kỳ khác</a>
                </div>
              @else
                <div class="mt-2">
                  <a href="{{ route('owner.utilities.create') }}" class="btn-brand text-decoration-none" style="display: inline-flex; padding: 6px 14px; font-size: 0.9rem;">+ Ghi chỉ số mới ngay</a>
                </div>
              @endif
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection
