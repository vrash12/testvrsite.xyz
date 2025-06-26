{{-- resources/views/patient/dashboard.blade.php --}}
@extends('layouts.patients')

@section('content')
<div class="container-fluid">
  {{-- Greeting --}}
  <div class="mb-5">
    <h1>Hello, <span class="text-primary">{{ $user->username }}</span>!</h1>
    <p class="text-muted">
      Welcome to your patient portal! A hub for patients to access medical records and bills anytime, anywhere.
    </p>
  </div>

  {{-- Top Metrics --}}
  <div class="row g-4 mb-5">
    {{-- Patient ID --}}
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-user fa-2x text-secondary me-3"></i>
          <div>
            <div class="text-muted">PATIENT ID</div>
            <strong>{{ str_pad($user->patient_id, 8, '0', STR_PAD_LEFT) }}</strong>
          </div>
        </div>
      </div>
    </div>

    {{-- Room Number --}}
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-door-closed fa-2x text-secondary me-3"></i>
          <div>
            <div class="text-muted">ROOM NUMBER</div>
            <strong>{{ $admission->room_number ?? '—' }}</strong>
          </div>
        </div>
      </div>
    </div>

    {{-- Latest Admit Date --}}
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-ticket-alt fa-2x text-secondary me-3"></i>
          <div>
            <div class="text-muted">LATEST ADMIT DATE</div>
            <strong>
              {{ optional($admission)->admission_date?->format('m/d/y') ?? '—' }}
            </strong>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Amount Due --}}
  <div class="row g-4 mb-5">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body d-flex align-items-center">
          <i class="fas fa-peso-sign fa-2x text-secondary me-3"></i>
          <div>
            <div class="text-muted">AMOUNT DUE</div>
            <strong>₱{{ number_format($amountDue, 2) }}</strong>
          </div>
        </div>
      </div>
    </div>
    {{-- placeholders --}}
    <div class="col-md-3"><div class="card shadow-sm" style="height:100%"></div></div>
    <div class="col-md-3"><div class="card shadow-sm" style="height:100%"></div></div>
  </div>

  {{-- Detail Cards --}}
  <div class="row g-4">
    {{-- Prescriptions to Take --}}
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header">PRESCRIPTIONS TO TAKE</div>
        <ul class="list-group list-group-flush">
          @forelse($prescriptions as $p)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              {{ $p->service->service_name }}
              <span class="text-success">— pending dispensing</span>
            </li>
          @empty
            <li class="list-group-item text-center text-muted">No prescriptions</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Your Schedule Today --}}
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header">YOUR SCHEDULE TODAY</div>
        <ul class="list-group list-group-flush">
          @forelse($todaySchedule as $s)
            <li class="list-group-item d-flex justify-content-between">
              <div>
                <small class="text-muted">
                  {{ $s->service->department->department_name }}
                </small><br>
                <strong>{{ $s->service->service_name }}</strong><br>
                <small>
                  {{ \Carbon\Carbon::parse($s->datetime)->format('h:ia') }}
                </small>
              </div>
              <i class="fas fa-procedures fa-2x text-secondary"></i>
            </li>
          @empty
            <li class="list-group-item text-center text-muted">No appointments</li>
          @endforelse
        </ul>
      </div>
    </div>

    {{-- Assigned Doctors --}}
    <div class="col-md-4">
      <div class="card shadow-sm h-100">
        <div class="card-header">ASSIGNED DOCTORS</div>
        <ul class="list-group list-group-flush">
          @forelse($assignedDoctors as $doc)
            <li class="list-group-item">
              <strong>{{ $doc->doctor_name }}</strong><br>
              <small class="text-muted">{{ $doc->doctor_specialization }}</small>
            </li>
          @empty
            <li class="list-group-item text-center text-muted">No doctors assigned</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>
@endsection
