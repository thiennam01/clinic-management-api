<?php

namespace App\Http\Requests;

class StorePatientRequest extends BaseApiRequest
{
    public function rules(): array
    {
        return [
            'full_name'     => 'required|string|max:255',
            'gender'        => 'required|in:male,female,other',
            'date_of_birth' => 'required|date|before:today',
            'phone'         => 'required|string|unique:patients,phone',
            'email'         => 'nullable|email|max:255',
            'address'       => 'nullable|string|max:500',
        ];
    }
}