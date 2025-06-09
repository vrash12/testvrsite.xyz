<?php
namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function __construct()
    {
        // Only Admission users can hit these methods:
        $this->middleware(['auth','role:admission']);
    }

    /**
     * GET /patients
     * Show a simple table/list of all patients.
     */
    public function index()
    {
        // Paginate or fetch all—here we fetch all for simplicity:
        $patients = Patient::orderBy('patient_last_name')->get();

        return view('patients.index', compact('patients'));
    }

    /**
     * GET /patients/create
     * Show the “New Patient Admission” form (Personal Info step).
     */
    public function create()
    {
        return view('patients.create');
    }

  
    public function store(Request $request)
    {
      
        $request->validate([
            'patient_first_name' => 'required|string|max:100',
            'patient_last_name'  => 'required|string|max:100',
            'patient_birthday'   => 'nullable|date',
            'civil_status'       => 'nullable|string|max:50',
            'email'              => 'nullable|email|unique:patients,email',
            'phone_number'       => 'nullable|string|max:20',
            'address'            => 'nullable|string',
            'password'           => 'required|string|min:8|confirmed',
        ]);

        // Create & save:
        $patient = new Patient();
        $patient->patient_first_name = $request->patient_first_name;
        $patient->patient_last_name  = $request->patient_last_name;
        $patient->patient_birthday   = $request->patient_birthday;
        $patient->civil_status       = $request->civil_status;
        $patient->email              = $request->email;
        $patient->phone_number       = $request->phone_number;
        $patient->address            = $request->address;
        $patient->password           = Hash::make($request->password);
        $patient->status             = 'active';
        $patient->save();

        return redirect()
            ->route('patients.index')
            ->with('success', 'Patient added successfully.');
    }




    public function dashboard()
    {
 
        $patient = Auth::user();

 
        $prescriptions = Prescription::where('patient_id', $patient->id)->get();

        $schedules = Schedule::where('patient_id', $patient->id)->get();

        $assignedDoctors = DoctorAssignment::where('patient_id', $patient->id)->get();

        return view('patient.dashboard', compact('patient', 'prescriptions', 'schedules', 'assignedDoctors'));
    }

}