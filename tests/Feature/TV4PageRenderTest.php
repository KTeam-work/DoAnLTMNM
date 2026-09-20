<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Invoice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TV4PageRenderTest extends TestCase
{
    use RefreshDatabase;

    public function test_owner_pages_render_seeded_data(): void
    {
        config()->set('app.demo_guest', true);
        $this->seed(\Database\Seeders\TV3DemoSeeder::class);

        $this->get('/owner/payments')->assertSuccessful()->assertSee('PAY-202609-12A01');
        $this->get('/owner/maintenance')->assertSuccessful()->assertSee('Vòi nước lavabo');
        $this->get('/owner/invoices')->assertSuccessful()->assertSee('INV-202609-12A01');

        // Fallback chống kẹt skeleton: JS luôn gỡ lớp ẩn nội dung.
        $this->get('/owner/payments')->assertSee("remove('skeleton-mode'); }, 2500);", false);
        $this->get('/owner/maintenance')->assertSee("remove('skeleton-mode'); }, 2500);", false);

        // Chi tiết HĐ bên chủ có nút Xác nhận cho giao dịch đang chờ.
        $pendingInvoice = \App\Models\Invoice::where('invoice_code', 'INV-202609-12A01')->first();
        $this->get("/owner/invoices/{$pendingInvoice->id}")->assertSuccessful()->assertSee('Xác nhận');

        // 2 trang chi tiết đều có menu owner.
        $this->get("/owner/invoices/{$pendingInvoice->id}")->assertSee('Quản lý nhà');
        $ticket = \App\Models\MaintenanceRequest::first();
        $this->get("/owner/maintenance/{$ticket->id}")->assertSuccessful()->assertSee('Quản lý nhà');
    }

    public function test_tenant_pay_button_saves_and_notifies_owner(): void
    {
        config()->set('app.demo_guest', true);
        $this->seed(\Database\Seeders\TV3DemoSeeder::class);

        $contract1 = Contract::where('contract_code', 'HD-2026-01')->first();
        $inv = Invoice::create([
            'invoice_code' => 'INV-202610-12A01', 'contract_id' => $contract1->id,
            'billing_month' => '2026-10-01', 'issue_date' => '2026-10-05', 'due_date' => '2026-10-15',
            'subtotal' => 100000, 'discount' => 0, 'total' => 100000, 'status' => 'unpaid',
        ]);
        $this->get("/tenant/invoices/{$inv->id}")->assertSuccessful()->assertSee('Tôi đã chuyển khoản');

        $this->post('/tenant/payment', ['invoice_id' => $inv->id, 'method' => 'cash'])
            ->assertRedirect(route('tenant.invoices.show', $inv->id));
        $this->assertDatabaseHas('payments', ['invoice_id' => $inv->id, 'status' => 'pending']);
        $this->assertDatabaseHas('notifications', ['type' => 'payment', 'reference_id' => $inv->id]);
    }

    public function test_owner_generates_october_invoices_from_seeded_readings(): void
    {
        config()->set('app.demo_guest', true);
        $this->seed(\Database\Seeders\TV3DemoSeeder::class);

        // Tháng 10: cả 2 phòng đã có chỉ số, chưa có hóa đơn -> tạo được 2 cái.
        $this->post('/owner/invoices', ['month' => '2026-10'])
            ->assertRedirect(route('owner.invoices.index', ['month' => '2026-10']));
        $this->assertEquals(2, Invoice::whereDate('billing_month', '2026-10-01')->count());

        // Tạo lại -> báo đã có, không trùng.
        $this->post('/owner/invoices', ['month' => '2026-10'])->assertSessionHasErrors('month');
        $this->assertEquals(2, Invoice::whereDate('billing_month', '2026-10-01')->count());
    }
}
