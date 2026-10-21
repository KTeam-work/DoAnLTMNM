@extends('Layout.landlord')


@section("title","Tạo Người Thuê Cùng")
  
@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/create_tenats.css') }}">
@endpush

@section("content")


<div class="page-wrap">

    <!-- Quay lại -->
    <div class="page-header">

        <a href="{{ route('owner.tenants.manage') }}" class="back-link">
            ← Quay lại quản lý người thuê
        </a>

        <h1 class="page-title">
            Thêm người ở cùng
        </h1>

        <div class="page-desc">
            Chọn hợp đồng rồi nhập thông tin người cùng sinh sống trong phòng.
        </div>

    </div>


    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($contracts->isEmpty())
        <div class="alert alert-warning">Chưa có hợp đồng nào đang hiệu lực để thêm người ở cùng.</div>
    @else
    <!-- Chọn hợp đồng -->
    <div class="info-card">

        <div class="info-title">
            Chọn hợp đồng
        </div>

        <select id="contractSelect" class="form-select form-select-lg">
            @foreach($contracts as $contract)
                <option value="{{ $contract->id }}"
                    data-code="{{ $contract->contract_code }}"
                    data-tenant="{{ $contract->tenant->name ?? '' }}"
                    data-room="{{ $contract->room->name ?? '' }} · {{ $contract->room->property->name ?? '' }}"
                    data-count="{{ $contract->members->count() }}"
                    data-max="{{ $contract->room->max_people ?? '' }}"
                    @selected((string) old('contract_id', request('contract_id')) === (string) $contract->id)>
                    {{ $contract->contract_code }} — {{ $contract->room->name ?? '' }} ({{ $contract->tenant->name ?? '' }})
                </option>
            @endforeach
        </select>

        <div class="contract-info mt-3" id="contractInfoBox">

            <div class="info-item">
                <div class="info-label">
                    Mã hợp đồng
                </div>

                <div class="info-value" id="infoCode">
                    —
                </div>
            </div>


            <div class="info-item">
                <div class="info-label">
                    Người thuê chính
                </div>

                <div class="info-value" id="infoTenant">
                    —
                </div>
            </div>


            <div class="info-item">
                <div class="info-label">
                    Phòng
                </div>

                <div class="info-value" id="infoRoom">
                    —
                </div>
            </div>

            <div class="info-item">
                <div class="info-label">
                    Đã ở / Tối đa
                </div>

                <div class="info-value" id="infoCount">
                    —
                </div>
            </div>

        </div>

    </div>


    <!-- Form -->
    <div class="form-card">

        <div class="form-title">
            Thông tin người ở cùng
        </div>

        <div class="form-subtitle">
            Nhập thông tin của người sẽ cùng sinh sống trong phòng.
        </div>


        <form id="memberForm" action="#" method="POST">

            @csrf
            <input type="hidden" name="action" value="add">
            <input type="hidden" name="contract_id" id="contractIdHidden" value="{{ old('contract_id', request('contract_id')) }}">

            <div class="row g-4">

                <!-- Họ tên -->
                <div class="col-md-6">

                    <label class="form-label">
                        Họ và tên <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        class="form-control"
                        placeholder="Nhập họ và tên"
                        required
                        maxlength="100"
                    >

                </div>


                <!-- CCCD -->
                <div class="col-md-6">

                    <label class="form-label">
                        Số CCCD <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="identity_card"
                        value="{{ old('identity_card') }}"
                        class="form-control"
                        placeholder="Nhập số CCCD"
                        required
                        maxlength="20"
                    >

                </div>


                <!-- Số điện thoại -->
                <div class="col-md-6">

                    <label class="form-label">
                        Số điện thoại
                    </label>

                    <input
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="form-control"
                        placeholder="Nhập số điện thoại"
                        maxlength="20"
                    >

                </div>


                <!-- Quan hệ -->
                <div class="col-md-6">

                    <label class="form-label">
                        Quan hệ với người thuê chính
                    </label>

                    <select
                        name="relationship"
                        class="form-select"
                    >

                        <option value="">
                            -- Chọn quan hệ --
                        </option>

                        @foreach(['Bạn', 'Vợ/Chồng', 'Anh/Chị/Em', 'Người thân', 'Khác'] as $rel)
                            <option value="{{ $rel }}" @selected(old('relationship') === $rel)>
                                {{ $rel }}
                            </option>
                        @endforeach

                    </select>

                </div>

            </div>


            <!-- Ghi chú -->
            <div class="form-note mt-4">
                💡 Người ở cùng chỉ được ghi nhận trong hợp đồng và
                <strong>không cần tạo tài khoản Trọ Ơi</strong>.
            </div>


            <!-- Buttons -->
            <div class="form-actions">

                <a
                    href="{{ route('owner.tenants.manage') }}"
                    class="btn-cancel"
                >
                    Hủy
                </a>

                <button
                    type="submit"
                    class="btn-save"
                >
                    ✓ Thêm người ở cùng
                </button>

            </div>

        </form>

        @endif

    </div>

</div>




@push('scripts')
    <script src="{{ asset('js/tv3/tenants/created_tenants.js') }}"></script>
@endpush

@endsection