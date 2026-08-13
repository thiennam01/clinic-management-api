<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Specialty\StoreSpecialtyRequest;
use App\Http\Requests\Specialty\UpdateSpecialtyRequest;
use App\Http\Resources\SpecialtyResource;
use App\Models\Specialty;
use Illuminate\Http\JsonResponse;

class SpecialtyController extends Controller
{
    // Danh sách chuyên khoa (có phân trang hoặc lấy tất cả)
    public function index(): JsonResponse
    {
        $specialties = Specialty::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách chuyên khoa thành công',
            'data' => SpecialtyResource::collection($specialties)->response()->getData(true)
        ]);
    }

    // Tạo mới chuyên khoa
    public function store(StoreSpecialtyRequest $request): JsonResponse
    {
        $specialty = Specialty::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tạo chuyên khoa thành công',
            'data' => new SpecialtyResource($specialty)
        ], 201);
    }

    // Xem chi tiết 1 chuyên khoa
    public function show(Specialty $specialty): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin chuyên khoa thành công',
            'data' => new SpecialtyResource($specialty)
        ]);
    }

    // Cập nhật chuyên khoa
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty): JsonResponse
    {
        $specialty->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật chuyên khoa thành công',
            'data' => new SpecialtyResource($specialty)
        ]);
    }

    // Xóa chuyên khoa (Soft Delete)
    public function destroy(Specialty $specialty): JsonResponse
    {
        $specialty->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa chuyên khoa thành công'
        ]);
    }
}