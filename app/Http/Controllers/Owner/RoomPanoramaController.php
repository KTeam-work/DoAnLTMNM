<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RoomPanoramaController extends Controller
{
    public function store(Request $request, Room $room)
    {
        $this->authorizeOwner($request, $room);
        $request->validate(['panorama' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:15360', 'dimensions:ratio=2/1,max_width=12000,max_height=6000']], [
            'panorama.dimensions' => 'Ảnh 360° phải có tỷ lệ 2:1, kích thước tối đa 12000 × 6000 pixel.',
            'panorama.max' => 'Ảnh 360° không được vượt quá 15 MB.',
        ]);
        $path = $request->file('panorama')->store('room-panoramas', 'public');
        if (! $path) return back()->withErrors(['panorama' => 'Không thể lưu ảnh 360°. Vui lòng thử lại.']);
        $oldPath = $room->panorama_path;
        try { $room->update(['panorama_path' => $path]); }
        catch (\Throwable $e) { Storage::disk('public')->delete($path); throw $e; }
        if ($oldPath && $oldPath !== $path) Storage::disk('public')->delete($oldPath);
        return back()->with('success', 'Đã lưu ảnh 360° cho phòng.');
    }

    public function destroy(Request $request, Room $room)
    {
        $this->authorizeOwner($request, $room);
        $oldPath = $room->panorama_path;
        $room->update(['panorama_path' => null]);
        if ($oldPath) Storage::disk('public')->delete($oldPath);
        return back()->with('success', 'Đã xóa ảnh 360°.');
    }

    private function authorizeOwner(Request $request, Room $room): void
    {
        abort_unless($request->user()->role === 'owner' && $room->property?->owner_id === $request->user()->id, 404);
    }
}
