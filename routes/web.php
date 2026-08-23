<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AcademicYearController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\SalaryController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;

// Public Health check for CI/CD and monitoring
Route::get('/health', function () {
    try {
        \Illuminate\Support\Facades\DB::connection()->getPdo();
        $dbStatus = 'connected';
    } catch (\Throwable $e) {
        $dbStatus = 'disconnected';
    }

    $isOk = $dbStatus === 'connected';

    return response()->json([
        'status' => $isOk ? 'ok' : 'error',
        'database' => $dbStatus,
        'app_env' => config('app.env'),
        'timestamp' => now()->toIso8601String(),
    ], $isOk ? 200 : 503);
});

// Auth routes
Auth::routes(['register' => false]);

// Redirect root to dashboard
Route::redirect('/', '/dashboard');

// Authenticated routes
Route::middleware(['auth', 'active'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Super Admin routes
    Route::middleware(['role:super-admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::put('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::resource('roles', RoleController::class);
    });

    // Academic structure (Super Admin & Administrator)
    Route::middleware(['role:super-admin,administrator'])->group(function () {
        Route::resource('academic-years', AcademicYearController::class);
        Route::put('academic-years/{academic_year}/set-current', [AcademicYearController::class, 'setCurrent'])->name('academic-years.set-current');
        Route::resource('grades', GradeController::class);
        Route::resource('sections', SectionController::class);
    });

    // Students (Administrator, Class Teacher)
    Route::middleware(['role:super-admin,administrator,class-teacher'])->group(function () {
        Route::resource('students', StudentController::class);
        Route::get('students/{student}/payments', [StudentController::class, 'payments'])->name('students.payments');
    });

    // Payments (Administrator, Accountant)
    Route::middleware(['role:super-admin,administrator,accountant'])->group(function () {
        Route::resource('payments', PaymentController::class);
        Route::get('payments/{payment}/receipt', [PaymentController::class, 'receipt'])->name('payments.receipt');
        Route::get('payments-debtors', [PaymentController::class, 'debtors'])->name('payments.debtors');
    });

    // Expenses (Accountant)
    Route::middleware(['role:super-admin,accountant'])->group(function () {
        Route::resource('expense-categories', ExpenseCategoryController::class);
        Route::resource('expenses', ExpenseController::class);
    });

    // Salaries (Accountant)
    Route::middleware(['role:super-admin,accountant'])->group(function () {
        Route::resource('salaries', SalaryController::class);
        Route::put('salaries/{salary}/pay', [SalaryController::class, 'pay'])->name('salaries.pay');
    });

    // Attendance (Class Teacher)
    Route::middleware(['role:super-admin,class-teacher'])->group(function () {
        Route::get('attendances', [AttendanceController::class, 'index'])->name('attendances.index');
        Route::get('attendances/create', [AttendanceController::class, 'create'])->name('attendances.create');
        Route::post('attendances', [AttendanceController::class, 'store'])->name('attendances.store');
        Route::get('attendances/report', [AttendanceController::class, 'report'])->name('attendances.report');
    });

    // Reports (Super Admin, Accountant)
    Route::middleware(['role:super-admin,accountant'])->group(function () {
        Route::get('reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
        Route::get('reports/monthly-income', [ReportController::class, 'monthlyIncome'])->name('reports.monthly-income');
    });

    // API-like routes for AJAX
    Route::get('api/sections-by-grade/{grade}', [SectionController::class, 'getByGrade'])->name('api.sections-by-grade');
    Route::get('api/students-by-section/{section}', [StudentController::class, 'getBySection'])->name('api.students-by-section');
    Route::get('api/student-fee/{student}', [PaymentController::class, 'getStudentFee'])->name('api.student-fee');
});
