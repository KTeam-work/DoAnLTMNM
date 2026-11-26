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
      <a href="{{ route('owner.utilities.export', array_filter(['month' => $month, 'room_id' => $selectedRoomId, 'property_id' => $selectedPropertyId])) }}" id="utilitiesExcelLink" class="btn-outline-brand text-decoration-none d-inline-flex align-items-center gap-1" data-no-ajax="true">
        Xuất Excel
      </a>
      <a class="btn-brand" href="{{ route('owner.utilities.create', array_filter(['month' => $month, 'room_id' => $selectedRoomId, 'property_id' => $selectedPropertyId])) }}" >Ghi chỉ số mới</a>
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
  <div class="row g-3 mb-4" id="utilitiesStatCards">
    <div class="col-6 col-lg-3">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Phòng trọ</span>
          <span class="stat-trend" style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Hệ thống</span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 5px 0;">{{ $totalRooms }}</div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Tổng số phòng</div>
      </div>
    </div>
    
    <div class="col-6 col-lg-3">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Chốt chỉ số</span>
          <span class="stat-trend" style="background: {{ $currentMonth && ($totalRooms - $doneCount > 0) ? '#fef3c7' : '#f0fdf4' }}; color: {{ $currentMonth && ($totalRooms - $doneCount > 0) ? '#b45309' : '#16a34a' }}; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">
            {{ $currentMonth ? 'Còn ' . max(0, $totalRooms - $doneCount) . ' phòng' : 'Đã chốt' }}
          </span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 5px 0;">
          {{ $doneCount }} @if($currentMonth)<small style="font-size: 16px; color: #64748b; font-weight: 600;">/ {{ $totalRooms }}</small>@endif
        </div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">{{ $currentMonth ? 'Phòng đã chốt chỉ số' : 'Tổng bản ghi điện nước' }}</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Tiêu thụ</span>
          <span class="stat-trend" style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">
            Kỳ {{ $currentMonth ? $currentMonth->format('m/Y') : 'Toàn bộ' }}
          </span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 5px 0;">{{ number_format($totalKwh + $totalM3) }}</div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Tổng tiêu thụ (kWh + m³)</div>
      </div>
    </div>

    <div class="col-6 col-lg-3">
      <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 22px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03); border-left: 3px solid #16834b;">
        <div class="stat-top d-flex justify-content-between align-items-center">
          <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Tiền điện nước</span>
          <span class="stat-trend" style="background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Dự kiến</span>
        </div>
        <div class="stat-value" style="font-size: 30px; font-weight: 800; color: #16834b; line-height: 1.2; margin: 12px 0 5px 0;">{{ number_format($totalMoney) }}đ</div>
        <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Tổng tiền điện nước</div>
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
    <form method="GET" action="{{ route('owner.utilities.index') }}" id="utilityFilterForm" class="row g-3 mb-3">
      <div class="col-md-3">
        <label class="filter-label">Khu trọ / Nhà trọ</label>
        <select name="property_id" id="utilityPropertySelect" class="custom-input">
          <option value="">Tất cả khu trọ</option>
          @foreach($properties as $prop)
            <option value="{{ $prop->id }}" @selected((string) $selectedPropertyId === (string) $prop->id)>{{ $prop->name }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="filter-label">Phòng</label>
        <select name="room_id" id="utilityRoomSelect" class="custom-input">
          <option value="">Tất cả phòng</option>
          @php
            $groupedRooms = $rooms->groupBy(fn($r) => $r->property->name ?? 'Khác');
          @endphp
          @foreach($groupedRooms as $propName => $propRooms)
            <optgroup label="Khu: {{ $propName }}">
              @foreach($propRooms as $room)
                <option value="{{ $room->id }}" @selected((string) $selectedRoomId === (string) $room->id)>{{ $room->name }}</option>
              @endforeach
            </optgroup>
          @endforeach
        </select>
      </div>
      <div class="col-md-3">
        <label class="filter-label">Tháng ghi chỉ số (trống = tất cả)</label>
        <input type="month" name="month" id="utilityMonthInput" class="custom-input" value="{{ $month }}" lang="vi" title="Chọn tháng và năm ghi chỉ số">
      </div>
      <div class="col-md-3 d-flex align-items-end gap-2">
        <button type="submit" class="btn-brand flex-grow-1" style="height: 44px; justify-content: center;">Lọc dữ liệu</button>
        <a href="{{ route('owner.utilities.index', ['all' => 1]) }}" class="btn-outline-brand text-decoration-none utility-ajax-link" style="height: 44px; display: inline-flex; align-items: center; white-space: nowrap;">Tất cả kỳ</a>
      </div>
    </form>

    <!-- STATUS BAR -->
    <div id="utilitiesStatusBar" class="d-flex align-items-center justify-content-between mb-4 px-1 flex-wrap gap-2">
      <div class="text-muted small">
        @if($month)
          Đang xem kỳ: <strong class="text-dark">{{ \Carbon\Carbon::parse($month)->format('m/Y') }}</strong>
        @else
          Đang xem: <strong class="text-dark">Tất cả các kỳ</strong>
        @endif
        @if($selectedPropertyId)
          &bull; Khu trọ: <strong class="text-dark">{{ $properties->firstWhere('id', $selectedPropertyId)?->name ?? '' }}</strong>
        @endif
        @if($selectedRoomId)
          &bull; Phòng: <strong class="text-dark">{{ $rooms->firstWhere('id', $selectedRoomId)?->name ?? $selectedRoomId }}</strong>
        @endif
        &bull; Hiển thị: <strong class="text-dark">{{ $readings->count() }} bản ghi</strong>
      </div>
      @if($month)
        <a href="{{ route('owner.utilities.index', ['all' => 1]) }}" class="small text-decoration-none utility-ajax-link" style="color: var(--brand-dark, #a85827); font-weight: 600;">Xem tất cả các tháng &rarr;</a>
      @endif
    </div>

    <!-- TABLE -->
    <div class="table-responsive" id="utilitiesTableWrapper">
      <table class="data-table">
        <thead>
          <tr>
            <th>Phòng</th>
            <th>Kỳ &amp; Ngày ghi</th>
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
                  <div class="cell-sub" style="font-size: 11px; color: #64748b;">Ngày ghi: {{ $reading->created_at ? $reading->created_at->format('d/m/Y') : \Carbon\Carbon::parse($reading->month)->format('d/m/Y') }}</div>
                </div>
              </div>
            </td>
            <td>
              <div style="font-weight: 700; color: #4b3621;">Tháng {{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }}</div>
              @if($reading->period_start && $reading->period_end)
                <div class="cell-sub">Kỳ: {{ $reading->period_start->format('d/m/Y') }} – {{ $reading->period_end->format('d/m/Y') }}</div>
              @endif
              <div style="font-size: 11.5px; color: #64748b; margin-top: 2px;">
                Ngày ghi: <span style="font-weight: 600; color: #0c2340;">{{ $reading->created_at ? $reading->created_at->format('d/m/Y') : \Carbon\Carbon::parse($reading->month)->format('d/m/Y') }}</span>
              </div>
            </td>
            <td>
              <div>Cũ: <strong>{{ number_format((float)$reading->electricity_old) }}</strong></div>
              <div class="mt-1">Mới: <strong>{{ number_format((float)$reading->electricity_new) }}</strong></div>
            </td>
            <td>
              <div>Cũ: <strong>{{ number_format((float)$reading->water_old) }}</strong></div>
              <div class="mt-1">Mới: <strong>{{ number_format((float)$reading->water_new) }}</strong></div>
            </td>
            <td>
              <div>Điện: <strong>{{ number_format($eUse) }}</strong> kWh</div>
              <div class="mt-1">Nước: <strong>{{ number_format($wUse) }}</strong> m³</div>
            </td>
            <td>
              <div>Điện: {{ number_format($eMoney) }}đ</div>
              <div class="mt-1 border-bottom pb-1" style="border-color: #f0eadc!important;">Nước: {{ number_format($wMoney) }}đ</div>
              <div class="text-highlight mt-1">Tổng: {{ number_format($eMoney + $wMoney) }}đ</div>
            </td>
            <td><span class="badge-status occupied">Đã ghi</span></td>
            <td class="text-end">
              <div class="d-flex justify-content-end gap-1">
                <a class="btn-brand" style="padding: 6px 12px;" href="{{ route('owner.utilities.create', ['id' => $reading->id]) }}">Sửa</a>
                <form method="POST" action="{{ route('owner.utilities.destroy', $reading) }}" onsubmit="return confirm('Bạn có chắc muốn xóa bản ghi chỉ số tháng {{ \Carbon\Carbon::parse($reading->month)->format('m/Y') }} của phòng {{ $reading->room->name ?? '' }}?');" style="display:inline;">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="btn-outline-brand" style="padding: 6px 10px; color: #dc3545; border-color: #f5c6cb;" title="Xóa bản ghi">Xóa</button>
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
                  <a href="{{ route('owner.utilities.index', ['all' => 1]) }}" class="btn-outline-brand text-decoration-none utility-ajax-link" style="display: inline-flex; padding: 6px 14px; font-size: 0.9rem;">Xem tất cả các kỳ khác</a>
                </div>
              @else
                <div class="mt-2">
                  <a href="{{ route('owner.utilities.create') }}" class="btn-brand text-decoration-none" style="display: inline-flex; padding: 6px 14px; font-size: 0.9rem;">Ghi chỉ số mới ngay</a>
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

@push('scripts')
<script>
(function() {
  function initUtilityAjax() {
    const form = document.getElementById('utilityFilterForm');
    if (!form || form.dataset.ajaxInitialized === 'true') return;
    form.dataset.ajaxInitialized = 'true';

    let isFetching = false;

    async function applyUtilityFilter(url) {
      if (isFetching) return;
      isFetching = true;

      try {
        const res = await fetch(url, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'text/html'
          }
        });

        if (!res.ok) {
          window.location.href = url;
          return;
        }

        const html = await res.text();
        const doc = new DOMParser().parseFromString(html, 'text/html');

        // 1. Cập nhật Stat Cards
        const newStats = doc.getElementById('utilitiesStatCards');
        const curStats = document.getElementById('utilitiesStatCards');
        if (newStats && curStats) curStats.innerHTML = newStats.innerHTML;

        // 2. Cập nhật Status Bar
        const newStatusBar = doc.getElementById('utilitiesStatusBar');
        const curStatusBar = document.getElementById('utilitiesStatusBar');
        if (newStatusBar && curStatusBar) curStatusBar.innerHTML = newStatusBar.innerHTML;

        // 3. Cập nhật Bảng dữ liệu
        const newTable = doc.getElementById('utilitiesTableWrapper');
        const curTable = document.getElementById('utilitiesTableWrapper');
        if (newTable && curTable) curTable.innerHTML = newTable.innerHTML;

        // 4. Cập nhật link Xuất Excel
        const newExcel = doc.getElementById('utilitiesExcelLink');
        const curExcel = document.getElementById('utilitiesExcelLink');
        if (newExcel && curExcel) curExcel.href = newExcel.href;

        // 5. Cập nhật Select phòng tương ứng khu trọ
        const newRoomSelect = doc.getElementById('utilityRoomSelect');
        const curRoomSelect = document.getElementById('utilityRoomSelect');
        if (newRoomSelect && curRoomSelect) curRoomSelect.innerHTML = newRoomSelect.innerHTML;

        // 6. Cập nhật URL trên thanh địa chỉ không tải lại trang
        window.history.pushState({}, '', url);

        // Rebind click events
        bindAjaxLinks();
      } catch (err) {
        console.error('Lỗi lọc điện nước AJAX:', err);
        window.location.href = url;
      } finally {
        isFetching = false;
      }
    }

    function onFilterChange() {
      const formData = new FormData(form);
      const params = new URLSearchParams();
      for (const [key, value] of formData.entries()) {
        if (value) params.append(key, value);
      }
      const targetUrl = form.action + (params.toString() ? '?' + params.toString() : '');
      applyUtilityFilter(targetUrl);
    }

    form.querySelectorAll('select, input').forEach(el => {
      el.addEventListener('change', onFilterChange);
    });

    form.addEventListener('submit', function(e) {
      e.preventDefault();
      onFilterChange();
    });

    function bindAjaxLinks() {
      document.querySelectorAll('.utility-ajax-link').forEach(link => {
        link.onclick = function(e) {
          e.preventDefault();
          applyUtilityFilter(this.href);
        };
      });
    }

    bindAjaxLinks();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUtilityAjax);
  } else {
    initUtilityAjax();
  }
})();
</script>
@endpush

@endsection
