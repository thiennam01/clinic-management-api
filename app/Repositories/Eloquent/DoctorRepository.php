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
            ->latest('id')
            ->paginate($perPage);
    }

    public function findById(int $id): ?Doctor
    {
        return Doctor::with(['user', 'specialty'])
            ->find($id);
    }

    public function create(array $data): Doctor
    {
        $doctor = Doctor::create($data);

        return $doctor->load(['user', 'specialty']);
    }

    public function update(int $id, array $data): Doctor
    {
        $doctor = Doctor::findOrFail($id);

        $doctor->update($data);

        return $doctor->refresh()
            ->load(['user', 'specialty']);
    }

    public function delete(int $id): bool
    {
        $doctor = Doctor::findOrFail($id);

        return (bool) $doctor->delete();
    }
}