<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\PostReport;
use App\Models\RentalPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PostReportController extends Controller
{
    /**
     * Gửi báo cáo vi phạm tin đăng cho thuê.
     */
    public function store(Request $request, int $postId)
    {
        if (! Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vui lòng đăng nhập để gửi báo cáo vi phạm.');
        }

        $post = RentalPost::query()->findOrFail($postId);

        // Chống spam: Nếu đã có báo cáo đang chờ xử lý từ tài khoản này
        $existingPending = PostReport::query()
            ->where('rental_post_id', $post->id)
            ->where('reporter_id', Auth::id())
            ->where('status', 'pending')
            ->exists();

        if ($existingPending) {
            return back()->with('error', 'Bạn đã gửi một báo cáo cho tin đăng này và đang được Quản trị viên xử lý.');
        }

        $validated = $request->validate([
            'reason' => [
                'required',
                'in:fake_info,price_mismatch,fraud_scam,contact_failed,inappropriate,other',
            ],
            'description' => ['required', 'string', 'min:10', 'max:2000'],
            'evidence_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'], // Max 5MB
        ], [
            'reason.required' => 'Vui lòng chọn lý do báo cáo vi phạm.',
            'reason.in' => 'Lý do báo cáo không hợp lệ.',
            'description.required' => 'Vui lòng nhập mô tả chi tiết vi phạm.',
            'description.min' => 'Nội dung mô tả phải có ít nhất 10 ký tự.',
            'evidence_image.image' => 'Tệp tải lên phải là hình ảnh hợp lệ.',
            'evidence_image.max' => 'Ảnh bằng chứng không được vượt quá 5MB.',
        ]);

        $imagePath = null;
        if ($request->hasFile('evidence_image')) {
            $imagePath = $request->file('evidence_image')->store('reports', 'public');
        }

        // Tạo mã báo cáo duy nhất dạng BC-YYMMDD-XXXX
        $reportCode = 'BC-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        while (PostReport::where('report_code', $reportCode)->exists()) {
            $reportCode = 'BC-' . now()->format('ymd') . '-' . strtoupper(Str::random(4));
        }

        PostReport::create([
            'report_code' => $reportCode,
            'rental_post_id' => $post->id,
            'reporter_id' => Auth::id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'evidence_image' => $imagePath,
            'status' => 'pending',
            'action_taken' => 'none',
        ]);

        return back()->with('success', 'Báo cáo vi phạm (Mã: ' . $reportCode . ') đã được gửi thành công. Quản trị viên sẽ sớm kiểm tra và xử lý!');
    }
}

