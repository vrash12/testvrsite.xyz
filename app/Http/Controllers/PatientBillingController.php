<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Bill;
use App\Models\BillingInformation;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\Dispute;
use App\Models\ServiceAssignment;
use App\Models\BillItem;
use App\Helpers\Audit;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Patient;


class PatientBillingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);      // default web guard
    }


public function index(Request $request)
    {
        // 1️⃣ Get the logged-in patient
        $patient = Auth::user()->patient
                 ?? abort(404, 'No patient profile found.');

        // 2️⃣ Build the admissions dropdown
        $admissions = $patient
            ->admissionDetail()
            ->orderByDesc('admission_date')
            ->get();

        $admissionId = $request->input('admission_id')
                     ?? optional($admissions->first())->admission_id;

        // 3️⃣ Compute total charges & balance
        $chargesQ = BillItem::join('bills','bill_items.billing_id','=','bills.billing_id')
            ->where('bills.patient_id', $patient->patient_id)
            ->when($admissionId, fn($q) => $q->where('bills.admission_id', $admissionId));

        $totalCharges = (float) $chargesQ->sum('bill_items.amount');
        $paymentsMade  = 0; // TODO: implement real payments
        $totals = [
            'total'    => $totalCharges,
            'balance'  => $totalCharges - $paymentsMade,
            'discount' => 0,
        ];

        // 4️⃣ Admission‐scoped bill items
        $admissionItems = Bill::with(['items.service.department','items.dispute'])
            ->where('patient_id', $patient->patient_id)
            ->when($admissionId, fn($q) => $q->where('admission_id', $admissionId))
            ->get()
            ->flatMap(function($bill) {
                // here we use a normal anonymous function so we can refer to $bill inside
                return $bill->items->map(fn($item) => (object)[
                    'billing_item_id' => $item->billing_item_id,
                    'billing_date'    => $bill->billing_date,
                    'ref_no'          => $bill->billing_id,
                    'description'     => $item->service?->service_name ?? '—',
                    'provider'        => $item->service?->department?->department_name ?? '—',
                    'amount'          => $item->amount,
                    'status'          => $item->dispute
                                        ? $item->dispute->status
                                        : ($item->status ?? $bill->payment_status),
                ]);
            });

        // 5️⃣ Manual (no-admission) bill items
        $manualItems = Bill::with(['items.service.department','items.dispute'])
            ->where('patient_id', $patient->patient_id)
            ->whereNull('admission_id')
            ->get()
            ->flatMap(function($bill) {
                return $bill->items->map(fn($item) => (object)[
                    'billing_item_id' => $item->billing_item_id,
                    'billing_date'    => $bill->billing_date,
                    'ref_no'          => $bill->billing_id,
                    'description'     => $item->service?->service_name ?? '—',
                    'provider'        => $item->service?->department?->department_name ?? '—',
                    'amount'          => $item->amount,
                    'status'          => $item->dispute
                                        ? $item->dispute->status
                                        : ($item->status ?? $bill->payment_status),
                ]);
            });

        // 6️⃣ Service assignments
        $assignmentItems = ServiceAssignment::with('service.department')
            ->where('patient_id', $patient->patient_id)
            ->get()
            ->map(fn($as) => (object)[
                'billing_item_id' => 'SA-'.$as->assignment_id,
                'billing_date'    => $as->datetime ?? $as->created_at,
                'ref_no'          => 'SA'.$as->assignment_id,
                'description'     => $as->service?->service_name ?? '—',
                'provider'        => $as->service?->department?->department_name ?? '—',
                'amount'          => $as->amount ?? 0,
                'status'          => $as->service_status,
            ]);

        // 7️⃣ Merge all streams
        $items = collect($assignmentItems)
            ->concat($admissionItems)
            ->concat($manualItems);

        // 8️⃣ Apply search filter
        if ($s = $request->input('q')) {
            $items = $items->filter(fn($r) =>
                str_contains(strtolower($r->description), strtolower($s))
            );
        }

        // 9️⃣ Sort by billing_date
        $desc = $request->input('order','desc') === 'desc';
        $items = $items->sortBy('billing_date', descending: $desc)->values();

        // 🔟 Paginate manually at 10 per page
        $perPage = 10;
        $page    = $request->input('page', 1);
        $total   = $items->count();
        $current = $items->forPage($page, $perPage);

        $paginator = new LengthAwarePaginator(
            $current,
            $total,
            $perPage,
            $page,
            [
                'path'  => $request->url(),
                'query' => $request->query(),
            ]
        );

        // 1️⃣1️⃣ Return the view
        return view('patient.billing', [
            'admissions'  => $admissions,
            'admissionId' => $admissionId,
            'totals'      => $totals,
            'items'       => $paginator,
        ]);
    }
public function chargeTrace(string $key)
{
    if (Str::startsWith($key, 'SA-')) {
        /* ---------- ServiceAssignment branch ---------- */
        $assignmentId = intval(Str::after($key, 'SA-'));

        $as = ServiceAssignment::with(['service.department','doctor'])
                 ->findOrFail($assignmentId);

        // synthesise a pseudo-charge object so the same Blade works
        $charge = (object) [
            'is_assignment'   => true,
            'billing_item_id' => 'SA-'.$as->assignment_id,
            'service'         => $as->service,
            'amount'          => $as->amount ?? 0,
            'status'          => $as->service_status,
            'billing_date'    => $as->datetime ?? $as->created_at,
            'logs'            => collect([
                (object)[
                    'action'     => 'created',
                    'actor'      => optional($as->doctor)->doctor_name ?? '—',
                    'created_at' => $as->datetime ?? $as->created_at,
                ],
                $as->service_status === 'completed'
                    ? (object)[
                        'action'     => 'completed',
                        'actor'      => optional($as->doctor)->doctor_name ?? '—',
                        'created_at' => $as->updated_at ?? $as->datetime,
                      ]
                    : null,
            ])->filter(),
        ];
    } else {
        /* ---------- BillItem branch ---------- */
        $charge = BillItem::with(['service.department','logs'])
                  ->findOrFail(intval($key));
    }

    return view('patient.billing.chargeTrace', compact('charge'));
}
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
