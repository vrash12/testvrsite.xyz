<?php
namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Medication;      // ← import!
use App\Models\LabTest;         // ← import!
use App\Models\ImagingStudy;    // ← import!
use App\Models\HospitalService; // ← import!


class DoctorController extends Controller
{
  public function dashboard(Request $request)
    {
        // Optional: implement simple “q” search
        $q = $request->query('q');
        $patients = Patient::when($q, fn($qsb) => 
                        $qsb->whereRaw("CONCAT(patient_first_name,' ',patient_last_name) LIKE ?", ["%{$q}%"])
                    )
                    ->with('admissionDetail.room') // eager‐load room
                    ->get();

        return view('doctor.dashboard', compact('patients', 'q'));
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
}
