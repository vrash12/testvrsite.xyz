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
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\Admin\ResourceController;
use App\Http\Controllers\SuppliesController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\Pharmacy\ChargeController;



Route::prefix('patient')
     ->name('patient.')
     ->middleware('auth')
     ->group(function(){
         Route::get('dashboard', [PatientDashboardController::class, 'dashboard'])
              ->name('dashboard');
         // … any other patient‐only pages …
     });

     Route::middleware('auth')
     ->prefix('supplies')
     ->name('supplies.')
     ->group(function(){
         Route::get('dashboard',      [SuppliesController::class,'dashboard'])
              ->name('dashboard');

         Route::get('create',         [SuppliesController::class,'create'])
              ->name('create');

         Route::post('/',             [SuppliesController::class,'store'])
              ->name('store');

         Route::get('queue',          [SuppliesController::class,'queue'])
              ->name('queue');

         Route::get('{id}',           [SuppliesController::class,'show'])
              ->name('show');

         Route::post('{id}/complete', [SuppliesController::class,'markCompleted'])
              ->name('complete');
     });


Route::middleware('auth')->get('/dashboard', function () {
    $role = Auth::user()->role;

    return match ($role) {
        'admin'     => redirect()->route('admin.dashboard'),
        'admission' => redirect()->route('admission.dashboard'),
        'pharmacy'  => redirect()->route('pharmacy.dashboard'),
        default     => redirect()->route('home'),
    };
});
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/login',   [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class,'login'])
     ->name('login.attempt');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');


// Your existing panel routes (they still exist if you need them)
Route::middleware(['auth'])
     ->prefix('admission')->name('admission.')
     ->group(function () {
         Route::get('departments/{department}/doctors',
                    [PatientController::class,'getDoctorsByDepartment'])
              ->name('departments.doctors');
         Route::get('departments/{department}/rooms',
                    [PatientController::class,'getRoomsByDepartment'])
              ->name('departments.rooms');
         Route::get('rooms/{room}/beds',
                    [PatientController::class,'getBedsByRoom'])
              ->name('rooms.beds');
         Route::get('dashboard',[AdmissionController::class,'dashboard'])->name('dashboard');
         Route::resource('patients', PatientController::class)
              ->only(['index','create','store','show']);
     });

Route::middleware(['auth'])
     ->prefix('pharmacy')->name('pharmacy.')
     ->group(function () {
         Route::get('dashboard',[PharmacyController::class,'index'])->name('dashboard');
         Route::resource('medicines', MedicineController::class);
          Route::resource('charges', ChargeController::class);
     });

Route::middleware(['auth'])
      ->prefix('admin')->name('admin.')
      ->group(function(){
          Route::get('dashboard',[AdminController::class,'dashboard'])->name('dashboard');
          Route::resource('users', AdminUsersController::class);
          Route::get('users/{user}/assign',
    [AdminUsersController::class,'showAssignment'])
    ->name('users.assign');

Route::post('users/{user}/assign',
    [AdminUsersController::class,'updateAssignment'])
    ->name('users.assign.update');
   Route::get  ('resources',              [ResourceController::class,'index'])->name('resources.index');
    Route::get  ('resources/create',       [ResourceController::class,'create'])->name('resources.create');
    Route::post ('resources',              [ResourceController::class,'store'])->name('resources.store');

    // edit/update/destroy for both rooms & beds:
    Route::get    ('resources/{type}/{id}/edit',   [ResourceController::class,'edit'])
           ->where('type','room|bed')->name('resources.edit');
    Route::put    ('resources/{type}/{id}',        [ResourceController::class,'update'])
           ->where('type','room|bed')->name('resources.update');
    Route::delete ('resources/{type}/{id}',        [ResourceController::class,'destroy'])
           ->where('type','room|bed')->name('resources.destroy');


      });

                  
Route::prefix('doctor')
     ->name('doctor.')
     ->middleware('auth')       // checks Auth::guard('web')
     ->group(function(){
         // GET /doctor/dashboard → DoctorController@dashboard
         Route::get('/dashboard', [DoctorController::class,'dashboard'])
              ->name('dashboard');

  Route::get('/patients/{patient}', [DoctorController::class,'show'])
              ->name('patient.show');
               Route::get('/order-entry/{patient}',  // GET form
                     [DoctorController::class,'orderEntry'])
               ->name('order');

          Route::post('/orders/{patient}',     // POST assign order
                     [DoctorController::class,'storeOrder'])
               ->name('orders.store');
     });