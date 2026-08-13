<?php

namespace App\Http\Controllers\Api;

use App\Constants\ScheduleConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Resources\BaseResourceCollection;
use App\Http\Resources\ScheduleResource;
use App\Services\ScheduleService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ScheduleService $scheduleService
    ) {}

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 10);
        $schedules = $this->scheduleService->getSchedules(
            $request->only(['doctor_id', 'date', 'is_active']),
            $perPage
        );

        return (new BaseResourceCollection($schedules))
            ->additional([
                'success' => true,
                'message' => ScheduleConstant::MSG_GET_LIST_SUCCESS,
            ]);
    }

    public function store(StoreScheduleRequest $request)
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());

        return $this->successResponse(
            new ScheduleResource($schedule),
            ScheduleConstant::MSG_CREATE_SUCCESS,
            201
        );
    }
}