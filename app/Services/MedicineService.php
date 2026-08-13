<?php

namespace App\Services;

use App\Constants\MedicineMessage;
use App\Models\Medicine;
use App\Repositories\Contracts\MedicineRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class MedicineService
{
    protected MedicineRepositoryInterface $medicineRepository;

    public function __construct(MedicineRepositoryInterface $medicineRepository)
    {
        $this->medicineRepository = $medicineRepository;
    }

    /**
     * Get paginated list of medicines.
     */
    public function getAllMedicines(int $perPage = 15): LengthAwarePaginator
    {
        return $this->medicineRepository->getAll($perPage);
    }

    /**
     * Find a medicine by its ID.
     */
    public function getMedicineById(int $id): ?Medicine
    {
        return $this->medicineRepository->findById($id);
    }

    /**
     * Create a new medicine record.
     */
    public function createMedicine(array $data): Medicine
    {
        return $this->medicineRepository->create($data);
    }

    /**
     * Update an existing medicine.
     */
    public function updateMedicine(int $id, array $data): Medicine
    {
        $medicine = $this->medicineRepository->findById($id);

        if (!$medicine) {
            throw ValidationException::withMessages([
                'id' => [MedicineMessage::NOT_FOUND],
            ]);
        }

        $this->medicineRepository->update($medicine, $data);

        return $medicine->fresh();
    }

    /**
     * Delete a medicine record.
     */
    public function deleteMedicine(int $id): bool
    {
        $medicine = $this->medicineRepository->findById($id);

        if (!$medicine) {
            throw ValidationException::withMessages([
                'id' => [MedicineMessage::NOT_FOUND],
            ]);
        }

        return $this->medicineRepository->delete($medicine);
    }

    /**
     * Validate medicine list for new prescriptions.
     * Business rule: Inactive medicines (is_active = false) cannot be prescribed.
     */
    public function validateMedicinesForPrescription(array $medicineIds): void
    {
        $medicines = Medicine::whereIn('id', $medicineIds)->get();

        foreach ($medicines as $medicine) {
            if (!$medicine->is_active) {
                throw ValidationException::withMessages([
                    'medicines' => [MedicineMessage::inactivePrescription($medicine->name, $medicine->code)],
                ]);
            }
        }
    }
}