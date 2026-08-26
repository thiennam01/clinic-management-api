<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Examination;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Patient;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Schedule;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_without_stats_permission_is_forbidden(): void
    {
        $role = Role::create([
            'name' => 'RECEPTIONIST',
            'display_name' => 'Receptionist',
        ]);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/stats')
            ->assertForbidden();
    }

    public function test_user_with_stats_permission_can_view_overview(): void
    {
        $role = Role::create([
            'name' => 'CASHIER',
            'display_name' => 'Cashier',
        ]);

        $permission = Permission::create([
            'name' => 'STATS.SHOW',
        ]);

        $role->permissions()->attach($permission->id);

        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $patient1 = Patient::create([
            'code' => 'BN-000001',
            'full_name' => 'Test Patient 1',
            'gender' => 'male',
            'date_of_birth' => '2000-01-01',
            'phone' => '0900000001',
        ]);

        $patient2 = Patient::create([
            'code' => 'BN-000002',
            'full_name' => 'Test Patient 2',
            'gender' => 'female',
            'date_of_birth' => '2000-01-02',
            'phone' => '0900000002',
        ]);

        $doctorRole = Role::create([
            'name' => 'DOCTOR',
            'display_name' => 'Doctor',
        ]);

        $doctorUser = User::factory()->create([
            'role_id' => $doctorRole->id,
        ]);

        $specialty = Specialty::create([
            'code' => 'TEST',
            'name' => 'Test Specialty',
            'description' => 'Test specialty',
            'is_active' => true,
        ]);

        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,
            'specialty_id' => $specialty->id,
            'license_number' => 'TEST-001',
            'experience_years' => 1,
            'consultation_fee' => 100000,
            'is_active' => true,
        ]);

        $schedule = Schedule::create([
            'doctor_id' => $doctor->id,
            'date' => today(),
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'max_patients' => 10,
            'current_patients' => 2,
            'is_active' => true,
        ]);

        $appointment1 = Appointment::create([
            'patient_id' => $patient1->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => now(),
            'status' => 'pending',
        ]);

        $appointment2 = Appointment::create([
            'patient_id' => $patient2->id,
            'schedule_id' => $schedule->id,
            'appointment_date' => now(),
            'status' => 'pending',
        ]);

        $examination = Examination::create([
            'appointment_id' => $appointment1->id,
            'doctor_id' => $doctor->id,
            'patient_id' => $patient1->id,
            'diagnosis' => 'Test diagnosis',
            'notes' => 'Test notes',
            'examined_at' => now(),
        ]);

        Invoice::create([
            'examination_id' => $examination->id,
            'invoice_code' => 'INV-TEST-001',
            'subtotal' => 100000,
            'discount' => 0,
            'total' => 100000,
            'status' => 'paid',
            'issued_at' => now(),
        ]);

        Medicine::create([
            'code' => 'MED-TEST-001',
            'name' => 'Test Medicine 1',
            'unit' => 'tablet',
            'price' => 10000,
            'stock' => 5,
            'is_active' => true,
        ]);

        Medicine::create([
            'code' => 'MED-TEST-002',
            'name' => 'Test Medicine 2',
            'unit' => 'tablet',
            'price' => 20000,
            'stock' => 20,
            'is_active' => true,
        ]);

        $this->actingAs($user, 'sanctum')
            ->getJson('/api/stats')
            ->assertOk()
            ->assertJsonPath('data.patients', 2)
            ->assertJsonPath('data.appointments_today', 2)
            ->assertJsonPath('data.monthly_revenue', 100000)
            ->assertJsonPath('data.low_stock_medicines', 1);
    }
}