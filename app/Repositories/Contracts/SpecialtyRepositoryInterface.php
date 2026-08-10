<?php

namespace App\Repositories\Contracts;

use App\Models\Specialty;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SpecialtyRepositoryInterface
{
    public function paginate(int $perPage = 10): LengthAwarePaginator;
    public function findById(int $id): ?Specialty;
    public function create(array $data): Specialty;
    public function update(Specialty $specialty, array $data): Specialty;
    public function delete(Specialty $specialty): bool;
    public function generateNextCode(): string;
}