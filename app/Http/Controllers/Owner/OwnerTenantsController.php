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
                ->whereHas('tenantContracts', fn ($query) => $query->where('owner_id', $owner->id))
                ->with(['tenantContracts' => fn ($query) => $query
                    ->where('owner_id', $owner->id)
                    ->with(['room:id,name,room_code,property_id', 'members'])])
                ->orderBy('name')
                ->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('owner.tenants.manage', compact('tenants'));
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
        return view('owner.tenants.show', compact('id'));
    }

    public function edit(Request $request)
    {
        return view('owner.tenants.edit');
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
