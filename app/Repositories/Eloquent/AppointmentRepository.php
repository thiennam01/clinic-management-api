<?php

namespace App\Repositories\Eloquent;

use App\Models\Appointment;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Carbon\Carbon;

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

    public function find($id)
    {
        return Appointment::find($id);
    }

    public function update($id, array $data)
    {
        $appointment = Appointment::find($id);
        if ($appointment) {
            $appointment->update($data);
            return $appointment;
        }
        return null;
    }

    /**
     * Check if a doctor has a conflicting appointment within a 30-minute time slot.
     * 
     * @param int $doctorId
     * @param string $appointmentDate
     * @param int $scheduleId
     * @return bool
     */
    public function hasConflict(int $doctorId, string $appointmentDate, int $scheduleId): bool
    {
        // Define the duration for each appointment slot (e.g., 30 minutes)
        $startNew = Carbon::parse($appointmentDate);
        $endNew = $startNew->copy()->addMinutes(30);

        // Fetch all active appointments for this doctor (excluding cancelled ones)
        return Appointment::whereHas('schedule', function ($query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            })
            ->where('status', '!=', 'cancelled')
            ->get()
            ->contains(function ($existingAppointment) use ($startNew, $endNew) {
                $startExisting = Carbon::parse($existingAppointment->appointment_date);
                $endExisting = $startExisting->copy()->addMinutes(30);

                // Check for time slot overlap: (StartA < EndB) && (EndA > StartB)
                return $startNew->lt($endExisting) && $endNew->gt($startExisting);
            });
    }   
}