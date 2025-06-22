<?php

namespace App\Http\Controllers\Pharmacy;

use App\Http\Controllers\Controller;
use App\Models\PharmacyCharge;
use App\Models\PharmacyChargeItem;
use App\Models\Patient;
use App\Models\HospitalService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChargeController extends Controller
{
    /**
     * Display a listing of charges.
     */
    public function index()
    {
        $charges = PharmacyCharge::with('patient','items')
                    ->latest()
                    ->paginate(15);

        return view('pharmacy.charges.index', compact('charges'));
    }

    /**
     * Show form to create a new charge.
     */
    public function create()
    {
        $patients = Patient::all();
        $services = HospitalService::all();

        return view('pharmacy.create', compact('patients','services'));
    }

    /**
     * Store a newly created charge in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'           => 'required|exists:patients,patient_id',
            'prescribing_doctor'   => 'required|string|max:255',
            'rx_number'            => 'required|string|max:100|unique:pharmacy_charges,rx_number',
            'notes'                => 'nullable|string',
            'medications'          => 'required|array|min:1',
            'medications.*.service_id'  => 'required|exists:hospital_services,service_id',
            'medications.*.quantity'    => 'required|integer|min:1',
        ]);

        // Create the charge (initial total_amount = 0)
        $charge = PharmacyCharge::create([
            'patient_id'         => $data['patient_id'],
            'prescribing_doctor' => $data['prescribing_doctor'],
            'rx_number'          => $data['rx_number'],
            'notes'              => $data['notes'] ?? null,
            'total_amount'       => 0,
        ]);

        // Attach items and accumulate total
        $total = 0;
        foreach ($data['medications'] as $item) {
            $svc = HospitalService::findOrFail($item['service_id']);
            $line = $svc->price * $item['quantity'];

            $charge->items()->create([
                'service_id' => $svc->service_id,
                'quantity'   => $item['quantity'],
                'unit_price' => $svc->price,
                'total'      => $line,
            ]);

            $total += $line;
        }

        // Update the real total
        $charge->update(['total_amount' => $total]);

        return redirect()
            ->route('pharmacy.charges.show', $charge)
            ->with('success','Medication charge created.');
    }

    /**
     * Display the specified charge.
     */
    public function show(PharmacyCharge $charge)
    {
        $charge->load('patient','items.service'); // service if you defined relationship
        return view('pharmacy.charges.show', compact('charge'));
    }

    /**
     * Show the form for editing the specified charge.
     */
    public function edit(PharmacyCharge $charge)
    {
        $patients = Patient::all();
        $services = HospitalService::all();
        $charge->load('items');

        return view('pharmacy.charges.edit', compact('charge','patients','services'));
    }

    /**
     * Update the specified charge in storage.
     */
    public function update(Request $request, PharmacyCharge $charge)
    {
        $data = $request->validate([
            'patient_id'           => 'required|exists:patients,patient_id',
            'prescribing_doctor'   => 'required|string|max:255',
            'rx_number'            => [
                'required','string','max:100',
                Rule::unique('pharmacy_charges','rx_number')->ignore($charge->id),
            ],
            'notes'                => 'nullable|string',
            'medications'          => 'required|array|min:1',
            'medications.*.service_id'  => 'required|exists:hospital_services,service_id',
            'medications.*.quantity'    => 'required|integer|min:1',
        ]);

        // Update charge header
        $charge->update([
            'patient_id'         => $data['patient_id'],
            'prescribing_doctor' => $data['prescribing_doctor'],
            'rx_number'          => $data['rx_number'],
            'notes'              => $data['notes'] ?? null,
            'total_amount'       => 0,
        ]);

        // Rebuild items
        $charge->items()->delete();
        $total = 0;
        foreach ($data['medications'] as $item) {
            $svc = HospitalService::findOrFail($item['service_id']);
            $line = $svc->price * $item['quantity'];
            $charge->items()->create([
                'service_id' => $svc->service_id,
                'quantity'   => $item['quantity'],
                'unit_price' => $svc->price,
                'total'      => $line,
            ]);
            $total += $line;
        }
        $charge->update(['total_amount' => $total]);

        return redirect()
            ->route('pharmacy.charges.show', $charge)
            ->with('success','Charge updated.');
    }

    /**
     * Remove the specified charge from storage.
     */
    public function destroy(PharmacyCharge $charge)
    {
        $charge->delete();

        return redirect()
            ->route('pharmacy.charges.index')
            ->with('success','Charge deleted.');
    }
}
