<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Services\AppointmentService;
use Illuminate\Http\Request;
use Exception;

class AppointmentController extends Controller
{
    public function __construct(protected AppointmentService $appointmentService) {}

    public function index(Request $request)
    {
        $appointments = $this->appointmentService->getAppointments($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách lịch hẹn thành công',
            'data' => AppointmentResource::collection($appointments),
            'meta' => [
                'current_page' => $appointments->currentPage(),
                'last_page' => $appointments->lastPage(),
                'per_page' => $appointments->perPage(),
                'total' => $appointments->total(),
            ]
        ]);
    }

    public function store(StoreAppointmentRequest $request)
    {
        // Lấy dữ liệu đã được validate chuẩn từ StoreAppointmentRequest
        $validated = $request->validated();
        
        // Tự động gán bệnh nhân là user đang đăng nhập
        $validated['patient_id'] = $request->user()->id;

        try {
            $appointment = $this->appointmentService->createAppointment($validated);

            return response()->json([
                'success' => true,
                'message' => 'Đặt lịch khám thành công!',
                'data' => new AppointmentResource($appointment)
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() <= 500 ? $e->getCode() : 400);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:scheduled,confirmed,completed,cancelled'
        ]);

        try {
            $appointment = $this->appointmentService->updateStatus($id, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái lịch khám thành công!',
                'data' => new AppointmentResource($appointment)
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], $e->getCode() >= 400 && $e->getCode() <= 500 ? $e->getCode() : 400);
        }
    }
}