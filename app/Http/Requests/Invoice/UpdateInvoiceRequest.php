<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status'   => ['sometimes', 'string', 'in:unpaid,cancelled'], // Only allow updating status to cancelled or keeping it unpaid
        ];
    }
}