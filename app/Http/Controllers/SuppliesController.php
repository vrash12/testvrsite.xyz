<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MiscellaneousCharge;
use App\Models\Patient;
use Illuminate\Support\Facades\DB; 
use App\Models\HospitalService;
use App\Models\Department;


class SuppliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth'); // only logged-in staff
    }

    /**
     * GET /supplies/dashboard
     * Show a summary panel + recent 5 supplies
     */
  public function dashboard()
    {
        // 1) All supplies ever given
        $suppliesGiven = MiscellaneousCharge::all();

        // 2) Unique patients served
        $patientsServe = MiscellaneousCharge::distinct('patient_id')->count('patient_id');

        // 3) Pending orders count
        $pendingOrders = MiscellaneousCharge::where('status', 'pending')->count();
  $services    = HospitalService::with('department')->orderBy('service_name')->get();
    $departments = Department::orderBy('department_name')->get();
        // 4) 5 most recent
        $recentSupplies = MiscellaneousCharge::with(['patient','service','creator'])
            ->latest('created_at')
            ->take(5)
            ->get();

        // 5) Top‐served items (by quantity)
        $mostServedSupply = MiscellaneousCharge::select('service_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('service_id')
            ->orderByDesc('total_qty')
            ->with('service')
            ->take(5)
            ->get();

        return view('supplies.dashboard', compact(
            'suppliesGiven',
            'patientsServe',
            'pendingOrders',
            'recentSupplies',
            'mostServedSupply',
            'services',
            'departments'
        ));
    }

    /**
     * GET /supplies/create
     * Show form to add a new supply charge
     */
    public function create()
    {
        $patients = Patient::orderBy('patient_last_name')->get();
        $services = HospitalService::orderBy('service_name')->get();

        return view('supplies.create', compact('patients','services'));
    }

    /**
     * POST /supplies
     * Validate & store a new supply charge
     */
 public function store(Request $request)
{
    $data = $request->validate([
        'patient_id'             => 'required|exists:patients,patient_id',
        'notes'                  => 'nullable|string',
        'misc_item'              => 'required|array|min:1',
        'misc_item.*.service_id' => 'required|exists:hospital_services,service_id',
        'misc_item.*.quantity'   => 'required|integer|min:1',
    ]);

    foreach ($data['misc_item'] as $item) {
        $service   = HospitalService::findOrFail($item['service_id']);
        $unitPrice = $service->price;
        $total     = $unitPrice * $item['quantity'];

        MiscellaneousCharge::create([
            'patient_id'   => $data['patient_id'],
            'service_id'   => $item['service_id'],
            'quantity'     => $item['quantity'],
            'unit_price'   => $unitPrice,
            'total'        => $total,
            'status'       => 'pending',
            'notes'        => $data['notes'] ?? null,
            'created_by'   => Auth::id(),
        ]);
    }

    return redirect()
        ->route('supplies.queue')
        ->with('success','Supply charge(s) created.');
}

    public function queue()
    {
        $miscReq = MiscellaneousCharge::with([
                'patient.admissionDetail.doctor',  // ← load admission → doctor
                'service',
                'creator',
                'completer',
            ])
            ->orderByDesc('created_at')
            ->get();

        return view('supplies.queue', compact('miscReq'));
    }


       public function show($id)
    {
        $charge = MiscellaneousCharge::with([
                'patient.admissionDetail.doctor',  // ← same here
                'service',
                'creator',
                'completer',
            ])
            ->findOrFail($id);

        return view('supplies.show', compact('charge'));
    }
public function checkout($id)
{
    $charge = MiscellaneousCharge::findOrFail($id);

    // mark as completed
    $charge->update([
        'status'       => 'completed',
        'completed_by' => Auth::id(),
    ]);

    return redirect()
        ->route('supplies.queue')
        ->with('success', 'Supply charge has been checked out.');
}
    /**
     * POST /supplies/{id}/complete
     * Mark a pending request as completed
     */
    public function markCompleted($id)
    {
        $charge = MiscellaneousCharge::findOrFail($id);
        $charge->update([
            'status'       => 'completed',
            'completed_by' => Auth::id(),
        ]);

        return redirect()
            ->route('supplies.queue')
            ->with('success','Supply request marked completed.');
    }
}
