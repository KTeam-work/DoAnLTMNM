<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\MaintenanceRequest;
use App\Models\Payment;
use App\Models\Property;
use App\Models\RentalPost;
use App\Models\Room;
use App\Models\User;
use App\Models\ViewingAppointment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompleteRentalJourneyTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_admin_and_tenant_complete_a_rental_journey(): void
    {
        Http::preventStrayRequests();
        Http::fake();
        config(['mail.default' => 'array', 'services.resend.key' => null, 'app.demo_guest' => false]);
        Storage::fake('public');
        $this->travelTo(\Carbon\Carbon::parse('2026-11-26 09:00:00'));

        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà thử nghiệm', 'type' => 'rooming_house', 'address' => 'Đường A', 'district' => 'Quận 1', 'ward' => 'Phường 1']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'Phòng thử nghiệm', 'room_code' => 'TEST-101', 'area' => 20, 'price' => 1000000, 'deposit' => 500000, 'max_people' => 2, 'status' => 'available']);
        $image = UploadedFile::fake()->createWithContent('room.png', base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO+/aN8AAAAASUVORK5CYII='));

        $this->actingAs($owner)->post(route('owner.rental-posts.store'), ['room_id' => $room->id, 'title' => 'Phòng thử nghiệm', 'description' => 'Mô tả phòng', 'status' => 'pending', 'images' => [$image]])->assertSessionHasNoErrors()->assertRedirect();
        $post = RentalPost::where('room_id', $room->id)->firstOrFail();
        $this->assertSame('pending', $post->status);

        $this->actingAs($admin)->patch(route('admin.rental-posts.update-status', $post), ['status' => 'published'])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('published', $post->fresh()->status);
        $this->actingAs($tenant)->get(route('rooms.index'))->assertOk()->assertSee('Phòng thử nghiệm');
        $this->post(route('appointments.store'), ['room_id' => $room->id, 'appointment_date' => '2026-11-27', 'appointment_time' => '09:00', 'phone' => '0901234567'])->assertSessionHasNoErrors()->assertRedirect();
        $appointment = ViewingAppointment::where('room_id', $room->id)->firstOrFail();
        $this->actingAs($owner)->put(route('owner.appointments.confirm', $appointment->id))->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('confirmed', $appointment->fresh()->status);

        $this->post(route('owner.contracts.store'), ['tenant_mode' => 'existing', 'room_id' => $room->id, 'tenant_id' => $tenant->id, 'start_date' => '2026-11-26', 'end_date' => '2027-11-26', 'rent' => 1000000, 'deposit' => 500000, 'payment_cycle' => 'monthly', 'status' => 'active'])->assertSessionHasNoErrors()->assertRedirect();
        $contract = Contract::where('room_id', $room->id)->firstOrFail();
        $this->assertSame('active', $contract->status);
        $this->assertSame('rented', $room->fresh()->status);
        $this->putJson(route('owner.contracts.initial-utilities', $contract), ['initial_electricity_reading' => 100, 'initial_water_reading' => 20])->assertOk();

        $this->travelTo(\Carbon\Carbon::parse('2026-12-26 09:00:00'));
        $this->postJson(route('owner.utilities.store'), ['room_id' => $room->id, 'month' => '2026-12', 'electricity_new' => 140, 'electricity_price' => 3500, 'water_new' => 25, 'water_price' => 20000])->assertCreated();
        $this->postJson(route('owner.invoices.store'), ['month' => '2026-12'])->assertCreated();
        $invoice = Invoice::where('contract_id', $contract->id)->firstOrFail();
        $this->assertEquals(1240000, $invoice->total);

        $this->actingAs($tenant)->post(route('tenant.payment.store'), ['invoice_id' => $invoice->id, 'method' => 'cash'])->assertSessionHasNoErrors()->assertRedirect();
        $payment = Payment::where('invoice_id', $invoice->id)->firstOrFail();
        $this->actingAs($owner)->put(route('owner.payments.confirm', $payment->id))->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('paid', $invoice->fresh()->status);

        $this->actingAs($tenant)->post(route('tenant.maintenance.store'), ['contract_id' => $contract->id, 'title' => 'Hỏng đèn', 'description' => 'Đèn không sáng', 'priority' => 'low'])->assertSessionHasNoErrors()->assertRedirect();
        $ticket = MaintenanceRequest::where('contract_id', $contract->id)->firstOrFail();
        $this->actingAs($owner)->put(route('owner.maintenance.status', $ticket->id), ['status' => 'processing'])->assertSessionHasNoErrors()->assertRedirect();
        $this->put(route('owner.maintenance.status', $ticket->id), ['status' => 'completed', 'damage_type' => 'landlord'])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('completed', $ticket->fresh()->status);

        $this->travelTo(\Carbon\Carbon::parse('2027-11-26 09:00:00'));
        $this->put(route('owner.contracts.terminate', $contract), ['termination_type' => 'normal', 'return_date' => '2027-11-26', 'deduction_amount' => 0])->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('terminated', $contract->fresh()->status);
        $this->assertSame('available', $room->fresh()->status);
        $this->assertSame('published', $post->fresh()->status);
        $this->actingAs($tenant)->get(route('rooms.index'))->assertOk()->assertSee('Phòng thử nghiệm');
        $this->travelBack();
    }
}
