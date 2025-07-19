<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\BillItem;
use App\Models\User;          // for notifiable (patient & billing staff)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DisputeFiled;
use Illuminate\Support\Facades\Log;    
use App\Helpers\Audit;
use App\Notifications\DisputeResolved;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{

   public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:patient')->only(['store', 'myDisputes']);
        $this->middleware('role:billing')->only(['index','show','update','queue']);
    }

  public function queue(Request $request)
    {
        // Optional: apply search & status filters
        $query = Dispute::query();

        if ($q = $request->input('q')) {
            $query->whereHas('patient', fn($q2) =>
                $q2->where('patient_first_name', 'like', "%{$q}%")
                   ->orWhere('patient_last_name', 'like', "%{$q}%")
                   ->orWhere('patient_id', $q)
            );
        }

        if ($status = $request->input('status')) {
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $disputes = $query->with(['patient','billItem.bill','billItem.service'])
                          ->latest('datetime')
                          ->paginate(15)
                          ->withQueryString();

        return view('billing.dispute.queue', compact('disputes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_item_id' => 'required|exists:bill_items,billing_item_id',
            'reason'       => 'required|string|max:2000',
        ]);

        $billItem = BillItem::with('bill.patient')->findOrFail($request->bill_item_id);

        // Ensure the authenticated patient owns the bill item
        abort_unless(
            $billItem->bill->patient_id === auth()->user()->patient_id,
            403,
            'You may dispute only your own charges.'
        );

        $dispute = Dispute::create([
            'billing_item_id' => $billItem->billing_item_id,
            'patient_id'      => $billItem->bill->patient_id,
            'datetime'        => now(),
            'reason'          => $request->reason,
            'status'          => 'pending',
        ]);

        /* Notify all billing users */
        $billingUsers = User::where('role', 'billing')->get();
        Notification::send($billingUsers, new DisputeFiled($dispute));

        return back()->with('success', 'Dispute submitted. Billing staff will review it shortly.');
    }

    

    /**
     * GET /my-disputes
     * Lists the authenticated patient’s disputes.
     */
    public function myDisputes()
    {
        $disputes = Dispute::where('patient_id', auth()->user()->patient_id)
                           ->with(['billItem.bill', 'billItem.service'])
                           ->latest('datetime')
                           ->paginate(10);

        return view('disputes.patient_index', compact('disputes'));
    }

    /* -----------------------------------------------------------------
     |  BILLING-STAFF ACTIONS
     |------------------------------------------------------------------*/

    /**
     * GET /disputes
     * Shows all unresolved disputes.
     */
    public function index()
    {
        $disputes = Dispute::where('status', 'pending')
                           ->with(['patient', 'billItem.bill', 'billItem.service'])
                           ->latest('datetime')
                           ->paginate(15);

        return view('disputes.index', compact('disputes'));
    }
public function show(Dispute $dispute)
{
    // 1) Eager-load related data
    $dispute->load([
        'patient',
        'billItem.bill.items.service.department',
        'billItem.service.department',
        'billItem.logs',
    ]);

    // 2) Extract patient & the single disputed charge
    $patient = $dispute->patient;
    $charge  = $dispute->billItem;

    // 3) Grab the parent Bill and all its items
    $bill    = $charge->bill;
    $charges = $bill->items;  // Collection of BillItem models

    // 4) Compute totals
    $totalCharges  = $charges->sum(fn($item) => $item->amount - ($item->discount_amount ?? 0));
    // If you have a Deposit model/relation, swap out the next line:
    $totalDeposits = 0;  
    $balance       = $totalCharges - $totalDeposits;

    // 5) Return the view with everything it needs
    return view('billing.show', compact(
        'dispute',
        'patient',
        'charge',
        'charges',
        'totalCharges',
        'totalDeposits',
        'balance'
    ));
}

    /**
     * PATCH /disputes/{dispute}
     * Accepts: action = approve|reject  ;  optional notes
     */
    public function update(Request $request, Dispute $dispute)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'notes'  => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($request, $dispute) {

            // Update dispute status
            $dispute->update([
                'status'      => $request->action === 'approve' ? 'approved' : 'rejected',
                'approved_by' => auth()->id(),
            ]);

            /* If approved, zero-out or adjust the bill item */
            if ($request->action === 'approve') {
                $dispute->billItem->update([
                    'discount_amount' => $dispute->billItem->amount,
                ]);
            }

            /* Optional notes can be stored as a JSON meta column, or a new table.
               Shown here as a quick example of attaching staff notes:           */
            if ($request->filled('notes')) {
                $dispute->billItem->update([
                    'notes' => $request->notes,
                ]);
            }

                        Audit::log(
                $dispute->billItem->billing_item_id,
                ucfirst($request->action),
                "Dispute {$request->action} by ".auth()->user()->username,
                auth()->user()->username,
                $request->action === 'approve' ? 'fa-check' : 'fa-times'
            );
        });

        /* Notify the patient */
        $dispute->patient->notify(new DisputeResolved($dispute));

        return redirect()
            ->route('disputes.index')
            ->with('success', 'Dispute processed.');
    }
}
