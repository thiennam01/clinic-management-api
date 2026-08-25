<?php

namespace App\Http\Requests\Doctor;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
                // Custom rule kiểm tra user phải có role là DOCTOR (giả sử role name hoặc ID của DOCTOR)
                function ($attribute, $value, $fail) {
                    $user = User::with('role')->find($value);
                    if ($user && $user->role && $user->role->name !== 'DOCTOR') { // Hoặc check theo ID nếu hệ thống dùng ID
                        $fail('Người dùng được chọn không phải là Bác sĩ (Doctor).');
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