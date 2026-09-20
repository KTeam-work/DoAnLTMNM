<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LandlordTenantTaskTest extends TestCase
{
    use RefreshDatabase;

    private function makeOwnerProperty(string $ownerName = 'Owner A'): array
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Nha tro ' . $ownerName, 'type' => 'rooming_house',
            'address' => '1 Duong A', 'district' => 'Quan 1', 'ward' => 'Phuong 1',
        ]);
        $room = Room::query()->create([
            'property_id' => $property->id, 'name' => 'Phong 101',
            'area' => 25, 'price' => 3000000, 'deposit' => 3000000, 'max_people' => 2,
        ]);

        return [$owner, $property, $room];
    }

    private function makeContract(User $owner, User $tenant, Room $room): Contract
    {
        return Contract::query()->create([
            'contract_code' => 'HD-' . $room->id . '-' . $tenant->id,
            'room_id' => $room->id, 'owner_id' => $owner->id, 'tenant_id' => $tenant->id,
            'start_date' => '2026-01-01', 'end_date' => '2026-12-31',
            'rent' => 3000000, 'deposit' => 3000000, 'payment_cycle' => 'monthly',
            'status' => 'active', 'signed_at' => now(),
        ]);
    }

    public function test_owner_cannot_touch_other_owner_rooms(): void
    {
        [$ownerA] = $this->makeOwnerProperty('A');
        [$ownerB, , $roomB] = $this->makeOwnerProperty('B');

        $this->actingAs($ownerA);

        // Ghi chỉ số cho phòng của owner khác -> 404.
        $this->postJson('/owner/utilities', [
            'room_id' => $roomB->id, 'month' => '2026-09-01',
            'electricity_new' => 10, 'electricity_price' => 3500,
            'water_new' => 5, 'water_price' => 15000,
        ])->assertNotFound();

        // Sửa dịch vụ của owner khác -> 404.
        $serviceB = $ownerB->services()->create(['name' => 'X', 'unit' => 'tháng', 'price' => 1, 'status' => 'active']);
        $this->putJson("/owner/services/{$serviceB->id}", ['price' => 2])->assertNotFound();
    }

    public function test_guest_json_utility_endpoints_require_login(): void
    {
        $this->postJson('/owner/utilities', [])->assertUnauthorized();
        $this->getJson('/owner/utilities')->assertUnauthorized();
    }

    public function test_tenant_cannot_view_other_tenant_contract(): void
    {
        [$owner, , $room] = $this->makeOwnerProperty();
        $tenant1 = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $tenant2 = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $contract = $this->makeContract($owner, $tenant1, $room);

        // Web: tenant khác xem -> 404.
        $this->actingAs($tenant2)->get("/tenant/contracts/{$contract->id}")->assertNotFound();
        // JSON: tenant khác xem -> 404.
        $this->actingAs($tenant2)->getJson("/tenant/contracts/{$contract->id}")->assertNotFound();
        // Chính chủ xem được cả web lẫn JSON.
        $this->actingAs($tenant1)->get("/tenant/contracts/{$contract->id}")->assertSuccessful();
        $this->actingAs($tenant1)->getJson("/tenant/contracts/{$contract->id}")->assertOk();
    }

    public function test_tenant_creates_request_attached_to_own_contract(): void
    {
        config()->set('app.demo_guest', true);
        [$owner, , $room] = $this->makeOwnerProperty();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $contract = $this->makeContract($owner, $tenant, $room);
        $this->actingAs($tenant);

        // Form tạo có liệt kê hợp đồng của mình.
        $this->get('/tenant/maintenance/create')->assertSuccessful()->assertSee($contract->contract_code);

        // Gửi yêu cầu -> lưu DB đúng tenant + contract + pending, về trang chi tiết.
        $response = $this->post('/tenant/maintenance/store', [
            'contract_id' => $contract->id,
            'category' => 'dien_nuoc',
            'title' => 'Vòi nước bị rỉ',
            'description' => 'Vòi lavabo chảy liên tục.',
            'priority' => 'high',
        ]);
        $created = \App\Models\MaintenanceRequest::query()->latest('id')->first();
        $this->assertNotNull($created);
        $response->assertRedirect(route('tenant.maintenance.show', $created->id));
        $this->assertDatabaseHas('maintenance_requests', [
            'id' => $created->id,
            'contract_id' => $contract->id,
            'tenant_id' => $tenant->id,
            'room_id' => $room->id,
            'status' => 'pending',
        ]);

        // Danh sách + chi tiết hiện dữ liệu thật.
        $this->get('/tenant/maintenance')->assertSuccessful()->assertSee('Vòi nước bị rỉ');
        $this->get("/tenant/maintenance/{$created->id}")->assertSuccessful()->assertSee($contract->contract_code);
    }

    public function test_tenant_cannot_create_request_for_other_tenant_contract(): void
    {
        [$owner, , $room] = $this->makeOwnerProperty();
        $tenant1 = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $tenant2 = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $contract = $this->makeContract($owner, $tenant1, $room);

        $this->actingAs($tenant2)->post('/tenant/maintenance/store', [
            'contract_id' => $contract->id,
            'title' => 'Giả mạo',
            'description' => 'Không phải HĐ của mình.',
            'priority' => 'low',
        ])->assertSessionHasErrors('contract_id');
        $this->assertDatabaseMissing('maintenance_requests', ['title' => 'Giả mạo']);

        // Thiếu tiêu đề -> lỗi validation hiển thị lại form.
        $this->actingAs($tenant1)->post('/tenant/maintenance/store', [
            'contract_id' => $contract->id,
            'description' => 'Thiếu tiêu đề.',
            'priority' => 'low',
        ])->assertSessionHasErrors('title');
    }

    public function test_tenant_can_upload_request_image(): void
    {
        Storage::fake('public');
        [$owner, , $room] = $this->makeOwnerProperty();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $contract = $this->makeContract($owner, $tenant, $room);

        $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==');
        $tmpPath = sys_get_temp_dir() . '/tran_test_' . uniqid() . '.png';
        file_put_contents($tmpPath, $png);
        $file = new UploadedFile($tmpPath, 'tran.png', 'image/png', null, true);

        $this->actingAs($tenant)->post('/tenant/maintenance/store', [
            'contract_id' => $contract->id,
            'title' => 'Trần nhà thấm',
            'description' => 'Có vết ố vàng.',
            'priority' => 'medium',
            'image' => $file,
        ])->assertRedirect();

        $created = \App\Models\MaintenanceRequest::query()->latest('id')->first();
        $this->assertNotNull($created->image);
        Storage::disk('public')->assertExists($created->image);
    }

    public function test_utility_index_filters_by_room_and_month(): void
    {
        config()->set('app.demo_guest', true);
        [$owner, , $room] = $this->makeOwnerProperty();
        $this->actingAs($owner);

        $this->postJson('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-08-01',
            'electricity_new' => 50, 'electricity_price' => 3500,
            'water_new' => 5, 'water_price' => 15000,
        ])->assertCreated();
        $this->postJson('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-09-01',
            'electricity_new' => 60, 'electricity_price' => 3500,
            'water_new' => 6, 'water_price' => 15000,
        ])->assertCreated();

        // Lọc đúng tháng 08 -> chỉ thấy chỉ số cũ 50, không thấy 60.
        $this->get('/owner/utilities?month=2026-08')->assertSuccessful()->assertSee('50')->assertDontSee('>60<');
    }

    public function test_utility_validation_errors_return_to_form(): void
    {
        [$owner, , $room] = $this->makeOwnerProperty();
        $this->actingAs($owner);

        // Chỉ số mới < cũ -> quay về form kèm lỗi.
        $this->post('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-09-01',
            'electricity_old' => 100, 'electricity_new' => 50,
            'electricity_price' => 3500,
            'water_old' => 1, 'water_new' => 2, 'water_price' => 15000,
        ])->assertSessionHasErrors();
    }

    public function test_overview_link_and_guest_rooms_properties_access(): void
    {
        config()->set('app.demo_guest', true);
        [$owner] = $this->makeOwnerProperty();

        // Link Tổng quan trên trang owner trỏ đúng /owner/home (không còn 404 /landlord).
        $this->get('/owner/services')
            ->assertSuccessful()
            ->assertSee('/owner/home', false);

        // Guest vào quản lý nhà/phòng không cần login (demo mode).
        $this->get('/owner/properties')->assertSuccessful();
        $this->get('/owner/rooms')->assertSuccessful();
        $this->get('/owner/rooms/create')->assertSuccessful();
    }
}
