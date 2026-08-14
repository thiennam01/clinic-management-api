<?php

namespace App\Services;

use App\Constants\AppointmentConstant;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Exception;

class AppointmentService
{
    public function __construct(
        protected AppointmentRepositoryInterface $appointmentRepository,
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function getAppointments(array $filters, int $perPage = 10)
    {
        return $this->appointmentRepository->paginate($filters, $perPage);
    }

    public function createAppointment(array $data)
    {
        // 1. Check if the work schedule exists
        $schedule = $this->scheduleRepository->find($data['schedule_id']);
        if (!$schedule) {
            throw new Exception(AppointmentConstant::MSG_SCHEDULE_NOT_FOUND, 404);
        }
        
        // 2. Check if the schedule has reached its maximum patient capacity
        $currentBookings = $this->appointmentRepository->countBySchedule($data['schedule_id']);
        if ($currentBookings >= $schedule->max_patients) {
            throw new Exception(AppointmentConstant::MSG_SCHEDULE_FULL, 422);
        }

        // 3. Check for doctor schedule conflicts (Task T2.6)
        $doctorId = $schedule->doctor_id;
        $appointmentDate = $data['appointment_date'] ?? $schedule->appointment_date;

        $isConflict = $this->appointmentRepository->hasConflict($doctorId, $appointmentDate, $data['schedule_id']);
        if ($isConflict) {
            throw new Exception(AppointmentConstant::MSG_DOCTOR_CONFLICT, 422);
        }

        // 4. Create the appointment record
        return $this->appointmentRepository->create($data);
    }

    public function updateStatus($id, string $newStatus)
    {
        // 1. Find the appointment via Repository
        $appointment = $this->appointmentRepository->find($id);
        if (!$appointment) {
            throw new Exception(AppointmentConstant::MSG_APPOINTMENT_NOT_FOUND, 404);
        }

        $currentStatus = $appointment->status ?? 'pending';

        // 2. Define State Machine transition rules (Task T2.5)
        $allowedTransitions = [
            'pending'   => ['scheduled', 'confirmed', 'cancelled'],
            'scheduled' => ['confirmed', 'cancelled'],
            'confirmed' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];

        // 3. Validate status transition validity
        if (!isset($allowedTransitions[$currentStatus]) || !in_array($newStatus, $allowedTransitions[$currentStatus])) {
            $errorMessage = sprintf(AppointmentConstant::MSG_INVALID_STATUS_TRANSITION, $currentStatus, $newStatus);
            throw new Exception($errorMessage, 422);
        }

        // 4. Update status via Repository
        return $this->appointmentRepository->update($id, ['status' => $newStatus]);
    }
}