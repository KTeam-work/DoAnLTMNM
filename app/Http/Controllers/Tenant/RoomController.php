<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use App\Models\Amenity;
use App\Models\Property;

class RoomController extends Controller
{
    /**
     * Danh sách phòng công khai, tìm kiếm và lọc.
     */
    public function index(Request $request)
    {
        $query = Room::query()
            ->with([
                'property.owner',
                'property.images',
                'amenities',
                'rentalPosts' => function ($query) {
                    $query->where('status', 'published')
                        ->with('images');
                },
            ])
            ->where('status', 'available')
            ->whereHas('property', fn ($query) => $query->where('status', 'active'))
            ->whereHas('rentalPosts', function (Builder $query) {
                $query->where('status', 'published')
                    ->where(function (Builder $query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            });

        // Tìm theo tên phòng, mã phòng, khu trọ và địa chỉ.
        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));

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

        // Lọc giá thuê.
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Lọc diện tích.
        if ($request->filled('min_area')) {
            $query->where('area', '>=', $request->input('min_area'));
        }

        if ($request->filled('max_area')) {
            $query->where('area', '<=', $request->input('max_area'));
        }

        // Lọc quận/huyện.
        if ($request->filled('district')) {
            $district = $request->input('district');

            $query->whereHas('property', function (Builder $propertyQuery) use ($district) {
                $propertyQuery->where('district', $district);
            });
        }

        // Lọc theo tất cả tiện ích đã chọn.
        if ($request->filled('amenities')) {
            $amenityIds = collect((array) $request->input('amenities'))
                ->filter(fn($id) => filter_var($id, FILTER_VALIDATE_INT) !== false)
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();

            foreach ($amenityIds as $amenityId) {
                $query->whereExists(function ($amenityQuery) use ($amenityId) {
                    $amenityQuery->selectRaw('1')->from('room_amenities')
                        ->join('property_amenities', function ($join) {
                            $join->on('property_amenities.amenity_id', '=', 'room_amenities.amenity_id');
                        })
                        ->whereColumn('room_amenities.room_id', 'rooms.id')
                        ->whereColumn('property_amenities.property_id', 'rooms.property_id')
                        ->where('property_amenities.status', 'active')
                        ->where('room_amenities.amenity_id', $amenityId);
                });
            }
        }

        $rooms = $query
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        // Lấy ID bản ghi yêu thích theo ID phòng.
        // Kết quả: [room_id => favorite_id].
        $favoriteIdsByRoom = Auth::check()
            ? Favorite::where('user_id', Auth::id())
            ->pluck('id', 'room_id')
            ->mapWithKeys(fn($favoriteId, $roomId) => [
                (int) $roomId => (int) $favoriteId,
            ])
            ->all()
            : [];

        // Dữ liệu để tạo các lựa chọn lọc.
        $districts = Property::query()
            ->whereNotNull('district')
            ->where('district', '!=', '')
            ->distinct()
            ->orderBy('district')
            ->pluck('district');

        $amenities = Amenity::query()
            ->whereExists(fn ($query) => $query->selectRaw('1')->from('property_amenities')
                ->whereColumn('property_amenities.amenity_id', 'amenities.id')
                ->where('property_amenities.status', 'active'))
            ->orderBy('name')
            ->get();

        // AJAX chỉ trả phần kết quả, không trả lại toàn bộ trang.
        if ($request->ajax()) {
            return response()->json([
                'html' => view('rooms.partials.results', compact(
                    'rooms',
                    'favoriteIdsByRoom'
                ))->render(),
                'total' => $rooms->total(),
            ]);
        }

        return view('rooms.index', compact(
            'rooms',
            'favoriteIdsByRoom',
            'districts',
            'amenities'
        ));
    }

    /**
     * Chi tiết một phòng được đăng công khai.
     */
    public function show(int $id)
    {
        $room = Room::query()
            ->with([
                'property.owner.services' => function ($query) {
                    $query->where('status', 'active')->orderBy('name');
                },
                'property.images',
                'amenities',
                'rentalPosts' => function ($query) {
                    $query->where('status', 'published')
                        ->where(function ($query) {
                            $query->whereNull('expired_at')
                                ->orWhere('expired_at', '>', now());
                        })
                        ->with('images');
                },
                'reviews' => function ($query) {
                    $query->where('status', 'visible')
                        ->with('tenant:id,name,avatar')
                        ->latest();
                },
            ])
            ->where('status', 'available')
            ->whereHas('property', fn ($query) => $query->where('status', 'active'))
            ->whereHas('rentalPosts', function (Builder $query) {
                $query->where('status', 'published')
                    ->where(function (Builder $query) {
                        $query->whereNull('expired_at')
                            ->orWhere('expired_at', '>', now());
                    });
            })
            ->findOrFail($id);

        $reviews = $room->reviews;
        $totalReviews = $reviews->count();
        $avgRating = $totalReviews > 0 ? round($reviews->avg('rating'), 1) : 0;
        $ratingCounts = [
            5 => $reviews->where('rating', 5)->count(),
            4 => $reviews->where('rating', 4)->count(),
            3 => $reviews->where('rating', 3)->count(),
            2 => $reviews->where('rating', 2)->count(),
            1 => $reviews->where('rating', 1)->count(),
        ];

        return view('rooms.show', compact('room', 'reviews', 'totalReviews', 'avgRating', 'ratingCounts'));
    }
}
