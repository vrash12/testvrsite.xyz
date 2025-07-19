<?php

namespace App\Http\Controllers;

use App\Models\{
    Patient,
    MedicalDetail,
    AdmissionDetail,
    BillingInformation,
    Bill,
    BillItem,
    Department,
    Doctor,
    InsuranceProvider,
    PaymentMethod,
    Room,
    Bed,
    User

};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\ServiceAssignment;
use App\Models\PharmacyCharge;
use Carbon\Carbon;
use App\Http\Controllers\PatientNotificationController;
use App\Models\PrescriptionItem;

class PatientController extends Controller
{
   public function __construct()
{
    $this->middleware('auth');
}


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

        // 3) Prescriptions to take (for the tile)
        $prescriptions = PrescriptionItem::whereHas('prescription', function($q) use ($patientId) {
                                $q->where('patient_id', $patientId);
                            })
                            ->with('service')
                            ->where('status', 'pending')
                            ->orderByDesc('datetime')
                            ->get();

        // 4) Today’s schedule
        $todaySchedule = ServiceAssignment::with('service.department')
            ->where('patient_id', $patientId)
            ->whereDate('datetime', Carbon::today())
            ->where('service_status', 'confirmed')
            ->get();

        // 5) Assigned doctors (attending + any today)
        $assignedDoctors = collect([ optional($admission)->doctor ])
            ->merge($todaySchedule->pluck('doctor'))
            ->filter()
            ->unique('doctor_id');

        // 6) Pharmacy “Charges” derived from prescription items
        $pharmacyCharges = PrescriptionItem::whereHas('prescription', function($q) use ($patientId) {
                                    $q->where('patient_id', $patientId);
                                })
                                ->with('service')
                                ->orderByDesc('datetime')
                                ->get();

        $pharmacyTotal = $pharmacyCharges
            ->sum(fn($item) => $item->quantity_asked * $item->service->price);
            
              $serviceAssignments = ServiceAssignment::with(['service.department','doctor'])
        ->where('patient_id', $patientId)
        ->orderByDesc('datetime')
        ->get();

    /* 8) Compute the running total based on HospitalService.price */
    $servicesTotal = $serviceAssignments
        ->sum(fn ($sa) => $sa->service->price);
        return view('patient.dashboard', compact(
        'user',
        'admission',
        'amountDue',
        'prescriptions',
        'todaySchedule',
        'assignedDoctors',
        'pharmacyCharges',
        'pharmacyTotal',
        // ⬇ NEW
        'serviceAssignments',
        'servicesTotal'
    ));
    }

