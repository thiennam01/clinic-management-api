<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'doctor_id'        => $this->doctor_id,
            'doctor'           => new DoctorResource($this->whenLoaded('doctor')),
            'date'             => $this->date ? $this->date->format('Y-m-d') : null,
            'start_time'       => $this->start_time,
            'end_time'         => $this->end_time,
            'max_patients'     => $this->max_patients,
            'current_patients' => $this->current_patients,
            'is_available'     => $this->current_patients < $this->max_patients && $this->is_active,
            'is_active'        => $this->is_active,
            'created_at'       => $this->created_at?->toDateTimeString(),
        ];
    }
}