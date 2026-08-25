<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAppointmentWebRequest;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Schedule;
use App\Services\AppointmentService;
use App\Constants\AppointmentConstant;
use Illuminate\Http\Request;

class AppointmentWebController extends Controller
{
    public function __construct(
        protected AppointmentService $appointmentService
    ) {}

    public function index(Request $request)
    {
        $appointments = $this->appointmentService->getAppointments(
            $request->only(['patient_id', 'status']),
            10
        );

        $statuses = AppointmentConstant::getStatuses();

        return view('appointments.index', compact(
            'appointments',
            'statuses'
        ));
    }

    public function create()
    {
        $patients = Patient::query()
            ->orderBy('full_name')
            ->get();

        $doctors = \App\Models\Doctor::with([
            'user',
            'specialty',
        ])
            ->whereHas('user')
            ->orderBy(
                \App\Models\User::select('name')
                    ->whereColumn('users.id', 'doctors.user_id')
            )
            ->get();

        $schedules = Schedule::query()
            ->where('is_active', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get([
                'id',
                'doctor_id',
                'date',
                'start_time',
                'end_time',
                'max_patients',
                'current_patients',
            ]);

        $statuses = AppointmentConstant::getStatuses();

        return view('appointments.create', compact(
            'patients',
            'doctors',
            'schedules',
            'statuses'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => [
                'required',
                'exists:patients,id',
            ],

            'doctor_id' => [
                'required',
                'exists:doctors,id',
            ],

            'appointment_date' => [
                'required',
                'date',
            ],

            'appointment_time' => [
                'required',
                'date_format:H:i',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ]);

        $appointmentDate = \Carbon\Carbon::createFromFormat(
            'Y-m-d H:i',
            $validated['appointment_date'] . ' ' . $validated['appointment_time']
        );

        $schedule = Schedule::query()
            ->where('doctor_id', $validated['doctor_id'])
            ->where('date', $appointmentDate->toDateString())
            ->where('is_active', true)
            ->whereTime('start_time', '<=', $appointmentDate->format('H:i:s'))
            ->whereTime('end_time', '>', $appointmentDate->format('H:i:s'))
            ->whereColumn('current_patients', '<', 'max_patients')
            ->first();

        if (!$schedule) {
            return back()
                ->withInput()
                ->withErrors([
                    'appointment_date' =>
                        AppointmentConstant::MSG_SCHEDULE_NOT_AVAILABLE,
                ]);
        }

        $data = [
            'patient_id' => $validated['patient_id'],
            'schedule_id' => $schedule->id,
            'appointment_date' => $appointmentDate,
            'notes' => $validated['notes'] ?? null,
        ];

        $appointment = $this->appointmentService->createAppointment($data);

        return redirect()
            ->route('appointments.web.show', $appointment->id)
            ->with(
                'success',
                AppointmentConstant::MSG_CREATE_SUCCESS
            );
    }

    public function show(Appointment $appointment)
    {
        $appointment->load([
            'patient',
            'schedule.doctor.user',
            'schedule.doctor.specialty',
        ]);

        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $patients = Patient::query()
            ->orderBy('full_name')
            ->get();

        $schedules = Schedule::with([
            'doctor.user',
            'doctor.specialty',
        ])
            ->where('is_active', true)
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        $statuses = AppointmentConstant::getStatuses();

        return view('appointments.edit', compact(
            'appointment',
            'patients',
            'schedules',
            'statuses'
        ));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'status' => 'required|in:pending,scheduled,confirmed,completed,cancelled',
            'notes' => 'nullable|string|max:500',
        ]);

        $schedule = Schedule::findOrFail($validated['schedule_id']);

        $appointment->update([
            'schedule_id' => $schedule->id,
            'appointment_date' => $schedule->date->format('Y-m-d') . ' ' . $schedule->start_time,
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()
            ->route('appointments.web.show', $appointment->id)
            ->with(
                'success',
                AppointmentConstant::MSG_UPDATE_SUCCESS
            );
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,confirmed,completed,cancelled',
            ],
        ]);

        try {
            $this->appointmentService->updateStatus(
                $appointment->id,
                $validated['status']
            );

            return redirect()
                ->route('appointments.web.show', $appointment->id)
                ->with(
                    'success',
                    AppointmentConstant::MSG_UPDATE_STATUS_SUCCESS
                );

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' => $e->getMessage(),
                ]);
        }
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()
            ->route('appointments.web.index')
            ->with(
                'success',
                AppointmentConstant::MSG_DELETE_SUCCESS
            );
    }
}