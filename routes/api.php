<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\V1\UserController; // Sử dụng đúng namespace Controller V1
// use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecialtyController; 
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\V1\AppointmentController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::post('login', [AuthController::class, 'login']);

// 1. Nhóm chỉ cần đăng nhập (Auth Sanctum) là gọi được Schedules
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/schedules', [ScheduleController::class, 'index']);
    Route::post('/schedules', [ScheduleController::class, 'store']);
});

// 2. Nhóm PROTECTED cũ vẫn giữ nguyên check permission
Route::middleware(['auth:sanctum', 'permission'])->group(function () {

    // Route::post('logout', [AuthController::class, 'logout']);

    Route::apiResource('patients', PatientController::class);
    Route::apiResource('specialties', SpecialtyController::class); 

    // Bật lại route users và trỏ đúng đến UserController trong Api\V1
    Route::apiResource('users', UserController::class);
    
    // Route::apiResource('roles', RoleController::class)->only(['index']);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('appointments', AppointmentController::class)->only(['index', 'store']);
    
    // Route::apiResource('appointments', AppointmentController::class);
    // Route::apiResource('examinations', ExaminationController::class);
    // Route::apiResource('medicines', MedicineController::class);
    // Route::apiResource('prescriptions', PrescriptionController::class);
    // Route::apiResource('invoices', InvoiceController::class);
    // Route::apiResource('payments', PaymentController::class);

    // Route::patch('users/{user}/status', [UserController::class, 'updateStatus']);
    // Route::patch('medicines/{medicine}/stock', [MedicineController::class, 'adjustStock']);
    // Route::get('stats', [StatsController::class, 'show']);
});