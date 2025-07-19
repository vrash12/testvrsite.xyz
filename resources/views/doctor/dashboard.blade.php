{{-- resources/views/doctor/dashboard.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="container-fluid">
    <h3 class="fw-bold text-primary mb-1">Order Entry</h3>
    <p class="text-muted mb-4">Create prescriptions and order services for patients</p>
@if($recentAdmissions->count())
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Recently admitted &mdash; {{ now()->format('M d') }}
        </div>

        <div class="card-body p-0">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Room</th>
                        <th class="text-end pe-4">Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAdmissions as $admit)
                        <tr>
                            <td>
                                {{ $admit->patient->patient_first_name }}
                                {{ $admit->patient->patient_last_name }}
                            </td>
                            <td>{{ $admit->room?->room_number ?? '—' }}</td>
                            <td class="text-end pe-4">
                                {{ $admit->admission_date->format('h:i A') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-2 align-items-center">
                <div class="col">
                    <input type="text" name="q" class="form-control" placeholder="Search by Name or MRN" value="{{ $q }}">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary">Search</button>
                </div>
            </form>
        </div>
    </div>

    <table class="table table-hover">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Sex</th>
                <th>Room</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td>{{ $patient->patient_first_name }} {{ $patient->patient_last_name }}</td>
                    <td>{{ $patient->sex ?? '—' }}</td>
                    <td>{{ $patient->admissionDetail?->room?->room_number ?? '—' }}</td>
                    <td class="text-center">
                        <a href="{{ route('doctor.order', $patient) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-file-alt me-1"></i>Details
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center text-muted">No patients found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        {{ $patients->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
