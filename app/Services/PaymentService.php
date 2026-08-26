<?php

namespace App\Services;

use App\Constants\PaymentConstant;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentService
{
    public function __construct(
        protected PayPalService $payPalService
    ) {
    }

    public function createPayment(Invoice $invoice, array $data): array
    {
        $amount = (float) $data['amount'];

        $paidAmount = (float) $invoice->payments()
            ->where('status', 'completed')
            ->sum('amount');

        $remainingAmount = (float) $invoice->total - $paidAmount;

        if ($remainingAmount <= 0) {
            throw ValidationException::withMessages([
                'amount' => [PaymentConstant::INVOICE_ALREADY_PAID],
            ]);
        }

        if ($amount > $remainingAmount) {
            throw ValidationException::withMessages([
                'amount' => [PaymentConstant::AMOUNT_EXCEEDS_REMAINING],
            ]);
        }

        $paypalAmount = round(
            $amount / config('paypal.exchange_rate'),
            2
        );

        $order = $this->payPalService->createOrder(
            $paypalAmount,
            route('payments.web.success', ['invoice' => $invoice->id]),
            route('payments.web.cancel', ['invoice' => $invoice->id])
        );

        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $amount,
            'method' => $data['method'],
            'status' => 'pending',
            'provider' => 'paypal',
            'provider_order_id' => $order['order_id'],
        ]);

        return [
            'payment' => $payment,
            'order_id' => $order['order_id'],
            'approval_url' => $order['approval_url'],
        ];
    }

    public function capturePayment(Payment $payment): Payment
    {
        if ($payment->status !== 'pending') {
            throw ValidationException::withMessages([
                'payment' => [PaymentConstant::PAYMENT_NOT_PENDING],
            ]);
        }

        $result = $this->payPalService->captureOrder(
            $payment->provider_order_id
        );

        if (!$result['success']) {
            $payment->update([
                'status' => 'failed',
            ]);

            throw ValidationException::withMessages([
                'payment' => [PaymentConstant::PAYPAL_CAPTURE_FAILED],
            ]);
        }

        return DB::transaction(function () use ($payment, $result) {
            $payment->update([
                'status' => 'completed',
                'provider_capture_id' => $result['capture_id'],
                'paid_at' => now(),
            ]);

            $invoice = $payment->invoice()->lockForUpdate()->first();

            $completedAmount = (float) $invoice->payments()
                ->where('status', 'completed')
                ->sum('amount');

            if ($completedAmount >= (float) $invoice->total) {
                $invoice->update([
                    'status' => 'paid',
                ]);
            }

            return $payment->fresh();
        });
    }
}