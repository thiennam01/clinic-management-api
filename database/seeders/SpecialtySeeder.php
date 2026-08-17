<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Khoa Nhi',
                'code' => 'PEDIATRICS',
                'description' => 'Chuyên khoa khám và điều trị bệnh lý cho trẻ em.',
            ],
            [
                'name' => 'Khoa Tai Mũi Họng',
                'code' => 'ENT',
                'description' => 'Chuyên khoa khám và điều trị các bệnh về Tai - Mũi - Họng.',
            ],
            [
                'name' => 'Khoa Nội Tổng Quát',
                'code' => 'INTERNAL_MEDICINE',
                'description' => 'Chuyên khoa chẩn đoán và điều trị bệnh nội khoa người lớn.',
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::firstOrCreate(
                ['code' => $specialty['code']],
                $specialty
            );
        }
    }
}   