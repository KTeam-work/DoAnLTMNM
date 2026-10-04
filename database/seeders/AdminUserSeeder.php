<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
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
    }
}