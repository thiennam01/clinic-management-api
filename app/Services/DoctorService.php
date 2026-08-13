<?php

namespace App\Services;

use App\Models\Doctor;
use App\Repositories\Contracts\DoctorRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DoctorService
{
    public function __construct(
        protected DoctorRepositoryInterface $doctorRepository
    ) {}

    public function getAllDoctors(int $perPage = 10): LengthAwarePaginator
    {
        return $this->doctorRepository->getAllPaginated($perPage);
    }

    public function getDoctorById(int $id): ?Doctor
    {
        return $this->doctorRepository->findById($id);
    }

    public function createDoctor(array $data): Doctor
    {
        return $this->doctorRepository->create($data);
    }

    public function updateDoctor(int $id, array $data): Doctor
    {
        return $this->doctorRepository->update($id, $data);
    }

    public function deleteDoctor(int $id): bool
    {
        return $this->doctorRepository->delete($id);
    }
}