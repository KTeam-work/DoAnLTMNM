<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RoomCompareController extends Controller
{
    private function publicRooms(): Builder
    {
        return Room::query()->where('status', 'available')
            ->whereHas('property', fn ($q) => $q->where('status', 'active'))
            ->whereHas('rentalPosts', fn ($q) => $q->where('status', 'published')->where(fn ($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', now())));
    }

    private function selected(Request $request): array
    {
        $ids = collect($request->session()->get('compare_rooms', []))->filter(fn ($id) => is_numeric($id) && (int) $id > 0)->map(fn ($id) => (int) $id)->unique()->take(3)->values();
        $available = $this->publicRooms()->whereIn('id', $ids)->pluck('id')->map(fn ($id) => (int) $id)->all();
        return $ids->filter(fn ($id) => in_array($id, $available, true))->values()->all();
    }

    public function index(Request $request)
    {
        $ids = $this->selected($request);
        $request->session()->put('compare_rooms', $ids);
        $roomsById = $this->publicRooms()->whereIn('id', $ids)->with(['property', 'amenities', 'rentalPosts' => fn ($q) => $q->where('status', 'published')->where(fn ($q) => $q->whereNull('expired_at')->orWhere('expired_at', '>', now()))->with('images')])->get()->keyBy('id');
        $rooms = collect($ids)->map(fn ($id) => $roomsById->get($id))->filter()->values();
        return view('rooms.compare', compact('rooms'));
    }

    public function store(Request $request, Room $room)
    {
        $this->publicRooms()->findOrFail($room->id);
        $ids = $this->selected($request);
        if (in_array($room->id, $ids, true)) return back()->with('compare_message', 'Phòng đã có trong danh sách so sánh.');
        if (count($ids) >= 3) return back()->with('compare_error', 'Bạn có thể so sánh tối đa 3 phòng. Hãy bỏ một phòng trước khi thêm phòng khác.');
        $ids[] = $room->id;
        $request->session()->put('compare_rooms', $ids);
        return back()->with('compare_message', 'Đã thêm phòng vào danh sách so sánh.');
    }

    public function destroy(Request $request, Room $room)
    {
        $request->session()->put('compare_rooms', array_values(array_diff($this->selected($request), [$room->id])));
        return back()->with('compare_message', 'Đã bỏ phòng khỏi danh sách so sánh.');
    }
}
