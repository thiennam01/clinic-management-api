<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role; // Hoặc Spatie Permission tùy theo cách dự án phân quyền
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WeekTwoFeatureTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_login_successfully()
    {
        // 1. Tạo một user mẫu trong DB test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        // 2. Gửi request đăng nhập
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        // 3. Kiểm tra kết quả trả về
        $response->assertStatus(200)
                 ->assertJsonStructure(['success', 'data' => ['token']]);
    }

    /** @test */
    public function user_without_permission_gets_forbidden_403()
    {
        // 1. Tạo user có role hạn chế (không có quyền xem/tạo danh mục nhạy cảm)
        $user = User::factory()->create();
        
        // Giả lập user đăng nhập và gọi route yêu cầu quyền admin/doctor nhưng user này không có
        $response = $this->actingAs($user, 'sanctum') // Hoặc guard đang dùng trong dự án
                         ->postJson('/api/v1/examinations', [
                             'appointment_id' => 1,
                             'diagnosis' => 'Test',
                         ]);

        // 2. Kỳ vọng trả về 403 Forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function authorized_user_can_create_patient()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->postJson('/api/v1/patients', [
                             'name' => 'Nguyễn Văn A',
                             'phone' => '0987654321',
                             'gender' => 'male',
                             'dob' => '2000-01-01',
                         ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true);
        
        $this->assertDatabaseHas('patients', ['phone' => '0987654321']);
    }

    /** @test */
    public function authorized_user_can_create_appointment()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
                         ->postJson('/api/v1/appointments', [
                             'patient_id' => 1,
                             'schedule_id' => 1,
                             'appointment_date' => now()->addDay()->toDateString(),
                         ]);

        $response->assertStatus(201)
                 ->assertJsonPath('success', true);
    }
}