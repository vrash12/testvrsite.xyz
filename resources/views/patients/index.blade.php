{{--resources/views/patients/index.blade.php--}}

@extends('layouts.admission') {{-- assumes you have an admission‐layout with sidebar, etc. --}}

@section('content')

<div class="container-fluid h-100 border p-5 d-flex flex-column" style="background-color: #fafafa;">

     {{-- Heading --}}
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
          <input type="text" class="form-control" placeholder="MRN or Patient Name">
        </div>
        <div class="col-md-1"><a href="" class="btn btn-outline-primary w-100">Search</a></div>
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
          <td></td>
          <td></td>
          <td></td>
          <td>
          @php
            $status = strtolower($patient->status);
            $badgeClass = match($status) {
                'active' => 'bg-success',
                'completed' => 'bg-primary',
                'pending' => 'bg-warning',
                default => 'bg-secondary'
            };
          @endphp

          <span class="badge text-white {{ $badgeClass }}">
            {{ ucfirst($status) }}
          </span>
        </td>
          <td>
          <a href="{{ route('admission.patients.show', $patient) }}"
              class="btn btn-sm btn-primary">
           <i class="fa-solid fa-eye me-2"></i> View
          </a>

          </td>
        </tr>
      @endforeach
    </tbody>
    </table>

</div>

@endsection