public function edit(Patient $patient)
{
    $departments = Department::all();

    // grab the department from the existing admission
    $deptId = optional($patient->admissionDetail)->department_id;

    // load doctors for that department (with today's load)
    $doctors = $deptId
      ? Doctor::where('department_id', $deptId)
          ->withCount(['admissions as today_load' => function($q){
              $q->whereDate('admission_date', now()); 
          }])
          ->get()
      : collect();

    // load rooms for that department
    $rooms = $deptId
      ? Room::where('department_id', $deptId)
          ->with('beds')   // so occupiedCount() works
          ->get()
      : collect();

    // if they already had a room selected, pre-load its beds:
    $beds = optional($patient->admissionDetail)->room_id
      ? Bed::where('room_id', $patient->admissionDetail->room_id)
           ->where('status','available')
           ->get()
      : collect();

    return view('patients.edit', compact(
      'patient','departments','doctors','rooms','beds'
    ));
}
public function store(Request $request)
{
    // 1. Validate all input
    $data = $request->validate([
        // Personal
        'patient_first_name' => 'required|string|max:100',
        'patient_last_name'  => 'required|string|max:100',
        'patient_birthday'   => 'nullable|date',
        'civil_status'       => 'nullable|string|max:50',
        'phone_number'       => 'nullable|string|max:20',
        'address'            => 'nullable|string',
'sex'                => 'required|in:Male,Female',
        // Medical
        'primary_reason'     => 'nullable|string',
        'weight'             => 'nullable|numeric',
        'height'             => 'nullable|numeric',
        'temperature'        => 'nullable|numeric',
        'blood_pressure'     => 'nullable|string',
        'heart_rate'         => 'nullable|integer',
        'history_others'     => 'nullable|string',
        'allergy_others'     => 'nullable|string',

        // Admission
        'admission_date'   => 'required|date',
        'admission_type'   => 'required|string|max:50',
        'admission_source' => 'nullable|string|max:100',
        'department_id'    => 'required|exists:departments,department_id',
        'doctor_id'        => 'required|exists:doctors,doctor_id',
        'room_id'          => 'required|exists:rooms,room_id',
        'bed_id'           => 'nullable|exists:beds,bed_id',
        'admission_notes'  => 'nullable|string',

        // Billing
        'insurance_provider' => 'nullable|string|max:100',
        'policy_number'      => 'nullable|string|max:100',
        'initial_deposit'    => 'nullable|numeric|min:0',
    ]);

    // 2. Generate unique email & default password
    $base = strtolower(substr($data['patient_first_name'],0,1) . substr($data['patient_last_name'],0,1));
    $latest = Patient::where('email','like',"$base.%@patientcare.com")
                     ->orderByDesc('email')
                     ->first();
    $seq = ($latest && preg_match('/\.(\d{3})@/',$latest->email,$m))
         ? intval($m[1]) + 1
         : 1;
    $generatedEmail = "{$base}." . str_pad($seq,3,'0',STR_PAD_LEFT) . "@patientcare.com";
    $plainPassword  = 'password'; // will be hashed by Patient::setPasswordAttribute

    // 3. Wrap in DB transaction
    $patient = DB::transaction(function() use ($data, $generatedEmail, $plainPassword, $request) {
        // 3.1 Create Patient
        $p = Patient::create([
            'patient_first_name' => $data['patient_first_name'],
            'patient_last_name'  => $data['patient_last_name'],
             'sex'                => $data['sex'],    
            'patient_birthday'   => $data['patient_birthday'],
            'civil_status'       => $data['civil_status'],
            'email'              => $generatedEmail,
            'phone_number'       => $data['phone_number'],
            'address'            => $data['address'],
            'password'           => $plainPassword,
            'status'             => 'active',
        ]);

        // 3.2 Medical Details
        $p->medicalDetail()->create([
            'primary_reason'  => $data['primary_reason'],
            'weight'          => $data['weight'],
            'height'          => $data['height'],
            'temperature'     => $data['temperature'],
            'blood_pressure'  => $data['blood_pressure'],
            'heart_rate'      => $data['heart_rate'],
            'medical_history' => json_encode([
                'hypertension'   => (bool)$request->history_hypertension,
                'heart_disease'  => (bool)$request->history_heart_disease,
                'copd'           => (bool)$request->history_copd,
                'diabetes'       => (bool)$request->history_diabetes,
                'asthma'         => (bool)$request->history_asthma,
                'kidney_disease' => (bool)$request->history_kidney_disease,
                'others'         => $data['history_others'],
            ]),
            'allergies'       => json_encode([
                'penicillin'   => (bool)$request->allergy_penicillin,
                'nsaids'       => (bool)$request->allergy_nsaids,
                'contrast_dye' => (bool)$request->allergy_contrast_dye,
                'sulfa'        => (bool)$request->allergy_sulfa,
                'latex'        => (bool)$request->allergy_latex,
                'none'         => (bool)$request->allergy_none,
                'others'       => $data['allergy_others'],
            ]),
        ]);

        // 3.3 Admission Details (capture for billing link)
        $room = Room::findOrFail($data['room_id']);
        $bed  = $data['bed_id'] ? Bed::findOrFail($data['bed_id']) : null;
        $admission = $p->admissionDetail()->create([
            'admission_date'   => $data['admission_date'],
            'admission_type'   => $data['admission_type'],
            'admission_source' => $data['admission_source'] ?? '',
            'department_id'    => $data['department_id'],
            'doctor_id'        => $data['doctor_id'],
            'room_number'      => $room->room_number,
            'bed_number'       => $bed ? $bed->bed_number : '',
            'admission_notes'  => $data['admission_notes'],
        ]);

        if ($data['bed_id']) {
    Bed::where('bed_id', $data['bed_id'])
       ->update([
         'patient_id' => $p->patient_id,
         'status'     => 'occupied',
       ]);
}


        // 3.4 Billing Information
        $insuranceProviderId = $data['insurance_provider']
            ? InsuranceProvider::firstOrCreate(['name'=>$data['insurance_provider']])
                ->insurance_provider_id
            : null;
        $p->billingInformation()->create([
            'payment_method_id'     => 1,
            'insurance_provider_id' => $insuranceProviderId,
            'policy_number'         => $data['policy_number'],
            'payment_status'        => 'pending',
        ]);

        // 3.5 Initial Deposit → link to admission_id
        if (! empty($data['initial_deposit'])) {
            $bill = $p->bills()->create([
                'billing_date'   => now(),
                'payment_status' => 'partial',
                'admission_id'   => $admission->admission_id,
            ]);
            $bill->items()->create([
                'amount'          => $data['initial_deposit'],
                'billing_date'    => now(),
                'discount_amount' => 0,
            ]);
        }

      $p->user()->create([
    'username'      => Str::before($generatedEmail,'@'),
    'email'         => $generatedEmail,
    'password'      => $plainPassword,
    'role'          => 'patient',
    'department_id' => $data['department_id'],
    'room_id'       => $data['room_id'],
    'bed_id'        => $data['bed_id'] ?? null,
    'doctor_id'     => $data['doctor_id'],   // ← link the attending doctor
]);

        return $p;
    });

   return redirect()
    ->route('admission.patients.show', $patient->patient_id)
    ->with([
        'generatedEmail' => $generatedEmail,
        'plainPassword'  => $plainPassword,
        'success'        => 'Patient admitted successfully.',
    ]);

}

    public function index(Request $request)
{
    // start a query builder
    $query = Patient::query();

    // full-text search on MRN or name
    if ($search = $request->input('search')) {
        $query->where(function($q) use($search) {
            $q->where('patient_id', 'like', "%{$search}%")
              ->orWhere('patient_first_name', 'like', "%{$search}%")
              ->orWhere('patient_last_name',  'like', "%{$search}%");
        });
    }

    

    // simple status filter
    if ($status = $request->input('status')) {
        $query->where('status', $status);
    }

 $patients = $query
        ->with('admissionDetail.doctor')    // ← eager-load
        ->orderBy('patient_last_name')
        ->paginate(15)
        ->withQueryString();

    return view('patients.index', compact('patients'));
}



    public function getDoctorsByDepartment($departmentId)
{
    $doctors = Doctor::where('department_id', $departmentId)->get();
    return response()->json($doctors);
}

