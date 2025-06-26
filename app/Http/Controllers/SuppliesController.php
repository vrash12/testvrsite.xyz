<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\MiscellaneousCharge;
use App\Models\Patient;
use Illuminate\Support\Facades\DB; 
use App\Models\HospitalService;

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
            'mostServedSupply'
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
            'patient_id' => 'required|exists:patients,patient_id',
            'service_id' => 'required|exists:hospital_services,service_id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $service   = HospitalService::findOrFail($data['service_id']);
        $unitPrice = $service->price;
        $total     = $unitPrice * $data['quantity'];

        $charge = MiscellaneousCharge::create([
            'patient_id'   => $data['patient_id'],
            'service_id'   => $data['service_id'],
            'quantity'     => $data['quantity'],
            'unit_price'   => $unitPrice,
            'total'        => $total,
            'status'       => 'pending',
            'created_by'   => Auth::id(),
        ]);

        return redirect()
            ->route('supplies.show', $charge->id)
            ->with('success','Supply charge created.');
    }

    /**
     * GET /supplies/queue
     * Show all supply requests, pending and completed
     */
    public function queue()
    {
        $miscReq = MiscellaneousCharge::with(['patient','service','creator','completer'])
            ->orderByDesc('created_at')
            ->get();

        return view('supplies.queue', compact('miscReq'));
    }

    /**
     * GET /supplies/{id}
     * Show details for a single supply request
     */
    public function show($id)
    {
        $charge = MiscellaneousCharge::with(['patient','service','creator','completer'])
            ->findOrFail($id);

        return view('supplies.show', compact('charge'));
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
