<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SpecialtyController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ExaminationController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\PrescriptionController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

// 1. Route PUBLIC: Đăng nhập để lấy Bearer Token test trên Postman
Route::post('login', [AuthController::class, 'login']);

// 2. ⑬ Route PROTECTED: Yêu cầu Đăng nhập (auth:sanctum) + Kiểm tra Phân quyền (permission)
Route::middleware(['auth:sanctum', 'permission'])->group(function () {

    Route::post('logout', [AuthController::class, 'logout']);

    // API Resources
    Route::apiResource('users', UserController::class);
    Route::apiResource('roles', RoleController::class)->only(['index']);
    Route::apiResource('specialties', SpecialtyController::class);
    Route::apiResource('doctors', DoctorController::class);
    Route::apiResource('patients', PatientController::class);
    Route::apiResource('appointments', AppointmentController::class);
    Route::apiResource('examinations', ExaminationController::class);
    Route::apiResource('medicines', MedicineController::class);
    Route::apiResource('prescriptions', PrescriptionController::class);
    Route::apiResource('invoices', InvoiceController::class);
    Route::apiResource('payments', PaymentController::class);

    // Custom Actions
    Route::patch('users/{user}/status', [UserController::class, 'updateStatus']);
    Route::patch('medicines/{medicine}/stock', [MedicineController::class, 'adjustStock']);
    Route::get('stats', [StatsController::class, 'show']);
});