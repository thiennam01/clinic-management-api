<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function paginate(
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator {

        $query = Schedule::query()
            ->with([
                'doctor.user',
                'doctor.specialty',
            ]);

        if (!empty($filters['doctor_id'])) {
            $query->where(
                'doctor_id',
                $filters['doctor_id']
            );
        }

        if (!empty($filters['date'])) {
            $query->where(
                'date',
                $filters['date']
            );
        }

        if (isset($filters['is_active'])) {
            $query->where(
                'is_active',
                $filters['is_active']
            );
        }

        return $query
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->paginate($perPage);
    }

    public function find(int $id)
    {
        return Schedule::find($id);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function findById(int $id): ?Schedule
    {
        return Schedule::with([
            'doctor.user',
            'doctor.specialty',
        ])->find($id);
    }

    public function update(
        int $id,
        array $data
    ): ?Schedule {

        $schedule = Schedule::find($id);

        if (!$schedule) {
            return null;
        }

        $schedule->update($data);

        return $schedule->fresh([
            'doctor.user',
            'doctor.specialty',
        ]);
    }

    public function delete(int $id): bool
    {
        $schedule = Schedule::find($id);

        if (!$schedule) {
            return false;
        }

        return (bool) $schedule->delete();
    }

    public function hasConflict(
        int $doctorId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $ignoreId = null
    ): bool {

        $query = Schedule::query()
            ->where('doctor_id', $doctorId)
            ->where('date', $date);

        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query
            ->where(function ($q) use (
                $startTime,
                $endTime
            ) {

                $q
                    ->where('start_time', '<', $endTime)
                    ->where('end_time', '>', $startTime);

            })
            ->exists();
    }
}