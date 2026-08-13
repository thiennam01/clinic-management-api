<?php

namespace App\Http\Controllers;

use App\Constants\PatientConstant;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\PatientResource;
use App\Services\PatientService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected PatientService $patientService
    ) {}

    public function index(Request $request)
    {
        // Retrieve all query parameters from request (including 'q' and 'per_page')
        $filters = $request->only(['q']);
        $perPage = (int) $request->get('per_page', 10);
        
        // Pass $filters to the Service to handle searching/filtering
        $patients = $this->patientService->getAllPatients($filters, $perPage);

        return new BaseResourceCollection($patients);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = $this->patientService->createPatient($request->validated());

        return $this->successResponse(
            new PatientResource($patient),
            PatientConstant::MSG_CREATE_SUCCESS,
            201 
        );
    }

    public function show($id)
    {
        $patient = $this->patientService->getPatientById($id);

        return $this->successResponse(
            new PatientResource($patient),
            PatientConstant::MSG_GET_DETAIL_SUCCESS
        );
    }

    public function update(UpdatePatientRequest $request, int $id)
    {
        $patient = $this->patientService->updatePatient($id, $request->validated());

        return $this->successResponse(
            new PatientResource($patient),
            PatientConstant::MSG_UPDATE_SUCCESS
        );
    }

    public function destroy(int $id)
    {
        $this->patientService->deletePatient($id);

        return $this->successResponse(
            null,
            PatientConstant::MSG_DELETE_SUCCESS
        );
    }
}