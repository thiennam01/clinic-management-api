<?php

namespace App\Http\Requests\Specialty;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSpecialtyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Retrieve the current specialty ID from the route
        $specialtyId = $this->route('specialty')->id ?? $this->route('specialty');

        return [
            // The 'name' field is required, has a maximum length of 255 characters, and must be unique except for the current record
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('specialties', 'name')->ignore($specialtyId),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}