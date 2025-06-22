<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        // If user OR patient is already logged in, redirect them
        if (Auth::check() || Auth::guard('patient')->check()) {
            $role = Auth::check()
                ? Auth::user()->role
                : 'patient';
            return $this->redirectByRole($role);
        }

        return view('auth.login');
    }
public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    $remember = $request->boolean('remember');

    // 1) Try the default users table
    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return $this->redirectByRole(Auth::user()->role);
    }

    // 2) Then try the patients table
    if (Auth::guard('patient')->attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return redirect()->route('patient.dashboard');
    }

    // 3) If both attempts fail, send back to login with an error
    return redirect()
        ->route('login')
        ->withErrors(['email' => 'These credentials do not match our records.'])
        ->withInput($request->only('email', 'remember'));
}


    private function redirectByRole(string $role)
    {
        return match ($role) {
            'admin'     => redirect()->route('admin.dashboard'),
            'admission' => redirect()->route('admission.dashboard'),
            'pharmacy'  => redirect()->route('pharmacy.dashboard'),
            'patient'   => redirect()->route('patient.dashboard'),
            'doctor'   => redirect()->route('doctor.dashboard'),
            default     => redirect()->route('home'),
        };
    }

    public function logout(Request $request)
    {
        // Log out from whichever guard is active
        Auth::logout();
        Auth::guard('patient')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
