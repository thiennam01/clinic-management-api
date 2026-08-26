<?php

namespace App\Repositories;

use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Patient;

class StatsRepository
{
    private const LOW_STOCK_THRESHOLD = 10;

    public function getOverview(): array
    {
        return [
            'patients' => Patient::query()->count(),

            'appointments_today' => Appointment::query()
                ->whereDate('appointment_date', today())
                ->count(),

            'monthly_revenue' => (float) Invoice::query()
                ->where('status', 'paid')
                ->whereBetween('issued_at', [
                    now()->startOfMonth(),
                    now()->endOfMonth(),
                ])
                ->sum('total'),

            'low_stock_medicines' => Medicine::query()
                ->where('is_active', true)
                ->where('stock', '<=', self::LOW_STOCK_THRESHOLD)
                ->count(),
        ];
    }
}
