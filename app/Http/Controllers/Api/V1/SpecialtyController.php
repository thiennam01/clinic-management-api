<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\SpecialtyConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Specialty\StoreSpecialtyRequest;
use App\Http\Requests\Specialty\UpdateSpecialtyRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\SpecialtyResource;
use App\Models\Specialty;
use App\Services\SpecialtyService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class SpecialtyController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected SpecialtyService $specialtyService
    ) {}

    // Get list of specialties with pagination
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $specialties = $this->specialtyService->getAllSpecialties($perPage);

        return new BaseResourceCollection($specialties);
    }

    // Create a new specialty
    public function store(StoreSpecialtyRequest $request)
    {
        $specialty = $this->specialtyService->createSpecialty($request->validated());

        return $this->successResponse(
            new SpecialtyResource($specialty),
            SpecialtyConstant::MSG_CREATE_SUCCESS,
            201
        );
    }

    // Show specialty details
    public function show(Specialty $specialty)
    {
        $specialtyData = $this->specialtyService->getSpecialtyById($specialty);

        return $this->successResponse(
            new SpecialtyResource($specialtyData),
            SpecialtyConstant::MSG_GET_DETAIL_SUCCESS
        );
    }

    // Update specialty information
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty)
    {
        $updatedSpecialty = $this->specialtyService->updateSpecialty($specialty, $request->validated());

        return $this->successResponse(
            new SpecialtyResource($updatedSpecialty),
            SpecialtyConstant::MSG_UPDATE_SUCCESS
        );
    }

    // Delete a specialty (Soft Delete)
    public function destroy(Specialty $specialty)
    {
        $this->specialtyService->deleteSpecialty($specialty);

        return $this->successResponse(
            null,
            SpecialtyConstant::MSG_DELETE_SUCCESS
        );
    }
}