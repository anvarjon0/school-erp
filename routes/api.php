<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Admin & Administrator routes
    Route::middleware('role:super-admin,administrator')->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('academic-years', AcademicYearController::class);
        Route::apiResource('grades', GradeController::class);
        Route::apiResource('sections', SectionController::class);
        Route::apiResource('salaries', SalaryController::class);
        Route::apiResource('expense-categories', ExpenseCategoryController::class);
    });

    // Finance routes
    Route::middleware('role:super-admin,administrator,accountant')->group(function () {
        Route::apiResource('payments', PaymentController::class);
        Route::get('payments/debtors', [PaymentController::class, 'debtors']);
        
        Route::apiResource('expenses', ExpenseController::class);
        
        Route::get('reports/financial', [ReportController::class, 'financial']);
        Route::get('reports/monthly-income', [ReportController::class, 'monthlyIncome']);
    });

    // Academic routes
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/payments', [StudentController::class, 'payments']);
    Route::get('sections/{section}/students', [StudentController::class, 'getBySection']);

    // Attendance
    Route::apiResource('attendances', AttendanceController::class)->except(['show', 'destroy']);
    Route::get('attendances/report', [AttendanceController::class, 'report']);
});
