<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'ADMIN',
                'display_name' => 'Quản trị viên',
            ],
            [
                'name' => 'RECEPTIONIST',
                'display_name' => 'Lễ tân',
            ],
            [
                'name' => 'DOCTOR',
                'display_name' => 'Bác sĩ',
            ],
            [
                'name' => 'PHARMACIST',
                'display_name' => 'Dược sĩ',
            ],
            [
                'name' => 'CASHIER',
                'display_name' => 'Thu ngân',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}