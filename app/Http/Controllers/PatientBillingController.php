<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bill;
use App\Models\BillingInformation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Dispute;

class PatientBillingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);      // default web guard
    }

    /**
     * Display the Billing & Transactions dashboard.
     */
    public function index(Request $request)
    {
        $patient = Auth::user()->patient;

        // 1) All admissions for dropdown
        $admissions = $patient->admissionDetail()
                              ->orderByDesc('admission_date')
                              ->get();

        // Admission filter: ?admission_id=123
        $admissionId = $request->input('admission_id', $admissions->first()?->admission_id);

        // 2) Summary cards (from billing_information)
        $billingInfo = BillingInformation::where('patient_id', $patient->patient_id)->first();

        $totals = [
            'total'    => $billingInfo?->total_charges    ?? 0,
            'balance'  => ($billingInfo?->total_charges ?? 0) - ($billingInfo?->payments_made ?? 0),
            'discount' => $billingInfo?->discount_amount ?? 0,
        ];

        // 3) Table rows – flatten all items under selected admission
        $items = Bill::with(['items.service.department'])
            ->where('patient_id', $patient->patient_id)
            ->when($admissionId, fn($q) => $q->where('admission_id', $admissionId))
            ->get()
            ->flatMap(function ($bill) {
                return $bill->items->map(function ($item) use ($bill) {
                    return (object) [
                        'billing_date' => $bill->billing_date,
                        'ref_no'       => $bill->billing_id,
                        'description'  => $item->service->service_name,
                        'provider'     => $item->service->department->department_name ?? '-',
                        'amount'       => $item->total,
                        'status'       => $item->status ?? $bill->payment_status,
                        'bill_id'      => $bill->billing_id,
                    ];
                });
            });

        // Optional simple search
        if ($search = $request->input('q')) {
            $items = $items->filter(fn($row) =>
                str_contains(strtolower($row->description), strtolower($search))
            );
        }

        // Optional sort order (?order=asc|desc)
        $items = $items->sortBy('billing_date', descending: $request->input('order','desc')==='desc');

        return view('patient.billing', compact(
            'admissions',
            'admissionId',
            'totals',
            'items'
        ));
    }

    /**
     * Show a single Bill (modal / separate page).
     */
    public function show(Bill $bill)
    {
        $this->authorize('view', $bill); // optional policy

        $bill->load(['items.service.department']);
        return view('patient.bill-show', compact('bill'));
    }

    /**
     * Export the patient’s statement as PDF (stub).
     */
  public function downloadStatement(Request $request)
    {
        $patient = Auth::user()->patient;

        // 1) Determine admission
        $admissionId = $request->input('admission_id')
            ?? $patient->admissionDetail()->latest('admission_date')->first()->admission_id;

        // 2) Fetch billing info
        $billingInfo = BillingInformation::where('patient_id', $patient->patient_id)->first();

        $totals = [
            'total'    => $billingInfo?->total_charges    ?? 0,
            'balance'  => ($billingInfo?->total_charges ?? 0) - ($billingInfo?->payments_made ?? 0),
            'discount' => $billingInfo?->discount_amount ?? 0,
        ];

        // 3) Fetch all line items for that admission
        $items = Bill::with(['items.service.department'])
            ->where('patient_id', $patient->patient_id)
            ->where('admission_id', $admissionId)
            ->get()
            ->flatMap(fn($bill) => $bill->items->map(fn($item) => [
                'date'        => $bill->billing_date->format('Y-m-d'),
                'ref_no'      => $bill->billing_id,
                'description' => $item->service->service_name,
                'provider'    => $item->service->department->department_name ?? '-',
                'amount'      => $item->total,
                'status'      => $item->status ?? $bill->payment_status,
            ]));

        // 4) Load a simple Blade for PDF generation
        $pdf = Pdf::loadView('patient.pdf.statement', [
            'patient'     => $patient,
            'totals'      => $totals,
            'items'       => $items,
            'admission'   => $patient->admissionDetail()->find($admissionId),
        ])->setPaper('a4', 'portrait');

        // 5) Download with a filename
        $filename = 'statement_adm'.$admissionId.'_'.now()->format('Ymd').'.pdf';
        return $pdf->download($filename);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'bill_item_id' => 'required|exists:bill_items,id',
            'reason'       => 'required|string|max:255',
            'details'      => 'nullable|string',
            'documents.*'  => 'file|max:10240',
        ]);

        // save dispute
        $dispute = Dispute::create([
            'bill_item_id' => $data['bill_item_id'],
            'patient_id'   => Auth::user()->patient->patient_id,
            'reason'       => $data['reason'],
            'details'      => $data['details'] ?? '',
            'status'       => 'pending',
        ]);

        // store docs (optional)
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $file->storeAs('disputes/'.$dispute->id, $file->getClientOriginalName(), 'public');
            }
        }

        return back()->with('success','Your dispute request has been submitted.');
    }
}
