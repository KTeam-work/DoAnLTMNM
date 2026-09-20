<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use App\Models\UtilityReading;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);

        $query = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['contract.room:id,name', 'contract.tenant:id,name'])
            ->latest('billing_month');

        if ($request->filled('month')) {
            $query->whereDate('billing_month', Carbon::parse($request->input('month'))->startOfMonth());
        }
        if ($request->filled('status') && in_array($request->input('status'), ['unpaid', 'pending', 'paid', 'overdue', 'cancelled'], true)) {
            $query->where('status', $request->input('status'));
        }

        $invoices = $query->paginate(15)->withQueryString();
        $filters = $request->only(['month', 'status']);
        $currentMonth = $request->input('month', now()->format('Y-m'));

        $base = Invoice::query()->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id));
        $monthBase = (clone $base)->whereDate('billing_month', Carbon::parse($currentMonth)->startOfMonth());
        $stats = [
            'expected' => (clone $monthBase)->sum('total'),
            'collected' => (clone $monthBase)->where('status', 'paid')->sum('total'),
            'outstanding' => (clone $monthBase)->whereIn('status', ['unpaid', 'pending'])->sum('total'),
            'outstanding_count' => (clone $monthBase)->whereIn('status', ['unpaid', 'pending'])->count(),
        ];

        if (! $request->expectsJson()) {
            return view('owner.invoices.index', compact('invoices', 'filters', 'stats', 'currentMonth'));
        }

        return response()->json(['data' => $invoices]);
    }

    public function show(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['items.service:id,name', 'contract.room.property', 'contract.tenant:id,name,phone', 'payments'])
            ->findOrFail($id);

        if (! $request->expectsJson()) {
            return view('owner.invoices.show', compact('invoice'));
        }

        return response()->json(['data' => $invoice]);
    }

    /**
     * Tạo hóa đơn tháng: 1 hợp đồng (contract_id) hoặc hàng loạt.
     */
    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);

        $data = $request->validate([
            'month' => ['required', 'date'],
            'contract_id' => ['nullable', 'integer', 'exists:contracts,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);
        $billingMonth = Carbon::parse($data['month'])->startOfMonth();

        $contracts = Contract::query()
            ->where('owner_id', $owner->id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $billingMonth->copy()->endOfMonth())
            ->when($data['contract_id'] ?? null, fn ($q, $id) => $q->where('id', $id))
            ->with('room')
            ->get();

        if ($contracts->isEmpty()) {
            $message = 'Không có hợp đồng hiệu lực nào cho kỳ này.';
            if (! $request->expectsJson()) {
                return back()->withErrors(['month' => $message])->withInput();
            }
            abort(422, $message);
        }

        $created = [];
        $skipped = [];
        foreach ($contracts as $contract) {
            if (Invoice::query()->where('contract_id', $contract->id)->whereDate('billing_month', $billingMonth)->exists()) {
                $skipped[] = $contract->room->name . ' (đã có hóa đơn kỳ này)';
                continue;
            }
            $reading = UtilityReading::query()
                ->where('room_id', $contract->room_id)
                ->whereDate('month', $billingMonth)
                ->first();
            if (! $reading) {
                $skipped[] = $contract->room->name . ' (thiếu chỉ số điện nước)';
                continue;
            }
            $created[] = $this->buildInvoice($contract, $reading, $billingMonth, (float) ($data['discount'] ?? 0));
        }

        if ($created === []) {
            $message = 'Không tạo được hóa đơn nào. ' . implode('; ', $skipped);
            if (! $request->expectsJson()) {
                return back()->withErrors(['month' => $message])->withInput();
            }
            abort(422, $message);
        }

        $summary = 'Đã tạo ' . count($created) . ' hóa đơn.'
            . ($skipped ? ' Bỏ qua: ' . implode('; ', $skipped) : '');
        if (! $request->expectsJson()) {
            return redirect()->route('owner.invoices.index', ['month' => $billingMonth->format('Y-m')])
                ->with('success', $summary);
        }

        return response()->json([
            'message' => $summary,
            'data' => Invoice::query()->whereKey($created)->with('items')->get(),
            'skipped' => $skipped,
        ], 201);
    }

    private function buildInvoice(Contract $contract, UtilityReading $reading, Carbon $billingMonth, float $discount): int
    {
        return DB::transaction(function () use ($contract, $reading, $billingMonth, $discount) {
            $eUse = (float) $reading->electricity_new - (float) $reading->electricity_old;
            $wUse = (float) $reading->water_new - (float) $reading->water_old;

            $items = [
                ['type' => 'rent', 'description' => 'Tiền phòng ' . $contract->room->name, 'quantity' => 1, 'unit_price' => (float) $contract->rent, 'amount' => (float) $contract->rent, 'service_id' => null],
                ['type' => 'electricity', 'description' => "Điện: {$reading->electricity_old} → {$reading->electricity_new} ({$eUse} kWh)", 'quantity' => $eUse, 'unit_price' => (float) $reading->electricity_price, 'amount' => $eUse * (float) $reading->electricity_price, 'service_id' => null],
                ['type' => 'water', 'description' => "Nước: {$reading->water_old} → {$reading->water_new} ({$wUse} m³)", 'quantity' => $wUse, 'unit_price' => (float) $reading->water_price, 'amount' => $wUse * (float) $reading->water_price, 'service_id' => null],
            ];

            // Dịch vụ gắn với hợp đồng; nếu chưa gắn thì dùng dịch vụ đang hoạt động của chủ trọ.
            $contractServices = DB::table('contract_services')
                ->where('contract_id', $contract->id)->where('status', 'active')->get();
            if ($contractServices->isEmpty()) {
                $contractServices = Service::query()->where('owner_id', $contract->owner_id)
                    ->where('status', 'active')->whereNotIn('unit', ['kWh', 'm³'])
                    ->get()->map(fn ($s) => (object) ['service_id' => $s->id, 'quantity' => 1, 'unit_price' => (float) $s->price]);
            }
            foreach ($contractServices as $cs) {
                $service = Service::query()->find($cs->service_id);
                if (! $service || $service->status !== 'active') {
                    continue;
                }
                $qty = (float) $cs->quantity;
                $price = (float) $cs->unit_price;
                $items[] = ['type' => 'service', 'description' => $service->name . " ({$service->unit})", 'quantity' => $qty, 'unit_price' => $price, 'amount' => $qty * $price, 'service_id' => $service->id];
            }

            $subtotal = array_sum(array_column($items, 'amount'));
            $discount = min($discount, $subtotal);

            $invoice = Invoice::query()->create([
                'invoice_code' => $this->invoiceCode($billingMonth),
                'contract_id' => $contract->id,
                'utility_reading_id' => $reading->id,
                'billing_month' => $billingMonth->toDateString(),
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays(10)->toDateString(),
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $subtotal - $discount,
                'status' => 'unpaid',
            ]);
            $invoice->items()->createMany($items);

            Notification::notify(
                $contract->tenant_id,
                'Hóa đơn mới',
                "Hóa đơn {$invoice->invoice_code} kỳ {$billingMonth->format('m/Y')} phòng {$contract->room->name}: " . number_format($invoice->total) . ' đ. Hạn thanh toán ' . Carbon::parse($invoice->due_date)->format('d/m/Y') . '.',
                'invoice',
                'invoice',
                $invoice->id
            );

            return $invoice->id;
        });
    }

    private function invoiceCode(Carbon $month): string
    {
        do {
            $code = 'INV-' . $month->format('Ym') . '-' . Str::upper(Str::random(5));
        } while (Invoice::query()->where('invoice_code', $code)->exists());

        return $code;
    }

    private function actingOwner(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'owner', 403, 'Only owners can manage invoices.');

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

