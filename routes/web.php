<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\MedicineController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUsersController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\LoginController;
// Public + auth
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login',   [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login',  [LoginController::class, 'login'])->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// New: single dashboard entry point
Route::middleware('auth:admin,web')
     ->get('/dashboard', [DashboardController::class, 'index'])
     ->name('dashboard');

// Your existing panel routes (they still exist if you need them)
Route::middleware(['auth','role:admission'])
     ->prefix('admission')->name('admission.')
     ->group(function () {
         Route::get('dashboard',[AdmissionController::class,'dashboard'])->name('dashboard');
         Route::resource('patients', PatientController::class)
              ->only(['index','create','store','show']);
     });

Route::middleware(['auth','role:pharmacy'])
     ->prefix('pharmacy')->name('pharmacy.')
     ->group(function () {
         Route::get('dashboard',[PharmacyController::class,'index'])->name('dashboard');
         Route::resource('medicines', MedicineController::class);
     });

Route::middleware('auth:admin')
     ->prefix('admin')->name('admin.')
     ->group(function(){
         Route::get('dashboard',[AdminController::class,'dashboard'])->name('dashboard');
         Route::resource('users', AdminUsersController::class);
     });