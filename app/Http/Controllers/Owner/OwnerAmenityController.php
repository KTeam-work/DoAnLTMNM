<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OwnerAmenityController extends Controller
{
    private function owner(Request $request): User
    {
        if ($request->user()) {
            abort_unless($request->user()->role === 'owner', 403);
            return $request->user();
        }
        if (config('app.demo_guest') && ! $request->expectsJson()) {
            return User::where('role', 'owner')->orderBy('id')->firstOrFail();
        }
        throw new AuthenticationException();
    }

    private function property(Request $request, $id): Property
    {
        return $this->owner($request)->properties()->findOrFail($id);
    }

    public function index(Request $request)
    {
        $properties = $this->owner($request)->properties()->orderBy('name')->get();
        $selectedProperty = $request->filled('property_id')
            ? $properties->firstWhere('id', (int) $request->input('property_id'))
            : $properties->first();
        if ($request->filled('property_id') && ! $selectedProperty) abort(404);

        $amenities = $selectedProperty
            ? $selectedProperty->amenities()->withCount(['rooms as property_rooms_count' => fn ($q) => $q->where('rooms.property_id', $selectedProperty->id)])->orderBy('name')->get()
            : collect();
        return view('owner.amenities.index', compact('amenities', 'properties', 'selectedProperty'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'property_id' => 'required|integer', 'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500', 'status' => 'required|in:active,inactive',
        ]);
        $property = $this->property($request, $data['property_id']);
        $name = trim($data['name']);
        if ($name === '') throw ValidationException::withMessages(['name' => 'Vui lòng nhập tên tiện ích.']);

        DB::transaction(function () use ($property, $data, $name) {
            $amenity = Amenity::firstOrCreate(['name' => $name], ['status' => 'active']);
            if ($property->amenities()->where('amenities.id', $amenity->id)->exists()) {
                throw ValidationException::withMessages(['name' => 'Tiện ích này đã có trong nhà trọ.']);
            }
            $property->amenities()->attach($amenity->id, ['description' => $data['description'] ?? null, 'status' => $data['status']]);
        });
        return redirect()->route('owner.amenities.index', ['property_id' => $property->id])->with('success', 'Đã thêm tiện ích cho nhà trọ.');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $data = $request->validate([
            'property_id' => 'required|integer', 'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:500', 'status' => 'required|in:active,inactive',
        ]);
        $property = $this->property($request, $data['property_id']);
        abort_unless($property->amenities()->where('amenities.id', $amenity->id)->exists(), 404);
        $name = trim($data['name']);
        if ($name === '') throw ValidationException::withMessages(['name' => 'Vui lòng nhập tên tiện ích.']);

        DB::transaction(function () use ($property, $amenity, $data, $name) {
            $target = Amenity::firstOrCreate(['name' => $name], ['status' => 'active']);
            if ($target->id !== $amenity->id) {
                if ($property->amenities()->where('amenities.id', $target->id)->exists()) {
                    throw ValidationException::withMessages(['name' => 'Tên tiện ích đã tồn tại trong nhà trọ.']);
                }
                $property->amenities()->attach($target->id, ['description' => $data['description'] ?? null, 'status' => $data['status']]);
                DB::table('room_amenities')->where('amenity_id', $amenity->id)
                    ->whereIn('room_id', $property->rooms()->select('id'))->update(['amenity_id' => $target->id]);
                $property->amenities()->detach($amenity->id);
            } else {
                $property->amenities()->updateExistingPivot($amenity->id, ['description' => $data['description'] ?? null, 'status' => $data['status']]);
            }
        });
        return redirect()->route('owner.amenities.index', ['property_id' => $property->id])->with('success', 'Đã cập nhật tiện ích của nhà trọ.');
    }

    public function destroy(Request $request, Amenity $amenity)
    {
        $property = $this->property($request, $request->input('property_id'));
        abort_unless($property->amenities()->where('amenities.id', $amenity->id)->exists(), 404);
        DB::transaction(function () use ($property, $amenity) {
            DB::table('room_amenities')->where('amenity_id', $amenity->id)
                ->whereIn('room_id', $property->rooms()->select('id'))->delete();
            $property->amenities()->detach($amenity->id);
        });
        return redirect()->route('owner.amenities.index', ['property_id' => $property->id])->with('success', 'Đã xóa tiện ích khỏi nhà trọ và các phòng thuộc nhà này.');
    }
}
