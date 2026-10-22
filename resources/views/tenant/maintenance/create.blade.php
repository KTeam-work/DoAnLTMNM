

@extends('layout.tenant')

@section('title', 'Tổng quan | Trọ Ơi')

@push('styles')
     <link rel="stylesheet"
          href="{{ asset('css/tv3/tenant/maintenance/create.css') }}">
@endpush

@section('content')

<div class="page-wrap" style="padding-top: 40px; max-width: 900px;">
  
  <div class="mb-4">
    <a href="{{ route('tenant.maintenance.index') }}" class="text-decoration-none text-muted fw-bold" style="font-size: 13px;">← QUAY LẠI TRUNG TÂM HỖ TRỢ</a>
    <h2 class="page-title mt-2" style="font-size: 32px;">Tạo Ticket Sự Cố Mới</h2>
    <p class="text-muted mt-2" style="font-size: 14px;">Mô tả chi tiết tình trạng để Ban quản lý có thể chuẩn bị vật tư và khắc phục nhanh nhất.</p>
  </div>

  <div class="form-panel">
    @if($errors->any())
      <div class="alert alert-danger mb-4">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
    @if($contracts->isEmpty())
      <div class="alert alert-warning">Bạn chưa có hợp đồng nào để tạo yêu cầu. Vui lòng liên hệ chủ trọ.</div>
    @else
    <form action="{{ route('tenant.maintenance.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-4">
        <!-- Hợp đồng liên quan -->
        <div class="col-12">
          <label class="form-label">Hợp đồng liên quan <span class="text-danger">*</span></label>
          <select name="contract_id" id="contractSelect" class="form-select fw-bold text-dark" required>
            @foreach($contracts as $contract)
              <option value="{{ $contract->id }}" data-room="{{ $contract->room->name ?? '' }}"
                @selected((string) old('contract_id', $prefillContractId) === (string) $contract->id)>
                {{ $contract->contract_code }} — {{ $contract->room->name ?? '' }}
              </option>
            @endforeach
          </select>
        </div>

        <!-- Section 1: Thông tin cơ bản -->
        <div class="col-md-6">
          <label class="form-label">Phòng tiếp nhận sự cố</label>
          <div class="form-control fw-bold text-dark d-flex align-items-center" style="background: #f0f0f0; opacity: 0.8;">
            <span style="margin-right: 10px;">🏠</span> <span id="roomName">—</span>
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Phân loại sự cố <span class="text-danger">*</span></label>
          <select name="category" class="form-select fw-bold text-dark" required>
            <option value="" disabled @selected(!old('category'))>-- Vui lòng chọn hệ thống --</option>
            <option value="dien_nuoc" @selected(old('category') === 'dien_nuoc')>💧 Hệ thống Điện / Nước</option>
            <option value="dien_lanh" @selected(old('category') === 'dien_lanh')>❄️ Thiết bị Điện lạnh (Điều hòa, Tủ lạnh)</option>
            <option value="noi_that" @selected(old('category') === 'noi_that')>🚪 Nội thất (Giường, Tủ, Cửa, Khóa)</option>
            <option value="khac" @selected(old('category') === 'khac')>🔧 Vấn đề khác</option>
          </select>
        </div>

        <div class="col-12">
          <label class="form-label">Tiêu đề <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control fw-bold" value="{{ old('title') }}" placeholder="VD: Điều hòa không mát" required maxlength="200">
        </div>

        <!-- Section 2: Enterprise Priority Cards -->
        <div class="col-12 mt-5">
          <label class="form-label">Đánh giá mức độ nghiêm trọng</label>
          <div class="row g-3 mt-1">
            <div class="col-md-6">
              <label class="w-100 h-100 m-0">
                <input type="radio" name="priority" value="low" class="radio-card-input" @checked(old('priority') === 'low')>
                <div class="radio-card normal">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size: 20px;">🐢</span>
                    <h6 class="fw-bold mb-0 text-dark">Thấp</h6>
                  </div>
                  <p class="text-muted mb-0" style="font-size: 13px;">Việc nhỏ, lúc nào rảnh làm cũng được.</p>
                </div>
              </label>
            </div>
            <div class="col-md-6">
              <label class="w-100 h-100 m-0">
                <input type="radio" name="priority" value="medium" class="radio-card-input" @checked(old('priority', 'medium') === 'medium')>
                <div class="radio-card normal">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size: 20px;">🛡️</span>
                    <h6 class="fw-bold mb-0 text-dark">Bình thường</h6>
                  </div>
                  <p class="text-muted mb-0" style="font-size: 13px;">Sự cố nhỏ, không ảnh hưởng trực tiếp đến sinh hoạt. Xử lý trong 24-48h.</p>
                </div>
              </label>
            </div>
            <div class="col-md-6">
              <label class="w-100 h-100 m-0">
                <input type="radio" name="priority" value="high" class="radio-card-input" @checked(old('priority') === 'high')>
                <div class="radio-card urgent">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size: 20px;">⚠️</span>
                    <h6 class="fw-bold mb-0 text-danger">Cao</h6>
                  </div>
                  <p class="text-muted mb-0" style="font-size: 13px;">Ảnh hưởng sinh hoạt, cần xử lý sớm trong ngày.</p>
                </div>
              </label>
            </div>
            <div class="col-md-6">
              <label class="w-100 h-100 m-0">
                <input type="radio" name="priority" value="urgent" class="radio-card-input" @checked(old('priority') === 'urgent')>
                <div class="radio-card urgent">
                  <div class="d-flex align-items-center gap-3 mb-2">
                    <span style="font-size: 20px;">🚨</span>
                    <h6 class="fw-bold mb-0 text-danger">Khẩn cấp</h6>
                  </div>
                  <p class="text-muted mb-0" style="font-size: 13px;">Không thể sinh hoạt (mất điện, vỡ ống nước...). Xử lý ngay lập tức.</p>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Section 3: Chi tiết & Đính kèm -->
        <div class="col-12 mt-5">
          <label class="form-label">Mô tả hiện trạng <span class="text-danger">*</span></label>
          <textarea name="description" class="form-control" rows="5" placeholder="Ví dụ: Điều hòa bật vẫn lên nguồn nhưng không phả ra hơi lạnh, cục nóng bên ngoài kêu rất to..." required style="resize: none;">{{ old('description') }}</textarea>
        </div>

        <div class="col-12">
          <label class="form-label">Tài liệu đính kèm / Hình ảnh hiện trường (Tùy chọn)</label>
          <div class="upload-zone" id="uploadZone">
            <div class="upload-icon">📁</div>
            <div>
              <div class="fw-bold text-dark" style="font-size: 15px;">Kéo thả file hoặc nhấn để tải lên</div>
              <div class="text-muted mt-1" style="font-size: 13px;" id="uploadHint">Hỗ trợ định dạng: JPG, PNG (Tối đa 10MB)</div>
            </div>
            <input type="file" name="image" id="imageInput" accept="image/*" class="d-none">
          </div>
        </div>
      </div>

      <hr style="border-top: 1px solid #e0e0e0; margin: 40px 0 30px;">

      <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="policy" required style="cursor: pointer;">
          <label class="form-check-label text-muted" for="policy" style="font-size: 13px; cursor: pointer;">
            Tôi xác nhận các thông tin báo cáo trên là hoàn toàn chính xác.
          </label>
        </div>
        <div>
          <a href="{{ route('tenant.maintenance.index') }}" class="btn btn-light fw-bold px-4 py-3 me-2" style="border-radius: 12px; font-size: 14px;">Hủy bỏ</a>
          <button type="submit" class="btn btn-brand px-5 py-3" style="font-size: 14px; box-shadow: 0 4px 15px rgba(245,200,75,0.4);">🚀 Gửi Ticket</button>
        </div>
      </div>
    </form>
    @endif
  </div>

</div>

@push('scripts')
    <script src="{{ asset('js/tv3/tenant/maintencance/create.js') }}"></script>
@endpush


@endsection