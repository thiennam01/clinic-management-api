<?php

namespace Database\Seeders;

use App\Models\Medicine;
use Illuminate\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            ['Paracetamol 500mg', 'Viên', 1000, 500],
            ['Amoxicillin 500mg', 'Viên', 2500, 300],
            ['Cetirizine 10mg', 'Viên', 1500, 250],
            ['Omeprazole 20mg', 'Viên', 2000, 200],
            ['Natri Clorid 0.9%', 'Chai', 8000, 100],
            ['Ibuprofen 400mg', 'Viên', 2000, 300],
            ['Azithromycin 500mg', 'Viên', 5000, 150],
            ['Vitamin C 500mg', 'Viên', 1200, 400],
            ['Loratadine 10mg', 'Viên', 1800, 200],
            ['Ambroxol 30mg', 'Viên', 2200, 180],
        ];

        foreach ($medicines as $index => $medicine) {
            Medicine::updateOrCreate(
                ['code' => sprintf('MED-%06d', $index + 1)],
                [
                    'name' => $medicine[0],
                    'unit' => $medicine[1],
                    'price' => $medicine[2],
                    'stock' => $medicine[3],
                    'is_active' => true,
                ]
            );
        }
    }
}