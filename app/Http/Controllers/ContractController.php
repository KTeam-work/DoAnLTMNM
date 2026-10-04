<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        // Production (flag tắt): guest web bị chuyển về login, không lộ dữ liệu demo.
        $tenant = $request->user() && $request->user()->role === 'tenant'
            ? $request->user()
            : (config('app.demo_guest')
                ? User::query()->where('role', 'tenant')->orderBy('id')->first()
                : $this->tenant($request));

        $contracts = $tenant
            ? Contract::query()
                ->where('tenant_id', $tenant->id)
                ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination'])
                ->latest('start_date')
                ->get()
            : collect();

        if (! $request->expectsJson()) {
            return view('tenant.contracts.index', compact('contracts', 'tenant'));
        }

        $this->tenant($request);

        return response()->json(['data' => $contracts]);
    }

    public function show(Request $request, int $id)
    {
        // Tenant đã đăng nhập chỉ xem được hợp đồng của chính mình (web + JSON).
        if ($request->user() && $request->user()->role === 'tenant') {
            $contract = Contract::query()
                ->where('tenant_id', $request->user()->id)
                ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination'])
                ->find($id);
            abort_if(! $contract, 404);

            if (! $request->expectsJson()) {
                return view('tenant.contracts.show', compact('contract'));
            }

            return response()->json(['data' => $contract]);
        }

        if (! $request->expectsJson()) {
            // Demo mode: guest chỉ xem theo id khi bật flag, còn lại về login.
            if (! config('app.demo_guest')) {
                $this->tenant($request);
            }
            $contract = Contract::query()
                ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination'])
                ->findOrFail($id);

            return view('tenant.contracts.show', compact('contract'));
        }

        $tenant = $this->tenant($request);

        $contract = Contract::query()
            ->where('tenant_id', $tenant->id)
            ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination'])
            ->findOrFail($id);

        return response()->json(['data' => $contract]);
    }

    private function tenant(Request $request): User
    {
        $user = $request->user();
        if (! $user) {
            if (! $request->expectsJson()) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }

            abort(401, 'Authentication is required.');
        }
        abort_unless($user->role === 'tenant', 403, 'Only tenants can access contracts.');

        return $user;
    }
}
