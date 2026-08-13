<?php

namespace App\Repositories\Eloquent;

use App\Models\Medicine;
use App\Repositories\Contracts\MedicineRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class MedicineRepository implements MedicineRepositoryInterface
{
    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Medicine::paginate($perPage);
    }

    public function findById(int $id): ?Medicine
    {
        return Medicine::find($id);
    }

    public function create(array $data): Medicine
    {
        return Medicine::create($data);
    }

    public function update(Medicine $medicine, array $data): bool
    {
        return $medicine->update($data);
    }

    public function delete(Medicine $medicine): bool
    {
        return $medicine->delete();
    }
}