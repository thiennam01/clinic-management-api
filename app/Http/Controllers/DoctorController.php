<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Http\Resources\BaseResourceCollection;
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

        return new BaseResourceCollection($doctors);
    }

    public function store(StoreDoctorRequest $request)
    {
        $doctor = $this->doctorService->createDoctor($request->validated());

        return $this->successResponse(
            new DoctorResource($doctor),
            'Tạo bác sĩ thành công',
            201
        );
    }

    public function show(int $id)
    {
        $doctor = $this->doctorService->getDoctorById($id);

        return $this->successResponse(
            new DoctorResource($doctor),
            'Chi tiết bác sĩ'
        );
    }

    public function update(UpdateDoctorRequest $request, int $id)
    {
        $doctor = $this->doctorService->updateDoctor($id, $request->validated());

        return $this->successResponse(
            new DoctorResource($doctor),
            'Cập nhật thông tin bác sĩ thành công'
        );
    }

    public function destroy(int $id)
    {
        $this->doctorService->deleteDoctor($id);

        return $this->successResponse(
            null,
            'Xóa bác sĩ thành công'
        );
    }
}