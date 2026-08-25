<?php

namespace App\Http\Requests;

use App\Constants\AppointmentConstant;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'notes' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_id.required' => AppointmentConstant::MSG_SCHEDULE_REQUIRED,
            'schedule_id.exists' => AppointmentConstant::MSG_SCHEDULE_EXISTS,
            'appointment_date.required' => AppointmentConstant::MSG_DATE_REQUIRED,
            'appointment_date.date' => AppointmentConstant::MSG_DATE_INVALID,
            'appointment_date.after_or_equal' => AppointmentConstant::MSG_DATE_AFTER_OR_EQUAL,
        ];
    }
}