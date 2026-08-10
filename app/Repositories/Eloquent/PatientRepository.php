<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatientRepository implements PatientRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Patient::query();

        // Xử lý tìm kiếm theo q (tên, SĐT, code)
        if (!empty($filters['q'])) {
            $keyword = $filters['q'];
            $query->where(function ($q) use ($keyword) {
                $q->where('full_name', 'like', "%{$keyword}%")
                  ->orWhere('phone', 'like', "%{$keyword}%")
                  ->orWhere('code', 'like', "%{$keyword}%");
            });
        }

        return $query->latest('id')->paginate($perPage);
    }

    public function findById(int $id): ?Patient
    {
        return Patient::find($id);
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(Patient $patient, array $data): Patient
    {
        $patient->update($data);
        return $patient;
    }

    public function delete(Patient $patient): bool
    {
        return $patient->delete();
    }

    /**
     * Tự động sinh mã bệnh nhân dạng BN-000001
     */
    public function generateNextCode(): string
    {
        $lastPatient = Patient::withTrashed()->latest('id')->first();
        $nextId = $lastPatient ? $lastPatient->id + 1 : 1;

        return 'BN-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
    }
}