<?php

namespace App\Http\Controllers\Web;

use App\Constants\PaymentConstant;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PaymentWebController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {}

    /**
     * Display payment page for an invoice.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load([
            'examination.patient',
            'examination.doctor.user',
            'examination.doctor.specialty',
            'payments' => fn ($query) => $query->latest(),
        ]);

        $paidAmount = (float) $invoice->payments
            ->where('status', 'completed')
            ->sum('amount');

        $remainingAmount = max(
            0,
            (float) $invoice->total - $paidAmount
        );

        return view('payments.show', [
            'invoice' => $invoice,
            'paidAmount' => $paidAmount,
            'remainingAmount' => $remainingAmount,
        ]);
    }

    /**
     * Create a payment order and redirect to PayPal.
     */
    public function store(
        Request $request,
        Invoice $invoice
    ): RedirectResponse {
        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'gt:0',
                'max:' . $invoice->total,
            ],
            'method' => [
                'required',
                'in:paypal,visa',
            ],
        ]);

        try {
            $result = $this->paymentService->createPayment(
                $invoice,
                $validated
            );

            return redirect($result['approval_url']);

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }

    /**
     * PayPal success callback.
     */
    public function success(
        Request $request,
        Invoice $invoice
    ): RedirectResponse {
        $orderId = $request->query('token');

        if (!$orderId) {
            return redirect()
                ->route('payments.web.show', $invoice)
                ->with('error', PaymentConstant::PAYMENT_FAILED);
        }

        $payment = $invoice->payments()
            ->where('provider_order_id', $orderId)
            ->where('status', 'pending')
            ->first();

        if (!$payment) {
            return redirect()
                ->route('payments.web.show', $invoice)
                ->with('error', PaymentConstant::PAYMENT_FAILED);
        }

        try {
            $this->paymentService->capturePayment($payment);

            return redirect()
                ->route('payments.web.show', $invoice)
                ->with(
                    'success',
                    PaymentConstant::PAYMENT_SUCCESS
                );

        } catch (\Throwable $e) {
            report($e);

            return redirect()
                ->route('payments.web.show', $invoice)
                ->with(
                    'error',
                    PaymentConstant::PAYMENT_FAILED
                );
        }
    }

    /**
     * PayPal cancel callback.
     */
    public function cancel(Invoice $invoice): RedirectResponse
    {
        return redirect()
            ->route('payments.web.show', $invoice)
            ->with(
                'error',
                PaymentConstant::PAYMENT_CANCELLED
            );
    }
}