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

    public function run(): void
    {
        // Chạy theo thứ tự: RoleSeeder tạo roles -> PermissionSeeder tạo & map permissions
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
        ]);

        // Tạo Admin User mặc định
        $adminRole = Role::where('name', 'ADMIN')->first();

        User::updateOrCreate(
            ['email' => 'admin@clinic.test'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password123'),
                'role_id'  => $adminRole?->id,
            ]
        );
    }
}