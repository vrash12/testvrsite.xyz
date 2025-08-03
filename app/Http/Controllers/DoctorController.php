<?php
//app/Http/Controllers/DoctorController.php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Medication;      // ← import!
use App\Models\LabTest;         // ← import!
use App\Models\ImagingStudy;    // ← import!
use App\Models\HospitalService; // ← import!
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\AdmissionDetail; // ← import!
use App\Models\Bill;   
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceAssignment;
use Carbon\Carbon;  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PharmacyCharge;
use App\Models\PharmacyChargeItem;
use Illuminate\Support\Str;



class DoctorController extends Controller
{
public function dashboard(Request $request)
    {
        $q = $request->input('q');
        $user = Auth::user();
        $doctor = $user->doctor;
        $doctorId = optional($doctor)->doctor_id;
        Log::debug("[DoctorDashboard] user_id {$user->user_id} doctorId {$doctorId}");

        $patientsQuery = Patient::whereHas('admissionDetail', function($qb) use ($doctorId) {
            $qb->where('doctor_id', $doctorId);
        });
        $initialCount = $patientsQuery->count();
        Log::debug("[DoctorDashboard] patientsQuery count before search: {$initialCount}");

        if ($q) {
            $patientsQuery->where(function($w) use ($q) {
                $w->where('patient_id', 'like', "%{$q}%")
                  ->orWhere('patient_first_name', 'like', "%{$q}%")
                  ->orWhere('patient_last_name', 'like', "%{$q}%");
            });
            $countAfterSearch = $patientsQuery->count();
            Log::debug("[DoctorDashboard] patientsQuery count after search '{$q}': {$countAfterSearch}");
        }

        $patients = $patientsQuery
            ->with('admissionDetail.room')
            ->orderBy('patient_last_name')
            ->paginate(10)
            ->withQueryString();
        Log::debug("[DoctorDashboard] paginated total {$patients->total()} current page count {$patients->count()}");

        $recentAdmissions = AdmissionDetail::with('patient','room')
            ->where('doctor_id', $doctorId)
            ->whereDate('admission_date', Carbon::today())
            ->latest('admission_date')
            ->take(10)
            ->get();
        Log::debug("[DoctorDashboard] recentAdmissions count: " . count($recentAdmissions));

        return view('doctor.dashboard', [
            'patients' => $patients,
            'q' => $q,
            'recentAdmissions' => $recentAdmissions,
        ]);
    }

    public function showRegistrationForm()
    {
        return view('doctors.create');
    }

