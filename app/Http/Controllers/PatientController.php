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
use App\Models\Service;
use App\Notifications\AdmissionCharged;

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

        // 1️⃣ Latest admission (with bed / room / doctor)
        $admission = AdmissionDetail::with(['room','bed','doctor'])
            ->where('patient_id', $patientId)
            ->latest('admission_date')
            ->first();

        // 2️⃣ Base billing (charges minus payments)
        $billing  = BillingInformation::where('patient_id', $patientId)->first();
        $baseDue  = $billing
                  ? ($billing->total_charges - $billing->payments_made)
                  : 0;
 $labTotal = ServiceAssignment::where('patient_id', $patientId)
        ->whereHas('service', fn($q) => $q->where('service_type','lab'))
        ->sum('amount');

            // Service (OR/Consult) subtotal
    $serviceTotal = ServiceAssignment::where('patient_id', $patientId)
    ->whereHas('service', fn($q) => $q->where('service_type','service'))
    ->sum('amount');
        // 3️⃣ Pharmacy subtotal (pending Rx’s × their price)
        $pharmacyTotal = PrescriptionItem::whereHas('prescription', fn($q) =>
                $q->where('patient_id',$patientId)
            )
            ->with('service')
            ->get()
            ->sum(fn($item) => $item->quantity_asked * $item->service->price);

            $servicesSubtotal = $baseDue + $pharmacyTotal + $labTotal + $serviceTotal;
        // 5️⃣ Resource rates
        $resourceRate = optional($admission?->bed)->rate
                      ?? optional($admission?->room)->rate
                      ?? 0;
        $doctorRate   = optional($admission?->doctor)->rate ?? 0;

        // 6️⃣ Grand total
        $amountDue = $servicesSubtotal + $resourceRate + $doctorRate;

        // 7️⃣ Prescriptions to take
        $prescriptions = PrescriptionItem::whereHas('prescription', fn($q) =>
                $q->where('patient_id',$patientId)
            )
            ->with('service')
            ->where('status','pending')
            ->orderByDesc('datetime')
            ->get();

        // 8️⃣ Today’s confirmed schedule
        $todaySchedule = ServiceAssignment::with('service.department')
            ->where('patient_id',$patientId)
            ->whereDate('datetime', Carbon::today())
            ->where('service_status','confirmed')
            ->get();

        // 9️⃣ Assigned doctors
        $assignedDoctors = collect([$admission?->doctor])
            ->merge($todaySchedule->pluck('doctor'))
            ->filter()
            ->unique('doctor_id');

        //  🔟 Service assignments list
        $serviceAssignments = ServiceAssignment::with(['service.department','doctor'])
            ->where('patient_id',$patientId)
            ->orderByDesc('datetime')
            ->get();

        $servicesTotal = $serviceAssignments
            ->sum(fn($sa) => $sa->service->price);

        return view('patient.dashboard', [
            'user'              => $user,
            'admission'         => $admission,
            'baseDue'           => $baseDue,
            'pharmacyTotal'     => $pharmacyTotal,
            'servicesSubtotal'  => $servicesSubtotal,
            'labTotal'          => $labTotal,    
            'resourceRate'      => $resourceRate,
            'serviceTotal'     => $serviceTotal,
            'doctorRate'        => $doctorRate,
            'amountDue'         => $amountDue,
            'prescriptions'     => $prescriptions,
            'todaySchedule'     => $todaySchedule,
            'assignedDoctors'   => $assignedDoctors,
            'pharmacyCharges'   => $prescriptions,
            'pharmacyTotal'     => $pharmacyTotal,
            'serviceAssignments'=> $serviceAssignments,
            'servicesTotal'     => $servicesTotal,
        ]);
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
    $data = $request->validate([
        'patient_first_name'     => 'required|string|max:100',
        'patient_last_name'      => 'required|string|max:100',
        'patient_birthday'       => 'nullable|date',
        'civil_status'           => 'nullable|string|max:50',
        'phone_number'           => 'nullable|string|max:20',
        'address'                => 'nullable|string',
        'sex'                    => 'required|in:Male,Female',
        'primary_reason'         => 'nullable|string',
        'weight'                 => 'nullable|numeric',
        'height'                 => 'nullable|numeric',
        'temperature'            => 'nullable|numeric',
        'blood_pressure'         => 'nullable|string',
        'heart_rate'             => 'nullable|integer',
        'history_others'         => 'nullable|string',
        'allergy_others'         => 'nullable|string',
        'admission_date'         => 'required|date',
        'admission_type'         => 'required|string|max:50',
        'admission_source'       => 'nullable|string|max:100',
        'department_id'          => 'required|exists:departments,department_id',
        'doctor_id'              => 'required|exists:doctors,doctor_id',
        'room_id'                => 'required|exists:rooms,room_id',
        'bed_id'                 => 'nullable|exists:beds,bed_id',
        'admission_notes'        => 'nullable|string',
        'guarantor_name'         => 'required|string|max:100',
        'guarantor_relationship' => 'required|string|max:50',
        'initial_deposit'        => 'nullable|numeric|min:0',
    ]);

    /* ------------- generate unique email & default password ------------- */
    $base  = strtolower(substr($data['patient_first_name'],0,1) . substr($data['patient_last_name'],0,1));
    $last  = Patient::where('email','like',"$base.%@patientcare.com")->orderByDesc('email')->first();
    $seq   = ($last && preg_match('/\.(\d{3})@/',$last->email,$m)) ? intval($m[1])+1 : 1;
    $email = "{$base}." . str_pad($seq,3,'0',STR_PAD_LEFT) . "@patientcare.com";
    $pwd   = 'password';

    /* ------------------- wrap everything in a transaction ---------------- */
    $patient = DB::transaction(function() use ($data,$email,$pwd,$request) {

        /* 1️⃣  PATIENT */
        $p = Patient::create([
            'patient_first_name' => $data['patient_first_name'],
            'patient_last_name'  => $data['patient_last_name'],
            'sex'                => $data['sex'],
            'patient_birthday'   => $data['patient_birthday'],
            'civil_status'       => $data['civil_status'],
            'email'              => $email,
            'phone_number'       => $data['phone_number'],
            'address'            => $data['address'],
            'password'           => $pwd,          // hashed by mutator
            'status'             => 'active',
        ]);

        /* 2️⃣  MEDICAL DETAILS */
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

        /* 3️⃣  ADMISSION */
        $room = Room::findOrFail($data['room_id']);
        $bed  = $data['bed_id'] ? Bed::findOrFail($data['bed_id']) : null;

        $admission = $p->admissionDetail()->create([
            'admission_date'  => now(),
            'admission_type'  => $data['admission_type'],
            'admission_source'=> $data['admission_source'] ?? '',
            'department_id'   => $data['department_id'],
            'doctor_id'       => $data['doctor_id'],
            'room_number'     => $room->room_number,
            'bed_number'      => $bed ? $bed->bed_number : '',
            'admission_notes' => $data['admission_notes'],
        ]);

        if ($bed) {
            $bed->update(['patient_id'=>$p->patient_id,'status'=>'occupied']);
        }

        /* 4️⃣  BILLING INFORMATION */
        $p->billingInformation()->create([
            'payment_method_id'      => 1,
            'guarantor_name'         => $data['guarantor_name'],
            'guarantor_relationship' => $data['guarantor_relationship'],
            'payment_status'         => 'pending',
        ]);

        /* 5️⃣  CREATE USER FIRST! */
        $newUser = $p->user()->create([
            'username'      => Str::before($email,'@'),
            'email'         => $email,
            'password'      => $pwd,
            'role'          => 'patient',
            'department_id' => $data['department_id'],
            'room_id'       => $data['room_id'],
            'bed_id'        => $data['bed_id'] ?? null,
            'doctor_id'     => $data['doctor_id'],
        ]);

        /* 6️⃣  OPTIONAL INITIAL DEPOSIT & NOTIFY  */
        if (!empty($data['initial_deposit'])) {

            $bill = $p->bills()->create([
                'billing_date'   => now(),
                'payment_status' => 'partial',
                'admission_id'   => $admission->admission_id,
            ]);

            $item = $bill->items()->create([
                'amount'          => $data['initial_deposit'],
                'billing_date'    => now(),
                'discount_amount' => 0,
            ]);

            // now we have a real user instance → safe to notify
            $newUser->notify(new AdmissionCharged(
                optional($bed)->rate ?? 0,
                $room->rate,
                optional(Doctor::find($data['doctor_id']))->rate ?? 0,
                $item->billing_item_id
            ));
        }

        return $p;
    });

    return redirect()
        ->route('admission.patients.show', $patient->patient_id)
        ->with([
            'generatedEmail' => $email,
            'plainPassword'  => 'password',
            'success'        => 'Patient admitted successfully.',
        ]);
}


public function index(Request $request)
{
    $query = Patient::query();

    // ← changed from input('search') to input('q')
    if ($search = $request->input('q')) {
        $query->where(function($q) use ($search) {
            $q->where('patient_id', 'like', "%{$search}%")
              ->orWhere('patient_first_name', 'like', "%{$search}%")
              ->orWhere('patient_last_name',  'like', "%{$search}%");
        });
    }

    // status filter (if you ever add one)
    if ($status = $request->input('status')) {
        $query->where('status', $status);
    }

    $patients = $query
        ->with('admissionDetail.doctor')
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


    $paymentMethods     = PaymentMethod::pluck('name','payment_method_id');

    return view('patients.create', compact(
        'departments','doctors','rooms','beds',
     'paymentMethods'
    ));
}

public function show($patient_id)
{
    // Fetch patient using patient_id
    $patient = Patient::findOrFail($patient_id);
    $patient->load([
        'medicalDetail',
        'admissionDetail',
        'billingInformation',
        'bills.items'
    ]);

    // Pass the patient data to the view
    return view('patients.show', compact('patient'));
}

}
