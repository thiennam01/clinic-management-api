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
        // 1. Run RoleSeeder and PermissionSeeder
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class, // Seed permissions and map permissions to roles
        ]);

        // 2. Get the ADMIN role to initialize the test Admin user
        $adminRole = Role::where('name', 'ADMIN')->first();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role_id' => $adminRole->id,
        ]);
    }
}
