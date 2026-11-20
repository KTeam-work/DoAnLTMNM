<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * GET /favorites
     * Xem danh sách phòng yêu thích của người đang đăng nhập.
     */
    public function index(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vui lòng đăng nhập để lưu phòng yêu thích.',
                    'login_url' => route('login'),
                ], 401);
            }

            return redirect()
                ->route('login')
                ->with('error', 'Vui lòng đăng nhập để xem phòng yêu thích.');
        }

        $favorites = Favorite::with([
            'room.property',
            'room.amenities',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $rooms = $favorites
            ->pluck('room')
            ->filter();

        $favoriteCount = $rooms->count();

        $areaCount = $rooms
            ->map(function ($room) {
                return $room->property?->district;
            })
            ->filter()
            ->unique()
            ->count();

        $minPrice = $rooms->min('price');
        $maxPrice = $rooms->max('price');

        return view('favorites.index', compact(
            'favorites',
            'favoriteCount',
            'areaCount',
            'minPrice',
            'maxPrice'
        ));
    }

    /**
     * POST /favorites
     * Thêm phòng vào danh sách yêu thích.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vui lòng đăng nhập để cập nhật phòng yêu thích.',
                    'login_url' => route('login'),
                ], 401);
            }

            return redirect()
                ->route('login')
                ->with('error', 'Vui lòng đăng nhập để lưu phòng yêu thích.');
        }

        $validated = $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
        ]);

        $favorite = Favorite::firstOrCreate([
            'user_id' => Auth::id(),
            'room_id' => $validated['room_id'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'favorited' => true,
                'favorite_id' => $favorite->id,
                'room_id' => (int) $favorite->room_id,
                'message' => $favorite->wasRecentlyCreated
                    ? 'Đã thêm phòng vào danh sách yêu thích.'
                    : 'Phòng này đã có trong danh sách yêu thích.',
            ]);
        }

        return redirect()
            ->back()
            ->with(
                'success',
                $favorite->wasRecentlyCreated
                    ? 'Đã thêm phòng vào danh sách yêu thích.'
                    : 'Phòng này đã có trong danh sách yêu thích.'
            );
    }

    /**
     * DELETE /favorites/{id}
     * Bỏ một phòng khỏi danh sách yêu thích.
     */
    public function destroy(Request $request, int $id)
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vui lòng đăng nhập để thực hiện thao tác.',
                    'login_url' => route('login'),
                ], 401);
            }

            return redirect()
                ->route('login')
                ->with('error', 'Vui lòng đăng nhập để thực hiện thao tác.');
        }

        $favorite = Favorite::where('user_id', Auth::id())
            ->findOrFail($id);

        $roomId = (int) $favorite->room_id;

        $favorite->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'favorited' => false,
                'room_id' => $roomId,
                'message' => 'Đã bỏ phòng khỏi danh sách yêu thích.',
            ]);
        }

        return redirect()
            ->back()
            ->with('success', 'Đã bỏ phòng khỏi danh sách yêu thích.');
    }
}
