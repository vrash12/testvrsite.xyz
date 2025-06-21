<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdmissionDetail;
use App\Models\BillingInformation;
use App\Models\Bed;

class AdminController extends Controller
{
  public function __construct()
{
    $this->middleware('auth');
}
    /** GET /admin/dashboard */
    public function dashboard()
    {
        $recentAdmissions = AdmissionDetail::with(['patient','doctor'])
            ->latest()
            ->take(5)
            ->get();

        $pendingBillings = BillingInformation::whereNull('payment_status')
            ->with('patient')
            ->take(5)
            ->get();

        $availableBeds = Bed::where('status','available')->count();

        return view('admin.dashboard', compact(
            'recentAdmissions','pendingBillings','availableBeds'
        ));
    }

    /** POST /admin/logout */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
