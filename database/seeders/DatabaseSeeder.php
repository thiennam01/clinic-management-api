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
        // 1. Run platform & category seeders
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            SpecialtySeeder::class,
        ]);

        // 2. Create default Admin User
        $adminRole = Role::where('name', 'ADMIN')->first();

        User::updateOrCreate(
            ['email' => 'admin@clinic.test'],
            [
                'name'     => 'System Admin',
                'password' => Hash::make('password123'),
                'role_id'  => $adminRole?->id,
            ]
        );

        // 3. Run business seeders (dependent on Role, Specialty)
        $this->call([
            DoctorSeeder::class,
            PatientSeeder::class,
        ]);
    }
}
