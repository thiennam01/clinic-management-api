<?php

namespace App\Services;

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
            throw new Exception('Lịch làm việc không tồn tại.', 404);
        }

        // 2. Check if the schedule has available slots (max_patients)
        $currentBookings = $this->appointmentRepository->countBySchedule($data['schedule_id']);
        if ($currentBookings >= $schedule->max_patients) {
            throw new Exception('Khung giờ này đã hết chỗ.', 422);
        }

        // 3. Assign patient_id from the logged-in user (or pass in)
        return $this->appointmentRepository->create($data);
    }
}