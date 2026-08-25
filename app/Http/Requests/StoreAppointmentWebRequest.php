<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentWebRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => [
                'required',
                'exists:patients,id',
            ],

            'schedule_id' => [
                'required',
                'exists:schedules,id',
            ],

            'appointment_date' => [
                'required',
                'date',
                'after_or_equal:today',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }
}