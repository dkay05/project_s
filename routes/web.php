<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use Illuminate\Support\Facades\Route;

// Auth routes (no middleware)
Route::get('/', fn () => redirect()->route('login'));
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin routes (protected by 'admin.auth' middleware)
Route::middleware(['admin.auth'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Employee CRUD
    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{id}/toggle-status', [EmployeeController::class, 'toggleStatus'])
        ->name('employees.toggle-status');
});
