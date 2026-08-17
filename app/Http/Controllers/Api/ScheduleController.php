<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Resources\ScheduleResource;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $schedules = $this->scheduleService->getSchedules(
            $request->only(['doctor_id', 'date', 'is_active']),
            $request->get('per_page', 10)
        );

        return response()->json([
            'success' => true,
            'message' => 'Lấy danh sách lịch làm việc thành công',
            'data'    => ScheduleResource::collection($schedules),
            'meta'    => [
                'current_page' => $schedules->currentPage(),
                'last_page'    => $schedules->lastPage(),
                'per_page'     => $schedules->perPage(),
                'total'        => $schedules->total(),
            ]
        ]);
    }

    public function store(StoreScheduleRequest $request): JsonResponse
    {
        try {
            $schedule = $this->scheduleService->createSchedule($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tạo lịch làm việc thành công',
                'data'    => new ScheduleResource($schedule)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], $e->getCode() ?: 400);
        }
    }
}