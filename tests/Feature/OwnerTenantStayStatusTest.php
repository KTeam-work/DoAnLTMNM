<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerTenantStayStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_contract_takes_precedence_over_a_historical_contract_in_tenant_list(): void
    {
        $this->travelTo(\Carbon\Carbon::parse('2026-11-28 09:00:00'));

        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Nhà kiểm tra',
            'type' => 'rooming_house',
            'address' => 'Đường A',
            'ward' => 'Phường 1',
            'district' => 'Quận 1',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'name' => 'A1',
            'area' => 20,
            'price' => 1000000,
            'deposit' => 0,
            'max_people' => 2,
            'status' => 'rented',
        ]);

        Contract::create([
            'contract_code' => 'HD-OLD',
            'room_id' => $room->id,
            'owner_id' => $owner->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2025-11-28',
            'end_date' => '2026-11-27',
            'rent' => 1000000,
            'deposit' => 0,
            'payment_cycle' => 'monthly',
            'status' => 'terminated',
        ]);
        Contract::create([
            'contract_code' => 'HD-CURRENT',
            'room_id' => $room->id,
            'owner_id' => $owner->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-11-28',
            'end_date' => '2027-11-28',
            'rent' => 1000000,
            'deposit' => 0,
            'payment_cycle' => 'monthly',
            'status' => 'active',
        ]);

        $this->actingAs($owner)
            ->get(route('owner.tenants.manage'))
            ->assertOk()
            ->assertSee('HD-CURRENT')
            ->assertSee('data-status="active"', false)
            ->assertSee('● Đang ở');
    }

    public function test_only_terminated_contracts_are_marked_as_tenant_moved_out(): void
    {
        $this->travelTo(\Carbon\Carbon::parse('2026-11-28 09:00:00'));

        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $property = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Nhà kiểm tra',
            'type' => 'rooming_house',
            'address' => 'Đường A',
            'ward' => 'Phường 1',
            'district' => 'Quận 1',
            'status' => 'active',
        ]);
        $room = Room::create([
            'property_id' => $property->id,
            'name' => 'A1',
            'area' => 20,
            'price' => 1000000,
            'deposit' => 0,
            'max_people' => 2,
            'status' => 'rented',
        ]);

        foreach ([
            ['name' => 'Đã thanh lý', 'code' => 'HD-TERMINATED', 'status' => 'terminated'],
            ['name' => 'Chờ thanh lý', 'code' => 'HD-EXPIRED', 'status' => 'expired'],
            ['name' => 'Hợp đồng nháp', 'code' => 'HD-DRAFT', 'status' => 'draft'],
        ] as $contractData) {
            $tenant = User::factory()->create([
                'name' => $contractData['name'],
                'role' => 'tenant',
                'status' => 'active',
            ]);
            Contract::create([
                'contract_code' => $contractData['code'],
                'room_id' => $room->id,
                'owner_id' => $owner->id,
                'tenant_id' => $tenant->id,
                'start_date' => '2026-01-01',
                'end_date' => '2026-11-27',
                'rent' => 1000000,
                'deposit' => 0,
                'payment_cycle' => 'monthly',
                'status' => $contractData['status'],
            ]);
        }

        $this->actingAs($owner)
            ->get(route('owner.tenants.manage'))
            ->assertOk()
            ->assertSee('data-status="left"', false)
            ->assertSee('data-status="pending"', false)
            ->assertSee('● Đã chuyển đi')
            ->assertSee('● Chờ thanh lý')
            ->assertSee('● Chưa kích hoạt');
    }
}
