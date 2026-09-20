<?php

namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Room;
use App\Models\User;
use App\Models\UtilityReading;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class OwnerUtilitiesController extends Controller
{
    public function index(Request $request)
    {
        // Production (flag tắt): guest web bị chuyển về login, không lộ dữ liệu demo.
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        $month = $request->input('month', now()->format('Y-m'));
        $selectedRoomId = $request->input('room_id');

        $readings = $owner
            ? UtilityReading::query()
                ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
                ->when($selectedRoomId, fn ($query) => $query->where('room_id', $selectedRoomId))
                ->when($month, fn ($query) => $query->whereDate('month', Carbon::parse($month)->startOfMonth()))
                ->with('room:id,name,room_code,property_id')
                ->orderByDesc('month')
                ->get()
            : collect();

        $rooms = $owner
            ? Room::query()
                ->whereHas('property', fn ($query) => $query->where('owner_id', $owner->id))
                ->with('property:id,name')
                ->orderBy('name')
                ->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('owner.utilities.index', compact('readings', 'rooms', 'month', 'selectedRoomId'));
        }

        $owner = $this->owner($request);

        $data = $request->validate([
            'room_id' => ['nullable', 'integer'],
            'month' => ['nullable', 'date'],
        ]);

        $readings = UtilityReading::query()
            ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
            ->when($data['room_id'] ?? null, fn ($query, $roomId) => $query->where('room_id', $roomId))
            ->when($data['month'] ?? null, fn ($query, $month) => $query->whereDate('month', Carbon::parse($month)->startOfMonth()))
            ->with('room:id,name,room_code,property_id')
            ->orderByDesc('month')
            ->get();

        return response()->json(['data' => $readings]);
    }

    public function create(Request $request)
    {
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        $rooms = $owner
            ? Room::query()
                ->whereHas('property', fn ($query) => $query->where('owner_id', $owner->id))
                ->with('property:id,name')
                ->orderBy('name')
                ->get()
            : collect();

        // Chế độ sửa khi có ?id=: load bản ghi để prefill form.
        $reading = null;
        if ($request->filled('id') && $owner) {
            $reading = UtilityReading::query()
                ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
                ->with('room:id,name,room_code')
                ->find($request->input('id'));
        }

        $prefillRoomId = $request->input('room_id', $reading?->room_id);
        $prefillMonth = $request->input('month', $reading ? Carbon::parse($reading->month)->format('Y-m') : now()->format('Y-m'));

        return view('owner.utilities.create', compact('rooms', 'reading', 'prefillRoomId', 'prefillMonth'));
    }

    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);
        $data = $this->validatedReading($request);
        $room = $this->ownedRoom($owner, $data['room_id']);
        $data['month'] = Carbon::parse($data['month'])->startOfMonth()->toDateString();
        if (UtilityReading::query()->where('room_id', $room->id)->whereDate('month', $data['month'])->exists()) {
            $message = 'Phòng này đã có chỉ số cho tháng ' . Carbon::parse($data['month'])->format('m/Y') . '.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['month' => $message])->withInput();
            }
            abort(422, 'A reading already exists for this room and month.');
        }

        [$electricityOld, $waterOld] = $this->previousReadings($room, $data['month']);
        $data['electricity_old'] = $data['electricity_old'] ?? $electricityOld;
        $data['water_old'] = $data['water_old'] ?? $waterOld;
        if ($redirect = $this->invalidReadingsRedirect($request, $data)) {
            return $redirect;
        }
        $reading = UtilityReading::query()->create($data);

        if (! $request->expectsJson()) {
            return redirect()->route('owner.utilities.index')->with('success', 'Đã ghi chỉ số điện nước.');
        }

        return response()->json(['message' => 'Utility reading created.', 'data' => $reading->load('room:id,name,room_code')], 201);
    }

    public function update(Request $request, ?UtilityReading $utilityReading = null)
    {
        $owner = $this->actingOwner($request);
        $utilityReading ??= UtilityReading::query()->findOrFail($request->integer('id'));
        $this->ownedRoom($owner, $utilityReading->room_id);
        $data = $this->validatedReading($request, true);
        abort_if($data === [], 422, 'Provide at least one utility reading field to update.');
        if (isset($data['room_id']) && (int) $data['room_id'] !== $utilityReading->room_id) {
            $message = 'Không thể chuyển chỉ số sang phòng khác.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['room_id' => $message])->withInput();
            }
            abort(422, 'A utility reading cannot be moved to another room.');
        }
        unset($data['room_id']);

        if (isset($data['month'])) {
            $data['month'] = Carbon::parse($data['month'])->startOfMonth()->toDateString();
            if (UtilityReading::query()->where('room_id', $utilityReading->room_id)->whereDate('month', $data['month'])->whereKeyNot($utilityReading->id)->exists()) {
                $message = 'Phòng này đã có chỉ số cho tháng ' . Carbon::parse($data['month'])->format('m/Y') . '.';
                if (! $request->expectsJson()) {
                    return back()->withErrors(['month' => $message])->withInput();
                }
                abort(422, 'A reading already exists for this room and month.');
            }
        }

        $candidate = array_merge($utilityReading->only([
            'electricity_old', 'electricity_new', 'electricity_price', 'water_old', 'water_new', 'water_price',
        ]), $data);
        if ($redirect = $this->invalidReadingsRedirect($request, $candidate)) {
            return $redirect;
        }
        $utilityReading->update($data);

        if (! $request->expectsJson()) {
            return redirect()->route('owner.utilities.index')->with('success', 'Đã cập nhật chỉ số điện nước.');
        }

        return response()->json(['message' => 'Utility reading updated.', 'data' => $utilityReading->fresh()->load('room:id,name,room_code')]);
    }

    private function validatedReading(Request $request, bool $updating = false): array
    {
        $prefix = $updating ? 'sometimes' : 'required';

        return $request->validate([
            'room_id' => [$prefix, 'integer', 'exists:rooms,id'],
            'month' => [$prefix, 'date'],
            'electricity_old' => ['nullable', 'numeric', 'min:0'],
            'electricity_new' => [$prefix, 'numeric', 'min:0'],
            'electricity_price' => [$prefix, 'numeric', 'min:0'],
            'water_old' => ['nullable', 'numeric', 'min:0'],
            'water_new' => [$prefix, 'numeric', 'min:0'],
            'water_price' => [$prefix, 'numeric', 'min:0'],
        ]);
    }

    private function previousReadings(Room $room, string $month): array
    {
        $previous = $room->utilityReadings()->whereDate('month', '<', $month)->latest('month')->first();
        if ($previous) {
            return [(float) $previous->electricity_new, (float) $previous->water_new];
        }

        $contract = Contract::query()
            ->where('room_id', $room->id)
            ->whereIn('status', ['active', 'expired', 'terminated'])
            ->whereDate('start_date', '<=', $month)
            ->latest('start_date')
            ->first();

        return $contract
            ? [(float) $contract->initial_electricity_reading, (float) $contract->initial_water_reading]
            : [0, 0];
    }

    private function assertNewReadingsAreValid(array $data): void
    {
        abort_if((float) $data['electricity_new'] < (float) $data['electricity_old'], 422, 'The new electricity reading cannot be lower than the old reading.');
        abort_if((float) $data['water_new'] < (float) $data['water_old'], 422, 'The new water reading cannot be lower than the old reading.');
    }

    /**
     * Web: trả redirect về form kèm lỗi khi chỉ số mới < cũ.
     * JSON: abort 422 như cũ. Trả về null khi hợp lệ.
     */
    private function invalidReadingsRedirect(Request $request, array $data)
    {
        $errors = [];
        if ((float) $data['electricity_new'] < (float) $data['electricity_old']) {
            $errors['electricity_new'] = 'Chỉ số điện mới không được nhỏ hơn chỉ số cũ.';
        }
        if ((float) $data['water_new'] < (float) $data['water_old']) {
            $errors['water_new'] = 'Chỉ số nước mới không được nhỏ hơn chỉ số cũ.';
        }
        if ($errors === []) {
            return null;
        }
        if (! $request->expectsJson()) {
            return back()->withErrors($errors)->withInput();
        }
        $this->assertNewReadingsAreValid($data);

        return null;
    }

    private function ownedRoom(User $owner, int $roomId): Room
    {
        $room = Room::query()->with('property')->findOrFail($roomId);
        abort_unless($room->property->owner_id === $owner->id, 404);

        return $room;
    }

    private function owner(Request $request): User
    {
        $user = $request->user();
        if (! $user) {
            if (! $request->expectsJson()) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }

            abort(401, 'Authentication is required.');
        }
        abort_unless($user->role === 'owner', 403, 'Only owners can manage utility readings.');

        return $user;
    }

    /**
     * DEMO MODE: web (trình duyệt) chưa đăng nhập thì dùng owner đầu tiên
     * để test luồng ghi/sửa chỉ số. JSON API vẫn bắt buộc đăng nhập.
     * Muốn bật lại login: thay actingOwner() bằng owner() ở store/update.
     */
    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage utility readings.');

            return $user;
        }
        if (! $request->expectsJson()) {
            // DEMO MODE tách rõ với production: chỉ khi bật flag mới cho guest thao tác.
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
