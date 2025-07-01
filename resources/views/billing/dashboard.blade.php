@extends('layouts.billing')

@section('content')
<div class="container-fluid">

    <h4 class="fw-bold text-primary">Patient Billing  Management</h4>
    <small class="text-muted">Manage patient billing records and disputes</small>

    <div class="row g-3 my-4">
        <div class="col-md-3">
            <div class="bg-light rounded p-3 h-100">
                <div class="text-muted">Total Revenue</div>
                <div class="fs-4 fw-bold">₱{{ number_format($totalRevenue,2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-light rounded p-3 h-100">
                <div class="text-muted">Outstanding Balance</div>
                <div class="fs-4 fw-bold">₱{{ number_format($outstandingBalance,2) }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-light rounded p-3 h-100">
                <div class="text-muted">Active Patients</div>
                <div class="fs-4 fw-bold">{{ $activePatients }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="bg-light rounded p-3 h-100">
                <div class="text-muted">Pending Disputes</div>
                <div class="fs-4 fw-bold">{{ $pendingDisputes }}</div>
            </div>
        </div>
    </div>

    <div class="bg-white border rounded p-4">
        <h5 class="fw-bold mb-3">Recent Billing Activity</h5>

        <form class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" class="form-control form-control-sm" placeholder="Search by Name or MRN">
            </div>
            <div class="col-md-3">
                <select class="form-select form-select-sm">
                    <option selected>Filter By</option>
                </select>
            </div>
            <div class="col-md-1">
                <button class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-filter"></i>
                </button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>PatientID</th>
                        <th>Procedure</th>
                        <th>Patient</th>
                        <th>Department</th>
                        <th class="text-end">Total Amount</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $i)
                    <tr>
                        <td>{{ optional($i->created_at)->format('Y-m-d') }}</td>
                        <td>{{ $i->bill->patient->patient_id ?? '-' }}</td>
                        <td>{{ $i->service->service_name }}</td>
                        <td>{{ $i->bill->patient->patient_first_name }} {{ $i->bill->patient->patient_last_name }}</td>
                        <td>{{ $i->service->department->department_name ?? '-' }}</td>
                        <td class="text-end">₱{{ number_format($i->total,2) }}</td>
                        <td class="text-center">
                            <a href="{{ route('billing.show',$i->bill_id) }}" class="btn btn-outline-secondary btn-sm">
                                Details
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
