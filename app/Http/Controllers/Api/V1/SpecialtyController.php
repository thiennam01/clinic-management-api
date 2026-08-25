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
    // List of specialties (paginated or fetch all)
    public function index(): JsonResponse
    {
        $specialties = Specialty::latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách chuyên khoa thành công',
            'data' => SpecialtyResource::collection($specialties)->response()->getData(true)
        ]);
    }

    // Create new specialty
    public function store(StoreSpecialtyRequest $request): JsonResponse
    {
        $specialty = Specialty::create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tạo chuyên khoa thành công',
            'data' => new SpecialtyResource($specialty)
        ], 201);
    }

    // View details of a specialty
    public function show(Specialty $specialty): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Lấy thông tin chuyên khoa thành công',
            'data' => new SpecialtyResource($specialty)
        ]);
    }

    // Update a specialty
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty): JsonResponse
    {
        $specialty->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật chuyên khoa thành công',
            'data' => new SpecialtyResource($specialty)
        ]);
    }

    // Delete a specialty (Soft Delete)
    public function destroy(Specialty $specialty): JsonResponse
    {
        $specialty->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa chuyên khoa thành công'
        ]);
    }
}