<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TV3NegativeRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_tv3_negative_business_rules(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Nha tro A', 'type' => 'rooming_house',
            'address' => '1 Duong A', 'district' => 'Quan 1', 'ward' => 'Phuong 1',
        ]);
        $room = Room::query()->create([
            'property_id' => $property->id, 'name' => 'Phong 101',
            'area' => 25, 'price' => 3000000, 'deposit' => 3000000, 'max_people' => 2,
        ]);

        $this->actingAs($owner);

        $contract = $this->postJson('/owner/contracts', [
            'room_id' => $room->id, 'tenant_id' => $tenant->id,
            'start_date' => '2026-01-01', 'end_date' => '2026-12-31',
            'rent' => 3000000, 'deposit' => 3000000, 'status' => 'active',
        ])->assertCreated()->json('data');

        // Phòng đã có HĐ active -> tạo nữa phải 422
        $this->postJson('/owner/contracts', [
            'room_id' => $room->id, 'tenant_id' => $tenant->id,
            'start_date' => '2026-03-01', 'end_date' => '2026-12-31',
            'rent' => 3000000, 'deposit' => 3000000,
        ])->assertStatus(422);

        // max_people = 2 -> chỉ thêm được 1 người ở cùng
        $this->postJson("/owner/contracts/{$contract['id']}/members", [
            'name' => 'Nguoi 1', 'identity_card' => 'ID-001',
        ])->assertCreated();
        $this->postJson("/owner/contracts/{$contract['id']}/members", [
            'name' => 'Nguoi 2', 'identity_card' => 'ID-002',
        ])->assertStatus(422);

        // Trùng CCCD -> 422
        $this->postJson("/owner/contracts/{$contract['id']}/members", [
            'name' => 'Nguoi 1 copy', 'identity_card' => 'ID-001',
        ])->assertStatus(422);

        // Chỉ số mới < cũ -> 422
        $this->postJson('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-02-01',
            'electricity_old' => 100, 'electricity_new' => 50,
            'electricity_price' => 3500,
            'water_old' => 10, 'water_new' => 12, 'water_price' => 15000,
        ])->assertStatus(422);

        // Trùng room+month -> 422
        $this->postJson('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-03-01',
            'electricity_new' => 120, 'electricity_price' => 3500,
            'water_new' => 15, 'water_price' => 15000,
        ])->assertCreated();
        $this->postJson('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-03-01',
            'electricity_new' => 130, 'electricity_price' => 3500,
            'water_new' => 16, 'water_price' => 15000,
        ])->assertStatus(422);

        // Khấu trừ vượt cọc -> 422
        $this->putJson("/owner/contracts/{$contract['id']}/terminate", [
            'deduction_amount' => 99999999, 'return_date' => '2026-12-31',
        ])->assertStatus(422);
    }

    public function test_tv3_web_forms_create_and_update(): void
    {
        config()->set('app.demo_guest', true); // bật demo mode cho guest test
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Nha tro A', 'type' => 'rooming_house',
            'address' => '1 Duong A', 'district' => 'Quan 1', 'ward' => 'Phuong 1',
        ]);
        $room = Room::query()->create([
            'property_id' => $property->id, 'name' => 'Phong 101',
            'area' => 25, 'price' => 3000000, 'deposit' => 3000000, 'max_people' => 2,
        ]);

        // DEMO MODE: chưa đăng nhập vẫn Lưu được trên web (dùng owner đầu tiên).
        $this->post('/owner/services', [
            'name' => 'Dịch vụ demo', 'unit' => 'tháng', 'price' => 10000,
        ])->assertRedirect(route('owner.services.index'));
        $this->assertDatabaseHas('services', ['owner_id' => $owner->id, 'name' => 'Dịch vụ demo']);
        $this->post('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-09-01',
            'electricity_new' => 100, 'electricity_price' => 3500,
            'water_new' => 20, 'water_price' => 15000,
        ])->assertRedirect(route('owner.utilities.index'));
        $this->assertDatabaseHas('utility_readings', ['room_id' => $room->id]);

        // JSON không đăng nhập vẫn 401.
        $this->postJson('/owner/services', [
            'name' => 'X', 'unit' => 'tháng', 'price' => 1,
        ])->assertUnauthorized();

        // Login bằng form thật rồi Lưu phải thành công.
        $this->post('/login', ['email' => $owner->email, 'password' => 'password'])
            ->assertRedirect(route('landlord.home'));
        $this->assertAuthenticatedAs($owner);

        // Trang Thêm dịch vụ mở được (trước đây route không tồn tại)
        $this->get('/owner/services/create')->assertSuccessful();

        // Submit form web tạo dịch vụ -> redirect về index
        $this->post('/owner/services', [
            'name' => 'Vệ sinh', 'unit' => 'tháng', 'price' => 50000,
        ])->assertRedirect(route('owner.services.index'));
        $this->assertDatabaseHas('services', ['owner_id' => $owner->id, 'name' => 'Vệ sinh']);
        $service = \App\Models\Service::query()->where('owner_id', $owner->id)->first();

        // Trang Sửa load đúng dữ liệu
        $this->get("/owner/services/{$service->id}")->assertSuccessful()->assertSee('Vệ sinh');

        // Submit form web sửa dịch vụ -> redirect về index
        $this->put("/owner/services/{$service->id}", [
            'name' => 'Vệ sinh', 'unit' => 'tháng', 'price' => 60000, 'status' => 'active',
        ])->assertRedirect(route('owner.services.index'));

        // Trang ghi điện nước mở được với danh sách phòng
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Nha tro A', 'type' => 'rooming_house',
            'address' => '1 Duong A', 'district' => 'Quan 1', 'ward' => 'Phuong 1',
        ]);
        $room = Room::query()->create([
            'property_id' => $property->id, 'name' => 'Phong 101',
            'area' => 25, 'price' => 3000000, 'deposit' => 3000000, 'max_people' => 2,
        ]);
        $this->get('/owner/utilities/create')->assertSuccessful()->assertSee('Phong 101');

        // Submit form web ghi chỉ số -> redirect về index
        $this->post('/owner/utilities', [
            'room_id' => $room->id, 'month' => '2026-09-01',
            'electricity_new' => 100, 'electricity_price' => 3500,
            'water_new' => 20, 'water_price' => 15000,
        ])->assertRedirect(route('owner.utilities.index'));
        $this->assertDatabaseHas('utility_readings', ['room_id' => $room->id]);
    }

    public function test_tv3_web_contract_store_and_members(): void
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Nha tro A', 'type' => 'rooming_house',
            'address' => '1 Duong A', 'district' => 'Quan 1', 'ward' => 'Phuong 1',
        ]);
        $room = Room::query()->create([
            'property_id' => $property->id, 'name' => 'Phong 101',
            'area' => 25, 'price' => 3000000, 'deposit' => 3000000, 'max_people' => 3,
        ]);
        // Yêu cầu thuê do người thuê gửi tới.
        \Illuminate\Support\Facades\DB::table('viewing_appointments')->insert([
            'room_id' => $room->id, 'tenant_id' => $tenant->id,
            'appointment_date' => '2026-09-20', 'appointment_time' => '09:00:00',
            'phone' => '0900000000', 'message' => 'Muon thue', 'status' => 'pending',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->actingAs($owner);

        // Trang tạo HĐ hiện giá phòng + yêu cầu thuê từ DB.
        $this->get('/owner/contracts/create')
            ->assertSuccessful()
            ->assertSee('data-price=', false)
            ->assertSee('Muon thue')
            ->assertSee('Tổng quan');

        // Submit form web tạo HĐ -> redirect về danh sách.
        $this->post('/owner/contracts', [
            'room_id' => $room->id, 'tenant_id' => $tenant->id,
            'start_date' => '2026-01-01', 'end_date' => '2026-12-31',
            'rent' => 3000000, 'deposit' => 3000000, 'status' => 'active',
        ])->assertRedirect(route('owner.contracts.index'));
        $contract = \App\Models\Contract::query()->where('room_id', $room->id)->first();
        $this->assertNotNull($contract);

        // Trang chi tiết có form thêm người ở cùng.
        $this->get("/owner/contracts/{$contract->id}")->assertSuccessful()->assertSee('Người ở cùng');

        // Thêm người ở cùng qua web -> quay về chi tiết.
        $this->post("/owner/contracts/{$contract->id}/members", [
            'name' => 'Nguoi o cung', 'identity_card' => 'ID-001', 'phone' => '0911111111',
        ])->assertRedirect(route('owner.contracts.show', $contract));
        $this->assertDatabaseHas('contract_members', ['contract_id' => $contract->id, 'identity_card' => 'ID-001']);
        $member = \App\Models\ContractMember::query()->where('contract_id', $contract->id)->first();

        // Vượt quá số người -> quay về kèm lỗi (không 500).
        $this->post("/owner/contracts/{$contract->id}/members", [
            'name' => 'Nguoi 2', 'identity_card' => 'ID-002',
        ]);
        $this->post("/owner/contracts/{$contract->id}/members", [
            'name' => 'Nguoi 3', 'identity_card' => 'ID-003',
        ])->assertSessionHasErrors('member');

        // Xóa người ở cùng qua web.
        $this->post("/owner/contracts/{$contract->id}/members", [
            'action' => 'remove', 'member_id' => $member->id,
        ])->assertRedirect(route('owner.contracts.show', $contract));
        $this->assertDatabaseMissing('contract_members', ['id' => $member->id]);

        // Trang "Thêm người ở cùng" liệt kê hợp đồng thật, form submit được.
        $this->get('/owner/tenants/create')
            ->assertSuccessful()
            ->assertSee('Thêm người ở cùng')
            ->assertSee($contract->contract_code)
            ->assertSee('Tổng quan');
        $this->post("/owner/contracts/{$contract->id}/members", [
            'contract_id' => $contract->id,
            'name' => 'Nguoi tu trang them', 'identity_card' => 'ID-999',
        ])->assertRedirect(route('owner.contracts.show', $contract));
        $this->assertDatabaseHas('contract_members', ['contract_id' => $contract->id, 'identity_card' => 'ID-999']);
    }
}
