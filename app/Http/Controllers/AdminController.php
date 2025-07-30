<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Patient;
use App\Models\Room;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $totalActiveUsers = Patient::where('status', 'active')->count();
        $totalCreatedRooms = Room::count();
        $recentUsers = Patient::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalActiveUsers',
            'totalCreatedRooms',
            'recentUsers'
        ));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
