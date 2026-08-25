<?php

namespace App\Http\Controllers\Web;

use App\Constants\DoctorConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doctor\StoreDoctorRequest;
use App\Http\Requests\Doctor\UpdateDoctorRequest;
use App\Services\DoctorService;
use App\Services\SpecialtyService;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DoctorWebController extends Controller
{
    public function __construct(
        protected DoctorService $doctorService,
        protected SpecialtyService $specialtyService,
        protected UserService $userService
    ) {}

    public function index(Request $request): View
    {
        $doctors = $this->doctorService->getAllDoctors(10);

        return view('doctors.index', [
            'doctors' => $doctors,
        ]);
    }

    public function create(): View
    {
        $specialties = $this->specialtyService->getAllSpecialties(100);
        $doctorUsers = $this->userService->getDoctorUsers();

        return view('doctors.create', [
            'specialties' => $specialties,
            'doctorUsers' => $doctorUsers,
        ]);
    }

    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        $doctor = $this->doctorService->createDoctor(
            $request->validated()
        );

        return redirect()
            ->route('web.doctors.show', $doctor->id)
            ->with(
                'success',
                DoctorConstant::MSG_CREATE_SUCCESS
            );
    }

    public function show(int $doctor): View
    {
        $doctorData = $this->doctorService->getDoctorById($doctor);

        return view('doctors.show', [
            'doctor' => $doctorData,
        ]);
    }

    public function edit(int $doctor): View
    {
        $doctorData = $this->doctorService->getDoctorById($doctor);

        $specialties = $this->specialtyService->getAllSpecialties(100);
        $doctorUsers = $this->userService->getDoctorUsers();

        return view('doctors.edit', [
            'doctor' => $doctorData,
            'specialties' => $specialties,
            'doctorUsers' => $doctorUsers,
        ]);
    }

    public function update(
        UpdateDoctorRequest $request,
        int $doctor
    ): RedirectResponse {
        $this->doctorService->updateDoctor(
            $doctor,
            $request->validated()
        );

        return redirect()
            ->route('web.doctors.show', $doctor)
            ->with(
                'success',
                DoctorConstant::MSG_UPDATE_SUCCESS
            );
    }

    public function destroy(int $doctor): RedirectResponse
    {
        $this->doctorService->deleteDoctor($doctor);

        return redirect()
            ->route('web.doctors.index')
            ->with(
                'success',
                DoctorConstant::MSG_DELETE_SUCCESS
            );
    }
}