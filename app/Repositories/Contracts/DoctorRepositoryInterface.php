<?php

namespace App\Repositories\Contracts;

use App\Models\Doctor;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DoctorRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator;

    public function findById(int $id): ?Doctor;

    public function create(array $data): Doctor;

    public function update(int $id, array $data): Doctor;

    public function delete(int $id): bool;
}