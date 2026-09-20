<?php


namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class OwnerRoomController extends Controller
{
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);
        $rooms = Room::query()
            ->whereHas('property', fn ($query) => $query->where('owner_id', $owner->id))
            ->with('property:id,name')
            ->orderBy('name')
            ->get();

        return view('owner.rooms.manage', compact('rooms'));
    }

    public function create(Request $request)
    {
        $owner = $this->actingOwner($request);
        $properties = Property::query()->where('owner_id', $owner->id)->orderBy('name')->get(['id', 'name']);

        return view('owner.rooms.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);
        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'name' => ['required', 'string', 'max:100'],
            'room_code' => ['nullable', 'string', 'max:20', 'unique:rooms,room_code'],
            'area' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'deposit' => ['required', 'numeric', 'min:0'],
            'max_people' => ['required', 'integer', 'min:1'],
            'floor' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:available,pending,rented,maintenance,hidden'],
        ]);

        abort_unless(Property::query()->whereKey($data['property_id'])->where('owner_id', $owner->id)->exists(), 404);
        Room::query()->create([...$data, 'status' => $data['status'] ?? 'available']);

        return redirect()->route('owner.rooms.index')->with('success', 'Tạo phòng thành công.');
    }

    private function owner(Request $request): User
    {
        $user = $request->user();
        if (! $user) {
            if (! $request->expectsJson()) {
                throw new AuthenticationException('Authentication is required.', ['web'], route('login'));
            }

            abort(401, 'Authentication is required.');
        }
        abort_unless($user->role === 'owner', 403, 'Only owners can manage rooms.');

        return $user;
    }

    /**
     * DEMO MODE: web chưa đăng nhập (flag bật) dùng owner đầu tiên để test.
     * Tắt flag là guest bị chuyển về login. JSON luôn cần login.
     */
    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage rooms.');

            return $user;
        }
        if (! $request->expectsJson()) {
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'owner')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản chủ trọ, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }
}
