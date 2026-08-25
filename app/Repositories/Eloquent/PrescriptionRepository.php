<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PrescriptionRepositoryInterface;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use Illuminate\Support\Facades\DB;

class PrescriptionRepository implements PrescriptionRepositoryInterface
{
    /**
     * @var Prescription
     */
    protected $model;

    /**
     * PrescriptionRepository constructor.
     *
     * @param Prescription $model
     */
    public function __construct(Prescription $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new prescription with items using a database transaction.
     *
     * @param array $data
     * @param array $items
     * @return Prescription
     */
    public function createWithItems(array $data, array $items): Prescription
    {
        return DB::transaction(function () use ($data, $items) {
            $prescription = $this->model->create($data);
            $prescription->items()->createMany($items);
            return $prescription->load(['items.medicine', 'doctor', 'examination']);
        });
    }

    /**
     * Add a new item to an existing prescription.
     *
     * @param int $prescriptionId
     * @param array $data
     * @return PrescriptionItem
     */
    public function addItemToPrescription(int $prescriptionId, array $data): PrescriptionItem
    {
        $prescription = Prescription::findOrFail($prescriptionId);
        return $prescription->items()->create($data);
    }

    /**
     * Update an existing prescription item.
     *
     * @param int $itemId
     * @param array $data
     * @return PrescriptionItem
     */
    public function updatePrescriptionItem(int $itemId, array $data): PrescriptionItem
    {
        $item = PrescriptionItem::findOrFail($itemId);
        $item->update($data);
        return $item->fresh('medicine');
    }

    /**
     * Remove a prescription item from storage.
     *
     * @param int $itemId
     * @return bool
     */
    public function removePrescriptionItem(int $itemId): bool
    {
        $item = PrescriptionItem::findOrFail($itemId);
        return $item->delete();
    }

    /**
     * Check if a specific medicine already exists in a prescription.
     *
     * @param int $prescriptionId
     * @param int $medicineId
     * @return bool
     */
    public function checkMedicineExists(int $prescriptionId, int $medicineId): bool
    {
        return PrescriptionItem::where('prescription_id', $prescriptionId)
            ->where('medicine_id', $medicineId)
            ->exists();
    }
}