@extends('Layout.landlord')

@section('title', 'Tiện ích phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/services/index.css') }}">
    <style>
        .amenity-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 16px;
            margin-top: 20px;
        }
        .amenity-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: all 0.2s ease;
        }
        .amenity-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border-color: #cbd5e1;
        }
        .amenity-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
        }
        .amenity-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 999px;
            margin-top: 6px;
        }
        .badge-active {
            background: #dcfce7;
            color: #166534;
        }
        .badge-inactive {
            background: #f1f5f9;
            color: #64748b;
        }
        .amenity-desc {
            font-size: 13px;
            color: #64748b;
            margin-top: 8px;
            line-height: 1.4;
        }
        .amenity-actions {
            display: flex;
            gap: 6px;
        }
        .action-btn-sm {
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s;
        }
        .action-btn-sm:hover {
            background: #f8fafc;
            border-color: #94a3b8;
        }
        .action-btn-sm.delete {
            color: #dc2626;
            border-color: #fecaca;
            background: #fff5f5;
        }
        .action-btn-sm.delete:hover {
            background: #fef2f2;
            border-color: #ef4444;
        }
    </style>
@endpush

@section('content')

    <main class="page-wrap">

        {{-- ALERT NOTIFICATION --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 20px;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 12px; margin-bottom: 20px;">
                {{ $errors->first() }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- HEADER --}}
        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý dịch vụ & tiện ích</h1>
                <div class="page-desc">Danh mục tiện ích trang thiết bị sẵn có trong phòng trọ</div>
            </div>
            <div>
                <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#createAmenityModal" {{ $selectedProperty ? '' : 'disabled' }}>
                    <span>＋</span> Thêm tiện ích mới
                </button>
            </div>
        </div>

        {{-- NAVIGATION TABS GIỮA DỊCH VỤ VÀ TIỆN ÍCH --}}
        <div style="display: flex; gap: 12px; margin-bottom: 24px; border-bottom: 2px solid #e2e8f0; padding-bottom: 2px;">
            <a href="{{ route('owner.services.index') }}" style="padding: 10px 20px; font-weight: 600; font-size: 15px; color: #64748b; text-decoration: none; margin-bottom: -4px; transition: color 0.2s;">
                Dịch vụ tính phí
            </a>
            <a href="{{ route('owner.amenities.index') }}" style="padding: 10px 20px; font-weight: 700; font-size: 15px; color: #0c2340; border-bottom: 3px solid #0c2340; text-decoration: none; margin-bottom: -4px;">
                Tiện ích phòng
            </a>
        </div>

        <div class="property-box">
            <div class="row align-items-center g-3">
                <div class="col-md-7">
                    <div class="property-label">Nhà trọ đang xem</div>
                    <div class="property-name">{{ $selectedProperty?->name ?? 'Chưa có nhà trọ nào' }}</div>
                    <div class="property-address">{{ $selectedProperty?->address ?? 'Hãy thêm nhà trọ trước khi cấu hình tiện ích.' }}</div>
                </div>
                <div class="col-md-5 text-md-end">
                    @if($properties->isNotEmpty())
                        <label for="amenity-property" class="visually-hidden">Chọn nhà trọ</label>
                        <select id="amenity-property" class="property-select" onchange="window.location.href = '{{ route('owner.amenities.index') }}?property_id=' + encodeURIComponent(this.value)">
                            @foreach($properties as $property)
                                <option value="{{ $property->id }}" @selected($selectedProperty?->id === $property->id)>{{ $property->name }}</option>
                            @endforeach
                        </select>
                    @endif
                </div>
            </div>
        </div>

        {{-- BỘ LỌC TRẠNG THÁI --}}
        @php
            $totalAmenities = $amenities->count();
            $activeAmenities = $amenities->filter(fn($item) => $item->pivot->status === 'active')->count();
            $inactiveAmenities = $amenities->filter(fn($item) => $item->pivot->status === 'inactive')->count();
        @endphp
        <div class="segmented-filter-wrapper mb-4">
            <div class="segmented-filter-bar" role="tablist">
                <button type="button" class="segmented-tab active" data-status="" onclick="filterAmenities('')">
                    <span class="tab-label">Tất cả</span>
                    <span class="tab-count">{{ $totalAmenities }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="active" onclick="filterAmenities('active')">
                    <span class="tab-label">Đang hoạt động</span>
                    <span class="tab-count">{{ $activeAmenities }}</span>
                </button>
                <button type="button" class="segmented-tab" data-status="inactive" onclick="filterAmenities('inactive')">
                    <span class="tab-label">Tạm ngưng</span>
                    <span class="tab-count">{{ $inactiveAmenities }}</span>
                </button>
            </div>
        </div>

        {{-- LIST AMENITIES --}}
        @if($amenities->isNotEmpty())
            <div class="amenity-card-grid" id="amenityCardGrid">
                @foreach($amenities as $amenity)
                    <div class="amenity-card" data-status="{{ $amenity->pivot->status }}">
                        <div>
                            <div class="amenity-title">
                                {{ $amenity->name }}
                            </div>
                            <span class="amenity-badge {{ $amenity->pivot->status === 'active' ? 'badge-active' : 'badge-inactive' }}">
                                {{ $amenity->pivot->status === 'active' ? 'Đang hoạt động' : 'Tạm ngưng' }}
                            </span>
                            @if($amenity->pivot->description)
                                <div class="amenity-desc">{{ $amenity->pivot->description }}</div>
                            @endif
                            <div style="margin-top: 10px; font-size: 12px; color: #94a3b8;">
                                Áp dụng cho: <strong>{{ $amenity->property_rooms_count }}</strong> phòng trọ
                            </div>
                        </div>

                        <div class="amenity-actions">
                            <button type="button" class="action-btn-sm" data-bs-toggle="modal" data-bs-target="#editAmenityModal"
                                data-action="{{ route('owner.amenities.update', $amenity) }}" data-name="{{ $amenity->name }}" data-description="{{ $amenity->pivot->description }}" data-status="{{ $amenity->pivot->status }}">
                                Sửa
                            </button>
                            <form action="{{ route('owner.amenities.destroy', $amenity->id) }}" method="POST" onsubmit="return confirm('Xóa tiện ích này khỏi nhà trọ và các phòng thuộc nhà này?');" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="property_id" value="{{ $selectedProperty->id }}">
                                <button type="submit" class="action-btn-sm delete">Xóa</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-state" style="text-align: center; padding: 48px 20px; background: #fff; border: 1px dashed #cbd5e1; border-radius: 14px; margin-top: 20px;">
                <div style="font-size: 18px; font-weight: 700; color: #0f172a; margin-bottom: 6px;">Chưa có tiện ích nào trong danh mục</div>
                <div style="color: #64748b; font-size: 14px; margin-bottom: 18px;">Thêm các tiện ích như Máy lạnh, Tủ lạnh, Gác lửng để gán cho các phòng trọ của bạn.</div>
                <button type="button" class="btn-brand" data-bs-toggle="modal" data-bs-target="#createAmenityModal" {{ $selectedProperty ? '' : 'disabled' }}>
                    ＋ Thêm tiện ích đầu tiên
                </button>
            </div>
        @endif

    </main>

    {{-- MODAL THÊM TIỆN ÍCH --}}
    <div class="modal fade" id="createAmenityModal" tabindex="-1" aria-labelledby="createAmenityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <form action="{{ route('owner.amenities.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="property_id" value="{{ $selectedProperty?->id }}">
                    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                        <h5 class="modal-title font-weight-bold" id="createAmenityModalLabel" style="font-weight: 700; color: #0c2340;">
                            Thêm tiện ích mới
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 20px 24px;">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 14px;">Tên tiện ích <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Ví dụ: Điều hòa, Máy giặt, Gác lửng..." required style="border-radius: 10px; padding: 10px 14px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 14px;">Mô tả / Ghi chú</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Mô tả chi tiết tiện ích (nếu có)..." style="border-radius: 10px; padding: 10px 14px;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 14px;">Trạng thái</label>
                            <select name="status" class="form-select" style="border-radius: 10px; padding: 10px 14px;">
                                <option value="active">Đang hoạt động</option>
                                <option value="inactive">Tạm ngưng</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 16px 24px;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Hủy</button>
                        <button type="submit" class="btn-brand" style="border-radius: 10px;">Lưu tiện ích</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL SỬA TIỆN ÍCH --}}
    <div class="modal fade" id="editAmenityModal" tabindex="-1" aria-labelledby="editAmenityModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 16px; border: none; box-shadow: 0 10px 25px rgba(0,0,0,0.15);">
                <form id="editAmenityForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="property_id" value="{{ $selectedProperty?->id }}">
                    <div class="modal-header" style="border-bottom: 1px solid #f1f5f9; padding: 20px 24px;">
                        <h5 class="modal-title font-weight-bold" id="editAmenityModalLabel" style="font-weight: 700; color: #0c2340;">
                            Cập nhật tiện ích
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" style="padding: 20px 24px;">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 14px;">Tên tiện ích <span class="text-danger">*</span></label>
                            <input type="text" id="editName" name="name" class="form-control" required style="border-radius: 10px; padding: 10px 14px;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 14px;">Mô tả / Ghi chú</label>
                            <textarea id="editDescription" name="description" class="form-control" rows="3" style="border-radius: 10px; padding: 10px 14px;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; font-size: 14px;">Trạng thái</label>
                            <select id="editStatus" name="status" class="form-select" style="border-radius: 10px; padding: 10px 14px;">
                                <option value="active">Đang hoạt động</option>
                                <option value="inactive">Tạm ngưng</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer" style="border-top: 1px solid #f1f5f9; padding: 16px 24px;">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">Hủy</button>
                        <button type="submit" class="btn-brand" style="border-radius: 10px;">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            if (!window.amenityEditListenerInstalled) document.addEventListener('show.bs.modal', function(event) {
                if (event.target.id !== 'editAmenityModal' || !event.relatedTarget) return;
                var button = event.relatedTarget;
                document.getElementById('editAmenityForm').action = button.dataset.action;
                document.getElementById('editName').value = button.dataset.name;
                document.getElementById('editDescription').value = button.dataset.description;
                document.getElementById('editStatus').value = button.dataset.status;
            });
            window.amenityEditListenerInstalled = true;

            window.filterAmenities = function(status) {
                // Đổi active tab
                document.querySelectorAll('.segmented-tab').forEach(function(tab) {
                    if (tab.getAttribute('data-status') === status) {
                        tab.classList.add('active');
                    } else {
                        tab.classList.remove('active');
                    }
                });

                // Lọc các thẻ tiện ích
                var cards = document.querySelectorAll('.amenity-card');
                cards.forEach(function(card) {
                    if (!status || card.getAttribute('data-status') === status) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            };
        </script>
    @endpush

@endsection
