


@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     
@endpush

@section('content')



    <main class="page-wrap">

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div class="alert alert-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mt-3">
                <div class="fw-bold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- QUAY LẠI --}}
        <div class="mb-4">
            @if ($selectedRoom)
                <a
                    href="{{ route('rooms.show', ['id' => $selectedRoom->id]) }}"
                    class="section-link text-decoration-none">
                    ← Quay lại phòng
                </a>
            @else
                <a
                    href="{{ route('rooms.index') }}"
                    class="section-link text-decoration-none">
                    ← Quay lại danh sách phòng
                </a>
            @endif
        </div>

        {{-- TIÊU ĐỀ --}}
        <div class="page-header">
            <div>
                <div class="badge-status pending mb-2">
                    📅 ĐẶT LỊCH XEM PHÒNG
                </div>

                <h1 class="page-title">
                    Chọn thời gian bạn muốn xem phòng
                </h1>

                <div class="page-desc">
                    Gửi lịch hẹn để chủ nhà xác nhận thời gian phù hợp.
                </div>
            </div>
        </div>

        @if ($rooms->isEmpty())

            <div class="panel text-center py-5">
                <div class="stat-icon mx-auto mb-3">🏠</div>

                <h2 class="panel-title mb-2">
                    Hiện chưa có phòng phù hợp
                </h2>

                <p class="page-desc mb-4">
                    Chưa có phòng đang được đăng cho thuê hoặc phòng chưa đáp ứng điều kiện đặt lịch.
                </p>

                <a
                    href="{{ route('rooms.index') }}"
                    class="btn-brand text-decoration-none d-inline-block">
                    ← Tìm phòng khác
                </a>
            </div>

        @else

            <div class="row g-4">

                {{-- CỘT TRÁI: THÔNG TIN PHÒNG --}}
                <div class="col-lg-5">
                    <div class="panel">

                        <div class="panel-head">
                            <h2 class="panel-title">Phòng bạn muốn xem</h2>
                            <span class="badge-status vacant">Còn phòng</span>
                        </div>

                        @if ($selectedRoom)

                            @php
                                $roomName = $selectedRoom->name ?? 'Phòng #' . $selectedRoom->id;
                                $roomAddress = $selectedRoom->property?->address ?? 'Chưa cập nhật địa chỉ';
                                $roomArea = $selectedRoom->area ?? null;
                                $roomPrice = $selectedRoom->price ?? null;
                            @endphp

                            <div class="mt-3">
                                <div class="room-title">
                                    {{ $roomName }}
                                </div>

                                <div class="room-address">
                                    📍 {{ $roomAddress }}
                                </div>

                                <div class="room-stats mt-3">
                                    @if ($roomArea !== null)
                                        <span>📐 {{ $roomArea }} m²</span>
                                    @endif
                                </div>

                                <div class="room-bottom mt-3">
                                    <div class="room-price">
                                        @if (is_numeric($roomPrice))
                                            {{ number_format((float) $roomPrice, 0, ',', '.') }} đ
                                        @else
                                            Liên hệ chủ nhà
                                        @endif
                                        <small>/ tháng</small>
                                    </div>

                                    <a
                                        href="{{ route('rooms.show', ['id' => $selectedRoom->id]) }}"
                                        class="detail-btn text-decoration-none">
                                        Xem phòng
                                    </a>
                                </div>
                            </div>

                        @else

                            <p class="cell-sub mt-3 mb-0">
                                Em hãy chọn phòng trong danh sách bên cạnh để xem thông tin phòng tại đây.
                            </p>

                        @endif
                    </div>

                    <div class="panel mt-4">
                        <div class="panel-head">
                            <h3 class="panel-title">Lưu ý</h3>
                        </div>

                        <div class="cell-sub">
                            Sau khi gửi lịch hẹn, lịch sẽ ở trạng thái
                            <strong>Chờ xác nhận</strong>. Chủ nhà sẽ xem và phản hồi lịch hẹn.
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: FORM --}}
                <div class="col-lg-7">
                    <div class="panel">

                        <div class="panel-head">
                            <div>
                                <h2 class="panel-title">Thông tin lịch hẹn</h2>
                                <div class="page-desc">
                                    Vui lòng điền đầy đủ thông tin.
                                </div>
                            </div>
                        </div>

                        <form
                            action="{{ route('appointments.store') }}"
                            method="POST">
                            @csrf

                            {{-- CHỌN PHÒNG --}}
                            <div class="mb-3">
                                <label for="room_id" class="form-label fw-bold small">
                                    Phòng muốn xem
                                </label>

                                <select
                                    name="room_id"
                                    id="room_id"
                                    class="form-select @error('room_id') is-invalid @enderror"
                                    required>

                                    <option value="">-- Chọn phòng --</option>

                                    @foreach ($rooms as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            @selected(
                                                (string) old('room_id', $selectedRoom?->id)
                                                === (string) $item->id
                                            )>
                                            {{ $item->name ?? 'Phòng #' . $item->id }}
                                            @if ($item->property?->address)
                                                - {{ $item->property->address }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                @error('room_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- NGÀY VÀ GIỜ --}}
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label
                                        for="appointment_date"
                                        class="form-label fw-bold small">
                                        Ngày xem phòng
                                    </label>

                                    <input
                                        type="date"
                                        id="appointment_date"
                                        name="appointment_date"
                                        class="form-control @error('appointment_date') is-invalid @enderror"
                                        min="{{ now()->format('Y-m-d') }}"
                                        value="{{ old('appointment_date') }}"
                                        required>

                                    @error('appointment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror

                                    <div class="form-text">
                                        Không chọn ngày trong quá khứ.
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label
                                        for="appointment_time"
                                        class="form-label fw-bold small">
                                        Giờ xem phòng
                                    </label>

                                    <input
                                        type="time"
                                        id="appointment_time"
                                        name="appointment_time"
                                        class="form-control @error('appointment_time') is-invalid @enderror"
                                        value="{{ old('appointment_time') }}"
                                        required>

                                    @error('appointment_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- SỐ ĐIỆN THOẠI --}}
                            <div class="mt-3">
                                <label
                                    for="phone"
                                    class="form-label fw-bold small">
                                    Số điện thoại liên hệ
                                </label>

                                <input
                                    type="tel"
                                    id="phone"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                    maxlength="20"
                                    required>

                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">
                                    Chủ nhà sẽ sử dụng số này để liên hệ xác nhận lịch.
                                </div>
                            </div>

                            {{-- LỜI NHẮN --}}
                            <div class="mt-3">
                                <label
                                    for="message"
                                    class="form-label fw-bold small">
                                    Lời nhắn cho chủ nhà
                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="5"
                                    maxlength="1000"
                                    class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Nhập câu hỏi hoặc yêu cầu của bạn...">{{ old('message') }}</textarea>

                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">
                                    Không bắt buộc.
                                </div>
                            </div>

                            {{-- GHI CHÚ --}}
                            <div class="panel mt-4">
                                <div class="d-flex gap-3">
                                    <div class="stat-icon">💡</div>

                                    <div>
                                        <div class="cell-title">
                                            Lưu ý trước khi gửi lịch
                                        </div>

                                        <div class="cell-sub mt-1">
                                            Lịch hẹn sẽ ở trạng thái
                                            <strong>Chờ xác nhận</strong> sau khi gửi.
                                            Chủ nhà sẽ xác nhận hoặc phản hồi lịch hẹn.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- NÚT --}}
                            <div class="d-flex justify-content-end gap-2 mt-4 flex-wrap">

                                <a
                                    href="{{ $selectedRoom
                                        ? route('rooms.show', ['id' => $selectedRoom->id])
                                        : route('appointments.index') }}"
                                    class="btn-outline-brand text-decoration-none">
                                    Hủy
                                </a>

                                <button type="submit" class="btn-brand">
                                    📅 Gửi lịch hẹn
                                </button>

                            </div>
                        </form>
                    </div>

                    {{-- QUY TRÌNH --}}
                    <div class="panel mt-4">
                        <div class="panel-head">
                            <h3 class="panel-title">Quy trình đặt lịch</h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <div class="avatar-sm">1</div>
                                    <div>
                                        <div class="cell-title">Gửi lịch</div>
                                        <div class="cell-sub">
                                            Chọn phòng, ngày và giờ muốn xem.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <div class="avatar-sm">2</div>
                                    <div>
                                        <div class="cell-title">Chủ nhà xử lý</div>
                                        <div class="cell-sub">
                                            Chủ nhà xem và phản hồi lịch hẹn.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex gap-2">
                                    <div class="avatar-sm">3</div>
                                    <div>
                                        <div class="cell-title">Đến xem phòng</div>
                                        <div class="cell-sub">
                                            Đến xem theo thời gian đã thống nhất.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </main>



@push('scripts')
    <script src="{{ asset('js/tv2/appointments/create.js') }}"></script>
@endpush



    @endsection
  