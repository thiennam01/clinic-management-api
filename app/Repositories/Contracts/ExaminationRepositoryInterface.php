<?php

namespace App\Repositories\Contracts;

use App\Models\Examination;
use Illuminate\Database\Eloquent\Collection;

interface ExaminationRepositoryInterface
{
    /**
     * Get all examination records.
     */
    public function all(): Collection;

    /**
     * Find an examination by ID.
     */
    public function find(int $id): ?Examination;

    /**
     * Create a new examination.
     */
    public function create(array $data): Examination;

    /**
     * Find an examination by appointment ID.
     */
    public function findByAppointmentId(int $appointmentId): ?Examination;
}