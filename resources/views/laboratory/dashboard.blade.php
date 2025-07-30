@extends('layouts.laboratory')

@section('content')
<div class="container-fluid h-100 border p-4 flex-column" style="background-color: #fafafa;">

    {{-- Header --}}
    <div class="mb-4">
        <h5 class="hdng">Laboratory Services Management</h5>
        <p>Welcome to Laboratory! Manage Lab Charges and Service Completion</p>
    </div>

    {{-- Stats / Metrics --}}
    <div class="row g-3 mb-4">
        <div class="col-lg-4 col-md-6">
            <div class="card border">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-vials fa-2x text-secondary me-4"></i>
                    <div>
                        <div class="text-muted small">Services Completed</div>
                        <h5 class="mb-0">{{ $completedCount }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card border">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-users fa-2x text-secondary me-4"></i>
                    <div>
                        <div class="text-muted small">Patients Served</div>
                        <h5 class="mb-0">{{ $patientsServed->count() }}</h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card border">
                <div class="card-body d-flex align-items-center">
                    <i class="fa-solid fa-hourglass-start fa-2x text-secondary me-4"></i>
                    <div>
                        <div class="text-muted small">Pending Orders</div>
                        <h5 class="mb-0">{{ $pendingCount }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Admissions --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fa-solid fa-calendar-day me-2 text-secondary"></i>
                Today's Admissions
            </h6>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Service</th>
                        <th>Assigned By</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($todayAdmissions as $i => $assign)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $assign->patient->patient_first_name }} {{ $assign->patient->patient_last_name }}</div>
                                <small class="text-muted">P-{{ $assign->patient->patient_id }}</small>
                            </td>
                            <td>{{ $assign->service->service_name }}</td>
                            <td>
                                <span class="badge bg-info text-white">{{ $assign->doctor->doctor_name }}</span>
                            </td>
                            <td class="text-end">₱{{ number_format($assign->amount,2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('laboratory.details', $assign) }}" class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="6" class="text-warning">No admissions today</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Earlier Admissions --}}
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h6 class="mb-0">
                <i class="fa-solid fa-clock-rotate-left me-2 text-secondary"></i>
                Earlier Admissions
            </h6>
        </div>
        <div class="table-responsive p-2">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Patient</th>
                        <th>Service</th>
                        <th>Assigned By</th>
                        <th class="text-end">Amount</th>
                        <th class="text-center">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($earlierAdmissions as $i => $assign)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $assign->patient->patient_first_name }} {{ $assign->patient->patient_last_name }}</div>
                                <small class="text-muted">P-{{ $assign->patient->patient_id }}</small>
                            </td>
                            <td>{{ $assign->service->service_name }}</td>
                            <td>
                                <span class="badge bg-info text-white">{{ $assign->doctor->doctor_name }}</span>
                            </td>
                            <td class="text-end">₱{{ number_format($assign->amount,2) }}</td>
                            <td class="text-center">
                                <a href="{{ route('laboratory.details', $assign) }}" class="btn btn-sm btn-outline-secondary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr class="text-center">
                            <td colspan="6" class="text-warning">No earlier admissions</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
