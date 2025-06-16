<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form (or kick out an already-logged-in user).
     */
    public function showLoginForm()
    {
        //  ⬇ Kick authenticated users to their panel instead of showing /login
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user = Auth::guard('web')->user()) {
            return $user->role === 'admission'
                ? redirect()->route('admission.dashboard')
                : redirect()->route('pharmacy.dashboard');
        }

        //  ⬇ No one logged in → show the form
        return view('auth.login');
    }

  public function login(Request $request)
{
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);
    $remember = $request->boolean('remember');

    // 1️⃣ Try admin guard first
    if (Auth::guard('admin')->attempt($credentials, $remember)) {
        $request->session()->regenerate();
        return redirect()->route('admin.dashboard');
    }

    // 2️⃣ Then try web guard (users table)
    if (Auth::guard('web')->attempt($credentials, $remember)) {
        $request->session()->regenerate();
        $user = Auth::guard('web')->user();
        return $user->role === 'admission'
             ? redirect()->route('admission.dashboard')
             : redirect()->route('pharmacy.dashboard');
    }

    // 3️⃣ Fail
    return back()
        ->withErrors(['email' => 'These credentials do not match our records.'])
        ->onlyInput('email');
}


    /**
     * POST /logout : sign out whichever guard is active.
     */
    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
