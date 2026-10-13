<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OwnerpropertiesController extends Controller
{
    /**
     * Danh sách nhà trọ của chủ trọ đang đăng nhập.
     */
    public function index()
    {
        $properties = Property::query()
            ->where('owner_id', Auth::id())
            ->withCount('rooms')
            ->with('rooms')
            ->latest()
            ->get();

        return view('owner.properties.index', compact('properties'));
    }


    public function show(Property $property)
    {
        abort_unless(
            (int) $property->owner_id === (int) Auth::id(),
            403
        );

        $property->load('rooms');

        return view('owner.properties.show', compact('property'));
    }


    public function edit(Property $property)
    {
        abort_unless(
            (int) $property->owner_id === (int) Auth::id(),
            403
        );

        return view('owner.properties.edit', compact('property'));
    }


    /**
     * Lưu nhà trọ mới.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => [
                'required',
                Rule::in(['rooming_house', 'apartment', 'house']),
            ],
            'address' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $validated['owner_id'] = Auth::id();

        Property::create($validated);

        return redirect()
            ->route('owner.properties.index')
            ->with('success', 'Thêm nhà trọ thành công.');
    }

    /**
     * Cập nhật nhà trọ thuộc chủ trọ đang đăng nhập.
     */
    public function update(Request $request, Property $property)
    {
        abort_unless(
            (int) $property->owner_id === (int) Auth::id(),
            403
        );

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => [
                'required',
                Rule::in(['rooming_house', 'apartment', 'house']),
            ],
            'address' => ['required', 'string', 'max:255'],
            'district' => ['required', 'string', 'max:100'],
            'ward' => ['required', 'string', 'max:100'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $property->update($validated);

        return redirect()
            ->route('owner.properties.index')
            ->with('success', 'Cập nhật nhà trọ thành công.');
    }

    public function destroy(Property $property)
    {
        abort_unless(
            $property->owner_id === Auth::id(),
            403
        );

        if ($property->rooms()->exists()) {
            return redirect()
                ->route('owner.properties.index')
                ->with('error', 'Không thể xóa nhà trọ đang có phòng.');
        }

        $property->delete();

        return redirect()
            ->route('owner.properties.index')
            ->with('success', 'Xóa nhà trọ thành công.');
    }
}
