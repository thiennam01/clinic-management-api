<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

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

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $adminRole->id,
        ]);
    }
}