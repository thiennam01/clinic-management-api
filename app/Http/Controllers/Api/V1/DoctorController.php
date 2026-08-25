<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\DoctorConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Services\DoctorService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected DoctorService $doctorService
    ) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);

        $doctors = $this->doctorService->getAllDoctors($perPage);

        return response()->json([
            'success' => true,
            'message' => DoctorConstant::MSG_GET_LIST_SUCCESS,
            'data' => DoctorResource::collection($doctors),
            'meta' => [
                'current_page' => $doctors->currentPage(),
                'last_page' => $doctors->lastPage(),
                'per_page' => $doctors->perPage(),
                'total' => $doctors->total(),
            ],
        ]);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = $this->doctorService->createDoctor(
            $request->validated()
        );

        $doctor->load(['user', 'specialty']);

        return $this->successResponse(
            new DoctorResource($doctor),
            DoctorConstant::MSG_CREATE_SUCCESS,
            201
        );
    }

    public function show(int $doctor)
    {
        $doctorData = $this->doctorService->getDoctorById($doctor);

        return $this->successResponse(
            new DoctorResource($doctorData),
            DoctorConstant::MSG_GET_DETAIL_SUCCESS
        );
    }

    public function update(
        UpdateDoctorRequest $request,
        int $doctor
    ) {
        $doctorData = $this->doctorService->updateDoctor(
            $doctor,
            $request->validated()
        );

        return $this->successResponse(
            new DoctorResource($doctorData),
            DoctorConstant::MSG_UPDATE_SUCCESS
        );
    }

    public function destroy(int $doctor)
    {
        $this->doctorService->deleteDoctor($doctor);

        return $this->successResponse(
            null,
            DoctorConstant::MSG_DELETE_SUCCESS
        );
    }
}