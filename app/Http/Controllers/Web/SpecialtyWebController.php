<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Specialty\StoreSpecialtyRequest;
use App\Http\Requests\Specialty\UpdateSpecialtyRequest;
use App\Models\Specialty;
use App\Services\SpecialtyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Constants\SpecialtyConstant;

class SpecialtyWebController extends Controller
{
    public function __construct(
        protected SpecialtyService $specialtyService
    ) {}

    /**
     * Specialty list
     */
    public function index(Request $request): View
    {
        $specialties = $this->specialtyService->getAllSpecialties(10);

        return view('specialties.index', [
            'specialties' => $specialties,
        ]);
    }

    /**
     * Form add specialty
     */
    public function create(): View
    {
        return view('specialties.create');
    }

    /**
     * Save specialty.
     */
    public function store(
        StoreSpecialtyRequest $request
    ): RedirectResponse {
        $specialty = $this->specialtyService->createSpecialty(
            $request->validated()
        );

        return redirect()
            ->route('web.specialties.show', $specialty->id)
            ->with('success', SpecialtyConstant::MSG_CREATE_SUCCESS);
    }

    /**
     * Show specialty details.
     */
    public function show(int $specialty): View
    {
        $specialtyData = $this->specialtyService->getSpecialtyById(
            $specialty
        );

        return view('specialties.show', [
            'specialty' => $specialtyData,
        ]);
    }

    /**
     * Form edit Specialty
     */
    public function edit(int $specialty): View
    {
        $specialtyData = $this->specialtyService->getSpecialtyById(
            $specialty
        );

        return view('specialties.edit', [
            'specialty' => $specialtyData,
        ]);
    }

    /**
     * Update Specialty
     */
    public function update(
        UpdateSpecialtyRequest $request,
        int $specialty
    ): RedirectResponse {
        $this->specialtyService->updateSpecialty(
            $specialty,
            $request->validated()
        );

        return redirect()
            ->route('web.specialties.show', $specialty)
            ->with('success', SpecialtyConstant::MSG_UPDATE_SUCCESS);
    }

    /**
     * Soft delete Specialty
     */
    public function destroy(int $specialty): RedirectResponse
    {
        $this->specialtyService->deleteSpecialty($specialty);

        return redirect()
            ->route('web.specialties.index')
            ->with('success', SpecialtyConstant::MSG_DELETE_SUCCESS);
    }
}