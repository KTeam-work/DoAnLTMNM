<?php



namespace App\Http\Controllers\Owner;
use App\Http\Controllers\Controller;    
use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;


class OwnerTenantsController extends Controller{
    public function index(Request $request){
        // Production (flag tắt): guest web bị chuyển về login, không lộ dữ liệu demo.
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        $tenants = $owner
            ? User::query()
                ->select(['id', 'name', 'email', 'phone', 'status'])
                ->where('role', 'tenant')
                ->whereHas('rentedContracts', fn ($query) => $query->where('owner_id', $owner->id))
                ->with(['rentedContracts' => fn ($query) => $query
                    ->where('owner_id', $owner->id)
                    ->with(['room:id,name,room_code,property_id', 'members'])])
                ->orderBy('name')
                ->get()
            : collect();

        $contracts = $owner
            ? Contract::query()
                ->where('owner_id', $owner->id)
                ->where('status', '!=', 'terminated')
                ->with(['room.property', 'tenant:id,name', 'members'])
                ->latest('start_date')
                ->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('owner.tenants.manage', compact('tenants', 'contracts'));
        }

        $this->owner($request);

        return response()->json(['data' => $tenants]);
    }

    public function create(Request $request)
    {
        // Trang "Thêm người ở cùng": chọn 1 hợp đồng của chủ trọ rồi nhập thông tin.
        // Production (flag tắt): guest web bị chuyển về login.
        $owner = $request->user() && $request->user()->role === 'owner'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'owner')->orderBy('id')->first()
                : $this->owner($request));

        $contracts = $owner
            ? Contract::query()
                ->where('owner_id', $owner->id)
                ->where('status', '!=', 'terminated')
                ->with(['room.property', 'tenant:id,name', 'members'])
                ->latest('start_date')
                ->get()
            : collect();

        return view('owner.tenants.create', compact('contracts'));
    }

   

public function show(Request $request, int $id)
{
    $owner = $request->user() && $request->user()->role === 'owner'
        ? $request->user()
        : (
            config('app.demo_guest')
                ? User::query()
                    ->where('role', 'owner')
                    ->orderBy('id')
                    ->first()
                : $this->owner($request)
        );

    abort_unless($owner, 403, 'Không tìm thấy chủ trọ.');

    // Lấy người thuê thuộc hợp đồng của chủ trọ này
    $tenant = User::query()
        ->where('role', 'tenant')
        ->whereHas('rentedContracts', function ($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })
        ->with([
            'rentedContracts' => function ($query) use ($owner) {
                $query->where('owner_id', $owner->id)
                    ->with([
                        'room.property',
                        'tenant:id,name,email,phone',
                        'members',
                    ]);
            },
        ])
        ->findOrFail($id);

    // Lấy hợp đồng của người thuê với chủ trọ hiện tại
    $contract = $tenant->rentedContracts
        ->sortByDesc('created_at')
        ->first();

    // Thông tin phòng và nhà trọ
    $room = $contract?->room;
    $property = $room?->property;

    // Danh sách người ở cùng trong hợp đồng
    $members = $contract?->members ?? collect();

    return view('owner.tenants.show', compact(
        'tenant',
        'contract',
        'room',
        'property',
        'members'
    ));
}


public function edit(Request $request, int $id)
{
    $owner = $request->user() && $request->user()->role === 'owner'
        ? $request->user()
        : (config('app.demo_guest')
            ? User::query()
                ->where('role', 'owner')
                ->orderBy('id')
                ->first()
            : $this->owner($request));

    abort_unless($owner, 403, 'Không tìm thấy chủ trọ.');

    $tenant = User::query()
        ->where('role', 'tenant')
        ->whereHas('rentedContracts', function ($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })
        ->findOrFail($id);

    return view('owner.tenants.edit', compact('tenant'));
}


public function update(Request $request, int $id)
{
    $owner = $request->user() && $request->user()->role === 'owner'
        ? $request->user()
        : (config('app.demo_guest')
            ? User::query()
                ->where('role', 'owner')
                ->orderBy('id')
                ->first()
            : $this->owner($request));

    abort_unless($owner, 403, 'Không tìm thấy chủ trọ.');

    $tenant = User::query()
        ->where('role', 'tenant')
        ->whereHas('rentedContracts', function ($query) use ($owner) {
            $query->where('owner_id', $owner->id);
        })
        ->findOrFail($id);

    $minAgeDate = now()->subYears(18)->format('Y-m-d');

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $tenant->id],
        'phone' => ['nullable', 'regex:/^(0[3|5|7|8|9])[0-9]{8}$/'],
        'gender' => ['nullable', 'in:male,female,other'],
        'date_of_birth' => ['nullable', 'date', 'before_or_equal:' . $minAgeDate],
        'identity_card' => ['nullable', 'regex:/^(?:[0-9]{9}|[0-9]{12})$/'],
        'address' => ['nullable', 'string', 'max:255'],
    ], [
        'name.required' => 'Vui lòng nhập họ và tên.',
        'email.required' => 'Vui lòng nhập email.',
        'email.email' => 'Email không đúng định dạng.',
        'email.unique' => 'Email này đã được sử dụng bởi người dùng khác.',
        'phone.regex' => 'Số điện thoại không hợp lệ (phải gồm đúng 10 chữ số, ví dụ: 0901234567, không được là số âm).',
        'date_of_birth.before_or_equal' => 'Người đứng tên thuê phòng phải từ đủ 18 tuổi trở lên (sinh vào hoặc trước ngày ' . now()->subYears(18)->format('d/m/Y') . ').',
        'identity_card.regex' => 'Số CCCD không hợp lệ. Vui lòng nhập đúng 12 chữ số (hoặc CMND 9 chữ số), không được là số âm (-) hay chứa chữ cái.',
    ]);

    $tenant->update($validated);

    return redirect()
        ->route('owner.tenants.show', $tenant->id)
        ->with('success', 'Cập nhật hồ sơ người thuê thành công.');
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
        abort_unless($user->role === 'owner', 403, 'Only owners can access tenants.');

        return $user;
    }
}
