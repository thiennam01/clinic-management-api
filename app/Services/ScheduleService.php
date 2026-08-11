<?php

namespace App\Services;

use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Exception;

class ScheduleService
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    public function getSchedules(array $filters, int $perPage = 10)
    {
        return $this->scheduleRepository->paginate($filters, $perPage);
    }

    public function createSchedule(array $data)
    {
        // Check if the schedule overlaps with another shift for the same doctor
        if ($this->scheduleRepository->hasConflict($data['doctor_id'], $data['date'], $data['start_time'], $data['end_time'])) {
            throw new Exception('Bác sĩ đã có lịch làm việc trùng với khung giờ này.', 422);
        }

        return $this->scheduleRepository->create($data);
    }
}