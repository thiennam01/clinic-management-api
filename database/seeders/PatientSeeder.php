<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    public function run(): void
    {
        $patientsData = [
            [
                'code'          => 'BN-000001',
                'full_name'     => 'Nguyễn Văn Bệnh Nhân',
                'gender'        => 'male',
                'date_of_birth' => '1995-05-15',
                'phone'         => '0912345678',
                'email'         => 'patient1@clinic.test',
                'address'       => 'Số 1 Đại Cồ Việt, Hai Bà Trưng, Hà Nội',
            ],
            [
                'code'          => 'BN-000002',
                'full_name'     => 'Lê Thị Bệnh Nhân',
                'gender'        => 'female',
                'date_of_birth' => '1998-10-20',
                'phone'         => '0987654321',
                'email'         => 'patient2@clinic.test',
                'address'       => 'Số 12 Chùa Bộc, Đống Đa, Hà Nội',
            ],
        ];

        foreach ($patientsData as $data) {
            Patient::firstOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}