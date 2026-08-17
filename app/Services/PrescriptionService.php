<?php

namespace App\Services;

use App\Repositories\Contracts\PrescriptionRepositoryInterface;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Constants\PrescriptionConstant;
use Illuminate\Support\Facades\DB;
use Exception;

class PrescriptionService
{
    /**
     * @var PrescriptionRepositoryInterface
     */
    protected $prescriptionRepository;

    /**
     * PrescriptionService constructor.
     *
     * @param PrescriptionRepositoryInterface $prescriptionRepository
     */
    public function __construct(PrescriptionRepositoryInterface $prescriptionRepository)
    {
        $this->prescriptionRepository = $prescriptionRepository;
    }

    /**
     * Get all prescriptions with their items.
     */
    public function getAllPrescriptions()
    {
        return Prescription::with(['items', 'examination', 'doctor'])->get();
    }

    /**
     * Get a single prescription by ID.
     */
    public function getPrescriptionById(int $id): Prescription
    {
        $prescription = Prescription::with(['items', 'examination', 'doctor'])->find($id);

        if (!$prescription) {
            throw new Exception(PrescriptionConstant::NOT_FOUND);
        }

        return $prescription;
    }

    /**
     * Handle business logic to create a prescription.
     */
    public function createPrescription(array $data): Prescription
    {
        return DB::transaction(function () use ($data) {
            $items = $data['items'] ?? [];
            unset($data['items']);

            return $this->prescriptionRepository->createWithItems($data, $items);
        });
    }

    /**
     * Add a new item to an existing prescription.
     */
    public function addItem(int $prescriptionId, array $data): PrescriptionItem
    {
        return DB::transaction(function () use ($prescriptionId, $data) {
            if ($this->prescriptionRepository->checkMedicineExists($prescriptionId, $data['medicine_id'])) {
                throw new Exception(PrescriptionConstant::MEDICINE_ALREADY_EXISTS);
            }

            return $this->prescriptionRepository->addItemToPrescription($prescriptionId, $data);
        });
    }

    /**
     * Update an existing prescription item.
     */
    public function updateItem(int $itemId, array $data): PrescriptionItem
    {
        return DB::transaction(function () use ($itemId, $data) {
            return $this->prescriptionRepository->updatePrescriptionItem($itemId, $data);
        });
    }

    /**
     * Remove a prescription item.
     */
    public function removeItem(int $itemId): bool
    {
        return DB::transaction(function () use ($itemId) {
            return $this->prescriptionRepository->removePrescriptionItem($itemId);
        });
    }
}