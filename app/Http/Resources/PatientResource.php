<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'full_name'     => $this->full_name,
            'gender'        => $this->gender,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'phone'         => $this->phone,
            'email'         => $this->email,
            'address'       => $this->address,
            'created_at'    => $this->created_at?->toIso8601String(),
        ];
    }
}