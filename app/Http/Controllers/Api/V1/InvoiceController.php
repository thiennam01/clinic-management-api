<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Services\InvoiceService;
use App\Constants\InvoiceMessage;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;

class InvoiceController extends Controller
{
    protected InvoiceService $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    public function store(StoreInvoiceRequest $request): JsonResponse
    {
        $invoice = $this->invoiceService->createInvoice($request->validated());

        return response()->json([
            'message' => InvoiceMessage::CREATED_SUCCESSFULLY,
            'data'    => $invoice
        ], 201);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): JsonResponse
    {
        $updatedInvoice = $this->invoiceService->updateInvoice($invoice, $request->validated());

        return response()->json([
            'message' => InvoiceMessage::UPDATED_SUCCESSFULLY,
            'data'    => $updatedInvoice
        ], 200);
    }
    
    public function show(Invoice $invoice): JsonResponse
    {
        return response()->json([
            'message' => 'Invoice retrieved successfully.',
            'data'    => $invoice
        ], 200);
    }
}