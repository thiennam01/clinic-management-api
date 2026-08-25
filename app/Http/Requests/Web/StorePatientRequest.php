<?php

namespace App\Http\Requests\Web;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'gender' => [
                'required',
                'in:male,female,other',
            ],

            'date_of_birth' => [
                'required',
                'date',
                'before:today',
            ],

            'phone' => [
                'required',
                'string',
                'max:30',
                'unique:patients,phone',
            ],

            'email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'address' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'full_name' => 'họ và tên',
            'gender' => 'giới tính',
            'date_of_birth' => 'ngày sinh',
            'phone' => 'số điện thoại',
            'email' => 'email',
            'address' => 'địa chỉ',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute là bắt buộc.',

            'gender.in' =>
                'Giới tính không hợp lệ.',

            'date_of_birth.before' =>
                'Ngày sinh phải nhỏ hơn ngày hiện tại.',

            'phone.unique' =>
                'Số điện thoại này đã tồn tại.',

            'email.email' =>
                'Email không đúng định dạng.',
        ];
    }
}