<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdatePatientRequest extends BaseApiRequest
{
    public function rules(): array
    {
        $patientId = $this->route('patient');

        return [
            'full_name'     => 'sometimes|required|string|max:255',
            'gender'        => 'sometimes|required|in:male,female,other',
            'date_of_birth' => 'sometimes|required|date|before:today',
            'phone'         => ['sometimes', 'required', 'string', Rule::unique('patients')->ignore($patientId)],
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string|max:500',
        ];
    }
}