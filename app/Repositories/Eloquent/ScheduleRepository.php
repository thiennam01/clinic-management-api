<?php

namespace App\Repositories\Eloquent;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Schedule::query()->with('doctor.user');

        if (!empty($filters['doctor_id'])) {
            $query->where('doctor_id', $filters['doctor_id']);
        }

        if (!empty($filters['date'])) {
            $query->where('date', $filters['date']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('date', 'asc')->orderBy('start_time', 'asc')->paginate($perPage);
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
        return Schedule::with('doctor.user')->find($id);
    }

    public function hasConflict(int $doctorId, string $date, string $startTime, string $endTime): bool
    {
        return Schedule::where('doctor_id', $doctorId)
            ->where('date', $date)
            ->where(function ($q) use ($startTime, $endTime) {
                $q->whereBetween('start_time', [$startTime, $endTime])
                  ->orWhereBetween('end_time', [$startTime, $endTime])
                  ->orWhere(function ($sub) use ($startTime, $endTime) {
                      $sub->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                  });
            })->exists();
    }
}   