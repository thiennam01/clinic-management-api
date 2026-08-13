<?php

namespace App\Services;

use App\Constants\PatientConstant;
use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatientService
{
    public function __construct(
        protected PatientRepositoryInterface $patientRepository
    ) {}

    /**
     * Get paginated list of patients with optional filters.
     */
    public function getAllPatients(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        return $this->patientRepository->paginate($filters, $perPage);
    }

    /**
     * Find a specific patient by ID, throw 404 if not found.
     */
    public function getPatientById($id): Patient
    {
        // Cast ID to integer to ensure data type safety
        $id = (int) $id;
        
        $patient = $this->patientRepository->findById($id);

        if (!$patient) {
            abort(404, PatientConstant::MSG_NOT_FOUND);
        }

        return $patient;
    }

    /**
     * Create a new patient record with an auto-generated code.
     */
    public function createPatient(array $data): Patient
    {
        // Automatically generate a unique patient code (e.g., BN-000001)
        $data['code'] = $this->patientRepository->generateNextCode();

        return $this->patientRepository->create($data);
    }

    /**
     * Update an existing patient's information.
     */
    public function updatePatient($id, array $data): Patient
    {
        $id = (int) $id;
        $patient = $this->getPatientById($id);

        // Security: Prevent updating the auto-generated patient code
        unset($data['code']);

        return $this->patientRepository->update($patient, $data);
    }

    /**
     * Delete a patient record.
     */
    public function deletePatient($id): bool
    {
        $id = (int) $id;
        $patient = $this->getPatientById($id);
        
        return $this->patientRepository->delete($patient);
    }
}