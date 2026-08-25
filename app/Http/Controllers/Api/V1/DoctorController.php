<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    // Danh sách bác sĩ (kèm quan hệ user và specialty)
    public function index(): JsonResponse
    {
        $doctors = Doctor::with(['user', 'specialty'])->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách bác sĩ thành công',
            'data' => DoctorResource::collection($doctors)->response()->getData(true)
        ]);
    }

    // Tạo mới hồ sơ bác sĩ
    public function store(StoreDoctorRequest $request): JsonResponse
    {
        $doctor = Doctor::create($request->validated());
        $doctor->load(['user', 'specialty']);

        return response()->json([
            'success' => true,
            'message' => 'Tạo hồ sơ bác sĩ thành công',
            'data' => new DoctorResource($doctor)
        ], 201);
    }

    // Xem chi tiết 1 bác sĩ
    public function show(Doctor $doctor): JsonResponse
    {
        $doctor->load(['user', 'specialty']);

        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin bác sĩ thành công',
            'data' => new DoctorResource($doctor)
        ]);
    }

    // Cập nhật hồ sơ bác sĩ
    public function update(UpdateDoctorRequest $request, Doctor $doctor): JsonResponse
    {
        $doctor->update($request->validated());
        $doctor->load(['user', 'specialty']);

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật hồ sơ bác sĩ thành công',
            'data' => new DoctorResource($doctor)
        ]);
    }

    // Xóa bác sĩ (Soft Delete)
    public function destroy(Doctor $doctor): JsonResponse
    {
        $doctor->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa hồ sơ bác sĩ thành công'
        ]);
    }
}