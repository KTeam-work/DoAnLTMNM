<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Notification;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $roomIds = Contract::query()->where('tenant_id', $tenant->id)->pluck('room_id');
        $reviews = Review::query()
            ->where('tenant_id', $tenant->id)
            ->with('room:id,name')
            ->latest()
            ->get();
        $rooms = \App\Models\Room::query()
            ->whereIn('id', $roomIds)
            ->with('property:id,name')
            ->orderBy('name')
            ->get();

        if (! $request->expectsJson()) {
            return view('tenant.reviews.index', compact('reviews', 'rooms', 'tenant'));
        }

        return response()->json(['data' => $reviews]);
    }

    /**
     * Chính sách: một review/phòng/tenant — gửi lại sẽ cập nhật đánh giá cũ.
     */
    public function store(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Chỉ đánh giá phòng mình đã/đang thuê, room_id suy từ hợp đồng.
        $allowed = Contract::query()
            ->where('tenant_id', $tenant->id)
            ->where('room_id', $data['room_id'])
            ->exists();
        if (! $allowed) {
            if (! $request->expectsJson()) {
                return back()->withErrors(['room_id' => 'Bạn chỉ được đánh giá phòng mình đã thuê.'])->withInput();
            }
            abort(422, 'You can only review rooms you have rented.');
        }

        $review = Review::query()->updateOrCreate(
            ['room_id' => $data['room_id'], 'tenant_id' => $tenant->id],
            ['rating' => $data['rating'], 'comment' => $data['comment'] ?? null, 'status' => 'visible']
        );

        $ownerId = Contract::query()->where('tenant_id', $tenant->id)->where('room_id', $data['room_id'])
            ->value('owner_id');
        if ($ownerId) {
            Notification::notify(
                $ownerId,
                'Đánh giá mới',
                "{$tenant->name} vừa đánh giá {$data['rating']} sao cho phòng {$review->room->name}.",
                'review',
                'contract',
                Contract::query()->where('tenant_id', $tenant->id)->where('room_id', $data['room_id'])->value('id')
            );
        }

        if (! $request->expectsJson()) {
            return redirect()->route('tenant.reviews.index')
                ->with('success', 'Cảm ơn bạn! Đánh giá đã được hệ thống ghi nhận.');
        }

        return response()->json(['message' => 'Review saved.', 'data' => $review], 201);
    }

    private function actingTenant(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'tenant', 403, 'Only tenants can review.');

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

