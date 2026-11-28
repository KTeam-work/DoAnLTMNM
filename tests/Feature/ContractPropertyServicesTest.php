<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ContractPropertyServicesTest extends TestCase
{
    use RefreshDatabase;

    public function test_contract_services_follow_the_selected_room_property_and_use_its_price(): void
    {
        Http::preventStrayRequests();
        Http::fake();
        config(['app.demo_guest' => false, 'mail.default' => 'array', 'services.resend.key' => null]);
        $this->travelTo(\Carbon\Carbon::parse('2026-11-27 09:00:00'));

        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $firstProperty = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Nhà A',
            'type' => 'rooming_house',
            'address' => 'Đường A',
            'ward' => 'Phường 1',
            'district' => 'Quận 1',
            'status' => 'active',
        ]);
        $secondProperty = Property::create([
            'owner_id' => $owner->id,
            'name' => 'Nhà B',
            'type' => 'rooming_house',
            'address' => 'Đường B',
            'ward' => 'Phường 2',
            'district' => 'Quận 2',
            'status' => 'active',
        ]);
        $firstRoom = Room::create([
            'property_id' => $firstProperty->id,
            'name' => 'A1',
            'area' => 20,
            'price' => 1000000,
            'deposit' => 0,
            'max_people' => 2,
            'status' => 'available',
        ]);
        Room::create([
            'property_id' => $secondProperty->id,
            'name' => 'B1',
            'area' => 20,
            'price' => 1000000,
            'deposit' => 0,
            'max_people' => 2,
            'status' => 'available',
        ]);

        $firstService = Service::create([
            'owner_id' => $owner->id,
            'name' => 'Rác Nhà A',
            'unit' => 'tháng',
            'price' => 10000,
            'status' => 'active',
        ]);
        $otherPropertyService = Service::create([
            'owner_id' => $owner->id,
            'name' => 'Xe Nhà B',
            'unit' => 'chiếc',
            'price' => 20000,
            'status' => 'active',
        ]);
        DB::table('property_services')->insert([
            [
                'property_id' => $firstProperty->id,
                'service_id' => $firstService->id,
                'price' => 15000,
                'status' => 'active',
            ],
            [
                'property_id' => $secondProperty->id,
                'service_id' => $otherPropertyService->id,
                'price' => 25000,
                'status' => 'active',
            ],
        ]);
        DB::table('viewing_appointments')->insert([
            'room_id' => $firstRoom->id,
            'tenant_id' => $tenant->id,
            'appointment_date' => '2026-11-27',
            'appointment_time' => '09:00:00',
            'phone' => '0901234567',
            'status' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->actingAs($owner)
            ->get(route('owner.contracts.create', ['room_id' => $firstRoom->id]))
            ->assertOk()
            ->assertSee('data-property-id="' . $firstProperty->id . '"', false)
            ->assertSee('data-property-id="' . $secondProperty->id . '"', false);

        $contractData = [
            'room_id' => $firstRoom->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-11-27',
            'end_date' => '2027-11-27',
            'rent' => 1000000,
            'deposit' => 0,
            'status' => 'active',
        ];

        $this->postJson(route('owner.contracts.store'), [
            ...$contractData,
            'service_ids' => [$otherPropertyService->id],
        ])->assertUnprocessable();

        $this->postJson(route('owner.contracts.store'), [
            ...$contractData,
            'service_ids' => [$firstService->id],
        ])->assertCreated();

        $contractId = DB::table('contracts')->where('room_id', $firstRoom->id)->value('id');
        $this->assertSame(
            15000.0,
            (float) DB::table('contract_services')
                ->where('contract_id', $contractId)
                ->where('service_id', $firstService->id)
                ->value('unit_price')
        );
    }
}
