<?php

namespace App\Http\Requests\Doctor;

use App\Constants\DoctorConstant;
use App\Models\User;
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
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
                'unique:doctors,user_id',
                // Custom validation rule to ensure the selected user has the DOCTOR role
                function ($attribute, $value, $fail) {
                    $user = User::with('role')->find($value);
                    if ($user && $user->role && $user->role->name !== 'DOCTOR') {
                        $fail(DoctorConstant::MSG_USER_NOT_DOCTOR);
                    }
                },
            ],
            'specialty_id' => ['required', 'integer', 'exists:specialties,id'],
            'license_number' => ['required', 'string', 'max:50', 'unique:doctors,license_number'],
            'experience_years' => ['sometimes', 'integer', 'min:0'],
            'bio' => ['nullable', 'string'],
            'consultation_fee' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}