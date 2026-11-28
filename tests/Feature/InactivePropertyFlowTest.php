<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\Contract;
use App\Models\RentalPost;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InactivePropertyFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_inactive_property_blocks_new_business_but_keeps_existing_records(): void
    {
        Http::preventStrayRequests();
        Http::fake();
        config(['app.demo_guest' => false, 'mail.default' => 'array', 'services.resend.key' => null]);
        $this->travelTo(\Carbon\Carbon::parse('2026-11-27 10:00:00', 'Asia/Ho_Chi_Minh'));
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà đang kiểm tra', 'type' => 'rooming_house', 'address' => 'Đường A', 'ward' => 'P1', 'district' => 'Q1', 'status' => 'active']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'Phòng thử ngưng', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 2, 'status' => 'available']);
        $occupied = Room::create(['property_id' => $property->id, 'name' => 'Phòng đang thuê', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 2, 'status' => 'rented']);
        $contract = Contract::create(['contract_code' => 'HD-INACTIVE', 'room_id' => $occupied->id, 'owner_id' => $owner->id, 'tenant_id' => $tenant->id, 'start_date' => '2026-10-27', 'end_date' => '2027-10-27', 'rent' => 1000000, 'deposit' => 0, 'payment_cycle' => 'monthly', 'status' => 'active']);
        $post = RentalPost::create(['room_id' => $room->id, 'title' => 'Phòng thử ngưng', 'description' => 'Mô tả', 'status' => 'published']);
        $this->actingAs($tenant)->get(route('rooms.index'))->assertOk()->assertSee('Phòng thử ngưng');
        $property->update(['status' => 'inactive']);
        $this->get(route('rooms.index'))->assertOk()->assertDontSee('Phòng thử ngưng');
        $this->get(route('rooms.show', $room->id))->assertNotFound();
        $this->get(route('tenant.dashboard'))->assertOk()->assertDontSee('Phòng thử ngưng');
        $this->post(route('appointments.store'), ['room_id' => $room->id, 'appointment_date' => '2026-11-28', 'appointment_time' => '09:00', 'phone' => '0901234567'])->assertSessionHasErrors('room_id');
        $this->actingAs($owner)->get(route('owner.rooms.create'))->assertOk()->assertSee('Nhà đang kiểm tra')->assertSee('Tạm ngưng');
        $this->get(route('owner.contracts.show', $contract))->assertOk()->assertSee('HD-INACTIVE');
        $this->assertSame('active', $contract->fresh()->status);
        $this->post(route('owner.rooms.store'), ['property_id' => $property->id, 'name' => 'A2', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 2])->assertRedirect(route('owner.rooms.index'));
        $newRoom = Room::query()->where('property_id', $property->id)->where('name', 'A2')->firstOrFail();
        $this->assertDatabaseMissing('rental_posts', ['room_id' => $newRoom->id]);
        $this->post(route('owner.rental-posts.store'), ['room_id' => $newRoom->id, 'title' => 'Tin phòng A2', 'description' => 'Mô tả', 'status' => 'draft'])->assertSessionHasErrors('room_id');
        $this->assertSame('published', $post->fresh()->status);
        $property->update(['status' => 'active']);
        $this->actingAs($tenant)->get(route('rooms.index'))->assertOk()->assertSee('Phòng thử ngưng')->assertDontSee('A2');
    }
}
