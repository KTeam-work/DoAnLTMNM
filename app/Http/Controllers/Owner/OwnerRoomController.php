<?php


namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;


class OwnerRoomController extends Controller
{
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);

        $rooms = Room::with('property:id,name')
            ->whereHas('property', function ($query) use ($owner) {
                $query->where('owner_id', $owner->id);
            })
            ->orderBy('name')
            ->get();

        $properties = \App\Models\Property::where(
            'owner_id',
            $owner->id
        )->orderBy('name')->get();

        return view('owner.rooms.manage', compact(
            'rooms',
            'properties'
        ));
    }

    public function create(Request $request)
    {
        $owner = $this->actingOwner($request);
        $properties = Property::query()
            ->where('owner_id', $owner->id)
            ->orderBy('name')
            ->get(['id', 'name', 'district', 'status']);

        $amenityProperties = DB::table('property_amenities')->join('properties', 'properties.id', '=', 'property_amenities.property_id')
            ->where('properties.owner_id', $owner->id)->where('property_amenities.status', 'active')
            ->get(['property_amenities.amenity_id', 'property_amenities.property_id'])->groupBy('amenity_id');
        $amenities = Amenity::whereIn('id', $amenityProperties->keys())->orderBy('name')->get();

        $existingCodes = Room::query()->whereNotNull('room_code')->pluck('room_code');
        foreach ($properties as $property) {
            $property->setAttribute(
                'next_room_code',
                $this->nextRoomCode($this->roomCodePrefix($property->district), $existingCodes)
            );
        }

        return view('owner.rooms.create', compact('properties', 'amenities', 'amenityProperties'));
    }

    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);

        // Chuẩn hóa tiền tệ trước khi validate (loại bỏ ký tự không phải số như dấu chấm, phẩy phân cách)
        $cleanPrice = preg_replace('/[^\d]/', '', (string) $request->input('price'));
        $cleanDeposit = preg_replace('/[^\d]/', '', (string) $request->input('deposit'));
        $request->merge([
            'price' => $cleanPrice !== '' ? $cleanPrice : null,
            'deposit' => $cleanDeposit !== '' ? $cleanDeposit : null,
        ]);

        $propertyId = $request->input('property_id');
        $floor = $request->input('floor');

        $duplicateMsg = ($floor !== null && $floor !== '')
            ? "Tên phòng này đã tồn tại ở tầng {$floor} trong khu trọ này. Vui lòng đặt tên khác."
            : "Tên phòng này đã tồn tại trong khu trọ này. Vui lòng đặt tên khác.";

        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'floor' => ['nullable', 'integer', 'min:0'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms', 'name')->where(function ($query) use ($propertyId, $floor) {
                    $query->where('property_id', $propertyId);
                    if ($floor !== null && $floor !== '') {
                        $query->where('floor', $floor);
                    } else {
                        $query->whereNull('floor');
                    }
                }),
            ],
            'area' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'deposit' => ['required', 'numeric', 'min:0', 'lte:price'],
            'max_people' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'in:available,pending,rented,maintenance,hidden'],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ], [
            'property_id.required' => 'Vui lòng chọn khu trọ.',
            'property_id.exists' => 'Khu trọ không tồn tại trong hệ thống.',
            'name.required' => 'Vui lòng nhập tên phòng.',
            'name.unique' => $duplicateMsg,
            'price.required' => 'Vui lòng nhập giá thuê phòng.',
            'price.numeric' => 'Giá thuê phải là một số hợp lệ.',
            'price.min' => 'Giá thuê không được nhỏ hơn 0.',
            'deposit.required' => 'Vui lòng nhập tiền đặt cọc.',
            'deposit.numeric' => 'Tiền đặt cọc phải là một số hợp lệ.',
            'deposit.min' => 'Tiền đặt cọc không được nhỏ hơn 0.',
            'area.required' => 'Vui lòng nhập diện tích phòng.',
            'area.numeric' => 'Diện tích phòng phải là một số.',
            'max_people.required' => 'Vui lòng nhập sức chứa.',
            'max_people.min' => 'Sức chứa tối thiểu là 1 người.',

        ]);

        $amenityIds = $request->input('amenities', []);
        unset($data['amenities']);

        $property = Property::query()
            ->whereKey($data['property_id'])
            ->where('owner_id', $owner->id)
            ->firstOrFail();

        $this->validatePropertyAmenities($property, $amenityIds);

        DB::transaction(function () use ($property, $data, $amenityIds): void {
            // Serialize generation for properties in the same district.
            Property::query()
                ->where('district', $property->district)
                ->orderBy('id')
                ->lockForUpdate()
                ->get(['id']);

            $prefix = $this->roomCodePrefix($property->district);
            // Continue numbering from existing codes that used either "_" or "-".
            $room = Room::query()->create([
                ...$data,
                'room_code' => $this->nextRoomCode(
                    $prefix,
                    Room::query()->whereNotNull('room_code')->pluck('room_code')
                ),
                'status' => $data['status'] ?? 'available',
            ]);

            if (!empty($amenityIds)) {
                $room->amenities()->sync($amenityIds);
            }
        });

        return redirect()->route('owner.rooms.index')->with('success', 'Tạo phòng thành công.');
    }

    private function roomCodePrefix(string $district): string
    {
        $normalized = Str::upper(Str::ascii($district));
        $normalized = trim((string) preg_replace('/[^A-Z0-9]+/', ' ', $normalized));

        $administrativePrefixes = [
            '/^(?:QUAN|Q)\s*/' => 'Q',
            '/^(?:HUYEN|H)\s*/' => 'H',
            '/^(?:THANH PHO|TP)\s*/' => 'TP',
            '/^(?:THI XA|TX)\s*/' => 'TX',
            '/^(?:THI TRAN|TT)\s*/' => 'TT',
        ];

        $prefix = '';
        foreach ($administrativePrefixes as $pattern => $candidatePrefix) {
            if (preg_match($pattern, $normalized)) {
                $normalized = trim((string) preg_replace($pattern, '', $normalized, 1));
                $prefix = $candidatePrefix;
                break;
            }
        }

        $words = preg_split('/\s+/', $normalized, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $districtCode = implode('', array_map(
            static fn (string $word): string => ctype_digit($word) ? $word : substr($word, 0, 1),
            $words
        ));

        return $prefix . ($districtCode !== '' ? $districtCode : 'P');
    }

    private function nextRoomCode(string $prefix, iterable $existingCodes): string
    {
        $pattern = '/^' . preg_quote($prefix, '/') . '[-_]([0-9]+)$/i';
        $nextNumber = 1;

        foreach ($existingCodes as $existingCode) {
            if (preg_match($pattern, $existingCode, $matches)) {
                $nextNumber = max($nextNumber, ((int) $matches[1]) + 1);
            }
        }

        return sprintf('%s_%02d', $prefix, $nextNumber);
    }
    /**
     * Hiển thị form sửa phòng.
     */
    public function edit(Request $request, Room $room)
    {
        $owner = $this->actingOwner($request);

        // Chỉ cho phép sửa phòng thuộc nhà trọ của chủ trọ hiện tại.
        $this->authorizeRoomOwner($room, $owner);

        $hasActiveContract = $room->contracts()->where('status', 'active')->exists() || $room->status === 'rented';

        $properties = Property::query()
            ->where('owner_id', $owner->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        $room->load('amenities');
        $amenities = $room->property->amenities()->where(function ($query) use ($room) {
            $query->where('property_amenities.status', 'active')
                ->orWhereIn('amenities.id', $room->amenities->pluck('id'));
        })->orderBy('name')->get();

        return view('owner.rooms.edit', compact('room', 'properties', 'amenities', 'hasActiveContract'));
    }

    /**
     * Cập nhật thông tin phòng.
     */
    public function update(Request $request, Room $room)
    {
        $owner = $this->actingOwner($request);

        $this->authorizeRoomOwner($room, $owner);

        $hasActiveContract = $room->contracts()->where('status', 'active')->exists() || $room->status === 'rented';

        // Không cho phép đổi nhà trọ của phòng đã tạo
        $request->merge([
            'property_id' => $room->property_id,
        ]);

        // Nếu phòng đang có hợp đồng thuê hoạt động hoặc đang được thuê:
        if ($hasActiveContract) {
            // Giá thuê và tiền cọc được cố định theo hợp đồng
            $request->merge([
                'price' => $room->price,
                'deposit' => $room->deposit,
            ]);

            // Không thể chuyển trạng thái về "Còn trống" khi hợp đồng còn hiệu lực
            if ($request->input('status') === 'available') {
                return back()->withInput()->withErrors([
                    'status' => 'Phòng đang có hợp đồng thuê đang có hiệu lực. Không thể chuyển trạng thái về "Còn trống" khi chưa thanh lý hợp đồng.',
                ]);
            }
        }

        // Chuẩn hóa tiền tệ trước khi validate
        $cleanPrice = preg_replace('/[^\d]/', '', (string) $request->input('price'));
        $cleanDeposit = preg_replace('/[^\d]/', '', (string) $request->input('deposit'));
        $request->merge([
            'price' => $cleanPrice !== '' ? $cleanPrice : null,
            'deposit' => $cleanDeposit !== '' ? $cleanDeposit : null,
        ]);

        $propertyId = $request->input('property_id');
        $floor = $request->input('floor');

        $duplicateMsg = ($floor !== null && $floor !== '')
            ? "Tên phòng này đã tồn tại ở tầng {$floor} trong khu trọ này. Vui lòng đặt tên khác."
            : "Tên phòng này đã tồn tại trong khu trọ này. Vui lòng đặt tên khác.";

        $data = $request->validate([
            'property_id' => ['required', 'integer', 'exists:properties,id'],
            'floor' => ['nullable', 'integer', 'min:0'],
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('rooms', 'name')
                    ->where(function ($query) use ($propertyId, $floor) {
                        $query->where('property_id', $propertyId);
                        if ($floor !== null && $floor !== '') {
                            $query->where('floor', $floor);
                        } else {
                            $query->whereNull('floor');
                        }
                    })
                    ->ignore($room->id),
            ],
            'area' => ['required', 'numeric', 'min:0.01'],
            'price' => ['required', 'numeric', 'min:0'],
            'deposit' => ['required', 'numeric', 'min:0', 'lte:price'],
            'max_people' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'status' => [
                'required',
                'in:available,pending,rented,maintenance,hidden',
            ],
            'amenities' => ['nullable', 'array'],
            'amenities.*' => ['integer', 'exists:amenities,id'],
        ], [
            'name.required' => 'Vui lòng nhập tên phòng.',
            'name.unique' => $duplicateMsg,
            'price.required' => 'Vui lòng nhập giá thuê phòng.',
            'price.numeric' => 'Giá thuê phải là một số hợp lệ.',
            'deposit.required' => 'Vui lòng nhập tiền đặt cọc.',
            'deposit.numeric' => 'Tiền đặt cọc phải là một số hợp lệ.',
        ]);

        $amenityIds = $request->input('amenities', []);
        unset($data['amenities']);

        // Không được chuyển phòng sang nhà trọ của chủ khác.
        abort_unless(
            Property::query()
                ->whereKey($data['property_id'])
                ->where('owner_id', $owner->id)
                ->exists(),
            404
        );

        $this->validatePropertyAmenities($room->property, $amenityIds, $room);

        $room->update($data);
        $room->amenities()->sync($amenityIds);

        return redirect()
            ->route('owner.rooms.index')
            ->with('success', 'Cập nhật phòng thành công.');
    }

    private function validatePropertyAmenities(Property $property, array $ids, ?Room $room = null): void
    {
        $allowed = $property->amenities()->where(function ($query) use ($room) {
            $query->where('property_amenities.status', 'active');
            if ($room) $query->orWhereIn('amenities.id', $room->amenities()->select('amenities.id'));
        })->pluck('amenities.id')->map(fn ($id) => (int) $id)->all();
        if (array_diff(array_map('intval', $ids), $allowed)) {
            throw \Illuminate\Validation\ValidationException::withMessages(['amenities' => 'Tiện ích không thuộc nhà trọ đã chọn hoặc đang tạm ngưng.']);
        }
    }

    /**
     * Xóa phòng trọ.
     */
    public function destroy(Request $request, Room $room)
    {
        $owner = $this->actingOwner($request);

        // Chỉ được xóa phòng thuộc khu trọ của chủ nhà hiện tại.
        $this->authorizeRoomOwner($room, $owner);

        // Không xóa phòng đang có hợp đồng.
        if ($room->contracts()->exists()) {
            return redirect()
                ->route('owner.rooms.index')
                ->with('error', 'Không thể xóa phòng đang có lịch sử hợp đồng.');
        }

        // Không xóa phòng đang có tin đăng cho thuê đang chờ duyệt hoặc đang hiển thị
        if ($room->rentalPosts()->whereIn('status', ['published', 'pending'])->exists()) {
            return redirect()
                ->route('owner.rooms.index')
                ->with('error', 'Không thể xóa phòng đang có bài đăng cho thuê trên hệ thống.');
        }

        // Không xóa phòng đang có yêu cầu bảo trì chưa xử lý xong
        if ($room->maintenanceRequests()->whereIn('status', ['pending', 'processing'])->exists()) {
            return redirect()
                ->route('owner.rooms.index')
                ->with('error', 'Không thể xóa phòng đang có yêu cầu sửa chữa chưa hoàn thành.');
        }

        $panoramaPath = $room->panorama_path;
        $room->delete();
        if ($panoramaPath) \Illuminate\Support\Facades\Storage::disk('public')->delete($panoramaPath);

        return redirect()
            ->route('owner.rooms.index')
            ->with('success', 'Xóa phòng thành công.');
    }
    public function show(Request $request, Room $room)
    {
        $owner = $this->actingOwner($request);

        $this->authorizeRoomOwner($room, $owner);

        $room->load('property');

        return view('owner.rooms.show', compact('room'));
    }


    private function authorizeRoomOwner(Room $room, User $owner): void
    {
        abort_unless(
            Property::query()
                ->whereKey($room->property_id)
                ->where('owner_id', $owner->id)
                ->exists(),
            404
        );
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
