<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\ScheduleService;
use App\Constants\ScheduleConstant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Exception;
use Carbon\Carbon;

class ScheduleWebController extends Controller
{
    public function __construct(
        protected ScheduleService $scheduleService
    ) {}

    public function index(Request $request)
    {
        $schedules = $this->scheduleService->getSchedules(
            $request->only(['doctor_id', 'date', 'is_active']),
            10
        );

        $doctors = Doctor::with(['user', 'specialty'])
            ->whereHas('user')
            ->get()
            ->sortBy(fn ($doctor) => $doctor->user?->name);

        return view('schedules.index', compact('schedules', 'doctors'));
    }

    public function create()
    {
        $doctors = Doctor::with(['user', 'specialty'])
            ->whereHas('user')
            ->get()
            ->sortBy(fn ($doctor) => $doctor->user?->name);

        return view('schedules.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'max_patients' => 'required|integer|min:1|max:100',
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($end->lessThanOrEqualTo($start)) {
            throw ValidationException::withMessages([
                'end_time' => ScheduleConstant::MSG_INVALID_TIME_RANGE,
            ]);
        }

        try {
            $validated['current_patients'] = 0;
            $validated['is_active'] = true;

            $this->scheduleService->createSchedule($validated);

            return redirect()
                ->route('schedules.web.index')
                ->with('success', ScheduleConstant::MSG_CREATE_SUCCESS);

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['schedule' => $e->getMessage()]);
        }
    }

    public function edit(Schedule $schedule)
    {
        $schedule->load(['doctor.user', 'doctor.specialty']);

        $doctors = Doctor::with(['user', 'specialty'])
            ->whereHas('user')
            ->get()
            ->sortBy(fn ($doctor) => $doctor->user?->name);

        return view('schedules.edit', compact('schedule', 'doctors'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'max_patients' => 'required|integer|min:1|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $start = Carbon::createFromFormat('H:i', $validated['start_time']);
        $end = Carbon::createFromFormat('H:i', $validated['end_time']);

        if ($end->lessThanOrEqualTo($start)) {
            return back()
                ->withInput()
                ->withErrors([
                    'end_time' => ScheduleConstant::MSG_INVALID_TIME_RANGE,
                ]);
        }

        try {
            $validated['is_active'] = $request->boolean('is_active');

            $this->scheduleService->updateSchedule(
                $schedule->id,
                $validated
            );

            return redirect()
                ->route('schedules.web.index')
                ->with('success', ScheduleConstant::MSG_UPDATE_SUCCESS);

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['schedule' => $e->getMessage()]);
        }
    }

    public function destroy(Schedule $schedule)
    {
        if ($schedule->current_patients > 0) {
            return back()->withErrors([
                'schedule' => ScheduleConstant::MSG_DELETE_HAS_APPOINTMENTS,
            ]);
        }

        $this->scheduleService->deleteSchedule($schedule->id);

        return redirect()
            ->route('schedules.web.index')
            ->with('success', ScheduleConstant::MSG_DELETE_SUCCESS);
    }
}