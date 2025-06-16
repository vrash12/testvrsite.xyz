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

/* Public landing + default login */
Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes([
    'register' => false,
    'reset'    => false,
    'verify'   => false,
]);

/* Redirect /dashboard based on role */
Route::get('/dashboard', function() {
    $user = Auth::user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'admission') {
        return redirect()->route('admission.dashboard');
    }
    if ($user->role === 'pharmacy') {
        return redirect()->route('pharmacy.dashboard');
    }
    return redirect()->route('home');
})->middleware('auth')->name('dashboard');


/* Admission panel (guard=web + role=admission) */
Route::middleware(['auth','role:admission'])
     ->prefix('admission')
     ->name('admission.')
     ->group(function () {
         Route::get('dashboard', [AdmissionController::class,'dashboard'])
              ->name('dashboard');
         Route::resource('patients', PatientController::class)
              ->only(['index','create','store','show']);
     });


/* Pharmacy panel (guard=web + role=pharmacy) */
Route::middleware(['auth','role:pharmacy'])
     ->prefix('pharmacy')
     ->name('pharmacy.')
     ->group(function () {
         Route::get('dashboard', [PharmacyController::class,'index'])
              ->name('dashboard');
         // charges, medicines etc...
         Route::resource('medicines', MedicineController::class);
     });


Route::middleware('auth:admin')
     ->prefix('admin')
     ->name('admin.')
     ->group(function(){
         Route::get('dashboard', [AdminController::class,'dashboard'])
              ->name('dashboard');
         Route::resource('users', AdminUsersController::class);
         Route::post('logout', [AdminController::class,'logout'])
              ->name('logout');
     });

