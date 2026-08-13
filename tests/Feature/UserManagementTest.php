<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Nếu bảng users chưa tồn tại (do DatabaseTransactions không chạy migrate), tự tạo nó
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->unsignedInteger('role_id')->default(1);
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    public function test_it_prevents_deactivating_the_last_admin()
    {
        // Tạo 1 admin
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => 1,
            'is_active' => true,
        ]);

        $this->actingAs($admin, 'sanctum');

        // Gửi request khóa admin này
        $response = $this->putJson("/api/v1/users/{$admin->id}", [
            'is_active' => false,
        ]);

        // Kiểm tra lỗi 422
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                 ]);
    }
}