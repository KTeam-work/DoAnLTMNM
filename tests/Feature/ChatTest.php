<?php

namespace Tests\Feature;

use App\Events\ChatMessageSent;
use App\Models\Contract;
use App\Models\Conversation;
use App\Models\Property;
use App\Models\RentalPost;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ChatTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_can_open_chat_from_own_contract_even_when_room_is_occupied(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $stranger = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Q1', 'ward' => 'P1', 'status' => 'active']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'A1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1, 'status' => 'rented']);
        $contract = Contract::create(['contract_code' => 'CTR-CHAT', 'owner_id' => $owner->id, 'tenant_id' => $tenant->id, 'room_id' => $room->id, 'start_date' => now(), 'end_date' => now()->addYear(), 'rent' => 1000000, 'deposit' => 0, 'status' => 'active']);

        $this->post(route('chat.start-contract', $contract))->assertRedirect(route('login'));
        $this->actingAs($stranger)->post(route('chat.start-contract', $contract))->assertNotFound();
        $this->assertDatabaseCount('conversations', 0);
        $this->actingAs($tenant)->get(route('tenant.contracts.show', $contract))->assertOk()->assertSee('Nhắn tin cho chủ trọ');
        $this->post(route('chat.start-contract', $contract))->assertRedirect();
        $conversation = Conversation::firstOrFail();
        $this->post(route('chat.start-contract', $contract))->assertRedirect(route('chat.show', $conversation));
        $this->assertDatabaseCount('conversations', 1);
    }

    public function test_participants_can_chat_and_other_users_cannot_access_it(): void
    {
        Event::fake([ChatMessageSent::class]);
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $stranger = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Q1', 'ward' => 'P1', 'status' => 'active']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'A1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1, 'status' => 'available']);
        RentalPost::create(['room_id' => $room->id, 'title' => 'Phòng A1', 'description' => 'Phòng đẹp', 'status' => 'published']);
        $this->post(route('chat.start', $room))->assertRedirect(route('login'));
        $this->actingAs($tenant)->post(route('chat.start', $room))->assertRedirect();
        $conversation = Conversation::firstOrFail();
        $this->post(route('chat.start', $room))->assertRedirect(route('chat.show', $conversation));
        $this->assertDatabaseCount('conversations', 1);
        $this->postJson(route('chat.send', $conversation), ['body' => 'Phòng còn không?'])->assertCreated();
        Event::assertDispatched(ChatMessageSent::class);
        $this->postJson(route('chat.send', $conversation), ['body' => '   '])->assertUnprocessable();
        $this->actingAs($stranger)->get(route('chat.show', $conversation))->assertNotFound();
        $this->postJson(route('chat.send', $conversation), ['body' => 'Xem trộm'])->assertNotFound();
        $this->post(route('chat.read', $conversation))->assertNotFound();
        $this->actingAs($owner)->get(route('chat.index'))->assertOk()->assertSee('A1');
        $this->get(route('chat.show', $conversation))->assertOk()->assertSee('Phòng còn không?');
        $this->assertNotNull($conversation->messages()->first()->read_at);
        $this->postJson(route('chat.send', $conversation), ['body' => 'Còn nhé'])->assertCreated();
        $this->assertDatabaseCount('chat_messages', 2);
    }
}
