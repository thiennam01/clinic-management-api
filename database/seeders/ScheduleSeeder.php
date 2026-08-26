<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        foreach ($doctors as $doctor) {
            $this->createSchedule($doctor->id, today()->toDateString(), '08:00:00', '10:00:00');
            $this->createSchedule($doctor->id, today()->toDateString(), '10:00:00', '12:00:00');

            $tomorrow = today()->copy()->addDay()->toDateString();

            $this->createSchedule($doctor->id, $tomorrow, '08:00:00', '10:00:00');
            $this->createSchedule($doctor->id, $tomorrow, '14:00:00', '16:00:00');
        }
    }

    private function createSchedule(
        int $doctorId,
        string $date,
        string $startTime,
        string $endTime
    ): void {
        Schedule::updateOrCreate(
            [
                'doctor_id' => $doctorId,
                'date' => $date,
                'start_time' => $startTime,
                'end_time' => $endTime,
            ],
            [
                'max_patients' => 10,
                'is_active' => true,
            ]
        );
    }
}