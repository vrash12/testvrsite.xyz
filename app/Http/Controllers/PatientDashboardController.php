<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\AdmissionDetail;
use App\Models\BillingInformation;
use App\Models\ServiceAssignment;
use App\Models\PharmacyCharge;
use Carbon\Carbon;

class PatientDashboardController extends Controller
{
    public function dashboard()
    {
        $user      = Auth::user();
        $patientId = $user->patient_id;

        // 1) Latest admission
        $admission = AdmissionDetail::where('patient_id', $patientId)
                        ->latest('admission_date')
                        ->first();

        // 2) Amount due
        $billing   = BillingInformation::where('patient_id', $patientId)->first();
        $amountDue = $billing 
                    ? ($billing->total_charges - $billing->payments_made)
                    : 0;

        // 3) Prescriptions to take
        //    all CONFIRMED service_assignments handled by Pharmacy (dept_id=2)
        $prescriptions = ServiceAssignment::with('service')
            ->where('patient_id', $patientId)
            ->where('service_status', 'confirmed')
            ->whereHas('service', function($q) {
                $q->where('department_id', 2);
            })
            ->get();

        // 4) Today’s schedule 
        $todaySchedule = ServiceAssignment::with('service.department')
            ->where('patient_id', $patientId)
            ->whereDate('datetime', Carbon::today())
            ->get();

        // 5) Assigned doctors (unique)
        $assignedDoctors = ServiceAssignment::with('doctor')
            ->where('patient_id', $patientId)
            ->get()
            ->pluck('doctor')
            ->unique('user_id');

        // 6) Charged items
        $charges = PharmacyCharge::with('items.service')
            ->where('patient_id', $patientId)
            ->get();

        return view('patient.dashboard', compact(
            'user',
            'admission',
            'amountDue',
            'prescriptions',
            'todaySchedule',
            'assignedDoctors',
            'charges'
        ));
    }
}
