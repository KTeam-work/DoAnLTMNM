
@extends('Layout.admin')

@section('title', 'Quản lý người dùng | Trọ Ơi')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/tv1/users.css') }}?v=5">
@endpush

@section('content')
@php
    $query = trim(request('q', ''));
    $role = request('role', '');
    $status = request('status', '');

    $users = \App\Models\User::query()
        ->when($query !== '', function ($builder) use ($query) {
            $builder->where(function ($sub) use ($query) {
                $sub->where('name', 'like', "%{$query}%")
                    ->orWhere('email', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('user_code', 'like', "%{$query}%");
            });
        })
        ->when($role !== '', fn ($builder) => $builder->where('role', $role))
        ->when($status !== '', function ($builder) use ($status) {
            if ($status === 'unlock_requested') {
                $builder->where('status', 'inactive')->whereNotNull('unlock_requested_at');
            } else {
                $builder->where('status', $status);
            }
        })
        ->latest()
        ->paginate(10)
        ->withQueryString();

    $unlockRequestedCount = \App\Models\User::where('status', 'inactive')->whereNotNull('unlock_requested_at')->count();
@endphp

<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý người dùng</h2>
        <div class="page-desc">Danh sách tài khoản trên nền tảng Trọ Ơi.</div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm mb-3" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Đóng"></button>
    </div>
@endif

<div class="segmented-filter-wrapper mb-4">
    <div class="segmented-filter-bar" role="tablist">
        <a href="{{ route('admin.users') }}" class="segmented-tab {{ empty($role) && empty($status) ? 'active' : '' }}">
            <span class="tab-label">Tất cả</span>
            <span class="tab-count">{{ \App\Models\User::count() }}</span>
        </a>
        <a href="{{ route('admin.users', ['role' => 'owner']) }}" class="segmented-tab {{ $role === 'owner' ? 'active' : '' }}">
            <span class="tab-label">Chủ trọ</span>
            <span class="tab-count">{{ \App\Models\User::where('role', 'owner')->count() }}</span>
        </a>
        <a href="{{ route('admin.users', ['role' => 'tenant']) }}" class="segmented-tab {{ $role === 'tenant' ? 'active' : '' }}">
            <span class="tab-label">Người thuê</span>
            <span class="tab-count">{{ \App\Models\User::where('role', 'tenant')->count() }}</span>
        </a>
        <a href="{{ route('admin.users', ['status' => 'inactive']) }}" class="segmented-tab {{ $status === 'inactive' ? 'active' : '' }}">
            <span class="tab-label">Bị khóa</span>
            <span class="tab-count">{{ \App\Models\User::where('status', 'inactive')->count() }}</span>
        </a>
        <a href="{{ route('admin.users', ['status' => 'unlock_requested']) }}" class="segmented-tab {{ $status === 'unlock_requested' ? 'active' : '' }}">
            <span class="tab-label">Yêu cầu mở khóa</span>
            <span class="tab-count">{{ $unlockRequestedCount }}</span>
        </a>
    </div>
</div>

<div class="tv1-panel">
    <form method="GET" action="{{ route('admin.users') }}" class="tv1-filter-form">
        <input
            type="search"
            name="q"
            value="{{ $query }}"
            placeholder="Tìm tên, email, số điện thoại..."
            aria-label="Tìm kiếm người dùng"
        >

        <select name="role" aria-label="Lọc vai trò">
            <option value="">Tất cả vai trò</option>
            <option value="admin" @selected($role === 'admin')>Quản trị viên</option>
            <option value="owner" @selected($role === 'owner')>Chủ trọ</option>
            <option value="tenant" @selected($role === 'tenant')>Người thuê</option>
        </select>

        <select name="status" aria-label="Lọc trạng thái">
            <option value="">Tất cả trạng thái</option>
            <option value="active" @selected($status === 'active')>Hoạt động</option>
            <option value="inactive" @selected($status === 'inactive')>Bị khóa</option>
            <option value="unlock_requested" @selected($status === 'unlock_requested')>Yêu cầu mở khóa</option>
        </select>

        <button type="submit" class="tv1-btn-primary">Tìm kiếm</button>
        <a href="{{ route('admin.users') }}" class="tv1-btn-secondary">Đặt lại</a>
    </form>

    <div class="table-responsive">
        <table class="tv1-table">
            <thead>
                <tr>
                    <th>Mã tài khoản</th>
                    <th>Người dùng</th>
                    <th>Liên hệ</th>
                    <th>Vai trò</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th class="text-center" style="width: 140px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>
                            @if($user->user_code)
                                <code class="px-2 py-1 rounded bg-light border fw-bold text-dark" style="font-size: 11.5px;">{{ $user->user_code }}</code>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>
                            <div class="tv1-user-name">{{ $user->name }}</div>
                            <div class="tv1-subtext">{{ $user->email }}</div>
                        </td>
                        <td>{{ $user->phone ?: '—' }}</td>
                        <td>
                            @switch($user->role)
                                @case('admin') Quản trị viên @break
                                @case('owner') Chủ trọ @break
                                @case('tenant') Người thuê @break
                                @default {{ $user->role ?: 'Chưa phân quyền' }}
                            @endswitch
                        </td>
                        <td>
                            @if ($user->status === 'active')
                                <span class="tv1-status is-active">Hoạt động</span>
                            @elseif ($user->unlock_requested_at)
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning py-1 px-2 fw-bold" style="font-size: 11px; color: #b45309 !important;">
                                    Chờ mở khóa
                                </span>
                                <div class="text-muted mt-1" style="font-size: 10.5px;">
                                    Yêu cầu: {{ $user->unlock_requested_at->format('H:i d/m') }}
                                </div>
                                @if ($user->unlock_reason)
                                    <div class="text-secondary fst-italic mt-1" style="font-size: 10.5px; max-width: 170px; word-break: break-word;" title="{{ $user->unlock_reason }}">
                                        "{{ Str::limit($user->unlock_reason, 35) }}"
                                    </div>
                                @endif
                            @else
                                <span class="tv1-status is-inactive">Bị khóa</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-center">
                            @if ($user->id === auth()->id())
                                <span class="badge bg-light text-muted border py-1 px-2" style="font-size: 11px;">Tài khoản của bạn</span>
                            @else
                                <form method="POST" action="{{ route('admin.users.update-status', $user) }}" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if ($user->status === 'active')
                                        <button type="submit" class="btn btn-sm btn-outline-danger py-1 px-2" style="font-size: 11.5px; border-radius: 8px;" onclick="return confirm('Xác nhận KHÓA tài khoản {{ $user->name }} ({{ $user->email }})?\nHệ thống sẽ gửi email thông báo kèm liên kết xin mở khóa cho người dùng.')" title="Khóa tài khoản này">
                                            Khóa
                                        </button>
                                    @elseif ($user->unlock_requested_at)
                                        <button type="submit" class="btn btn-sm btn-success py-1 px-2 fw-bold" style="font-size: 11.5px; border-radius: 8px;" onclick="return confirm('Xác nhận DUYỆT MỞ KHÓA cho tài khoản {{ $user->name }} ({{ $user->email }})?\nHệ thống sẽ kích hoạt lại tài khoản và gửi email thông báo cho người dùng.')" title="Duyệt mở khóa">
                                            Duyệt mở khóa
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-sm btn-outline-success py-1 px-2" style="font-size: 11.5px; border-radius: 8px;" onclick="return confirm('Xác nhận MỞ KHÓA cho tài khoản {{ $user->name }} ({{ $user->email }})?\nHệ thống sẽ gửi email thông báo cho người dùng.')" title="Mở khóa tài khoản này">
                                            Mở khóa
                                        </button>
                                    @endif
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="tv1-empty">Không tìm thấy người dùng phù hợp.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="tv1-pagination">{{ $users->links() }}</div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/tv1/users.js') }}" defer></script>
@endpush
