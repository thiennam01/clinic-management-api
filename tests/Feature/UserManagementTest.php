<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_prevents_deactivating_the_last_admin()
    {
        // 1. Create a Role with full 'name' and 'display_name'
        $role = Role::create([
            'name' => 'Admin',
            'display_name' => 'Administrator'
        ]);
        
        $permission = Permission::create([
            'name' => 'USERS.UPDATE',
            'display_name' => 'Update User'
        ]);
        
        // Assign permissions to the role
        $role->permissions()->attach($permission->id);

        // 2. Create a single admin associated with the role above
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $role->id,
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'sanctum');

        // 3. Send request to deactivate the admin user (sửa chuẩn theo api v1 của dự án)
        $response = $this->putJson("/api/v1/users/{$admin->id}", [
            'is_active' => false,
        ]);

        // 4. Verify the system blocks the action and returns status 422
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}