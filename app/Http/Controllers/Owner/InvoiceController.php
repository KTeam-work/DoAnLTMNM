<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Notification;
use App\Models\Service;
use App\Models\User;
use App\Models\UtilityReading;
use App\Services\ResendService;
use Carbon\Carbon;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    //hiển thị trang quản lý hóa đơn
    public function index(Request $request)
    {
        $owner = $this->actingOwner($request);

        $selectedPropertyId = $request->input('property_id');
        $selectedRoomId = $request->input('room_id');

        $query = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->when($selectedPropertyId, fn ($q) => $q->whereHas('contract.room', fn ($rq) => $rq->where('property_id', $selectedPropertyId)))
            ->when($selectedRoomId, fn ($q) => $q->whereHas('contract', fn ($cq) => $cq->where('room_id', $selectedRoomId)))
            ->with(['contract.room:id,name,property_id', 'contract.room.property:id,name', 'contract.tenant:id,name'])
            ->latest('billing_month');
        
        // lọc theo tháng
        if ($request->filled('month')) {
            $query->whereDate('billing_month', Carbon::parse($request->input('month'))->startOfMonth());
        }

        // lọc theo trạng thái
        if ($request->filled('status') && in_array($request->input('status'), ['unpaid', 'pending', 'paid', 'overdue', 'cancelled'], true)) {
            $query->where('status', $request->input('status'));
        }

        // chia trang kết quả
        $invoices = $query->paginate(15)->withQueryString();
        $filters = $request->only(['month', 'status', 'property_id', 'room_id']);

        $properties = \App\Models\Property::query()->where('owner_id', $owner->id)->orderBy('name')->get();

        $currentMonth = $request->input('month', now()->format('Y-m'));
        $billingMonth = Carbon::parse($currentMonth)->startOfMonth();

        $allContracts = Contract::query()
            ->where('owner_id', $owner->id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $billingMonth->copy()->endOfMonth())
            ->when($selectedPropertyId, fn ($q) => $q->whereHas('room', fn ($rq) => $rq->where('property_id', $selectedPropertyId)))
            ->with(['room:id,name,property_id', 'room.property:id,name', 'tenant:id,name'])
            ->orderBy('room_id')
            ->get();

        // CHỈ HIỆN CÁC PHÒNG ĐÃ GHI ĐIỆN NƯỚC VÀ CHƯA CÓ HÓA ĐƠN TRONG THÁNG NÀY (HÓA ĐƠN HỦY COI NHƯ ĐƯỢC MỞ KHÓA)
        $activeContracts = $allContracts->filter(function ($contract) use ($billingMonth) {
            $hasInvoice = Invoice::query()->where('contract_id', $contract->id)->whereDate('billing_month', $billingMonth)->where('status', '!=', 'cancelled')->exists();
            if ($hasInvoice) return false;
            return UtilityReading::query()->where('room_id', $contract->room_id)->whereDate('month', $billingMonth)
                ->where(fn ($q) => $q->whereNull('contract_id')->orWhere('contract_id', $contract->id))->exists();
        });

        $missingReadingsContracts = $allContracts->filter(function ($contract) use ($billingMonth) {
            $hasInvoice = Invoice::query()->where('contract_id', $contract->id)->whereDate('billing_month', $billingMonth)->where('status', '!=', 'cancelled')->exists();
            if ($hasInvoice) return false;
            return ! UtilityReading::query()->where('room_id', $contract->room_id)->whereDate('month', $billingMonth)
                ->where(fn ($q) => $q->whereNull('contract_id')->orWhere('contract_id', $contract->id))->exists();
        });

        $initialSummary = [
            'total' => $allContracts->count(),
            'eligible' => $activeContracts->count(),
            'missing' => $missingReadingsContracts->count(),
            'missing_names' => $missingReadingsContracts->map(fn($c) => $c->room->name ?? '')->filter()->values()->all(),
        ];

        $base = Invoice::query()->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id));
        if ($selectedPropertyId) {
            $base->whereHas('contract.room', fn ($q) => $q->where('property_id', $selectedPropertyId));
        }
        $monthBase = (clone $base)->whereDate('billing_month', $billingMonth);
        $stats = [
            'expected' => (clone $monthBase)->where('status', '!=', 'cancelled')->sum('total'),
            'collected' => (clone $monthBase)->where('status', 'paid')->sum('total'),
            'outstanding' => (clone $monthBase)->whereIn('status', ['unpaid', 'pending', 'overdue'])->sum('total'),
            'outstanding_count' => (clone $monthBase)->whereIn('status', ['unpaid', 'pending', 'overdue'])->count(),
        ];

        if (! $request->expectsJson()) {
            return view('owner.invoices.index', compact('invoices', 'filters', 'stats', 'currentMonth', 'activeContracts', 'properties', 'selectedPropertyId', 'initialSummary'));
        }

        return response()->json(['data' => $invoices]);
    }

    // xem chi tiết hóa đơn
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

    // mẫu in pdf
    public function print(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with([
                'items.service:id,name',
                'contract.room.property',
                'contract.tenant:id,name,phone',
                'contract.owner:id,name,phone',
                'utilityReading',
                'payments'
            ])
            ->findOrFail($id);

        return view('invoices.print', [
            'invoice' => $invoice,
            'backUrl' => route('owner.invoices.show', $invoice->id),
            'role' => 'owner',
        ]);
    }

    // Cập nhật hạn thanh toán cho hóa đơn
    public function updateDueDate(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['contract.tenant', 'contract.room'])
            ->findOrFail($id);

        if (in_array($invoice->status, ['paid', 'cancelled'], true)) {
            return back()->with('error', 'Không thể đổi hạn thanh toán cho hóa đơn đã thanh toán hoặc đã hủy.');
        }

        $data = $request->validate([
            'due_date' => ['required', 'date'],
        ]);

        $oldDueDate = Carbon::parse($invoice->due_date)->format('d/m/Y');
        $newDueDate = Carbon::parse($data['due_date'])->toDateString();
        $newDueDateFormatted = Carbon::parse($data['due_date'])->format('d/m/Y');

        $invoice->update([
            'due_date' => $newDueDate,
            'status' => ($invoice->status === 'overdue' && $newDueDate >= now()->toDateString()) ? 'unpaid' : $invoice->status,
        ]);

        // Gửi thông báo cho người thuê (không dùng icon)
        if ($invoice->contract && $invoice->contract->tenant_id) {
            $billingMonthStr = Carbon::parse($invoice->billing_month)->format('m/Y');
            Notification::notify(
                $invoice->contract->tenant_id,
                'Cập nhật hạn thanh toán',
                "Chủ nhà đã cập nhật hạn thanh toán cho hóa đơn {$invoice->invoice_code} (kỳ {$billingMonthStr}, phòng {$invoice->contract->room->name}) từ ngày {$oldDueDate} sang ngày {$newDueDateFormatted}.",
                'invoice',
                'invoice',
                $invoice->id
            );
        }

        return back()->with('success', 'Đã cập nhật hạn thanh toán thành công sang ngày ' . $newDueDateFormatted . '. Hệ thống đã gửi thông báo đến người thuê.');
    }

    // Hủy hóa đơn tạo sai khi chưa thanh toán (chuyển sang trạng thái Đã hủy, mở khóa kỳ hóa đơn)
    public function destroy(Request $request, int $id)
    {
        $owner = $this->actingOwner($request);

        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('owner_id', $owner->id))
            ->with(['contract.tenant', 'contract.room'])
            ->findOrFail($id);

        if ($invoice->status === 'paid') {
            return back()->with('error', 'Không thể hủy hóa đơn đã thanh toán tiền.');
        }

        if ($invoice->status === 'cancelled') {
            return back()->with('error', 'Hóa đơn này đã ở trạng thái đã hủy trước đó.');
        }

        if ($invoice->payments()->where('status', 'pending')->exists()) {
            return back()->with('error', 'Không thể hủy hóa đơn đang có giao dịch thanh toán chờ xác nhận.');
        }

        $invoiceCode = $invoice->invoice_code;
        $tenantId = $invoice->contract->tenant_id ?? null;
        $roomName = $invoice->contract->room->name ?? '';
        $billingMonthStr = Carbon::parse($invoice->billing_month)->format('m/Y');

        // Chuyển trạng thái sang cancelled (vô hiệu hóa thanh toán, giữ lại lịch sử đối soát)
        $invoice->update([
            'status' => 'cancelled',
        ]);

        // Cập nhật các giao dịch dở dang sang đã hủy
        $invoice->payments()->whereIn('status', ['pending', 'failed'])->update([
            'status' => 'cancelled',
        ]);

        // Gửi thông báo cho người thuê (không dùng icon)
        if ($tenantId) {
            Notification::notify(
                $tenantId,
                'Hóa đơn đã được hủy',
                "Hóa đơn {$invoiceCode} kỳ {$billingMonthStr} (phòng {$roomName}) đã được chủ nhà hủy để điều chỉnh lại. Bạn không cần thanh toán hóa đơn này.",
                'invoice',
                'invoices',
                $invoice->id
            );
        }

        return redirect()->route('owner.invoices.show', $invoice->id)
            ->with('success', 'Đã chuyển trạng thái hóa đơn #' . $invoiceCode . ' sang Đã hủy. Kỳ này đã được mở khóa để bạn có thể tạo lại hóa đơn mới.');
    }

    // cái quét nợ
    public function processDueNow(Request $request)
    {
        $owner = $this->actingOwner($request);

        $stats = \App\Console\Commands\ProcessDueInvoicesCommand::processDueInvoices(false, $owner->id);

        $summary = "Đã quét {$stats['total_scanned']} hóa đơn: "
            . "Đã gửi {$stats['upcoming_reminders']} nhắc nợ sắp hạn, "
            . "{$stats['due_today_reminders']} nhắc nợ hôm nay, "
            . "đã cập nhật {$stats['marked_overdue']} hóa đơn quá hạn.";

        return back()->with('success', $summary);
    }

    // AJAX: Lấy danh sách các phòng ĐÃ GHI ĐIỆN NƯỚC và CHƯA CÓ HÓA ĐƠN trong tháng
    public function eligibleContracts(Request $request)
    {
        $owner = $this->actingOwner($request);
        $month = $request->input('month', now()->format('Y-m'));
        $billingMonth = Carbon::parse($month)->startOfMonth();
        $propertyId = $request->input('property_id');

        $allContracts = Contract::query()
            ->where('owner_id', $owner->id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $billingMonth->copy()->endOfMonth())
            ->when($propertyId, fn ($q) => $q->whereHas('room', fn ($rq) => $rq->where('property_id', $propertyId)))
            ->with(['room.property:id,name', 'tenant:id,name'])
            ->orderBy('room_id')
            ->get();

        $eligibleContracts = [];
        $missingReadings = [];
        $alreadyInvoiced = [];

        foreach ($allContracts as $contract) {
            $hasInvoice = Invoice::query()
                ->where('contract_id', $contract->id)
                ->whereDate('billing_month', $billingMonth)
                ->where('status', '!=', 'cancelled')
                ->exists();

            if ($hasInvoice) {
                $alreadyInvoiced[] = [
                    'id' => $contract->id,
                    'room_name' => $contract->room->name ?? '',
                    'property_name' => $contract->room->property->name ?? '',
                ];
                continue;
            }

            $reading = UtilityReading::query()
                ->where('room_id', $contract->room_id)
                ->whereDate('month', $billingMonth)
                ->where(fn ($q) => $q->whereNull('contract_id')->orWhere('contract_id', $contract->id))
                ->first();

            if (! $reading) {
                $missingReadings[] = [
                    'id' => $contract->id,
                    'room_name' => $contract->room->name ?? '',
                    'property_name' => $contract->room->property->name ?? '',
                ];
                continue;
            }

            $cDay = Carbon::parse($contract->start_date)->day;
            $suggestedDue = now('Asia/Ho_Chi_Minh')->addDays(5)->toDateString();

            $eligibleContracts[] = [
                'id' => $contract->id,
                'room_id' => $contract->room_id,
                'room_name' => $contract->room->name ?? '',
                'property_id' => $contract->room->property_id ?? null,
                'property_name' => $contract->room->property->name ?? '',
                'tenant_name' => $contract->tenant->name ?? '',
                'contract_day' => $cDay,
                'suggested_due_date' => $suggestedDue,
            ];
        }

        return response()->json([
            'month' => $billingMonth->format('m/Y'),
            'total_rooms' => $allContracts->count(),
            'eligible_contracts' => $eligibleContracts,
            'eligible_count' => count($eligibleContracts),
            'missing_readings_count' => count($missingReadings),
            'missing_readings' => $missingReadings,
            'already_invoiced_count' => count($alreadyInvoiced),
        ]);
    }

    // tạo hóa đơn tháng
    public function store(Request $request)
    {
        $owner = $this->actingOwner($request);

        $data = $request->validate([
            'month' => ['required', 'date'],
            'property_id' => ['nullable', 'integer', 'exists:properties,id'],
            'contract_id' => ['nullable', 'integer', 'exists:contracts,id'],
            'discount' => ['nullable', 'numeric', 'min:0'],
        ]);
        $billingMonth = Carbon::parse($data['month'])->startOfMonth();
        $issueDate = now('Asia/Ho_Chi_Minh')->startOfDay();

        $contracts = Contract::query()
            ->where('owner_id', $owner->id)
            ->where('status', 'active')
            ->whereDate('start_date', '<=', $billingMonth->copy()->endOfMonth())
            ->when($data['contract_id'] ?? null, fn ($q, $id) => $q->where('id', $id))
            ->when($data['property_id'] ?? null, fn ($q, $pid) => $q->whereHas('room', fn ($rq) => $rq->where('property_id', $pid)))
            ->with(['room.property', 'tenant'])
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
            if (Invoice::query()->where('contract_id', $contract->id)->whereDate('billing_month', $billingMonth)->where('status', '!=', 'cancelled')->exists()) {
                $skipped[] = $contract->room->name . ' (đã có hóa đơn kỳ này)';
                continue;
            }
            $reading = UtilityReading::query()
                ->where('room_id', $contract->room_id)
                ->whereDate('month', $billingMonth)
                ->where(fn ($q) => $q->whereNull('contract_id')->orWhere('contract_id', $contract->id))
                ->first();
            if (! $reading) {
                $skipped[] = $contract->room->name . ' (thiếu chỉ số điện nước)';
                continue;
            }
            $created[] = $this->buildInvoice($contract, $reading, $billingMonth, (float) ($data['discount'] ?? 0), $issueDate);
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

    private function buildInvoice(Contract $contract, UtilityReading $reading, Carbon $billingMonth, float $discount, Carbon $issueDate): int
    {
        return DB::transaction(function () use ($contract, $reading, $billingMonth, $discount, $issueDate) {
            $eUse = (float) $reading->electricity_new - (float) $reading->electricity_old;
            $wUse = (float) $reading->water_new - (float) $reading->water_old;

            $items = [
                ['type' => 'rent', 'description' => 'Tiền phòng ' . $contract->room->name, 'quantity' => 1, 'unit_price' => (float) $contract->rent, 'amount' => (float) $contract->rent, 'service_id' => null],
                ['type' => 'electricity', 'description' => "Điện: {$reading->electricity_old} → {$reading->electricity_new} ({$eUse} kWh)", 'quantity' => $eUse, 'unit_price' => (float) $reading->electricity_price, 'amount' => $eUse * (float) $reading->electricity_price, 'service_id' => null],
                ['type' => 'water', 'description' => "Nước: {$reading->water_old} → {$reading->water_new} ({$wUse} m³)", 'quantity' => $wUse, 'unit_price' => (float) $reading->water_price, 'amount' => $wUse * (float) $reading->water_price, 'service_id' => null],
            ];

            // Chỉ tính các dịch vụ đã được gắn trực tiếp vào hợp đồng.
            $contractServices = DB::table('contract_services')
                ->where('contract_id', $contract->id)->where('status', 'active')->get();
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
            $invoiceDueDate = $issueDate->copy()->addDays(5)->toDateString();

            $invoice = Invoice::query()->create([
                'invoice_code' => $this->invoiceCode($billingMonth),
                'contract_id' => $contract->id,
                'utility_reading_id' => $reading->id,
                'billing_month' => $billingMonth->toDateString(),
                'issue_date' => $issueDate->toDateString(),
                'due_date' => $invoiceDueDate,
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

            // Bắn email thông báo hóa đơn kèm thông tin đăng nhập và 2 cách thanh toán qua Gmail cho khách
            try {
                $tenant = $contract->tenant;
                if ($tenant && !empty($tenant->email)) {
                    /** @var ResendService $resendService */
                    $resendService = app(ResendService::class);
                    $resendService->sendInvoiceEmail(
                        toEmail: $tenant->email,
                        tenantName: $tenant->name,
                        roomName: $contract->room->name ?? 'Phòng trọ',
                        propertyName: $contract->room->property->name ?? 'Khu trọ',
                        invoiceCode: $invoice->invoice_code,
                        billingMonth: $billingMonth->format('m/Y'),
                        totalAmount: $invoice->total,
                        dueDate: Carbon::parse($invoice->due_date)->format('d/m/Y'),
                        invoiceId: $invoice->id,
                        initialPassword: $tenant->initial_password ?? null
                    );
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Lỗi gửi email hóa đơn qua Resend: ' . $e->getMessage(), [
                    'invoice_id' => $invoice->id,
                    'tenant_email' => $contract->tenant->email ?? null,
                ]);
            }

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

