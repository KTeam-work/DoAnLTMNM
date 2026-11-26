<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UtilityContractCycleTest extends TestCase
{
    use RefreshDatabase;

    private function setupContract(string $start): array
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Quận 1', 'ward' => 'P1']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'A1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1]);
        $contract = Contract::create(['contract_code' => 'HD-CYCLE', 'room_id' => $room->id, 'owner_id' => $owner->id, 'tenant_id' => $tenant->id, 'start_date' => $start, 'end_date' => '2028-12-31', 'rent' => 1000000, 'deposit' => 0, 'payment_cycle' => 'monthly', 'status' => 'active', 'initial_electricity_reading' => 100, 'initial_water_reading' => 20]);
        return [$owner, $room, $contract];
    }

    private function payload(Room $room, string $month, int $electric, int $water): array
    {
        return ['room_id' => $room->id, 'month' => $month, 'electricity_old' => 0, 'electricity_new' => $electric, 'electricity_price' => 3500, 'water_old' => 0, 'water_new' => $water, 'water_price' => 20000];
    }

    public function test_first_and_following_cycle_use_contract_start_and_previous_reading(): void
    {
        [$owner, $room, $contract] = $this->setupContract('2026-11-26');
        $this->actingAs($owner)->getJson(route('owner.utilities.previous-reading', ['room_id' => $room->id, 'month' => '2026-12']))
            ->assertOk()->assertJsonPath('period_start', '2026-11-26')->assertJsonPath('period_end', '2026-12-26')
            ->assertJsonPath('electricity_old', 100);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-12', 140, 25))->assertCreated();
        $this->get(route('owner.utilities.create', ['month' => '2026-12']))->assertOk()->assertSee('Đã ghi kỳ này');
        $first = $room->utilityReadings()->where('contract_id', $contract->id)->whereDate('month', '2026-12-01')->firstOrFail();
        $this->assertSame('2026-11-26', $first->period_start->toDateString());
        $this->assertSame('2026-12-26', $first->period_end->toDateString());
        $this->assertEquals(100, $first->electricity_old);
        $this->postJson(route('owner.invoices.store'), ['month' => '2026-12'])->assertCreated();
        $this->assertDatabaseHas('invoices', ['contract_id' => $contract->id, 'utility_reading_id' => $first->id]);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2027-01', 160, 28))->assertCreated();
        $second = $room->utilityReadings()->where('contract_id', $contract->id)->whereDate('month', '2027-01-01')->firstOrFail();
        $this->assertSame('2026-12-26', $second->period_start->toDateString());
        $this->assertSame('2027-01-26', $second->period_end->toDateString());
        $this->assertEquals(140, $second->electricity_old);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2027-01', 160, 28))->assertStatus(422);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2027-03', 180, 30))->assertStatus(422);
        $this->deleteJson(route('owner.utilities.destroy', $first))->assertStatus(422);
    }

    public function test_month_end_is_clamped_and_returns_to_original_contract_day(): void
    {
        [$owner, $room] = $this->setupContract('2027-01-31');
        $this->actingAs($owner)->postJson(route('owner.utilities.store'), $this->payload($room, '2027-02', 110, 21))->assertCreated();
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2027-03', 120, 22))->assertCreated();
        $first = $room->utilityReadings()->whereDate('month', '2027-02-01')->firstOrFail();
        $second = $room->utilityReadings()->whereDate('month', '2027-03-01')->firstOrFail();
        $this->assertSame('2027-01-31', $first->period_start->toDateString());
        $this->assertSame('2027-02-28', $first->period_end->toDateString());
        $this->assertSame('2027-02-28', $second->period_start->toDateString());
        $this->assertSame('2027-03-31', $second->period_end->toDateString());
    }
}
