<?php
//app/Http/Controllers/PharmacyController.php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\HospitalService as Service;
use App\Models\PharmacyCharge;
use App\Models\PharmacyChargeItem;
use App\Models\BillItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:pharmacy']);
    }


public function index(Request $request)
{
$totalCharges   = PharmacyCharge::completed()->count();
$pendingCharges = PharmacyCharge::pending()->count();
$patientsServed = PharmacyCharge::completed()->distinct('patient_id')->count('patient_id');
    /* ───── Base query with search / filters ───── */
    $query = PharmacyCharge::with('patient','items');

    if ($q = $request->input('q')) {
        $query->where(function($sub) use ($q) {
            $sub->where('rx_number','like',"%$q%")
                ->orWhereHas('patient', fn($p) =>
                    $p->where(DB::raw("CONCAT(patient_first_name,' ',patient_last_name)"), 'like', "%$q%"));
        });
    }

    if ($from = $request->input('from')) {
        $query->whereDate('created_at','>=',$from);
    }
    if ($to = $request->input('to')) {
        $query->whereDate('created_at','<=',$to);
    }

    /* ───── Split into today / earlier ───── */
    $todayCharges   = (clone $query)
        ->whereDate('created_at', now()->toDateString())
        ->orderByDesc('created_at')
        ->get();

    $earlierCharges = (clone $query)
        ->whereDate('created_at','<', now()->toDateString())
        ->orderByDesc('created_at')
        ->take(10)          // show only 10 earlier for brevity
        ->get();

    return view('pharmacy.dashboard', compact(
        'totalCharges',
        'patientsServed',
        'pendingCharges',
        'todayCharges',
        'earlierCharges'
    ));
}


public function dispense(PharmacyCharge $charge)
{
    if ($charge->status === 'completed') {
        return back()->with('info','Already marked as dispensed.');
    }

    $charge->update([
      'status'       => 'completed',
      'dispensed_at' => now(),
    ]);

    // notify the patient
    $charge->patient->notify(new PharmacyChargeDispensed($charge));

    return back()->with('success','Medication dispensed & flagged for billing.');
}
    public function create()
    {
        // Active patients
        $patients = Patient::where('status','active')
                    ->orderBy('patient_last_name')
                    ->get();

        // All services as “medications”
        $services = Service::with('department')->get();

        return view('pharmacy.create', compact('patients','services'));
    }

    public function store(Request $request)
    {
        $patient = Patient::findOrFail($request->patient_id);
        if ($patient->billing_closed_at) {
            return back()->with('error', 'Action failed: The patient\'s bill is locked.');
        }

        $data = $request->validate([
            'patient_id'         => 'required|exists:patients,patient_id',
            'prescribing_doctor' => 'required|string|max:255',
            'rx_number'          => 'required|string|max:100',
            'notes'              => 'nullable|string',
            'medications'        => 'required|array|min:1',
            'medications.*.service_id' => 'required|exists:hospital_services,service_id',
            'medications.*.quantity'   => 'required|integer|min:1',
        ]);

        DB::transaction(function() use($data, &$charge) {
            $charge = PharmacyCharge::create([
                'patient_id'         => $data['patient_id'],
                'prescribing_doctor' => $data['prescribing_doctor'],
                'rx_number'          => $data['rx_number'],
                'notes'              => $data['notes'] ?? null,
                'total_amount'       => 0,
            ]);

            $grandTotal = 0;
            foreach ($data['medications'] as $item) {
                $service   = Service::findOrFail($item['service_id']);
                $lineTotal = $service->price * $item['quantity'];
                $grandTotal += $lineTotal;

                PharmacyChargeItem::create([
                    'charge_id'   => $charge->id,
                    'service_id'  => $service->service_id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $service->price,
                    'total'       => $lineTotal,
                ]);
            }

            $charge->update(['total_amount' => $grandTotal]);
        });

        // notify the patient
        $charge->patient->notify(new PharmacyChargeCreated($charge));

        return redirect()
            ->route('pharmacy.index')
            ->with('success', 'Medication charge created successfully.');
    }

    /**
     * GET /pharmacy/{charge}
     * Show details for a single medication charge.
     */
    public function show(PharmacyCharge $charge)
    {
        $charge->load([
            'patient',
            'items.service.department'
        ]);

        return view('pharmacy.show', compact('charge'));
    }
}
