<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);

        $query = MaintenanceRequest::query()
            ->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['room:id,name', 'tenant:id,name', 'contract:id,contract_code'])
            ->latest();

        if ($request->filled('status') && in_array($request->input('status'), ['pending', 'processing', 'completed', 'rejected'], true)) {
            $query->where('status', $request->input('status'));
        }

        $requests = $query->paginate(15)->withQueryString();
        $statusFilter = $request->input('status', 'all');
        $counts = [
            'total' => MaintenanceRequest::query()->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))->count(),
            'pending' => MaintenanceRequest::query()->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))->where('status', 'pending')->count(),
            'processing' => MaintenanceRequest::query()->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))->where('status', 'processing')->count(),
            'completed' => MaintenanceRequest::query()->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))->where('status', 'completed')->count(),
            'urgent' => MaintenanceRequest::query()->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))->where('status', 'pending')->whereIn('priority', ['high', 'urgent'])->count(),
        ];

        if (! $request->expectsJson()) {
            return view('owner.maintenance.index', compact('requests', 'statusFilter', 'counts'));
        }

        return response()->json(['data' => $requests]);
    }

    public function show(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $maintenance = MaintenanceRequest::query()
            ->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['room:id,name', 'tenant:id,name,phone', 'contract:id,contract_code'])
            ->findOrFail($id);

        if (! $request->expectsJson()) {
            return view('owner.maintenance.show', compact('maintenance'));
        }

        return response()->json(['data' => $maintenance]);
    }

    /**
     * Cập nhật trạng thái theo luồng: pending -> processing -> completed,
     * pending/processing -> rejected. Kèm ghi chú, hoàn thành ghi completed_at.
     */
    public function updateStatus(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $maintenance = MaintenanceRequest::query()
            ->whereHas('room.property', fn ($q) => $q->where('owner_id', $owner->id))
            ->findOrFail($id);

        $data = $request->validate([
            'status' => ['required', 'in:processing,completed,rejected'],
            'owner_note' => ['nullable', 'string'],
        ]);

        $allowed = [
            'pending' => ['processing', 'rejected'],
            'processing' => ['completed', 'rejected'],
        ];
        if (! in_array($data['status'], $allowed[$maintenance->status] ?? [], true)) {
            $message = 'Không thể chuyển trạng thái từ ' . $maintenance->status . ' sang ' . $data['status'] . '.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['status' => $message])->withInput();
            }
            abort(422, $message);
        }

        $maintenance->update([
            'status' => $data['status'],
            'owner_note' => $data['owner_note'] ?? $maintenance->owner_note,
            'completed_at' => $data['status'] === 'completed' ? now() : null,
        ]);

        $statusLabels = ['processing' => 'đang xử lý', 'completed' => 'hoàn thành', 'rejected' => 'từ chối'];
        Notification::notify(
            $maintenance->tenant_id,
            'Yêu cầu sửa chữa ' . ($statusLabels[$data['status']] ?? $data['status']),
            "Yêu cầu #TCK-{$maintenance->id} ({$maintenance->title}) đã được chủ nhà cập nhật.",
            'maintenance',
            'maintenance',
            $maintenance->id
        );

        if (! $request->expectsJson()) {
            return back()->with('success', 'Đã cập nhật trạng thái yêu cầu.');
        }

        return response()->json(['message' => 'Status updated.', 'data' => $maintenance->fresh()]);
    }

    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage maintenance requests.');

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

