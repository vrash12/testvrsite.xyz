{{-- resources/views/admission/dashboard.blade.php --}}
@extends('layouts.admission')

@section('content')
<div class="container-fluid py-4">
  <h2 class="mb-4">Patient Admission Management</h2>
  <p class="text-muted mb-5">Manage patient admissions and doctor assignments</p>

  {{-- Metrics Row --}}
  <div class="row g-4 mb-5">
    @foreach ([
      ['label'=>'Total Patients',   'value'=>$totalPatients,  'icon'=>'fa-users'],
      ['label'=>'New Admissions',   'value'=>$newAdmissions,  'icon'=>'fa-user-plus'],
      ['label'=>'Available Beds',   'value'=>$availableBeds,  'icon'=>'fa-bed'],
    ] as $card)
    
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
          <div class="me-3">
            <i class="fas {{ $card['icon'] }} fa-2x text-primary"></i>
          </div>
          <div>
            <div class="text-muted">{{ $card['label'] }}</div>
            <h3 class="mb-0">{{ $card['value'] }}</h3>
          </div>
        </div>
      </div>
    </div>
    @endforeach
  </div>

  {{-- Recent Admissions Table --}}
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <div class="input-group w-50">
        <input type="text" class="form-control" placeholder="Search by Name or MRN">
        <span class="input-group-text"><i class="fas fa-search"></i></span>
      </div>
      <a href="{{ route('admission.patients.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> Admit new patient
      </a>
    </div>
    <div class="table-responsive">
      <table class="table table-hover mb-0">
        <thead class="table-light">
          <tr>
            <th>Date</th>
            <th>Patient ID</th>
            <th>Patient</th>
            <th>Room/Ward</th>
            <th>Assigned Doctor</th>
            <th>Diagnosis</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentAdmissions as $adm)
            <tr>
              <td>{{ $adm->admission_date->format('Y-m-d') }}</td>
              <td>{{ optional($adm->patient)->patient_id ?? '–' }}</td>
              <td>{{ optional($adm->patient)->patient_first_name }} {{ optional($adm->patient)->patient_last_name }}</td>
              <td>
  {{ optional($adm->room)->room_number ?? '–' }}
</td>

              <td>{{ $adm->doctor->doctor_name }}</td>
              <td>
  {{ optional(optional($adm->patient)->medicalDetail)->primary_reason ?? '—' }}
</td>

<td>
  @if($adm->patient)
    <a href="{{ route('admission.patients.show', $adm->patient) }}"
       class="btn btn-sm btn-outline-secondary">
      <i class="fas fa-file-alt"></i> Details
    </a>
  @else
    &mdash;
  @endif
</td>

            </tr>
          @empty
            <tr><td colspan="7" class="text-center">No recent admissions.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
