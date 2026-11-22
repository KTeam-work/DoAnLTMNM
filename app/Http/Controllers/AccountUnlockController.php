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

        $reasonText = !empty($data['reason']) ? " Lý do: \"{$data['reason']}\"" : "";
        $notifyContent = "Người dùng {$user->name} ({$user->email}) vừa gửi yêu cầu mở khóa tài khoản.{$reasonText}";

        // 1. Gửi thông báo chuông (In-app notification) đến toàn bộ Admin
        $admins = User::query()->where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::notify(
                $admin->id,
                'Yêu cầu mở khóa tài khoản',
                $notifyContent,
                'system',
                'user',
                $user->id
            );
        }

        // 2. Gửi Email thông báo có kèm đầy đủ thông tin tới Admin
        try {
            /** @var \App\Services\ResendService $resendService */
            $resendService = app(\App\Services\ResendService::class);
            $reasonHtml = nl2br(e($data['reason'] ?? 'Không có lời nhắn bổ sung'));
            $adminManageUrl = route('admin.users');

            foreach ($admins as $admin) {
                if (!empty($admin->email)) {
                    $adminHtml = "
                    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 24px; border: 1px solid #e2e8f0; border-radius: 12px; background: #ffffff;'>
                        <div style='border-bottom: 2px solid #ea580c; padding-bottom: 12px; margin-bottom: 18px;'>
                            <h2 style='color: #1e293b; margin: 0; font-size: 20px;'>Yêu cầu mở khóa tài khoản</h2>
                            <small style='color: #64748b;'>Hệ thống quản lý phòng trọ Trọ Ơi</small>
                        </div>
                        <p style='color: #334155; font-size: 14px;'>Xin chào Ban quản trị,</p>
                        <p style='color: #334155; font-size: 14px;'>Tài khoản người dùng sau đây vừa gửi yêu cầu mở khóa:</p>
                        <div style='background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px 18px; border-radius: 8px; margin: 15px 0; font-size: 13.5px; line-height: 1.6;'>
                            <div><strong>Họ và tên:</strong> {$user->name}</div>
                            <div><strong>Email:</strong> {$user->email}</div>
                            <div><strong>Vai trò:</strong> " . ($user->role === 'owner' ? 'Chủ trọ' : 'Người thuê') . "</div>
                            <div><strong>Thời gian gửi:</strong> " . now()->format('H:i d/m/Y') . "</div>
                        </div>
                        <div style='background: #fefce8; border-left: 4px solid #eab308; padding: 12px 16px; border-radius: 4px; margin: 15px 0; font-size: 13.5px;'>
                            <strong style='color: #854d0e;'>Lời nhắn / Lý do giải trình:</strong><br>
                            <span style='color: #713f12;'>{$reasonHtml}</span>
                        </div>
                        <p style='margin-top: 20px; text-align: center;'>
                            <a href='{$adminManageUrl}' style='display: inline-block; background: #1e293b; color: #ffffff; padding: 11px 22px; text-decoration: none; border-radius: 8px; font-weight: bold; font-size: 13.5px;'>Xem & Mở khóa trong Quản trị</a>
                        </p>
                    </div>";

                    $resendService->sendEmail(
                        $admin->email,
                        "Yêu cầu mở khóa tài khoản: {$user->name} ({$user->email})",
                        $adminHtml
                    );
                }
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi gửi email thông báo yêu cầu mở khóa đến Admin: ' . $e->getMessage());
        }

        return back()->with('success', 'Đã gửi yêu cầu mở khóa tài khoản đến Ban quản trị. Quản trị viên sẽ xem xét và gửi thông báo kết quả qua Gmail của bạn sớm nhất.');
    }
}
