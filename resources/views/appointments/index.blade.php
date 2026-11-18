


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
<style>
/* Segmented Filter Bar (Modern Pill Filter - No Icons) */
.segmented-filter-wrapper {
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
}

.segmented-filter-bar {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px;
    background: #f2efe9;
    border: 1px solid #e5e0d5;
    border-radius: 12px;
    box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.03);
    max-width: 100%;
    overflow-x: auto;
}

.segmented-tab {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 18px;
    border: none;
    background: transparent;
    border-radius: 8px;
    color: #555c58;
    font-size: 13.5px;
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.18s ease;
    text-decoration: none !important;
    user-select: none;
}

.segmented-tab:hover {
    background: rgba(255, 255, 255, 0.6);
    color: #1a221e;
}

.segmented-tab.active {
    background: #ffffff;
    color: #162c21;
    font-weight: 600;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08), 0 2px 8px rgba(0, 0, 0, 0.03);
}

.tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 20px;
    height: 20px;
    padding: 0 6px;
    border-radius: 999px;
    font-size: 11.5px;
    font-weight: 700;
    background: #ded7cb;
    color: #525955;
    line-height: 1;
    transition: all 0.18s ease;
}

.segmented-tab.active .tab-count {
    background: #e4efe7;
    color: #134e2c;
}
</style>
@endpush

