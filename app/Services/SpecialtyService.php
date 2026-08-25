<?php

namespace App\Services;

use App\Models\Specialty;
use App\Repositories\Contracts\SpecialtyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SpecialtyService
{
    public function __construct(
        protected SpecialtyRepositoryInterface $specialtyRepository
    ) {}

    public function getAllSpecialties(int $perPage = 10): LengthAwarePaginator
    {
        return $this->specialtyRepository->paginate($perPage);
    }

    public function getSpecialtyById(int $id): Specialty
    {
        $specialty = $this->specialtyRepository->findById($id);

        if (!$specialty) {
            abort(404, 'Không tìm thấy thông tin chuyên khoa');
        }

        return $specialty;
    }

    public function createSpecialty(array $data): Specialty
    {
        $data['code'] = $this->specialtyRepository->generateNextCode();

        return $this->specialtyRepository->create($data);
    }

    public function updateSpecialty(int $id, array $data): Specialty
    {
        $specialty = $this->getSpecialtyById($id);

        unset($data['code']); // Do not allow updating the specialty code

        return $this->specialtyRepository->update($specialty, $data);
    }

    public function deleteSpecialty(int $id): bool
    {
        $specialty = $this->getSpecialtyById($id);
        return $this->specialtyRepository->delete($specialty);
    }
}