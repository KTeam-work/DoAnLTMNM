<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);

        $query = Review::query()
            ->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['room:id,name', 'tenant:id,name'])
            ->latest();

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->input('room_id'));
        }
        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }
        if ($request->filled('status') && in_array($request->input('status'), ['visible', 'hidden'], true)) {
            $query->where('status', $request->input('status'));
        }

        $reviews = (clone $query)->paginate(15)->withQueryString();
        $stats = [
            'average' => round((clone $query)->avg('rating') ?? 0, 1),
            'total' => (clone $query)->count(),
            'distribution' => [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0],
        ];
        foreach ((clone $query)->selectRaw('rating, COUNT(*) as c')->groupBy('rating')->pluck('c', 'rating') as $rating => $count) {
            $stats['distribution'][(int) $rating] = $count;
        }
        $rooms = \App\Models\Room::query()
            ->whereHas('property', fn ($q) => $q->where('owner_id', $owner->id))
            ->orderBy('name')->get(['id', 'name']);
        $filters = $request->only(['room_id', 'rating', 'status']);

        if (! $request->expectsJson()) {
            return view('owner.reviews.index', compact('reviews', 'stats', 'rooms', 'filters'));
        }

        return response()->json(['data' => $reviews, 'stats' => $stats]);
    }

    /**
     * Ẩn/hiện review (không sửa nội dung, không xóa).
     */
    public function toggleVisibility(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $review = Review::query()
            ->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))
            ->findOrFail($id);

        $review->update(['status' => $review->status === 'visible' ? 'hidden' : 'visible']);

        if (! $request->expectsJson()) {
            return back()->with('success', 'Đã cập nhật trạng thái hiển thị đánh giá.');
        }

        return response()->json(['message' => 'Visibility updated.', 'data' => $review->fresh()]);
    }

    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage reviews.');

            return $user;
        }
        if (! $request->expectsJson()) {
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'owner')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản chủ trọ, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }
}
