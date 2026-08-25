<?php

namespace App\Repositories\Eloquent;

use App\Models\Examination;
use App\Repositories\Contracts\ExaminationRepositoryInterface;

class ExaminationRepository implements ExaminationRepositoryInterface
{
    /**
     * Create a new examination record.
     *
     * @param array $data
     * @return Examination
     */
    public function create(array $data): Examination
    {
        return Examination::create($data);
    }

    /**
     * Find an examination by its appointment ID.
     *
     * @param int $appointmentId
     * @return Examination|null
     */
    public function findByAppointmentId(int $appointmentId): ?Examination
    {
        return Examination::where('appointment_id', $appointmentId)->first();
    }
}