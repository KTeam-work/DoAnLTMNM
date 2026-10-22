<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TenantDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        abort_unless($user && $user->role === 'tenant', 403);

        // Phòng đang trống và có bài đăng được duyệt, còn hạn.
        $roomsQuery = DB::table('rooms')
            ->leftJoin(
                'properties',
                'properties.id',
                '=',
                'rooms.property_id'
            )
            ->select(
                'rooms.*',
                'properties.address as property_address'
            )
            ->where('rooms.status', 'available')
            ->whereExists(function ($query) {
                $query->selectRaw('1')
                    ->from('rental_posts')
                    ->whereColumn('rental_posts.room_id', 'rooms.id')
                    ->where('rental_posts.status', 'published')
                    ->where(function ($q) {
                        $q->whereNull('rental_posts.published_at')
                            ->orWhere(
                                'rental_posts.published_at',
                                '<=',
                                now()
                            );
                    })
                    ->where(function ($q) {
                        $q->whereNull('rental_posts.expired_at')
                            ->orWhere(
                                'rental_posts.expired_at',
                                '>=',
                                now()
                            );
                    });
            });

        // Lọc theo khu vực, tên phòng hoặc mã phòng.
        if ($request->filled('location')) {
            $keyword = trim($request->input('location'));

            $roomsQuery->where(function ($query) use ($keyword) {
                $query->where('rooms.name', 'like', "%{$keyword}%")
                    ->orWhere(
                        'rooms.room_code',
                        'like',
                        "%{$keyword}%"
                    )
                    ->orWhere(
                        'properties.address',
                        'like',
                        "%{$keyword}%"
                    );
            });
        }

        // Giá thuê tối đa.
        if ($request->filled('max_price')) {
            $maxPrice = $request->input('max_price');

            if (is_numeric($maxPrice) && $maxPrice >= 0) {
                $roomsQuery->where('rooms.price', '<=', $maxPrice);
            }
        }

        // Diện tích tối thiểu.
        if ($request->filled('min_area')) {
            $minArea = $request->input('min_area');

            if (is_numeric($minArea) && $minArea >= 0) {
                $roomsQuery->where('rooms.area', '>=', $minArea);
            }
        }

        $rooms = $roomsQuery
            ->orderByDesc('rooms.created_at')
            ->limit(6)
            ->get();

        // Danh sách phòng đã yêu thích của người đang đăng nhập.
        $favoriteRoomIds = DB::table('favorites')
            ->where('user_id', $user->id)
            ->pluck('room_id')
            ->map(fn ($id) => (int) $id)
            ->all();

        // Lịch xem phòng của chính người thuê hiện tại.
        $appointments = DB::table('viewing_appointments')
            ->join(
                'rooms',
                'rooms.id',
                '=',
                'viewing_appointments.room_id'
            )
            ->leftJoin(
                'properties',
                'properties.id',
                '=',
                'rooms.property_id'
            )
            ->where('viewing_appointments.tenant_id', $user->id)
            ->select(
                'viewing_appointments.*',
                'rooms.name as room_name',
                'rooms.room_code',
                'properties.address as property_address'
            )
            ->orderByDesc('viewing_appointments.appointment_date')
            ->orderByDesc('viewing_appointments.appointment_time')
            ->limit(5)
            ->get();

       return view('tenant.dashboard', [
        'user' => $user,
        'lastName' => $user->last_name ?? $user->name,
        'rooms' => $rooms,
        'favoriteRoomIds' => $favoriteRoomIds,
        'appointments' => $appointments,
    ]);
    }
}
