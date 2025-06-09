<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admission\AdmissionController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Auth;


Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false,
]);

Route::get('/dashboard', fn() => redirect()->route('admission.dashboard'))
     ->middleware('auth')
     ->name('dashboard');

Route::get('/admission/dashboard', [AdmissionController::class, 'index'])
     ->middleware(['auth','role:admission'])
     ->name('admission.dashboard');

// ⬇︎ BELOW: PatientController for Admission users ⬇︎
Route::middleware(['auth','role:admission'])->group(function () {
    // List all patients
    Route::get('patients', [PatientController::class, 'index'])
         ->name('patients.index');

    // “New Patient” form
    Route::get('patients/create', [PatientController::class, 'create'])
         ->name('patients.create');

    // Handle submission of “New Patient”
    Route::post('patients', [PatientController::class, 'store'])
         ->name('patients.store');
});
