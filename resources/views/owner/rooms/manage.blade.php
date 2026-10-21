
@extends('Layout.landlord')

@section('title', 'Tạo Phòng | Trọ Ơi')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/tv3/rooms/manage.css') }}">
@endpush

@section('content')





    {{-- NỘI DUNG --}}
    <main class="page-wrap">

        <div class="page-header">
            <div>
                <h1 class="page-title">Quản lý phòng</h1>
                <div class="page-desc">
                    Quản lý thông tin và trạng thái các phòng thuộc nhà trọ của bạn.
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                <a class="btn-brand btn-brand-light"
                    href="{{ route('owner.rental-posts.index') }}">
                    Quản lý tin đăng
                </a>

                <a class="btn-brand"
                    href="{{ route('owner.rental-posts.create') }}">
                    + Tạo tin đăng
                </a>

                <a class="btn-brand"
                    href="{{ route('owner.rooms.create') }}">
                    + Thêm phòng
                </a>
            </div>
        </div>

        {{-- THÔNG BÁO --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Dữ liệu chưa hợp lệ!</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
            </div>
        @endif

        {{-- THỐNG KÊ --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Tổng số phòng</div>
                    <div class="stat-value">{{ $rooms->count() }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Còn trống</div>
                    <div class="stat-value">{{ $rooms->where('status', 'available')->count() }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Đang cho thuê</div>
                    <div class="stat-value">{{ $rooms->where('status', 'rented')->count() }}</div>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card">
                    <div class="stat-label">Bảo trì</div>
                    <div class="stat-value">{{ $rooms->where('status', 'maintenance')->count() }}</div>
                </div>
            </div>
        </div>

        {{-- DANH SÁCH PHÒNG --}}
        <section class="panel">
            <div class="panel-heading">Danh sách phòng</div>
            <div class="panel-subheading">
                Theo dõi giá thuê, sức chứa và trạng thái từng phòng.
            </div>

            <div class="toolbar">
                <input
                    type="search"
                    id="roomSearch"
                    class="search-input"
                    placeholder="Tìm theo tên phòng, mã phòng hoặc nhà trọ..."
                    aria-label="Tìm phòng">

                <select id="statusFilter" class="filter-select" aria-label="Lọc trạng thái">
                    <option value="">Tất cả trạng thái</option>
                    <option value="available">Còn trống</option>
                    <option value="rented">Đang thuê</option>
                    <option value="pending">Đang chờ</option>
                    <option value="maintenance">Đang bảo trì</option>
                    <option value="hidden">Đang ẩn</option>
                </select>
            </div>

            <div class="result-count" id="resultCount">
                Tổng cộng {{ $rooms->count() }} phòng
            </div>

            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Thông tin phòng</th>
                            <th>Nhà trọ</th>
                            <th>Diện tích</th>
                            <th>Giá thuê</th>
                            <th>Sức chứa</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>

                    <tbody id="roomTableBody">
                        @forelse ($rooms as $room)
                            <tr
                                class="room-row"
                                data-search="{{ $room->name }} {{ $room->room_code ?? '' }} {{ $room->property?->name ?? '' }}"
                                data-status="{{ $room->status }}">

                                <td>
                                    <div class="room-name">{{ $room->name }}</div>
                                    <div class="room-code">
                                        Mã phòng: {{ $room->room_code ?: 'Chưa có mã' }}
                                    </div>
                                </td>

                                <td>{{ $room->property?->name ?? 'Chưa có nhà trọ' }}</td>

                                <td>
                                    {{ $room->area !== null ? number_format((float) $room->area, 2, ',', '.') : 'Chưa cập nhật' }}
                                    @if ($room->area !== null)
                                        m²
                                    @endif
                                </td>

                                <td>
                                    <span class="room-price">
                                        {{ number_format((float) ($room->price ?? 0), 0, ',', '.') }}đ
                                    </span>
                                    <div class="room-code">/ tháng</div>
                                </td>

                                <td>{{ $room->max_people ?? '—' }} người</td>

                                <td>
                                    @switch($room->status)
                                        @case('available')
                                            <span class="badge-status available">Còn trống</span>
                                            @break

                                        @case('rented')
                                            <span class="badge-status rented">Đang thuê</span>
                                            @break

                                        @case('pending')
                                            <span class="badge-status pending">Đang chờ</span>
                                            @break

                                        @case('maintenance')
                                            <span class="badge-status maintenance">Bảo trì</span>
                                            @break

                                        @case('hidden')
                                            <span class="badge-status hidden">Đang ẩn</span>
                                            @break

                                        @default
                                            <span class="badge-status other">
                                                {{ $room->status ?? 'Chưa xác định' }}
                                            </span>
                                    @endswitch
                                </td>

                                <td>
                                    <div class="action-group">
                                        <a
                                            class="btn-action btn-detail"
                                            href="{{ route('owner.rooms.show', $room) }}">
                                            Chi tiết
                                        </a>

                                        <a
                                            class="btn-action btn-edit-room"
                                            href="{{ route('owner.rooms.edit', $room) }}">
                                            Sửa
                                        </a>

                                        <form
                                            action="{{ route('owner.rooms.destroy', $room) }}"
                                            method="POST"
                                            onsubmit="return confirm('Bạn có chắc muốn xóa phòng {{ $room->name }} không?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-action btn-delete-room">
                                                Xóa
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="emptyRoomsRow">
                                <td colspan="7" class="empty-state">
                                    <div class="empty-state-icon">🏠</div>
                                    <div class="fw-bold mb-2">Chưa có phòng nào</div>
                                    <div class="mb-3">
                                        Hãy thêm phòng đầu tiên để bắt đầu quản lý.
                                    </div>
                                    <a class="btn-brand" href="{{ route('owner.rooms.create') }}">
                                        + Thêm phòng
                                    </a>
                                </td>
                            </tr>
                        @endforelse

                        <tr id="noSearchResults" style="display: none;">
                            <td colspan="7" class="empty-state">
                                Không tìm thấy phòng phù hợp với bộ lọc.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

  

 
@push('scripts')
    <script src="{{ asset('js/tv3/rooms/manage.js') }}"></script>
@endpush


@endsection