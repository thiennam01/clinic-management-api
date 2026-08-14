<?php

namespace App\Repositories\Contracts;

use App\Models\Medicine;
use Illuminate\Pagination\LengthAwarePaginator;

interface MedicineRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator;
    public function findById(int $id): ?Medicine;
    public function create(array $data): Medicine;
    public function update(Medicine $medicine, array $data): bool;
    public function delete(Medicine $medicine): bool;
}