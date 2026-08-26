<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Examination;
use Illuminate\Database\Seeder;

class ExaminationSeeder extends Seeder
{
    public function run(): void
    {
        $appointments = Appointment::query()
            ->with('schedule')
            ->where('status', 'completed')
            ->whereDoesntHave('examination')
            ->orderBy('id')
            ->limit(10)
            ->get();

        $diagnoses = [
            'Viêm họng cấp.',
            'Viêm đường hô hấp trên.',
            'Viêm mũi dị ứng.',
            'Cảm cúm thông thường.',
            'Rối loạn tiêu hóa.',
            'Viêm amidan.',
            'Đau dạ dày.',
            'Viêm phế quản nhẹ.',
            'Sốt virus.',
            'Viêm xoang.',
        ];

        foreach ($appointments as $index => $appointment) {
            Examination::updateOrCreate(
                ['appointment_id' => $appointment->id],
                [
                    'doctor_id' => $appointment->schedule->doctor_id,
                    'patient_id' => $appointment->patient_id,
                    'diagnosis' => $diagnoses[$index % count($diagnoses)],
                    'notes' => 'Bệnh nhân được khám và hướng dẫn điều trị.',
                    'examined_at' => $appointment->appointment_date,
                ]
            );
        }
    }
}