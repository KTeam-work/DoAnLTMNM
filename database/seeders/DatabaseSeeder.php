<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tài khoản Admin dùng để test
        User::updateOrCreate(
            [
                'email' => 'admin@trooi.com',
            ],
            [
                'name' => 'Admin Trọ Ơi',
                'phone' => '0900000000',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Tài khoản test thường
        User::updateOrCreate(
            [
                'email' => 'test@example.com',
            ],
            [
                'name' => 'Test User',
                'password' => Hash::make('12345678'),
                'role' => 'tenant',
            ]
        );
    }
}