<?php

namespace Database\Seeders;

use App\Models\Contract;
use App\Models\Property;
use App\Models\Room;
use App\Models\Service;
use App\Models\User;
use App\Models\UtilityReading;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TV3DemoSeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::query()->updateOrCreate(
            ['email' => 'owner@trooi.local'],
            [
                'user_code' => 'OWN-001',
                'name' => 'Chủ trọ A',
                'password' => Hash::make('password'),
                'phone' => '0909000111',
                'role' => 'owner',
                'status' => 'active',
            ]
        );

        $tenant1 = User::query()->updateOrCreate(
            ['email' => 'huyen.nt@email.com'],
            [
                'user_code' => 'TEN-001',
                'name' => 'Nguyễn Thanh Huyền',
                'password' => Hash::make('password'),
                'phone' => '0901234567',
                'role' => 'tenant',
                'status' => 'active',
            ]
        );

        $tenant2 = User::query()->updateOrCreate(
            ['email' => 'minh.tran@email.com'],
            [
                'user_code' => 'TEN-002',
                'name' => 'Trần Hoàng Minh',
                'password' => Hash::make('password'),
                'phone' => '0988765432',
                'role' => 'tenant',
                'status' => 'active',
            ]
        );

        $property = Property::query()->updateOrCreate(
            ['name' => 'Nhà trọ Q.7', 'owner_id' => $owner->id],
            [
                'type' => 'rooming_house',
                'address' => '123 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh',
                'district' => 'Quận 7',
                'ward' => 'Tân Phú',
                'description' => 'Dữ liệu demo TV3',
                'status' => 'active',
            ]
        );

        $room12A = Room::query()->updateOrCreate(
            ['room_code' => 'Q7-12A'],
            [
                'property_id' => $property->id,
                'name' => 'Phòng 12A',
                'area' => 22,
                'price' => 2800000,
                'deposit' => 2800000,
                'max_people' => 3,
                'floor' => 1,
                'description' => 'Phòng máy lạnh Q.7',
                'status' => 'rented',
            ]
        );

        $room105 = Room::query()->updateOrCreate(
            ['room_code' => 'Q7-105'],
            [
                'property_id' => $property->id,
                'name' => 'Phòng 105',
                'area' => 20,
                'price' => 2500000,
                'deposit' => 2500000,
                'max_people' => 2,
                'floor' => 1,
                'description' => 'Phòng tiêu chuẩn',
                'status' => 'rented',
            ]
        );

        Room::query()->updateOrCreate(
            ['room_code' => 'Q7-102'],
            [
                'property_id' => $property->id,
                'name' => 'Phòng 102',
                'area' => 18,
                'price' => 2200000,
                'deposit' => 2200000,
                'max_people' => 2,
                'floor' => 1,
                'description' => 'Phòng trống demo',
                'status' => 'available',
            ]
        );

        // Người thuê mới chưa có hợp đồng, vừa gửi yêu cầu muốn thuê.
        $tenant3 = User::query()->updateOrCreate(
            ['email' => 'moi.nguyen@email.com'],
            [
                'user_code' => 'TEN-003',
                'name' => 'Lê Văn Mới',
                'password' => Hash::make('password'),
                'phone' => '0933777888',
                'role' => 'tenant',
                'status' => 'active',
            ]
        );

        $room102 = Room::query()->where('room_code', 'Q7-102')->first();

        $services = [
            ['name' => 'Điện', 'description' => 'Tiền điện sử dụng hàng tháng', 'unit' => 'kWh', 'price' => 3500, 'status' => 'active'],
            ['name' => 'Nước', 'description' => 'Tiền nước sử dụng hàng tháng', 'unit' => 'm³', 'price' => 20000, 'status' => 'active'],
            ['name' => 'Internet', 'description' => 'Internet và Wifi dùng chung', 'unit' => 'tháng', 'price' => 100000, 'status' => 'active'],
            ['name' => 'Giữ xe', 'description' => 'Phí gửi xe hàng tháng', 'unit' => 'chiếc', 'price' => 100000, 'status' => 'active'],
            ['name' => 'Vệ sinh', 'description' => 'Phí vệ sinh khu vực chung', 'unit' => 'tháng', 'price' => 50000, 'status' => 'active'],
            ['name' => 'Rác', 'description' => 'Phí thu gom rác sinh hoạt', 'unit' => 'tháng', 'price' => 30000, 'status' => 'active'],
            ['name' => 'Máy lạnh', 'description' => 'Phí sử dụng và bảo trì máy lạnh', 'unit' => 'tháng', 'price' => 150000, 'status' => 'inactive'],
        ];
        foreach ($services as $svc) {
            Service::query()->updateOrCreate(
                ['owner_id' => $owner->id, 'name' => $svc['name']],
                $svc + ['owner_id' => $owner->id]
            );
        }

        $contract1 = Contract::query()->updateOrCreate(
            ['contract_code' => 'HD-2026-01'],
            [
                'room_id' => $room12A->id,
                'owner_id' => $owner->id,
                'tenant_id' => $tenant1->id,
                'start_date' => '2026-02-01',
                'end_date' => '2027-02-01',
                'rent' => 2800000,
                'deposit' => 2800000,
                'payment_cycle' => 'monthly',
                'terms' => 'Demo TV3',
                'status' => 'active',
                'signed_at' => '2026-02-01 08:00:00',
                'initial_electricity_reading' => 1200,
                'initial_water_reading' => 75,
            ]
        );

        $contract2 = Contract::query()->updateOrCreate(
            ['contract_code' => 'HD-2025-42'],
            [
                'room_id' => $room105->id,
                'owner_id' => $owner->id,
                'tenant_id' => $tenant2->id,
                'start_date' => '2025-08-15',
                'end_date' => '2026-08-15',
                'rent' => 2500000,
                'deposit' => 2500000,
                'payment_cycle' => 'monthly',
                'terms' => 'Demo TV3',
                'status' => 'active',
                'signed_at' => '2025-08-15 08:00:00',
                'initial_electricity_reading' => 2000,
                'initial_water_reading' => 110,
            ]
        );

        $contract1->members()->updateOrCreate(
            ['identity_card' => '079200001234'],
            [
                'name' => 'Trần Minh Bình',
                'phone' => '0912345678',
                'relationship' => 'Người ở cùng',
            ]
        );

        UtilityReading::query()->updateOrCreate(
            ['room_id' => $room12A->id, 'month' => '2026-08-01'],
            [
                'electricity_old' => 1200,
                'electricity_new' => 1250,
                'electricity_price' => 3500,
                'water_old' => 75,
                'water_new' => 80,
                'water_price' => 20000,
            ]
        );

        UtilityReading::query()->updateOrCreate(
            ['room_id' => $room12A->id, 'month' => '2026-09-01'],
            [
                'electricity_old' => 1250,
                'electricity_new' => 1380,
                'electricity_price' => 3500,
                'water_old' => 80,
                'water_new' => 85,
                'water_price' => 20000,
            ]
        );

        UtilityReading::query()->updateOrCreate(
            ['room_id' => $room105->id, 'month' => '2026-08-01'],
            [
                'electricity_old' => 2000,
                'electricity_new' => 2100,
                'electricity_price' => 3500,
                'water_old' => 110,
                'water_new' => 120,
                'water_price' => 20000,
            ]
        );

        // Chỉ số tháng 10 để demo tạo hóa đơn tháng.
        UtilityReading::query()->updateOrCreate(
            ['room_id' => $room12A->id, 'month' => '2026-10-01'],
            [
                'electricity_old' => 1380,
                'electricity_new' => 1450,
                'electricity_price' => 3500,
                'water_old' => 85,
                'water_new' => 90,
                'water_price' => 20000,
            ]
        );
        UtilityReading::query()->updateOrCreate(
            ['room_id' => $room105->id, 'month' => '2026-10-01'],
            [
                'electricity_old' => 2100,
                'electricity_new' => 2180,
                'electricity_price' => 3500,
                'water_old' => 120,
                'water_new' => 126,
                'water_price' => 20000,
            ]
        );

        // Yêu cầu thuê / lịch xem phòng do người thuê gửi tới.
        DB::table('viewing_appointments')->updateOrInsert(
            ['tenant_id' => $tenant3->id, 'room_id' => $room102->id, 'appointment_date' => '2026-09-20'],
            [
                'appointment_time' => '09:00:00',
                'phone' => $tenant3->phone,
                'message' => 'Em muốn thuê phòng này, chủ xem giúp em.',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('viewing_appointments')->updateOrInsert(
            ['tenant_id' => $tenant2->id, 'room_id' => $room102->id, 'appointment_date' => '2026-09-18'],
            [
                'appointment_time' => '15:00:00',
                'phone' => $tenant2->phone,
                'message' => 'Đã xem phòng, muốn thuê thêm cho người nhà.',
                'status' => 'confirmed',
                'confirmed_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        DB::table('maintenance_requests')->updateOrInsert(
            ['tenant_id' => $tenant1->id, 'title' => 'Vòi nước lavabo bị rỉ'],
            [
                'contract_id' => $contract1->id,
                'room_id' => $room12A->id,
                'category' => 'dien_nuoc',
                'description' => 'Vòi lavabo trong nhà vệ sinh chảy rỉ liên tục, nhờ chủ cho thợ qua xem.',
                'priority' => 'medium',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Dịch vụ gắn với hợp đồng 1 (dùng khi tạo hóa đơn).
        $internet = Service::query()->where('owner_id', $owner->id)->where('name', 'Internet')->first();
        $giuxe = Service::query()->where('owner_id', $owner->id)->where('name', 'Giữ xe')->first();
        foreach ([$internet, $giuxe] as $svc) {
            if ($svc) {
                DB::table('contract_services')->updateOrInsert(
                    ['contract_id' => $contract1->id, 'service_id' => $svc->id],
                    ['quantity' => 1, 'unit_price' => $svc->price, 'status' => 'active', 'created_at' => now(), 'updated_at' => now()]
                );
            }
        }

        // Hóa đơn T09/2026 cho HĐ-2026-01 (chưa thanh toán).
        $readingSep = UtilityReading::query()->where('room_id', $room12A->id)->whereDate('month', '2026-09-01')->first();
        DB::table('invoices')->updateOrInsert(
            ['contract_id' => $contract1->id, 'billing_month' => '2026-09-01'],
            [
                'invoice_code' => 'INV-202609-12A01',
                'utility_reading_id' => $readingSep?->id,
                'issue_date' => '2026-09-05',
                'due_date' => '2026-09-15',
                'subtotal' => 3555000,
                'discount' => 0,
                'total' => 3555000,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $invoice1 = \App\Models\Invoice::query()->where('contract_id', $contract1->id)->whereDate('billing_month', '2026-09-01')->first();
        if ($invoice1 && $invoice1->items()->count() === 0) {
            $invoice1->items()->createMany([
                ['type' => 'rent', 'description' => 'Tiền phòng Phòng 12A', 'quantity' => 1, 'unit_price' => 2800000, 'amount' => 2800000, 'service_id' => null],
                ['type' => 'electricity', 'description' => 'Điện: 1250 → 1380 (130 kWh)', 'quantity' => 130, 'unit_price' => 3500, 'amount' => 455000, 'service_id' => null],
                ['type' => 'water', 'description' => 'Nước: 80 → 85 (5 m³)', 'quantity' => 5, 'unit_price' => 20000, 'amount' => 100000, 'service_id' => null],
                ['type' => 'service', 'description' => 'Internet (tháng)', 'quantity' => 1, 'unit_price' => 100000, 'amount' => 100000, 'service_id' => $internet?->id],
                ['type' => 'service', 'description' => 'Giữ xe (chiếc)', 'quantity' => 1, 'unit_price' => 100000, 'amount' => 100000, 'service_id' => $giuxe?->id],
            ]);
            DB::table('payments')->updateOrInsert(
                ['payment_code' => 'PAY-202609-12A01'],
                [
                    'invoice_id' => $invoice1->id,
                    'payer_id' => $tenant1->id,
                    'amount' => 3555000,
                    'method' => 'bank_transfer',
                    'transaction_code' => 'MBVCB123456',
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Hóa đơn T08/2026 cho HĐ-2025-42 (đã thanh toán).
        $readingAug = UtilityReading::query()->where('room_id', $room105->id)->whereDate('month', '2026-08-01')->first();
        DB::table('invoices')->updateOrInsert(
            ['contract_id' => $contract2->id, 'billing_month' => '2026-08-01'],
            [
                'invoice_code' => 'INV-202608-10501',
                'utility_reading_id' => $readingAug?->id,
                'issue_date' => '2026-08-05',
                'due_date' => '2026-08-15',
                'subtotal' => 3330000,
                'discount' => 0,
                'total' => 3330000,
                'status' => 'paid',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        $invoice2 = \App\Models\Invoice::query()->where('contract_id', $contract2->id)->whereDate('billing_month', '2026-08-01')->first();
        if ($invoice2 && $invoice2->items()->count() === 0) {
            $invoice2->items()->createMany([
                ['type' => 'rent', 'description' => 'Tiền phòng Phòng 105', 'quantity' => 1, 'unit_price' => 2500000, 'amount' => 2500000, 'service_id' => null],
                ['type' => 'electricity', 'description' => 'Điện: 2000 → 2100 (100 kWh)', 'quantity' => 100, 'unit_price' => 3500, 'amount' => 350000, 'service_id' => null],
                ['type' => 'water', 'description' => 'Nước: 110 → 120 (10 m³)', 'quantity' => 10, 'unit_price' => 20000, 'amount' => 200000, 'service_id' => null],
                ['type' => 'service', 'description' => 'Internet (tháng)', 'quantity' => 1, 'unit_price' => 100000, 'amount' => 100000, 'service_id' => $internet?->id],
                ['type' => 'service', 'description' => 'Giữ xe (chiếc)', 'quantity' => 1, 'unit_price' => 100000, 'amount' => 100000, 'service_id' => $giuxe?->id],
                ['type' => 'service', 'description' => 'Vệ sinh (tháng)', 'quantity' => 1, 'unit_price' => 50000, 'amount' => 50000, 'service_id' => Service::query()->where('owner_id', $owner->id)->where('name', 'Vệ sinh')->value('id')],
                ['type' => 'service', 'description' => 'Rác (tháng)', 'quantity' => 1, 'unit_price' => 30000, 'amount' => 30000, 'service_id' => Service::query()->where('owner_id', $owner->id)->where('name', 'Rác')->value('id')],
            ]);
            DB::table('payments')->updateOrInsert(
                ['payment_code' => 'PAY-202608-10501'],
                [
                    'invoice_id' => $invoice2->id,
                    'payer_id' => $tenant2->id,
                    'amount' => 3330000,
                    'method' => 'cash',
                    'transaction_code' => null,
                    'status' => 'success',
                    'paid_at' => '2026-08-10 10:00:00',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Đánh giá demo.
        DB::table('reviews')->updateOrInsert(
            ['room_id' => $room12A->id, 'tenant_id' => $tenant1->id],
            ['rating' => 5, 'comment' => 'Phòng thoáng, chủ nhiệt tình, điện nước rõ ràng.', 'status' => 'visible', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('reviews')->updateOrInsert(
            ['room_id' => $room105->id, 'tenant_id' => $tenant2->id],
            ['rating' => 4, 'comment' => 'Phòng ổn, giờ giấc tự do.', 'status' => 'visible', 'created_at' => now(), 'updated_at' => now()]
        );

        // Thông báo demo.
        if ($invoice1) {
            DB::table('notifications')->updateOrInsert(
                ['user_id' => $tenant1->id, 'type' => 'invoice', 'reference_id' => $invoice1->id],
                ['title' => 'Hóa đơn mới', 'message' => "Hóa đơn {$invoice1->invoice_code} kỳ 09/2026: 3,555,000 đ.", 'reference_type' => 'invoice', 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
