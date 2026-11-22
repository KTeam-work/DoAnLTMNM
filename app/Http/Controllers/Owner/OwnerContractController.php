<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\ContractMember;
use App\Models\ContractTermination;
use App\Models\Notification;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use App\Services\ResendService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class OwnerContractController extends Controller{
    public function index(Request $request)
    {
        // Production (flag tắt): guest web bị chuyển về login, không lộ dữ liệu demo.
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        if ($owner) {
            // Tự động đồng bộ phòng đã có hợp đồng (active hoặc draft) sang trạng thái đã thuê
            \App\Models\Room::query()
                ->whereIn('id', Contract::where('owner_id', $owner->id)->whereIn('status', ['active', 'draft'])->pluck('room_id'))
                ->where('status', 'available')
                ->update(['status' => 'rented']);
        }

        $contracts = $owner
            ? Contract::query()
                ->where('owner_id', $owner->id)
                ->with(['room.property', 'tenant:id,name,email,phone,identity_card', 'members', 'termination'])
                ->latest('start_date')
                ->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('owner.contracts.index', compact('contracts'));
        }

        $this->owner($request);

        return response()->json(['data' => $contracts]);
    }

    public function create(Request $request)
    {
        // Chưa đăng nhập: demo mode (flag bật) dùng owner đầu tiên để xem trước form.
        // (Submit vẫn yêu cầu đăng nhập owner ở bước store khi flag tắt.)
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        if (! $owner) {
            return view('owner.contracts.create');
        }

        if ($request->user()) {
            $this->owner($request);
        }

        $rooms = Room::query()
            ->whereHas('property', fn ($query) => $query->where('owner_id', $owner->id))
            ->where('status', 'available')
            ->with('property:id,name')
            ->orderBy('name')
            ->get();

        // Danh sách tất cả lịch hẹn xem phòng của chủ trọ (pending & confirmed)
        $appointments = DB::table('viewing_appointments as va')
            ->join('users as u', 'u.id', '=', 'va.tenant_id')
            ->join('rooms as r', 'r.id', '=', 'va.room_id')
            ->join('properties as p', 'p.id', '=', 'r.property_id')
            ->where('p.owner_id', $owner->id)
            ->whereIn('va.status', ['pending', 'confirmed'])
            ->orderByDesc('va.created_at')
            ->select([
                'va.id',
                'va.tenant_id',
                'u.name as tenant_name',
                'u.phone as tenant_phone',
                'u.email as tenant_email',
                'r.id as room_id',
                'r.name as room_name',
                'r.price as room_price',
                'p.name as property_name',
                'va.appointment_date',
                'va.status as appointment_status',
                'va.status',
                'va.message',
            ])
            ->get();

        // Nhóm lịch hẹn theo từng phòng (độc nhất theo tenant_id)
        $appointmentsByRoom = [];
        foreach ($appointments as $appt) {
            $roomId = (string) $appt->room_id;
            if (! isset($appointmentsByRoom[$roomId])) {
                $appointmentsByRoom[$roomId] = [];
            }
            $exists = false;
            foreach ($appointmentsByRoom[$roomId] as $item) {
                if ((string)$item['tenant_id'] === (string)$appt->tenant_id) {
                    $exists = true;
                    break;
                }
            }
            if (! $exists) {
                $appointmentsByRoom[$roomId][] = [
                    'tenant_id' => $appt->tenant_id,
                    'tenant_name' => $appt->tenant_name,
                    'tenant_phone' => $appt->tenant_phone ?? '',
                    'tenant_email' => $appt->tenant_email ?? '',
                    'appointment_date' => $appt->appointment_date ? \Carbon\Carbon::parse($appt->appointment_date)->format('d/m/Y') : '',
                    'room_id' => $appt->room_id,
                    'room_name' => $appt->room_name,
                    'property_name' => $appt->property_name,
                ];
            }
        }

        $appointedTenantIds = $appointments->pluck('tenant_id')->unique()->filter()->values();

        $tenants = User::query()
            ->where('role', 'tenant')
            ->where('status', 'active')
            ->whereIn('id', $appointedTenantIds)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'phone', 'identity_card']);

        // Gắn thông tin lịch hẹn xem phòng tương ứng của người thuê với chủ trọ này
        $apptByTenant = $appointments->groupBy('tenant_id');
        foreach ($tenants as $t) {
            $t->latest_appointment = $apptByTenant->get($t->id)?->first();
        }

        $services = Service::query()
            ->where('owner_id', $owner->id)
            ->where('status', 'active')
            // Điện và nước đã được tính theo chỉ số, không phải dịch vụ cố định của hợp đồng.
            ->whereNotIn('unit', ['kWh', 'm³'])
            ->orderBy('name')
            ->get(['id', 'name', 'unit', 'price']);

        // Yêu cầu thuê (lịch xem phòng) mà người thuê gửi tới các phòng của chủ trọ.
        // Dùng để chọn nhanh người thuê + phòng khi tạo hợp đồng.
        $requests = $appointments->take(20);

        return view('owner.contracts.create-form', compact('rooms', 'tenants', 'services', 'requests', 'appointmentsByRoom'));
    }

    public function show(Request $request, Contract $contract)
    {
        if (! $request->user() && ! config('app.demo_guest')) {
            $this->owner($request); // chuyển về login, không lộ dữ liệu demo
        }

        if (! $request->user()) {
            $contract->load(['room.property', 'tenant:id,name,email,phone,identity_card', 'members', 'termination', 'services']);

            return view('owner.contracts.detail', compact('contract'));
        }

        $this->ownedContract($request, $contract);
        $contract->load(['room.property', 'tenant:id,name,email,phone,identity_card', 'members', 'termination', 'services']);

        return view('owner.contracts.detail', compact('contract'));
    }

    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);
        $tenantMode = $request->input('tenant_mode', 'existing');

        if ($request->filled('walkin_phone')) {
            $cleanPhone = preg_replace('/[^\d]/', '', (string) $request->input('walkin_phone'));
            $request->merge(['walkin_phone' => $cleanPhone]);
        }

        $rules = [
            'tenant_mode' => ['nullable', 'in:existing,walkin'],
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'rent' => ['required', 'numeric', 'min:0'],
            'deposit' => ['required', 'numeric', 'min:0'],
            'payment_cycle' => ['nullable', 'in:monthly,quarterly'],
            'terms' => ['nullable', 'string'],
            'status' => ['nullable', 'in:draft,active'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => ['integer', 'distinct'],
            'service_quantities' => ['nullable', 'array'],
            'service_quantities.*' => ['nullable', 'numeric', 'min:0.01'],
        ];

        if ($tenantMode === 'walkin') {
            $rules['walkin_name'] = ['required', 'string', 'min:2', 'max:100'];
            $rules['walkin_phone'] = ['required', 'string', 'regex:/^(0[35789])[0-9]{8}$/'];
            $rules['walkin_email'] = ['required', 'email', 'max:150'];
            $rules['walkin_identity_card'] = ['nullable', 'string', 'regex:/^([0-9]{9}|[0-9]{12})$/'];
        } else {
            $rules['tenant_id'] = ['required', 'integer', 'exists:users,id'];
        }

        $messages = [
            'room_id.required' => 'Vui lòng chọn phòng trống cần lập hợp đồng.',
            'room_id.exists' => 'Phòng được chọn không tồn tại trên hệ thống.',
            'start_date.required' => 'Vui lòng chọn ngày bắt đầu hợp đồng.',
            'start_date.date' => 'Ngày bắt đầu hợp đồng không hợp lệ.',
            'end_date.required' => 'Vui lòng chọn ngày kết thúc hợp đồng.',
            'end_date.date' => 'Ngày kết thúc hợp đồng không hợp lệ.',
            'end_date.after' => 'Ngày kết thúc hợp đồng phải sau ngày bắt đầu.',
            'rent.required' => 'Vui lòng nhập số tiền thuê hàng tháng.',
            'rent.numeric' => 'Tiền thuê phòng phải là một số hợp lệ.',
            'rent.min' => 'Tiền thuê phòng không được nhỏ hơn 0 đ.',
            'deposit.required' => 'Vui lòng nhập số tiền đặt cọc phòng.',
            'deposit.numeric' => 'Tiền đặt cọc phòng phải là một số hợp lệ.',
            'deposit.min' => 'Tiền đặt cọc phòng không được nhỏ hơn 0 đ.',
            'walkin_name.required' => 'Vui lòng nhập họ và tên khách thuê.',
            'walkin_name.min' => 'Họ và tên khách thuê phải có ít nhất 2 ký tự.',
            'walkin_name.max' => 'Họ và tên khách thuê không được vượt quá 100 ký tự.',
            'walkin_phone.required' => 'Vui lòng nhập số điện thoại khách thuê.',
            'walkin_phone.regex' => 'Số điện thoại không hợp lệ (phải gồm 10 chữ số, bắt đầu bằng 03, 05, 07, 08 hoặc 09).',
            'walkin_email.required' => 'Vui lòng nhập địa chỉ email của khách thuê.',
            'walkin_email.email' => 'Địa chỉ email của khách thuê không đúng định dạng.',
            'walkin_identity_card.regex' => 'Số CCCD/CMND không hợp lệ (phải gồm đúng 9 hoặc 12 chữ số).',
            'tenant_id.required' => 'Vui lòng chọn người thuê đã đặt lịch hẹn trong danh sách.',
            'tenant_id.exists' => 'Tài khoản người thuê đã chọn không tồn tại.',
        ];

        $data = $request->validate($rules, $messages);

        // Kiểm tra ràng buộc người thuê: Chỉ người đã đặt lịch hẹn với chủ trọ mới được chọn
        if ($tenantMode !== 'walkin') {
            $hasAppointment = DB::table('viewing_appointments as va')
                ->join('rooms as r', 'r.id', '=', 'va.room_id')
                ->join('properties as p', 'p.id', '=', 'r.property_id')
                ->where('p.owner_id', $owner->id)
                ->where('va.tenant_id', $data['tenant_id'])
                ->exists();

            if (! $hasAppointment) {
                return back()
                    ->withErrors(['tenant_id' => 'Người thuê này chưa từng đặt lịch hẹn xem phòng với khu trọ của bạn. Vui lòng chọn khách đã đặt lịch hẹn hoặc chuyển sang Chế độ 2: Khách vãng lai.'])
                    ->withInput();
            }
        }

        $room = Room::query()->with('property')->findOrFail($data['room_id']);
        if ($room->property->owner_id !== $owner->id) {
            return back()->withErrors(['room_id' => 'Phòng này không thuộc quyền quản lý của bạn.'])->withInput();
        }

        if (Contract::query()->where('room_id', $room->id)->where('status', 'active')->exists()) {
            return back()->withErrors(['room_id' => 'Phòng này hiện đã có hợp đồng đang hoạt động.'])->withInput();
        }

        $isNewAccount = false;
        $defaultPassword = '';

        if ($tenantMode === 'walkin') {
            $email = trim(strtolower($data['walkin_email']));
            $phone = trim($data['walkin_phone']);
            $name = trim($data['walkin_name']);
            $idCard = !empty($data['walkin_identity_card']) ? trim($data['walkin_identity_card']) : null;

            // Kiểm tra: Tìm tài khoản dựa CHÍNH XÁC theo Email khách thuê nhập vào
            $tenant = User::query()
                ->where('email', $email)
                ->first();

            if ($tenant) {
                // Đã có tài khoản mang email này: cập nhật thêm phone hoặc CCCD nếu chưa có
                $updates = [];
                if (empty($tenant->phone) && !empty($phone)) {
                    $updates['phone'] = $phone;
                }
                if (empty($tenant->identity_card) && !empty($idCard)) {
                    $updates['identity_card'] = $idCard;
                }
                if (!empty($updates)) {
                    $tenant->update($updates);
                }
                $isNewAccount = false;
                $defaultPassword = $tenant->initial_password ?? 'Mật khẩu tài khoản đã đăng ký của khách';
            } else {
                // Hệ thống tự động tạo mật khẩu ngẫu nhiên cho khách vãng lai (ví dụ: TroOi@4829)
                $generatedPassword = 'TroOi@' . random_int(1000, 9999);
                $defaultPassword = $generatedPassword;

                $tenant = User::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'identity_card' => $idCard,
                    'role' => 'tenant',
                    'status' => 'active',
                    'password' => Hash::make($generatedPassword),
                    'initial_password' => $generatedPassword,
                ]);
                $isNewAccount = true;
            }

            $data['tenant_id'] = $tenant->id;
        } else {
            $tenant = User::query()->findOrFail($data['tenant_id']);
            $defaultPassword = $tenant->initial_password ?? 'Mật khẩu tài khoản đã đăng ký của khách';
        }

        abort_unless($tenant->role === 'tenant' && $tenant->status === 'active', 422, 'Tài khoản người thuê không hợp lệ hoặc đang bị khóa.');
        abort_if(Contract::query()->where('room_id', $room->id)->where('status', 'active')->exists(), 422, 'Phòng này hiện đã có hợp đồng đang hoạt động.');

        $selectedServiceIds = collect($data['service_ids'] ?? [])->map(fn ($id) => (int) $id)->unique()->values();
        $services = Service::query()
            ->where('owner_id', $owner->id)
            ->where('status', 'active')
            ->whereNotIn('unit', ['kWh', 'm³'])
            ->whereIn('id', $selectedServiceIds)
            ->get(['id', 'price']);
        abort_unless($services->count() === $selectedServiceIds->count(), 422, 'Một hoặc nhiều dịch vụ không hợp lệ.');

        $status = $data['status'] ?? 'draft';
        $contractData = Arr::except($data, [
            'tenant_mode',
            'walkin_name',
            'walkin_phone',
            'walkin_email',
            'walkin_identity_card',
            'service_ids',
            'service_quantities',
        ]);

        $contract = DB::transaction(function () use ($contractData, $data, $owner, $room, $services, $status) {
            $contract = Contract::query()->create([
                ...$contractData,
                'contract_code' => $this->contractCode(),
                'owner_id' => $owner->id,
                'payment_cycle' => $data['payment_cycle'] ?? 'monthly',
                'status' => $status,
                'signed_at' => $status === 'active' ? now() : null,
            ]);

            $quantities = $data['service_quantities'] ?? [];
            foreach ($services as $service) {
                DB::table('contract_services')->insert([
                    'contract_id' => $contract->id,
                    'service_id' => $service->id,
                    'quantity' => $quantities[$service->id] ?? 1,
                    'unit_price' => $service->price,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Khi tạo hợp đồng (kể cả bản nháp), phòng đều chuyển sang trạng thái đã thuê để ẩn khỏi web
            $room->update(['status' => 'rented']);

            return $contract;
        });

        $contract->load(['room.property', 'tenant:id,name,email,phone,identity_card']);

        // Gửi email tự động qua Resend API (bọc try-catch an toàn tuyệt đối)
        try {
            if (!empty($tenant->email)) {
                /** @var ResendService $resendService */
                $resendService = app(ResendService::class);
                $resendService->sendWalkInContractEmail(
                    toEmail: $tenant->email,
                    tenantName: $tenant->name,
                    roomName: $room->name,
                    propertyName: $room->property->name ?? 'Khu trọ',
                    contractCode: $contract->contract_code,
                    defaultPassword: $defaultPassword,
                    isNewAccount: $isNewAccount
                );
            }
        } catch (\Throwable $e) {
            Log::error('Lỗi gửi email hợp đồng qua Resend API: ' . $e->getMessage(), [
                'contract_id' => $contract->id,
                'email' => $tenant->email ?? null,
            ]);
        }

        // Bắn thông báo chuông cho người thuê khi có hợp đồng mới
        Notification::notify(
            $tenant->id,
            'Hợp đồng thuê phòng mới #' . $contract->contract_code,
            "Chủ trọ vừa tạo hợp đồng thuê phòng {$room->name} ({$contract->contract_code}) cho bạn. Thời hạn từ " . \Carbon\Carbon::parse($contract->start_date)->format('d/m/Y') . " đến " . \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') . ".",
            'contract',
            'contract',
            $contract->id
        );

        $successMsg = $isNewAccount
            ? "Tạo hợp đồng thành công! Đã tự động tạo tài khoản người thuê và gửi mật khẩu đăng nhập ({$defaultPassword}) tới Gmail: {$tenant->email}."
            : "Tạo hợp đồng thành công! Đã gán hợp đồng và gửi email thông báo tới {$tenant->email}.";

        if (! $request->expectsJson()) {
            return redirect()->route('owner.contracts.index')->with('success', $successMsg);
        }

        return response()->json(['message' => $successMsg, 'data' => $contract], 201);
    }

    public function manageMembers(Request $request, Contract $contract)
    {
        $this->ownedContract($request, $contract);
        if ($contract->status === 'terminated') {
            if (! $request->expectsJson()) {
                return back()->withErrors(['member' => 'Hợp đồng đã thanh lý, không thể thay đổi người ở cùng.']);
            }
            abort(422, 'Cannot change members on a terminated contract.');
        }
        $action = $request->validate(['action' => ['nullable', 'in:add,remove']])['action'] ?? 'add';

        if ($action === 'remove') {
            $data = $request->validate(['member_id' => ['required', 'integer']]);
            $member = $contract->members()->findOrFail($data['member_id']);
            $member->delete();

            if (! $request->expectsJson()) {
                return redirect()->route('owner.contracts.show', $contract)->with('success', 'Đã xóa người ở cùng.');
            }

            return response()->json(['message' => 'Contract member removed.']);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'identity_card' => ['required', 'regex:/^(?:[0-9]{9}|[0-9]{12})$/'],
            'phone' => ['nullable', 'regex:/^0[0-9]{9}$/'],
            'relationship' => ['nullable', 'string', 'max:50'],
        ], [
            'name.required' => 'Vui lòng nhập họ và tên người ở cùng.',
            'identity_card.required' => 'Vui lòng nhập số CCCD.',
            'identity_card.regex' => 'Số CCCD phải gồm đúng 12 chữ số (hoặc CMND 9 chữ số), không được là số âm hay chứa chữ cái.',
            'phone.regex' => 'Số điện thoại phải gồm đúng 10 chữ số, không được là số âm và phải bắt đầu bằng số 0.',
        ]);
        $room = $contract->room;
        $memberError = null;
        if (1 + $contract->members()->count() >= $room->max_people) {
            $memberError = 'Phòng đã đủ số người tối đa (' . $room->max_people . ' người).';
        } elseif ($contract->members()->where('identity_card', $data['identity_card'])->exists()) {
            $memberError = 'CCCD này đã có trong hợp đồng.';
        }
        if ($memberError) {
            if (! $request->expectsJson()) {
                return back()->withErrors(['member' => $memberError])->withInput();
            }
            abort(422, $memberError);
        }

        $member = $contract->members()->create($data);

        if (! $request->expectsJson()) {
            return back()->with('success', 'Đã thêm người ở cùng thành công.');
        }

        return response()->json(['message' => 'Contract member added.', 'data' => $member], 201);
    }

    public function setInitialUtilities(Request $request, Contract $contract)
    {
        $this->ownedContract($request, $contract);
        abort_if($contract->status === 'terminated', 422, 'Cannot change readings on a terminated contract.');
        $data = $request->validate([
            'initial_electricity_reading' => ['required', 'numeric', 'min:0'],
            'initial_water_reading' => ['required', 'numeric', 'min:0'],
        ]);
        $contract->update($data);

        return response()->json(['message' => 'Initial utility readings updated.', 'data' => $contract->fresh()]);
    }

    public function activate(Request $request, Contract $contract)
    {
        $this->ownedContract($request, $contract);
        abort_unless($contract->status === 'draft', 422, 'Hợp đồng này không ở trạng thái nháp.');

        DB::transaction(function () use ($contract) {
            $contract->update([
                'status' => 'active',
                'signed_at' => now(),
            ]);
            $contract->room->update(['status' => 'rented']);
        });

        // Bắn thông báo chuông cho người thuê khi hợp đồng được kích hoạt
        if ($contract->tenant_id) {
            $roomName = $contract->room?->name ?? 'phòng trọ';
            Notification::notify(
                $contract->tenant_id,
                'Hợp đồng thuê phòng đã được kích hoạt',
                "Hợp đồng thuê {$roomName} (#{$contract->contract_code}) của bạn đã được kích hoạt và bắt đầu có hiệu lực.",
                'contract',
                'contract',
                $contract->id
            );
        }

        if (! $request->expectsJson()) {
            return redirect()
                ->route('owner.contracts.show', $contract)
                ->with('success', 'Đã kích hoạt hợp đồng thành công! Phòng đã chuyển sang trạng thái đang thuê.');
        }

        return response()->json(['message' => 'Hợp đồng đã được kích hoạt thành công.', 'data' => $contract->fresh()]);
    }

    public function terminate(Request $request, Contract $contract)
    {
        $owner = $this->actingOwner($request);
        abort_unless($contract->owner_id === $owner->id, 404);

        $terminationType = $request->input('termination_type', 'normal');

        $isExpiredOrDue = $contract->status === 'expired' || now()->startOfDay()->gte(\Carbon\Carbon::parse($contract->end_date)->startOfDay());
        if ($terminationType === 'normal' && ! $isExpiredOrDue) {
            $msg = 'Hợp đồng chưa đến ngày kết thúc (' . \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') . '). Nếu người thuê muốn chuyển đi trước hạn, vui lòng chọn hình thức "Bỏ phòng / Chấm dứt trước hạn (Không hoàn cọc)".';
            if (! $request->expectsJson()) {
                return back()->withErrors(['terminate' => $msg])->withInput();
            }
            abort(422, $msg);
        }

        $data = $request->validate([
            'termination_type' => ['nullable', 'in:normal,early'],
            'deduction_amount' => ['nullable', 'numeric', 'min:0'],
            'deduction_reason' => ['nullable', 'string'],
            'return_date' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ], [
            'return_date.required' => 'Vui lòng chọn ngày kết thúc / trả phòng thực tế.',
            'deduction_amount.numeric' => 'Số tiền khấu trừ phải là một số hợp lệ.',
            'deduction_amount.min' => 'Số tiền khấu trừ không được nhỏ hơn 0.',
        ]);

        $termination = DB::transaction(function () use ($contract, $data, $terminationType) {
            $contract = Contract::query()->lockForUpdate()->with('room')->findOrFail($contract->id);
            abort_if($contract->status === 'terminated' || $contract->termination()->exists(), 422, 'Hợp đồng này đã được thanh lý trước đó.');

            $deposit = (float) $contract->deposit;

            // Nếu là trường hợp 1: Chấm dứt trước hạn / Khách bỏ phòng -> Không hoàn tiền cọc (khấu trừ toàn bộ)
            if ($terminationType === 'early') {
                $deduction = $deposit;
                $reasonPrefix = 'Khách đơn phương chấm dứt hợp đồng trước hạn (không hoàn cọc)';
                $deductionReason = !empty($data['deduction_reason'])
                    ? $reasonPrefix . ' - ' . $data['deduction_reason']
                    : $reasonPrefix;
                $refund = 0;
            } else {
                // Trường hợp 2: Hết hạn hợp đồng / Đúng hạn -> Khấu trừ theo thực tế và hoàn phần còn lại
                $deduction = (float) ($data['deduction_amount'] ?? 0);
                abort_if($deduction > $deposit, 422, 'Số tiền khấu trừ không thể vượt quá tiền đặt cọc.');
                $deductionReason = $data['deduction_reason'] ?? 'Thanh lý hợp đồng theo thỏa thuận';
                $refund = round(max(0, $deposit - $deduction), 2);
            }

            $termination = ContractTermination::query()->create([
                'contract_id' => $contract->id,
                'deposit_amount' => $deposit,
                'deduction_amount' => $deduction,
                'deduction_reason' => $deductionReason,
                'refund_amount' => $refund,
                'return_date' => $data['return_date'],
                'note' => $data['note'] ?? null,
            ]);

            $contract->update(['status' => 'terminated', 'terminated_at' => now()]);
            if ($contract->room) {
                $contract->room->update(['status' => 'available']);
            }

            return $termination;
        });

        // Bắn thông báo chuông cho người thuê khi thanh lý / chấm dứt hợp đồng
        if ($contract->tenant_id) {
            $roomName = $contract->room?->name ?? 'phòng trọ';
            $msgNotice = $terminationType === 'early'
                ? "Hợp đồng thuê {$roomName} (#{$contract->contract_code}) đã được thanh lý do chấm dứt trước hạn vào ngày " . \Carbon\Carbon::parse($data['return_date'])->format('d/m/Y') . ". Tiền cọc được giữ lại theo điều khoản hợp đồng."
                : "Hợp đồng thuê {$roomName} (#{$contract->contract_code}) đã được thanh lý vào ngày " . \Carbon\Carbon::parse($data['return_date'])->format('d/m/Y') . ". Tiền hoàn cọc: " . number_format($termination->refund_amount) . " đ.";

            Notification::notify(
                $contract->tenant_id,
                'Hợp đồng thuê phòng đã được thanh lý',
                $msgNotice,
                'contract',
                'contract',
                $contract->id
            );
        }

        if (! $request->expectsJson()) {
            return redirect()
                ->route('owner.contracts.show', $contract)
                ->with('success', 'Thanh lý hợp đồng thành công! Trạng thái hợp đồng đã chuyển sang "Đã thanh lý", toàn bộ hồ sơ và biên bản thanh lý được lưu trữ đầy đủ để theo dõi lịch sử. Phòng ' . ($contract->room?->name ?? '') . ' đã chuyển về trạng thái Còn trống.');
        }

        return response()->json(['message' => 'Contract terminated and deposit settled.', 'data' => $termination]);
    }

    /**
     * Gia hạn thời hạn hợp đồng thuê phòng.
     */
    public function extend(Request $request, Contract $contract)
    {
        $owner = $this->actingOwner($request);
        abort_unless($contract->owner_id === $owner->id, 404);
        abort_if($contract->status === 'terminated', 422, 'Không thể gia hạn hợp đồng đã thanh lý.');

        $isExpiredOrDue = $contract->status === 'expired' || now()->startOfDay()->gte(\Carbon\Carbon::parse($contract->end_date)->startOfDay());
        if (! $isExpiredOrDue) {
            $msg = 'Hợp đồng vẫn đang còn thời hạn, chỉ có thể gia hạn khi hợp đồng đã hết hiệu lực hoặc tới ngày kết thúc.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['extend' => $msg])->withInput();
            }
            abort(422, $msg);
        }

        // Chuẩn hóa giá thuê mới nếu có nhập
        if ($request->filled('new_rent')) {
            $cleanRent = preg_replace('/[^\d]/', '', (string) $request->input('new_rent'));
            $request->merge(['new_rent' => $cleanRent !== '' ? $cleanRent : null]);
        }

        $data = $request->validate([
            'new_end_date' => ['required', 'date', 'after:' . $contract->end_date],
            'new_rent' => ['nullable', 'numeric', 'min:0'],
            'note' => ['nullable', 'string'],
        ], [
            'new_end_date.required' => 'Vui lòng chọn ngày kết thúc mới cho hợp đồng.',
            'new_end_date.after' => 'Ngày kết thúc mới phải sau ngày kết thúc hiện tại (' . \Carbon\Carbon::parse($contract->end_date)->format('d/m/Y') . ').',
            'new_rent.numeric' => 'Giá thuê mới phải là một số hợp lệ.',
            'new_rent.min' => 'Giá thuê không được nhỏ hơn 0.',
        ]);

        $updates = [
            'end_date' => $data['new_end_date'],
            'status' => 'active',
        ];

        if (!empty($data['new_rent'])) {
            $updates['rent'] = (float) $data['new_rent'];
        }

        $contract->update($updates);

        if ($contract->room) {
            $roomUpdates = ['status' => 'rented'];
            if (!empty($data['new_rent'])) {
                $roomUpdates['price'] = (float) $data['new_rent'];
            }
            $contract->room->update($roomUpdates);
        }

        // Bắn thông báo chuông cho người thuê
        if ($contract->tenant_id) {
            $roomName = $contract->room?->name ?? 'phòng trọ';
            $formattedDate = \Carbon\Carbon::parse($data['new_end_date'])->format('d/m/Y');
            Notification::notify(
                $contract->tenant_id,
                'Hợp đồng thuê phòng đã được gia hạn',
                "Hợp đồng thuê {$roomName} (#{$contract->contract_code}) đã được gia hạn thành công đến ngày {$formattedDate}." . (!empty($data['new_rent']) ? " Giá thuê: " . number_format((float)$data['new_rent']) . " đ/tháng." : ""),
                'contract',
                'contract',
                $contract->id
            );
        }

        if (! $request->expectsJson()) {
            return redirect()
                ->route('owner.contracts.show', $contract)
                ->with('success', 'Gia hạn hợp đồng thành công đến ngày ' . \Carbon\Carbon::parse($data['new_end_date'])->format('d/m/Y') . '.');
        }

        return response()->json(['message' => 'Contract extended successfully.', 'data' => $contract]);
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
        abort_unless($user->role === 'owner', 403, 'Only owners can manage contracts.');

        return $user;
    }

    /**
     * DEMO MODE: web (trình duyệt) chưa đăng nhập thì dùng owner đầu tiên
     * để test luồng tạo HĐ/thêm người ở cùng. JSON API vẫn bắt buộc đăng nhập.
     * Muốn bật lại login: thay actingOwner() bằng owner() ở store/terminate/ownedContract.
     */
    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage contracts.');

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

    private function ownedContract(Request $request, Contract $contract): Contract
    {
        $owner = $this->actingOwner($request);
        abort_unless($contract->owner_id === $owner->id, 404);

        return $contract;
    }

    private function contractCode(): string
    {
        do {
            $code = 'CTR-'.now()->format('Ymd').'-'.Str::upper(Str::random(6));
        } while (Contract::query()->where('contract_code', $code)->exists());

        return $code;
    }
}
