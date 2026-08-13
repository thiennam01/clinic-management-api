<?php

namespace App\Http\Requests\Doctor;

use App\Constants\DoctorConstant;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDoctorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $doctorId = $this->route('doctor')->id ?? $this->route('doctor');

        return [
            'user_id' => [
                'sometimes',
                'required',
                'integer',
                'exists:users,id',
                "unique:doctors,user_id,{$doctorId}",
                function ($attribute, $value, $fail) {
                    $user = User::with('role')->find($value);
                    if ($user && $user->role && $user->role->name !== 'DOCTOR') {
                        $fail(DoctorConstant::MSG_USER_NOT_DOCTOR);
                    }
                },
            ],
            'specialty_id' => ['sometimes', 'required', 'integer', 'exists:specialties,id'],
            'license_number' => ['sometimes', 'required', 'string', 'max:50', "unique:doctors,license_number,{$doctorId}"],
            'experience_years' => ['sometimes', 'integer', 'min:0'],
            'bio' => ['nullable', 'string'],
            'consultation_fee' => ['sometimes', 'numeric', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}