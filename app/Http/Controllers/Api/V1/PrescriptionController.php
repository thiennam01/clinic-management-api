<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Prescription\StorePrescriptionRequest;
use App\Http\Requests\PrescriptionItem\AddPrescriptionItemRequest;
use App\Http\Requests\PrescriptionItem\UpdatePrescriptionItemRequest;
use App\Services\PrescriptionService;
use App\Constants\PrescriptionConstant;
use Illuminate\Http\JsonResponse;

class PrescriptionController extends Controller
{
    /**
     * @var PrescriptionService
     */
    protected $prescriptionService;

    /**
     * PrescriptionController constructor.
     *
     * @param PrescriptionService $prescriptionService
     */
    public function __construct(PrescriptionService $prescriptionService)
    {
        $this->prescriptionService = $prescriptionService;
    }

    /**
     * Store a newly created prescription in storage.
     *
     * @param StorePrescriptionRequest $request
     * @return JsonResponse
     */
    public function store(StorePrescriptionRequest $request): JsonResponse
    {
        try {
            $prescription = $this->prescriptionService->createPrescription($request->validated());

            return response()->json([
                'success' => true,
                'message' => PrescriptionConstant::CREATED_SUCCESS,
                'data' => $prescription
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => PrescriptionConstant::CREATE_FAILED,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Display a listing of the prescriptions.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $prescriptions = $this->prescriptionService->getAllPrescriptions();

            return response()->json([
                'success' => true,
                'data' => $prescriptions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Display the specified prescription with items.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $prescription = $this->prescriptionService->getPrescriptionById($id);

            return response()->json([
                'success' => true,
                'data' => $prescription
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Add a new item to an existing prescription.
     *
     * @param AddPrescriptionItemRequest $request
     * @param int $prescriptionId
     * @return JsonResponse
     */
    public function addItem(AddPrescriptionItemRequest $request, int $prescriptionId): JsonResponse
    {
        try {
            $item = $this->prescriptionService->addItem($prescriptionId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => PrescriptionConstant::ITEM_ADDED_SUCCESS,
                'data' => $item
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Update an existing prescription item.
     *
     * @param UpdatePrescriptionItemRequest $request
     * @param int $itemId
     * @return JsonResponse
     */
    public function updateItem(UpdatePrescriptionItemRequest $request, int $itemId): JsonResponse
    {
        try {
            $item = $this->prescriptionService->updateItem($itemId, $request->validated());

            return response()->json([
                'success' => true,
                'message' => PrescriptionConstant::ITEM_UPDATED_SUCCESS,
                'data' => $item
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Remove a prescription item.
     *
     * @param int $itemId
     * @return JsonResponse
     */
    public function removeItem(int $itemId): JsonResponse
    {
        try {
            $this->prescriptionService->removeItem($itemId);

            return response()->json([
                'success' => true,
                'message' => PrescriptionConstant::ITEM_REMOVED_SUCCESS
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}