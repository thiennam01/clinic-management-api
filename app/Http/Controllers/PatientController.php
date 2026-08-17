<?php

namespace App\Http\Controllers;

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
        // Lấy tất cả query parameters từ request (bao gồm cả 'q' và 'per_page')
        $filters = $request->only(['q']);
        $perPage = (int) $request->get('per_page', 10);
        
        // Truyền $filters vào Service để xử lý tìm kiếm
        $patients = $this->patientService->getAllPatients($filters, $perPage);

        return new BaseResourceCollection($patients);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = $this->patientService->createPatient($request->validated());

        return $this->successResponse(
            new PatientResource($patient),
            'Tạo hồ sơ bệnh nhân thành công',
            201 
        );
    }

    public function show($id)
    {
        $patient = $this->patientService->getPatientById($id);

        return $this->successResponse(
            new PatientResource($patient),
            'Chi tiết hồ sơ bệnh nhân'
        );
    }

    public function update(UpdatePatientRequest $request, int $id)
    {
        $patient = $this->patientService->updatePatient($id, $request->validated());

        return $this->successResponse(
            new PatientResource($patient),
            'Cập nhật hồ sơ bệnh nhân thành công'
        );
    }

    public function destroy(int $id)
    {
        $this->patientService->deletePatient($id);

        return $this->successResponse(
            null,
            'Xóa hồ sơ bệnh nhân thành công'
        );
    }
}