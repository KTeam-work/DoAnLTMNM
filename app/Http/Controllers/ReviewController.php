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

        // Lấy danh sách tất cả hợp đồng của người thuê (gồm hợp đồng đang hiệu lực và đã thanh lý)
        $contracts = Contract::query()
            ->where('tenant_id', $tenant->id)
            ->with(['room.property'])
            ->orderByDesc('created_at')
            ->get();

        $roomIds = $contracts->pluck('room_id')->unique()->filter()->values();

        $reviews = Review::query()
            ->where('tenant_id', $tenant->id)
            ->with(['room.property'])
            ->latest()
            ->get();

        $rooms = \App\Models\Room::query()
            ->whereIn('id', $roomIds)
            ->with('property:id,name')
            ->orderBy('name')
            ->get();

        foreach ($rooms as $r) {
            $r->related_contract = $contracts->firstWhere('room_id', $r->id);
        }

        if (! $request->expectsJson()) {
            return view('tenant.reviews.index', compact('reviews', 'rooms', 'contracts', 'tenant'));
        }

        return response()->json(['data' => $reviews]);
    }

 
    public function store(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $data = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ]);

        // Chỉ được đánh giá phòng mà người thuê đã từng có hợp đồng thuê (đang thuê hoặc đã thanh lý)
        $contract = Contract::query()
            ->where('tenant_id', $tenant->id)
            ->where('room_id', $data['room_id'])
            ->latest()
            ->first();

        if (! $contract) {
            $msg = 'Bạn chỉ được đánh giá phòng mà bạn đã từng có hợp đồng thuê hoặc hợp đồng đã thanh lý.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['room_id' => $msg])->withInput();
            }
            abort(422, $msg);
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

       $review->load('room');

        if (! $request->expectsJson()) {
            return redirect()->route('tenant.reviews.index')
                ->with('success', 'Cảm ơn bạn! Đánh giá đã được hệ thống ghi nhận.');
        }

        return response()->json([
            'success' => true,
            'message' => 'Cảm ơn bạn! Đánh giá đã được hệ thống ghi nhận.',
            'review' => [
                'rating' => $review->rating,
                'room_name' => $review->room->name ?? '',
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('d/m/Y'),
            ]
        ], 201);
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

