<?php

namespace App\Services;

use App\Constants\ScheduleConstant;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Exception;

class ScheduleService
{
    public function __construct(
        protected ScheduleRepositoryInterface $scheduleRepository
    ) {}

    /**
     * Get paginated schedules based on filters.
     */
    public function getSchedules(array $filters, int $perPage = 10)
    {
        return $this->scheduleRepository->paginate($filters, $perPage);
    }

    /**
     * Create a new schedule with conflict validation.
     */
    public function createSchedule(array $data)
    {
        // Check if the doctor already has a conflicting schedule during this timeframe
        if ($this->scheduleRepository->hasConflict($data['doctor_id'], $data['date'], $data['start_time'], $data['end_time'])) {
            throw new Exception(ScheduleConstant::MSG_CONFLICT, 422);
        }

        return $this->scheduleRepository->create($data);
    }
}   