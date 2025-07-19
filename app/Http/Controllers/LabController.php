<?php

namespace App\Http\Controllers;

use App\Models\HospitalService;
use Illuminate\Http\Request;
use App\Models\ServiceAssignment;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\Doctor;
use App\Models\Patient;
//array
use Illuminate\Support\Facades\DB;
use App\Models\LabTest;

class LabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $labs = HospitalService::where('service_type', 'lab')->get();
        $completedCount = ServiceAssignment::whereHas('service', function ($query) {
            $query->where('service_type', 'lab');
        })->where('service_status', 'completed')->count();
        $patientsServed = ServiceAssignment::whereHas('service', function ($query) {
            $query->where('service_type', 'lab');
        })->distinct('patient_id')->get();
        $pendingCount = ServiceAssignment::whereHas('service', function ($query) {
            $query->where('service_type', 'lab');
        })->where('service_status', 'pending')->count();
        $recentActivities = ServiceAssignment::with('patient', 'doctor')
            ->whereHas('service', function ($query) {
                $query->where('service_type', 'lab');
            })
            ->latest()
            ->take(5)
            ->get();

        return view('laboratory.dashboard', compact(
            'labs',
            'completedCount',
            'patientsServed',
            'pendingCount',
            'recentActivities'
        ));
    }

    public function queue(Request $request)
    {
        $statusFilter = $request->input('status', 'all');
        $labRequests = ServiceAssignment::with('patient', 'doctor', 'service')
            ->whereHas('service', function ($query) {
                $query->where('service_type', 'lab');
            });
     

        if ($statusFilter !== 'all') {
            $labRequests->where('service_status', $statusFilter);
        }

        $labRequests = $labRequests->get();

        return view('laboratory.queue', compact('labRequests'));
    }

public function create()
{
    $patients = Patient::select('patient_id','patient_first_name','patient_last_name')
                       ->orderBy('patient_last_name')
                       ->get();

    $doctors  = Doctor::select('doctor_id','doctor_name')
                      ->orderBy('doctor_name')
                      ->get();

    $services = HospitalService::where('service_type','lab')->get();

    return view('laboratory._form', compact('patients','doctors','services'));
}
    public function store(Request $request)
    {
      $data = $request->validate([
    'search_patient'       => 'required|string',
    'doctor_id'            => 'required|exists:doctors,doctor_id',
    'charges'              => 'required|array',
    'charges.*.service_id' => 'required|exists:hospital_services,service_id',
    'charges.*.amount'     => 'required|numeric|min:0',
    'notes'                => 'nullable|string',
]);

        $patient = Patient::where('patient_id', $data['search_patient'])
            ->orWhere('patient_first_name', 'like', "%{$data['search_patient']}%")
            ->orWhere('patient_last_name', 'like', "%{$data['search_patient']}%")
            ->firstOrFail();

        $serviceAssignments = [];
        foreach ($data['charges'] as $chargeData) {
            $serviceAssignments[] = [
                'patient_id'     => $patient->patient_id,
                'doctor_id'      => $data['doctor_id'],
                'service_id'     => $chargeData['service_id'],
                'amount'         => $chargeData['amount'],
                'service_status' => 'pending',
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        ServiceAssignment::insert($serviceAssignments);

        return redirect()->route('laboratory.queue')
                         ->with('success', 'Lab charges have been successfully created.');
    }

// Show the details for a ServiceAssignment
public function show(ServiceAssignment $assignment)
{
    $assignment->load(['patient', 'doctor', 'service.department']);
    return view('laboratory.details', compact('assignment'));
}

public function markCompleted(ServiceAssignment $assignment)
{
    $assignment->service_status = 'completed';
    $assignment->save();

    return redirect()
        ->route('laboratory.details', $assignment)
        ->with('success', 'Request marked as completed.');
}


 public function showRequest(ServiceAssignment $request)
    {
        // eager-load relationships
        $request->load(['patient', 'doctor', 'service']);

        return view('laboratory.request-details', [
            'request' => $request,
        ]);
    }




    public function edit(HospitalService $service)
    {
        return view('laboratory.edit', compact('service'));
    }

    public function update(Request $request, HospitalService $service)
    {
        $data = $request->validate([
            'service_name'  => 'required|string|max:150',
            'department_id' => 'required|exists:departments,department_id',
            'price'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:0',
            'description'   => 'nullable|string',
        ]);

        $service->update($data);

        return redirect()->route('laboratory.dashboard')
                         ->with('success', 'Lab service updated successfully.');
    }

    public function destroy(HospitalService $service)
    {
        $service->delete();

        return redirect()->route('laboratory.dashboard')
                         ->with('success', 'Lab service deleted successfully.');
    }
}
