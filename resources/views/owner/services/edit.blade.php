
@extends('Layout.landlord')

@section('title', 'Dịch vụ | Trọ Ơi')


@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/services/edit.css') }}">
@endpush

@section('content')

    {{-- =========================================================
         MAIN CONTENT
    ========================================================== --}}
    <main class="service-edit-page">
        <div class="service-edit-container">

            {{-- BACK --}}
            <a href="{{ route('owner.services.index') }}" class="service-back">
                ← Quay lại danh sách dịch vụ
            </a>

            {{-- HEADER --}}
            <div class="service-header">
                <h1 class="service-title">{{ ($mode ?? 'edit') === 'create' ? 'Thêm dịch vụ' : 'Sửa dịch vụ' }}</h1>
                <div class="service-description">{{ ($mode ?? 'edit') === 'create' ? 'Tạo dịch vụ mới áp dụng cho nhà trọ.' : 'Cập nhật thông tin dịch vụ đang áp dụng cho nhà trọ.' }}</div>
            </div>

            {{-- PROPERTY --}}
            <div class="service-property">
                <div class="service-property-icon">🏠</div>
                <div>
                    <div class="service-property-label">Dịch vụ thuộc nhà trọ</div>
                    <div class="service-property-name">Nhà trọ Q.7</div>
                    <div class="service-property-address">123 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh</div>
                </div>
            </div>

            {{-- EDIT CARD --}}
            <div class="service-card">
                
                {{-- CARD HEADER --}}
                <div class="service-card-header">
                    <h2 class="service-card-title">Thông tin dịch vụ</h2>
                    <div class="service-card-description">Chỉnh sửa thông tin và mức giá của dịch vụ.</div>
                </div>

                {{-- FORM --}}
                <form action="{{ ($mode ?? 'edit') === 'create' ? route('owner.services.store') : route('owner.services.update', $service->exists ? $service : $service->id ?? 0) }}" method="POST">
                    @csrf
                    @if(($mode ?? 'edit') !== 'create')
                        @method('PUT')
                    @endif

                    {{-- CARD BODY --}}
                    <div class="service-card-body">

                        @if($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- TÊN DỊCH VỤ --}}
                        <div class="service-form-group">
                            <label class="service-label">Tên dịch vụ <span class="service-required">*</span></label>
                            <input type="text" name="name" class="service-input" value="{{ old('name', $service->name) }}" placeholder="Nhập tên dịch vụ">
                            <div class="service-help">Ví dụ: Internet, Giữ xe, Vệ sinh, Rác...</div>
                        </div>

                        {{-- MÔ TẢ --}}
                        <div class="service-form-group">
                            <label class="service-label">Mô tả</label>
                            <textarea name="description" class="service-input" placeholder="Nhập mô tả dịch vụ">{{ old('description', $service->description) }}</textarea>
                            <div class="service-help">Mô tả ngắn gọn về dịch vụ.</div>
                        </div>

                        {{-- UNIT + PRICE --}}
                        <div class="row g-3">
                            {{-- ĐƠN VỊ --}}
                            <div class="col-md-6">
                                <div class="service-form-group">
                                    <label class="service-label">Đơn vị tính <span class="service-required">*</span></label>
                                    <select name="unit" class="service-select">
                                        @foreach(['tháng', 'người', 'chiếc', 'kWh', 'm³'] as $unit)
                                            <option value="{{ $unit }}" {{ old('unit', $service->unit) === $unit ? 'selected' : '' }}>{{ $unit }}</option>
                                        @endforeach
                                    </select>
                                    <div class="service-help">Đơn vị dùng để tính phí dịch vụ.</div>
                                </div>
                            </div>

                            {{-- ĐƠN GIÁ --}}
                            <div class="col-md-6">
                                <div class="service-form-group">
                                    <label class="service-label">Đơn giá áp dụng <span class="service-required">*</span></label>
                                    <div class="service-price-wrapper">
                                        <input type="number" name="price" class="service-input" value="{{ old('price', $service->price ?? '') }}" min="0" placeholder="Nhập đơn giá">
                                        <span class="service-price-unit">₫</span>
                                    </div>
                                    <div class="service-help">Đơn giá hiện đang áp dụng cho nhà trọ này.</div>
                                </div>
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="service-form-group">
                            <label class="service-label">Trạng thái</label>
                            <div class="service-status">
                                <div class="service-status-content">
                                    <div class="service-status-dot"></div>
                                    <div>
                                        <div class="service-status-title">Đang hoạt động</div>
                                        <div class="service-status-description">Bỏ tick để tạm ngưng dịch vụ này.</div>
                                    </div>
                                </div>

                                <label class="service-switch">
                                    <input type="checkbox" name="status" value="active" {{ old('status', $service->status) === 'active' ? 'checked' : '' }}>
                                    <span class="service-slider"></span>
                                </label>
                            </div>
                        </div>

                       
                        <div class="service-note">
                            <strong>Lưu ý:</strong>
                            Khi thay đổi đơn giá, mức giá mới sẽ được áp dụng cho các khoản phí phát sinh sau thời điểm cập nhật. Các khoản phí hoặc hóa đơn đã ghi nhận trước đó không nên tự động thay đổi.
                        </div>
                    </div>

                  
                    <div class="service-card-footer">
                        <a href="{{ route('owner.services.index') }}" class="service-btn-cancel">Hủy</a>
                        <button type="submit" class="service-btn-save">{{ ($mode ?? 'edit') === 'create' ? '+ &nbsp; Thêm dịch vụ' : '✓ &nbsp; Lưu thay đổi' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </main>

@endsection