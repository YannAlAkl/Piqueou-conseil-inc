<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@test.com'],
            [
                'first_name' => 'Admin',
                'last_name' => 'Test',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
                'account_status' => 'active',
                'activated_at' => now(),
            ]
        );

        $user->assignRole('admin');
    }
}
