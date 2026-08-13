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
        // Lấy số lượng trang động từ request (mặc định là 10)
        $perPage = (int) $request->get('per_page', 10);
        $patients = $this->patientService->getAllPatients($perPage);

        // Truyền thẳng $patients (Paginator) vào BaseResourceCollection
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

    public function show(int $id)
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