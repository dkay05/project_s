<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeApiController;
use App\Http\Controllers\Api\DashboardApiController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Base URL: http://localhost:8000/api
| All responses are JSON
| Auth: Laravel Sanctum (Bearer Token)
|
*/

// Public routes (no token needed)
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (Bearer token required)
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Dashboard Stats
    Route::get('/dashboard/stats', [DashboardApiController::class, 'stats']);

    // Employees CRUD
    Route::apiResource('employees', EmployeeApiController::class);

    // Extra Employee Actions
    Route::post('/employees/{id}/toggle-status', [EmployeeApiController::class, 'toggleStatus']);
    Route::get('/employees/{id}/educations', [EmployeeApiController::class, 'educations']);
    Route::get('/employees/{id}/employers', [EmployeeApiController::class, 'previousEmployers']);
    Route::get('/employees/{id}/bank-details', [EmployeeApiController::class, 'bankDetails']);
    Route::get('/employees/{id}/official', [EmployeeApiController::class, 'officialDetail']);
});
