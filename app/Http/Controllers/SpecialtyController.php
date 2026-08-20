<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSpecialtyRequest;
use App\Http\Requests\UpdateSpecialtyRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\SpecialtyResource;
use App\Services\SpecialtyService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SpecialtyService $specialtyService
    ) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $specialties = $this->specialtyService->getAllSpecialties($perPage);

        return new BaseResourceCollection($specialties);
    }

    public function store(StoreSpecialtyRequest $request)
    {
        $specialty = $this->specialtyService->createSpecialty($request->validated());

        return $this->successResponse(
            new SpecialtyResource($specialty),
            'Tạo chuyên khoa thành công',
            201
        );
    }

    public function show(int $id)
    {
        $specialty = $this->specialtyService->getSpecialtyById($id);

        return $this->successResponse(
            new SpecialtyResource($specialty),
            'Chi tiết chuyên khoa'
        );
    }

    public function update(UpdateSpecialtyRequest $request, int $id)
    {
        $specialty = $this->specialtyService->updateSpecialty($id, $request->validated());

        return $this->successResponse(
            new SpecialtyResource($specialty),
            'Cập nhật chuyên khoa thành công'
        );
    }

    public function destroy(int $id)
    {
        $this->specialtyService->deleteSpecialty($id);

        return $this->successResponse(
            null,
            'Xóa chuyên khoa thành công'
        );
    }
}