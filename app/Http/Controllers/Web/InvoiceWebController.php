<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvoiceWebController extends Controller
{
    public function __construct(
        protected InvoiceService $invoiceService
    ) {}

    public function index(Request $request): View
    {
        $query = Invoice::with([
            'examination.patient',
            'examination.doctor.user',
        ])->latest('issued_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('invoice_code', 'ilike', "%{$search}%")
                    ->orWhereHas('examination.patient', function ($q) use ($search) {
                        $q->where('full_name', 'ilike', "%{$search}%")
                            ->orWhere('phone', 'ilike', "%{$search}%");
                    });
            });
        }

        $invoices = $query->paginate(10)->withQueryString();

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $examinations = Examination::with([
            'patient',
            'doctor.user',
            'doctor.specialty',
            'prescription',
        ])
            ->whereDoesntHave('invoice')
            ->latest('examined_at')
            ->get();

        return view('invoices.create', compact('examinations'));
    }
    
    public function store(Examination $examination): RedirectResponse
    {
        if ($examination->invoice) {
            return redirect()
                ->route('invoices.web.show', $examination->invoice)
                ->with('error', 'Hóa đơn đã tồn tại.');
        }

        $invoice = $this->invoiceService->createInvoice([
            'examination_id' => $examination->id,
        ]);

        return redirect()
            ->route('invoices.web.show', $invoice)
            ->with('success', 'Tạo hóa đơn thành công.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'examination.patient',
            'examination.doctor.user',
            'examination.doctor.specialty',
            'payments',
        ]);

        return view('invoices.show', compact('invoice'));
    }

}