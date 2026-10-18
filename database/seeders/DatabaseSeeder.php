<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Room;
use App\Models\RentalPost;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tài khoản admin
        $this->call(AdminUserSeeder::class);

        // Dữ liệu demo TV3 (chủ trọ, người thuê, nhà trọ, phòng, dịch vụ, hợp đồng, hóa đơn...)
        $this->call(TV3DemoSeeder::class);

        $owner = User::where('role', 'owner')->first();
        $tenant = User::where('role', 'tenant')->where('email', 'test@example.com')->first();

        if (!$tenant) {
            $tenant = User::updateOrCreate(
                ['email' => 'test@example.com'],
                [
                    'name' => 'Người thuê Test',
                    'phone' => '0900000001',
                    'password' => Hash::make('12345678'),
                    'role' => 'tenant',
                    'status' => 'active',
                ]
            );
        }

        // Tạo thêm phòng trống có bài đăng cho thuê để người thuê test tìm kiếm/trang chủ.
        if ($owner) {
            $property = Property::updateOrCreate(
                ['name' => 'Căn hộ Riverside', 'owner_id' => $owner->id],
                [
                    'type' => 'apartment',
                    'address' => '45 Lê Văn Việt, Quận 9, TP. Hồ Chí Minh',
                    'district' => 'Quận 9',
                    'ward' => 'Phường Long Trường',
                    'description' => 'Căn hộ mới, đầy đủ tiện ích, gần trung tâm.',
                    'status' => 'active',
                ]
            );

            $rooms = [
                ['Q9-01', 'Phòng 01', 25, 3500000, 3500000, 2, 'available'],
                ['Q9-02', 'Phòng 02', 22, 3200000, 3200000, 2, 'available'],
                ['Q9-03', 'Phòng 03', 28, 4000000, 4000000, 3, 'available'],
            ];

            foreach ($rooms as [$code, $name, $area, $price, $deposit, $maxPeople, $status]) {
                $room = Room::updateOrCreate(
                    ['room_code' => $code],
                    [
                        'property_id' => $property->id,
                        'name' => $name,
                        'area' => $area,
                        'price' => $price,
                        'deposit' => $deposit,
                        'max_people' => $maxPeople,
                        'floor' => 2,
                        'description' => "Phòng {$name} Riverside",
                        'status' => $status,
                        'images' => json_encode([
                            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80',
                        ]),
                    ]
                );

                // Tạo bài đăng cho thuê cho các phòng trống.
                if ($status === 'available') {
                    RentalPost::updateOrCreate(
                        ['room_id' => $room->id],
                        [
                            'title' => "Cho thuê {$name} - Riverside",
                            'description' => "Phòng {$name}, diện tích {$area}m², giá {$price}đ/tháng, Math trang trí sẵn.",
                            'status' => 'published',
                            'published_at' => now(),
                            'expired_at' => now()->addMonths(6),
                        ]
                    );
                }
            }
        }

        // Đảm bảo phòng trống trong demo TV3 (Q7-102) cũng có bài đăng cho thuê.
        $room102 = Room::where('room_code', 'Q7-102')->first();
        if ($room102 && $room102->status === 'available' && !$room102->rentalPosts()->where('status', 'published')->exists()) {
            RentalPost::updateOrCreate(
                ['room_id' => $room102->id],
                [
                    'title' => 'Cho thuê Phòng 102 - Nhà trọ Q.7',
                    'description' => 'Phòng trống demo, diện tích 18m², giá 2,2 triệu/tháng.',
                    'status' => 'published',
                    'published_at' => now(),
                    'expired_at' => now()->addMonths(6),
                ]
            );
        }
    }
}