@section('content')





    <div class="page-wrap">


        <!-- =====================================================
         HEADER
    ====================================================== -->

        <div class="page-header">

            <div>

                <div
                    class="badge-status pending mb-2"
                    style="display:inline-block;">
                    📅 LỊCH XEM CỦA BẠN
                </div>

                <h1 class="page-title">
                    Lịch xem phòng
                </h1>

                <div class="page-desc">
                    Theo dõi các lịch hẹn xem phòng bạn đã gửi cho chủ nhà.
                </div>

            </div>


            <!-- TẠO LỊCH MỚI -->

            <a
                href="{{ route('appointments.create') }}"
                class="btn-brand text-decoration-none">
                + Đặt lịch xem phòng
            </a>

        </div>



        <!-- =====================================================
         STATISTICS
    ====================================================== -->

        <!-- =====================================================
             SEGMENTED FILTER TABS (NO ICONS)
        ====================================================== -->
        <div class="segmented-filter-wrapper mb-4">
            <div class="segmented-filter-bar" role="tablist">
                <button type="button" class="segmented-tab active" data-status="">
                    <span class="tab-label">Tất cả</span>
                    <span class="tab-count">{{ $totalAppointments }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="pending">
                    <span class="tab-label">Chờ xác nhận</span>
                    <span class="tab-count">{{ $pendingCount }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="confirmed">
                    <span class="tab-label">Đã xác nhận</span>
                    <span class="tab-count">{{ $confirmedCount }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="completed">
                    <span class="tab-label">Đã xem phòng</span>
                    <span class="tab-count">{{ $completedCount }}</span>
                </button>
            </div>
        </div>



        <!-- LỊCH XEM SẮP TỚI -->
        <div class="panel mb-4">

            <div class="panel-head">
                <div>
                    <h2 class="panel-title">Lịch xem sắp tới</h2>
                    <div class="page-desc">
                        Lịch hẹn gần nhất của bạn.
                    </div>
                </div>

                @if ($upcomingAppointments->isNotEmpty())
                @php
                $nextAppointment = $upcomingAppointments->first();

                $upcomingStatusLabels = [
                'pending' => ['Chờ xác nhận', 'pending'],
                'confirmed' => ['Đã xác nhận', 'occupied'],
                ];

                $upcomingStatus = $upcomingStatusLabels[
                $nextAppointment->status
                ] ?? ['Chưa xác định', 'pending'];
                @endphp

                <span class="badge-status {{ $upcomingStatus[1] }}">
                    {{ $upcomingStatus[0] }}
                </span>
                @endif
            </div>

            @if ($upcomingAppointments->isNotEmpty())

            <div class="row align-items-center g-4">
                <div class="col-lg-8">
                    <div class="d-flex gap-3 align-items-start">

                        <div class="stat-icon green">📅</div>

                        <div>
                            <div class="cell-title">
                                {{ $nextAppointment->room?->name
                                ?? 'Phòng đã cập nhật' }}
                            </div>

                            <div class="cell-sub mb-2">
                                📍
                                {{ $nextAppointment->room?->property?->address
                                ?? 'Chưa có địa chỉ' }}
                            </div>

                            <div class="d-flex flex-wrap gap-2">

                                <span class="badge-status occupied">
                                    📅
                                    {{ $nextAppointment->appointment_date
                                    ->format('d/m/Y') }}
                                </span>

                                <span class="badge-status pending">
                                    🕒
                                    {{ substr(
                                    (string) $nextAppointment->appointment_time,
                                    0,
                                    5
                                ) }}
                                </span>

                                <span class="badge-status new">
                                    ☎️ {{ $nextAppointment->phone }}
                                </span>

                            </div>

                            @if ($nextAppointment->message)
                            <div class="cell-sub mt-2">
                                Ghi chú: {{ $nextAppointment->message }}
                            </div>
                            @endif
                        </div>

                    </div>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <a
                        href="{{ route('appointments.create') }}"
                        class="btn-outline-brand text-decoration-none">
                        Đặt lịch khác
                    </a>
                </div>
            </div>

            @else

            <div class="text-center py-4">
                <div class="stat-icon mx-auto mb-3">📅</div>

                <div class="cell-title">
                    Bạn chưa có lịch xem phòng sắp tới
                </div>

                <div class="cell-sub mt-2 mb-3">
                    Những lịch đã qua, đã hủy hoặc đã từ chối
                    sẽ không hiển thị ở khu vực này.
                </div>

                <a
                    href="{{ route('appointments.create') }}"
                    class="btn-brand text-decoration-none">
                    + Đặt lịch xem phòng
                </a>
            </div>

            @endif

        </div>



        <!-- =====================================================
         FILTER
    ====================================================== -->

        <div class="panel mb-4">

            <div class="panel-head">

                <div>

                    <h2 class="panel-title">
                        Danh sách lịch hẹn
                    </h2>

                    <div class="page-desc">
                        Lịch xem được sắp xếp theo thời gian mới nhất.
                    </div>

                </div>


                <select
                    class="form-select"
                    style="
                    width:auto;
                    min-width:180px;
                    border-color:var(--border);
                    border-radius:11px;
                    font-size:12px;
                    font-weight:600;
                ">

                    <option selected>
                        Tất cả trạng thái
                    </option>

                    <option>
                        Chờ xác nhận
                    </option>

                    <option>
                        Đã xác nhận
                    </option>

                    <option>
                        Đề xuất giờ khác
                    </option>

                    <option>
                        Đã xem phòng
                    </option>

                    <option>
                        Đã từ chối
                    </option>

                    <option>
                        Đã hủy
                    </option>

                </select>

            </div>



            <!-- APPOINTMENT TABLE -->

            <div class="table-responsive">

                <table class="data-table">

                    <thead>

                        <tr>

                            <th>
                                Phòng
                            </th>

                            <th>
                                Ngày
                            </th>

                            <th>
                                Giờ
                            </th>

                            <th>
                                Liên hệ
                            </th>

                            <th>
                                Trạng thái
                            </th>

                            <th>
                                Thao tác
                            </th>

                        </tr>

                    </thead>



                    <tbody>
                        @forelse ($appointments as $appointment)
                        <tr class="appointment-row" data-status="{{ $appointment->status }}">
                            <td>
                                <div class="cell-title">
                                    {{ $appointment->room?->name ?? 'Phòng đã cập nhật' }}
                                </div>
                                <div class="cell-sub">
                                    {{ $appointment->room?->property?->address ?? 'Chưa có địa chỉ' }}
                                </div>
                            </td>

                            <td>
                                {{ $appointment->appointment_date?->format('d/m/Y') }}
                            </td>

                            <td>
                                {{ substr((string) $appointment->appointment_time, 0, 5) }}
                            </td>

                            <td>{{ $appointment->phone }}</td>

                            <td>
                                @php
                                $statusLabels = [
                                'pending' => ['Chờ xác nhận', 'pending'],
                                'confirmed' => ['Đã xác nhận', 'occupied'],
                                'rescheduled' => ['Đề xuất giờ khác', 'new'],
                                'completed' => ['Đã xem phòng', 'resolved'],
                                'rejected' => ['Đã từ chối', 'overdue'],
                                'cancelled' => ['Đã hủy', 'overdue'],
                                ];

                                $statusInfo = $statusLabels[$appointment->status]
                                ?? [$appointment->status, 'pending'];
                                @endphp

                                <span class="badge-status {{ $statusInfo[1] }}">
                                    {{ $statusInfo[0] }}
                                </span>
                            </td>

                            <td>
                                @if (in_array($appointment->status, ['pending', 'confirmed']))
                                <form
                                    action="{{ route('appointments.destroy', $appointment->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Em có chắc muốn hủy lịch hẹn này không?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn-outline-brand">
                                        Hủy lịch
                                    </button>
                                </form>
                                @else
                                <span class="cell-sub">
                                    {{ $appointment->owner_note ?? 'Không có ghi chú' }}
                                </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="mb-3" style="font-size: 40px;">
                                    📅
                                </div>

                                <div class="cell-title mb-2">
                                    Chưa có lịch xem phòng nào
                                </div>

                                <div class="cell-sub mb-3">
                                    Hãy đặt lịch để chủ nhà xác nhận thời gian xem phòng phù hợp.
                                </div>

                                <a
                                    href="{{ route('appointments.create') }}"
                                    class="btn-brand text-decoration-none d-inline-flex">
                                    + Đặt lịch xem phòng
                                </a>
                            </td>
                        </tr>
                        @endforelse
                        <tr id="noAppointmentsFilter" style="display: none;">
                            <td colspan="6" class="text-center text-muted py-4">
                                Không tìm thấy lịch hẹn phù hợp trạng thái này.
                            </td>
                        </tr>
                    </tbody>


                </table>

            </div>

        </div>



        <!-- =====================================================
         STATUS GUIDE
    ====================================================== -->

        <div class="panel">

            <div class="panel-head">

                <h3 class="panel-title">
                    Trạng thái lịch xem phòng
                </h3>

            </div>


            <div class="row g-3">


                <div class="col-md-4">

                    <div
                        class="p-3"
                        style="
                        background:var(--yellow-light);
                        border-radius:14px;
                    ">

                        <span class="badge-status pending">
                            PENDING
                        </span>

                        <div class="cell-title mt-2">
                            Chờ xác nhận
                        </div>

                        <div class="cell-sub">
                            Lịch đang chờ chủ nhà xử lý.
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div
                        class="p-3"
                        style="
                        background:var(--green-soft);
                        border-radius:14px;
                    ">

                        <span class="badge-status occupied">
                            CONFIRMED
                        </span>

                        <div class="cell-title mt-2">
                            Đã xác nhận
                        </div>

                        <div class="cell-sub">
                            Chủ nhà đã xác nhận lịch xem.
                        </div>

                    </div>

                </div>


                <div class="col-md-4">

                    <div
                        class="p-3"
                        style="
                        background:var(--blue-soft);
                        border-radius:14px;
                    ">

                        <span class="badge-status new">
                            RESCHEDULED
                        </span>

                        <div class="cell-title mt-2">
                            Đề xuất giờ khác
                        </div>

                        <div class="cell-sub">
                            Chủ nhà đã đề xuất thời gian mới.
                        </div>

                    </div>

                </div>


            </div>

        </div>

    </div>


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabBtns = document.querySelectorAll('.segmented-tab');
        const rows = document.querySelectorAll('.data-table tbody tr.appointment-row');
        const noResults = document.getElementById('noAppointmentsFilter');
        const statusSelect = document.querySelector('.panel .panel-head select.form-select');

        function filterAppointments(targetStatus) {
            let visibleCount = 0;
            rows.forEach(row => {
                const status = row.getAttribute('data-status') || '';
                const match = !targetStatus || status === targetStatus;
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            if (noResults) {
                noResults.style.display = (rows.length > 0 && visibleCount === 0) ? '' : 'none';
            }
        }

        tabBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                tabBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                const status = this.getAttribute('data-status') || '';
                filterAppointments(status);
            });
        });
    });
</script>
@endpush

@endsection
  