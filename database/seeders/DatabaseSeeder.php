<?php

namespace Database\Seeders;

use App\Models\Role;
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
        // 1. Chạy RoleSeeder và PermissionSeeder
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class, // Nạp permissions và map quyền cho roles
        ]);

        // 2. Lấy role ADMIN để khởi tạo Admin test user
        $adminRole = Role::where('name', 'ADMIN')->first();

        // 3. Khởi tạo User admin@clinic.test với thông tin chuẩn
        User::updateOrCreate(
            ['email' => 'admin@clinic.test'],
            [
                'name'     => 'Administrator',
                'password' => Hash::make('password123'), // Hash password để Login được ngay
                'role_id'  => $adminRole?->id,
            ]
        );
    }
}