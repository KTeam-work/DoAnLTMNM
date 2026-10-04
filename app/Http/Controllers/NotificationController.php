<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $query = Notification::query()->where('user_id', $tenant->id)->latest();
        if ($request->filled('filter') && in_array($request->input('filter'), ['unread', 'read'], true)) {
            $request->input('filter') === 'unread'
                ? $query->whereNull('read_at')
                : $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();
        $filter = $request->input('filter', 'all');
        $unreadCount = Notification::query()->where('user_id', $tenant->id)->whereNull('read_at')->count();

        if (! $request->expectsJson()) {
            return view('tenant.notifications.index', compact('notifications', 'filter', 'unreadCount'));
        }

        return response()->json(['data' => $notifications]);
    }

    public function markAsRead(Request $request, int $id)
    {
        $tenant = $this->actingTenant($request);

        $notification = Notification::query()
            ->where('user_id', $tenant->id)
            ->findOrFail($id);
        $notification->update(['read_at' => $notification->read_at ?? now()]);

        if (! $request->expectsJson()) {
            $target = $notification->targetUrl();

            return $target
                ? redirect($target)
                : back()->with('success', 'Đã đánh dấu đã đọc.');
        }

        return response()->json(['message' => 'Marked as read.', 'data' => $notification->fresh()]);
    }

    public function markAllAsRead(Request $request)
    {
        $tenant = $this->actingTenant($request);

        Notification::query()->where('user_id', $tenant->id)->whereNull('read_at')->update(['read_at' => now()]);

        if (! $request->expectsJson()) {
            return redirect()->back()->with('success', 'Đã đánh dấu tất cả là đã đọc.');
        }

        return response()->json(['message' => 'All marked as read.']);
    }

    private function actingTenant(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'tenant', 403, 'Only tenants can access notifications.');

            return $user;
        }
        if (! $request->expectsJson()) {
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'tenant')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản người thuê, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }
}
