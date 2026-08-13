<?php

namespace App\Services;

use App\Constants\SpecialtyConstant;
use App\Models\Specialty;
use App\Repositories\Contracts\SpecialtyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class SpecialtyService
{
    public function __construct(
        protected SpecialtyRepositoryInterface $specialtyRepository
    ) {}

    /**
     * Get paginated list of specialties.
     */
    public function getAllSpecialties(int $perPage = 10): LengthAwarePaginator
    {
        return $this->specialtyRepository->paginate($perPage);
    }

    /**
     * Find a specific specialty by ID, throw 404 if not found.
     */
    public function getSpecialtyById(int $id): Specialty
    {
        $specialty = $this->specialtyRepository->findById($id);

        if (!$specialty) {
            abort(404, SpecialtyConstant::MSG_NOT_FOUND);
        }

        return $specialty;
    }

    /**
     * Create a new specialty record with an auto-generated code.
     */
    public function createSpecialty(array $data): Specialty
    {
        $data['code'] = $this->specialtyRepository->generateNextCode();

        return $this->specialtyRepository->create($data);
    }

    /**
     * Update an existing specialty's information.
     */
    public function updateSpecialty(int $id, array $data): Specialty
    {
        $specialty = $this->getSpecialtyById($id);

        // Security: Prevent updating the auto-generated specialty code
        unset($data['code']); 

        return $this->specialtyRepository->update($specialty, $data);
    }

    /**
     * Delete a specialty record.
     */
    public function deleteSpecialty(int $id): bool
    {
        $specialty = $this->getSpecialtyById($id);
        
        return $this->specialtyRepository->delete($specialty);
    }
}