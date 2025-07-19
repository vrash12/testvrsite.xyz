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

        <div class="col-md-6">
          <label class="form-label">Medication
    @error('medication_id')
        <span class="text-danger small ms-1">{{ $message }}</span>
    @enderror
</label>
            <select class="form-select" name="medication_id" required>
                <option value="" disabled selected>Select medication</option>
                @foreach($medications as $med)
                    <option value="{{ $med->service_id }}">{{ $med->service_name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Dosage</label>
        <select name="dosage" class="form-select" required>
    <option disabled {{ old('dosage') ? '' : 'selected' }}>Select dosage</option>
    <option value="250 mg"  @selected(old('dosage')=='250 mg')>250 mg</option>
                <option>500 mg</option>
                <option>1 g</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Frequency</label>
            <select class="form-select" name="frequency" required>
                <option value="" disabled selected>Select frequency</option>
                <option>Once daily</option>
                <option>BID</option>
                <option>TID</option>
                <option>QID</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Route</label>
            <select class="form-select" name="route" required>
                <option value="" disabled selected>Select route</option>
                <option>PO</option>
                <option>IV</option>
                <option>IM</option>
                <option>SC</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Duration</label>
            <input type="number" min="1" class="form-control" name="duration" placeholder="Days" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Unit</label>
            <select class="form-select" name="duration_unit" required>
                <option value="days">Days</option>
                <option value="weeks">Weeks</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label">Special Instructions</label>
            <textarea class="form-control" rows="2" name="instructions"></textarea>
        </div>

        <div class="col-md-3">
            <label class="form-label">Quantity</label>
            <input type="number" min="1" class="form-control" name="quantity" value="1" required>
        </div>

        <div class="col-md-3">
            <label class="form-label">Refills</label>
            <input type="number" min="0" class="form-control" name="refills" value="0">
        </div>

        <div class="col-12">
            <label class="form-label d-block mb-1">Pharmacy Routing</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="routing" value="internal" checked>
                <label class="form-check-label">Internal Pharmacy</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="routing" value="external">
                <label class="form-check-label">External Pharmacy (e-prescribe)</label>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label d-block mb-1">Priority</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="priority" value="routine" checked>
                <label class="form-check-label">Routine</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="priority" value="urgent">
                <label class="form-check-label">Urgent</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="priority" value="stat">
                <label class="form-check-label">STAT</label>
            </div>
            <div class="form-check form-check-inline ms-3">
                <input class="form-check-input" type="checkbox" name="daw" value="1">
                <label class="form-check-label">Dispense as Written (DAW)</label>
            </div>
        </div>

        <div class="col-12 d-flex justify-content-end mt-4">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Medication Order</button>
        </div>
    </form>
</div>


    {{-- TAB 2 – LABORATORY --}}
<div class="tab-pane fade" id="laboratory" role="tabpanel">
    <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="lab">

        <div class="col-12">
            <label class="form-label">Select Laboratory Tests</label>
            <div class="row g-2">
                @foreach($labTests as $lab)
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="labs[]" value="{{ $lab->service_id }}" id="lab{{ $lab->service_id }}">
                            <label class="form-check-label" for="lab{{ $lab->service_id }}">{{ $lab->service_name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Diagnosis / Clinical Indication</label>
            <textarea class="form-control" name="diagnosis" rows="2"></textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">Collection Date</label>
            <input type="date" class="form-control" name="collection_date" value="{{ now()->toDateString() }}">
        </div>

        <div class="col-md-8">
            <label class="form-label d-block">Priority</label>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="priority" value="routine" checked><label class="form-check-label">Routine</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="priority" value="urgent"><label class="form-check-label">Urgent</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="priority" value="stat"><label class="form-check-label">STAT</label></div>
        </div>

        <div class="col-12">
            <label class="form-label">Additional Notes</label>
            <textarea class="form-control" name="notes" rows="2"></textarea>
        </div>

        <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="fasting" value="1" id="fasting"><label class="form-check-label" for="fasting">Fasting Required</label></div></div>

        <div class="col-12 d-flex justify-content-end mt-4">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-2">Cancel</a>
            <button class="btn btn-primary">Submit Laboratory Order</button>
        </div>
    </form>
</div>

    {{-- TAB 3 – IMAGING --}}
<div class="tab-pane fade" id="imaging" role="tabpanel">
    <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="imaging">

        <div class="col-12">
            <label class="form-label">Select Imaging Studies</label>
            <div class="row g-2">
                @foreach($imagingStudies as $img)
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="studies[]" value="{{ $img->service_id }}" id="img{{ $img->service_id }}">
                            <label class="form-check-label" for="img{{ $img->service_id }}">{{ $img->service_name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="col-12">
            <label class="form-label">Diagnosis / Clinical Indication</label>
            <textarea class="form-control" name="diagnosis" rows="2"></textarea>
        </div>

        <div class="col-md-4">
            <label class="form-label">Scheduled Date</label>
            <input type="date" class="form-control" name="scheduled_date" value="{{ now()->toDateString() }}">
        </div>

        <div class="col-md-8">
            <label class="form-label d-block">Priority</label>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="priority" value="routine" checked><label class="form-check-label">Routine</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="priority" value="urgent"><label class="form-check-label">Urgent</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="priority" value="stat"><label class="form-check-label">STAT</label></div>
        </div>

        <div class="col-12">
            <label class="form-label">Special Instructions</label>
            <textarea class="form-control" name="instructions" rows="2"></textarea>
        </div>

        <div class="col-12">
            <label class="form-label d-block">Transport</label>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="transport" value="ambulatory" checked><label class="form-check-label">Ambulatory</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="transport" value="wheelchair"><label class="form-check-label">Wheelchair</label></div>
            <div class="form-check form-check-inline"><input class="form-check-input" type="radio" name="transport" value="stretcher"><label class="form-check-label">Stretcher</label></div>
        </div>

        <div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="contrast" value="1" id="contrast"><label class="form-check-label" for="contrast">Use Contrast (if applicable)</label></div></div>

        <div class="col-12 d-flex justify-content-end mt-4">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-2">Cancel</a>
            <button class="btn btn-primary">Submit Imaging Order</button>
        </div>
    </form>
</div>

    {{-- TAB 4 – OTHER SERVICES --}}
    <div class="tab-pane fade" id="services" role="tabpanel">
    <form method="POST" action="{{ route('doctor.orders.store', $patient) }}" class="row gy-3">
        @csrf
        <input type="hidden" name="type" value="service">
        <div class="col-12">
            <label class="form-label">Select Services</label>
            <div class="row g-2">
                @foreach($otherServices as $svc)
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="services[]" value="{{ $svc->service_id }}" id="service{{ $svc->service_id }}">
                            <label class="form-check-label" for="service{{ $svc->service_id }}">{{ $svc->service_name }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-12">
            <label class="form-label">Diagnosis / Clinical Indication</label>
            <textarea class="form-control" name="diagnosis" rows="2"></textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label">Scheduled Date</label>
            <input type="date" class="form-control" name="scheduled_date" value="{{ now()->toDateString() }}">
        </div>
        <div class="col-md-6">
            <label class="form-label d-block">Priority</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="priority" value="routine" checked>
                <label class="form-check-label">Routine</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="priority" value="urgent">
                <label class="form-check-label">Urgent</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="priority" value="stat">
                <label class="form-check-label">STAT</label>
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label">Frequency</label>
            <select class="form-select" name="frequency">
                <option disabled selected>Select frequency</option>
                <option>Once daily</option>
                <option>BID</option>
                <option>TID</option>
                <option>QID</option>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label">Duration</label>
            <input type="number" class="form-control"
       name="duration"
       value="{{ old('duration',1) }}" min="1" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Unit</label>
            <select class="form-select" name="duration_unit">
                <option disabled selected>Unit</option>
                <option value="days">Days</option>
                <option value="weeks">Weeks</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label">Special Instructions</label>
            <textarea class="form-control" name="instructions" rows="2"></textarea>
        </div>
        <div class="col-12 d-flex justify-content-end">
            <a href="{{ route('doctor.dashboard') }}" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Submit Service Order</button>
        </div>
    </form>
</div>

  </div>
</div>
@endsection
