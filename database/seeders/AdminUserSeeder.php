<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (!$email || !$password) {
            throw new \RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD before running the admin seeder.');
        }

        \App\Models\User::updateOrCreate(
            ['email' => $email],
            [
                'name' => 'Admin YourStudio',
                'password' => $password,
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
