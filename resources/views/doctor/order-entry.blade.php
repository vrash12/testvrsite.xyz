{{-- resources/views/doctor/order-entry.blade.php --}}
@extends('layouts.doctor')

@section('content')
<div class="container-fluid">

  <h2 class="fw-bold mb-2">Order Entry</h2>
  <p class="text-muted mb-4">Create Prescriptions and Order services for patients</p>

  {{-- PATIENT CARD --}}
  <div class="card mb-4">
    <div class="card-body">
      <h6 class="fw-bold mb-2">Patient Selection</h6>
      <p class="text-muted small mb-3">Select a patient to create orders for</p>

      <div class="d-flex align-items-start">
        {{-- Avatar / placeholder --}}
        <div class="rounded-circle bg-light flex-shrink-0 me-3" style="width:48px;height:48px;"></div>

        {{-- Patient info --}}
        <div class="flex-grow-1">
          <div class="d-flex align-items-center mb-1">
            <strong class="me-2">{{ $patient->patient_first_name }} {{ $patient->patient_last_name }}</strong>
            <span class="text-muted small">| P-{{ $patient->patient_id }}</span>
          </div>

          <div class="small text-muted">
            {{ ucfirst($patient->civil_status) }}, 
            {{ $patient->patient_birthday?->age }} yrs • 
            DOB: {{ $patient->patient_birthday?->format('m/d/Y') }}
            <br>
            MRN: <span class="fw-semibold">{{ $patient->mrn ?? 'N/A' }}</span>
            <br>
            Allergies:
            @forelse($patient->medicalDetail?->allergies ?? [] as $allergy)
              <span class="badge bg-danger-subtle text-danger border border-danger me-1">{{ $allergy }}</span>
            @empty
              <span class="text-muted">None</span>
            @endforelse
          </div>
        </div>

        {{-- Change-patient button --}}
        <a 
          href="{{ route('doctor.dashboard') }}" 
          class="btn btn-outline-secondary btn-sm ms-3"
        >
          <i class="fa-solid fa-xmark me-1"></i> Change Patient
        </a>
      </div>
    </div>
  </div>

  {{-- NAV TABS --}}
  <ul class="nav nav-tabs nav-fill mb-3" id="orderTabs" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="med-tab" data-bs-toggle="tab" data-bs-target="#medications" type="button" role="tab">💊 Medications</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="lab-tab" data-bs-toggle="tab" data-bs-target="#laboratory" type="button" role="tab">🧪 Laboratory</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="img-tab" data-bs-toggle="tab" data-bs-target="#imaging" type="button" role="tab">🖼️ Imaging</button>
    </li>
    <li class="nav-item" role="presentation">
      <button class="nav-link" id="svc-tab" data-bs-toggle="tab" data-bs-target="#services" type="button" role="tab">🛠️ Other Services</button>
    </li>
  </ul>

  <div class="tab-content">

    {{-- TAB 1 – MEDICATIONS --}}
    <div class="tab-pane fade show active" id="medications" role="tabpanel">
      <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="medication">

        <div class="col-12">
          <label class="form-label">Select Medication</label>
          <select class="form-select" name="medication_id" required>
            <option value="" selected disabled>– Choose Medication –</option>
      @foreach($medications as $med)
  <option value="{{ $med->service_id }}">
    {{ $med->service_name }}
  </option>
@endforeach

          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Quantity</label>
          <input type="number" min="1" class="form-control" name="quantity" required>
        </div>

        <div class="col-md-4">
          <label class="form-label">Refills</label>
          <input type="number" min="0" class="form-control" name="refills" value="0">
        </div>

        <div class="col-12 text-end mt-3">
          <button type="submit" class="btn btn-primary">Assign</button>
        </div>
      </form>
    </div>

    {{-- TAB 2 – LABORATORY --}}
    <div class="tab-pane fade" id="laboratory" role="tabpanel">
      <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="lab">
        <div class="col-md-6">
          <label class="form-label">Lab Test</label>
          <select class="form-select" name="lab_id" required>
            <option value="" selected disabled>– Choose Lab Test –</option>
            @foreach($labTests as $lab)
              <option value="{{ $lab->id }}">{{ $lab->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 text-end">
          <button class="btn btn-primary">Assign</button>
        </div>
      </form>
    </div>

    {{-- TAB 3 – IMAGING --}}
    <div class="tab-pane fade" id="imaging" role="tabpanel">
      <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="imaging">
        <div class="col-md-6">
          <label class="form-label">Imaging Study</label>
          <select class="form-select" name="imaging_id" required>
            <option value="" selected disabled>– Choose Imaging Study –</option>
            @foreach($imagingStudies as $img)
              <option value="{{ $img->id }}">{{ $img->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 text-end">
          <button class="btn btn-primary">Assign</button>
        </div>
      </form>
    </div>

    {{-- TAB 4 – OTHER SERVICES --}}
    <div class="tab-pane fade" id="services" role="tabpanel">
      <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="service">
        <div class="col-md-6">
          <label class="form-label">Service</label>
          <select class="form-select" name="service_id" required>
            <option value="" selected disabled>– Choose Service –</option>
            @foreach($otherServices as $svc)
              <option value="{{ $svc->id }}">{{ $svc->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12 text-end">
          <button class="btn btn-primary">Assign</button>
        </div>
      </form>
    </div>

  </div>
</div>
@endsection
