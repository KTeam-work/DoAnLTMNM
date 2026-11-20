
@extends('Layout.landlord')

@section('title', 'Quản thêm người ở cùng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/index.css') }}">
@endpush

@section('content')

  

    <!-- MAIN CONTENT -->
    <main class="owner-main">

        <div class="heading">
            <div>
                <h1>Danh sách phòng</h1>
                <p>Quản lý trạng thái và thông tin các phòng đang cho thuê.</p>
            </div>
            <button class="btn-owner" onclick="openAddModal()">＋ Thêm phòng</button>
        </div>

        <!-- STATS CARDS -->
        <div class="stats" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
            <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 20px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Tổng phòng</span>
                    <span style="background: #eff6ff; color: #2563eb; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Tất cả</span>
                </div>
                <div class="stat-number" id="totalRooms" style="font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 4px 0;">00</div>
                <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Tổng số phòng quản lý</div>
            </div>
            <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 20px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Còn trống</span>
                    <span style="background: #fef3c7; color: #b45309; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Sẵn sàng</span>
                </div>
                <div class="stat-number" id="availableRooms" style="font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 4px 0;">00</div>
                <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Phòng sẵn sàng đón khách</div>
            </div>
            <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 20px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Đang thuê</span>
                    <span style="background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Có khách</span>
                </div>
                <div class="stat-number" id="rentedRooms" style="font-size: 28px; font-weight: 800; color: #0f172a; line-height: 1.2; margin: 12px 0 4px 0;">00</div>
                <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Phòng có khách đang ở</div>
            </div>
            <div class="stat-card" style="background: #ffffff; border: 1px solid #edf2f7; border-radius: 18px; padding: 20px 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.03);">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 0.5px; color: #64748b;">Bảo trì</span>
                    <span style="background: #fef2f2; color: #dc2626; font-size: 11px; font-weight: 600; padding: 3px 12px; border-radius: 9999px;">Cần sửa</span>
                </div>
                <div class="stat-number" id="maintenanceRooms" style="font-size: 28px; font-weight: 800; color: #dc3545; line-height: 1.2; margin: 12px 0 4px 0;">00</div>
                <div class="stat-label" style="font-size: 12.5px; font-weight: 500; color: #64748b;">Đang bảo trì / sửa chữa</div>
            </div>
        </div>

        <!-- ROOM CONTAINER -->
        <section class="room-container">

            <!-- TOOLBAR -->
            <div class="toolbar">
                <div class="search-box">
                    <span>⌕</span>
                    <input type="text" id="roomSearch" placeholder="Tìm theo tên phòng, mã phòng, khu vực...">
                </div>

                <select id="propertyFilter" class="filter-select">
                    <option value="">Tất cả nhà trọ</option>
                    <option value="Nguyễn Thị Thập">Nguyễn Thị Thập</option>
                    <option value="An Phú">An Phú</option>
                    <option value="Tân Phú">Tân Phú</option>
                </select>

                <select id="statusFilter" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="available">Còn trống</option>
                    <option value="rented">Đang thuê</option>
                    <option value="maintenance">Bảo trì</option>
                </select>
            </div>


            <div class="room-grid" id="roomGrid">
                @forelse ($rooms as $room)
                @php
                $statusLabels = [
                'available' => 'CÒN TRỐNG',
                'rented' => 'ĐANG THUÊ',
                'maintenance' => 'BẢO TRÌ',
                'pending' => 'CHỜ XỬ LÝ',
                'hidden' => 'ĐANG ẨN',
                ];

                $propertyName = $room->property?->name ?? 'Chưa có nhà trọ';

                $roomSearch = mb_strtolower(
                $room->name . ' ' .
                $room->room_code . ' ' .
                $propertyName
                );
                @endphp

                <article class="room-card"
                    data-room-id="{{ $room->id }}"
                    data-property="{{ $propertyName }}"
                    data-status="{{ $room->status }}"
                    data-search="{{ $roomSearch }}"
                    data-area="{{ $room->area }}"
                    data-capacity="{{ $room->max_people }}"
                    data-deposit="{{ $room->deposit }}"
                    data-floor="{{ $room->floor }}"
                    data-description="{{ $room->description }}">

                    <div class="room-img">
                        <img
                            src="https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=900&q=80"
                            alt="{{ $room->name }}">

                        <span class="room-code">
                            {{ $room->room_code }}
                        </span>

                        <span class="room-status {{ $room->status }}">
                            {{ $statusLabels[$room->status] ?? $room->status }}
                        </span>
                    </div>

                    <div class="room-body">
                        <h3>{{ $room->name }}</h3>

                        <div class="room-property">
                            {{ $propertyName }}
                        </div>

                        <div class="room-price">
                            {{ number_format($room->price, 0, ',', '.') }}đ / tháng
                        </div>

                        <div class="room-info">
                            <span>{{ $room->area }} m²</span>
                            <span>{{ $room->max_people }} người</span>
                            <span>Tầng {{ $room->floor ?? '—' }}</span>
                        </div>

                        <div class="amenities">
                            <span class="amenity">
                                Cọc: {{ number_format($room->deposit, 0, ',', '.') }}đ
                            </span>
                        </div>

                        <div class="room-actions">
                            <button type="button" class="btn-edit"
                                onclick="openEditModal(this)">
                                Chỉnh sửa
                            </button>

                            <button type="button" class="btn-more"
                                onclick="openDetailModal(this)">
                                Chi tiết
                            </button>
                        </div>
                    </div>
                </article>
                @empty
                <div class="empty-room-message">
                    <div class="empty-room-icon">🏠</div>
                    Chưa có phòng nào. Hãy nhấn “Thêm phòng” để bắt đầu.
                </div>
                @endforelse
            </div>

        </section>

    </main>

    <!-- =========================
     MODAL CHI TIẾT PHÒNG
