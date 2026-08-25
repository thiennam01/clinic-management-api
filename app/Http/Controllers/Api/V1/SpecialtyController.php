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
<<<<<<< HEAD
    use ApiResponse;
=======
    // List of specialties (paginated or fetch all)
    public function index(): JsonResponse
    {
        $specialties = Specialty::latest()->paginate(10);
>>>>>>> main

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

<<<<<<< HEAD
    // Create a new specialty
    public function store(StoreSpecialtyRequest $request)
=======
    // Create new specialty
    public function store(StoreSpecialtyRequest $request): JsonResponse
>>>>>>> main
    {
        $specialty = $this->specialtyService->createSpecialty($request->validated());

        return $this->successResponse(
            new SpecialtyResource($specialty),
            SpecialtyConstant::MSG_CREATE_SUCCESS,
            201
        );
    }

    // View details of a specialty
    public function show(Specialty $specialty): JsonResponse
    {
        $specialtyData = $this->specialtyService->getSpecialtyById($specialty);

        return $this->successResponse(
            new SpecialtyResource($specialtyData),
            SpecialtyConstant::MSG_GET_DETAIL_SUCCESS
        );
    }

    // Update a specialty
    public function update(UpdateSpecialtyRequest $request, Specialty $specialty): JsonResponse
    {
        $updatedSpecialty = $this->specialtyService->updateSpecialty($specialty, $request->validated());

        return $this->successResponse(
            new SpecialtyResource($updatedSpecialty),
            SpecialtyConstant::MSG_UPDATE_SUCCESS
        );
    }

    // Delete a specialty (Soft Delete)
    public function destroy(Specialty $specialty): JsonResponse
    {
        $this->specialtyService->deleteSpecialty($specialty);

        return $this->successResponse(
            null,
            SpecialtyConstant::MSG_DELETE_SUCCESS
        );
    }
}