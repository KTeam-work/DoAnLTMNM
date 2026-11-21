<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use Illuminate\Http\Request;

class OwnerAmenityController extends Controller
{
    public function index(Request $request)
    {
        $amenities = Amenity::orderBy('id', 'desc')->get();

        return view('owner.amenities.index', compact('amenities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:amenities,name',
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Vui lòng nhập tên tiện ích.',
            'name.unique' => 'Tiện ích này đã tồn tại trong danh sách.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        Amenity::create([
            'name' => trim($request->input('name')),
            'description' => $request->input('description'),
            'status' => $request->input('status', 'active'),
        ]);

        return redirect()->route('owner.amenities.index')->with('success', 'Thêm tiện ích mới thành công!');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:amenities,name,' . $amenity->id,
            'description' => 'nullable|string|max:500',
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Vui lòng nhập tên tiện ích.',
            'name.unique' => 'Tên tiện ích đã tồn tại.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $amenity->update([
            'name' => trim($request->input('name')),
            'description' => $request->input('description'),
            'status' => $request->input('status'),
        ]);

        return redirect()->route('owner.amenities.index')->with('success', 'Cập nhật tiện ích thành công!');
    }

    public function destroy(Amenity $amenity)
    {
        // Gỡ liên kết phòng trước khi xóa
        $amenity->rooms()->detach();
        $amenity->delete();

        return redirect()->route('owner.amenities.index')->with('success', 'Đã xóa tiện ích khỏi danh sách!');
    }
}