========================= -->
    <div class="custom-modal" id="detailModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title-area">
                    <div class="modal-title-icon">🚪</div>
                    <div>
                        <h2 class="modal-title" id="detailTitle">Thông tin phòng</h2>
                        <p class="modal-subtitle" id="detailSubtitle">Mã phòng: ---</p>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="detail-image-wrap">
                    <img id="detailImg" src="" class="detail-image" alt="Ảnh phòng">
                </div>
                <div class="detail-grid">
                    <div>
                        <div class="detail-label">Thuộc nhà trọ</div>
                        <div class="detail-value" id="detailProperty">---</div>
                    </div>
                    <div>
                        <div class="detail-label">Trạng thái</div>
                        <div><span class="detail-status" id="detailStatus">---</span></div>
                    </div>
                    <div>
                        <div class="detail-label">Giá thuê theo tháng</div>
                        <div class="detail-value price" id="detailPrice">---</div>
                    </div>
                    <div>
                        <div class="detail-label">Diện tích & Sức chứa</div>
                        <div class="detail-value" id="detailSpecs">---</div>
                    </div>
                    <div class="detail-item full">
                        <div class="detail-label">Tiện ích đi kèm</div>
                        <div class="detail-amenities" id="detailAmenities"></div>
                    </div>
                    <div class="detail-item full">
                        <div class="detail-label">Mô tả thêm</div>
                        <div class="detail-value" id="detailDescription" style="font-weight: 400; line-height: 1.5;">Chưa có thông tin mô tả chi tiết cho phòng này.</div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-back" onclick="closeModal('detailModal')">Đóng</button>
            </div>
        </div>
    </div>

    <!-- =========================
     MODAL CHỈNH SỬA PHÒNG
========================= -->
    <div class="custom-modal" id="editModal">
        <div class="modal-box">
            <div class="modal-header">
                <div class="modal-title-area">
                    <div class="modal-title-icon">✏️</div>
                    <div>
                        <h2 class="modal-title">Chỉnh sửa thông tin phòng</h2>
                        <p class="modal-subtitle">Cập nhật thông tin chi tiết phòng trọ</p>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('editModal')">&times;</button>
            </div>
            <form id="editRoomForm" onsubmit="saveEditRoom(event)">
                <div class="modal-body">
                    <input type="hidden" id="editCardId">
                    <div class="form-grid">
                        <div>
                            <label class="form-label">Tên phòng <span class="required">*</span></label>
                            <input type="text" class="edit-input" id="editRoomName" required>
                        </div>
                        <div>
                            <label class="form-label">Mã phòng <span class="required">*</span></label>
                            <input type="text" class="edit-input" id="editRoomCode" required>
                        </div>
                        <div>
                            <label class="form-label">Thuộc nhà trọ</label>
                            <select class="edit-select" id="editProperty">
                                <option value="Nguyễn Thị Thập">Nguyễn Thị Thập</option>
                                <option value="An Phú">An Phú</option>
                                <option value="Tân Phú">Tân Phú</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Trạng thái</label>
                            <select class="edit-select" id="editStatus">
                                <option value="available">Còn trống</option>
                                <option value="rented">Đang thuê</option>
                                <option value="maintenance">Bảo trì</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Giá thuê (VNĐ/tháng) <span class="required">*</span></label>
                            <input type="number" class="edit-input" id="editPrice" required>
                        </div>
                        <div>
                            <label class="form-label">Diện tích (m²)</label>
                            <input type="number" class="edit-input" id="editArea" value="20">
                        </div>
                        <div>
                            <label class="form-label">Sức chứa (người)</label>
                            <input type="number" class="edit-input" id="editCapacity" value="2">
                        </div>
                        <div>
                            <label class="form-label">Số WC</label>
                            <input type="number" class="edit-input" id="editWc" value="1">
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Tiện ích</label>
                            <div class="amenity-checks">
                                <label class="amenity-check"><input type="checkbox" name="editAmenities" value="❄ Máy lạnh"> Máy lạnh</label>
                                <label class="amenity-check"><input type="checkbox" name="editAmenities" value="📶 Wifi"> Wifi</label>
                                <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🛏 Nội thất"> Nội thất</label>
                                <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🌅 Ban công"> Ban công</label>
                                <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🧺 Máy giặt"> Máy giặt</label>
                                <label class="amenity-check"><input type="checkbox" name="editAmenities" value="🅿️ Chỗ để xe"> Chỗ để xe</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('editModal')">Hủy bỏ</button>
                    <button type="submit" class="btn-save">Lưu thay đổi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- =========================
     MODAL THÊM PHÒNG MỚI
