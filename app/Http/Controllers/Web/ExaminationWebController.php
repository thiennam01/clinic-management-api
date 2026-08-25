<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\ExaminationService;
use App\Constants\ExaminationConstant;
use Illuminate\Http\Request;

class ExaminationWebController extends Controller
{
    public function __construct(
        protected ExaminationService $examinationService
    ) {}

    /**
     * Display examination records.
     */
    public function index()
    {
        $examinations = $this->examinationService
            ->getAllExaminations();

        return view(
            'examinations.index',
            compact('examinations')
        );
    }

    /**
     * Display examination form.
     *
     * Only confirmed appointments can be examined.
     */
    public function create(Appointment $appointment)
    {
        $appointment->load([
            'patient',
            'schedule.doctor.user',
            'schedule.doctor.specialty',
            'examination',
        ]);

        if ($appointment->status !== 'confirmed') {
            return redirect()
                ->route(
                    'appointments.web.show',
                    $appointment->id
                )
                ->with(
                    'error',
                    ExaminationConstant::MSG_APPOINTMENT_NOT_CONFIRMED
                );
        }

        if ($appointment->examination) {
            return redirect()
                ->route(
                    'examinations.web.show',
                    $appointment->examination->id
                )
                ->with(
                    'error',
                    ExaminationConstant::MSG_EXAMINATION_ALREADY_EXISTS
                );
        }

        return view(
            'examinations.create',
            compact('appointment')
        );
    }

    /**
     * Store examination result.
     */
    public function store(
        Request $request,
        Appointment $appointment
    ) {
        $validated = $request->validate([
            'diagnosis' => [
                'required',
                'string',
                'max:5000',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'examined_at' => [
                'nullable',
                'date',
            ],
        ]);

        $validated['appointment_id'] = $appointment->id;

        $examination = $this->examinationService
            ->createExamination($validated);

        return redirect()
            ->route(
                'examinations.web.show',
                $examination->id
            )
            ->with(
                'success',
                ExaminationConstant::MSG_CREATE_SUCCESS
            );
    }

    /**
     * Display examination details.
     */
    public function show($id)
    {
        $examination = $this->examinationService
            ->getExaminationById((int) $id);

        $examination->load([
            'appointment',
            'patient',
            'doctor.user',
            'doctor.specialty',
            'prescription',
        ]);

        return view(
            'examinations.show',
            compact('examination')
        );
    }
}