<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    protected function redirectTo()
    {
        $role = Auth::user()->role;

        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
            case 'patient':
                return route('patient.dashboard');
            case 'doctor':
                return route('doctor.dashboard');
            case 'admission':
                return route('admission.dashboard');
            case 'billing':
                return route('billing.dashboard');
            case 'hospital_services':
                return route('hospital.dashboard');
            default:
                return '/';
        }
    }
}
