<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\UserController; 
use App\Http\Controllers\Api\V1\SpecialtyController; 
use App\Http\Controllers\Api\V1\DoctorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\Api\V1\ExaminationController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

// 1. Groups only need to be logged in (Sanctum Auth) to call Schedules
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
    Route::patch('/appointments/{id}/status', [AppointmentController::class, 'updateStatus']);
});

// 2. Groups PROTECTED check permission
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
});