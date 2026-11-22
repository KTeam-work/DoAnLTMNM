@extends('layout.tenant')

@section('title', 'Đặt lịch xem phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/appointment/create.css') }}?v={{ file_exists(public_path('css/tv3/appointment/create.css')) ? filemtime(public_path('css/tv3/appointment/create.css')) : time() }}">
@endpush

@section('content')

    <main class="appointment-create-page">

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (isset($errors) && $errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3" role="alert">
                <div class="fw-bold mb-1">Vui lòng kiểm tra lại thông tin:</div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- QUAY LẠI --}}
        <div class="mb-4">
            @if ($selectedRoom)
                <a href="{{ route('rooms.show', ['id' => $selectedRoom->id]) }}" class="back-link-custom">
                    ← Quay lại phòng
                </a>
            @else
                <a href="{{ route('rooms.index') }}" class="back-link-custom">
                    ← Quay lại danh sách phòng
                </a>
            @endif
        </div>

        {{-- TIÊU ĐỀ --}}
        <div class="mb-4">
            <span class="badge-tag-clean">
                Đặt lịch xem phòng
            </span>

            <h1 class="appointment-page-title">
                Chọn thời gian bạn muốn xem phòng
            </h1>

            <div class="appointment-page-desc">
                Gửi lịch hẹn để chủ nhà xác nhận thời gian phù hợp.
            </div>
        </div>

        @if ($rooms->isEmpty())
            <div class="appointment-card text-center py-5">
                <h2 style="color: #0c2340; font-weight: 800; font-size: 20px; margin-bottom: 8px;">
                    Hiện chưa có phòng phù hợp
                </h2>

                <p class="appointment-page-desc mb-4">
                    Chưa có phòng đang được đăng cho thuê hoặc phòng chưa đáp ứng điều kiện đặt lịch.
                </p>

                <a href="{{ route('rooms.index') }}" class="btn-submit-clean text-decoration-none">
                    Tìm phòng khác
                </a>
            </div>
        @else
            <div class="row g-4">

                {{-- CỘT TRÁI: THÔNG TIN PHÒNG --}}
                <div class="col-lg-5">
                    <div class="appointment-card">
                        <div class="card-header-clean">
                            <h2>Phòng bạn muốn xem</h2>
                            <span class="badge-vacant-clean">Còn phòng</span>
                        </div>

                        @if ($selectedRoom)
                            @php
                                $roomName = $selectedRoom->name ?? 'Phòng #' . $selectedRoom->id;
                                $roomAddress = $selectedRoom->property?->address ?? 'Chưa cập nhật địa chỉ';
                                $roomArea = $selectedRoom->area ?? null;
                                $roomPrice = $selectedRoom->price ?? null;

                                // Lấy ảnh phòng hoặc ảnh bài đăng
                                $previewImg = null;
                                $activePost = $selectedRoom->rentalPosts->first();
                                if ($activePost && $activePost->images && $activePost->images->isNotEmpty()) {
                                    $previewImg = $activePost->images->first()->image_path;
                                }
                                if (!$previewImg && !empty($selectedRoom->images)) {
                                    $rImgs = is_array($selectedRoom->images) ? $selectedRoom->images : json_decode($selectedRoom->images, true);
                                    $previewImg = $rImgs[0] ?? null;
                                }
                                if (!$previewImg && $selectedRoom->property && $selectedRoom->property->images && $selectedRoom->property->images->isNotEmpty()) {
                                    $previewImg = $selectedRoom->property->images->first()->image_path;
                                }

                                if ($previewImg) {
                                    if (!\Illuminate\Support\Str::startsWith($previewImg, ['http://', 'https://', '//'])) {
                                        $previewImg = asset(\Illuminate\Support\Str::startsWith($previewImg, ['storage/', '/storage/']) ? ltrim($previewImg, '/') : 'storage/' . ltrim($previewImg, '/'));
                                    }
                                } else {
                                    $previewImg = 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80';
                                }
                            @endphp

                            <img src="{{ $previewImg }}" alt="{{ $roomName }}" class="room-preview-thumb" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80';">

                            <div class="room-title-highlight">
                                {{ $roomName }}
                            </div>

                            <div class="room-address-text">
                                {{ $roomAddress }}
                            </div>

                            <div class="room-specs-pill">
                                <div>
                                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Diện tích</div>
                                    <div style="font-size: 14.5px; font-weight: 700; color: #0c2340;">{{ $roomArea ? $roomArea . ' m²' : '—' }}</div>
                                </div>
                                <div class="text-end">
                                    <div style="font-size: 11px; font-weight: 700; text-transform: uppercase; color: #64748b;">Giá niêm yết</div>
                                    <div class="room-price-val">
                                        @if (is_numeric($roomPrice))
                                            {{ number_format((float) $roomPrice, 0, ',', '.') }} đ
                                        @else
                                            Liên hệ
                                        @endif
                                        <span>/ tháng</span>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="{{ route('rooms.show', ['id' => $selectedRoom->id]) }}" class="btn-detail-clean">
                                    Xem chi tiết phòng
                                </a>
                            </div>
                        @else
                            <div class="p-4 text-center text-muted" style="font-size: 13.5px;">
                                Vui lòng chọn một phòng trong danh sách bên cạnh để xem thông tin tóm tắt tại đây.
                            </div>
                        @endif
                    </div>

                    {{-- LƯU Ý BÊN TRÁI --}}
                    <div class="appointment-card mt-4">
                        <div class="card-header-clean">
                            <h3>Lưu ý khi đặt lịch</h3>
                        </div>

                        <div class="notice-box-clean">
                            Sau khi gửi lịch hẹn, yêu cầu sẽ được chuyển đến chủ nhà ở trạng thái <strong>Chờ xác nhận</strong>. Chủ nhà sẽ liên hệ theo số điện thoại của bạn để thống nhất lịch đón tiếp.
                        </div>
                    </div>
                </div>

                {{-- CỘT PHẢI: FORM --}}
                <div class="col-lg-7">
                    <div class="appointment-card">
                        <div class="card-header-clean">
                            <div>
                                <h2>Thông tin lịch hẹn</h2>
                                <div class="appointment-page-desc">
                                    Vui lòng điền đầy đủ và chính xác thông tin hẹn.
                                </div>
                            </div>
                        </div>

                        <form id="appointmentCreateForm" action="{{ route('appointments.store') }}" method="POST">
                            @csrf

                            {{-- CHỌN PHÒNG --}}
                            <div class="mb-3">
                                <label for="room_id" class="form-label">
                                    Phòng muốn xem <span class="text-danger">*</span>
                                </label>

                                <select
                                    name="room_id"
                                    id="room_id"
                                    class="form-select @error('room_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn phòng muốn xem --</option>
                                    @foreach ($rooms as $item)
                                        <option
                                            value="{{ $item->id }}"
                                            @selected((string) old('room_id', $selectedRoom?->id) === (string) $item->id)>
                                            {{ $item->name ?? 'Phòng #' . $item->id }}
                                            @if ($item->property?->address)
                                                — {{ $item->property->address }}
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
                                    <label for="appointment_date" class="form-label">
                                        Ngày xem phòng <span class="text-danger">*</span>
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
                                    <label for="appointment_time" class="form-label">
                                        Giờ xem phòng <span class="text-danger">*</span>
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

                                    <div class="form-text">
                                        Nên chọn khoảng thời gian từ 08:00 đến 19:00.
                                    </div>
                                </div>
                            </div>

                            {{-- SỐ ĐIỆN THOẠI --}}
                            <div class="mt-3">
                                <label for="phone" class="form-label">
                                    Số điện thoại liên hệ <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="tel"
                                    inputmode="numeric"
                                    id="phone"
                                    name="phone"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                    maxlength="10"
                                    pattern="0[0-9]{9}"
                                    placeholder="VD: 0901234567"
                                    title="Số điện thoại gồm 10 số bắt đầu bằng 0"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);"
                                    required>

                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                <div class="form-text">
                                    Chủ nhà sẽ liên hệ số này để xác nhận lịch hẹn (đúng 10 chữ số bắt đầu bằng số 0).
                                </div>
                            </div>

                            {{-- LỜI NHẮN --}}
                            <div class="mt-3">
                                <label for="message" class="form-label">
                                    Lời nhắn cho chủ nhà
                                </label>

                                <textarea
                                    id="message"
                                    name="message"
                                    rows="4"
                                    maxlength="1000"
                                    class="form-control @error('message') is-invalid @enderror"
                                    placeholder="Nhập câu hỏi hoặc yêu cầu thêm của bạn (nếu có)...">{{ old('message') }}</textarea>

                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- NÚT THAO TÁC --}}
                            <div class="d-flex justify-content-end gap-3 mt-4 flex-wrap">
                                <a
                                    href="{{ $selectedRoom ? route('rooms.show', ['id' => $selectedRoom->id]) : route('appointments.index') }}"
                                    class="btn-cancel-clean">
                                    Hủy bỏ
                                </a>

                                <button type="submit" class="btn-submit-clean">
                                    Gửi lịch hẹn
                                </button>
                            </div>
                        </form>
                    </div>

                    {{-- QUY TRÌNH 3 BƯỚC --}}
                    <div class="process-card-clean mt-4">
                        <div class="card-header-clean mb-3">
                            <h3>Quy trình xem phòng</h3>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="d-flex gap-2 align-items-start">
                                    <div class="step-num-circle">1</div>
                                    <div>
                                        <div class="step-title-text">Gửi lịch hẹn</div>
                                        <div class="step-desc-text">
                                            Chọn phòng, ngày và giờ muốn đến xem trực tiếp.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex gap-2 align-items-start">
                                    <div class="step-num-circle">2</div>
                                    <div>
                                        <div class="step-title-text">Chủ nhà xác nhận</div>
                                        <div class="step-desc-text">
                                            Chủ nhà xem và phản hồi lại thời gian phù hợp.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="d-flex gap-2 align-items-start">
                                    <div class="step-num-circle">3</div>
                                    <div>
                                        <div class="step-title-text">Đến xem phòng</div>
                                        <div class="step-desc-text">
                                            Trải nghiệm thực tế không gian phòng trọ.
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('appointmentCreateForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    var phoneInput = document.getElementById('phone');
                    if (phoneInput) {
                        var phoneVal = phoneInput.value.trim();
                        if (!/^0[0-9]{9}$/.test(phoneVal)) {
                            e.preventDefault();
                            alert('Số điện thoại không hợp lệ! Vui lòng nhập đúng 10 chữ số và bắt đầu bằng số 0.');
                            phoneInput.focus();
                            return false;
                        }
                    }
                });
            }
        });
    </script>
@endpush

@endsection
