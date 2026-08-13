<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\DoctorConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use Illuminate\Http\JsonResponse;

class DoctorController extends Controller
{
    // Retrieve paginated list of doctors (with user and specialty relations)
    public function index(): JsonResponse
    {
        $doctors = Doctor::with(['user', 'specialty'])->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => DoctorConstant::MSG_GET_LIST_SUCCESS,
            'data' => DoctorResource::collection($doctors)->response()->getData(true)
        ]);
    }

    // Create a new doctor profile
    public function store(StoreDoctorRequest $request): JsonResponse
    {
        $doctor = Doctor::create($request->validated());
        $doctor->load(['user', 'specialty']);

        return response()->json([
            'success' => true,
            'message' => DoctorConstant::MSG_CREATE_SUCCESS,
            'data' => new DoctorResource($doctor)
        ], 201);
    }

    // Retrieve details of a specific doctor
    public function show(Doctor $doctor): JsonResponse
    {
        $doctor->load(['user', 'specialty']);

        return response()->json([
            'success' => true,
            'message' => DoctorConstant::MSG_GET_DETAIL_SUCCESS,
            'data' => new DoctorResource($doctor)
        ]);
    }

    // Update an existing doctor profile
    public function update(UpdateDoctorRequest $request, Doctor $doctor): JsonResponse
    {
        $doctor->update($request->validated());
        $doctor->load(['user', 'specialty']);

        return response()->json([
            'success' => true,
            'message' => DoctorConstant::MSG_UPDATE_SUCCESS,
            'data' => new DoctorResource($doctor)
        ]);
    }

    // Delete a doctor profile (Soft Delete)
    public function destroy(Doctor $doctor): JsonResponse
    {
        $doctor->delete();

        return response()->json([
            'success' => true,
            'message' => DoctorConstant::MSG_DELETE_SUCCESS
        ]);
    }
}