<?php

namespace App\Services;

use App\Constants\PaymentConstant;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PayPalService
{
    protected function getAccessToken(): string
    {
        $response = Http::timeout(15)
            ->asForm()
            ->withBasicAuth(
                config('paypal.client_id'),
                config('paypal.client_secret')
            )
            ->post(config('paypal.base_url') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if ($response->failed()) {
            throw new RuntimeException(
                PaymentConstant::PAYPAL_AUTH_FAILED
            );
        }

        $token = $response->json('access_token');

        if (!$token) {
            throw new RuntimeException(
                PaymentConstant::PAYPAL_TOKEN_NOT_RETURNED
            );
        }

        return $token;
    }

    public function createOrder(
        float $amount,
        string $returnUrl,
        string $cancelUrl
    ): array {
        $response = Http::timeout(15)
            ->withToken($this->getAccessToken())
            ->acceptJson()
            ->post(config('paypal.base_url') . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [
                    [
                        'amount' => [
                            'currency_code' => config('paypal.currency'),
                            'value' => number_format($amount, 2, '.', ''),
                        ],
                    ],
                ],
                'application_context' => [
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                    'user_action' => 'PAY_NOW',
                ],
            ]);

        if ($response->failed()) {
            logger()->error('PayPal create order failed', [
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            throw new RuntimeException(
                PaymentConstant::PAYPAL_ORDER_CREATE_FAILED
            );
        }

        $orderId = $response->json('id');

        if (!$orderId) {
            throw new RuntimeException(
                PaymentConstant::PAYPAL_ORDER_ID_NOT_RETURNED
            );
        }

        $approvalUrl = collect($response->json('links', []))
            ->firstWhere('rel', 'approve')['href'] ?? null;

        if (!$approvalUrl) {
            throw new RuntimeException(
                PaymentConstant::PAYPAL_APPROVAL_URL_NOT_RETURNED
            );
        }

        return [
            'order_id' => $orderId,
            'approval_url' => $approvalUrl,
        ];
    }

    public function captureOrder(string $orderId): array
    {
        $response = Http::timeout(15)
            ->withToken($this->getAccessToken())
            ->acceptJson()
            ->withBody('{}', 'application/json')
            ->post(
                config('paypal.base_url') .
                "/v2/checkout/orders/{$orderId}/capture"
            );

        if ($response->failed()) {
            \Log::error('PayPal capture failed', [
                'order_id' => $orderId,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);

            return [
                'success' => false,
                'capture_id' => null,
            ];
        }

        $captureId = data_get(
            $response->json(),
            'purchase_units.0.payments.captures.0.id'
        );

        return [
            'success' => true,
            'capture_id' => $captureId,
        ];
    }
}