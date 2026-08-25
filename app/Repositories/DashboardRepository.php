<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Examination;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardRepository
{
    /**
     * Get the Doctor corresponding to the currently logged-in account.
     */
    public function getDoctorByUserId(int $userId): ?Doctor
    {
        return Doctor::query()
            ->with(['user', 'specialty'])
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Base appointment query for the dashboard.
     *
     * ADMIN / RECEPTIONIST / PHARMACIST / CASHIER:
     *   view all.
     *
     * DOCTOR:
     *   only view schedules belonging to the currently logged-in doctor.
     */
    public function appointmentQuery(
        string $role,
        ?int $doctorId = null
    ): Builder {
        $query = Appointment::query()
            ->with([
                'patient',
                'schedule.doctor.user',
                'schedule.doctor.specialty',
            ]);

        if ($role === 'DOCTOR' && $doctorId !== null) {
            $query->whereHas('schedule', function (Builder $query) use ($doctorId) {
                $query->where('doctor_id', $doctorId);
            });
        }

        return $query;
    }

    /**
     * Number of appointments today.
     */
    public function countAppointmentsToday(
        string $role,
        ?int $doctorId = null
    ): int {
        return $this->appointmentQuery($role, $doctorId)
            ->whereDate('appointment_date', today())
            ->count();
    }

    /**
     * Number of patients waiting for examination today.
     */
    public function countWaitingToday(
        string $role,
        ?int $doctorId = null
    ): int {
        return $this->appointmentQuery($role, $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'pending')
            ->count();
    }

    /**
     * Number of appointments in progress today.
     *
     * The current project does not have an "in_progress" status yet,
     * so temporarily treating "confirmed" as admitted cases.
     */
    public function countInProgressToday(
        string $role,
        ?int $doctorId = null
    ): int {
        return $this->appointmentQuery($role, $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'confirmed')
            ->count();
    }

    /**
     * Number of completed appointments today.
     */
    public function countCompletedToday(
        string $role,
        ?int $doctorId = null
    ): int {
        return $this->appointmentQuery($role, $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'completed')
            ->count();
    }

    /**
     * List of today's appointments.
     */
    public function getTodayAppointments(
        string $role,
        ?int $doctorId = null,
        int $limit = 10
    ) {
        return $this->appointmentQuery($role, $doctorId)
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Patients waiting for examination today.
     */
    public function getWaitingAppointments(
        string $role,
        ?int $doctorId = null,
        int $limit = 5
    ) {
        return $this->appointmentQuery($role, $doctorId)
            ->whereDate('appointment_date', today())
            ->where('status', 'pending')
            ->orderBy('appointment_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Total number of patients.
     */
    public function countPatients(): int
    {
        return Patient::query()->count();
    }

    /**
     * Number of unpaid invoices.
     */
    public function countUnpaidInvoices(): int
    {
        return Invoice::query()
            ->where('status', 'unpaid')
            ->count();
    }

    /**
     * Total revenue from paid invoices today.
     */
    public function revenueToday(): float
    {
        return (float) Invoice::query()
            ->where('status', 'paid')
            ->whereDate('issued_at', today())
            ->sum('total');
    }
}