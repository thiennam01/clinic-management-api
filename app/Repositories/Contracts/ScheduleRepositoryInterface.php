<?php

namespace App\Repositories\Contracts;

use App\Models\Schedule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ScheduleRepositoryInterface
{
    public function paginate(
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator;

    public function find(int $id);

    public function findById(int $id): ?Schedule;

    public function create(array $data): Schedule;

    public function update(
        int $id,
        array $data
    ): ?Schedule;

    public function delete(int $id): bool;

    public function hasConflict(
        int $doctorId,
        string $date,
        string $startTime,
        string $endTime,
        ?int $ignoreId = null
    ): bool;
}