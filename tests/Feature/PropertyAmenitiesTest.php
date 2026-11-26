<?php

namespace Tests\Feature;

use App\Models\Amenity;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyAmenitiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_properties_manage_shared_names_independently_and_preserve_other_rooms(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $other = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $one = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Quận 1', 'ward' => 'P1']);
        $two = Property::create(['owner_id' => $other->id, 'name' => 'Nhà B', 'type' => 'rooming_house', 'address' => 'B', 'district' => 'Quận 1', 'ward' => 'P1']);
        $room = Room::create(['property_id' => $two->id, 'name' => 'B1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1]);
        $amenity = Amenity::create(['name' => 'Máy lạnh', 'status' => 'active']);
        $one->amenities()->attach($amenity, ['status' => 'active', 'description' => 'A']);
        $two->amenities()->attach($amenity, ['status' => 'active', 'description' => 'B']);
        $room->amenities()->attach($amenity);

        $this->actingAs($owner)->get(route('owner.amenities.index', ['property_id' => $one->id]))->assertOk()->assertSee('Máy lạnh')->assertDontSee('Nhà B');
        $this->actingAs($owner)->get(route('owner.amenities.index', ['property_id' => $two->id]))->assertNotFound();
        $this->put(route('owner.amenities.update', $amenity), ['property_id' => $one->id, 'name' => 'Điều hòa', 'description' => 'A mới', 'status' => 'inactive'])->assertRedirect();
        $this->assertDatabaseHas('property_amenities', ['property_id' => $two->id, 'amenity_id' => $amenity->id, 'description' => 'B', 'status' => 'active']);
        $this->assertDatabaseHas('room_amenities', ['room_id' => $room->id, 'amenity_id' => $amenity->id]);
        $this->delete(route('owner.amenities.destroy', $amenity), ['property_id' => $two->id])->assertNotFound();
        $this->assertDatabaseHas('room_amenities', ['room_id' => $room->id, 'amenity_id' => $amenity->id]);
    }

    public function test_owner_cannot_assign_amenity_from_another_property(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $one = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Quận 1', 'ward' => 'P1']);
        $two = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà B', 'type' => 'rooming_house', 'address' => 'B', 'district' => 'Quận 1', 'ward' => 'P1']);
        $amenity = Amenity::create(['name' => 'Máy lạnh', 'status' => 'active']);
        $two->amenities()->attach($amenity, ['status' => 'active']);
        $this->actingAs($owner)->post(route('owner.rooms.store'), [
            'property_id' => $one->id, 'name' => 'A1', 'area' => 20,
            'price' => 1000000, 'deposit' => 0, 'max_people' => 1, 'amenities' => [$amenity->id],
        ])->assertSessionHasErrors('amenities');
        $this->assertDatabaseMissing('rooms', ['property_id' => $one->id, 'name' => 'A1']);
    }

    public function test_renaming_and_deleting_an_amenity_only_changes_rooms_in_the_selected_property(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $one = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'A', 'district' => 'Quận 1', 'ward' => 'P1']);
        $two = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà B', 'type' => 'rooming_house', 'address' => 'B', 'district' => 'Quận 1', 'ward' => 'P1']);
        $roomA = Room::create(['property_id' => $one->id, 'name' => 'A1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1]);
        $roomB = Room::create(['property_id' => $two->id, 'name' => 'B1', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 1]);
        $amenity = Amenity::create(['name' => 'Máy lạnh', 'status' => 'active']);
        $one->amenities()->attach($amenity, ['status' => 'active']);
        $two->amenities()->attach($amenity, ['status' => 'active']);
        $roomA->amenities()->attach($amenity);
        $roomB->amenities()->attach($amenity);

        $this->actingAs($owner)->put(route('owner.amenities.update', $amenity), ['property_id' => $one->id, 'name' => 'Điều hòa', 'status' => 'active'])->assertRedirect();
        $renamed = Amenity::where('name', 'Điều hòa')->firstOrFail();
        $this->assertDatabaseHas('room_amenities', ['room_id' => $roomA->id, 'amenity_id' => $renamed->id]);
        $this->assertDatabaseHas('room_amenities', ['room_id' => $roomB->id, 'amenity_id' => $amenity->id]);

        $this->get(route('owner.rooms.create'))->assertOk()->assertSee('Máy lạnh');
        $this->get(route('owner.rooms.edit', $roomB))->assertOk()->assertSee('Máy lạnh');

        $this->delete(route('owner.amenities.destroy', $renamed), ['property_id' => $one->id])->assertRedirect();
        $this->assertDatabaseMissing('room_amenities', ['room_id' => $roomA->id, 'amenity_id' => $renamed->id]);
        $this->assertDatabaseHas('room_amenities', ['room_id' => $roomB->id, 'amenity_id' => $amenity->id]);
    }
}
