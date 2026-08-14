<?php

namespace App\Http\Requests\Specialty;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Authorization is handled by Middleware or Controller
        return true; 
    }

    public function rules(): array
    {
        return [
            // The 'code' field is removed from validation because it is auto-generated in the Service.
            'name' => ['required', 'string', 'max:255', 'unique:specialties,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}