<?php

namespace App\Repositories\Eloquent;

use App\Models\Examination;
use App\Repositories\Contracts\ExaminationRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ExaminationRepository implements ExaminationRepositoryInterface
{
    /**
     * Get all examination records.
     */
    public function all(): Collection
    {
        return Examination::with([
            'appointment',
            'patient',
            'doctor.user',
            'doctor.specialty',
        ])
            ->latest('examined_at')
            ->get();
    }

    /**
     * Find an examination by ID.
     */
    public function find(int $id): ?Examination
    {
        return Examination::with([
            'appointment',
            'patient',
            'doctor.user',
            'doctor.specialty',
            'prescription',
        ])->find($id);
    }

    /**
     * Create a new examination.
     */
    public function create(array $data): Examination
    {
        return Examination::create($data);
    }

    /**
     * Find an examination by appointment ID.
     */
    public function findByAppointmentId(int $appointmentId): ?Examination
    {
        return Examination::where(
            'appointment_id',
            $appointmentId
        )->first();
    }
}