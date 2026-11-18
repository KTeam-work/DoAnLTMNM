<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostReportController extends Controller
{
    /**
     * Danh sách báo cáo vi phạm tin đăng cho Admin.
     */
    public function index(Request $request)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);

        $status = $request->input('status', '');
        $reason = $request->input('reason', '');
        $query = trim($request->input('q', ''));

        $reports = PostReport::query()
            ->with(['rentalPost.room.property.owner', 'reporter', 'handler'])
            ->when($status !== '', fn ($b) => $b->where('status', $status))
            ->when($reason !== '', fn ($b) => $b->where('reason', $reason))
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($sub) use ($query) {
                    $sub->where('report_code', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhereHas('reporter', fn ($u) => $u->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%"))
                        ->orWhereHas('rentalPost', fn ($p) => $p->where('title', 'like', "%{$query}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all' => PostReport::count(),
            'pending' => PostReport::where('status', 'pending')->count(),
            'investigating' => PostReport::where('status', 'investigating')->count(),
            'resolved' => PostReport::where('status', 'resolved')->count(),
            'dismissed' => PostReport::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports', compact('reports', 'counts', 'status', 'reason', 'query'));
    }

    /**
     * Xử lý báo cáo vi phạm.
     */
    public function update(Request $request, int $id)
    {
        abort_unless(Auth::check() && Auth::user()->role === 'admin', 403);

        $report = PostReport::with(['rentalPost.room.property.owner', 'reporter'])->findOrFail($id);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,investigating,resolved,dismissed'],
            'action_taken' => ['required', 'in:none,post_hidden,owner_warned,owner_banned'],
            'admin_note' => ['nullable', 'string', 'max:1000'],
        ]);

        $actionTaken = $validated['action_taken'];
        $post = $report->rentalPost;
        $owner = $post?->room?->property?->owner;
        $adminNote = trim($validated['admin_note'] ?? '');

        // Thực thi chế tài xử lý nếu chọn
        if ($actionTaken === 'post_hidden' && $post) {
            $post->update(['status' => 'hidden']);
        } elseif ($actionTaken === 'owner_banned') {
            if ($post) {
                $post->update(['status' => 'hidden']);
            }
            if ($owner) {
                $owner->update(['status' => 'inactive']);
            }
        }

        $report->update([
            'status' => $validated['status'],
            'action_taken' => $actionTaken,
            'admin_note' => $adminNote !== '' ? $adminNote : null,
            'handler_id' => Auth::id(),
            'resolved_at' => in_array($validated['status'], ['resolved', 'dismissed']) ? now() : null,
        ]);

        // 1. Tự động gửi thông báo cho CHỦ TRỌ
        if ($owner) {
            $postTitle = $post ? $post->title : 'Tin đăng';
            $reasonText = $report->reason_label;

            if ($actionTaken === 'post_hidden') {
                $msg = "Tin đăng \"{$postTitle}\" đã bị Ban quản trị ẩn do vi phạm quy chuẩn cộng đồng ({$reasonText}).";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                } else {
                    $msg .= " Vui lòng kiểm tra và chỉnh sửa nội dung đúng quy định để được kích hoạt lại.";
                }

                Notification::notify(
                    $owner->id,
                    '⚠️ Tin đăng đã bị ẩn do vi phạm',
                    $msg,
                    'warning',
                    'rental_post',
                    $post?->id
                );
            } elseif ($actionTaken === 'owner_warned') {
                $msg = "Tin đăng \"{$postTitle}\" của bạn vừa nhận cảnh cáo từ Ban quản trị ({$reasonText}).";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                } else {
                    $msg .= " Vui lòng rà soát lại thông tin giá, ảnh chụp và nội dung mô tả để tránh bị tạm khóa bài đăng.";
                }

                Notification::notify(
                    $owner->id,
                    '⚠️ Cảnh cáo vi phạm quy định đăng tin',
                    $msg,
                    'warning',
                    'rental_post',
                    $post?->id
                );
            } elseif ($actionTaken === 'owner_banned') {
                $msg = "Tài khoản của bạn đã bị tạm khóa do vi phạm nghiêm trọng quy chế hệ thống liên quan đến tin đăng \"{$postTitle}\" ({$reasonText}).";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                }

                Notification::notify(
                    $owner->id,
                    '🚫 Tài khoản đã bị tạm khóa',
                    $msg,
                    'danger',
                    'user',
                    $owner->id
                );
            } elseif ($adminNote !== '' && in_array($validated['status'], ['investigating', 'resolved'])) {
                // Nhắc nhở nếu admin có để lại lời nhắn
                Notification::notify(
                    $owner->id,
                    'ℹ️ Thông báo từ BQT về tin đăng',
                    "Ban quản trị gửi thông báo liên quan đến tin đăng \"{$postTitle}\": {$adminNote}",
                    'general',
                    'rental_post',
                    $post?->id
                );
            }
        }

        // 2. Tự động gửi thông báo kết quả cho NGƯỜI BÁO CÁO (Khách thuê)
        if ($report->reporter_id) {
            $postTitle = $post ? $post->title : 'tin đăng';
            $code = $report->report_code;

            if ($validated['status'] === 'resolved') {
                $actionDesc = match ($actionTaken) {
                    'post_hidden' => 'đã ẩn tin đăng vi phạm',
                    'owner_warned' => 'đã gửi cảnh cáo vi phạm đến chủ nhà',
                    'owner_banned' => 'đã tạm khóa tài khoản chủ nhà vi phạm',
                    default => 'đã tiếp nhận và xử lý vi phạm',
                };
                $msg = "Báo cáo #{$code} của bạn về tin \"{$postTitle}\" đã được Ban quản trị giải quyết ({$actionDesc}).";
                if ($adminNote !== '') {
                    $msg .= " Phản hồi từ BQT: {$adminNote}";
                } else {
                    $msg .= " Cảm ơn bạn đã đồng hành giữ gìn cộng đồng Trọ Ơi an toàn!";
                }

                Notification::notify(
                    $report->reporter_id,
                    '✅ Báo cáo vi phạm đã được giải quyết',
                    $msg,
                    'success',
                    'report',
                    $report->id
                );
            } elseif ($validated['status'] === 'dismissed') {
                $msg = "Báo cáo #{$code} của bạn về tin \"{$postTitle}\" đã được Ban quản trị xem xét. Tin đăng chưa phát hiện vi phạm hoặc đã được chủ nhà điều chỉnh hợp lệ.";
                if ($adminNote !== '') {
                    $msg .= " Phản hồi từ BQT: {$adminNote}";
                }

                Notification::notify(
                    $report->reporter_id,
                    'ℹ️ Kết quả xác minh báo cáo vi phạm',
                    $msg,
                    'info',
                    'report',
                    $report->id
                );
            }
        }

        return back()->with('success', 'Đã cập nhật xử lý báo cáo vi phạm #' . $report->report_code . ' và gửi thông báo thành công!');
    }
}

