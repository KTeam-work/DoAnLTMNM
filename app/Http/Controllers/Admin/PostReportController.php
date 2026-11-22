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
                        ->orWhereHas('rentalPost', fn ($p) => $p->where('title', 'like', "%{$query}%"))
                        ->orWhereHas('rentalPost.room.property.owner', fn ($o) => $o->where('name', 'like', "%{$query}%")->orWhere('email', 'like', "%{$query}%")->orWhere('phone', 'like', "%{$query}%"));
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Thống kê lịch sử vi phạm cho từng chủ trọ xuất hiện trên trang hiện tại (tránh N+1)
        $ownerIds = $reports->pluck('rentalPost.room.property.owner.id')->filter()->unique();
        $ownerStats = [];
        if ($ownerIds->isNotEmpty()) {
            $stats = PostReport::query()
                ->join('rental_posts', 'post_reports.rental_post_id', '=', 'rental_posts.id')
                ->join('rooms', 'rental_posts.room_id', '=', 'rooms.id')
                ->join('properties', 'rooms.property_id', '=', 'properties.id')
                ->whereIn('properties.owner_id', $ownerIds)
                ->selectRaw('properties.owner_id, 
                    COUNT(post_reports.id) as total_reports, 
                    SUM(CASE WHEN post_reports.status = "resolved" THEN 1 ELSE 0 END) as resolved_count,
                    SUM(CASE WHEN post_reports.status = "pending" THEN 1 ELSE 0 END) as pending_count')
                ->groupBy('properties.owner_id')
                ->get()
                ->keyBy('owner_id');

            $ownerStats = $stats->toArray();
        }

        $counts = [
            'all' => PostReport::count(),
            'pending' => PostReport::where('status', 'pending')->count(),
            'investigating' => PostReport::where('status', 'investigating')->count(),
            'resolved' => PostReport::where('status', 'resolved')->count(),
            'dismissed' => PostReport::where('status', 'dismissed')->count(),
        ];

        return view('admin.reports', compact('reports', 'counts', 'status', 'reason', 'query', 'ownerStats'));
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

        // 1. Tính số lần vi phạm đã xác nhận trước đây của chủ trọ này (không tính report hiện tại)
        $previousViolationsCount = 0;
        if ($owner) {
            $previousViolationsCount = PostReport::query()
                ->whereHas('rentalPost.room.property', fn ($q) => $q->where('owner_id', $owner->id))
                ->where('id', '!=', $report->id)
                ->where('status', 'resolved')
                ->count();
        }

        $isNowResolved = $validated['status'] === 'resolved';
        $cumulativeViolations = $isNowResolved ? ($previousViolationsCount + 1) : $previousViolationsCount;
        $autoBanned = false;

        // 2. CƠ CHẾ BẬC THANG / 3 GẬY: Nếu đạt từ 3 lần vi phạm được xác nhận -> TỰ ĐỘNG KHÓA VĨNH VIỄN
        if ($isNowResolved && $owner && $cumulativeViolations >= 3) {
            $actionTaken = 'owner_banned';
            $autoBanned = true;
        }

        // 3. Thực thi chế tài xử lý
        if ($actionTaken === 'post_hidden' && $post) {
            $post->update(['status' => 'hidden']);
        } elseif ($actionTaken === 'owner_banned') {
            if ($owner) {
                // Khóa tài khoản chủ trọ
                $owner->update(['status' => 'inactive']);
                // TỰ ĐỘNG ẨN TOÀN BỘ tất cả tin đăng của chủ trọ này để ngăn ngừa lừa đảo diện rộng
                \App\Models\RentalPost::query()
                    ->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))
                    ->where('status', '!=', 'hidden')
                    ->update(['status' => 'hidden']);
            } elseif ($post) {
                $post->update(['status' => 'hidden']);
            }
        }

        $report->update([
            'status' => $validated['status'],
            'action_taken' => $actionTaken,
            'admin_note' => $adminNote !== '' ? $adminNote : null,
            'handler_id' => Auth::id(),
            'resolved_at' => in_array($validated['status'], ['resolved', 'dismissed']) ? now() : null,
        ]);

        // 4. Tự động gửi thông báo cho CHỦ TRỌ
        if ($owner) {
            $postTitle = $post ? $post->title : 'Tin đăng';
            $reasonText = $report->reason_label;

            if ($autoBanned) {
                $msg = "Tài khoản của bạn đã bị KHÓA TỰ ĐỘNG do tích lũy {$cumulativeViolations} lần vi phạm quy chuẩn cộng đồng liên quan đến tin đăng \"{$postTitle}\" ({$reasonText}). Toàn bộ tin đăng của bạn đã được gỡ khỏi hệ thống.";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                }

                Notification::notify(
                    $owner->id,
                    'Tài khoản đã bị khóa do tái phạm nhiều lần',
                    $msg,
                    'danger',
                    'user',
                    $owner->id
                );
            } elseif ($actionTaken === 'post_hidden') {
                $msg = "Tin đăng \"{$postTitle}\" đã bị Ban quản trị ẩn do vi phạm quy chuẩn cộng đồng ({$reasonText}).";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                } else {
                    $msg .= " Vui lòng kiểm tra và chỉnh sửa nội dung đúng quy định để được kích hoạt lại.";
                }

                Notification::notify(
                    $owner->id,
                    'Tin đăng đã bị ẩn do vi phạm',
                    $msg,
                    'warning',
                    'rental_post',
                    $post?->id
                );
            } elseif ($actionTaken === 'owner_warned') {
                $msg = "Tin đăng \"{$postTitle}\" của bạn vừa nhận cảnh cáo từ Ban quản trị ({$reasonText}). Đây là vi phạm lần thứ {$cumulativeViolations}.";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                } else {
                    $msg .= " Lưu ý: Nếu tiếp tục vi phạm (đạt 3 lần), tài khoản sẽ bị tự động khóa vĩnh viễn và gỡ bỏ toàn bộ bài đăng.";
                }

                Notification::notify(
                    $owner->id,
                    "Cảnh cáo vi phạm quy định đăng tin (Lần {$cumulativeViolations})",
                    $msg,
                    'warning',
                    'rental_post',
                    $post?->id
                );
            } elseif ($actionTaken === 'owner_banned') {
                $msg = "Tài khoản của bạn đã bị tạm khóa do vi phạm nghiêm trọng quy chế hệ thống liên quan đến tin đăng \"{$postTitle}\" ({$reasonText}). Toàn bộ các tin đăng đã được gỡ bỏ.";
                if ($adminNote !== '') {
                    $msg .= " Ghi chú từ BQT: {$adminNote}";
                }

                Notification::notify(
                    $owner->id,
                    'Tài khoản đã bị tạm khóa',
                    $msg,
                    'danger',
                    'user',
                    $owner->id
                );
            } elseif ($adminNote !== '' && in_array($validated['status'], ['investigating', 'resolved'])) {
                // Nhắc nhở nếu admin có để lại lời nhắn
                Notification::notify(
                    $owner->id,
                    'Thông báo từ BQT về tin đăng',
                    "Ban quản trị gửi thông báo liên quan đến tin đăng \"{$postTitle}\": {$adminNote}",
                    'general',
                    'rental_post',
                    $post?->id
                );
            }
        }

        // 5. Tự động gửi thông báo kết quả & tiến độ cho NGƯỜI BÁO CÁO (Khách thuê)
        if ($report->reporter_id) {
            $postTitle = $post ? $post->title : 'tin đăng';
            $code = $report->report_code;

            if ($validated['status'] === 'resolved') {
                $actionDesc = match ($actionTaken) {
                    'post_hidden' => 'đã ẩn tin đăng vi phạm',
                    'owner_warned' => 'đã gửi cảnh cáo vi phạm đến chủ nhà',
                    'owner_banned' => $autoBanned
                        ? "đã khóa vĩnh viễn tài khoản chủ trọ do tái phạm {$cumulativeViolations} lần và gỡ toàn bộ tin đăng"
                        : 'đã khóa tài khoản chủ nhà vi phạm và gỡ toàn bộ tin đăng',
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
                    'Báo cáo vi phạm đã được giải quyết',
                    $msg,
                    'success',
                    'report',
                    $report->id
                );
            } elseif ($validated['status'] === 'investigating') {
                $msg = "Báo cáo #{$code} của bạn về tin \"{$postTitle}\" đang được Ban quản trị tiến hành xác minh và làm việc với chủ trọ.";
                if ($adminNote !== '') {
                    $msg .= " Phản hồi từ BQT: {$adminNote}";
                }

                Notification::notify(
                    $report->reporter_id,
                    'Báo cáo vi phạm đang được xác minh',
                    $msg,
                    'info',
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
                    'Kết quả xác minh báo cáo vi phạm',
                    $msg,
                    'info',
                    'report',
                    $report->id
                );
            } elseif ($adminNote !== '') {
                // Trường hợp vẫn chờ xử lý nhưng BQT có ghi chú phản hồi
                Notification::notify(
                    $report->reporter_id,
                    'Phản hồi từ Ban quản trị về báo cáo',
                    "Ban quản trị gửi phản hồi liên quan đến báo cáo #{$code} (\"{$postTitle}\"): {$adminNote}",
                    'general',
                    'report',
                    $report->id
                );
            }
        }

        $successMsg = 'Đã cập nhật xử lý báo cáo vi phạm #' . $report->report_code . '!';
        if ($autoBanned) {
            $successMsg .= " [HỆ THỐNG] Chủ trọ tích lũy {$cumulativeViolations} lần vi phạm -> Đã tự động kích hoạt KHÓA TÀI KHOẢN và ẨN TOÀN BỘ TIN ĐĂNG.";
        }

        return back()->with('success', $successMsg);
    }
}

