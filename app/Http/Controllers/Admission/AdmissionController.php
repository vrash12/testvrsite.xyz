<?php

namespace App\Http\Controllers\Admission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index()
    {
        return view('admission.dashboard'); // blade view at resources/views/admission/dashboard.blade.php
    }

    public function create()
    {
        return view('admission.create'); // optional: admit new patient
    }

    public function store(Request $request)
    {
        // logic for saving admission
    }

    public function assignRoom(Request $request, $admissionId)
    {
        // logic for assigning room
    }

}
