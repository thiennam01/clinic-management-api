<?php

namespace App\Http\Requests;

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
            'schedule_id.required' => 'Vui lòng chọn lịch làm việc.',
            'schedule_id.exists' => 'Lịch làm việc không tồn tại.',
            'appointment_date.required' => 'Vui lòng chọn ngày giờ khám.',
            'appointment_date.date' => 'Ngày giờ khám không đúng định dạng.',
            'appointment_date.after_or_equal' => 'Ngày khám phải từ hôm nay trở đi.',
        ];
    }
}