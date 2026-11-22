
@extends('Layout.landlord')

@section('title', 'Tạo Phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/created.css') }}">
    <link rel="stylesheet" href="{{ asset('css/tv3/modal_premium.css') }}">
@endpush

@section('content')

  <main class="page-wrap">
    <div class="mb-3">
      <a href="{{ route('owner.rooms.index') }}" class="back-link">← Quay lại quản lý phòng</a>
    </div>

    @if ($properties->isEmpty())
    <div class="alert alert-warning">Bạn chưa có khu trọ nào. Hãy tạo khu trọ trước khi thêm phòng.</div>
    @else
    <div class="panel-premium-card">
      <div class="panel-premium-header">
        <div>
          <h2 class="panel-premium-title">Tạo phòng mới</h2>
          <div class="panel-premium-subtitle">Nhập thông tin chi tiết phòng trọ thuộc khu trọ của bạn</div>
        </div>
        <span class="badge bg-light text-dark px-3 py-2 rounded-3 fw-bold" style="font-size: 11.5px;">Phòng mới</span>
      </div>

      <div class="panel-premium-body">
        <form method="POST" action="{{ route('owner.rooms.store') }}">
          @csrf
          @if ($errors->any())
          <div class="alert alert-danger mb-4">
            <ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
          </div>
          @endif

          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label" for="property_id">Khu trọ <span class="text-danger">*</span></label>
              <select id="property_id" name="property_id" class="form-select" required>
                <option value="">Chọn khu trọ</option>
                @foreach ($properties as $property)
                  <option value="{{ $property->id }}" data-room-code="{{ $property->next_room_code }}" @selected(old('property_id')==$property->id)>{{ $property->name }}</option>
                @endforeach
              </select>
            </div>

            <div class="col-md-6">
              <label class="form-label">Tên phòng <span class="text-danger">*</span></label>
              <input name="name" value="{{ old('name') }}" class="form-control" placeholder="Ví dụ: Phòng 101" required>
            </div>

            <div class="col-md-6">
              <label for="room_code_preview" class="form-label">Mã phòng</label>
              <input id="room_code_preview" class="form-control bg-light" value="" placeholder="Chọn khu trọ để xem mã" readonly>
            </div>

            <div class="col-md-6">
              <label class="form-label">Trạng thái ban đầu</label>
              <select name="status" class="form-select">
                <option value="available">Còn trống</option>
                <option value="maintenance">Bảo trì</option>
                <option value="hidden">Ẩn</option>
              </select>
            </div>

            <div class="col-md-4">
              <label class="form-label">
                Diện tích (m²) <span class="text-danger">*</span>
              </label>
              <input
                name="area"
                type="number"
                min="0.01"
                step="0.01"
                value="{{ old('area') }}"
                class="form-control"
                placeholder="Ví dụ: 25"
                required>
            </div>

            <div class="col-md-4">
              <label class="form-label">
                Giá thuê (VNĐ/tháng) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <input
                  id="price"
                  name="price"
                  type="text"
                  value="{{ old('price') ? number_format((float) preg_replace('/[^\d]/', '', old('price')), 0, ',', '.') : '' }}"
                  class="form-control"
                  placeholder="Ví dụ: 3.000.000"
                  required
                  autocomplete="off">
                <span class="input-group-text">đ/tháng</span>
              </div>
              <div
                class="form-text text-primary fw-medium"
                id="pricePreview"
                style="font-size: 12px; min-height: 18px;">
              </div>
            </div>

            <div class="col-md-4">
              <label class="form-label">
                Tiền đặt cọc (VNĐ) <span class="text-danger">*</span>
              </label>
              <div class="input-group">
                <input
                  id="deposit"
                  name="deposit"
                  type="text"
                  value="{{ old('deposit') ? number_format((float) preg_replace('/[^\d]/', '', old('deposit')), 0, ',', '.') : '' }}"
                  class="form-control"
                  placeholder="Ví dụ: 3.000.000"
                  required
                  autocomplete="off">
                <span class="input-group-text">đ</span>
              </div>
              <div
                class="form-text text-primary fw-medium"
                id="depositPreview"
                style="font-size: 12px; min-height: 18px;">
              </div>
              <div
                id="depositError"
                class="text-danger small mt-1"
                style="display: none;">
                Tiền cọc không được lớn hơn tiền thuê phòng.
              </div>
            </div>

            <div class="col-md-6"><label class="form-label">Sức chứa tối đa (người) <span class="text-danger">*</span></label><input name="max_people" type="number" min="1" value="{{ old('max_people', 1) }}" class="form-control" required></div>
            <div class="col-md-6"><label class="form-label">Tầng</label><input name="floor" type="number" min="0" value="{{ old('floor') }}" class="form-control" placeholder="Để trống nếu không áp dụng"></div>
            
            <div class="col-12">
              <label class="form-label">Tiện ích phòng có sẵn</label>
              @if(isset($amenities) && $amenities->isNotEmpty())
                <div class="p-3 bg-light rounded border d-flex flex-wrap gap-3">
                  @foreach($amenities as $amenity)
                    <div class="form-check me-2">
                      <input class="form-check-input" type="checkbox" name="amenities[]" value="{{ $amenity->id }}" id="amenity_{{ $amenity->id }}" @checked(is_array(old('amenities')) && in_array($amenity->id, old('amenities')))>
                      <label class="form-check-label" for="amenity_{{ $amenity->id }}" style="cursor: pointer;">
                        {{ $amenity->name }}
                      </label>
                    </div>
                  @endforeach
                </div>
              @else
                <div class="text-muted small">
                  Chưa có tiện ích nào trong danh mục. Bạn có thể thêm trong mục <a href="{{ route('owner.amenities.index') }}" target="_blank">Quản lý tiện ích</a>.
                </div>
              @endif
            </div>

            <div class="col-12"><label class="form-label">Mô tả phòng</label><textarea name="description" class="form-control" rows="3" placeholder="Nhập thêm thông tin mô tả chi tiết phòng nếu có...">{{ old('description') }}</textarea></div>
            <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top mt-4">
              <a href="{{ route('owner.rooms.index') }}" class="btn btn-light px-4 py-2 rounded-3 border fw-semibold">Hủy</a>
              <button type="submit" class="btn btn-primary px-4 py-2 rounded-3 fw-bold" style="background: #0c2340; border-color: #0c2340;">Thêm phòng mới</button>
            </div>
          </div>
        </form>
      </div>
    </div>
    @endif
  </main>

  <script>
    (() => {
      const propertySelect = document.getElementById('property_id');
      const roomCodeInput = document.getElementById('room_code_preview');
      const priceInput = document.getElementById('price');
      const depositInput = document.getElementById('deposit');
      const depositError = document.getElementById('depositError');
      const form = document.querySelector('form');

      const updateRoomCodePreview = () => {
        const selectedOption = propertySelect.options[propertySelect.selectedIndex];
        roomCodeInput.value = selectedOption?.dataset.roomCode ?? '';
      };


      if (propertySelect) {
        propertySelect.addEventListener('change', updateRoomCodePreview);
        updateRoomCodePreview();
      }

      // Tự động định dạng tiền tệ và hiển thị chữ tiếng Việt
      function setupCurrencyFormatter(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        if (!input || !preview) return;

        function formatWords(num) {
          if (num >= 1e9) {
            const ty = (num / 1e9).toLocaleString('vi-VN', {
              maximumFractionDigits: 2
            });
            return `${ty} tỷ đồng`;
          }
          if (num >= 1e6) {
            const tr = (num / 1e6).toLocaleString('vi-VN', {
              maximumFractionDigits: 2
            });
            return `${tr} triệu đồng`;
          }
          if (num >= 1e3) {
            const k = (num / 1e3).toLocaleString('vi-VN', {
              maximumFractionDigits: 2
            });
            return `${k} nghìn đồng`;
          }
          return `${num.toLocaleString('vi-VN')} đồng`;
        }

        function getRawValue() {
          return input.value.replace(/\D/g, '');
        }

        function update() {
          const raw = getRawValue();

          if (!raw) {
            preview.textContent = '';
            return;
          }

          const num = parseInt(raw, 10);
          input.value = num.toLocaleString('vi-VN');
          preview.textContent = '≈ ' + formatWords(num);
        }

        input.addEventListener('input', update);

        if (input.value) update();
      }

      setupCurrencyFormatter('price', 'pricePreview');
      setupCurrencyFormatter('deposit', 'depositPreview');

      // Kiểm tra tiền cọc không vượt quá giá thuê
      const validateDeposit = () => {
        if (!priceInput || !depositInput) return true;

        const priceRaw = priceInput.value.replace(/\D/g, '');
        const depositRaw = depositInput.value.replace(/\D/g, '');

        const price = priceRaw ? Number(priceRaw) : 0;
        const deposit = depositRaw ? Number(depositRaw) : 0;

        if (price > 0) {
          depositInput.max = price;
        } else {
          depositInput.removeAttribute('max');
        }

        if (
          depositRaw !== '' &&
          price > 0 &&
          deposit > price
        ) {
          depositError.style.display = 'block';
          depositInput.classList.add('is-invalid');
          return false;
        }

        depositError.style.display = 'none';
        depositInput.classList.remove('is-invalid');
        return true;
      };

      if (priceInput && depositInput) {
        priceInput.addEventListener('input', validateDeposit);
        depositInput.addEventListener('input', validateDeposit);
      }

      if (form) {
        form.addEventListener('submit', (e) => {
          if (!validateDeposit()) {
            e.preventDefault();
            alert('Tiền cọc không được lớn hơn tiền thuê phòng.');
            depositInput.focus();
          }
        });
      }

      // Kiểm tra trạng thái ban đầu
      validateDeposit();

    })();
  </script>

@endsection
