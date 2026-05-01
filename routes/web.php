<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('employees', EmployeeController::class);

    Route::get('/payroll', [PayrollController::class, 'index'])->name('payroll.index');
    Route::get('/payroll/create', [PayrollController::class, 'create'])->name('payroll.create');
    Route::post('/payroll', [PayrollController::class, 'store'])->name('payroll.store');
    Route::get('/payroll/{payroll}', [PayrollController::class, 'show'])->name('payroll.show');
    Route::post('/payroll/{payroll}/process', [PayrollController::class, 'process'])->name('payroll.process');
    Route::post('/payroll/{payroll}/approve', [PayrollController::class, 'approve'])->name('payroll.approve');
    Route::get('/payroll/{payroll}/print', [PayrollController::class, 'print'])->name('payroll.print');
    Route::get('/payroll/{payroll}/transmittal', [PayrollController::class, 'transmittal'])->name('payroll.transmittal');
    Route::get('/payroll-entry/{payroll}/edit', [PayrollController::class, 'editEntry'])->name('payroll.entry.edit');
    Route::put('/payroll-entry/{payroll}', [PayrollController::class, 'updateEntry'])->name('payroll.entry.update');
    Route::get('/payslip/{payroll}', [PayrollController::class, 'payslip'])->name('payroll.payslip');

    Route::resource('loans', LoanController::class)->except(['show']);

    Route::middleware('role:Admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::put('/settings', [SettingsController::class, 'update'])->name('settings.update');
        Route::get('/settings/contributions', [SettingsController::class, 'contributions'])->name('settings.contributions');

        Route::resource('users', UserController::class)->except(['show']);
    });
});
