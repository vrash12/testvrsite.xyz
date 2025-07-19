<?php

namespace App\Http\Controllers;

use App\Models\BillItem;
use App\Models\Patient;
use App\Models\Dispute;
use App\Models\ServiceAssignment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class BillingDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:billing']);
    }

    public function index()
    {
        // Metrics
        $totalRevenue = BillItem::sum(DB::raw('amount - COALESCE(discount_amount,0)'));
        $outstandingBalance = BillItem::whereHas('bill', function($q) {
                $q->where('payment_status','!=','paid');
            })->sum(DB::raw('amount - COALESCE(discount_amount,0)'));
        $activePatients = Patient::where('status','active')->count();
        $pendingDisputes = Dispute::where('status','pending')->count();

        // Recent billing items
        $recentBillItems = BillItem::whereNotNull('service_id')
            ->with(['bill.patient','service.department'])
            ->latest('billing_item_id')
            ->take(10)
            ->get();
        $billItemsTotal = $recentBillItems
            ->sum(fn($item)=> $item->amount - ($item->discount_amount ?? 0));

        // Recent service assignments
        $recentServiceAssignments = ServiceAssignment::with(['patient','service.department'])
            ->latest('assignment_id')
            ->take(10)
            ->get();
        $servicesTotal = $recentServiceAssignments
            ->sum(fn($sa)=> optional($sa->service)->price ?? 0);

        return view('billing.dashboard', [
            'totalRevenue'             => $totalRevenue,
            'outstandingBalance'       => $outstandingBalance,
            'activePatients'           => $activePatients,
            'pendingDisputes'          => $pendingDisputes,
            'recentBillItems'          => $recentBillItems,
            'billItemsTotal'           => $billItemsTotal,
            'recentServiceAssignments' => $recentServiceAssignments,
            'servicesTotal'            => $servicesTotal,
        ]);
    }

    public function print(Patient $patient)
    {
        $charges = BillItem::with('service')
                   ->where('patient_id', $patient->patient_id)
                   ->get();

        $pdf = Pdf::loadView('billing.pdf.soa', [
            'patient' => $patient,
            'charges' => $charges,
        ]);

        return $pdf->download("soa_{$patient->patient_id}.pdf");
    }

    // likewise for lock
    public function lock(Request $request, Patient $patient)
    {
        $patient->billing_locked = true;
        $patient->save();

        return back()->with('success','Billing locked.');
    }
}
