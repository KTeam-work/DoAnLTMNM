<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\RentalPost;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Models\RentalPostImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OwnerRentalPostController extends Controller
{
    
    # danh sách tin đăng của chủ trọ đang đăng nhập.
    
    public function index()
    {
        $ownerId = Auth::id() ?: (config('app.demo_guest') ? \App\Models\User::where('role', 'owner')->value('id') : null);

        $posts = RentalPost::query()
            ->whereHas('room.property', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->with(['room.property','images'])
            ->latest()
            ->get();

        $properties = Property::query()
            ->where('owner_id', $ownerId)
            ->orderBy('name')
            ->get();

        $availableRooms = Room::query()
            ->whereHas('property', function ($query) use ($ownerId) {
                $query->where('owner_id', $ownerId)->where('status', 'active');
            })
            ->where('status', 'available')
            ->whereDoesntHave('rentalPosts', function ($query) {
                $query->whereIn('status', ['published', 'pending', 'draft']);
            })
            ->with('property')
            ->orderBy('name')
            ->get();

        return view('owner.rental-posts.index', compact('posts', 'properties', 'availableRooms'));
    }

    /**
     * Hiển thị form tạo tin đăng.
     */
    public function create()
    {
        $properties = Property::query()
            ->where('owner_id', Auth::id())
            ->orderBy('name')
            ->get();

        // Chỉ lấy các phòng CÒN TRỐNG (available) và chưa có tin đăng đang hoạt động
        $rooms = Room::query()
            ->whereHas('property', function ($query) {
                $query->where('owner_id', Auth::id())->where('status', 'active');
            })
            ->where('status', 'available')
            ->whereDoesntHave('rentalPosts', function ($query) {
                $query->whereIn('status', ['published', 'pending']);
            })
            ->with('property')
            ->orderBy('property_id')
            ->orderBy('name')
            ->get();

        return view('owner.rental-posts.create', compact('rooms', 'properties'));
    }

    /**
     * Lưu tin đăng mới.
     */
    
public function store(Request $request)
{
    $validated = $request->validate([
        'room_id' => [
            'required',
            'integer',
            Rule::exists('rooms', 'id')->where(
                fn ($query) => $query->whereIn(
                    'property_id',
                    Property::query()
                        ->select('id')
                        ->where('owner_id', Auth::id())
                        ->where('status', 'active')
                )->where('status', 'available')
            ),
        ],

        'title' => ['required', 'string', 'max:200'],
        'description' => ['required', 'string'],
        'status' => [
            'required',
            Rule::in(['draft', 'pending', 'hidden']),
        ],
        'expired_at' => [
            'nullable',
            'date',
            'after:today',
        ],

        'images' => ['required', 'array', 'min:1', 'max:10'],
        'images.*' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],
    ], [
        'room_id.required' => 'Vui lòng chọn phòng trọ.',
        'room_id.exists' => 'Phòng được chọn không hợp lệ hoặc không ở trạng thái còn trống.',
        'title.required' => 'Vui lòng nhập tiêu đề tin đăng.',
        'description.required' => 'Vui lòng nhập mô tả chi tiết.',
        'status.required' => 'Vui lòng chọn trạng thái tin đăng.',
        'images.required' => 'Vui lòng tải lên ít nhất một hình ảnh.',
        'images.min' => 'Vui lòng tải lên ít nhất một hình ảnh.',
        'images.max' => 'Chỉ được tải lên tối đa 10 hình ảnh.',
    ]);

    // Không cho phép tạo thêm tin nếu phòng đã có tin đăng đang hiển thị hoặc chờ duyệt
    $hasActivePost = RentalPost::query()
        ->where('room_id', $validated['room_id'])
        ->whereIn('status', ['published', 'pending'])
        ->exists();

    if ($hasActivePost) {
        return back()
            ->withInput()
            ->withErrors([
                'room_id' => 'Phòng này hiện đã có tin đăng đang hiển thị hoặc chờ duyệt.',
            ]);
    }

    $uploadedPaths = [];

    try {
        DB::transaction(function () use (
            $validated,
            $request,
            &$uploadedPaths
        ) {
            $post = RentalPost::create([
                'room_id' => $validated['room_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'status' => $validated['status'],
                'expired_at' => $validated['expired_at'] ?? null,
            ]);

            foreach ($request->file('images', []) as $index => $image) {
                $path = $image->store(
                    'rental-posts/' . $post->id,
                    'public'
                );

                if (!$path) {
                    throw new \RuntimeException(
                        'Không thể lưu ảnh tin đăng.'
                    );
                }

                $uploadedPaths[] = $path;

                $post->images()->create([
                    'image_path' => $path,
                    'is_thumbnail' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        });
    } catch (\Throwable $exception) {
        Storage::disk('public')->delete($uploadedPaths);

        throw $exception;
    }

    return redirect()
        ->route('owner.rental-posts.index')
        ->with('success', 'Tạo tin đăng và tải ảnh thành công.');
}


    public function show(RentalPost $rental_post)
    {
        $this->authorizeOwnership($rental_post);

        $rental_post->load(['room.property', 'room.amenities', 'images']);

        return view('owner.rental-posts.show', [
            'post' => $rental_post,
        ]);
    }

    /**
     * Hiển thị form chỉnh sửa tin đăng.
     */
    public function edit(RentalPost $rental_post)
    {
        $this->authorizeOwnership($rental_post);

        $properties = Property::query()
            ->where('owner_id', Auth::id())
            ->orderBy('name')
            ->get();

        // Lấy phòng hiện tại của tin đăng VÀ các phòng còn trống khác chưa có tin
        $rooms = Room::query()
            ->whereHas('property', function ($query) {
                $query->where('owner_id', Auth::id());
            })
            ->where(function ($query) use ($rental_post) {
                $query->where(function ($sub) {
                    $sub->where('status', 'available')
                        ->whereDoesntHave('rentalPosts', function ($q) {
                            $q->whereIn('status', ['published', 'pending']);
                        });
                })->orWhere('id', $rental_post->room_id);
            })
            ->with('property')
            ->orderBy('property_id')
            ->orderBy('name')
            ->get();

        return view('owner.rental-posts.edit', [
            'post' => $rental_post,
            'rooms' => $rooms,
            'properties' => $properties,
        ]);
    }

    /**
     * Cập nhật tin đăng.
     */
   
public function update(Request $request, RentalPost $rental_post)
{
    $this->authorizeOwnership($rental_post);

    $validated = $request->validate([
        'room_id' => [
            'required',
            'integer',
            Rule::exists('rooms', 'id')->where(
                fn ($query) => $query->whereIn(
                    'property_id',
                    Property::query()
                        ->select('id')
                        ->where('owner_id', Auth::id())
                )->where(function ($q) use ($rental_post) {
                    $q->where('status', 'available')
                      ->orWhere('id', $rental_post->room_id);
                })
            ),
        ],

        'title' => ['required', 'string', 'max:200'],
        'description' => ['required', 'string'],
        'status' => [
            'required',
            Rule::in(['draft', 'pending', 'hidden']),
        ],
        'expired_at' => [
            'nullable',
            'date',
            'after:today',
        ],

        'images' => ['nullable', 'array', 'max:10'],
        'images.*' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:5120',
        ],

        'remove_images' => ['nullable', 'array'],
        'remove_images.*' => ['required', 'integer'],
    ], [
        'room_id.required' => 'Vui lòng chọn phòng trọ.',
        'room_id.exists' => 'Phòng được chọn không hợp lệ hoặc không ở trạng thái còn trống.',
        'title.required' => 'Vui lòng nhập tiêu đề tin đăng.',
        'description.required' => 'Vui lòng nhập mô tả chi tiết.',
        'status.required' => 'Vui lòng chọn trạng thái tin đăng.',
    ]);

    // Nếu đổi sang phòng khác, kiểm tra xem phòng mới đã có tin đăng active nào chưa
    if ((int) $validated['room_id'] !== (int) $rental_post->room_id) {
        $hasActivePost = RentalPost::query()
            ->where('room_id', $validated['room_id'])
            ->where('id', '!=', $rental_post->id)
            ->whereIn('status', ['published', 'pending'])
            ->exists();

        if ($hasActivePost) {
            return back()
                ->withInput()
                ->withErrors([
                    'room_id' => 'Phòng này hiện đã có tin đăng khác đang hiển thị hoặc chờ duyệt.',
                ]);
        }
    }

    // Kiểm tra ảnh được yêu cầu xóa có thuộc tin đăng này không.
    $removeIds = $request->input('remove_images', []);

    $imagesToRemove = $rental_post->images()
        ->whereIn('id', $removeIds)
        ->get();

    if ($imagesToRemove->count() !== count(array_unique($removeIds))) {
        return back()
            ->withInput()
            ->withErrors([
                'remove_images' => 'Danh sách ảnh xóa không hợp lệ.',
            ]);
    }

    // Không cho phép xóa hết ảnh nếu không tải ảnh mới.
    $remainingCount = $rental_post->images()->count()
        - $imagesToRemove->count()
        + count($request->file('images', []));

    if ($remainingCount < 1) {
        return back()
            ->withInput()
            ->withErrors([
                'images' => 'Tin đăng phải có ít nhất một hình ảnh.',
            ]);
    }

    // Tổng ảnh sau khi cập nhật không được vượt quá 10.
    if ($remainingCount > 10) {
        return back()
            ->withInput()
            ->withErrors([
                'images' => 'Tin đăng chỉ được có tối đa 10 hình ảnh.',
            ]);
    }

    $contentChanged =
        $rental_post->title !== $validated['title']
        || $rental_post->description !== $validated['description'];

    if ($rental_post->status === 'published' && $contentChanged) {
        $validated['status'] = 'pending';
        $validated['published_at'] = null;
    } elseif (
        $rental_post->status === 'published'
        && $validated['status'] === 'pending'
    ) {
        $validated['published_at'] = null;
    }

    // Nếu tin bị từ chối và chủ trọ chọn gửi duyệt lại
    if ($rental_post->status === 'rejected' && $validated['status'] === 'pending') {
        $validated['rejection_reason'] = null;
    }

    unset($validated['images'], $validated['remove_images']);

    $uploadedPaths = [];
    $pathsToDelete = [];

    try {
        DB::transaction(function () use (
            $request,
            $validated,
            $rental_post,
            $imagesToRemove,
            &$uploadedPaths,
            &$pathsToDelete
        ) {
            $rental_post->update($validated);

            // Xóa ảnh được chọn khỏi database.
            foreach ($imagesToRemove as $image) {
                $pathsToDelete[] = $image->image_path;
                $image->delete();
            }

            // Thêm ảnh mới.
            $nextOrder = (int) $rental_post->images()
                ->max('sort_order') + 1;

            foreach ($request->file('images', []) as $image) {
                $path = $image->store(
                    'rental-posts/' . $rental_post->id,
                    'public'
                );

                if (!$path) {
                    throw new \RuntimeException(
                        'Không thể lưu ảnh tin đăng.'
                    );
                }

                $uploadedPaths[] = $path;

                $rental_post->images()->create([
                    'image_path' => $path,
                    'is_thumbnail' => false,
                    'sort_order' => $nextOrder++,
                ]);
            }

            // Đảm bảo luôn có ảnh đại diện nếu tin còn ảnh.
            $hasThumbnail = $rental_post->images()
                ->where('is_thumbnail', true)
                ->exists();

            if (!$hasThumbnail) {
                $firstImage = $rental_post->images()
                    ->orderBy('sort_order')
                    ->first();

                if ($firstImage) {
                    $firstImage->update([
                        'is_thumbnail' => true,
                    ]);
                }
            }
        });
    } catch (\Throwable $exception) {
        Storage::disk('public')->delete($uploadedPaths);

        throw $exception;
    }

    // Chỉ xóa file cũ sau khi transaction thành công.
    Storage::disk('public')->delete($pathsToDelete);

    return redirect()
        ->route('owner.rental-posts.index')
        ->with('success', 'Cập nhật tin đăng và hình ảnh thành công.');
}


    /**
     * Ẩn tin đăng (thay vì xóa cứng khỏi database để bảo toàn dữ liệu).
     */
    public function destroy(RentalPost $rental_post)
    {
        $this->authorizeOwnership($rental_post);

        $rental_post->update([
            'status' => 'hidden',
        ]);

        return redirect()
            ->route('owner.rental-posts.index')
            ->with('success', 'Đã ẩn tin đăng thành công! Tin sẽ không còn hiển thị với người thuê.');
    }

    /**
     * Gửi duyệt lại tin đăng (chuyển từ rejected/draft sang pending).
     */
    public function resubmit(RentalPost $rental_post)
    {
        $this->authorizeOwnership($rental_post);

        $rental_post->update([
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('owner.rental-posts.index')
            ->with('success', 'Đã gửi duyệt lại tin đăng "' . $rental_post->title . '". Vui lòng chờ Admin kiểm duyệt.');
    }

    /**
     * Lấy danh sách phòng thuộc chủ trọ hiện tại.
     */
    private function getOwnerRooms()
    {
        return Room::query()
            ->whereHas('property', function ($query) {
                $query->where('owner_id', Auth::id());
            })
            ->with([
                'property',
                'rentalPosts' => function ($query) {
                    $query->whereIn('status', ['published', 'pending']);
                },
            ])
            ->orderBy('property_id')
            ->orderBy('name')
            ->get();
    }

    /**
     * Kiểm tra tin đăng có thuộc chủ trọ hiện tại không.
     */
    private function authorizeOwnership(RentalPost $post): void
    {
        abort_unless(
            $post->room
                && $post->room->property
                && (int) $post->room->property->owner_id === (int) Auth::id(),
            403,
            'Bạn không có quyền quản lý tin đăng này.'
        );
    }
}
