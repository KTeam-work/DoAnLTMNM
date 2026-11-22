<?php

namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use App\Models\UtilityReading;
use App\Services\GeminiService;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $selectedPropertyId = $request->input('property_id');
        $selectedRoomId = $request->input('room_id');

        // Xác định tháng lọc:
        // - Nếu user truyền ?all=1 hoặc form gửi month rỗng: hiển thị tất cả ($month = null)
        // - Nếu user chọn tháng cụ thể: lọc theo tháng đó ($month = Y-m)
        // - Nếu truy cập lần đầu không có tham số:
        //   + Nếu tháng hiện tại có bản ghi: lọc theo tháng hiện tại
        //   + Nếu tháng hiện tại chưa có bản ghi: để null (hiển thị tất cả) để chủ trọ thấy ngay dữ liệu đã tạo
        if ($request->boolean('all') || ($request->has('month') && ! $request->filled('month'))) {
            $month = null;
        } elseif ($request->filled('month')) {
            $month = $request->input('month');
        } else {
            $currentMonthStr = now()->format('Y-m');
            $hasCurrentMonth = $owner && UtilityReading::query()
                ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
                ->whereDate('month', Carbon::parse($currentMonthStr)->startOfMonth())
                ->exists();

            $month = $hasCurrentMonth ? $currentMonthStr : null;
        }

        $readings = $owner
            ? UtilityReading::query()
                ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
                ->when($selectedPropertyId, fn ($query) => $query->whereHas('room', fn ($rq) => $rq->where('property_id', $selectedPropertyId)))
                ->when($selectedRoomId, fn ($query) => $query->where('room_id', $selectedRoomId))
                ->when($month, fn ($query) => $query->whereDate('month', Carbon::parse($month)->startOfMonth()))
                ->with('room:id,name,room_code,property_id')
                ->orderByDesc('month')
                ->orderBy('room_id')
                ->get()
            : collect();

        $rooms = $owner
            ? Room::query()
                ->whereHas('property', fn ($query) => $query->where('owner_id', $owner->id))
                ->when($selectedPropertyId, fn ($query) => $query->where('property_id', $selectedPropertyId))
                ->with('property:id,name')
                ->orderBy('name')
                ->get()
            : collect();

        $properties = $owner
            ? \App\Models\Property::query()->where('owner_id', $owner->id)->orderBy('name')->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('owner.utilities.index', compact('readings', 'rooms', 'properties', 'month', 'selectedRoomId', 'selectedPropertyId'));
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

    public function export(Request $request)
    {
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        if (! $owner) {
            abort(403);
        }

        $selectedRoomId = $request->input('room_id');
        $month = $request->filled('month') ? $request->input('month') : null;

        $readings = UtilityReading::query()
            ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
            ->when($selectedRoomId, fn ($query) => $query->where('room_id', $selectedRoomId))
            ->when($month, fn ($query) => $query->whereDate('month', Carbon::parse($month)->startOfMonth()))
            ->with(['room.property'])
            ->orderByDesc('month')
            ->orderBy('room_id')
            ->get();

        $filename = 'chi_so_dien_nuoc_' . ($month ? str_replace('-', '_', $month) : 'tat_ca_' . date('Ymd_His')) . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($readings) {
            $handle = fopen('php://output', 'w');
            // Ghi UTF-8 BOM để Excel đọc tiếng Việt có dấu chuẩn xác 100%
            fputs($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'STT',
                'Phòng',
                'Mã phòng',
                'Nhà trọ',
                'Kỳ (Tháng)',
                'Điện cũ (kWh)',
                'Điện mới (kWh)',
                'Tiêu thụ điện (kWh)',
                'Đơn giá điện (đ/kWh)',
                'Tiền điện (đ)',
                'Nước cũ (m3)',
                'Nước mới (m3)',
                'Tiêu thụ nước (m3)',
                'Đơn giá nước (đ/m3)',
                'Tiền nước (đ)',
                'Tổng tiền (đ)',
                'Trạng thái',
                'Ngày ghi',
            ]);

            $index = 1;
            foreach ($readings as $r) {
                $eUse = (float)$r->electricity_new - (float)$r->electricity_old;
                $wUse = (float)$r->water_new - (float)$r->water_old;
                $eMoney = $eUse * (float)$r->electricity_price;
                $wMoney = $wUse * (float)$r->water_price;
                $total = $eMoney + $wMoney;

                fputcsv($handle, [
                    $index++,
                    $r->room->name ?? '',
                    $r->room->room_code ?? '',
                    $r->room->property->name ?? '',
                    $r->month ? Carbon::parse($r->month)->format('m/Y') : '',
                    $r->electricity_old,
                    $r->electricity_new,
                    $eUse,
                    $r->electricity_price,
                    $eMoney,
                    $r->water_old,
                    $r->water_new,
                    $wUse,
                    $r->water_price,
                    $wMoney,
                    $total,
                    'Đã ghi',
                    $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function create(Request $request)
    {
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        // Chế độ sửa khi có ?id=: load bản ghi để prefill form.
        $reading = null;
        if ($request->filled('id') && $owner) {
            $reading = UtilityReading::query()
                ->whereHas('room.property', fn ($query) => $query->where('owner_id', $owner->id))
                ->with([
                    'room.property:id,name,owner_id',
                    'room.contracts' => fn ($query) => $query->where('status', 'active')->with('tenant:id,name'),
                ])
                ->find($request->input('id'));
        }

        $selectedPropertyId = $request->input('property_id');
        $prefillMonth = $request->input('month', $reading ? Carbon::parse($reading->month)->format('Y-m') : now()->format('Y-m'));
        $targetMonthDate = Carbon::parse($prefillMonth)->startOfMonth()->toDateString();

        $properties = $owner
            ? \App\Models\Property::query()->where('owner_id', $owner->id)->orderBy('name')->get()
            : collect();

        // Chỉ lấy những phòng ĐANG CÓ HỢP ĐỒNG HIỆU LỰC (đang được thuê)
        // VÀ LOẠI BỎ các phòng đã có chỉ số trong tháng này (nếu không phải đang sửa bản ghi đó)
        $rooms = $owner
            ? Room::query()
                ->whereHas('property', fn ($query) => $query->where('owner_id', $owner->id))
                ->whereHas('contracts', fn ($query) => $query->where('status', 'active'))
                ->when($selectedPropertyId && ! $reading, fn ($q) => $q->where('property_id', $selectedPropertyId))
                ->when(! $reading, function ($q) use ($targetMonthDate) {
                    $q->whereDoesntHave('utilityReadings', fn ($uq) => $uq->whereDate('month', $targetMonthDate));
                })
                ->with([
                    'property:id,name,owner_id',
                    'contracts' => fn ($query) => $query->where('status', 'active')->with('tenant:id,name'),
                ])
                ->orderBy('name')
                ->get()
            : collect();

        // Nếu đang sửa bản ghi của phòng mà phòng chưa có trong danh sách thì vẫn đưa vào để hiển thị
        if ($reading && $reading->room && ! $rooms->contains('id', $reading->room_id)) {
            $rooms->push($reading->room);
        }

        $prefillRoomId = $request->input('room_id', $reading?->room_id ?? $rooms->first()?->id);

        $initialPrevious = null;
        if (! $reading && $prefillRoomId && $owner) {
            $room = $rooms->firstWhere('id', (int) $prefillRoomId);
            if ($room) {
                $initialPrevious = $this->getPreviousReadingInfo($room, $prefillMonth);
            }
        }

        return view('owner.utilities.create', compact('rooms', 'properties', 'reading', 'prefillRoomId', 'prefillMonth', 'selectedPropertyId', 'initialPrevious'));
    }

    /**
     * AJAX: Lấy chỉ số điện nước kỳ trước liền kề (hoặc từ hợp đồng) khi chọn phòng/tháng.
     */
    public function getPreviousReading(Request $request)
    {
        $owner = $this->actingOwner($request);
        $roomId = $request->integer('room_id');
        $month = $request->input('month', now()->format('Y-m'));

        if (! $roomId) {
            return response()->json(['error' => 'Vui lòng chọn phòng.'], 400);
        }

        $room = $this->ownedRoom($owner, $roomId);
        $info = $this->getPreviousReadingInfo($room, $month);

        return response()->json($info);
    }

    /**
     * AJAX: Nhận diện chỉ số từ ảnh chụp công tơ điện / đồng hồ nước.
     */
    public function scanMeter(Request $request, GeminiService $gemini)
    {
        $this->actingOwner($request);

        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:10240'],
            'meter_type' => ['nullable', 'string', 'in:electricity,water,auto'],
        ], [
            'image.required' => 'Vui lòng chọn ảnh chụp đồng hồ đo.',
            'image.image' => 'File tải lên phải là hình ảnh hợp lệ.',
            'image.mimes' => 'Hệ thống hỗ trợ các định dạng: JPEG, PNG, JPG, WEBP.',
            'image.max' => 'Dung lượng ảnh tối đa là 10MB.',
        ]);

        $file = $request->file('image');
        $meterType = $request->input('meter_type', 'auto');

        try {
            $result = $gemini->scanMeterReading(
                $file->getRealPath(),
                $file->getMimeType(),
                $meterType === 'auto' ? null : $meterType
            );

            return response()->json($result);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể nhận diện số từ ảnh chụp: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);
        $data = $this->validatedReading($request);
        $room = $this->ownedRoom($owner, $data['room_id']);

        // Ràng buộc: Phòng phải có hợp đồng đang hoạt động (đang được thuê)
        $hasActiveContract = Contract::query()
            ->where('room_id', $room->id)
            ->where('status', 'active')
            ->exists();

        if (! $hasActiveContract) {
            $message = 'Phòng ' . $room->name . ' hiện chưa có người thuê (chưa có hợp đồng hiệu lực). Bạn không thể ghi chỉ số điện nước cho phòng trống.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['room_id' => $message])->withInput();
            }
            abort(422, $message);
        }

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
            $monthParam = Carbon::parse($data['month'])->format('Y-m');
            return redirect()->route('owner.utilities.index', ['month' => $monthParam])
                ->with('success', 'Đã ghi chỉ số điện nước tháng ' . Carbon::parse($data['month'])->format('m/Y') . '.');
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
            $monthParam = isset($data['month'])
                ? Carbon::parse($data['month'])->format('Y-m')
                : Carbon::parse($utilityReading->month)->format('Y-m');

            return redirect()->route('owner.utilities.index', ['month' => $monthParam])
                ->with('success', 'Đã cập nhật chỉ số điện nước tháng ' . Carbon::parse($monthParam)->format('m/Y') . '.');
        }

        return response()->json(['message' => 'Utility reading updated.', 'data' => $utilityReading->fresh()->load('room:id,name,room_code')]);
    }

    public function destroy(Request $request, UtilityReading $utilityReading)
    {
        $owner = $this->actingOwner($request);
        $this->ownedRoom($owner, $utilityReading->room_id);

        $monthParam = Carbon::parse($utilityReading->month)->format('Y-m');

        // Không cho phép xóa chỉ số đã được gắn với hóa đơn còn hiệu lực (chưa hủy)
        if ($utilityReading->invoice()->where('status', '!=', 'cancelled')->exists()) {
            $msg = 'Không thể xóa chỉ số này vì đã được dùng để lập hóa đơn tiền phòng.';
            if (! $request->expectsJson()) {
                return redirect()->route('owner.utilities.index', ['month' => $monthParam])
                    ->with('error', $msg);
            }
            abort(422, $msg);
        }

        $utilityReading->delete();

        if (! $request->expectsJson()) {
            return redirect()->route('owner.utilities.index', ['month' => $monthParam])
                ->with('success', 'Đã xóa bản ghi chỉ số điện nước.');
        }

        return response()->json(['message' => 'Utility reading deleted.']);
    }

    private function validatedReading(Request $request, bool $updating = false): array
    {
        $prefix = $updating ? 'sometimes' : 'required';

        $validated = $request->validate([
            'room_id' => [$prefix, 'integer', 'exists:rooms,id'],
            'month' => [$prefix, 'date'],
            'electricity_old' => ['nullable', 'numeric', 'min:0'],
            'electricity_new' => [$prefix, 'numeric', 'min:0'],
            'electricity_price' => [$prefix, 'numeric', 'min:0'],
            'water_old' => ['nullable', 'numeric', 'min:0'],
            'water_new' => [$prefix, 'numeric', 'min:0'],
            'water_price' => [$prefix, 'numeric', 'min:0'],
        ]);

        if (isset($validated['electricity_price']) && (float) $validated['electricity_price'] > 0 && (float) $validated['electricity_price'] < 1000) {
            $validated['electricity_price'] = (float) $validated['electricity_price'] * 1000;
        }
        if (isset($validated['water_price']) && (float) $validated['water_price'] > 0 && (float) $validated['water_price'] < 1000) {
            $validated['water_price'] = (float) $validated['water_price'] * 1000;
        }

        return $validated;
    }

    /**
     * Truy xuất thông tin chỉ số cũ và đơn giá cho một phòng tại tháng mục tiêu:
     * 1. Tìm bản ghi chỉ số gần nhất trước tháng mục tiêu -> lấy electricity_new, water_new
     * 2. Nếu chưa có -> tìm chỉ số ban đầu từ hợp đồng thuê đang hiệu lực
     * 3. Mặc định là 0
     */
    public function getPreviousReadingInfo(Room $room, string $month): array
    {
        $targetMonth = Carbon::parse($month)->startOfMonth()->toDateString();

        if (! $room->relationLoaded('property')) {
            $room->load('property');
        }

        // Kiểm tra xem tháng này phòng đã chốt chỉ số chưa
        $existing = UtilityReading::query()
            ->where('room_id', $room->id)
            ->whereDate('month', $targetMonth)
            ->first();

        // 1. Xác định đơn giá điện & nước cấu hình riêng cho nhà trọ / chủ trọ
        $ownerId = $room->property?->owner_id;
        $defaultElectricPrice = 3500.0;
        $defaultWaterPrice = 20000.0;

        if ($ownerId) {
            // Giá điện từ dịch vụ áp dụng cho nhà trọ này nếu có
            $propElectric = DB::table('property_services')
                ->join('services', 'property_services.service_id', '=', 'services.id')
                ->where('property_services.property_id', $room->property_id)
                ->where('property_services.status', 'active')
                ->where(function ($q) {
                    $q->where('services.unit', 'kWh')
                      ->orWhere('services.name', 'like', '%điện%');
                })
                ->value('property_services.price');

            if ($propElectric && (float) $propElectric > 0) {
                $defaultElectricPrice = (float) $propElectric;
            } else {
                $eService = Service::query()
                    ->where('owner_id', $ownerId)
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->where('unit', 'kWh')
                          ->orWhere('name', 'like', '%điện%');
                    })
                    ->first();
                if ($eService && (float) $eService->price > 0) {
                    $defaultElectricPrice = (float) $eService->price;
                }
            }
            if ($defaultElectricPrice > 0 && $defaultElectricPrice < 1000) {
                $defaultElectricPrice *= 1000;
            }

            // Giá nước từ dịch vụ áp dụng cho nhà trọ này nếu có
            $propWater = DB::table('property_services')
                ->join('services', 'property_services.service_id', '=', 'services.id')
                ->where('property_services.property_id', $room->property_id)
                ->where('property_services.status', 'active')
                ->where(function ($q) {
                    $q->where('services.unit', 'm³')
                      ->orWhere('services.name', 'like', '%nước%');
                })
                ->value('property_services.price');

            if ($propWater && (float) $propWater > 0) {
                $defaultWaterPrice = (float) $propWater;
            } else {
                $wService = Service::query()
                    ->where('owner_id', $ownerId)
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->where('unit', 'm³')
                          ->orWhere('name', 'like', '%nước%');
                    })
                    ->first();
                if ($wService && (float) $wService->price > 0) {
                    $defaultWaterPrice = (float) $wService->price;
                }
            }
            if ($defaultWaterPrice > 0 && $defaultWaterPrice < 1000) {
                $defaultWaterPrice *= 1000;
            }
        }

        // 2. Tìm kỳ trước gần nhất của chính phòng này
        $previous = $room->utilityReadings()
            ->whereDate('month', '<', $targetMonth)
            ->latest('month')
            ->first();

        if ($previous) {
            $prevEPrice = (float) ($previous->electricity_price ?: $defaultElectricPrice);
            if ($prevEPrice > 0 && $prevEPrice < 1000) {
                $prevEPrice *= 1000;
            }
            $prevWPrice = (float) ($previous->water_price ?: $defaultWaterPrice);
            if ($prevWPrice > 0 && $prevWPrice < 1000) {
                $prevWPrice *= 1000;
            }

            return [
                'electricity_old' => (float) $previous->electricity_new,
                'water_old' => (float) $previous->water_new,
                'electricity_price' => $prevEPrice,
                'water_price' => $prevWPrice,
                'source' => 'Bản ghi kỳ ' . Carbon::parse($previous->month)->format('m/Y'),
                'source_type' => 'previous_reading',
                'already_exists' => (bool) $existing,
                'existing_id' => $existing?->id,
                'existing_month' => $existing ? Carbon::parse($existing->month)->format('m/Y') : null,
            ];
        }

        // 3. Tìm từ hợp đồng thuê hiệu lực
        $contract = Contract::query()
            ->where('room_id', $room->id)
            ->whereIn('status', ['active', 'expired', 'terminated'])
            ->whereDate('start_date', '<=', $targetMonth)
            ->latest('start_date')
            ->first();

        if ($contract) {
            return [
                'electricity_old' => (float) $contract->initial_electricity_reading,
                'water_old' => (float) $contract->initial_water_reading,
                'electricity_price' => $defaultElectricPrice,
                'water_price' => $defaultWaterPrice,
                'source' => 'Chỉ số đầu hợp đồng (' . Carbon::parse($contract->start_date)->format('d/m/Y') . ')',
                'source_type' => 'contract',
                'already_exists' => (bool) $existing,
                'existing_id' => $existing?->id,
                'existing_month' => $existing ? Carbon::parse($existing->month)->format('m/Y') : null,
            ];
        }

        // 4. Mặc định
        return [
            'electricity_old' => 0.0,
            'water_old' => 0.0,
            'electricity_price' => $defaultElectricPrice,
            'water_price' => $defaultWaterPrice,
            'source' => 'Đơn giá cấu hình theo nhà trọ',
            'source_type' => 'default',
            'already_exists' => (bool) $existing,
            'existing_id' => $existing?->id,
            'existing_month' => $existing ? Carbon::parse($existing->month)->format('m/Y') : null,
        ];
    }

    private function previousReadings(Room $room, string $month): array
    {
        $info = $this->getPreviousReadingInfo($room, $month);

        return [$info['electricity_old'], $info['water_old']];
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
