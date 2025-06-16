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
    PaymentMethod
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Hash};
use Illuminate\Support\Str;

class PatientController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:admission']);
    }
     public function index()
    {
        // Fetch all patients, ordered by last name
        $patients = Patient::orderBy('patient_last_name')->get();

        return view('patients.index', compact('patients'));
    }

    public function create()
    {
        $departments        = Department::pluck('department_name','department_id');
        $doctors            = Doctor::pluck('doctor_name','doctor_id');
        $insuranceProviders = InsuranceProvider::pluck('name','insurance_provider_id');
        $paymentMethods     = PaymentMethod::pluck('name','payment_method_id');

        return view('patients.create', compact(
            'departments','doctors','insuranceProviders','paymentMethods'
        ));
    }

public function store(Request $request)
{
    /* ---------------------- 1. VALIDATE INPUT ---------------------- */
    $data = $request->validate([
        /* Personal */
        'patient_first_name' => 'required|string|max:100',
        'patient_last_name'  => 'required|string|max:100',
        'patient_birthday'   => 'nullable|date',
        'civil_status'       => 'nullable|string|max:50',
        'phone_number'       => 'nullable|string|max:20',
        'address'            => 'nullable|string',

        /* Medical */
        'primary_reason'     => 'nullable|string',
        'weight'             => 'nullable|numeric',
        'height'             => 'nullable|numeric',
        'temperature'        => 'nullable|numeric',
        'blood_pressure'     => 'nullable|string',
        'heart_rate'         => 'nullable|integer',
        'history_others'     => 'nullable|string',
        'allergy_others'     => 'nullable|string',

        /* Admission */
        'admission_date'     => 'required|date',
        'admission_type'     => 'required|string',
        'admission_source'   => 'nullable|string',
        'department'         => 'required|string|max:100',
        'attending_doctors'  => 'required|string',
        'room_number'        => 'required|string',
        'bed_number'         => 'nullable|string',
        'admission_notes'    => 'nullable|string',

        /* Billing */
        'insurance_provider' => 'nullable|string|max:100',
        'policy_number'      => 'nullable|string|max:100',
        'initial_deposit'    => 'nullable|numeric|min:0',
    ]);

    /* ------------------ 2. GENERATE CREDENTIALS ------------------ */
    $base = strtolower(
        substr($data['patient_first_name'],0,1) .
        substr($data['patient_last_name'],0,1)
    );
    $latest = Patient::where('email','like',"$base.%@patientcare.com")
                     ->orderByDesc('email')->first();
    $seq = ($latest && preg_match('/\.(\d{3})@/',$latest->email,$m))
         ? intval($m[1]) + 1
         : 1;
    $seqPadded    = str_pad($seq,3,'0',STR_PAD_LEFT);
    $generatedEmail = "{$base}.{$seqPadded}@patientcare.com";

    $plainPassword  = Str::random(8);
    $hashedPassword = Hash::make($plainPassword);

    /* --------------------- 3. SAVE TRANSACTION --------------------- */
    $patient = DB::transaction(function() use ($data, $generatedEmail, $hashedPassword, $request) {
        // 3.1 Create Patient
        $p = Patient::create([
            'patient_first_name' => $data['patient_first_name'],
            'patient_last_name'  => $data['patient_last_name'],
            'patient_birthday'   => $data['patient_birthday'],
            'civil_status'       => $data['civil_status'],
            'email'              => $generatedEmail,
            'phone_number'       => $data['phone_number'],
            'address'            => $data['address'],
            'password'           => $hashedPassword,
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

        // 3.3 Department & Doctors
        $department = Department::firstOrCreate(
            ['department_name' => trim($data['department'])],
            ['description'     => null]
        );
        $doctorIds = collect(explode(',', $data['attending_doctors']))
            ->map(fn($n) => trim($n))
            ->filter()
            ->map(fn($n) => Doctor::firstOrCreate(['doctor_name' => $n])->doctor_id)
            ->all();

        // 3.4 Admission Details
        $p->admissionDetail()->create([
            'admission_date'   => $data['admission_date'],
            'admission_type'   => $data['admission_type'],
            'admission_source' => $data['admission_source'],
            'department_id'    => $department->department_id,
            'doctor_id'        => $doctorIds[0] ?? null,
            'room_number'      => $data['room_number'],
            'bed_number'       => $data['bed_number'],
            'admission_notes'  => $data['admission_notes'],
        ]);

        // 3.5 Billing Information & Initial Deposit
        $p->billingInformation()->create([
            'payment_method_id'     => 1,
            'insurance_provider_id' => $data['insurance_provider']
                ? InsuranceProvider::firstOrCreate(['name' => $data['insurance_provider']])
                    ->insurance_provider_id
                : null,
            'policy_number'   => $data['policy_number'],
            'payment_status'  => 'pending',
        ]);

        if (!empty($data['initial_deposit'])) {
            $bill = $p->bills()->create([
                'billing_date'   => now(),
                'payment_status' => 'partial',
            ]);
            $bill->items()->create([
                'amount'          => $data['initial_deposit'],
                'billing_date'    => now(),
                'discount_amount' => 0,
            ]);
        }

        return $p;
    });

    /* --------------------- 4. REDIRECT --------------------- */
    return redirect()
        ->route('patients.show', $patient)
        ->with([
            'generatedEmail' => $generatedEmail,
            'plainPassword'  => $plainPassword,
            'success'        => 'Patient created successfully.',
        ]);
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
