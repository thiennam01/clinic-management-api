<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\UserController; 
use App\Http\Controllers\Api\V1\SpecialtyController; 
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ExaminationController;
use App\Http\Controllers\Api\V1\MedicineController; 
use App\Http\Controllers\Api\V1\PrescriptionController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\InvoiceController;

// Public authentication route
Route::post('login', [AuthController::class, 'login']);

// 1. Routes requiring basic authentication (Sanctum)
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::patch('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
});

// 2. Protected routes requiring authentication and permission checks
Route::middleware(['auth:sanctum', 'permission'])->group(function () {

    Route::apiResource('patients', PatientController::class);
    
    // Specialty API mapped to Api\V1\SpecialtyController
    Route::apiResource('specialties', SpecialtyController::class); 

    // Users API mapped to Api\V1\UserController
    Route::apiResource('users', UserController::class);
    
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('appointments', AppointmentController::class)->only(['index', 'store']);

    // Examination API (Task T2.7)
    Route::post('/examinations', [ExaminationController::class, 'store']);
    // Retrieve all examinations
    Route::get('/examinations', [ExaminationController::class, 'index']);
    // Retrieve a specific examination by ID (e.g., /api/examinations/12)
    Route::get('/examinations/{id}', [ExaminationController::class, 'show']);

    // Medicine Management API (Task T3.1: CRUD medicines and prescribed history)
    Route::apiResource('medicines', MedicineController::class);
    // View prescribed orders containing a specific medicine
    Route::get('medicines/{id}/prescriptions', [MedicineController::class, 'prescribedHistory']);

    // Prescription Management API (Task T3.4: Create prescription)
    Route::get('/prescriptions', [PrescriptionController::class, 'index']); 
    Route::get('/prescriptions/{prescription}', [PrescriptionController::class, 'show']); 
    Route::post('/prescriptions', [PrescriptionController::class, 'store']);

    // Prescription Items Management API (Task T3.5: Add / Update / Remove prescription items)
    Route::post('/prescriptions/{prescription}/items', [PrescriptionController::class, 'addItem']);
    Route::put('/prescription-items/{item}', [PrescriptionController::class, 'updateItem']);
    Route::delete('/prescription-items/{item}', [PrescriptionController::class, 'removeItem']);

    Route::post('/invoices', [InvoiceController::class, 'store']);
    Route::put('/invoices/{invoice}', [InvoiceController::class, 'update']);
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show']);
});

