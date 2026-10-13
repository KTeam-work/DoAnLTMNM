<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * Danh sách phòng công khai, tìm kiếm và lọc.
     */
    public function index(Request $request)
    {
        $query = Room::query()
           ->with([
    'property',
    'amenities',
    'rentalPosts' => function ($query) {
                    $query->where('status', 'published');
                },
            ])
            ->where('status', 'available')
            ->whereHas('rentalPosts', function (Builder $query) {
                $query->where('status', 'published')
                    ->where(function (Builder $query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            });

        // Tìm theo tên phòng, mã phòng, khu trọ và địa chỉ.
        if ($request->filled('keyword')) {
            $keyword = trim($request->string('keyword')->toString());

            $query->where(function (Builder $query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('room_code', 'like', "%{$keyword}%")
                    ->orWhereHas('property', function (Builder $propertyQuery) use ($keyword) {
                        $propertyQuery->where('name', 'like', "%{$keyword}%")
                            ->orWhere('address', 'like', "%{$keyword}%")
                            ->orWhere('district', 'like', "%{$keyword}%")
                            ->orWhere('ward', 'like', "%{$keyword}%");
                    });
            });
        }

        // Lọc khoảng giá.
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Lọc khoảng diện tích.
        if ($request->filled('min_area')) {
            $query->where('area', '>=', $request->input('min_area'));
        }

        if ($request->filled('max_area')) {
            $query->where('area', '<=', $request->input('max_area'));
        }

        // Lọc theo quận/huyện.
        if ($request->filled('district')) {
            $district = $request->input('district');

            $query->whereHas('property', function (Builder $propertyQuery) use ($district) {
                $propertyQuery->where('district', $district);
            });
        }

        // Lọc theo tiện ích: amenities[]=1&amenities[]=2.
        if ($request->filled('amenities')) {
            $amenityIds = collect((array) $request->input('amenities'))
                ->filter(fn ($id) => filter_var($id, FILTER_VALIDATE_INT) !== false)
                ->map(fn ($id) => (int) $id)
                ->unique()
                ->values();

            foreach ($amenityIds as $amenityId) {
                $query->whereHas('amenities', function (Builder $amenityQuery) use ($amenityId) {
                    $amenityQuery->where('amenities.id', $amenityId)
                        ->where('amenities.status', 'active');
                });
            }
        }

        $rooms = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();
            $favoriteRoomIds = Auth::check()
    ? Favorite::where('user_id', Auth::id())
        ->pluck('room_id')
        ->map(fn ($id) => (int) $id)
        ->all()
    : [];

        return view('rooms.index', compact('rooms', 'favoriteRoomIds'));
    }

    /**
     * Chi tiết một phòng được đăng công khai.
     */
    public function show(int $id)
    {
        $room = Room::query()
            ->with([
                'property',
                'amenities',
                'rentalPosts' => function ($query) {
                    $query->where('status', 'published')
                        ->where(function ($query) {
                            $query->whereNull('expired_at')
                                ->orWhere('expired_at', '>', now());
                        });
                },
            ])
            ->where('status', 'available')
            ->whereHas('rentalPosts', function (Builder $query) {
                $query->where('status', 'published')
                    ->where(function (Builder $query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            })
            ->findOrFail($id);

        return view('rooms.show', compact('room'));
    }
}
