<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle the POST /login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->boolean('remember');

        /* ---------- 1. web guard (users table) ---------- */
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('dashboard'));
        }

        /* ---------- 2. admin guard (admins table) ---------- */
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            // land directly on the admin dashboard
            return redirect()->intended(route('admin.dashboard'));
        }

        /* ---------- 3. fail ---------- */
        return back()->withErrors([
            'email' => 'These credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * POST /logout  (logs out whichever guard is active)
     */
    public function logout(Request $request)
    {
        // log out of both, just in case
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
