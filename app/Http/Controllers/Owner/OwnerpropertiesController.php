<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
            ->with(['rooms', 'thumbnail', 'images'])
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

        $property->load(['rooms', 'images', 'thumbnail']);

        return view('owner.properties.show', compact('property'));
    }

    public function edit(Property $property)
    {
        abort_unless(
            (int) $property->owner_id === (int) Auth::id(),
            403
        );

        $property->load(['images', 'thumbnail']);

        return view('owner.properties.edit', compact('property'));
    }

    /**
     * Quy tắc xác thực dữ liệu nhà trọ.
     */
    private function propertyRules(): array
    {
        return [
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

            'images' => ['nullable', 'array', 'max:10'],
            'images.*' => [
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    /**
     * Lưu nhà trọ mới và ảnh được tải lên.
     */
    public function store(Request $request)
    {
        $validated = $request->validate($this->propertyRules());

        $uploadedImages = $request->file('images', []);
        unset($validated['images']);

        $validated['owner_id'] = Auth::id();

        $storedPaths = [];

        try {
            DB::transaction(function () use (
                $validated,
                $uploadedImages,
                &$storedPaths
            ) {
                $property = Property::create($validated);

                foreach ($uploadedImages as $index => $image) {
                    $path = $image->store(
                        'properties/' . $property->id,
                        'public'
                    );

                    if (!$path) {
                        throw new \RuntimeException('Không thể lưu ảnh nhà trọ.');
                    }

                    $storedPaths[] = $path;

                    $property->images()->create([
                        'image_path' => $path,
                        'is_thumbnail' => $index === 0,
                    ]);
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($exception);

            return back()
                ->withInput($request->except('images'))
                ->with('error', 'Không thể lưu nhà trọ hoặc ảnh. Vui lòng thử lại.');
        }

        return redirect()
            ->route('owner.properties.index')
            ->with('success', 'Thêm nhà trọ thành công.');
    }

    /**
     * Cập nhật thông tin nhà trọ, ảnh mới, ảnh cần xóa và ảnh đại diện.
     */
    public function update(Request $request, Property $property)
    {
        abort_unless(
            (int) $property->owner_id === (int) Auth::id(),
            403
        );

        $validated = $request->validate(array_merge(
            $this->propertyRules(),
            [
                'delete_images' => ['nullable', 'array'],
                'delete_images.*' => ['integer'],
                'thumbnail_image' => ['nullable', 'integer'],
            ]
        ));

        $uploadedImages = $request->file('images', []);
        $deleteImageIds = $validated['delete_images'] ?? [];
        $thumbnailImageId = $validated['thumbnail_image'] ?? null;

        unset(
            $validated['images'],
            $validated['delete_images'],
            $validated['thumbnail_image']
        );

        // Chỉ cho phép xóa ảnh thuộc đúng nhà trọ này.
        $imagesToDelete = $property->images()
            ->whereIn('id', $deleteImageIds)
            ->get();

        // Chỉ cho chọn ảnh đại diện thuộc nhà trọ này.
        if ($thumbnailImageId !== null) {
            $thumbnailExists = $property->images()
                ->whereKey($thumbnailImageId)
                ->whereNotIn('id', $deleteImageIds)
                ->exists();

            if (!$thumbnailExists) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'thumbnail_image' => 'Ảnh đại diện không hợp lệ hoặc đã được chọn xóa.',
                    ]);
            }
        }

        $storedPaths = [];
        $pathsToDelete = $imagesToDelete->pluck('image_path')->all();

        try {
            DB::transaction(function () use (
                $property,
                $validated,
                $uploadedImages,
                $imagesToDelete,
                $thumbnailImageId,
                &$storedPaths
            ) {
                $property->update($validated);

                foreach ($imagesToDelete as $image) {
                    $image->delete();
                }

                foreach ($uploadedImages as $image) {
                    $path = $image->store(
                        'properties/' . $property->id,
                        'public'
                    );

                    if (!$path) {
                        throw new \RuntimeException('Không thể lưu ảnh nhà trọ.');
                    }

                    $storedPaths[] = $path;

                    $property->images()->create([
                        'image_path' => $path,
                        'is_thumbnail' => false,
                    ]);
                }

                // Bỏ trạng thái ảnh đại diện cũ trước khi chọn ảnh mới.
                $property->images()->update([
                    'is_thumbnail' => false,
                ]);

                if ($thumbnailImageId !== null) {
                    $property->images()
                        ->whereKey($thumbnailImageId)
                        ->update(['is_thumbnail' => true]);
                } else {
                    // Nếu ảnh đại diện cũ bị xóa hoặc chưa có,
                    // chọn ảnh còn lại đầu tiên làm đại diện.
                    $thumbnail = $property->images()
                        ->orderBy('id')
                        ->first();

                    if ($thumbnail) {
                        $thumbnail->update(['is_thumbnail' => true]);
                    }
                }
            });
        } catch (\Throwable $exception) {
            foreach ($storedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            report($exception);

            return back()
                ->withInput($request->except('images'))
                ->with('error', 'Không thể cập nhật nhà trọ hoặc ảnh. Vui lòng thử lại.');
        }

        // Chỉ xóa file sau khi giao dịch database thành công.
        foreach ($pathsToDelete as $path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('owner.properties.edit', $property)
            ->with('success', 'Cập nhật nhà trọ và hình ảnh thành công.');
    }

    public function destroy(Property $property)
    {
        abort_unless(
            (int) $property->owner_id === (int) Auth::id(),
            403
        );

        if ($property->rooms()->exists()) {
            return redirect()
                ->route('owner.properties.index')
                ->with('error', 'Không thể xóa nhà trọ đang có phòng.');
        }

        $imagePaths = $property->images()
            ->pluck('image_path')
            ->all();

        $property->delete();

        foreach ($imagePaths as $path) {
            Storage::disk('public')->delete($path);
        }

        return redirect()
            ->route('owner.properties.index')
            ->with('success', 'Xóa nhà trọ thành công.');
    }
}
