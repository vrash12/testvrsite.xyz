<?php
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
//auth
//ray
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceAssignment;
use Carbon\Carbon;  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class DoctorController extends Controller
{
    
public function dashboard(Request $request)
{
    $q       = $request->input('q');
    $doctor  = Auth::user()->doctor;          // users.doctor() relationship
    $doctorId = optional($doctor)->doctor_id; // null-safe

    $patientsQuery = Patient::query();

    if ($q) {
        $patientsQuery->where(function($w) use ($q) {
            $w->where('patient_id', 'like', "%{$q}%")
              ->orWhere('patient_first_name', 'like', "%{$q}%")
              ->orWhere('patient_last_name',  'like', "%{$q}%");
        });
    }

    $patients = $patientsQuery
        ->with('admissionDetail.room')
        ->orderBy('patient_last_name')
        ->paginate(10)
        ->withQueryString();

    /* -------------- Recently admitted list ---------- */
    $recentAdmissions = collect();   // default empty

    if ($doctorId) {
        $recentAdmissions = AdmissionDetail::with('patient', 'room')
            ->where('doctor_id', $doctorId)
            ->whereDate('admission_date', Carbon::today())   // ↙ same-day admits
            ->latest('admission_date')
            ->take(10)
            ->get();
    }

    return view('doctor.dashboard', compact('patients', 'q', 'recentAdmissions'));
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
    
  $doctorId = optional(Auth::user()->doctor)->doctor_id
            ?? Doctor::first()?->doctor_id
            ?? abort(500, 'No doctor profile found.');


    $type = $request->input('type');
if ($request->input('type') === 'medication') {
    // 1) Validate exactly as before
    $data = $request->validate([
        'medication_id' => 'required|exists:hospital_services,service_id',
        'dosage'        => 'required|string',
        'frequency'     => 'required|string',
        'route'         => 'required|string',
        'duration'      => 'required|integer|min:1',
        'duration_unit' => 'required|string',
        'instructions'  => 'nullable|string',
        'quantity'      => 'required|integer|min:1',
        'refills'       => 'required|integer|min:0',
        'routing'       => 'required|in:internal,external',
        'priority'      => 'required|in:routine,urgent,stat',
        'daw'           => 'nullable|boolean',
    ]);

    Log::debug('▶ Medication branch hit', $data);

    // 2) Fetch the service
    $service = HospitalService::findOrFail($data['medication_id']);

    // 3) Wrap in a transaction
    DB::beginTransaction();
    try {
        // PICK A VALID DOCTOR ID:
        // - use the one on the user, or
        // - fall back to the first doctor record in your table
        $doctorId = Auth::user()->doctor_id 
                    ?? Doctor::first()->doctor_id;

        // 4) Create prescription header
        $prescription = Prescription::create([
            'patient_id' => $patient->patient_id,
            'doctor_id'  => $doctorId,
        ]);

        // 5) Create the item (minimal payload—no extra columns yet)
        $prescription->items()->create([
            'service_id'     => $service->service_id,
            'name'           => $service->service_name,
            'datetime'       => now(),
            'quantity_asked' => $data['quantity'],
            'quantity_given' => 0,
            'status'         => 'pending',
        ]);

        DB::commit();
        return back()->with('success','Medication order submitted.');
    } catch (\Throwable $e) {
        DB::rollBack();
        Log::error('Medication order failed', ['exception' => $e]);
        return back()->withErrors('Unable to submit medication order. Please try again.');
    }
}


    if ($type === 'lab') {
        $data = $request->validate([
            'labs'            => 'required|array|min:1',
            'labs.*'          => 'exists:hospital_services,service_id',
            'diagnosis'       => 'nullable|string',
            'collection_date' => 'required|date',
            'priority'        => 'required|in:routine,urgent,stat',
            'notes'           => 'nullable|string',
            'fasting'         => 'nullable|boolean',
        ]);

    foreach ($data['labs'] as $service_id) {
            $service = HospitalService::findOrFail($service_id);

            ServiceAssignment::create([
                'patient_id'     => $patient->patient_id,
                'doctor_id'      => $doctorId,   // ← fixed
                'service_id'     => $service->service_id,
                'datetime'       => $data['collection_date'],
                'service_status' => 'pending',
            ]);
        }
        return back()->with('success','Laboratory order submitted.');
    }

    if ($type === 'imaging') {
        $data = $request->validate([
            'studies'        => 'required|array|min:1',
            'studies.*'      => 'exists:hospital_services,service_id',
            'diagnosis'      => 'nullable|string',
            'scheduled_date' => 'required|date',
            'priority'       => 'required|in:routine,urgent,stat',
            'instructions'   => 'nullable|string',
            'transport'      => 'required|in:ambulatory,wheelchair,stretcher',
            'contrast'       => 'nullable|boolean',
        ]);

     foreach ($data['studies'] as $service_id) {
        $service = HospitalService::findOrFail($service_id);

        ServiceAssignment::create([
            'patient_id'     => $patient->patient_id,
            'doctor_id'      => $doctorId,           
            'service_id'     => $service->service_id,
            'datetime'       => $data['scheduled_date'],
            'service_status' => 'pending',
        ]);
    }

        return back()->with('success','Imaging order submitted.');
    }

if ($type === 'service') {
$doctorId = optional(Auth::user()->doctor)->doctor_id
              ?? Doctor::first()?->doctor_id;

    if (! $doctorId) {
        return back()->withErrors(
            'No doctor profile found. Please contact admin.'
        );
    }
    DB::enableQueryLog();

    // 1️⃣  — validate & capture input -------------------------
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


    // 2️⃣  — log the validated payload
    Log::debug('▶ SERVICE branch hit', $data);

    // 3️⃣  — wrap everything in a transaction ---------------
    DB::beginTransaction();
    try {
        foreach ($data['services'] as $service_id) {
            $service = HospitalService::findOrFail($service_id);

            Log::debug('Creating ServiceAssignment', [
                'patient_id' => $patient->patient_id,
                'doctor_id'  => $doctorId,          // <—— use the same resolved id
                'service_id' => $service->service_id,
            ]);

            ServiceAssignment::create([
                'patient_id'     => $patient->patient_id,
                'doctor_id'      => $doctorId,      // <—— DON’T use Auth::id()
                'service_id'     => $service->service_id,
                'datetime'       => $data['scheduled_date'],
                'service_status' => 'pending',
            ]);
        }

        DB::commit();

        // 4️⃣  — dump the SQL that just ran (comment out when done)
        Log::debug('Executed SQL', DB::getQueryLog());

        return back()->with('success', 'Service order submitted.');
    } catch (\Throwable $e) {
        DB::rollBack();

        // 5️⃣  — log the full exception & surface a friendly error
        Log::error('Service order FAILED', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()->withErrors(
            'Unable to submit service order: '.$e->getMessage()
        );
    }
}
    abort(400,'Unknown order type');
}

// in DoctorController.php
public function ordersIndex()
{
    $patients = Patient::where(function($q) {
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
