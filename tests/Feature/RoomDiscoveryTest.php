<?php

namespace Tests\Feature;

use App\Models\Property;
use App\Models\RentalPost;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RoomDiscoveryTest extends TestCase
{
    use RefreshDatabase;

    private function room(Property $property, string $name, string $postStatus = 'published'): Room
    {
        $room = Room::create(['property_id' => $property->id, 'name' => $name, 'area' => 20, 'price' => 1000000, 'deposit' => 500000, 'max_people' => 2, 'status' => 'available']);
        RentalPost::create(['room_id' => $room->id, 'title' => $name, 'description' => 'Mô tả', 'status' => $postStatus]);
        return $room;
    }

    private function png(int $width, int $height): string
    {
        $chunk = static function (string $type, string $data): string {
            return pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        };
        $scanline = "\x00" . str_repeat("\x44\x88\xbb", $width);
        return "\x89PNG\r\n\x1a\n" . $chunk('IHDR', pack('NNCCCCC', $width, $height, 8, 2, 0, 0, 0)) . $chunk('IDAT', gzcompress(str_repeat($scanline, $height))) . $chunk('IEND', '');
    }

    public function test_visitors_compare_up_to_three_public_rooms_and_remove_unavailable_ones(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'Đường A', 'ward' => 'P1', 'district' => 'Q1', 'status' => 'active']);
        $one = $this->room($property, 'Phòng Một'); $two = $this->room($property, 'Phòng Hai'); $three = $this->room($property, 'Phòng Ba'); $four = $this->room($property, 'Phòng Bốn');
        $hidden = $this->room($property, 'Phòng Ẩn', 'draft');
        $this->get(route('rooms.index'))->assertOk()->assertSee('Thêm vào so sánh')->assertSee('So sánh phòng');
        $this->post(route('rooms.compare.store', $hidden))->assertNotFound();
        foreach ([$one, $two, $three] as $room) $this->from(route('rooms.index'))->post(route('rooms.compare.store', $room))->assertRedirect();
        $this->from(route('rooms.index'))->post(route('rooms.compare.store', $four))->assertSessionHas('compare_error');
        $this->get(route('rooms.compare'))->assertOk()->assertSee('Phòng Một')->assertSee('Phòng Hai')->assertSee('Phòng Ba')->assertDontSee('Phòng Bốn');
        $two->update(['status' => 'rented']);
        $this->get(route('rooms.compare'))->assertOk()->assertDontSee('Phòng Hai')->assertSessionHas('compare_rooms', [$one->id, $three->id]);
        $this->from(route('rooms.compare'))->delete(route('rooms.compare.destroy', $one))->assertRedirect();
        $this->get(route('rooms.compare'))->assertOk()->assertDontSee('Phòng Một')->assertSee('Phòng Ba');
    }

    public function test_owner_can_upload_replace_and_remove_panorama_for_own_room(): void
    {
        Storage::fake('public');
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $other = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $property = Property::create(['owner_id' => $owner->id, 'name' => 'Nhà A', 'type' => 'rooming_house', 'address' => 'Đường A', 'ward' => 'P1', 'district' => 'Q1', 'status' => 'active']);
        $room = $this->room($property, 'Phòng Toàn Cảnh');
        $file = fn ($w, $h) => UploadedFile::fake()->createWithContent('view.png', $this->png($w, $h));
        $this->actingAs($other)->post(route('owner.rooms.panorama.store', $room), ['panorama' => $file(2, 1)])->assertNotFound();
        $this->actingAs($owner)->post(route('owner.rooms.panorama.store', $room), ['panorama' => $file(2, 2)])->assertSessionHasErrors('panorama');
        $this->post(route('owner.rooms.panorama.store', $room), ['panorama' => $file(2, 1)])->assertSessionHasNoErrors()->assertRedirect();
        $oldPath = $room->fresh()->panorama_path; Storage::disk('public')->assertExists($oldPath);
        $this->get(route('rooms.show', $room))->assertOk()->assertSee('Xem phòng 360°')->assertSee('storage/'.$oldPath, false);
        $this->post(route('owner.rooms.panorama.store', $room), ['panorama' => $file(4, 2)])->assertSessionHasNoErrors()->assertRedirect();
        Storage::disk('public')->assertMissing($oldPath); Storage::disk('public')->assertExists($room->fresh()->panorama_path);
        $this->delete(route('owner.rooms.panorama.destroy', $room))->assertRedirect();
        $this->assertNull($room->fresh()->panorama_path);
        $this->get(route('rooms.show', $room))->assertOk()->assertDontSee('Xem phòng 360°');
    }
}
