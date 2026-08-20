<?php

namespace App\Providers;

use App\Repositories\Contracts\DoctorRepositoryInterface;
use App\Repositories\Eloquent\DoctorRepository;
use App\Repositories\Contracts\PatientRepositoryInterface;
use App\Repositories\Eloquent\PatientRepository;
use App\Repositories\Contracts\SpecialtyRepositoryInterface;
use App\Repositories\Eloquent\SpecialtyRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interface với Eloquent Class
        $this->app->bind(PatientRepositoryInterface::class, PatientRepository::class);
        $this->app->bind(SpecialtyRepositoryInterface::class, SpecialtyRepository::class);
        $this->app->bind(DoctorRepositoryInterface::class, DoctorRepository::class); // <- Add binding for Doctor
        $this->app->bind(
            \App\Repositories\Contracts\ScheduleRepositoryInterface::class,
            \App\Repositories\Eloquent\ScheduleRepository::class
        );
        $this->app->bind(
        \App\Repositories\Contracts\AppointmentRepositoryInterface::class,
        \App\Repositories\Eloquent\AppointmentRepository::class
        );
    }   

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}