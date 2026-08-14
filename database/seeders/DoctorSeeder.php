<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Role;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $doctorRole = Role::where('name', 'DOCTOR')->first();
        $entSpecialty = Specialty::where('code', 'ENT')->first();
        $pediatricsSpecialty = Specialty::where('code', 'PEDIATRICS')->first();

        if (!$doctorRole) {
            return;
        }

        $doctorsData = [
            [
                'name' => 'Bác sĩ Nguyễn Văn A',
                'email' => 'doctor_a@clinic.test',
                'license_number' => 'BS-2026-001',
                'experience_years' => 10,
                'bio' => 'Bác sĩ Chuyên khoa CKI Tai Mũi Họng với 10 năm kinh nghiệm.',
                'consultation_fee' => 250000,
                'specialty_id' => $entSpecialty?->id,
            ],
            [
                'name' => 'Bác sĩ Trần Thị B',
                'email' => 'doctor_b@clinic.test',
                'license_number' => 'BS-2026-002',
                'experience_years' => 6,
                'bio' => 'Chuyên gia tư vấn và điều trị các bệnh lý Nhi khoa.',
                'consultation_fee' => 200000,
                'specialty_id' => $pediatricsSpecialty?->id,
            ],
        ];

        foreach ($doctorsData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role_id' => $doctorRole->id,
                ]
            );

            Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty_id' => $data['specialty_id'],
                    'license_number' => $data['license_number'],
                    'experience_years' => $data['experience_years'],
                    'bio' => $data['bio'],
                    'consultation_fee' => $data['consultation_fee'],
                    'is_active' => true,
                ]
            );
        }
    }
}