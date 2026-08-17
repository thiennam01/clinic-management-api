<?php
namespace App\Services;

use App\Models\Invoice;
use App\Models\Examination;
use App\Constants\InvoiceMessage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class InvoiceService
{
    public function createInvoice(array $data): Invoice
    {
        $examinationId = $data['examination_id'];

        if (Invoice::where('examination_id', $examinationId)->exists()) {
            throw ValidationException::withMessages([
                'examination_id' => [InvoiceMessage::EXAMINATION_ALREADY_EXISTS],
            ]);
        }

        $examination = Examination::with('prescription.items.medicine')->findOrFail($examinationId);

        $medicineTotal = 0;
        if ($examination->prescription && $examination->prescription->items) {
            foreach ($examination->prescription->items as $item) {
                $medicineTotal += $item->quantity * $item->price;
            }
        }

        $consultationFee = config('clinic.consultation_fee', 100000);
        $subtotal = $medicineTotal + $consultationFee;
        $discount = $data['discount'] ?? 0;
        $total = max(0, $subtotal - $discount);

        return Invoice::create([
            'examination_id' => $examinationId,
            'invoice_code'   => 'INV-' . strtoupper(Str::random(8)),
            'subtotal'       => $subtotal,
            'discount'       => $discount,
            'total'          => $total,
            'status'         => 'unpaid',
            'issued_at'      => now(),
        ]);
    }

    public function updateInvoice(Invoice $invoice, array $data): Invoice
    {
        // 1. Only allow editing or cancelling when the status is 'unpaid'
        if ($invoice->status !== 'unpaid') {
            throw ValidationException::withMessages([
                'status' => [InvoiceMessage::INVALID_STATUS_FOR_UPDATE],
            ]);
        }

        // 2. Check if there is already a completed payment (if there is a relationship with payments)
        /*
        if (method_exists($invoice, 'payments') && $invoice->payments()->where('status', 'completed')->exists()) {
            throw ValidationException::withMessages([
                'payment' => [InvoiceMessage::PAYMENT_COMPLETED],
            ]);
        }
        */

        // 3. Handle discount
        if (isset($data['discount'])) {
            $discount = $data['discount'];

            if ($discount > $invoice->subtotal) {
                throw ValidationException::withMessages([
                    'discount' => [InvoiceMessage::DISCOUNT_EXCEEDS_SUBTOTAL],
                ]);
            }

            $invoice->discount = $discount;
            $invoice->total = max(0, $invoice->subtotal - $discount);
        }

        // 4. Handle status (e.g., change to cancelled)
        if (isset($data['status'])) {
            $invoice->status = $data['status'];
        }

        $invoice->save();

        return $invoice;
    }
}