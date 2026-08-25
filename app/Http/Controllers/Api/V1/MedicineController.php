<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\MedicineMessage;
use App\Http\Requests\Medicine\StoreMedicineRequest;
use App\Http\Requests\Medicine\UpdateMedicineRequest;
use App\Services\MedicineService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicineController extends Controller
{
    protected MedicineService $medicineService;

    public function __construct(MedicineService $medicineService)
    {
        $this->medicineService = $medicineService;
    }

    /**
     * Display a listing of the medicines (MEDICINES.FINDALL).
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 15);
        $medicines = $this->medicineService->getAllMedicines($perPage);

        return response()->json([
            'success' => true,
            'data' => $medicines,
        ]);
    }

    /**
     * Store a newly created medicine in storage (MEDICINES.CREATE).
     */
    public function store(StoreMedicineRequest $request): JsonResponse
    {
        $medicine = $this->medicineService->createMedicine($request->validated());

        return response()->json([
            'success' => true,
            'message' => MedicineMessage::CREATE_SUCCESS,
            'data' => $medicine,
        ], 201);
    }

    /**
     * Display the specified medicine (MEDICINES.FINDONE).
     */
    public function show(int $id): JsonResponse
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (!$medicine) {
            return response()->json([
                'success' => false,
                'message' => MedicineMessage::NOT_FOUND,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $medicine,
        ]);
    }

    /**
     * Update the specified medicine in storage (MEDICINES.UPDATE).
     */
    public function update(UpdateMedicineRequest $request, int $id): JsonResponse
    {
        $medicine = $this->medicineService->updateMedicine($id, $request->validated());

        return response()->json([
            'success' => true,
            'message' => MedicineMessage::UPDATE_SUCCESS,
            'data' => $medicine,
        ]);
    }

    /**
     * Remove the specified medicine from storage (MEDICINES.DELETE).
     */
    public function destroy(int $id): JsonResponse
    {
        $this->medicineService->deleteMedicine($id);

        return response()->json([
            'success' => true,
            'message' => MedicineMessage::DELETE_SUCCESS,
        ]);
    }

    /**
     * View prescribed orders containing medicines (Fulfilling mentor's requirement: Xem đơn thuốc đã kê).
     */
    public function prescribedHistory(int $id): JsonResponse
    {
        $medicine = $this->medicineService->getMedicineById($id);

        if (!$medicine) {
            return response()->json([
                'success' => false,
                'message' => MedicineMessage::NOT_FOUND,
            ], 404);
        }

        // Retrieve prescriptions or examination details containing this medicine based on project Eloquent relationships
        // $prescriptions = $medicine->prescriptions()->paginate(15);

        return response()->json([
            'success' => true,
            'data' => [
                'medicine' => $medicine,
                // 'prescriptions' => $prescriptions, // Uncomment once the relation is established in the model
            ],
        ]);
    }
}