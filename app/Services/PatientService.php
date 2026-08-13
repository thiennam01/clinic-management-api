<?php

namespace App\Services;

use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatientService
{
    public function __construct(
        protected PatientRepositoryInterface $patientRepository
    ) {}

    public function getAllPatients(int $perPage = 10): LengthAwarePaginator
    {
        return $this->patientRepository->paginate($perPage);
    }

    public function getPatientById(int $id): Patient
    {
        $patient = $this->patientRepository->findById($id);

        if (!$patient) {
            abort(404, 'Không tìm thấy thông tin bệnh nhân');
        }

        return $patient;
    }

    public function createPatient(array $data): Patient
    {
        // Tự động sinh mã code cho patient (VD: BN-000001)
        $data['code'] = $this->patientRepository->generateNextCode();

        return $this->patientRepository->create($data);
    }

    public function updatePatient(int $id, array $data): Patient
    {
        $patient = $this->getPatientById($id);

        // Bảo vệ: Không cho phép sửa mã code đã tự sinh
        unset($data['code']);

        return $this->patientRepository->update($patient, $data);
    }

    public function deletePatient(int $id): bool
    {
        $patient = $this->getPatientById($id);
        return $this->patientRepository->delete($patient);
    }
}