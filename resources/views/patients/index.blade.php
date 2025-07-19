{{--resources/views/patients/index.blade.php--}}
@extends('layouts.admission')

@section('content')
<div class="container-fluid h-100 border p-5 d-flex flex-column" style="background-color: #fafafa;">
    <div class="row align-items-center justify-content-between mb-3">
        <div class="col">
            <h1 class="hdng mb-1">Patient Admission Management</h1>
            <p class="text-muted mb-2">Manage admitted patients and assign doctors</p>
        </div>
        <div class="col-auto">
            <a href="{{ route('admission.patients.create') }}" class="btn btn-outline-primary">
                <i class="fa-solid fa-user-plus me-2"></i>Admit new Patient
            </a>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-md-11">
            <input type="text" name="q" class="form-control" placeholder="MRN or Patient Name" value="{{ request('q') }}">
        </div>
        <div class="col-md-1">
            <a href="{{ url()->current().'?q='.request('q') }}" class="btn btn-outline-primary w-100">Search</a>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered table-hover mb-3">
        <thead>
            <tr>
                <th>MRN</th>
                <th>Name</th>
                <th>Room</th>
                <th>Admission Date</th>
                <th>Assigned Doctor</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
                <tr>
                    <td>{{ $patient->patient_id }}</td>
                    <td>{{ $patient->patient_first_name }} {{ $patient->patient_last_name }}</td>
                    <td>{{ $patient->admissionDetail?->room_number ?? '—' }}</td>
                    <td>{{ $patient->admissionDetail?->admission_date?->format('Y-m-d') ?? '—' }}</td>
                    <td>{{ $patient->admissionDetail?->doctor?->doctor_name ?? '—' }}</td>
                    <td>
                        @php
                            $badge = match(strtolower($patient->status)) {
                                'active'    => 'bg-success',
                                'completed' => 'bg-primary',
                                'pending'   => 'bg-warning',
                                default     => 'bg-secondary'
                            };
                        @endphp
                        <span class="badge text-white {{ $badge }}">
                            {{ ucfirst($patient->status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admission.patients.show', $patient) }}" class="btn btn-sm btn-primary">
                            <i class="fa-solid fa-eye me-2"></i>View
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end">
        {{ $patients->withQueryString()->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
