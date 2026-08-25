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

    public function getSchedules(
        array $filters,
        int $perPage = 10
    ) {
        return $this->scheduleRepository->paginate(
            $filters,
            $perPage
        );
    }

    public function getScheduleById(int $id)
    {
        $schedule = $this->scheduleRepository->findById($id);

        if (!$schedule) {
            throw new Exception(
                'Không tìm thấy ca làm việc.',
                404
            );
        }

        return $schedule;
    }

    public function createSchedule(array $data)
    {
        if (
            $this->scheduleRepository->hasConflict(
                $data['doctor_id'],
                $data['date'],
                $data['start_time'],
                $data['end_time']
            )
        ) {
            throw new Exception(
                ScheduleConstant::MSG_CONFLICT,
                422
            );
        }

        return $this->scheduleRepository->create($data);
    }

    public function updateSchedule(
        int $id,
        array $data
    ) {

        $schedule = $this->getScheduleById($id);

        if (
            $this->scheduleRepository->hasConflict(
                $data['doctor_id'],
                $data['date'],
                $data['start_time'],
                $data['end_time'],
                $id
            )
        ) {
            throw new Exception(
                ScheduleConstant::MSG_CONFLICT,
                422
            );
        }

        return $this->scheduleRepository->update(
            $id,
            $data
        );
    }

    public function deleteSchedule(int $id): bool
    {
        return $this->scheduleRepository->delete($id);
    }
}