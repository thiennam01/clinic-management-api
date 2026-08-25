<?php

namespace App\Http\Controllers\Web;

use App\Constants\MedicineMessage;
use App\Http\Controllers\Controller;
use App\Services\MedicineService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MedicineWebController extends Controller
{
    public function __construct(
        protected MedicineService $medicineService
    ) {}

    public function index(Request $request): View
    {
        $medicines = $this->medicineService->getAllMedicines(
            (int) $request->input('per_page', 10)
        );

        return view('medicines.index', compact('medicines'));
    }

    public function create(): View
    {
        return view('medicines.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code' => ['required', 'string', 'max:255', 'unique:medicines,code'],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->medicineService->createMedicine($data);

        return redirect()
            ->route('medicines.web.index')
            ->with('success', MedicineMessage::CREATE_SUCCESS);
    }

    public function edit(int $medicine): View
    {
        $medicineData = $this->medicineService->getMedicineById($medicine);

        abort_if(!$medicineData, 404);

        return view('medicines.edit', [
            'medicine' => $medicineData,
        ]);
    }

    public function update(Request $request, int $medicine): RedirectResponse
    {
        $data = $request->validate([
            'code' => [
                'required',
                'string',
                'max:255',
                'unique:medicines,code,' . $medicine,
            ],
            'name' => ['required', 'string', 'max:255'],
            'unit' => ['required', 'string', 'max:50'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $this->medicineService->updateMedicine($medicine, $data);

        return redirect()
            ->route('medicines.web.index')
            ->with('success', MedicineMessage::UPDATE_SUCCESS);
    }

    public function destroy(int $medicine): RedirectResponse
    {
        $this->medicineService->deleteMedicine($medicine);

        return redirect()
            ->route('medicines.web.index')
            ->with('success', MedicineMessage::DELETE_SUCCESS);
    }
}