========================= -->
    <div class="custom-modal" id="addModal">
        <div class="modal-box add-modal-box">
            <div class="modal-header">
                <div class="modal-title-area">
                    <div>
                        <h2 class="modal-title">Thêm phòng mới</h2>
                        <p class="modal-subtitle">Tạo phòng trọ mới vào danh sách quản lý</p>
                    </div>
                </div>
                <button class="modal-close" onclick="closeModal('addModal')">&times;</button>
            </div>
            <form id="addRoomForm" onsubmit="saveAddRoom(event)">
                <div class="modal-body">
                    <div class="form-grid">
                        <div>
                            <label class="form-label">Tên phòng <span class="required">*</span></label>
                            <input type="text" class="edit-input" id="addRoomName" placeholder="VD: Phòng 103" required>
                        </div>
                        <div>
                            <label class="form-label">Mã phòng <span class="required">*</span></label>
                            <input type="text" class="edit-input" id="addRoomCode" placeholder="VD: P.103" required>
                        </div>
                        <div>
                            <label class="form-label">Thuộc nhà trọ</label>
                            <select class="edit-select" id="addProperty">
                                <option value="Nguyễn Thị Thập">Nguyễn Thị Thập</option>
                                <option value="An Phú">An Phú</option>
                                <option value="Tân Phú">Tân Phú</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Trạng thái ban đầu</label>
                            <select class="edit-select" id="addStatus">
                                <option value="available">Còn trống</option>
                                <option value="rented">Đang thuê</option>
                                <option value="maintenance">Bảo trì</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label">Giá thuê (VNĐ/tháng) <span class="required">*</span></label>
                            <input type="number" class="edit-input" id="addPrice" placeholder="VD: 3000000" required>
                        </div>
                        <div>
                            <label class="form-label">Diện tích (m²)</label>
                            <input type="number" class="edit-input" id="addArea" value="20">
                        </div>
                        <div>
                            <label class="form-label">Sức chứa (người)</label>
                            <input type="number" class="edit-input" id="addCapacity" value="2">
                        </div>
                        <div>
                            <label class="form-label">Số WC</label>
                            <input type="number" class="edit-input" id="addWc" value="1">
                        </div>
                        <div class="form-group full">
                            <label class="form-label">Tiện ích chọn sẵn</label>
                            <div class="amenity-checks">
                                <label class="amenity-check"><input type="checkbox" name="addAmenities" value="❄ Máy lạnh" checked> Máy lạnh</label>
                                <label class="amenity-check"><input type="checkbox" name="addAmenities" value="📶 Wifi" checked> Wifi</label>
                                <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🛏 Nội thất"> Nội thất</label>
                                <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🌅 Ban công"> Ban công</label>
                                <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🧺 Máy giặt"> Máy giặt</label>
                                <label class="amenity-check"><input type="checkbox" name="addAmenities" value="🅿️ Chỗ để xe"> Chỗ để xe</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeModal('addModal')">Hủy bỏ</button>
                    <button type="submit" class="btn-add-save">Tạo phòng</button>
                </div>
            </form>
        </div>
    </div>

    <!-- TOAST MESSAGE -->
    <div class="toast-message" id="toastMessage">Thông báo từ hệ thống</div>

@push('scripts')
    <script src="{{ asset('js/tv3/rooms/index.js') }}"></script>
@endpush


@endsection