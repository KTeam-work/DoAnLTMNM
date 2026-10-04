<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContractServiceUtilityApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_browser_menu_links_render_their_blade_pages(): void
    {
        config()->set('app.demo_guest', true); // cho phép guest xem demo
        $this->get('/tenant')->assertRedirect('/tenant/home');
        $this->get('/owner/tenants')->assertSuccessful();
        $this->get('/owner/contracts')->assertSuccessful();
        $owner = User::factory()->create(['role' => 'owner']);

        foreach ([
            '/tenant/contracts',
            '/owner/tenants',
            '/owner/tenants/create',
            '/owner/tenants/edit',
            '/owner/tenants/1',
            '/owner/contracts',
            '/owner/contracts/create',
            '/owner/services',
            '/owner/services/1',
            '/owner/utilities',
            '/owner/utilities/create',
        ] as $url) {
            $this->actingAs($owner)->get($url)->assertSuccessful();
        }
    }

    public function test_owner_and_tenant_can_use_the_assigned_contract_service_and_utility_endpoints(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $tenant = User::factory()->create(['role' => 'tenant']);
        $property = Property::query()->create([
            'owner_id' => $owner->id,
            'name' => 'Nha tro A',
            'type' => 'rooming_house',
            'address' => '1 Duong A',
            'district' => 'Quan 1',
            'ward' => 'Phuong 1',
        ]);
        $room = Room::query()->create([
            'property_id' => $property->id,
            'name' => 'Phong 101',
            'area' => 25,
            'price' => 3000000,
            'deposit' => 3000000,
            'max_people' => 2,
        ]);

        $contract = $this->actingAs($owner)->postJson('/owner/contracts', [
            'room_id' => $room->id,
            'tenant_id' => $tenant->id,
            'start_date' => '2026-01-01',
            'end_date' => '2026-12-31',
            'rent' => 3000000,
            'deposit' => 3000000,
            'status' => 'active',
        ])->assertCreated()->json('data');

        $this->putJson("/owner/contracts/{$contract['id']}/initial-utilities", [
            'initial_electricity_reading' => 10,
            'initial_water_reading' => 4,
        ])->assertOk()->assertJsonPath('data.initial_electricity_reading', '10.00');

        $member = $this->postJson("/owner/contracts/{$contract['id']}/members", [
            'name' => 'Nguoi o cung',
            'identity_card' => '0123456789',
        ])->assertCreated()->json('data');
        $this->postJson("/owner/contracts/{$contract['id']}/members", [
            'action' => 'remove',
            'member_id' => $member['id'],
        ])->assertOk();

        $service = $this->postJson('/owner/services', [
            'name' => 'Internet',
            'unit' => 'thang',
            'price' => 100000,
        ])->assertCreated()->json('data');
        $this->putJson('/owner/services', ['id' => $service['id'], 'price' => 120000])->assertOk();
        $this->getJson('/owner/services')->assertOk()->assertJsonCount(1, 'data');

        $reading = $this->postJson('/owner/utilities', [
            'room_id' => $room->id,
            'month' => '2026-02-01',
            'electricity_new' => 25,
            'electricity_price' => 3500,
            'water_new' => 8,
            'water_price' => 15000,
        ])->assertCreated()->json('data');
        $this->assertSame('10.00', $reading['electricity_old']);
        $this->putJson('/owner/utilities', ['id' => $reading['id'], 'electricity_new' => 30])->assertOk();

        $this->getJson('/owner/tenants')->assertOk()->assertJsonPath('data.0.id', $tenant->id);
        $this->actingAs($tenant)->getJson('/tenant/contracts')->assertOk()->assertJsonPath('data.0.id', $contract['id']);
        $this->getJson("/tenant/contracts/{$contract['id']}")->assertOk();

        $this->actingAs($owner)->putJson("/owner/contracts/{$contract['id']}/terminate", [
            'deduction_amount' => 500000,
            'return_date' => '2026-12-31',
        ])->assertOk()->assertJsonPath('data.refund_amount', '2500000.00');
    }

    public function test_owner_creates_a_room_for_own_property_and_returns_to_room_management(): void
    {
        $owner = User::factory()->create(['role' => 'owner']);
        $otherOwner = User::factory()->create(['role' => 'owner']);
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Khu tro cua toi', 'type' => 'rooming_house',
            'address' => '1 Duong A', 'district' => 'Quan 1', 'ward' => 'Phuong 1',
        ]);
        $otherProperty = Property::query()->create([
            'owner_id' => $otherOwner->id, 'name' => 'Khu tro khac', 'type' => 'rooming_house',
            'address' => '2 Duong B', 'district' => 'Quan 2', 'ward' => 'Phuong 2',
        ]);
        Room::query()->create([
            'property_id' => $otherProperty->id, 'name' => 'Phong khong duoc xem',
            'area' => 20, 'price' => 2000000, 'deposit' => 2000000, 'max_people' => 2,
        ]);

        $this->actingAs($owner)->post('/owner/rooms', [
            'property_id' => $property->id, 'name' => 'Phong moi', 'room_code' => 'P-001',
            'area' => 25, 'price' => 3000000, 'deposit' => 3000000, 'max_people' => 2,
        ])->assertRedirect(route('owner.rooms.index'));

        $this->assertDatabaseHas('rooms', ['property_id' => $property->id, 'name' => 'Phong moi']);
        $this->get('/owner/rooms')->assertOk()->assertSee('Phong moi')->assertDontSee('Phong khong duoc xem');
    }
}
