<?php

namespace App\Http\Controllers\Api\V1;

use App\Constants\PaymentConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\StorePaymentRequest;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function __construct(
        protected PaymentService $paymentService
    ) {
    }

    public function store(
        StorePaymentRequest $request,
        Invoice $invoice
    ): JsonResponse {
        $result = $this->paymentService->createPayment(
            $invoice,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => PaymentConstant::CREATED_SUCCESSFULLY,
            'data' => $result,
        ], 201);
    }

    public function capture(Payment $payment): JsonResponse
    {
        $payment = $this->paymentService->capturePayment($payment);

        return response()->json([
            'success' => true,
            'message' => PaymentConstant::CAPTURED_SUCCESSFULLY,
            'data' => $payment,
        ]);
    }
}