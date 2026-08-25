<?php

namespace App\Services;

use App\Constants\ExaminationConstant;
use App\Repositories\Contracts\ExaminationRepositoryInterface;
use App\Repositories\Contracts\AppointmentRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class ExaminationService
{
    public function __construct(
        protected ExaminationRepositoryInterface $examinationRepository,
        protected AppointmentRepositoryInterface $appointmentRepository
    ) {}

    /**
     * Get all examination records.
     */
    public function getAllExaminations()
    {
        return $this->examinationRepository->all();
    }

    /**
     * Get an examination by ID.
     */
    public function getExaminationById(int $id)
    {
        $examination = $this->examinationRepository->find($id);
        if (!$examination) {
            throw new Exception(ExaminationConstant::MSG_NOT_FOUND, 404);
        }

        return $examination;
    }

    /**
     * Create a new examination record from a confirmed appointment.
     */
    public function createExamination(array $data)
    {
        return DB::transaction(function () use ($data) {
            $appointmentId = $data['appointment_id'];

            // 1. Check if the given appointment exists
            $appointment = $this->appointmentRepository->find($appointmentId);
            if (!$appointment) {
                throw new Exception(ExaminationConstant::MSG_APPOINTMENT_NOT_FOUND, 404);
            } 

            // 2. Validate if appointment status is confirmed
            if (strtolower($appointment->status) !== 'confirmed') {
                throw new Exception(ExaminationConstant::MSG_APPOINTMENT_NOT_CONFIRMED, 422);
            }

            // 3. Ensure an appointment has a maximum of one examination record
            $existingExamination = $this->examinationRepository->findByAppointmentId($appointmentId);
            if ($existingExamination) {
                throw new Exception(ExaminationConstant::MSG_EXAMINATION_ALREADY_EXISTS, 422);
            }

            // 4. Automatically derive doctor_id and patient_id from the appointment safely
            $appointment->loadMissing('schedule');
            
            $doctorId = $appointment->schedule ? ($appointment->schedule->doctor_id ?? $appointment->schedule->user_id ?? null) : null;
            $patientId = $appointment->patient_id;

            $examinationData = [
                'appointment_id' => $appointment->id,
                'doctor_id'      => $doctorId,
                'patient_id'     => $patientId,
                'diagnosis'      => $data['diagnosis'],
                'notes'          => $data['notes'] ?? null,
                'examined_at'    => $data['examined_at'] ?? now(),
            ];

            // 5. Create the examination record
            $examination = $this->examinationRepository->create($examinationData);

            // 6. Update appointment status to completed and rollback if it fails
            $appointment->status = 'completed';
            if (!$appointment->save()) {
                throw new Exception(ExaminationConstant::MSG_UPDATE_APPOINTMENT_FAILED, 500);
            }

            return $examination;
        });
    }
}