<?php

namespace App\Repositories\Eloquent;

use App\Models\Patient;
use App\Repositories\Contracts\PatientRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PatientRepository implements PatientRepositoryInterface
{
    public function paginate(
        array $filters = [],
        int $perPage = 10
    ): LengthAwarePaginator {
        $query = Patient::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        | Search by:
        | - Patient code
        | - Full name
        | - Phone number
        */
        if (!empty($filters['q'])) {
            $keyword = trim($filters['q']);

            $query->where(function ($query) use ($keyword) {
                $query->where('full_name', 'ilike', "%{$keyword}%")
                    ->orWhere('phone', 'ilike', "%{$keyword}%")
                    ->orWhere('code', 'ilike', "%{$keyword}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Gender filter
        |--------------------------------------------------------------------------
        */
        if (!empty($filters['gender'])) {
            $query->where(
                'gender',
                $filters['gender']
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */
        return $query
            ->latest('id')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findById(int $id): ?Patient
    {
        return Patient::find($id);
    }

    public function create(array $data): Patient
    {
        return Patient::create($data);
    }

    public function update(
        Patient $patient,
        array $data
    ): Patient {
        $patient->update($data);

        return $patient->refresh();
    }

    public function delete(Patient $patient): bool
    {
        return (bool) $patient->delete();
    }

    /**
     * Automatically generate patient code:
     * BN-000001
     * BN-000002
     * ...
     */
    public function generateNextCode(): string
    {
        $lastPatient = Patient::withTrashed()
            ->latest('id')
            ->first();

        $nextId = $lastPatient
            ? $lastPatient->id + 1
            : 1;

        return 'BN-' . str_pad(
            $nextId,
            6,
            '0',
            STR_PAD_LEFT
        );
    }
}