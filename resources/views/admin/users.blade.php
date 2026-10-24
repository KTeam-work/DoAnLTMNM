
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
        ->when($status !== '', fn ($builder) => $builder->where('status', $status))
        ->latest()
        ->paginate(10)
        ->withQueryString();
@endphp

<div class="page-header">
    <div>
        <h2 class="page-title">Quản lý người dùng</h2>
        <div class="page-desc">Danh sách tài khoản trên nền tảng Trọ Ơi.</div>
    </div>
</div>

<div class="tv1-stats-row">
    <div class="tv1-stat-card">
        <span>Tổng tài khoản</span>
        <strong>{{ \App\Models\User::count() }}</strong>
    </div>
    <div class="tv1-stat-card">
        <span>Chủ trọ</span>
        <strong>{{ \App\Models\User::where('role', 'owner')->count() }}</strong>
    </div>
    <div class="tv1-stat-card">
        <span>Người thuê</span>
        <strong>{{ \App\Models\User::where('role', 'tenant')->count() }}</strong>
    </div>
    <div class="tv1-stat-card">
        <span>Tài khoản bị khóa</span>
        <strong>{{ \App\Models\User::where('status', 'inactive')->count() }}</strong>
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
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->user_code ?: '—' }}</td>
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
                            <span class="tv1-status {{ $user->status === 'active' ? 'is-active' : 'is-inactive' }}">
                                {{ $user->status === 'active' ? 'Hoạt động' : ($user->status === 'inactive' ? 'Bị khóa' : ($user->status ?: 'Chưa xác định')) }}
                            </span>
                        </td>
                        <td>{{ $user->created_at?->format('d/m/Y') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="tv1-empty">Không tìm thấy người dùng phù hợp.</td>
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
