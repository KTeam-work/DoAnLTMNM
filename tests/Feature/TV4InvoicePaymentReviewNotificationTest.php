<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use App\Models\Property;
use App\Models\Room;
use App\Models\User;
use App\Models\UtilityReading;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TV4InvoicePaymentReviewNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function makeOwner(string $suffix = 'A'): array
    {
        $owner = User::factory()->create(['role' => 'owner', 'status' => 'active']);
        $property = Property::query()->create([
            'owner_id' => $owner->id, 'name' => 'Nha tro ' . $suffix, 'type' => 'rooming_house',
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
            'initial_electricity_reading' => 0, 'initial_water_reading' => 0,
        ]);
    }

    private function makeReading(Room $room, string $month = '2026-09-01'): UtilityReading
    {
        return UtilityReading::query()->create([
            'room_id' => $room->id, 'month' => $month,
            'electricity_old' => 10, 'electricity_new' => 60, 'electricity_price' => 3500,
            'water_old' => 2, 'water_new' => 7, 'water_price' => 15000,
        ]);
    }

    public function test_owner_generates_invoice_and_no_duplicates(): void
    {
        [$owner, , $room] = $this->makeOwner();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $this->makeContract($owner, $tenant, $room);
        $this->makeReading($room);
        $owner->services()->create(['name' => 'Internet', 'unit' => 'tháng', 'price' => 100000, 'status' => 'active']);

        $this->actingAs($owner);

        // Tạo hóa đơn tháng: 3000000 + 50*3500 + 5*15000 + 100000 = 3350000.
        $this->postJson('/owner/invoices', ['month' => '2026-09-01'])
            ->assertCreated()
            ->assertJsonPath('data.0.total', '3350000.00');
        $invoice = Invoice::query()->first();
        $this->assertNotNull($invoice);
        $this->assertEquals(4, $invoice->items()->count());
        $this->assertNotNull($invoice->utility_reading_id);

        // Tạo lại cùng kỳ -> báo không tạo được (không trùng).
        $this->postJson('/owner/invoices', ['month' => '2026-09-01'])->assertStatus(422);
        $this->assertEquals(1, Invoice::query()->count());

        // Tenant nhận thông báo hóa đơn mới.
        $this->assertDatabaseHas('notifications', [
            'user_id' => $tenant->id, 'type' => 'invoice', 'reference_id' => $invoice->id,
        ]);
    }

    public function test_tenant_payment_flow_and_owner_confirm(): void
    {
        [$owner, , $room] = $this->makeOwner();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $contract = $this->makeContract($owner, $tenant, $room);
        $reading = $this->makeReading($room);
        $this->actingAs($owner);
        $invoiceId = $this->postJson('/owner/invoices', ['month' => '2026-09-01'])->json('data.0.id');

        // Tenant khác không thanh toán hộ được.
        $other = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $this->actingAs($other)->post('/tenant/payment', [
            'invoice_id' => $invoiceId, 'method' => 'cash',
        ])->assertSessionHasErrors('invoice_id');

        // Tenant tạo thanh toán: số tiền lấy từ hóa đơn.
        $this->actingAs($tenant)->post('/tenant/payment', [
            'invoice_id' => $invoiceId, 'method' => 'bank_transfer', 'transaction_code' => 'MB123',
            'amount' => 1, // số tiền fake từ form phải bị bỏ qua
        ])->assertRedirect(route('tenant.invoices.show', $invoiceId));
        $payment = \App\Models\Payment::query()->first();
        $this->assertEquals((float) Invoice::find($invoiceId)->total, (float) $payment->amount);
        $this->assertEquals('pending', $payment->status);

        // Không tạo trùng khi đã có pending.
        $this->actingAs($tenant)->post('/tenant/payment', [
            'invoice_id' => $invoiceId, 'method' => 'cash',
        ])->assertSessionHasErrors('invoice_id');

        // Owner xác nhận -> paid + thông báo tenant.
        $this->actingAs($owner)->put("/owner/payments/{$payment->id}/confirm")
            ->assertRedirect();
        $this->assertEquals('paid', Invoice::find($invoiceId)->status);
        $this->assertEquals('success', $payment->fresh()->status);
        $this->assertDatabaseHas('notifications', ['user_id' => $tenant->id, 'type' => 'payment']);

        // Xác nhận lại -> lỗi, không đổi trạng thái.
        $this->actingAs($owner)->put("/owner/payments/{$payment->id}/confirm")
            ->assertSessionHasErrors('payment');

        // Hóa đơn đã paid không thanh toán tiếp được.
        $this->actingAs($tenant)->post('/tenant/payment', [
            'invoice_id' => $invoiceId, 'method' => 'cash',
        ])->assertSessionHasErrors('invoice_id');
    }

    public function test_payment_callback_idempotent(): void
    {
        [$owner, , $room] = $this->makeOwner();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $this->makeContract($owner, $tenant, $room);
        $this->makeReading($room);
        $this->actingAs($owner);
        $invoiceId = $this->postJson('/owner/invoices', ['month' => '2026-09-01'])->json('data.0.id');
        $total = (float) Invoice::find($invoiceId)->total;
        $this->actingAs($tenant)->post('/tenant/payment', ['invoice_id' => $invoiceId, 'method' => 'online']);
        $payment = \App\Models\Payment::query()->first();

        // Callback thành công lần 1 -> paid.
        $this->postJson('/tenant/payment/callback', [
            'payment_code' => $payment->payment_code, 'status' => 'success', 'amount' => $total,
        ])->assertOk();
        $this->assertEquals('paid', Invoice::find($invoiceId)->status);

        // Callback trùng -> không ghi 2 lần, vẫn 200.
        $this->postJson('/tenant/payment/callback', [
            'payment_code' => $payment->payment_code, 'status' => 'success', 'amount' => $total,
        ])->assertOk()->assertJsonPath('message', 'Already processed.');
        $this->assertEquals(1, \App\Models\Payment::query()->count());

        // Sai số tiền -> 422, không đổi trạng thái paid.
        $this->postJson('/tenant/payment/callback', [
            'payment_code' => $payment->payment_code, 'status' => 'failed', 'amount' => $total + 1,
        ])->assertStatus(422);
        $this->assertEquals('paid', Invoice::find($invoiceId)->status);
    }

    public function test_tenant_reviews_and_owner_manages(): void
    {
        [$owner, , $room] = $this->makeOwner();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $this->makeContract($owner, $tenant, $room);
        $otherRoom = Room::query()->create([
            'property_id' => $room->property_id, 'name' => 'Phong 102',
            'area' => 20, 'price' => 2000000, 'deposit' => 2000000, 'max_people' => 2,
        ]);

        // Không đánh giá phòng chưa thuê.
        $this->actingAs($tenant)->post('/tenant/reviews/store', [
            'room_id' => $otherRoom->id, 'rating' => 5,
        ])->assertSessionHasErrors('room_id');

        // Đánh giá hợp lệ -> lưu DB.
        $this->actingAs($tenant)->post('/tenant/reviews/store', [
            'room_id' => $room->id, 'rating' => 5, 'comment' => 'Rất tốt',
        ])->assertRedirect(route('tenant.reviews.index'));
        $this->assertDatabaseHas('reviews', ['room_id' => $room->id, 'tenant_id' => $tenant->id, 'rating' => 5]);

        // Gửi lại -> cập nhật (không trùng).
        $this->actingAs($tenant)->post('/tenant/reviews/store', [
            'room_id' => $room->id, 'rating' => 4, 'comment' => 'Tạm ổn',
        ])->assertRedirect(route('tenant.reviews.index'));
        $this->assertEquals(1, \App\Models\Review::query()->count());
        $this->assertEquals(4, \App\Models\Review::query()->value('rating'));

        // Rating vượt 1-5 -> lỗi.
        $this->actingAs($tenant)->post('/tenant/reviews/store', [
            'room_id' => $room->id, 'rating' => 9,
        ])->assertSessionHasErrors('rating');

        // Owner xem thống kê + ẩn/hiện.
        $this->actingAs($owner)->get('/owner/reviews')->assertSuccessful()->assertSee('1 lượt đánh giá');
        $reviewId = \App\Models\Review::query()->value('id');
        $this->actingAs($owner)->put("/owner/reviews/{$reviewId}/visibility")->assertRedirect();
        $this->assertEquals('hidden', \App\Models\Review::find($reviewId)->status);

        // Owner khác không thấy review này.
        [$ownerB] = $this->makeOwner('B');
        $this->actingAs($ownerB)->getJson('/owner/reviews')->assertJsonCount(0, 'data.data');
    }

    public function test_owner_maintenance_status_flow_and_notifications(): void
    {
        [$owner, , $room] = $this->makeOwner();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $contract = $this->makeContract($owner, $tenant, $room);

        $this->actingAs($tenant)->post('/tenant/maintenance/store', [
            'contract_id' => $contract->id, 'title' => 'Hỏng đèn', 'description' => 'Đèn không sáng', 'priority' => 'low',
        ]);
        $ticket = \App\Models\MaintenanceRequest::query()->first();

        // Owner thấy ticket của mình.
        $this->actingAs($owner)->get('/owner/maintenance')->assertSuccessful()->assertSee('Hỏng đèn');
        $this->actingAs($owner)->get("/owner/maintenance/{$ticket->id}")->assertSuccessful();

        // Sai luồng pending -> completed thẳng -> 422.
        $this->actingAs($owner)->put("/owner/maintenance/{$ticket->id}/status", [
            'status' => 'completed',
        ])->assertSessionHasErrors('status');

        // Đúng luồng + ghi chú + thông báo tenant.
        $this->actingAs($owner)->put("/owner/maintenance/{$ticket->id}/status", [
            'status' => 'processing', 'owner_note' => 'Thợ qua chiều nay',
        ])->assertRedirect();
        $this->assertDatabaseHas('notifications', ['user_id' => $tenant->id, 'type' => 'maintenance']);
        $this->actingAs($owner)->put("/owner/maintenance/{$ticket->id}/status", [
            'status' => 'completed',
        ])->assertRedirect();
        $ticket->refresh();
        $this->assertEquals('completed', $ticket->status);
        $this->assertNotNull($ticket->completed_at);

        // Owner khác không thấy/không sửa được.
        [$ownerB] = $this->makeOwner('B');
        $this->actingAs($ownerB)->get("/owner/maintenance/{$ticket->id}")->assertNotFound();
        $this->actingAs($ownerB)->put("/owner/maintenance/{$ticket->id}/status", [
            'status' => 'rejected',
        ])->assertNotFound();
    }

    public function test_tenant_notifications_read_flow(): void
    {
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        \App\Models\Notification::notify($tenant->id, 'Test 1', 'Nội dung 1', 'invoice', 'invoice', 999);
        \App\Models\Notification::notify($tenant->id, 'Test 2', 'Nội dung 2', 'general');

        $this->actingAs($tenant)->get('/tenant/notifications')->assertSuccessful()->assertSee('Test 1');
        // Tham chiếu đã xóa (id 999) không gây lỗi.
        $this->actingAs($tenant)->get('/tenant/notifications')->assertSuccessful();

        $first = \App\Models\Notification::query()->where('user_id', $tenant->id)->orderBy('id')->first();
        // Đánh dấu từng cái -> redirect về target hoặc back.
        $this->actingAs($tenant)->put("/tenant/notifications/{$first->id}/read")->assertRedirect();
        $this->assertNotNull($first->fresh()->read_at);

        // Tenant khác không đánh dấu hộ được.
        $other = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $second = \App\Models\Notification::query()->where('user_id', $tenant->id)->orderByDesc('id')->first();
        $this->actingAs($other)->put("/tenant/notifications/{$second->id}/read")->assertNotFound();

        // Đánh dấu tất cả.
        $this->actingAs($tenant)->post('/tenant/notifications/mark-read')->assertRedirect();
        $this->assertEquals(0, \App\Models\Notification::query()->where('user_id', $tenant->id)->whereNull('read_at')->count());
    }

    public function test_tenant_invoice_scoping_and_views(): void
    {
        config()->set('app.demo_guest', true);
        [$owner, , $room] = $this->makeOwner();
        $tenant = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $other = User::factory()->create(['role' => 'tenant', 'status' => 'active']);
        $this->makeContract($owner, $tenant, $room);
        $this->makeReading($room);
        $this->actingAs($owner);
        $invoiceId = $this->postJson('/owner/invoices', ['month' => '2026-09-01'])->json('data.0.id');

        $this->actingAs($tenant)->get('/tenant/invoices/index')->assertSuccessful()->assertSee('INV-');
        $this->actingAs($tenant)->get("/tenant/invoices/{$invoiceId}")->assertSuccessful()->assertSee('3,250,000');
        $this->actingAs($other)->get("/tenant/invoices/{$invoiceId}")->assertNotFound();
        $this->actingAs($other)->getJson("/tenant/invoices/{$invoiceId}")->assertNotFound();
    }
}

