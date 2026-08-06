<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Contracts\AppointmentRepositoryInterface;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 10)
    {
        $query = Appointment::with(['patient', 'schedule.doctor']);

        if (!empty($filters['patient_id'])) {
            $query->where('patient_id', $filters['patient_id']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->latest()->paginate($perPage);
    }

    public function create(array $data)
    {
        return Appointment::create($data);
    }

    public function countBySchedule(int $scheduleId): int
    {
        return Appointment::where('schedule_id', $scheduleId)
            ->where('status', '!=', 'cancelled')
            ->count();
    }
}