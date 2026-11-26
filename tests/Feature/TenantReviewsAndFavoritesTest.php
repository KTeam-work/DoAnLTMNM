<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Favorite;
use App\Models\Property;
use App\Models\RentalPost;
use App\Models\Review;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantReviewsAndFavoritesTest extends TestCase
{
    use RefreshDatabase;

    private function setupRoom(): array
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà thử', 'type' => 'rooming_house', 'address' => 'Đường A', 'district' => 'Quận 1', 'ward' => 'P1']);
        $room = Room::create(['property_id' => $property->id, 'name' => 'Phòng thử', 'room_code' => 'TEST-01', 'area' => 20, 'price' => 1000000, 'deposit' => 0, 'max_people' => 2, 'status' => 'available']);
        Contract::create(['contract_code' => 'TEST-HD', 'owner_id' => $owner->id, 'tenant_id' => $tenant->id, 'room_id' => $room->id, 'start_date' => '2026-01-01', 'end_date' => '2027-01-01', 'rent' => 1000000, 'deposit' => 0, 'payment_cycle' => 'monthly', 'status' => 'active']);
        return [$owner, $tenant, $property, $room];
    }

    public function test_review_response_identifies_updates_and_reports_true_count(): void
    {
        [, $tenant, , $room] = $this->setupRoom();
        $first = $this->actingAs($tenant)->postJson(route('tenant.reviews.store'), ['room_id' => $room->id, 'rating' => 5, 'comment' => 'Tốt'])
            ->assertCreated()->assertJsonPath('created', true)->assertJsonPath('total', 1);
        $id = $first->json('review.id');
        $this->postJson(route('tenant.reviews.store'), ['room_id' => $room->id, 'rating' => 3, 'comment' => '<b>Nội dung thử</b>'])
            ->assertOk()->assertJsonPath('created', false)->assertJsonPath('total', 1)->assertJsonPath('review.id', $id);
        $this->assertSame(1, Review::count());
        $this->get(route('tenant.reviews.index'))->assertOk()->assertSee('data-review-id="'.$id.'"', false)->assertSee('&lt;b&gt;Nội dung thử&lt;/b&gt;', false);
    }

    public function test_favorites_choose_published_post_then_room_then_property_image(): void
    {
        [, $tenant, $property, $room] = $this->setupRoom();
        Favorite::create(['user_id' => $tenant->id, 'room_id' => $room->id]);
        $property->images()->create(['image_path' => 'properties/house.jpg', 'is_thumbnail' => true]);
        $room->update(['images' => json_encode(['rooms/room.jpg'])]);
        $post = RentalPost::create(['room_id' => $room->id, 'title' => 'Tin phòng', 'description' => 'Mô tả', 'status' => 'published', 'published_at' => now()]);
        $post->images()->create(['image_path' => 'posts/other.jpg', 'is_thumbnail' => false, 'sort_order' => 0]);
        $post->images()->create(['image_path' => 'posts/thumb.jpg', 'is_thumbnail' => true, 'sort_order' => 1]);

        $this->actingAs($tenant)->get(route('favorites.index'))->assertOk()->assertSee('storage/posts/thumb.jpg')->assertDontSee('storage/posts/other.jpg');
        $post->update(['status' => 'hidden']);
        $this->get(route('favorites.index'))->assertOk()->assertSee('storage/rooms/room.jpg')->assertDontSee('storage/posts/thumb.jpg');
        $room->update(['images' => null]);
        $this->get(route('favorites.index'))->assertOk()->assertSee('storage/properties/house.jpg');
        $property->images()->delete();
        $this->get(route('favorites.index'))->assertOk()->assertSee('Chưa có ảnh phòng');
    }
}
