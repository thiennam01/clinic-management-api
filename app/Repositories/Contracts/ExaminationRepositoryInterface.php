<?php

namespace App\Repositories\Contracts;

use App\Models\Examination;
use Illuminate\Database\Eloquent\Collection;

interface ExaminationRepositoryInterface
{
    /**
     * Create a new examination record.
     *
     * @param array $data
     * @return Examination
     */
    public function create(array $data): Examination;

    /**
     * Find an examination by its appointment ID to enforce the unique constraint rule.
     *
     * @param int $appointmentId
     * @return Examination|null
     */
    public function findByAppointmentId(int $appointmentId): ?Examination;
}   