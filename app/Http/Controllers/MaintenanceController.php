<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\MaintenanceRequest;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $query = MaintenanceRequest::query()
            ->where('tenant_id', $tenant->id)
            ->with(['room:id,name', 'contract:id,contract_code'])
            ->latest();

        if ($request->filled('status') && in_array($request->input('status'), ['pending', 'processing', 'completed', 'rejected'], true)) {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->get();
        $statusFilter = $request->input('status', 'all');

        if (! $request->expectsJson()) {
            return view('tenant.maintenance.index', compact('requests', 'tenant', 'statusFilter'));
        }

        return response()->json(['data' => $requests]);
    }

    public function create(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $contracts = Contract::query()
            ->where('tenant_id', $tenant->id)
            ->whereIn('status', ['active', 'draft'])
            ->with('room:id,name')
            ->latest('start_date')
            ->get();

        $prefillContractId = $request->input('contract_id');
        if ($prefillContractId && ! $contracts->contains('id', (int) $prefillContractId)) {
            $prefillContractId = null;
        }

        return view('tenant.maintenance.create', compact('contracts', 'tenant', 'prefillContractId'));
    }

    public function store(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $data = $request->validate([
            'contract_id' => ['required', 'integer', 'exists:contracts,id'],
            'category' => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
            'description' => ['required', 'string'],
            'priority' => ['required', 'in:low,medium,high,urgent'],
            'image' => ['nullable', 'image', 'max:10240'],
        ]);

        // Chống gửi yêu cầu cho hợp đồng không thuộc tenant.
        $contract = Contract::query()->where('tenant_id', $tenant->id)->find($data['contract_id']);
        if (! $contract) {
            if (! $request->expectsJson()) {
                return back()->withErrors(['contract_id' => 'Hợp đồng không thuộc tài khoản của bạn.'])->withInput();
            }
            abort(422, 'The contract does not belong to you.');
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('maintenance', 'public');
        }

        $maintenance = MaintenanceRequest::query()->create([
            'contract_id' => $contract->id,
            'room_id' => $contract->room_id,
            'tenant_id' => $tenant->id,
            'category' => $data['category'] ?? null,
            'title' => $data['title'],
            'description' => $data['description'],
            'priority' => $data['priority'],
            'status' => 'pending',
            'image' => $imagePath,
        ]);

        if (! $request->expectsJson()) {
            return redirect()->route('tenant.maintenance.show', $maintenance->id)
                ->with('success', 'Đã gửi yêu cầu. Mã theo dõi: #' . $maintenance->id . ' — trạng thái: chờ xử lý.');
        }

        return response()->json(['message' => 'Request created.', 'data' => $maintenance], 201);
    }

    public function show(Request $request, $id)
    {
        $tenant = $this->actingTenant($request);

        $maintenance = MaintenanceRequest::query()
            ->where('tenant_id', $tenant->id)
            ->with(['room:id,name', 'tenant:id,name', 'contract:id,contract_code'])
            ->findOrFail($id);

        if (! $request->expectsJson()) {
            return view('tenant.maintenance.show', compact('maintenance', 'tenant'));
        }

        return response()->json(['data' => $maintenance]);
    }

    /**
     * Tenant từ user đăng nhập. Web demo (flag bật) cho guest dùng tenant
     * đầu tiên để test; production guest bị chuyển về login. JSON luôn cần login.
     */
    private function actingTenant(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'tenant', 403, 'Only tenants can manage requests.');

            return $user;
        }
        if (! $request->expectsJson()) {
            if (! config('app.demo_guest')) {
                throw new AuthenticationException('Vui lòng đăng nhập để tiếp tục.', ['web'], route('login'));
            }
            $demo = User::query()->where('role', 'tenant')->orderBy('id')->first();
            abort_unless($demo, 401, 'Chưa có tài khoản người thuê, vui lòng đăng nhập.');

            return $demo;
        }
        abort(401, 'Authentication is required.');
    }
}
