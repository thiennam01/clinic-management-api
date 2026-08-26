<?php

namespace App\Providers;

use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Eloquent\DoctorRepository;
use App\Repositories\Contracts\PatientRepositoryInterface;
use App\Repositories\Eloquent\PatientRepository;
use App\Repositories\Contracts\SpecialtyRepositoryInterface;
use App\Repositories\Eloquent\SpecialtyRepository;
use App\Repositories\Contracts\ExaminationRepositoryInterface; 
use App\Repositories\Eloquent\ExaminationRepository;         
use Illuminate\Support\ServiceProvider;
use App\Repositories\Contracts\MedicineRepositoryInterface;
use App\Repositories\Eloquent\MedicineRepository;
use App\Models\Appointment;
use App\Models\Examination;
use App\Models\Invoice;
use App\Models\Medicine;
use App\Models\Payment;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\User;
use App\Observers\ActivityLogObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces with their Eloquent Implementations
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);
        $this->app->bind(SpecialtyRepositoryInterface::class, SpecialtyRepository::class);
        $this->app->bind(DoctorRepositoryInterface::class, DoctorRepository::class);
        $this->app->bind(MedicineRepositoryInterface::class, MedicineRepository::class);
        $this->app->bind(
            \App\Repositories\Contracts\ScheduleRepositoryInterface::class,
            \App\Repositories\Eloquent\ScheduleRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\AppointmentRepositoryInterface::class,
            \App\Repositories\Eloquent\AppointmentRepository::class
        );
        // Bind Examination Repository
        $this->app->bind(ExaminationRepositoryInterface::class, ExaminationRepository::class);
        
        // Bind Prescription Repository 
        $this->app->bind(
            \App\Repositories\Contracts\PrescriptionRepositoryInterface::class,
            \App\Repositories\Eloquent\PrescriptionRepository::class
        );
    }   

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        User::observe(ActivityLogObserver::class);
        Appointment::observe(ActivityLogObserver::class);
        Examination::observe(ActivityLogObserver::class);
        Prescription::observe(ActivityLogObserver::class);
        PrescriptionItem::observe(ActivityLogObserver::class);
        Medicine::observe(ActivityLogObserver::class);
        Invoice::observe(ActivityLogObserver::class);
        Payment::observe(ActivityLogObserver::class);
    }
}
