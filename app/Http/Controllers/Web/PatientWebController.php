<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StorePatientRequest;
use App\Http\Requests\Web\UpdatePatientRequest; 
use App\Services\PatientService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Constants\PatientConstant; 

class PatientWebController extends Controller
{
    public function __construct(
        protected PatientService $patientService
    ) {}

    /**
     * Patient list
     */
    public function index(Request $request): View
    {
        $filters = [
            'q' => $request->input('q'),
            'gender' => $request->input('gender'),
        ];

        $patients = $this->patientService->getAllPatients(
            $filters,
            10
        );

        return view('patients.index', [
            'patients' => $patients,
            'filters' => $filters,
        ]);
    }

    /**
     * Form add patient.
     */
    public function create(): View
    {
        return view('patients.create');
    }

    /**
     * Save patient.
     */
    public function store(
        StorePatientRequest $request
    ): RedirectResponse {
        $patient = $this->patientService->createPatient(
            $request->validated()
        );

        return redirect()
        ->route('web.patients.show', $patient->id)
        ->with('success', PatientConstant::MSG_CREATE_SUCCESS);
    }

    /**
     * Show patient details.
     */
    public function show(int $patient): View
    {
        $patientData = $this->patientService->getPatientById(
            $patient
        );

        return view('patients.show', [
            'patient' => $patientData,
        ]);
    }

    /**
     * Form edit patient.
     */
    public function edit(int $patient): View
    {
        $patientData = $this->patientService->getPatientById(
            $patient
        );

        return view('patients.edit', [
            'patient' => $patientData,
        ]);
    }

    /**
     * Update patients.
     */
    public function update(
        UpdatePatientRequest $request,
        int $patient
    ): RedirectResponse {
        $this->patientService->updatePatient(
            $patient,
            $request->validated()
        );

        return redirect()
        ->route('web.patients.show', $patient)
        ->with('success', PatientConstant::MSG_UPDATE_SUCCESS);
    }

    /**
     * Soft delete patient .
     */
    public function destroy(int $patient): RedirectResponse
    {
        $this->patientService->deletePatient($patient);

        return redirect()
        ->route('web.patients.index')
        ->with('success', PatientConstant::MSG_DELETE_SUCCESS);
    }
}