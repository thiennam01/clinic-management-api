<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor');

        return [
            'user_id' => 'sometimes|required|exists:users,id|unique:doctors,user_id,' . $doctorId,
            'specialty_id' => 'sometimes|required|exists:specialties,id',
            'license_number' => 'sometimes|required|string|max:50|unique:doctors,license_number,' . $doctorId,
            'experience_years' => 'nullable|integer|min:0',
            'bio' => 'nullable|string',
            'consultation_fee' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }
}