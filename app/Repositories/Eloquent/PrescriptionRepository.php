<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\PrescriptionRepositoryInterface;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\Medicine;
use App\Constants\PrescriptionConstant;
use Exception;

class PrescriptionRepository implements PrescriptionRepositoryInterface
{
    protected $model;

    public function __construct(Prescription $model)
    {
        $this->model = $model;
    }

    public function createWithItems(array $data, array $items): Prescription
    {
        $prescription = $this->model->create($data);

        foreach ($items as $itemData) {
            $medicine = Medicine::where('id', $itemData['medicine_id'])->lockForUpdate()->first();

            if (!$medicine) {
                throw new Exception(PrescriptionConstant::MEDICINE_NOT_FOUND, 404);
            }

            if ($medicine->stock < $itemData['quantity']) {
                throw new Exception(PrescriptionConstant::INSUFFICIENT_STOCK, 422);
            }

            $medicine->decrement('stock', $itemData['quantity']);
            $prescription->items()->create($itemData);
        }

        return $prescription->load(['items.medicine', 'doctor', 'examination']);
    }

    public function addItemToPrescription(int $prescriptionId, array $data): PrescriptionItem
    {
        $prescription = Prescription::findOrFail($prescriptionId);

        $medicine = Medicine::where('id', $data['medicine_id'])->lockForUpdate()->first();

        if (!$medicine) {
            throw new Exception(PrescriptionConstant::MEDICINE_NOT_FOUND, 404);
        }

        if ($medicine->stock < $data['quantity']) {
            throw new Exception(PrescriptionConstant::INSUFFICIENT_STOCK, 422);
        }

        $medicine->decrement('stock', $data['quantity']);

        return $prescription->items()->create($data);
    }

    public function updatePrescriptionItem(int $itemId, array $data): PrescriptionItem
    {
        $item = PrescriptionItem::findOrFail($itemId);

        // Handle recalculating stock if the quantity is updated
        if (isset($data['quantity']) && $data['quantity'] != $item->quantity) {
            $medicine = Medicine::where('id', $item->medicine_id)->lockForUpdate()->first();

            if (!$medicine) {
                throw new Exception(PrescriptionConstant::MEDICINE_NOT_FOUND, 404);
            }

            // Delta = New quantity - Old quantity
            $delta = $data['quantity'] - $item->quantity;

            // Increase quantity (delta > 0) -> Check if inventory is sufficient
            if ($delta > 0 && $medicine->stock < $delta) {
                throw new Exception(PrescriptionConstant::INSUFFICIENT_STOCK, 422);
            }

            // Decrease/Increase inventory based on delta (delta > 0 then reduce stock, delta < 0 then increase stock)
            $medicine->decrement('stock', $delta);
        }

        $item->update($data);
        return $item->fresh('medicine');
    }

    public function removePrescriptionItem(int $itemId): bool
    {
        $item = PrescriptionItem::findOrFail($itemId);

        $medicine = Medicine::where('id', $item->medicine_id)->lockForUpdate()->first();

        if (!$medicine) {
            throw new Exception(PrescriptionConstant::MEDICINE_NOT_FOUND, 404);
        }

        // Restore the quantity of the medicine to the inventory: stock = stock + item.quantity
        $medicine->increment('stock', $item->quantity);

        return $item->delete();
    }

    public function checkMedicineExists(int $prescriptionId, int $medicineId): bool
    {
        return PrescriptionItem::where('prescription_id', $prescriptionId)
            ->where('medicine_id', $medicineId)
            ->exists();
    }
}