<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\RentalPost;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class OwnerRentalPostController extends Controller
{
    /**
     * Danh sách tin đăng của chủ trọ đang đăng nhập.
     */
    public function index()
    {
        $posts = RentalPost::query()
            ->whereHas('room.property', function ($query) {
                $query->where('owner_id', Auth::id());
            })
            ->with(['room.property'])
            ->latest()
            ->get();

        return view('owner.rental-posts.index', compact('posts'));
    }

    /**
     * Hiển thị form tạo tin đăng.
     */
    public function create()
    {
        $rooms = $this->getOwnerRooms();

        return view('owner.rental-posts.create', compact('rooms'));
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
                Rule::exists('rooms', 'id')->where(function ($query) {
                    $query->whereIn(
                        'property_id',
                        Property::query()
                            ->select('id')
                            ->where('owner_id', Auth::id())
                    );
                }),
            ],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'status' => [
                'required',
                Rule::in(['draft', 'pending', 'hidden']),
            ],
            'expired_at' => ['nullable', 'date', 'after:today'],
        ]);

        RentalPost::create($validated);

        return redirect()
            ->route('owner.rental-posts.index')
            ->with('success', 'Tạo tin đăng thành công.');
    }

    public function show(RentalPost $rental_post)
{
    $this->authorizeOwnership($rental_post);

    $rental_post->load('room.property');

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

        $rooms = $this->getOwnerRooms();

        return view('owner.rental-posts.edit', [
            'post' => $rental_post,
            'rooms' => $rooms,
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
                Rule::exists('rooms', 'id')->where(function ($query) {
                    $query->whereIn(
                        'property_id',
                        Property::query()
                            ->select('id')
                            ->where('owner_id', Auth::id())
                    );
                }),
            ],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'status' => [
                'required',
                Rule::in(['draft', 'pending', 'hidden']),
            ],
            'expired_at' => ['nullable', 'date', 'after:today'],
        ]);

        // Nội dung tin đã đăng thay đổi thì cần gửi duyệt lại.
        $contentChanged =
            $rental_post->title !== $validated['title']
            || $rental_post->description !== $validated['description']
            || (int) $rental_post->room_id !== (int) $validated['room_id'];

        if (
            $rental_post->status === 'published'
            && $contentChanged
        ) {
            $validated['status'] = 'pending';
            $validated['published_at'] = null;
        } elseif (
            $rental_post->status === 'published'
            && $validated['status'] === 'pending'
        ) {
            $validated['published_at'] = null;
        }

        // Chủ trọ không được tự sửa lý do từ chối.
        unset($validated['rejection_reason']);

        $rental_post->update($validated);

        return redirect()
            ->route('owner.rental-posts.index')
            ->with('success', 'Cập nhật tin đăng thành công.');
    }

    /**
     * Xóa tin đăng.
     */
    public function destroy(RentalPost $rental_post)
    {
        $this->authorizeOwnership($rental_post);

        $rental_post->delete();

        return redirect()
            ->route('owner.rental-posts.index')
            ->with('success', 'Xóa tin đăng thành công.');
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
            ->with('property')
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
