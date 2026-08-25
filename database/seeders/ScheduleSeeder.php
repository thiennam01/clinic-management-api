<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = Doctor::orderBy("id")->get();

        if ($doctors->isEmpty()) {
            return;
        }

        foreach ($doctors as $index => $doctor) {
            $this->createSchedule(
                $doctor->id,
                "2026-08-31",
                $index % 2 === 0 ? "08:00:00" : "13:30:00",
                $index % 2 === 0 ? "12:00:00" : "17:30:00"
            );

            $this->createSchedule(
                $doctor->id,
                "2026-09-01",
                $index % 2 === 0 ? "13:30:00" : "08:00:00",
                $index % 2 === 0 ? "17:30:00" : "12:00:00"
            );
        }
    }

    private function createSchedule(
        int $doctorId,
        string $date,
        string $startTime,
        string $endTime
    ): void {
        Schedule::firstOrCreate(
            [
                "doctor_id" => $doctorId,
                "date" => $date,
                "start_time" => $startTime,
                "end_time" => $endTime,
            ],
            [
                "max_patients" => 10,
                "current_patients" => 0,
                "is_active" => true,
            ]
        );
    }
}