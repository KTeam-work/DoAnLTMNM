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
            'damage_type' => ['nullable', 'in:landlord,tenant'],
            'cost' => ['nullable', 'numeric', 'min:0'],
        ]);

        $allowed = [
            'pending' => ['processing', 'completed', 'rejected'],
            'processing' => ['completed', 'rejected'],
            'completed' => ['completed', 'processing'],
        ];
        if (! in_array($data['status'], $allowed[$maintenance->status] ?? [], true)) {
            $message = 'Không thể chuyển trạng thái từ ' . $maintenance->status . ' sang ' . $data['status'] . '.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['status' => $message])->withInput();
            }
            abort(422, $message);
        }

        $updateData = [
            'status' => $data['status'],
            'owner_note' => $data['owner_note'] ?? $maintenance->owner_note,
            'completed_at' => $data['status'] === 'completed' ? now() : null,
        ];

        if ($data['status'] === 'completed') {
            $damageType = $data['damage_type'] ?? 'landlord';
            $updateData['damage_type'] = $damageType;

            if ($damageType === 'tenant') {
                $cost = floatval($data['cost'] ?? 0);
                $updateData['cost'] = $cost;

                // Tự động tạo hóa đơn riêng nếu có hợp đồng và chi phí > 0
                if ($cost > 0) {
                    $contract = $maintenance->contract ?? \App\Models\Contract::where('room_id', $maintenance->room_id)
                        ->where('tenant_id', $maintenance->tenant_id)
                        ->where('status', 'active')
                        ->first();

                    if ($contract) {
                        $invoiceCode = 'INV-SC-' . date('Ymd') . '-' . strtoupper(\Illuminate\Support\Str::random(4));
                        $invoice = \App\Models\Invoice::create([
                            'invoice_code' => $invoiceCode,
                            'contract_id' => $contract->id,
                            'billing_month' => now()->startOfMonth(),
                            'issue_date' => now()->toDateString(),
                            'due_date' => now()->addDays(5)->toDateString(),
                            'subtotal' => $cost,
                            'discount' => 0,
                            'total' => $cost,
                            'status' => 'unpaid',
                        ]);

                        \App\Models\InvoiceItem::create([
                            'invoice_id' => $invoice->id,
                            'type' => 'other',
                            'description' => "Chi phí sửa chữa sự cố #TCK-{$maintenance->id} ({$maintenance->title})",
                            'quantity' => 1,
                            'unit_price' => $cost,
                            'amount' => $cost,
                        ]);

                        $updateData['invoice_id'] = $invoice->id;

                        // Thông báo kèm link hóa đơn cho người thuê
                        Notification::notify(
                            $maintenance->tenant_id,
                            'Hóa đơn sửa chữa sự cố #TCK-' . $maintenance->id,
                            "Sự cố đã hoàn thành. Chi phí do người thuê chịu là " . number_format($cost) . " đ. Hóa đơn #{$invoice->invoice_code} đã được tạo, vui lòng thanh toán.",
                            'invoice',
                            'invoice',
                            $invoice->id
                        );
                    }
                }
            } else {
                // Hao mòn tự nhiên do chủ trọ chịu
                $updateData['cost'] = 0;
                $updateData['invoice_id'] = null;

                Notification::notify(
                    $maintenance->tenant_id,
                    'Sự cố #TCK-' . $maintenance->id . ' đã sửa xong (Miễn phí)',
                    "Sự cố '{$maintenance->title}' do hao mòn thiết bị đã được xử lý hoàn tất. Bạn không phải thanh toán chi phí nào.",
                    'maintenance',
                    'maintenance',
                    $maintenance->id
                );
            }
        }

        $maintenance->update($updateData);

        if ($data['status'] !== 'completed') {
            $statusLabels = ['processing' => 'đang xử lý', 'rejected' => 'từ chối'];
            $tenantTitle = $data['status'] === 'processing'
                ? 'Chủ trọ đã bắt đầu sửa chữa sự cố'
                : 'Yêu cầu sửa chữa: ' . ($statusLabels[$data['status']] ?? $data['status']);
            $tenantMessage = $data['status'] === 'processing'
                ? "Chủ trọ đã tiếp nhận sự cố '{$maintenance->title}' (#TCK-{$maintenance->id}) và đang tiến hành kiểm tra, sửa chữa."
                : "Yêu cầu sửa chữa sự cố #TCK-{$maintenance->id} ({$maintenance->title}) đã bị từ chối.";

            Notification::notify(
                $maintenance->tenant_id,
                $tenantTitle,
                $tenantMessage,
                'maintenance',
                'maintenance',
                $maintenance->id
            );
        }

        if (! $request->expectsJson()) {
            $flashMsg = match ($data['status']) {
                'processing' => "Đã tiếp nhận sự cố #TCK-{$maintenance->id} và bắt đầu sửa chữa! Hệ thống đã gửi thông báo đến người thuê.",
                'completed' => "Đã hoàn thành xử lý sự cố #TCK-{$maintenance->id} thành công!",
                'rejected' => "Đã từ chối yêu cầu sự cố #TCK-{$maintenance->id}.",
                default => 'Đã cập nhật trạng thái yêu cầu.',
            };
            return back()->with('success', $flashMsg);
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

