<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    // Hàm hiển thị danh sách thông báo
    public function index()
    {
        // Sau này sẽ query DB: $notifications = Notification::where('user_id', auth()->id())->get();
        return view('tenant.notifications.index');
    }

    // Hàm xử lý khi bấm "Đánh dấu tất cả đã đọc"
    public function markAllAsRead()
    {
        // Sau này sẽ update DB: Notification::where('user_id', auth()->id())->update(['is_read' => true]);
        
        return redirect()->back()->with('success', 'Đã đánh dấu tất cả là đã đọc.');
    }
}