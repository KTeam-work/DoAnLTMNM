<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yêu cầu mở khóa tài khoản | Trọ Ơi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/auth/unlock_request.css') }}">
</head>
<body>

<div class="unlock-card">
    <div class="card-top-bar"></div>

    <div class="card-header-inner">
        <a href="{{ url('/') }}" class="logo-text">Trọ <span>Ơi</span></a>
    </div>

    <div class="card-body-inner">
        @if (session('success'))
            <div class="alert alert-success rounded-3 mb-4 text-center">
                <div class="fw-bold mb-1" style="font-size: 15px;">Yêu cầu đã được gửi thành công!</div>
                <div style="font-size: 13px;">{{ session('success') }}</div>
            </div>
            <div class="text-center mt-3">
                <a href="{{ url('/') }}" class="btn-home">Về trang chủ</a>
            </div>

        @elseif ($invalidToken)
            <div class="text-center py-3">
                <h5 class="fw-bold text-danger mb-2">Liên kết không hợp lệ</h5>
                <p class="text-muted" style="font-size: 13.5px; line-height: 1.6;">
                    Đường dẫn yêu cầu mở khóa này không tồn tại hoặc đã hết hạn sử dụng. Nếu tài khoản của bạn đã được kích hoạt lại hoặc cần hỗ trợ thêm, vui lòng liên hệ quản trị viên.
                </p>
                <div class="mt-4">
                    <a href="{{ url('/') }}" class="btn-home">Về trang chủ</a>
                    <a href="{{ route('login') }}" class="btn btn-dark btn-sm ms-2 px-3 py-2" style="border-radius: 8px;">Đăng nhập</a>
                </div>
            </div>

        @elseif ($isAlreadyActive)
            <div class="text-center py-3">
                <h5 class="fw-bold text-success mb-2">Tài khoản đang hoạt động</h5>
                <p class="text-muted" style="font-size: 13.5px; line-height: 1.6;">
                    Tài khoản <strong>{{ $user->email }}</strong> hiện đang trong trạng thái hoạt động bình thường, không bị khóa. Bạn có thể đăng nhập ngay bây giờ.
                </p>
                <div class="mt-4">
                    <a href="{{ route('login') }}" class="btn btn-dark px-4 py-2 fw-bold" style="border-radius: 8px; font-size: 14px;">Đăng nhập ngay</a>
                </div>
            </div>

        @elseif ($hasRequested)
            <div class="text-center py-3">
                <h5 class="fw-bold text-warning mb-2" style="color: #b45309 !important;">Yêu cầu đang chờ duyệt</h5>
                <p class="text-muted" style="font-size: 13.5px; line-height: 1.6;">
                    Bạn đã gửi yêu cầu mở khóa cho tài khoản <strong>{{ $user->email }}</strong> vào lúc <strong>{{ $user->unlock_requested_at?->format('H:i d/m/Y') }}</strong>.
                </p>
                <div class="alert alert-warning text-start" style="font-size: 13px; line-height: 1.6; border-radius: 10px;">
                    Ban quản trị Trọ Ơi đang xem xét yêu cầu mở khóa của bạn. Kết quả sẽ được gửi thông báo trực tiếp qua địa chỉ Gmail của bạn.
                </div>
                @if ($user->unlock_reason)
                    <div class="text-start mb-3 p-2 bg-light rounded border" style="font-size: 12.5px;">
                        <span class="text-muted">Lời nhắn đã gửi:</span>
                        <div class="fw-bold text-dark mt-1">{{ $user->unlock_reason }}</div>
                    </div>
                @endif
                <div class="mt-3">
                    <a href="{{ url('/') }}" class="btn-home">Về trang chủ</a>
                </div>
            </div>

        @else
            <div>
                <h4 class="fw-bold text-dark text-center mb-1" style="font-size: 20px;">Xác nhận yêu cầu mở khóa</h4>
                <p class="text-muted text-center mb-4" style="font-size: 13px;">Gửi yêu cầu xem xét mở khóa tài khoản đến Ban quản trị Trọ Ơi.</p>

                <div class="info-pill-box">
                    <div class="info-row">
                        <span class="text-muted">Họ và tên:</span>
                        <span class="fw-bold text-dark">{{ $user->name }}</span>
                    </div>
                    <div class="info-row">
                        <span class="text-muted">Địa chỉ email:</span>
                        <span class="fw-bold text-dark">{{ $user->email }}</span>
                    </div>
                    @if ($user->user_code)
                    <div class="info-row">
                        <span class="text-muted">Mã tài khoản:</span>
                        <span class="font-monospace fw-bold text-primary">{{ $user->user_code }}</span>
                    </div>
                    @endif
                    <div class="info-row">
                        <span class="text-muted">Tình trạng:</span>
                        <span class="badge bg-danger py-1 px-2" style="font-size: 11px;">Đang bị khóa</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('account.unlock.submit', $user->unlock_token) }}">
                    @csrf

                    <div class="mb-3">
                        <label for="reason" class="form-label fw-bold" style="font-size: 13px;">
                            Lời nhắn / Lý do xin mở khóa (tùy chọn)
                        </label>
                        <textarea
                            class="form-control"
                            id="reason"
                            name="reason"
                            rows="3"
                            maxlength="500"
                            placeholder="Ví dụ: Tôi cam kết tuân thủ quy định đăng tin và nội quy nền tảng..."
                            style="font-size: 13.5px; border-radius: 10px;"
                        >{{ old('reason') }}</textarea>
                        @error('reason')
                            <div class="text-danger mt-1" style="font-size: 12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn-submit-unlock">
                        Gửi yêu cầu mở khóa tài khoản
                    </button>
                </form>

                <div class="text-center mt-3">
                    <a href="{{ url('/') }}" class="text-muted text-decoration-none" style="font-size: 12.5px;">
                        Quay lại trang chủ
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

</body>
</html>
