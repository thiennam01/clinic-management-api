<?php

namespace App\Repositories\Contracts;

interface AppointmentRepositoryInterface
{
    public function paginate(array $filters, int $perPage = 10);
    public function create(array $data);
    public function countBySchedule(int $scheduleId): int;
    public function find($id);
    public function update($id, array $data);
    

    public function hasConflict(int $doctorId, string $scheduledAt, int $scheduleId): bool;
}