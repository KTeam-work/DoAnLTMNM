<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class AccountUnlockController extends Controller
{
    /**
     * Hiển thị trang yêu cầu mở khóa tài khoản từ đường dẫn trong Gmail.
     */
    public function show(string $token)
    {
        $user = User::query()->where('unlock_token', $token)->first();

        if (!$user) {
            return view('auth.unlock_request', [
                'user' => null,
                'invalidToken' => true,
            ]);
        }

        return view('auth.unlock_request', [
            'user' => $user,
            'invalidToken' => false,
            'isAlreadyActive' => $user->status === 'active',
            'hasRequested' => !empty($user->unlock_requested_at),
        ]);
    }

    /**
     * Người dùng xác nhận gửi yêu cầu mở khóa đến Admin.
     */
    public function submit(Request $request, string $token)
    {
        $user = User::query()->where('unlock_token', $token)->firstOrFail();

        if ($user->status === 'active') {
            return redirect()->route('login')->with('success', 'Tài khoản của bạn hiện đang hoạt động bình thường.');
        }

        $data = $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ], [
            'reason.max' => 'Lời nhắn giải trình tối đa 500 ký tự.',
        ]);

        $user->update([
            'unlock_requested_at' => now(),
            'unlock_reason' => $data['reason'] ?? null,
        ]);

        // Gửi thông báo đến toàn bộ ban quản trị (Admin)
        $admins = User::query()->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::notify(
                $admin->id,
                'Yêu cầu mở khóa tài khoản',
                "Người dùng {$user->name} ({$user->email}) vừa gửi yêu cầu mở khóa tài khoản.",
                'system',
                'user',
                $user->id
            );
        }

        return back()->with('success', 'Đã gửi yêu cầu mở khóa tài khoản đến Ban quản trị. Quản trị viên sẽ xem xét và gửi thông báo kết quả qua Gmail của bạn sớm nhất.');
    }
}
