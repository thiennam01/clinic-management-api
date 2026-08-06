<?php

namespace App\Repositories\Contracts;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator;
    public function create(array $data): Schedule;
    public function findById(int $id): ?Schedule;
    public function hasConflict(int $doctorId, string $date, string $startTime, string $endTime): bool;
    public function find(int $id);
}