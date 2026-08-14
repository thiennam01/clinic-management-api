<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user?->id,
                'name' => $this->user?->name,
                'email' => $this->user?->email,
                'phone' => $this->user?->phone ?? null,
            ],
            'specialty' => [
                'id' => $this->specialty?->id,
                'name' => $this->specialty?->name,
                'code' => $this->specialty?->code,
            ],
            'license_number' => $this->license_number,
            'experience_years' => $this->experience_years,
            'bio' => $this->bio,
            'consultation_fee' => (float) $this->consultation_fee,
            'is_active' => (bool) $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}