@extends('layouts.laboratory')

@section('content')
<div class="container-fluid h-100 border p-4 d-flex flex-column" style="background-color: #fafafa;">

    {{-- Header --}}
    <div>
        <h5 class="hdng">Laboratory Services Management</h5>
        <p>Welcome to Laboratory! Manage Lab Charges and Service Completion</p>
    </div>

    {{-- Stats / Metrics --}}
    <div class="row g-3 mb-3">
        <div class="col col-lg-4 col-md-6 col-sm-12">
            <div class="card border">
                <div class="card-body d-flex align-items-center">
                    <div class="me-4">
                        <i class="fa-solid fa-vials fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Services Completed</div>
                        <h5 class="mb-0">{{ $completedCount }}</h5> <!-- Show completed count -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-lg-4 col-md-6 col-sm-12">
            <div class="card border">
                <div class="card-body d-flex align-items-center">
                    <div class="me-4">
                        <i class="fa-solid fa-users fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Patients Served</div>
                        <h5 class="mb-0">{{ $patientsServed->count() }}</h5> <!-- Show number of unique patients served -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col col-lg-4 col-md-6 col-sm-12">
            <div class="card border">
                <div class="card-body d-flex align-items-center">
                    <div class="me-4">
                        <i class="fa-solid fa-hourglass-start fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <div class="text-muted small">Pending Orders</div>
                        <h5 class="mb-0">{{ $pendingCount }}</h5> <!-- Show pending count -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="card" style="height: 70vh;">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0 fw-semibold">
                    <i class="fa-solid fa-calendar-plus me-2 text-secondary"></i> Recent Activity
                </h5>
                <a href="{{ route('laboratory.history') }}" class="btn btn-outline-primary">
                    <i class="fa-solid fa-clock-rotate-left me-2"></i>View History
                </a>
            </div>

            <div class="table-responsive rounded shadow-sm p-1">
                <table class="table align-middle table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Description</th>
                            <th>Assigned By</th>
                            <th class="text-end">Amount</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $index => $activity)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $activity->patient->full_name ?? 'Unknown' }}</div>
                                    <small class="text-muted">ID: {{ $activity->patient->mrn ?? 'N/A' }}</small>
                                </td>
                                <td>{{ $activity->description ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $activity->doctor->full_name ?? 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="text-end">₱{{ number_format($activity->amount ?? 0, 2) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('laboratory.details', $activity->service) }}" class="btn btn-outline-secondary">
                                        <i class="fa-solid fa-file-circle-question me-2"></i>Details
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="6" class="bg-warning">
                                    <i class="fa-solid fa-puzzle-piece me-2"></i>No Recent Activity
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