    // Handle doctor registration
    public function register(Request $request)
    {
        $validated = $request->validate([
            'doctor_name' => 'required|string|max:255',
            'doctor_specialization' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
        ]);

        Doctor::create($validated);

        return redirect()->route('doctors.index')->with('success', 'Doctor registered successfully');
    }
    public function show(Patient $patient)
    {
        // eager‐load any relationships you need, e.g. admissionDetail, medicalDetail, etc.
        $patient->load('admissionDetail.room', 'medicalDetail');

        return view('doctor.show', compact('patient'));
    }
public function orderEntry(Patient $patient)
{
    $services = HospitalService::all();

    return view('doctor.order-entry', [
        'patient'        => $patient->load('medicalDetail','admissionDetail.room'),
        'medications'    => $services->where('service_type','medication'),
        'labTests'       => $services->where('service_type','lab'),
        'imagingStudies' => $services->where('service_type','imaging'),
        'otherServices'  => $services->where('service_type','service'),
    ]);
}

public function storeOrder(Request $request, Patient $patient)
{
  
    if ($patient->billing_closed_at) {
        return back()->with('error', 'Action failed: The patient\'s bill is locked.');
    }
    /* ───────────────────────────────────────────────────────────
     *  0.  Common pre-flight
     * ─────────────────────────────────────────────────────────── */
    $rawPayload = $request->all();        // keep a copy for logging
    $type       = $request->input('type');
    $doctorId   = optional(Auth::user()->doctor)->doctor_id
               ?? Doctor::first()?->doctor_id;

    // 🔎  initial diagnostic line
    Log::debug('[OrderEntry] incoming request', [
        'user_id'  => Auth::id(),
        'patient'  => $patient->patient_id,
        'type'     => $type,
        'payload'  => $rawPayload,
    ]);

    if (! $doctorId) {
        Log::warning('[OrderEntry] NO DOCTOR ID RESOLVED!');
        return back()->withErrors('No doctor profile found.');
    }

if ($type === 'medication') {
    // 1️⃣  Validate (no dosage / freq / route anymore)
    $data = $request->validate([
        'medications'                 => 'required|array|min:1',
        'medications.*.medication_id' => 'required|exists:hospital_services,service_id',
        'medications.*.quantity'      => 'required|integer|min:1',
        'medications.*.duration'      => 'required|integer|min:1',
        'medications.*.duration_unit' => 'required|in:days,weeks',
        'medications.*.instructions'  => 'nullable|string',
       'refills' => 'nullable|integer|min:0',
    'daw'     => 'nullable|boolean',
    ]);
$refills = $data['refills'] ?? 0;
$daw     = $data['daw'] ?? false;
    DB::beginTransaction();
    try {
        /* ── 2️⃣ Bill header (one per day) ─────────────────── */
        $bill = Bill::firstOrCreate(
            [
                'patient_id'   => $patient->patient_id,
                'admission_id' => optional($patient->admissionDetail)->admission_id,
                'billing_date' => today(),
            ],
            ['payment_status' => 'pending']
        );

        /* ── 3️⃣ Prescription header ───────────────────────── */
        $prescription = Prescription::create([
            'patient_id' => $patient->patient_id,
            'doctor_id'  => $doctorId,
        'refills'    => $refills,   // ← optional
    'daw'        => $daw,       // ← optional
        ]);

        /* ── 4️⃣ Pharmacy Charge header ───────────────────── */
        $rxNumber = 'RX' . now()->format('YmdHis') . Str::upper(Str::random(3));

       $pharmCharge = PharmacyCharge::create([
    'patient_id'         => $patient->patient_id,
    'prescribing_doctor' => Doctor::find($doctorId)->doctor_name ?? '-',
    'rx_number'          => $rxNumber,
    'notes'              => $data['medications'][0]['instructions'] ?? null,
    'total_amount'       => 0,
    'status'             => 'pending',
]);

        $grandTotal = 0;

        /* ── 5️⃣ Loop through every medication row ─────────── */
        foreach ($data['medications'] as $row) {
            $svc   = HospitalService::findOrFail($row['medication_id']);
            $line  = $svc->price * $row['quantity'];
            $grandTotal += $line;

            // 5a) Prescription item
            $prescription->items()->create([
                'service_id'     => $svc->service_id,
                'name'           => $svc->service_name,
                'datetime'       => now(),
                'quantity_asked' => $row['quantity'],
                'quantity_given' => 0,
                'duration'       => $row['duration'],
                'duration_unit'  => $row['duration_unit'],
                'instructions'   => $row['instructions'] ?? '',
                'status'         => 'pending',
            ]);

            // 5b) Bill item
            $bill->items()->create([
                'service_id'      => $svc->service_id,
                'amount'          => $line,
                'billing_date'    => now(),
                'discount_amount' => 0,
                'status'          => 'pending',
            ]);

            // 5c) Pharmacy-charge item
            PharmacyChargeItem::create([
                'charge_id'  => $pharmCharge->id,
                'service_id' => $svc->service_id,
                'quantity'   => $row['quantity'],
                'unit_price' => $svc->price,
                'total'      => $line,
            ]);
        }

        // 6️⃣  Update pharmacy charge total
        $pharmCharge->update(['total_amount' => $grandTotal]);

        DB::commit();

        Log::debug('[OrderEntry] MED + PHARM OK', [
            'bill_id'      => $bill->billing_id,
            'rx'           => $pharmCharge->rx_number,
            'presc_id'     => $prescription->id,
        ]);

        return back()->with('success', 'Medication orders submitted, billed & sent to pharmacy.');

    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('[OrderEntry] MED FAIL', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return back()->withErrors('Unable to submit medication orders.');
    }
}

    /* ───────────────────────────────────────────────────────────
     *  B. LAB  +  IMAGING  (same form)
     * ─────────────────────────────────────────────────────────── */
    if ($type === 'lab') {
        $data = $request->validate([
            'labs'            => 'nullable|array',
            'labs.*'          => 'exists:hospital_services,service_id',
            'studies'         => 'nullable|array',               // ← imaging
            'studies.*'       => 'exists:hospital_services,service_id',
            'diagnosis'       => 'nullable|string',
            'collection_date' => 'required|date',
            'priority'        => 'required|in:routine,urgent,stat',
            'notes'           => 'nullable|string',
            'fasting'         => 'nullable|boolean',
        ]);

        // merge the two checkbox groups
        $serviceIDs = collect($data['labs']   ?? [])
                    ->merge($data['studies'] ?? [])
                    ->unique()
                    ->values();

        if ($serviceIDs->isEmpty()) {
            Log::info('[OrderEntry] LAB form submitted with no items');
            return back()->withErrors('Select at least one Lab / Imaging study.');
        }

        foreach ($serviceIDs as $service_id) {
            $service = HospitalService::findOrFail($service_id);

            ServiceAssignment::create([
                'patient_id'     => $patient->patient_id,
                'doctor_id'      => $doctorId,
                'service_id'     => $service->service_id,
                'datetime'       => $data['collection_date'],
                'service_status' => 'pending',
            ]);

            Log::debug('[OrderEntry] LAB/IMG assignment created', [
                'service' => $service->service_name,
            ]);
        }

        return back()->with('success', 'Lab / Imaging order saved.');
    }

    /* ───────────────────────────────────────────────────────────
     *  C. OTHER SERVICES
     * ─────────────────────────────────────────────────────────── */
    if ($type === 'service') {
        $data = $request->validate([
            'services'       => 'required|array|min:1',
            'services.*'     => 'exists:hospital_services,service_id',
            'diagnosis'      => 'nullable|string',
            'scheduled_date' => 'required|date',
            'priority'       => 'required|in:routine,urgent,stat',
            'frequency'      => 'nullable|string',
            'duration'       => 'nullable|integer|min:1',
            'duration_unit'  => 'nullable|string',
            'instructions'   => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            foreach ($data['services'] as $service_id) {
                $service = HospitalService::findOrFail($service_id);

                ServiceAssignment::create([
                    'patient_id'     => $patient->patient_id,
                    'doctor_id'      => $doctorId,
                    'service_id'     => $service->service_id,
                    'datetime'       => $data['scheduled_date'],
                    'service_status' => 'pending',
                ]);
            }
            DB::commit();
            Log::debug('[OrderEntry] OTHER services OK', $data['services']);
            return back()->with('success', 'Service order submitted.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('[OrderEntry] OTHER services FAILED', [
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors('Unable to submit service order.');
        }
    }

    /* ───────────────────────────────────────────────────────────
     *  D. Unknown type
     * ─────────────────────────────────────────────────────────── */
    Log::warning('[OrderEntry] Unknown type supplied', ['type' => $type]);
    abort(400, 'Unknown order type');
    
    return redirect()
       ->route('doctor.orders.index')
       ->with('success', 'Order saved.')
       ->with('show_patient', $patient->patient_id);

}


public function ordersIndex(Request $request)
    {
        $doctorId = optional(Auth::user()->doctor)->doctor_id;
        $patients = Patient::whereHas('admissionDetail', function($q) use ($doctorId) {
                $q->where('doctor_id', $doctorId);
            })
            ->where(function($q) {
                $q->whereHas('serviceAssignments')
                  ->orWhereHas('prescriptions');
            })
            ->withCount(['serviceAssignments','prescriptions'])
            ->orderBy('service_assignments_count','desc')
            ->paginate(12);

        return view('doctor.orders-index', compact('patients'));
    }


// in DoctorController.php

public function patientOrders(Patient $patient)
{
    try {
        // 1) existing service assignments
        $serviceOrders = ServiceAssignment::where('patient_id', $patient->patient_id)
            ->with('service')
            ->latest()
            ->get();

        // 2) prescription items for this patient
        $medOrders = PrescriptionItem::whereHas('prescription', function($q) use ($patient) {
                $q->where('patient_id', $patient->patient_id);
            })
            ->with('service')   // make sure PrescriptionItem::service() is defined and imported
            ->orderByDesc('datetime')
            ->get();

        return view('doctor.partials.orders-list', compact('serviceOrders','medOrders'));
    } catch (\Throwable $e) {
        \Log::error('Error in patientOrders: '.$e->getMessage(), ['trace'=>$e->getTraceAsString()]);
        // in debug you can return the message so you see it in the browser
        return response("Error loading orders: ".$e->getMessage(), 500);
    }
}




}
