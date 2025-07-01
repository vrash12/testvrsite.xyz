<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Bill,
    BillItem,
    Patient,
    Dispute
};

class BillingDashboardController extends Controller
{
    public function index()
    {
        $totalRevenue       = BillItem::sum('total');
        $outstandingBalance = Bill::where('payment_status','!=','paid')->withSum('items','total')->get()->sum('items_sum_total');
        $activePatients     = Patient::where('status','active')->count();
        $pendingDisputes    = Dispute::where('status','pending')->count();

        $recent = BillItem::with(['bill.patient','service.department'])
            ->latest('created_at')
            ->take(5)
            ->get();

        return view('billing.dashboard',compact(
            'totalRevenue',
            'outstandingBalance',
            'activePatients',
            'pendingDisputes',
            'recent'
        ));
    }
}
