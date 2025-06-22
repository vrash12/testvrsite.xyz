{{-- resources/views/doctor/dashboard.blade.php --}}
@extends('layouts.doctor')

@section('content')
  <div class="container-fluid">
    <h2 class="fw-bold mb-3">Order Entry</h2>
    <p class="text-muted mb-4">Create Prescriptions and Order services for patients</p>

    {{-- Search Panel --}}
    <div class="card mb-4">
      <div class="card-body">
        <h5 class="card-title">Patient Selection</h5>
        <p class="text-muted mb-3">Select A Patient to Create Orders</p>
        <form method="GET" action="{{ route('doctor.dashboard') }}" class="row g-2">
          <div class="col">
            <input 
              type="text" 
              name="q" 
              class="form-control" 
              placeholder="Search by Name or MRN"
              value="{{ old('q', $q) }}"
            >
          </div>
          <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
          </div>
        </form>
      </div>
    </div>

    {{-- Results Table --}}
    <div class="card">
      <div class="card-body p-0">
        <table class="table mb-0">
          <thead class="table-light">
            <tr>
              <th>Name</th>
              <th>Age</th>
              <th>Sex</th>
              <th>Room</th>
              <th class="text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($patients as $p)
              <tr>
                <td>{{ $p->patient_first_name }} {{ $p->patient_last_name }}</td>
                <td>{{ $p->patient_birthday?->age ?? '–' }}</td>
                <td>{{ ucfirst($p->civil_status ?? '–') }}</td>
                <td>{{ $p->admissionDetail->room->room_number ?? '–' }}</td>
                <td class="text-center">
                  <a 
                    href="{{ route('doctor.patient.show', $p->patient_id) }}" 
                    class="btn btn-outline-secondary btn-sm"
                  >
                    <i class="fas fa-file-alt me-1"></i> Details
                  </a>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="text-center text-muted py-4">
                  No patients found.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
@endsection
