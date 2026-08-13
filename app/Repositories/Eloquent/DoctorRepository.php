<?php

namespace App\Repositories\Eloquent;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function getAllPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Doctor::with(['user', 'specialty'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Doctor
    {
        return Doctor::with(['user', 'specialty'])->findOrFail($id);
    }

    public function create(array $data): Doctor
    {
        return Doctor::create($data);
    }

    public function update(int $id, array $data): Doctor
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->update($data);
        return $doctor->load(['user', 'specialty']);
    }

    public function delete(int $id): bool
    {
        $doctor = Doctor::findOrFail($id);
        return $doctor->delete();
    }
}