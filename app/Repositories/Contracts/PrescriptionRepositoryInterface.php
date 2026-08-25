<?php

namespace App\Repositories\Contracts;

use App\Models\Prescription;
use App\Models\PrescriptionItem;

interface PrescriptionRepositoryInterface
{
    /**
     * Create a new prescription along with its items within a database transaction.
     *
     * @param array $data
     * @param array $items
     * @return Prescription
     */
    public function createWithItems(array $data, array $items): Prescription;

    /**
     * Add a new item to an existing prescription.
     *
     * @param int $prescriptionId
     * @param array $data
     * @return PrescriptionItem
     */
    public function addItemToPrescription(int $prescriptionId, array $data): PrescriptionItem;

    /**
     * Update an existing prescription item.
     *
     * @param int $itemId
     * @param array $data
     * @return PrescriptionItem
     */
    public function updatePrescriptionItem(int $itemId, array $data): PrescriptionItem;

    /**
     * Remove a prescription item from storage.
     *
     * @param int $itemId
     * @return bool
     */
    public function removePrescriptionItem(int $itemId): bool;

    /**
     * Check if a specific medicine already exists in a prescription.
     *
     * @param int $prescriptionId
     * @param int $medicineId
     * @return bool
     */
    public function checkMedicineExists(int $prescriptionId, int $medicineId): bool;
}