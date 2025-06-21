@extends('layouts.doctor')

@section('content')

<div class="container-fluid py-4 border" style="background-color: #fafafafa">

    <h2 class="hdng mb-4">Patient - Doctor Managenement</h2>
    
    <div class="row mb-4">
        {{-- Number of Patients Served --}}
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                <h6 class="card-title">Patients Served</h6>
                <h2 class="card-text">{{ $patientsServed->count() }}</h2>
                </div>
            </div>
        </div>

        {{-- Number of Given Prescription --}}
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body">
                <h6 class="card-title">Prescription Issued</h6>
                <h2 class="card-text">{{ $prescriptionIssued->count() }}</h2>
                </div>
            </div>
        </div>
   </div>

   <div class="row mb-4">

    {{-- Recent Prescriptions: DATE | PATIENT | ITEM | DEPARTMENT | STATUS--}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Recent Prescriptions</div>
                <ul class="list-group list-group-flush">
                    @foreach ($recentPrescriptions as $rcnt)
                        <li class="list-group-item">
                            {{ $rcnt->recent_date->format('M d, Y') }}
                            {{ $rcnt->patient_name }}
                            {{ $rcnt->item_name}}
                            {{ $rcnt->department_name}}
                            {{ $rcnt->status}}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    
    {{-- Most Prescribed for the day: ITEM NAME | DEPARTMENT | COUNT  --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Most Prescribed</div>
                <ul class="list-group list-group-flush">
                    @foreach ($mostPrescribedItems as $mst)
                        <li class="list-group-item">
                            {{ $mst->item_name }}
                            {{ $mst->item_type }}
                            {{ $mst->item_count }} 
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
   </div>

</div>