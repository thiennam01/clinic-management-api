<?php

namespace App\Services;

use App\Repositories\DashboardRepository;
use Illuminate\Support\Facades\Auth;

class DashboardService
{
    public function __construct(
        protected DashboardRepository $repository
    ) {}

    public function getDashboardData(): array
    {
        $user = Auth::user();

        $role = $user->role?->name ?? 'USER';

        $doctor = null;

        if ($role === 'DOCTOR') {
            $doctor = $this->repository->getDoctorByUserId($user->id);
        }

        $doctorId = $doctor?->id;

        return [
            'user' => $user,

            'role' => $role,

            'doctor' => $doctor,

            'stats' => [
                'appointments_today' => $this->repository
                    ->countAppointmentsToday($role, $doctorId),

                'waiting' => $this->repository
                    ->countWaitingToday($role, $doctorId),

                'in_progress' => $this->repository
                    ->countInProgressToday($role, $doctorId),

                'completed' => $this->repository
                    ->countCompletedToday($role, $doctorId),

                'patients' => $this->repository
                    ->countPatients(),

                'unpaid_invoices' => $this->repository
                    ->countUnpaidInvoices(),

                'revenue_today' => $this->repository
                    ->revenueToday(),
            ],

            'appointments' => $this->repository
                ->getTodayAppointments($role, $doctorId),

            'waitingAppointments' => $this->repository
                ->getWaitingAppointments($role, $doctorId),
        ];
    }
}