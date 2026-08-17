<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id|unique:doctors,user_id',
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'required|string|max:50|unique:doctors,license_number',
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }
}