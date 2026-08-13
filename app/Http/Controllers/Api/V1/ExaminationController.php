<?php

namespace App\Http\Controllers\Api\V1; // Hoặc App\Http\Controllers\Api tùy theo route v1 của bạn

use App\Constants\ExaminationConstant;
use App\Http\Controllers\Controller;
use App\Services\ExaminationService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ExaminationService $examinationService
    ) {}

    /**
     * Display a listing of the examinations.
     */
    public function index()
    {
        $examinations = $this->examinationService->getAllExaminations();

        return $this->successResponse(
            $examinations,
            ExaminationConstant::MSG_GET_LIST_SUCCESS
        );
    }

    /**
     * Store a newly created examination in storage from a confirmed appointment.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'appointment_id' => ['required', 'exists:appointments,id'],
            'diagnosis'      => ['required', 'string'],
            'notes'          => ['nullable', 'string'],
            'examined_at'    => ['nullable', 'date'],
        ]);

        $examination = $this->examinationService->createExamination($validatedData);

        return $this->successResponse(
            $examination,
            ExaminationConstant::MSG_CREATE_SUCCESS,
            201
        );
    }

    /**
     * Display the specified examination.
     */
    public function show($id)
    {
        $examination = $this->examinationService->getExaminationById($id);

        return $this->successResponse(
            $examination,
            ExaminationConstant::MSG_GET_DETAIL_SUCCESS
        );
    }
}