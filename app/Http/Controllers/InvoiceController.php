<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $tenant = $this->actingTenant($request);

        $query = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with(['contract.room.property', 'contract:id,contract_code,room_id'])
            ->latest('billing_month');

        if ($request->filled('month')) {
            $query->whereDate('billing_month', \Carbon\Carbon::parse($request->input('month'))->startOfMonth());
        }
        if ($request->filled('status') && in_array($request->input('status'), ['unpaid', 'pending', 'paid', 'overdue', 'cancelled'], true)) {
            $query->where('status', $request->input('status'));
        }

        $invoices = $query->paginate(10)->withQueryString();
        $filters = $request->only(['month', 'status']);

        $base = Invoice::query()->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id));
        $today = now()->toDateString();
        $stats = [
            'overdue' => (clone $base)->whereIn('status', ['unpaid', 'pending'])->whereDate('due_date', '<', $today)->sum('total'),
            'unpaid' => (clone $base)->whereIn('status', ['unpaid', 'pending'])->sum('total'),
            'paid_year' => (clone $base)->where('status', 'paid')->whereYear('billing_month', now()->year)->sum('total'),
        ];

        if (! $request->expectsJson()) {
            return view('tenant.invoices.index', compact('invoices', 'tenant', 'filters', 'stats'));
        }

        return response()->json(['data' => $invoices]);
    }

    public function show(Request $request, $id)
    {
        $tenant = $this->actingTenant($request);

        $invoice = Invoice::query()
            ->whereHas('contract', fn ($q) => $q->where('tenant_id', $tenant->id))
            ->with(['items.service:id,name', 'contract.room.property', 'contract.tenant:id,name', 'contract.owner:id,name,phone', 'payments'])
            ->findOrFail($id);

        if (! $request->expectsJson()) {
            return view('tenant.invoices.show', compact('invoice', 'tenant'));
        }

        return response()->json(['data' => $invoice]);
    }

    private function actingTenant(Request $request): User
    {
        $user = $request->user();
        if ($user) {
            abort_unless($user->role === 'tenant', 403, 'Only tenants can access invoices.');

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
