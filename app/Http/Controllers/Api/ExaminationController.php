<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ExaminationService;
use Illuminate\Http\Request;
use Exception;

class ExaminationController extends Controller
{
    public function __construct(
        protected ExaminationService $examinationService
    ) {}

    /**
     * Display a listing of the examinations.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $examinations = $this->examinationService->getAllExaminations();

            return response()->json([
                'success' => true,
                'message' => 'Lấy danh sách phiếu khám thành công',
                'data'    => $examinations
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created examination in storage from a confirmed appointment.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        // Validate request inputs (doctor_id and patient_id are automatically derived from appointment)
        $validatedData = $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis'      => 'required|string',
            'notes'          => 'nullable|string',
            'examined_at'    => 'nullable|date',
        ]);

        try {
            $examination = $this->examinationService->createExamination($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'Tạo phiếu khám từ lịch hẹn thành công',
                'data'    => $examination
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 422);
        }
    }

    /**
     * Display the specified examination.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $examination = $this->examinationService->getExaminationById($id);

            return response()->json([
                'success' => true,
                'message' => 'Lấy thông tin phiếu khám thành công',
                'data'    => $examination
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 404);
        }
    }
}