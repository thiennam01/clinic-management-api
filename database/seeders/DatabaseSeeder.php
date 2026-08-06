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
        // 1. Chạy các Seeder nền tảng & danh mục
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            SpecialtySeeder::class,
        ]);

        // 2. Tạo Admin User mặc định
        $adminRole = Role::where('name', 'ADMIN')->first();

        User::updateOrCreate(
            ['email' => 'admin@clinic.test'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password123'),
                'role_id'  => $adminRole?->id,
            ]
        );

        // 3. Chạy các Seeder nghiệp vụ (phụ thuộc vào Role, Specialty)
        $this->call([
            DoctorSeeder::class,
            PatientSeeder::class,
        ]);
    }
}