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

        if (!$doctorRole) {
            return;
        }

        $specialties = Specialty::pluck('id', 'code');

        $doctors = [
            [
                'name' => 'Bác sĩ Nguyễn Văn An',
                'email' => 'doctor_a@clinic.test',
                'license_number' => 'BS-2026-001',
                'experience_years' => 10,
                'bio' => 'Bác sĩ chuyên khoa Tai Mũi Họng với 10 năm kinh nghiệm.',
                'consultation_fee' => 250000,
                'specialty' => 'ENT',
            ],
            [
                'name' => 'Bác sĩ Trần Thị Bình',
                'email' => 'doctor_b@clinic.test',
                'license_number' => 'BS-2026-002',
                'experience_years' => 7,
                'bio' => 'Bác sĩ chuyên khoa Nhi, giàu kinh nghiệm trong khám và điều trị trẻ em.',
                'consultation_fee' => 200000,
                'specialty' => 'PEDIATRICS',
            ],
            [
                'name' => 'Bác sĩ Lê Minh Cường',
                'email' => 'doctor_c@clinic.test',
                'license_number' => 'BS-2026-003',
                'experience_years' => 12,
                'bio' => 'Bác sĩ chuyên khoa Nội Tổng Quát.',
                'consultation_fee' => 300000,
                'specialty' => 'INTERNAL_MEDICINE',
            ],
            [
                'name' => 'Bác sĩ Phạm Thu Dung',
                'email' => 'doctor_d@clinic.test',
                'license_number' => 'BS-2026-004',
                'experience_years' => 8,
                'bio' => 'Bác sĩ chuyên khoa Tai Mũi Họng.',
                'consultation_fee' => 250000,
                'specialty' => 'ENT',
            ],
            [
                'name' => 'Bác sĩ Hoàng Minh Đức',
                'email' => 'doctor_e@clinic.test',
                'license_number' => 'BS-2026-005',
                'experience_years' => 6,
                'bio' => 'Bác sĩ chuyên khoa Nhi.',
                'consultation_fee' => 200000,
                'specialty' => 'PEDIATRICS',
            ],
        ];

        foreach ($doctors as $data) {
            $user = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role_id' => $doctorRole->id,
                ]
            );

            Doctor::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty_id' => $specialties[$data['specialty']] ?? null,
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