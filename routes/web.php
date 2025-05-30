<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\MedicalController;
use App\Http\Controllers\AuthenticatedSessionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Home
Route::view('/', 'welcome')->name('home');

// Guest (non-authenticated) routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Patient self-registration
Route::get('/patients/register', [PatientController::class, 'showRegistrationForm'])
     ->name('patients.register');
Route::post('/patients/register', [PatientController::class, 'register']);
Route::get('/patients/{id}/edit', [PatientController::class, 'showEditForm'])
     ->name('patients.edit');
Route::post('/patients/{id}/edit', [PatientController::class, 'update']);
Route::post('/patients/{id}/assign-doctor', [PatientController::class, 'assignDoctor']);

// Doctor self-registration
Route::get('/doctors/register', [DoctorController::class, 'showRegistrationForm'])
     ->name('doctors.register');
Route::post('/doctors/register', [DoctorController::class, 'register']);

// Authenticated user routes (non-admin)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
    Route::get('/profile',     [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',   [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',  [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ────────────────────────────────────────────────────────────────────────────
// Admin routes (all under /admin, named admin.*)
// ────────────────────────────────────────────────────────────────────────────
Route::prefix('admin')
     ->name('admin.')
     ->group(function () {
         
    // Guest-only admin login
    Route::middleware('guest:admin')->group(function () {
        Route::get('login',    [AdminController::class, 'login'])->name('login');
        Route::post('login',   [AdminController::class, 'authenticate'])->name('authenticate');
    });

    // Patient Routes
Route::middleware(['auth', 'role:patient'])->prefix('patient')->group(function () {
     // Patient dashboard
     Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('patient.dashboard');
     
     // Profile routes
     Route::get('/profile', [PatientController::class, 'profile'])->name('patient.profile');
     Route::get('/profile/edit', [PatientController::class, 'editProfile'])->name('patient.profile.edit');
     Route::post('/profile/update', [PatientController::class, 'updateProfile'])->name('patient.profile.update');
     
     Route::get('billing', [PatientController::class, 'billing'])->name('patient.billing');
     Route::get('billing/{billing}', [PatientController::class, 'showBilling'])->name('patient.billing.show');
     Route::get('billing/{billing}/request-review', [PatientController::class, 'requestReview'])->name('patient.billing.request-review');

     
     Route::get('/profile/change-password', [PatientController::class, 'changePasswordForm'])->name('patient.profile.change-password');
     Route::post('/profile/update-password', [PatientController::class, 'updatePassword'])->name('patient.profile.update-password');
 });
 

    // Authenticated admin
    Route::middleware('auth:admin')->group(function () {
        // Dashboard & basic
        Route::get('dashboard', [AdminController::class, 'dashboard'])
             ->name('dashboard');
        Route::get('patients',  [AdminController::class, 'patients'])
             ->name('patients');
        Route::post('logout',   [AdminController::class, 'logout'])
             ->name('logout');

        // Admit new patient form + store
    Route::get('admissions/create', [AdmissionController::class, 'create'])->name('admissions.create');
        Route::post('admissions',       [AdminController::class, 'storePatient'])
             ->name('admissions.store');

        // Admission resource routes
        Route::controller(AdmissionController::class)->group(function () {
            Route::get(   'admissions',               'index')->name('admissions.index');
            Route::get(   'admissions/{admission}',   'show')->name('admissions.show');
            Route::get(   'admissions/{admission}/edit','edit')->name('admissions.edit');
            Route::put(   'admissions/{admission}',   'update')->name('admissions.update');
            Route::delete('admissions/{admission}',   'destroy')->name('admissions.destroy');
        });
Route::get('/medical/{patient}', [MedicalController::class, 'show'])->name('admin.medical.show');

        Route::controller(MedicalController::class)->group(function () {
            Route::get('medical/{patient}', 'show')->name('medical.show');
            Route::post('medical/{patient}','store')->name('medical.store');
            Route::put('medical/{patient}', 'update')->name('medical.update');
        });

        // Billing management
        Route::controller(BillingController::class)->group(function () {
            Route::get(   'billing',              'index')->name('billing.index');
            Route::get(   'billing/create',       'create')->name('billing.create');
            Route::post(  'billing',              'store')->name('billing.store');
            Route::get(   'billing/{billing}',    'show')->name('billing.show');
            Route::get(   'billing/{billing}/edit','edit')->name('billing.edit');
            Route::put(   'billing/{billing}',    'update')->name('billing.update');
            Route::delete('billing/{billing}',    'destroy')->name('billing.destroy');
        });

        // Dynamic form APIs
        Route::prefix('api')->group(function () {
            Route::get('departments',              [AdminController::class, 'getDepartments']);
            Route::get('doctors/{department}',     [AdminController::class, 'getDoctorsByDepartment']);
            Route::get('rooms/{department}',       [AdminController::class, 'getRoomsByDepartment']);
            Route::get('beds/{room}',              [AdminController::class, 'getBedsByRoom']);
            Route::get('insurance-providers',      [AdminController::class, 'getInsuranceProviders']);
        });

        // Availability checks
        Route::prefix('check')->group(function () {
            Route::post('room-availability',   [AdmissionController::class, 'checkRoomAvailability']);
            Route::post('doctor-availability', [AdmissionController::class, 'checkDoctorAvailability']);
            Route::post('insurance-validity',  [BillingController::class,   'checkInsuranceValidity']);
        });

        // PDF Downloads
        Route::get('download/admission/{admission}', [AdmissionController::class, 'downloadPDF'])
             ->name('admission.download');
        Route::get('download/billing/{billing}',    [BillingController::class,   'downloadPDF'])
             ->name('billing.download');
    });

});

// Patient-portal (role: patient)
Route::prefix('patient')
     ->middleware(['auth','role:patient'])
     ->group(function () {
    Route::get('dashboard',            [PatientController::class, 'dashboard'])->name('patient.dashboard');
    Route::get('admissions',           [PatientController::class, 'admissions'])->name('patient.admissions');
    Route::get('admissions/{admission}', [PatientController::class, 'showAdmission'])->name('patient.admissions.show');
    Route::get('medical',              [PatientController::class, 'medical'])->name('patient.medical');
    Route::get('billing',              [PatientController::class, 'billing'])->name('patient.billing');
    Route::get('billing/{billing}',    [PatientController::class, 'showBilling'])->name('patient.billing.show');
});

// Include Laravel's default auth routes (password reset, registration, etc.)
require __DIR__.'/auth.php';
