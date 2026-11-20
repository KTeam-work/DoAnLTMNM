<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Service;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
                ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination', 'services'])
                ->find($id);
            abort_if(! $contract, 404);

            if (! $request->expectsJson()) {
                [$electricPrice, $waterPrice] = $this->getUtilityPrices($contract);
                return view('tenant.contracts.show', compact('contract', 'electricPrice', 'waterPrice'));
            }

            return response()->json(['data' => $contract]);
        }

        if (! $request->expectsJson()) {
            // Demo mode: guest chỉ xem theo id khi bật flag, còn lại về login.
            if (! config('app.demo_guest')) {
                $this->tenant($request);
            }
            $contract = Contract::query()
                ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination', 'services'])
                ->findOrFail($id);

            [$electricPrice, $waterPrice] = $this->getUtilityPrices($contract);
            return view('tenant.contracts.show', compact('contract', 'electricPrice', 'waterPrice'));
        }

        $tenant = $this->tenant($request);

        $contract = Contract::query()
            ->where('tenant_id', $tenant->id)
            ->with(['room.property', 'owner:id,name,email,phone', 'members', 'termination', 'services'])
            ->findOrFail($id);

        return response()->json(['data' => $contract]);
    }

    private function getUtilityPrices(Contract $contract): array
    {
        $defaultElectricPrice = 3500.0;
        $defaultWaterPrice = 20000.0;
        $ownerId = $contract->owner_id;
        $room = $contract->room;

        if ($room && $ownerId) {
            $propElectric = DB::table('property_services')
                ->join('services', 'property_services.service_id', '=', 'services.id')
                ->where('property_services.property_id', $room->property_id)
                ->where('property_services.status', 'active')
                ->where(function ($q) {
                    $q->where('services.unit', 'kWh')
                      ->orWhere('services.name', 'like', '%điện%');
                })
                ->value('property_services.price');

            if ($propElectric && (float) $propElectric > 0) {
                $defaultElectricPrice = (float) $propElectric;
            } else {
                $eService = Service::query()
                    ->where('owner_id', $ownerId)
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->where('unit', 'kWh')
                          ->orWhere('name', 'like', '%điện%');
                    })
                    ->first();
                if ($eService && (float) $eService->price > 0) {
                    $defaultElectricPrice = (float) $eService->price;
                }
            }

            $propWater = DB::table('property_services')
                ->join('services', 'property_services.service_id', '=', 'services.id')
                ->where('property_services.property_id', $room->property_id)
                ->where('property_services.status', 'active')
                ->where(function ($q) {
                    $q->where('services.unit', 'm³')
                      ->orWhere('services.unit', 'm3')
                      ->orWhere('services.name', 'like', '%nước%');
                })
                ->value('property_services.price');

            if ($propWater && (float) $propWater > 0) {
                $defaultWaterPrice = (float) $propWater;
            } else {
                $wService = Service::query()
                    ->where('owner_id', $ownerId)
                    ->where('status', 'active')
                    ->where(function ($q) {
                        $q->where('unit', 'm³')
                          ->orWhere('unit', 'm3')
                          ->orWhere('name', 'like', '%nước%');
                    })
                    ->first();
                if ($wService && (float) $wService->price > 0) {
                    $defaultWaterPrice = (float) $wService->price;
                }
            }
        }

        return [$defaultElectricPrice, $defaultWaterPrice];
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
