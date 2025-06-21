<?php
// app/Http/Controllers/PatientDashboardController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdmissionDetail;
use App\Models\BillingInformation;
use App\Models\PrescriptionItem;
use App\Models\ServiceAssignment;

class PatientDashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $patient = $user; // assuming your User *is* the patient

        // 1. Latest admission
        $admission = AdmissionDetail::where('patient_id',$patient->user_id)
                        ->latest('admission_date')
                        ->first();

        // 2. Billing info & amount due
        $billing   = BillingInformation::where('patient_id',$patient->user_id)->first();
        $amountDue = $billing 
                    ? ($billing->total_charges ?? 0) - ($billing->payments_made ?? 0)
                    : 0;

        // 3. Prescriptions to take (today’s or active ones)
        $prescriptions = PrescriptionItem::with('service')
                          ->whereHas('prescription', fn($q) =>
                              $q->where('patient_id',$patient->user_id)
                          )
                          ->get();

        // 4. Today’s schedule (e.g. service_assignments for today)
        $todaySchedule = ServiceAssignment::with('service','doctor')
                           ->where('patient_id',$patient->user_id)
                           ->whereDate('datetime', today())
                           ->get();

        // 5. Assigned doctors (unique)
        $assignedDoctors = $todaySchedule
                           ->pluck('doctor')
                           ->unique('doctor_id');

        return view('patient.dashboard', compact(
            'patient',
            'admission',
            'amountDue',
            'prescriptions',
            'todaySchedule',
            'assignedDoctors'
        ));
    }
}
