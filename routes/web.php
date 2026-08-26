<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Web\PatientWebController;
use App\Http\Controllers\Web\DoctorWebController;
use App\Http\Controllers\Web\SpecialtyWebController;
use App\Http\Controllers\Web\AppointmentWebController;
use App\Http\Controllers\Web\ExaminationWebController;
use App\Http\Controllers\Web\ScheduleWebController;
use App\Http\Controllers\Web\UserWebController;
use App\Http\Controllers\Web\MedicineWebController;
use App\Http\Controllers\Web\PrescriptionWebController;
use App\Http\Controllers\Web\InvoiceWebController;
use App\Http\Controllers\Web\PaymentWebController;
use App\Http\Controllers\Web\NotificationController;


/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [LoginController::class, 'showLoginForm'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.submit');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/notifications', [
        NotificationController::class,
        'index',
    ])->name('notifications.index');

    Route::post('/notifications/read-all', [
        NotificationController::class,
        'readAll',
    ])->name('notifications.read-all');
});

/*
|--------------------------------------------------------------------------
| Clinic Management
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'permission'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Patients
    |--------------------------------------------------------------------------
    */

    Route::resource('patients', PatientWebController::class)
        ->except(['show'])
        ->names('web.patients');

    Route::get('/patients/{patient}', [
        PatientWebController::class,
        'show',
    ])->name('web.patients.show');


    /*
    |--------------------------------------------------------------------------
    | Doctors
    |--------------------------------------------------------------------------
    */

    Route::resource('doctors', DoctorWebController::class)
        ->except(['show'])
        ->names('web.doctors');

    Route::get('/doctors/{doctor}', [
        DoctorWebController::class,
        'show',
    ])->name('web.doctors.show');


    /*
    |--------------------------------------------------------------------------
    | Specialties
    |--------------------------------------------------------------------------
    */

    Route::resource('specialties', SpecialtyWebController::class)
        ->except(['show'])
        ->names('web.specialties');

    Route::get('/specialties/{specialty}', [
        SpecialtyWebController::class,
        'show',
    ])->name('web.specialties.show');


    /*
    |--------------------------------------------------------------------------
    | Appointments
    |--------------------------------------------------------------------------
    */

    Route::get('/appointments', [
        AppointmentWebController::class,
        'index',
    ])->name('appointments.web.index');

    Route::get('/appointments/create', [
        AppointmentWebController::class,
        'create',
    ])->name('appointments.web.create');

    Route::post('/appointments', [
        AppointmentWebController::class,
        'store',
    ])->name('appointments.web.store');

    Route::get('/appointments/{appointment}', [
        AppointmentWebController::class,
        'show',
    ])->name('appointments.web.show');

    Route::get('/appointments/{appointment}/edit', [
        AppointmentWebController::class,
        'edit',
    ])->name('appointments.web.edit');

    Route::put('/appointments/{appointment}', [
        AppointmentWebController::class,
        'update',
    ])->name('appointments.web.update');

    Route::patch('/appointments/{appointment}/status', [
        AppointmentWebController::class,
        'updateStatus',
    ])->name('appointments.web.update-status');

    Route::delete('/appointments/{appointment}', [
        AppointmentWebController::class,
        'destroy',
    ])->name('appointments.web.destroy');


    /*
    |--------------------------------------------------------------------------
    | Examinations
    |--------------------------------------------------------------------------
    */

    Route::get('/examinations', [
        ExaminationWebController::class,
        'index',
    ])->name('examinations.web.index');

    Route::get('/examinations/create/{appointment}', [
        ExaminationWebController::class,
        'create',
    ])->name('examinations.web.create');

    Route::post('/examinations/{appointment}', [
        ExaminationWebController::class,
        'store',
    ])->name('examinations.web.store');

    Route::get('/examinations/{examination}', [
        ExaminationWebController::class,
        'show',
    ])->name('examinations.web.show');


    /*
    |--------------------------------------------------------------------------
    | Schedules
    |--------------------------------------------------------------------------
    */

    Route::get('/schedules', [
        ScheduleWebController::class,
        'index',
    ])->name('schedules.web.index');

    Route::get('/schedules/create', [
        ScheduleWebController::class,
        'create',
    ])->name('schedules.web.create');

    Route::post('/schedules', [
        ScheduleWebController::class,
        'store',
    ])->name('schedules.web.store');

    Route::get('/schedules/{schedule}/edit', [
        ScheduleWebController::class,
        'edit',
    ])->name('schedules.web.edit');

    Route::put('/schedules/{schedule}', [
        ScheduleWebController::class,
        'update',
    ])->name('schedules.web.update');

    Route::delete('/schedules/{schedule}', [
        ScheduleWebController::class,
        'destroy',
    ])->name('schedules.web.destroy');


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */

    Route::resource('users', UserWebController::class)
        ->except(['show'])
        ->names('users.web');


    /*
    |--------------------------------------------------------------------------
    | Medicines
    |--------------------------------------------------------------------------
    */

    Route::resource('medicines', MedicineWebController::class)
        ->except(['show'])
        ->names('medicines.web');


    /*
    |--------------------------------------------------------------------------
    | Prescriptions
    |--------------------------------------------------------------------------
    */

    Route::get('/prescriptions', [
        PrescriptionWebController::class,
        'index',
    ])->name('prescriptions.web.index');

    Route::get('/prescriptions/create', [
        PrescriptionWebController::class,
        'create',
    ])->name('prescriptions.web.create');

    Route::post('/prescriptions', [
        PrescriptionWebController::class,
        'store',
    ])->name('prescriptions.web.store');

    Route::get('/prescriptions/{prescription}', [
        PrescriptionWebController::class,
        'show',
    ])->name('prescriptions.web.show');

    Route::post('/prescriptions/{prescription}/items', [
        PrescriptionWebController::class,
        'addItem',
    ])->name('prescriptions.web.items.store');

    Route::put('/prescription-items/{item}', [
        PrescriptionWebController::class,
        'updateItem',
    ])->name('prescriptions.web.items.update');

    Route::delete('/prescription-items/{item}', [
        PrescriptionWebController::class,
        'removeItem',
    ])->name('prescriptions.web.items.destroy');


    /*
    |--------------------------------------------------------------------------
    | Invoices & Payments
    |--------------------------------------------------------------------------
    */

    Route::post('/examinations/{examination}/invoice', [
        InvoiceWebController::class,
        'store',
    ])->name('invoices.web.store');

    Route::get('/invoices', [
        \App\Http\Controllers\Web\InvoiceWebController::class,
        'index',
    ])->name('invoices.web.index');

    Route::get('/invoices/create', [
        \App\Http\Controllers\Web\InvoiceWebController::class,
        'create',
    ])->name('invoices.web.create');

    Route::get('/invoices/{invoice}', [
        InvoiceWebController::class,
        'show',
    ])->name('invoices.web.show');

    Route::get('/invoices/{invoice}/payment', [
        PaymentWebController::class,
        'show',
    ])->name('payments.web.show');

    Route::post('/invoices/{invoice}/payment', [
        PaymentWebController::class,
        'store',
    ])->name('payments.web.store');

    Route::get('/invoices/{invoice}/payment/success', [
        PaymentWebController::class,
        'success',
    ])->name('payments.web.success');

    Route::get('/invoices/{invoice}/payment/cancel', [
        PaymentWebController::class,
        'cancel',
    ])->name('payments.web.cancel');
});