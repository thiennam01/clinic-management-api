<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = Patient::orderBy('id')->get();

        $schedules = Schedule::query()
            ->whereDate('date', today())
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($patients->isEmpty() || $schedules->isEmpty()) {
            return;
        }

        $statusPattern = [
            'pending',
            'pending',
            'confirmed',
            'confirmed',
            'completed',
            'completed',
            'pending',
            'confirmed',
            'completed',
            'cancelled',
        ];

        $counter = 0;

        foreach ($schedules as $schedule) {
            $start = \Carbon\Carbon::parse($schedule->date->format('Y-m-d') . ' ' . $schedule->start_time);

            for ($slot = 0; $slot < 6 && $counter < 30; $slot++) {
                $appointmentTime = $start->copy()->addMinutes($slot * 15);

                $patient = $patients[$counter % $patients->count()];
                $status = $statusPattern[$counter % count($statusPattern)];

                Appointment::updateOrCreate(
                    [
                        'patient_id' => $patient->id,
                        'schedule_id' => $schedule->id,
                        'appointment_date' => $appointmentTime,
                    ],
                    [
                        'status' => $status,
                        'notes' => $status === 'completed'
                            ? 'Đã hoàn tất khám.'
                            : null,
                    ]
                );

                $counter++;
            }
        }

        foreach ($schedules as $schedule) {
            $schedule->update([
                'current_patients' => Appointment::query()
                    ->where('schedule_id', $schedule->id)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->count(),
            ]);
        }
    }
}