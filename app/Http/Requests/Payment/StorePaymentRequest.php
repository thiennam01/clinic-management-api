<?php

namespace App\Http\Requests\Payment;

use App\Http\Requests\BaseApiRequest;
use Illuminate\Validation\Rule;

class StorePaymentRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric', 'gt:0'],
            'method' => ['required', Rule::in(['paypal', 'visa'])],
        ];
    }
}