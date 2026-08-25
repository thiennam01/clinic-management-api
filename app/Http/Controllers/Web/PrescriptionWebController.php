<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Services\PrescriptionService;
use App\Constants\PrescriptionConstant;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Exception;

class PrescriptionWebController extends Controller
{
    public function __construct(
        protected PrescriptionService $prescriptionService
    ) {}

    public function index(): View
    {
        $prescriptions = $this->prescriptionService
            ->getAllPrescriptions();

        return view('prescriptions.index', compact(
            'prescriptions'
        ));
    }

    public function create(): View
    {
        $examinations = Examination::with([
            'patient',
            'doctor.user',
            'doctor.specialty',
        ])
            ->whereDoesntHave('prescription')
            ->latest('examined_at')
            ->get();

        $medicines = Medicine::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('prescriptions.create', compact(
            'examinations',
            'medicines'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'examination_id' => [
                'required',
                'exists:examinations,id',
                'unique:prescriptions,examination_id',
            ],

            'doctor_id' => [
                'required',
                'exists:doctors,id',
            ],

            'notes' => [
                'nullable',
                'string',
            ],

            'items' => [
                'required',
                'array',
                'min:1',
            ],

            'items.*.medicine_id' => [
                'required',
                'exists:medicines,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'items.*.dosage' => [
                'nullable',
                'string',
                'max:255',
            ],

            'items.*.usage_instruction' => [
                'nullable',
                'string',
            ],
        ]);

        try {
            $prescription = $this->prescriptionService
                ->createPrescription($validated);

            return redirect()
                ->route(
                    'prescriptions.web.show',
                    $prescription->id
                )
                ->with(
                    'success',
                    PrescriptionConstant::CREATED_SUCCESS
                );

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'prescription' => $e->getMessage(),
                ]);
        }
    }

    public function show(int $prescription): View
    {
        $prescription = $this->prescriptionService
            ->getPrescriptionById($prescription);

        $prescription->load([
            'items.medicine',
            'examination.patient',
            'doctor.user',
            'doctor.specialty',
        ]);

        return view('prescriptions.show', compact(
            'prescription'
        ));
    }

    public function addItem(
        Request $request,
        int $prescription
    ): RedirectResponse {
        $validated = $request->validate([
            'medicine_id' => [
                'required',
                'exists:medicines,id',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'dosage' => [
                'nullable',
                'string',
                'max:255',
            ],

            'usage_instruction' => [
                'nullable',
                'string',
            ],
        ]);

        try {
            $this->prescriptionService->addItem(
                $prescription,
                $validated
            );

            return back()->with(
                'success',
                PrescriptionConstant::ITEM_ADDED_SUCCESS
            );

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'prescription' => $e->getMessage(),
                ]);
        }
    }

    public function updateItem(
        Request $request,
        int $item
    ): RedirectResponse {
        $validated = $request->validate([
            'quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'dosage' => [
                'nullable',
                'string',
                'max:255',
            ],

            'usage_instruction' => [
                'nullable',
                'string',
            ],
        ]);

        try {
            $this->prescriptionService->updateItem(
                $item,
                $validated
            );

            return back()->with(
                'success',
                PrescriptionConstant::ITEM_UPDATED_SUCCESS
            );

        } catch (Exception $e) {
            return back()
                ->withInput()
                ->withErrors([
                    'prescription' => $e->getMessage(),
                ]);
        }
    }

    public function removeItem(int $item): RedirectResponse
    {
        try {
            $this->prescriptionService->removeItem($item);

            return back()->with(
                'success',
                PrescriptionConstant::ITEM_REMOVED_SUCCESS
            );

        } catch (Exception $e) {
            return back()->withErrors([
                'prescription' => $e->getMessage(),
            ]);
        }
    }
}

