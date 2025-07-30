<?php
//app/Http/Controllers/LabController.php
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
use Carbon\Carbon;

class LabController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

 public function dashboard()
{
    $today = Carbon::today();
    $labs = HospitalService::where('service_type','lab')->get();

    $completedCount = ServiceAssignment::whereHas('service',fn($q)=>$q->where('service_type','lab'))
        ->where('service_status','completed')->count();

    $pendingCount = ServiceAssignment::whereHas('service',fn($q)=>$q->where('service_type','lab'))
        ->where('service_status','pending')->count();

    $patientsServed = ServiceAssignment::whereHas('service',fn($q)=>$q->where('service_type','lab'))
        ->distinct('patient_id')->get();

    $todayAdmissions = ServiceAssignment::with('patient','doctor')
        ->whereHas('service',fn($q)=>$q->where('service_type','lab'))
        ->whereDate('created_at',$today)
        ->latest()->get();

    $earlierAdmissions = ServiceAssignment::with('patient','doctor')
        ->whereHas('service',fn($q)=>$q->where('service_type','lab'))
        ->whereDate('created_at','<',$today)
        ->latest()->take(10)->get();

    return view('laboratory.dashboard',compact(
        'labs',
        'completedCount',
        'patientsServed',
        'pendingCount',
        'todayAdmissions',
        'earlierAdmissions'
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

    return view('laboratory.create', compact('patients','doctors','services'));

}
public function store(Request $request)
{
    $data = $request->validate([
        'search_patient'       => 'required|string',
        'doctor_id'            => 'required|exists:doctors,doctor_id',
        'charges'              => 'required|array|min:1',
        'charges.*.service_id' => 'required|exists:hospital_services,service_id',
        'charges.*.amount'     => 'required|numeric|min:0',
        'notes'                => 'nullable|string',
    ]);

    $user    = Auth::user();
    $doctor  = Doctor::findOrFail($data['doctor_id']);
    $patient = Patient::where('patient_id', $data['search_patient'])
                      ->firstOrFail();

    // 1️⃣ Find or create today's Bill
    $bill = \App\Models\Bill::firstOrCreate(
        [
          'patient_id'   => $patient->patient_id,
          'billing_date' => now()->toDateString(),
        ],
        ['payment_status' => 'pending']
    );

    $assignments = [];

    foreach ($data['charges'] as $row) {
        $service = HospitalService::findOrFail($row['service_id']);
        $amount  = $row['amount'];

        // 2️⃣ Create the BillItem
        $billItem = \App\Models\BillItem::create([
            'billing_id'    => $bill->billing_id,
            'service_id'    => $service->service_id,
            'quantity'      => 1,
            'amount'        => $amount,
            'billing_date'  => $bill->billing_date,
        ]);

        // 3️⃣ Queue the ServiceAssignment
        $assignments[] = [
            'patient_id'     => $patient->patient_id,
            'doctor_id'      => $doctor->doctor_id,
            'service_id'     => $service->service_id,
            'amount'         => $amount,
            'service_status' => 'pending',
            'notes'          => $data['notes'] ?? null,
            'bill_item_id'   => $billItem->billing_item_id,
            'created_at'     => now(),
            'updated_at'     => now(),
        ];

        // 4️⃣ Write the AuditLog
        \App\Models\AuditLog::create([
            'bill_item_id' => $billItem->billing_item_id,
            'action'       => 'create',
            'message'      => "Lab “{$service->service_name}” (₱{$amount}) added by {$user->username} for Dr. {$doctor->doctor_name}",
            'actor'        => $user->username,
            'icon'         => 'fa-vials',
        ]);
    }

    // bulk‐insert assignments
    ServiceAssignment::insert($assignments);

    return redirect()
        ->route('laboratory.queue')
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
