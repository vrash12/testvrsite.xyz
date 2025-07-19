<?php

namespace App\Http\Controllers\Billing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Deposit;

class DepositController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','role:billing']);
    }

    public function create()
    {
        return view('billing.deposit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id'   => 'required|exists:patients,patient_id',
            'amount'       => 'required|numeric|min:0',
            'deposited_at' => 'required|date'
        ]);

        Deposit::create([
            'patient_id'   => $request->patient_id,
            'amount'       => $request->amount,
            'deposited_at' => $request->deposited_at
        ]);

        return back()->with('success','Deposit has been recorded.');
    }
}
