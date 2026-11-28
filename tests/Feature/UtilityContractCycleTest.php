<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use App\Models\UtilityReading;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UtilityContractCycleTest extends TestCase
{
    use RefreshDatabase;

    private function setupContract(): array
    {
        Http::preventStrayRequests();
        Http::fake();
        config(['mail.default' => 'array', 'services.resend.key' => null]);
        $this->travelTo(\Carbon\Carbon::parse('2026-11-27 12:00:00', 'Asia/Ho_Chi_Minh'));
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Quận 1', 'ward' => 'P1']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'A1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1]);
        $contract = Contract::create(['contract_code' => 'HD-CYCLE', 'room_id' => $room->id, 'owner_id' => $owner->id, 'tenant_id' => $tenant->id, 'start_date' => '2026-10-26', 'end_date' => '2028-12-31', 'rent' => 1000000, 'deposit' => 0, 'payment_cycle' => 'monthly', 'status' => 'active', 'initial_electricity_reading' => 100, 'initial_water_reading' => 20]);
        $contract->forceFill(['created_at' => \Carbon\Carbon::parse('2026-10-26 09:00:00', 'Asia/Ho_Chi_Minh')])->save();
        return [$owner, $room, $contract];
    }

    private function payload(Room $room, string $date, int $electric, int $water): array
    {
        return ['room_id' => $room->id, 'reading_date' => $date, 'electricity_old' => 0, 'electricity_new' => $electric, 'electricity_price' => 3500, 'water_old' => 0, 'water_new' => $water, 'water_price' => 20000];
    }

    public function test_contract_can_be_terminated_after_one_day_with_final_utilities(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $contract->forceFill(['start_date' => '2026-11-27', 'created_at' => now()])->save();
        $this->travelTo(\Carbon\Carbon::parse('2026-11-28 12:00:00', 'Asia/Ho_Chi_Minh'));
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-28', 'electricity_new' => 105, 'electricity_price' => 3500, 'water_new' => 21, 'water_price' => 20000];
        $this->actingAs($owner)->putJson(route('owner.contracts.terminate', $contract), $payload)->assertOk()->assertJsonPath('data.utility_total', 37500);
        $this->assertSame('terminated', $contract->fresh()->status);
        $this->assertSame('available', $room->fresh()->status);
        $this->assertEquals(37500, $contract->fresh()->termination->utilityInvoice->total);
    }

    public function test_contract_cannot_be_terminated_on_the_day_it_is_created(): void
    {
        [$owner, , $contract] = $this->setupContract();
        $contract->forceFill(['created_at' => now()])->save();
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-27', 'electricity_new' => 105, 'electricity_price' => 3500, 'water_new' => 21, 'water_price' => 20000];
        $response = $this->actingAs($owner)->putJson(route('owner.contracts.terminate', $contract), $payload)->assertUnprocessable()->assertJsonValidationErrors('return_date');
        $this->assertStringContainsString('28/11/2026', $response->json('errors.return_date.0'));
        $this->assertSame('active', $contract->fresh()->status);
    }

    public function test_termination_explains_when_the_contract_start_date_is_in_the_future(): void
    {
        [$owner, , $contract] = $this->setupContract();
        $contract->update(['start_date' => '2026-12-27']);
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-28', 'electricity_new' => 105, 'electricity_price' => 3500, 'water_new' => 21, 'water_price' => 20000];
        $response = $this->actingAs($owner)->putJson(route('owner.contracts.terminate', $contract), $payload)->assertUnprocessable()->assertJsonValidationErrors('return_date');
        $this->assertStringContainsString('27/12/2026', $response->json('errors.return_date.0'));
        $this->assertSame('active', $contract->fresh()->status);
    }

    public function test_flexible_dates_keep_previous_reading_and_invoice_month(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $this->actingAs($owner)->getJson(route('owner.utilities.previous-reading', ['room_id' => $room->id, 'reading_date' => '2026-11-24']))
            ->assertOk()->assertJsonPath('period_start', '2026-10-26')->assertJsonPath('period_end', '2026-11-24')->assertJsonPath('electricity_old', 100);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-24', 140, 25))->assertCreated();
        $first = UtilityReading::where('room_id', $room->id)->firstOrFail();
        $this->assertSame('2026-11-24', $first->period_end->toDateString());
        $this->assertSame('2026-11-01', $first->month->toDateString());
        $this->assertEquals(100, $first->electricity_old);
        $this->postJson(route('owner.invoices.store'), ['month' => '2026-11'])->assertCreated();
        $this->assertDatabaseHas('invoices', ['contract_id' => $contract->id, 'utility_reading_id' => $first->id]);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-25', 150, 26))->assertStatus(422);
        $this->getJson(route('owner.utilities.previous-reading', ['room_id' => $room->id, 'reading_date' => '2026-12-26']))
            ->assertOk()->assertJsonPath('period_start', '2026-11-24');
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-12-26', 180, 30))->assertCreated();
        $second = UtilityReading::where('room_id', $room->id)->whereDate('month', '2026-12-01')->firstOrFail();
        $this->assertSame('2026-11-24', $second->period_start->toDateString());
        $this->assertSame('2026-12-26', $second->period_end->toDateString());
        $this->assertEquals(140, $second->electricity_old);
        $this->deleteJson(route('owner.utilities.destroy', $first))->assertStatus(422);
    }

    public function test_saving_reading_returns_to_all_periods_without_hiding_other_records(): void
    {
        [$owner, $room] = $this->setupContract();
        $this->actingAs($owner)->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-24', 140, 25))->assertCreated();
        $response = $this->post(route('owner.utilities.store'), $this->payload($room, '2026-12-26', 180, 30));
        $response->assertRedirect(route('owner.utilities.index', ['all' => 1]));
        $this->get($response->headers->get('Location'))->assertOk()->assertSee('Tháng 11/2026')->assertSee('Tháng 12/2026')->assertSee('2 bản ghi');
        $this->get(route('owner.utilities.index', ['month' => '']))->assertOk()->assertSee('Tháng 11/2026')->assertSee('Tháng 12/2026');
    }

    public function test_legacy_reading_requires_confirmed_actual_date(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        UtilityReading::create(['room_id' => $room->id, 'month' => '2026-10-01', 'period_start' => '2026-09-26', 'period_end' => '2026-10-30', 'electricity_old' => 100, 'electricity_new' => 110, 'electricity_price' => 3500, 'water_old' => 20, 'water_new' => 21, 'water_price' => 20000]);
        $this->actingAs($owner)->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-24', 140, 25))->assertStatus(422);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-24', 140, 25) + ['previous_reading_date' => '2026-10-10'])->assertStatus(422);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-24', 140, 25) + ['previous_reading_date' => '2026-10-30'])->assertCreated();
        $reading = UtilityReading::where('contract_id', $contract->id)->firstOrFail();
        $this->assertSame('2026-10-30', $reading->period_start->toDateString());
        $this->assertEquals(110, $reading->electricity_old);
        $this->assertTrue($reading->reading_date_confirmed);
    }

    public function test_suggested_reading_date_follows_contract_and_latest_record(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $contract->update(['start_date' => '2026-12-27']);
        $this->actingAs($owner)->get(route('owner.utilities.create', ['room_id' => $room->id]))
            ->assertOk()->assertSee('data-suggested-date="2027-01-27"', false)
            ->assertSee('value="2027-01-27"', false);

        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2027-01-25', 140, 25))->assertCreated();
        $this->get(route('owner.utilities.create', ['room_id' => $room->id]))
            ->assertOk()->assertSee('data-suggested-date="2027-02-27"', false);
    }

    public function test_reading_window_and_early_cross_month_billing(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $contract->update(['start_date' => '2026-10-02']);
        $this->travelTo(\Carbon\Carbon::parse('2026-10-30 12:00:00', 'Asia/Ho_Chi_Minh'));
        $this->actingAs($owner)->postJson(route('owner.utilities.store'), $this->payload($room, '2026-10-29', 140, 25))->assertStatus(422);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-10-30', 140, 25))->assertCreated();
        $reading = UtilityReading::where('contract_id', $contract->id)->firstOrFail();
        $this->assertSame('2026-11-01', $reading->month->toDateString());
        $this->assertSame('2026-10-30', $reading->period_end->toDateString());
        $this->postJson(route('owner.invoices.store'), ['month' => '2026-11'])->assertCreated();
        $this->assertDatabaseHas('invoices', ['contract_id' => $contract->id, 'utility_reading_id' => $reading->id]);
    }

    public function test_early_and_late_readings_are_blocked_without_terminating_contract(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $this->actingAs($owner)->getJson(route('owner.utilities.previous-reading', ['room_id' => $room->id, 'reading_date' => '2026-11-22']))
            ->assertOk()->assertJsonPath('termination_required', true);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-22', 140, 25))->assertStatus(422);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-27', 140, 25))->assertStatus(422);
        $this->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-26', 140, 25))->assertCreated();
        $this->assertSame('active', $contract->fresh()->status);
    }

    public function test_termination_calculates_partial_utilities_from_initial_or_latest_reading(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-27', 'electricity_new' => 115, 'electricity_price' => 3500, 'water_new' => 22, 'water_price' => 20000];
        $this->actingAs($owner)->putJson(route('owner.contracts.terminate', $contract), $payload)
            ->assertOk()->assertJsonPath('data.utility_total', 92500);
        $this->assertEquals(100, $contract->fresh()->termination->electricity_old);
    }

    public function test_termination_uses_previous_reading_and_rejects_smaller_numbers(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $this->actingAs($owner)->postJson(route('owner.utilities.store'), $this->payload($room, '2026-11-24', 140, 25))->assertCreated();
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-27', 'electricity_new' => 139, 'electricity_price' => 3500, 'water_new' => 27, 'water_price' => 20000];
        $this->putJson(route('owner.contracts.terminate', $contract), $payload)->assertStatus(422);
        $payload['electricity_new'] = 150;
        $this->putJson(route('owner.contracts.terminate', $contract), $payload)->assertOk()->assertJsonPath('data.utility_total', 75000);
        $this->assertEquals(140, $contract->fresh()->termination->electricity_old);
    }

    public function test_termination_invoice_can_be_paid_using_existing_qr_flow(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $tenant = $contract->tenant;
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-27', 'electricity_new' => 115, 'electricity_price' => 3500, 'water_new' => 22, 'water_price' => 20000];
        $this->actingAs($owner)->putJson(route('owner.contracts.terminate', $contract), $payload)->assertOk();
        $invoice = $contract->fresh()->termination->utilityInvoice;
        $this->assertEquals(92500, $invoice->total);
        $this->assertSame('unpaid', $invoice->status);
        $this->assertSame(2, $invoice->items()->count());
        $this->assertDatabaseHas('notifications', ['user_id' => $tenant->id, 'reference_type' => 'invoice', 'reference_id' => $invoice->id]);
        $this->actingAs($tenant)->get(route('tenant.invoices.show', $invoice))->assertOk();
        $qr = $this->postJson(route('tenant.invoices.pay_qr', $invoice))->assertOk()->assertJsonPath('data.amount', 92500)->json('data');
        $this->assertStringContainsString('amount=92500', $qr['qr_url']);
        $this->postJson(route('tenant.invoices.pay_qr', $invoice))->assertOk()->assertJsonPath('data.payment_id', $qr['payment_id']);
        $this->actingAs($tenant)->postJson(route('tenant.invoices.simulate_sepay', $invoice))->assertOk();
        $this->assertSame('paid', $invoice->fresh()->status);
        $this->actingAs($owner);
        $this->putJson(route('owner.contracts.terminate', $contract), $payload)->assertStatus(422);
        $this->assertSame(1, Invoice::where('contract_id', $contract->id)->count());
    }

    public function test_zero_termination_utilities_do_not_create_invoice(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $payload = ['termination_type' => 'early', 'return_date' => '2026-11-27', 'electricity_new' => 100, 'electricity_price' => 3500, 'water_new' => 20, 'water_price' => 20000];
        $this->actingAs($owner)->putJson(route('owner.contracts.terminate', $contract), $payload)->assertOk();
        $this->assertNull($contract->fresh()->termination->utility_invoice_id);
        $this->assertSame(0, Invoice::where('contract_id', $contract->id)->count());
    }

    public function test_roommate_identity_and_phone_must_be_unique_within_contract(): void
    {
        [$owner, $room, $contract] = $this->setupContract();
        $room->update(['max_people' => 5]);
        $contract->tenant->update(['identity_card' => '012345678901', 'phone' => '0912345678']);
        $url = route('owner.contracts.members', $contract);
        $member = ['name' => 'Người ở cùng', 'identity_card' => '123456789012', 'phone' => '0987654321'];
        $this->actingAs($owner)->postJson($url, $member + ['action' => 'add'])->assertCreated();
        $this->postJson($url, array_replace($member, ['phone' => '0900000001']))->assertUnprocessable()->assertJsonValidationErrors('identity_card');
        $this->postJson($url, array_replace($member, ['identity_card' => '123456789013']))->assertUnprocessable()->assertJsonValidationErrors('phone');
        $this->postJson($url, array_replace($member, ['identity_card' => '012345678901', 'phone' => '0900000001']))->assertUnprocessable()->assertJsonValidationErrors('identity_card');
        $this->postJson($url, array_replace($member, ['identity_card' => '123456789013', 'phone' => '0912345678']))->assertUnprocessable()->assertJsonValidationErrors('phone');
        $this->postJson($url, ['name' => 'Người thứ ba', 'identity_card' => '123456789014'])->assertCreated();
        $this->assertSame(2, $contract->members()->count());
    }
}
