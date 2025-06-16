<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\HospitalService as Service;
use App\Models\PharmacyCharge;
use App\Models\PharmacyChargeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PharmacyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:pharmacy']);
    }

    /**
     * GET /pharmacy
     * Show dashboard with summary and recent charges.
     */
 public function index(Request $request)
{
    // Total medication charges
    $totalCharges = PharmacyCharge::count();

    // Unique patients served
    $patientsServed = PharmacyCharge::distinct('patient_id')->count('patient_id');

    // Pending charges (you can adjust the logic for “pending” however you like)
    // Here we count those with no items yet, or total_amount == 0
    $pendingCharges = PharmacyCharge::where('total_amount', 0)->count();

    // Recent charges (for the table on dashboard)
    $recentCharges = PharmacyCharge::with('patient', 'items')
                        ->orderByDesc('created_at')
                        ->take(5)
                        ->get();

    return view('pharmacy.dashboard', compact(
        'totalCharges',
        'patientsServed',
        'pendingCharges',
        'recentCharges'
    ));
}


    /**
     * GET /pharmacy/create
     * Show form to assign medication charge.
     */
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

    /**
     * POST /pharmacy
     * Validate and save a new medication charge.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'             => 'required|exists:patients,patient_id',
            'prescribing_doctor'     => 'required|string|max:255',
            'rx_number'              => 'required|string|max:100',
            'notes'                  => 'nullable|string',

            'medications'            => 'required|array|min:1',
            'medications.*.service_id'  => 'required|exists:hospital_services,service_id',
            'medications.*.quantity'    => 'required|integer|min:1',
        ]);

        DB::transaction(function() use($data) {
            // Create the charge record
            $charge = PharmacyCharge::create([
                'patient_id'         => $data['patient_id'],
                'prescribing_doctor' => $data['prescribing_doctor'],
                'rx_number'          => $data['rx_number'],
                'notes'              => $data['notes'] ?? null,
                'total_amount'       => 0, // will update below
            ]);

            $grandTotal = 0;

            // Create each item line
            foreach ($data['medications'] as $item) {
                $service   = Service::findOrFail($item['service_id']);
                $unitPrice = $service->price;
                $lineTotal = $unitPrice * $item['quantity'];
                $grandTotal += $lineTotal;

                PharmacyChargeItem::create([
                    'charge_id'   => $charge->id,
                    'service_id'  => $service->service_id,
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $unitPrice,
                    'total'       => $lineTotal,
                ]);
            }

            // Update the grand total
            $charge->update(['total_amount' => $grandTotal]);
        });

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