/**
 * GET /admission/departments/{department}/rooms
 */
public function getRoomsByDepartment($departmentId)
{
    $rooms = Room::where('department_id', $departmentId)
                 ->where('status', 'available')
                 ->get();
    return response()->json($rooms);
}



/**
 * GET /admission/rooms/{room}/beds
 */
public function getBedsByRoom($roomId)
{
    $beds = Bed::where('room_id', $roomId)
               ->where('status', 'available')
               ->get();
    return response()->json($beds);
}

public function create()
{
    $departments = Department::all();

    // If we are returning here after a validation error, use the previously-selected dept.
    $selectedDept = old('department_id');

    $doctors = collect();
    $rooms   = collect();
    $beds    = collect();

    if ($selectedDept) {
        // doctors + today’s load
        $doctors = Doctor::where('department_id', $selectedDept)
            ->withCount(['admissions as today_load' => function ($q) {
                $q->whereDate('admission_date', now()->toDateString());
            }])
            ->get();

        // rooms + current bed occupancy
        $rooms = Room::where('department_id', $selectedDept)
            ->with('beds')  // so Room::occupiedCount() has data
            ->get();
    }

    $insuranceProviders = InsuranceProvider::pluck('name','insurance_provider_id');
    $paymentMethods     = PaymentMethod::pluck('name','payment_method_id');

    return view('patients.create', compact(
        'departments','doctors','rooms','beds',
        'insuranceProviders','paymentMethods'
    ));
}



    public function show(Patient $patient)
{
    $patient->load([
        'medicalDetail',
        'admissionDetail',
        'billingInformation',
        'bills.items'
    ]);

    return view('patients.show', compact('patient'));
}

}
